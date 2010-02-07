<?php

error_reporting(E_ALL);

define('NOOGES', true, false);
define('SMF', true, false);

define('NOOGES_RETURN_ALL', 1, false);
define('NOOGES_RETURN_SINGLE', 2, false);
define('NOOGES_RETURN_IGNORE', 3, false);

define('NOOGES_BOARD_ID', 2, false);


$zebra_list = array('zebra1', 'zebra2');




require_once 'forum/Settings.php';

$dsn = "mysql:host={$db_server};port=3306;dbname={$db_name}";
$pdo = new PDO($dsn, $db_user, $db_passwd);

function exs($k, $a) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return true;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return true;
	}
	
	return false;
}

function er($k, $a, $return = NULL) {
	if ( true === is_object($a) && true === isset($a->$k) ) {
		return $a->$k;
	}
	
	if ( true === is_array($a) && true === isset($a[$k]) ) {
		return $a[$k];
	}
	
	return $return;
}

function nooges_get_ipv4() {
	$ip = NULL;
	if ( true === isset($_SERVER) ) {
		if ( true === isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( true === isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = er('REMOTE_ADDR', $_SERVER);
		}
	}
	
	return $ip;
}

function nooges_get_latest_nooge() {
	$sql = "SELECT fm.* FROM `forum_topics` ft
		INNER JOIN `forum_messages` fm
			ON ft.id_topic = fm.id_topic
		WHERE ft.id_board = ?
			AND ft.locked = 1
		ORDER BY fm.poster_time ASC
		LIMIT 1";
	$nooge = nooges_query_single($sql, array(NOOGES_BOARD_ID));
	return $nooge;
}

function nooges_query_single($sql, $replace=array()) {
	return nooges_query($sql, $replace, NOOGES_RETURN_SINGLE);
}

function nooges_query_all($sql, $replace=array()) {
	return nooges_query($sql, $replace, NOOGES_RETURN_ALL);
}

function nooges_query($sql, $replace, $return_type=NOOGES_RETURN_SINGLE) {
	global $pdo;
	
	$statement = $pdo->prepare($sql);
	$statement->execute($replace);
	
	if ( 0 == $statement->rowCount() ) {
		return array();
	}
	
	switch ( $return_type ) {
		case NOOGES_RETURN_ALL: {
			$data = $statement->fetchAll(PDO::FETCH_ASSOC);
			break;
		}
		
		case NOOGES_RETURN_SINGLE: {
			$data = $statement->fetch(PDO::FETCH_ASSOC);
			break;
		}
		
		case NOOGES_RETURN_IGNORE: {
			$data = $statement;
			break;
		}
		
		default: {
			
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

function nooges_remove_bbc($string) {
	return preg_replace('/(\[.*\])(.*)(\[\/.*\])/iU', '\\2', $string);
}

function nooges_remove_html($string) {
	return preg_replace('/<.*>(.*)<\/.*>/iU', '\\1', $string);
}