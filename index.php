<?php

require_once 'global.php';

/* Get the forum listing. */
$sql = "SELECT * FROM `forum_boards` fb
	WHERE fb.id_parent = 0 ORDER BY fb.id_board ASC";
$board_list = nooges_query_all($sql);


/* Get the latest nooge. */
$nooge = nooges_get_latest_nooge();


/* Get all of the responses. */
$sql = "SELECT * FROM `nooges_response` nr
	INNER JOIN `forum_messages` fm
		ON nr.id_msg = fm.id_msg
	WHERE fm.id_topic = ?
		AND nr.parent_id = 0
	ORDER BY nr.date_create DESC
	LIMIT 50";
$response_list = nooges_query_all($sql, array($nooge['id_topic']));

/* Order both of the lists into the left and right response lists. */
$response_list_left = $response_list_right = array();
foreach ( $response_list as $response ) {
	switch ( $response['side'] ) {
		case 0: {
			$response_list_left[] = $response;
			break;
		}
		
		case 1: {
			$response_list_right[] = $response;
			break;
		}
	}
}


require_once 'view/index.phtml';

unset($pdo);
exit;