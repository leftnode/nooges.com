<?php

error_reporting(E_ALL);

define('NOOGES', true, false);
define('SMF', true, false);

define('NOOGES_RETURN_ALL', 1, false);
define('NOOGES_RETURN_SINGLE', 2, false);

require_once 'forum/Settings.php';

$dsn = "mysql:host={$db_server};port=3306;dbname={$db_name}";
$pdo = new PDO($dsn, $db_user, $db_passwd);

function nooges_query_single($sql, $replace=array()) {
	return nooges_query($sql, $replace, NOOGES_RETURN_SINGLE);
}

function nooges_query_all($sql, $replace=array()) {
	return nooges_query($sql, $replace, NOOGES_RETURN_ALL);
}

function nooges_query($sql, $replace, $return_type=NOOGES_RETURN_SINGLE) {
	global $pdo;
	
	$statement = $pdo->prepare($sql);
	$result = $statement->execute($replace);
	
	if ( 0 == $statement->rowCount() ) {
		return array();
	}
	
	switch ( $return_type ) {
		case NOOGES_RETURN_ALL: {
			$data = $statement->fetchAll(PDO::FETCH_ASSOC);
			break;
		}
		
		case NOOGES_RETURN_SINGLE:
		default: {
			$data = $statement->fetch(PDO::FETCH_ASSOC);
			break;
		}
	}
	
	return $data;
}

/**
 * Very naive bb code replace
 */
function nooges_bbc_parse($string) {
	$replace = array(
		'/\[b\](.*)\[\/b\]/i',
		'/\[i\](.*)\[\/i\]/i',
		'/\[u\](.*)\[\/u\]/i',
		'/\[s\](.*)\[\/s\]/i',
		'/\[size=(\d+pt)\](.*)\[\/size\]/i',
		'/\[color=([a-z]+)\](.*)\[\/color\]/i',
		'/\[img](.*)\[img]/i'
	);
	
	$replace_to = array(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',
		'<s>$1</s>',
		'<span style="font-size:$1">$2</span>',
		'<span style="color:$1">$2</span>',
		'<img src="$1" />'
	);
	
	return preg_replace($replace, $replace_to, $string);
}

function nooges_bbc_kill($string) {
	return preg_replace('/(\[.*\])(.*)(\[\/.*\])/iU', '\\2', $string);
}

function nooges_html_kill($string) {
	return preg_replace('/<.*>(.*)<\/.*>/iU', '\\1', $string);
}