<?php

require_once 'global.php';

/* Get the data from the posted variables. */
$message = er('message', $_POST);
$side = intval(er('side', $_POST, 0));
$parent_id = intval(er('parent_id', $_POST, 0));

/* Get the latest topic_id. */
$nooge = nooges_get_latest_nooge();
$topic_id = er('id_topic', $nooge, 0);

/* Prepare for insertion. Heh heh. */
if ( $topic_id > 0 ) {
	/**
	 * First insert into the forum_messages table to get that message ID
	 * and then insert into the nooges_response table.
	 */
	$forum_messages = array(
		$topic_id, NOOGES_BOARD_ID, time(), 0,
		1, 'Latest Nooge', 'Anonymous Bozo', 'anonymous@nooges.com',
		nooges_get_ipv4(), 0, $message, 'xx',
		1
	);
	
	$sql = "INSERT INTO `forum_messages` (
			id_topic, id_board, poster_time, id_member,
			id_msg_modified, subject, poster_name, poster_email,
			poster_ip, smileys_enabled, body, icon,
			approved
		) VALUES (
			?, ?, ?, ?,
			?, ?, ?, ?,
			?, ?, ?, ?,
			?
		)";
	nooges_query($sql, $forum_messages, NOOGES_RETURN_IGNORE);
	$message_id = $pdo->lastInsertId();
	
	if ( $message_id > 0 ) {
		/* Create the response. */
		$nooges_response = array(
			time(), $topic_id, $message_id,
			$parent_id, $side, 1
		);
		
		$sql = "INSERT INTO `nooges_response` (
				date_create, id_topic, id_msg,
				parent_id, side, status
			) VALUES ( 
				?, ?, ?,
				?, ?, ?
			)";
		nooges_query($sql, $nooges_response);
	}
}