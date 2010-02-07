<?php

require_once 'global.php';

$action = er('action', $_POST);


switch ( $action ) {
	
	case 'respond': {
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
					$parent_id, $side, 0,
					0, 1
				);
				
				$sql = "INSERT INTO `nooges_response` (
						date_create, id_topic, id_msg,
						parent_id, side, likes,
						dislikes, status
					) VALUES ( 
						?, ?, ?,
						?, ?, ?,
						?, ?
					)";
				nooges_query($sql, $nooges_response);
			}
		}
		
		
		break;
	}
	
	case 'like': {
		$response_id = intval(er('response_id', $_POST, 0));
		
		if ( $response_id > 0 ) {
			$response = nooges_get_response($response_id);
			$likes = $response['likes'];
			$likes++;
			
			$sql = "UPDATE `nooges_response` SET likes = ? WHERE nooges_response_id = ?"; 
			nooges_query($sql, array($likes, $response_id), NOOGES_RETURN_IGNORE);
			
			echo $likes;
		}
		
		break;
	}
	
	case 'dislike': {
		$response_id = intval(er('response_id', $_POST, 0));
		
		if ( $response_id > 0 ) {
			$response = nooges_get_response($response_id);
			$dislikes = $response['dislikes'];
			$dislikes++;
			
			$sql = "UPDATE `nooges_response` SET dislikes = ? WHERE nooges_response_id = ?"; 
			nooges_query($sql, array($dislikes, $response_id), NOOGES_RETURN_IGNORE);
			
			echo $dislikes;
		}
		
		break;
	}
	
	case 'load-responses': {
		
		
		break;
	}
}