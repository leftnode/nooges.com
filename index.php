<?php

require_once 'global.php';

/* Get the forum listing. */
$sql = "SELECT * FROM `forum_boards` fb
	WHERE fb.id_parent = 0 ORDER BY fb.id_board ASC";
$board_list = nooges_query_all($sql);


/* Get the latest nooge. */
$sql = "SELECT fm.* FROM `forum_topics` ft
	INNER JOIN `forum_messages` fm
		ON ft.id_topic = fm.id_topic
	WHERE ft.id_board = 2
		AND ft.locked = 1
	ORDER BY fm.poster_time DESC
	LIMIT 1";
$nooge = nooges_query_single($sql);


/* Get all of the responses. */
$sql = "SELECT * FROM `nooges_response` nr
	INNER JOIN `forum_messages` fm
		ON nr.id_msg = fm.id_msg
	WHERE fm.id_topic = ?
		AND nr.parent_id = 0
	ORDER BY nr.date_create DESC
	LIMIT 50";
$response_list = nooges_query_all($sql, array($nooge['id_topic']));


$response_list_left = $response_list_right = array();

require_once 'view/index.phtml';

unset($pdo);
exit;