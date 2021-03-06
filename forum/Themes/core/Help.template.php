<?php
// Version: 2.0 RC2; Help

function template_popup()
{
	global $context, $settings, $options, $txt;

	// Since this is a popup of its own we need to start the html, etc.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<meta name="robots" content="noindex" />
		<title>', $context['page_title'], '</title>
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css" />
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js"></script>
	</head>
	<body style="margin: 1ex;">
		<div class="popuptext">
			', $context['help_text'], '<br />
			<br />
			<div class="centertext"><a href="javascript:self.close();">', $txt['close_window'], '</a></div>
		</div>
	</body>
</html>';
}

function template_find_members()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>', $txt['find_members'], '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<meta name="robots" content="noindex" />
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css" />
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js"></script>
		<script type="text/javascript"><!-- // --><![CDATA[
			var membersAdded = [];
			function addMember(name)
			{
				var theTextBox = window.opener.document.getElementById("', $context['input_box_name'], '");

				if (name in membersAdded)
					return;

				// If we only accept one name don\'t remember what is there.
				if (\'', $context['delimiter'], '\' != \'null\')
					membersAdded[name] = true;

				if (theTextBox.value.length < 1 || \'', $context['delimiter'], '\' == \'null\')
					theTextBox.value = ', $context['quote_results'] ? '"\"" + name + "\""' : 'name', ';
				else
					theTextBox.value += "', $context['delimiter'], '" + ', $context['quote_results'] ? '"\"" + name + "\""' : 'name', ';

				window.focus();
			}
		// ]]></script>
	</head>
	<body>
		<form action="', $scripturl, '?action=findmember;', $context['session_var'], '=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<table border="0" width="100%" cellpadding="4" cellspacing="0" class="tborder">
				<tr class="titlebg">
					<td align="center" colspan="2">', $txt['find_members'], '</td>
				</tr>
				<tr class="windowbg">
					<td align="left" colspan="2">
						<strong>', $txt['find_username'], ':</strong><br />
						<input type="text" name="search" id="search" value="', isset($context['last_search']) ? $context['last_search'] : '', '" style="margin-top: 4px; width: 96%;" class="input_text" /><br />
					</td>
				</tr>
				<tr class="windowbg" valign="top">';

	// Only offer to search for buddies if we have some!
	if (!empty($context['show_buddies']))
		echo '
					<td align="left">
						<span class="smalltext"><label for="buddies"><input type="checkbox" class="input_check" name="buddies" id="buddies"', !empty($context['buddy_search']) ? ' checked="checked"' : '', ' /> ', $txt['find_buddies'], '</label></span>
					</td>
					<td align="right">';
	else
		echo '
					<td>';

	echo '
						<span class="smalltext"><em>', $txt['find_wildcards'], '</em></span>
					</td>
				</tr>
				<tr class="windowbg">
					<td align="right" colspan="2">
						<input type="submit" value="', $txt['search'], '" class="button_submit" />
						<input type="button" value="', $txt['find_close'], '" onclick="window.close();" class="button_submit" />
					</td>
				</tr>
			</table>

			<br />

			<table border="0" width="100%" cellpadding="4" cellspacing="0" class="tborder">
				<tr class="titlebg">
					<td align="center">', $txt['find_results'], '</td>
				</tr>';

	if (empty($context['results']))
		echo '
				<tr class="windowbg">
					<td align="center">', $txt['find_no_results'], '</td>
				</tr>';
	else
	{
		$alternate = true;
		foreach ($context['results'] as $result)
		{
			echo '
				<tr class="', $alternate ? 'windowbg2' : 'windowbg', '" valign="middle">
					<td align="left">
						<a href="', $result['href'], '" target="_blank" class="new_win"><img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt['view_profile'] . '" title="' . $txt['view_profile'] . '" border="0" /></a>
						<a href="javascript:void(0);" onclick="addMember(this.title); return false;" title="', $result['username'], '">', $result['name'], '</a>
					</td>
				</tr>';

			$alternate = !$alternate;
		}

		echo '
				<tr class="titlebg">
					<td align="left">', $txt['pages'], ': ', $context['page_index'], '</td>
				</tr>';
	}

	echo '
			</table>
			<input type="hidden" name="input" value="', $context['input_box_name'], '" />
			<input type="hidden" name="delim" value="', $context['delimiter'], '" />
			<input type="hidden" name="quote" value="', $context['quote_results'] ? '1' : '0', '" />
		</form>';

	if (empty($context['results']))
		echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			document.getElementById("search").focus();
		// ]]></script>';

	echo '
	</body>
</html>';
}

// Top half of the help template.
function template_manual_above()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<div id="helpmain" class="windowbg2">
			<h2 class="section">', $context['manual_area_data']['label'], '</h2>';
}

// Bottom half of the help template.
function template_manual_below()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		</div>';
}

// The introduction help page.
function template_manual_intro()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_you_have_arrived_part1'], '<a href="http://www.simplemachines.org/">', $txt['manual_index_you_have_arrived_link_site0'], '</a>', $txt['manual_index_you_have_arrived_part2'], '<a href="', $scripturl, '?action=help;area=board_index">', $txt['manual_index_you_have_arrived_link_site0_board'], '</a>', $txt['manual_index_you_have_arrived_part3'], '</p>
	<p>', sprintf($txt['manual_index_guest_permit_read_part1'], $scripturl . '?action=credits'), '<a href="', $scripturl, '?action=help;area=registration_screen">', $txt['manual_index_guest_permit_read_link_registering'], '</a>', $txt['manual_index_guest_permit_read_part2'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_index_main_menu'], '</a></li>
		<li><a href="', $scripturl, '?action=help;area=board_index">', $txt['manual_index_sec_board_index'], '</a></li>
		<li><a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_sec_msg_index'], '</a></li>
		<li><a href="', $scripturl, '?action=help;area=topic_view">', $txt['manual_index_sec_topic'], '</a></li>
	</ol>';
}

// The main menu page.
function template_manual_main_menu()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_suppossing_guest'], '</p>
	<ul>
		<li>', $txt['manual_index_home_desc_part1'], '<a href="', $scripturl, '?action=help;area=board_index">', $txt['manual_index_home_desc_link_board'], '</a>', $txt['manual_index_home_desc_part2'], '</li>
		<li>', $txt['manual_index_help_desc'], '</li>
		<li>', $txt['manual_index_search_desc_part1'], '<a href="', $scripturl, '?action=help;area=searching">', $txt['manual_index_search_desc_link_searching'], '</a>', $txt['manual_index_search_desc_part2'], '</li>
		<li>', $txt['manual_index_calendar_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#calendar">', $txt['manual_index_calendar_desc_link_posting_calendar'], '</a>', $txt['manual_index_calendar_desc_part2'], '</li>
		<li>', $txt['manual_index_login_desc_part1'], '<a href="', $scripturl, '?action=help;area=logging_in">', $txt['manual_index_login_desc_link_loginout'], '</a>', $txt['manual_index_login_desc_part2'], '</li>
		<li>', $txt['manual_index_register_desc_part1'], '<a href="', $scripturl, '?action=help;area=registration_screen">', $txt['manual_index_register_desc_link_registering'], '</a>', $txt['manual_index_register_desc_part2'], '</li>
	</ul>
	<p>', $txt['manual_index_once_registered'], '</p>
	<ul>
		<li>', $txt['manual_index_home_reg'], '</li>
		<li>', $txt['manual_index_help_reg'], '</li>
		<li>', $txt['manual_index_search_reg'], '</li>
		<li>', $txt['manual_index_profile_reg_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary">', $txt['manual_index_profile_reg_link_profile'], '</a>', $txt['manual_index_profile_reg_part2'], '</li>
		<li>', $txt['manual_index_calendar_reg'], '</li>
		<li>', $txt['manual_index_logout_reg_part1'], '<a href="', $scripturl, '?action=help;area=logging_in#logout">', $txt['manual_index_logout_reg_link_loginout_logout'], '</a>', $txt['manual_index_logout_reg_part2'], '</li>
	</ul>
	<p>', $txt['manual_index_forum_admins_note_presentation'], '</p>';
}

// The board index page.
function template_manual_board_index()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_sec_board_index_def'], '</p>
	<div class="help_sample">
		<table width="100%" cellpadding="3" cellspacing="0">
			<tr>
				<td valign="bottom"><span class="nav"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_index_forum_name'], '</a></strong></span></td>
			</tr>
		</table>
		<script type="text/javascript">//<![CDATA[
			var collapseExpand = false;
			function collapseExpandCategory()
			{
					document.getElementById("collapseArrow").src = smf_images_url + "/" + (collapseExpand ? "collapse.gif" : "expand.gif");
					document.getElementById("collapseArrow").alt = collapseExpand ? "-" : "+";
					document.getElementById("collapseCategory").style.display = collapseExpand ? "" : "none";
					collapseExpand = !collapseExpand;
			}
			function markBoardRead()
			{
					document.getElementById("board-new-or-not").src = smf_images_url + "/" + "off.gif";
					document.getElementById("board-new-or-not").alt = "', $txt['manual_index_no_new'], '";
			}
		//]]></script>
			<div class="tborder">
				<table border="0" width="100%" cellspacing="1" cellpadding="5">
					<tr>
						<td colspan="4" class="catbg" height="18"><a href="javascript:collapseExpandCategory();"><img src="', $settings['images_url'], '/collapse.gif" alt="-" border="0" id="collapseArrow" name="collapseArrow" /></a>&nbsp; <a href="javascript:collapseExpandCategory();" class="board">', $txt['manual_index_cat_name'], '</a></td>
					</tr>
					<tr id="collapseCategory" class="windowbg2">
						<td class="windowbg" width="6%" align="center" valign="top"><img src="', $settings['images_url'], '/on.gif" id="board-new-or-not" alt="', $txt['manual_index_new_posts'], '" name="board-new-or-not" /></td>
						<td align="left" class="windowbg"><strong><a href="', $scripturl, '?action=help;area=message_view" class="board">', $txt['manual_index_board_name'], '</a></strong><br />
						', $txt['manual_index_board_desc'], '</td>
						<td class="windowbg" valign="middle" align="center" style="width: 12ex;"><span class="smalltext">', $txt['manual_index_topics_posts'], '</span></td>
						<td valign="middle" width="22%" class="windowbg">', $txt['manual_index_date_time'], '</td>
					</tr>
				</table>
			</div>';

	// This changes dependant on theme really...
	$mark_read_button = array('markread' => array('text' => 'mark_as_read', 'image' => 'markread.gif', 'lang' => true, 'url' => 'javascript:markBoardRead();'));
	if (!empty($settings['use_tabs']))
	{
		echo '
		<div class="nav floatright">', template_button_strip($mark_read_button, 'top'), '</div>
		<table border="0" width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<td align="', !$context['right_to_left'] ? 'left' : 'right', '" class="smalltext">
					<img src="' . $settings['images_url'] . '/new_some.gif" alt="" align="middle" /> ', $txt['manual_index_new_posts'], '
					<img src="' . $settings['images_url'] . '/new_none.gif" alt="" align="middle" style="margin-left: 4ex;" /> ', $txt['manual_index_no_new'], '
				</td>
			</tr>
		</table>
		';
	}
	else
	{
		echo '
			<br />
			<div class="tborder" style="padding: 3px;">
				<table border="0" width="100%" cellspacing="0" cellpadding="5">
					<tr class="titlebg">
						<td align="left" class="smalltext">';

		// To back support the classic theme we do a little hack here...
		if (file_exists($settings['theme_dir'] . '/images/' . $context['user']['language'] . '/new_some.gif'))
			echo '
				<img src="', $settings['lang_images_url'], '/new_some.gif" alt="', $txt['manual_index_new_posts'], '" border="0" />&nbsp;&nbsp;<img src="', $settings['lang_images_url'], '/new_none.gif" alt="', $txt['manual_index_no_new'], '" border="0" />';
		else
			echo '
				<img src="', $settings['images_url'], '/new_some.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_new_posts'], '<img src="', $settings['images_url'], '/new_none.gif" alt="" align="middle" style="margin-left: 4ex;" />&nbsp; ', $txt['manual_index_no_new'];

		echo '
						</td>
						<td>
							', template_button_strip($mark_read_button, 'top', 'align="right" class="smalltext"'), '
						</td>
					</tr>
				</table>
			</div><br />';
	}

	echo '
	</div>
	<ul>
		<li>', $txt['manual_index_f_name'], '</li>
		<li>', $txt['manual_index_cat'], '</li>
		<li>', $txt['manual_index_b_name_part1'], '<a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_b_name_link_message'], '</a>', $txt['manual_index_b_name_part2'], '</li>
		<li>', $txt['manual_index_b_desc'], '</li>
		<li>', $txt['manual_index_n_no_n_posts'], '</li>
		<li>', $txt['manual_index_m_read'], '</li>
	</ul>';
}

// The message index page.
function template_manual_message_view()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_sec_msg_index_def'], '</p>
	<div class="help_sample">
		<script type="text/javascript">//<![CDATA[
			var currentSort = false;
			function sortLastPost()
			{
					document.getElementById("sort-arrow").src = smf_images_url + "/" + (currentSort ? "sort_down.gif" : "sort_up.gif");
					document.getElementById("sort-arrow").alt = "";
					currentSort = !currentSort;
			}
			function markMessageRead()
			{
					document.getElementById("message-new-or-not").style.display = "none";
			}
		//]]></script>
			<table width="100%" cellpadding="3" cellspacing="0">
				<tr>
					<td><span class="nav"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_index_forum_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_index_cat_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=message_index" class="nav">', $txt['manual_index_board_name'], '</a></strong></span></td>
				</tr>
			</table>';

	// Create the buttons we need here...
	$mindex_buttons = array(
		'markmread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => 'javascript:markMessageRead();'),
		'notify' => array('text' => 'manual_index_notify', 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['manual_index_ru_sure_notify'] . '\');"', 'url' => $scripturl . '?action=help;area=message_view'),
		'topic' => array('text' => 'manual_index_start_new', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#newtopic'),
		'poll' => array('text' => 'manual_index_new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#newpoll'),
	);

	if (!empty($settings['use_tabs']))
	{
		echo '
				<div class="clearfix margintop" id="modbuttons_top">
					<div class="margintop middletext floatleft"><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</div>
					<div class="nav floatright">', template_button_strip($mindex_buttons, 'bottom'), '</div>
				</div>';
	}
	else
	{
		echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
				<tr>
					<td align="left" class="catbg" width="100%" height="30">
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</td>
								<td>
									', template_button_strip($mindex_buttons, 'bottom', 'align="right"  style="white-space: nowrap; font-size: smaller;"'), '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	echo '
			<div class="tborder" id="messageindex">
				<table cellspacing="1" cellpadding="3" class="bordercolor boardsframe">
					<thead>
						<tr>
							<th class="catbg3 headerpadding" width="9%" colspan="2"></th>
							<th class="catbg3 headerpadding"><a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_subject'], '</a></th>
							<th class="catbg3 headerpadding" width="14%"><a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_started_by'], '</a></th>
							<th class="catbg3 headerpadding" width="4%" align="center"><a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_replies'], '</a></th>
							<th class="catbg3 headerpadding" width="4%" align="center"><a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_index_views'], '</a></th>
							<th class="catbg3 headerpadding" width="22%"><a href="javascript:sortLastPost();">', $txt['manual_index_last_post'], ' &nbsp; <img id="sort-arrow" src="', $settings['images_url'], '/sort_down.gif" alt="" border="0" name="sort-arrow" /></a></th>
						</tr>
					</thead>
					<tbody>
						<tr class="windowbg2">
							<td class="windowbg2" valign="middle" align="center" width="5%"><img src="', $settings['images_url'], '/topic/my_normal_poll.gif" alt="" /></td>
							<td class="windowbg2" valign="middle" align="center" width="4%"><img src="', $settings['images_url'], '/post/xx.gif" alt="" align="middle" /></td>
							<td class="windowbg" valign="middle"><a href="', $scripturl, '?action=help;area=topic_view" class="board">', $txt['manual_index_topic_subject'], '</a> <a href="', $scripturl, '?action=help;area=topic_view"><img id="message-new-or-not" src="', $settings['lang_images_url'], '/new.gif" border="0" alt="', $txt['manual_index_new'], '" name="message-new-or-not" /></a></td>
							<td class="windowbg2" valign="middle" width="14%"><a href="', $scripturl, '?action=help;area=profile_summary" class="board">', $txt['manual_index_topic_starter'], '</a></td>
							<td class="windowbg" valign="middle" width="4%" align="center">0</td>
							<td class="windowbg" valign="middle" width="4%" align="center">0</td>
							<td class="windowbg2" valign="middle" width="22%"><span class="smalltext">', $txt['manual_index_last_poster'], '</span></td>
						</tr>
					</tbody>
				</table>
			</div>';

	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="middletext floatleft">', $txt['manual_index_pages'], ': [<strong>1</strong>]</div>
			<div class="nav floatright">', template_button_strip($mindex_buttons, 'top'), '</div>';
	}
	else
	{
		echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-top: 1ex;">
				<tr>
					<td align="left" class="catbg" width="100%" height="30">
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</td>
								<td>
									', template_button_strip($mindex_buttons, 'bottom', 'align="right"  style="white-space: nowrap; font-size: smaller;"'), '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	echo '
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="smalltext" align="left" style="padding-top: 1ex;"><img src="', $settings['images_url'], '/topic/my_normal_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_normal_post'], '<br />
					<img src="', $settings['images_url'], '/topic/normal_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_normal_topic'], '<br />
					<img src="', $settings['images_url'], '/topic/hot_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_hot_post'], '<br />
					<img src="', $settings['images_url'], '/topic/veryhot_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_very_hot_post'], '</td>
					<td class="smalltext" align="left" valign="top" style="padding-top: 1ex;"><img src="', $settings['images_url'], '/topic/normal_post_locked.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_locked'], '<br />
					<img src="', $settings['images_url'], '/topic/normal_post_sticky.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_sticky'], '<br />
					<img src="', $settings['images_url'], '/topic/normal_poll.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_poll'], '</td>
					<td class="smalltext" align="right" valign="middle">
						<form action="', $scripturl, '?action=help;area=topic_view" method="get" accept-charset="', $context['character_set'], '">
							<label for="jumpto">', $txt['manual_index_jump_to'], '</label>: <select name="jumpto" id="jumpto" onchange="if (this.options[this.selectedIndex].value) window.location.href = smf_prepareScriptUrl(smf_scripturl) + \'action=help;area=topic_view\' + this.options[this.selectedIndex].value;">
								<option value="">
									', $txt['manual_index_destination'], ':
								</option>
								<option value="">
									-----------------------------
								</option>
								<option value="#board">
									', $txt['manual_index_cat_name'], '
								</option>
								<option value="">
									-----------------------------
								</option>
								<option value="#message">
									=&gt; ', $txt['manual_index_board_name'], '
								</option>
								<option value="#message">
									=&gt; ', $txt['manual_index_another_board'], '
								</option>
							</select>&nbsp; <input type="button" onclick="if (this.form.jumpto.options[this.form.jumpto.selectedIndex].value) window.location.href = smf_prepareScriptUrl(smf_scripturl) + \'action=help;area=topic_view\' + this.form.jumpto.options[this.form.jumpto.selectedIndex].value;" value="', $txt['manual_index_go'], '" class="button_submit" />
						</form>
					</td>
				</tr>
			</table><br />
	</div>
	<ul>
		<li>', $txt['manual_index_nav_tree'], '</li>
		<li>', $txt['manual_index_page_number'], '</li>
		<li>', $txt['manual_index_mark_read_button'], '</li>
		<li>', $txt['manual_index_notify_button'], '</li>
		<li>', $txt['manual_index_new_topic_poll_button_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics">', $txt['manual_index_new_topic_poll_button_link_posting'], '</a>', $txt['manual_index_new_topic_poll_button_part2'], '</li>
		<li>', $txt['manual_index_subject_replies_etc'], '</li>
		<li>', $txt['manual_index_topic_icons'], '</li>
		<li>', $txt['manual_index_post_icons'], '</li>
		<li>', $txt['manual_index_topic_subject_links_part1'], '<a href="', $scripturl, '?action=help;area=topic_view">', $txt['manual_index_topic_subject_links_link_topic'], '</a>', $txt['manual_index_topic_subject_links_part2'], '</li>
		<li>', $txt['manual_index_where_topic_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary">', $txt['manual_index_where_topic_link_profile'], '</a>', $txt['manual_index_where_topic_part2'], '</li>
		<li>', $txt['manual_index_jump_to_menu'], '</li>
	</ul>';
}

function template_manual_topic_view()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_ref_thread'], '</p>';

	// The buttons...
	$display_buttons = array(
		'reply' => array('text' => 'manual_index_reply', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#reply'),
		'notify' => array('text' => 'manual_index_notify', 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['manual_index_ru_sure_enable_notify'] . '\');"', 'url' => $scripturl . '?action=help;area=topic_view'),
		'markunread' => array('text' => 'manual_index_mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#topic'),
		'sendtopic' => array('text' => 'manual_index_send_topic', 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#topic'),
		'print' => array('text' => 'manual_index_print', 'image' => 'print.gif', 'lang' => true, 'url' => $scripturl . '?action=help;area=posting_topics#topic'),
	);

	echo '
	<div class="help_sample">
			<table width="100%" cellpadding="3" cellspacing="0">
				<tr>
					<td valign="bottom"><span class="nav"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_index_forum_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_index_cat_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=message_view" class="nav">', $txt['manual_index_board_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=topic_view" class="nav">', $txt['manual_index_topic_subject'], '</a></strong></span></td>
				</tr>
			</table>';

	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="margintop middletext floatleft"><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</div>
			<div class="nav floatright">', template_button_strip($display_buttons, 'bottom'), '</div>';
	}
	else
	{
		echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-bottom: 1ex;">
				<tr>
					<td align="left" class="catbg" width="100%" height="35">
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</td>
								<td>
									', template_button_strip($display_buttons, 'bottom', 'align="right" style="font-size: smaller;"'), '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="border-bottom: 0;">
				<tr class="catbg3">
					<td valign="middle" align="left" width="2%" style="padding-left: 6px;"><img src="', $settings['images_url'], '/topic/normal_post.gif" alt="" align="middle" /></td>
					<td width="13%">', $txt['manual_index_author'], '</td>
					<td valign="middle" align="left" width="85%" style="padding-left: 6px;">', $txt['manual_index_topic'], ': ', $txt['manual_index_topic_subject'], ' &nbsp;(', $txt['manual_index_read_x_times'], ')</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="bordercolor">
				<tr>
					<td style="padding: 1px;">
						<table cellpadding="3" cellspacing="0" border="0" width="100%">
							<tr>
								<td class="windowbg">
									<table width="100%" cellpadding="5" cellspacing="0" style="table-layout: fixed;">
										<tr>
											<td valign="top" width="15%" rowspan="2" style="overflow: hidden;"><strong><a href="', $scripturl, '?action=help;area=profile_summary" class="board" title="', $txt['manual_index_view_author_profile'], '">', $txt['manual_index_author'], '</a></strong><br />
											<span class="smalltext">', $txt['manual_index_member_group'], '<br />
											', $txt['manual_index_post_group'], '<br />
											<img src="', $settings['images_url'], '/star.gif" alt="*" border="0" /><br />
											', $txt['manual_index_post_count'], '<br />
											<br />
											<br />
											<br />
											<a href="', $scripturl, '?action=help;area=profile_summary" title="', $txt['manual_index_view_profile'], '"><img src="', $settings['images_url'], '/icons/profile_sm.gif" border="0" alt="', $txt['manual_index_view_profile'], '" /></a> <a href="mailto:author@some.address" title="', $txt['manual_index_email'], '"><img src="', $settings['images_url'], '/email_sm.gif" border="0" alt="', $txt['manual_index_email'], '" /></a> <a href="', $scripturl, '?action=help;area=sending_pms" title="', $txt['manual_index_personal_msg'], '"><img src="', $settings['images_url'], '/im_off.gif" border="0" alt="', $txt['manual_index_personal_msg'], '" /></a></span></td>
											<td valign="top" width="85%" height="100%">
												<table width="100%" border="0">
													<tr>
														<td width="20" align="left" valign="middle"><img src="', $settings['images_url'], '/post/xx.gif" alt="" border="0" /></td>
														<td align="left" valign="middle">
															<strong><a href="', $scripturl, '?action=help;area=topic_view" class="board">', $txt['manual_index_topic_subject'], '</a></strong>
															<div class="smalltext">
																&laquo; ', $txt['manual_index_post_date_time'], ' &raquo;
															</div>
														</td>
														<td align="right" valign="bottom" height="20" style="font-size: smaller;"><a href="', $scripturl, '?action=help;area=posting_topics#quote">', create_button('quote.gif', 'manual_index_reply_quote', 'quote', 'align="middle"'), '</a></td>
													</tr>
												</table>
												<hr width="100%" size="1" class="hrcolor" />
												<div style="overflow: auto; width: 100%;">
													', $txt['manual_index_topic_text'], '&nbsp;<img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/smiley.gif" border="0" alt="', $txt['manual_index_smiley'], '" />
												</div>
											</td>
										</tr>
										<tr>
											<td valign="bottom" class="smalltext">
												<table width="100%" border="0" style="table-layout: fixed;">
													<tr>
														<td align="right" valign="bottom" class="smalltext"><a href="', $scripturl, '?action=help;area=topic_view" class="board" style="font-size: x-small;">', $txt['manual_index_report_to_mod'], '</a>&nbsp;&nbsp; <img src="', $settings['images_url'], '/ip.gif" alt="" border="0" />&nbsp; ', $txt['manual_index_logged'], '</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><a id="lastPost"></a>';
	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="nav floatright">', template_button_strip($display_buttons, 'top'), '</div>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="middletext"><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</td>
				</tr>
			</table>';
	}
	else
	{
		echo '
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="tborder" style="margin-top: 1ex;">
				<tr>
					<td align="left" class="catbg" width="100%" height="30">
						<table cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td><strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</td>
								<td>
									', template_button_strip($display_buttons, 'top', 'align="right" style="font-size: smaller;"'), '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	echo '
			<div style="padding-top: 4px; padding-bottom: 4px;"></div>
			<div class="righttext floatright" style="margin-bottom: 1ex;">
				<form action="', $scripturl, '?action=help;area=topic_view" method="get" accept-charset="', $context['character_set'], '">
					<label for="jump2">', $txt['manual_index_jump_to'], '</label>: <select name="jump2" id="jump2" onchange="if (this.options[this.selectedIndex].value) window.location.href = smf_prepareScriptUrl(smf_scripturl) + \'action=help;area=topic_view\' + this.options[this.selectedIndex].value;">
						<option value="">
							', $txt['manual_index_destination'], ':
						</option>
						<option value="">
							-----------------------------
						</option>
						<option value="#board">
							', $txt['manual_index_cat_name'], '
						</option>
						<option value="">
							-----------------------------
						</option>
						<option value="#message">
							=&gt; ', $txt['manual_index_board_name'], '
						</option>
						<option value="#message">
							=&gt; ', $txt['manual_index_another_board'], '
						</option>
					</select>&nbsp; <input type="button" onclick="if (this.form.jump2.options[this.form.jump2.selectedIndex].value) window.location.href = smf_prepareScriptUrl(smf_scripturl) + \'action=help;area=topic_view\' + this.form.jump2.options[this.form.jump2.selectedIndex].value;" value="', $txt['manual_index_go'], '" class="button_submit" />
				</form>
			</div><br />
	</div>
	<ul>
		<li>', $txt['manual_index_navigation_tree'], '</li>
		<li>', $txt['manual_index_prev_next'], '</li>
		<li>', $txt['manual_index_page_no_link'], '</li>
		<li>', $txt['manual_index_reply_button_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#reply">', $txt['manual_index_reply_button_link_posting_reply'], '</a>', $txt['manual_index_reply_button_part2'], '</li>
		<li>', $txt['manual_index_notify_button_enables'], '</li>
		<li>', $txt['manual_index_mark_unread_button'], '</li>
		<li>', $txt['manual_index_send_topic_button'], '</li>
		<li>', $txt['manual_index_print_button'], '</li>
		<li>', $txt['manual_index_author_name_link_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary">', $txt['manual_index_author_name_link_link_profile'], '</a></li>
		<li>', $txt['manual_index_author_details'], '</li>
		<li>', $txt['manual_index_topic_subject_links_start'], '</li>
		<li>', $txt['manual_index_quote_button_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#quote">', $txt['manual_index_quote_button_link_posting_quote'], '</a>', $txt['manual_index_quote_button_part2'], '</li>
		<li>', $txt['manual_index_modify_delete_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#modify">', $txt['manual_index_modify_delete_link_posting_modify'], '</a>', $txt['manual_index_modify_delete_part2'], '</li>
		<li>', $txt['manual_index_report_to_moderator'], '</li>
		<li>', $txt['manual_index_logged_IP'], '</li>
		<li>', $txt['manual_index_jump_to_menu_provides'], '</li>
	</ul>';
}

// When and how to register page.
function template_manual_when_how_register()
{
	// TODO : Write this.
}

// The register help page.
function template_manual_registration_screen()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_registering_you_have_arrived_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary">', $txt['manual_registering_you_have_arrived_link_profile'], '</a>', $txt['manual_registering_you_have_arrived_part2'], '<a href="', $scripturl, '?action=help;area=sending_pms">', $txt['manual_registering_you_have_arrived_link_profile_pm'], '</a>', $txt['manual_registering_you_have_arrived_part3'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;area=registration_screen#how-to">', $txt['manual_registering_sec_register'], '</a></li>
		<li><a href="', $scripturl, '?action=help;area=registration_screen#screen">', $txt['manual_registering_sec_reg_screen'], '</a></li>
	</ol>
	<h2 class="section" id="how-to">', $txt['manual_registering_sec_register'], '</h2>
	<p>', $txt['manual_registering_register_desc'], '</p>
	<ul>
		<li>', $txt['manual_registering_select_register_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_registering_select_register_link_index_main'], '</a>', $txt['manual_registering_select_register_part2'], '</li>
		<li>', $txt['manual_registering_login_Scr_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_registering_login_Scr_link_index_main'], '</a>', $txt['manual_registering_login_Scr_part2'], '</li>
	</ul>
	<div class="help_sample">
		<div id="fatal_error">
			<h3 class="catbg"><span class="left"></span>
				', $txt['manual_registering_warning'], '
			</h3>
			<div class="windowbg2">
				<span class="topslice"><span></span></span>
				<div class="padding">', $txt['manual_registering_warning_desc_1'], '<br />
				', $txt['manual_registering_warning_desc_2'], '<a href="', $scripturl, '?action=help;area=registration_screen#screen" class="board">', $txt['manual_registering_warning_desc_3'], '</a>', $txt['manual_registering_warning_desc_4'], '</div>
				<span class="botslice"><span></span></span>
			</div>
		</div>
	</div>
	<h2 class="section" id="screen">', $txt['manual_registering_sec_reg_screen'], '</h2>
	<div class="help_sample">
		<form action="', $scripturl, '?action=help;area=registration_screen" method="post" accept-charset="', $context['character_set'], '">
			<h3 class="catbg"><span class="left"></span>
				', $txt['manual_registering_registration_form'], '
			</h3>
			<h4 class="titlebg"><span class="left"></span>
				', $txt['manual_registering_required_info'], '
			</h4>
			<div class="windowbg2">
				<span class="topslice"><span></span></span>
				<fieldset class="content">
					<dl class="register_form">
						<dt><strong><label for="smf_autov_username">', $txt['username'], ':</label></strong></dt>
						<dd>
							<input type="text" name="user" id="smf_autov_username" size="30" tabindex="', $context['tabindex']++, '" maxlength="25" value="', isset($context['username']) ? $context['username'] : '', '" class="input_text" />
							<span id="smf_autov_username_div" style="display: none;">
								<a id="smf_autov_username_link" href="#">
									<img id="smf_autov_username_img" src="', $settings['images_url'], '/icons/field_check.gif" alt="*" />
								</a>
							</span>
						</dd>
						<dt><strong><label for="smf_autov_reserve1">', $txt['email'], ':</label></strong></dt>
						<dd>
							<input type="text" name="email" id="smf_autov_reserve1" size="30" tabindex="', $context['tabindex']++, '" value="', isset($context['email']) ? $context['email'] : '', '" class="input_text" />
						</dd>
						<dt><strong><label for="allow_email">', $txt['allow_user_email'], ':</label></strong></dt>
						<dd>
							<input type="checkbox" name="allow_email" id="allow_email" tabindex="', $context['tabindex']++, '" class="input_check" />
						</dd>
					</dl>
					<dl class="register_form" id="password1_group">
						<dt><strong><label for="smf_autov_pwmain">', $txt['choose_pass'], ':</label></strong></dt>
						<dd>
							<input type="password" name="passwrd1" id="smf_autov_pwmain" size="30" tabindex="', $context['tabindex']++, '" class="input_password" />
							<span id="smf_autov_pwmain_div" style="display: none;">
								<img id="smf_autov_pwmain_img" src="', $settings['images_url'], '/icons/field_invalid.gif" alt="*" />
							</span>
						</dd>
					</dl>
					<dl class="register_form" id="password2_group">
						<dt><strong><label for="smf_autov_pwverify">', $txt['verify_pass'], ':</label></strong></dt>
						<dd>
							<input type="password" name="passwrd2" id="smf_autov_pwverify" size="30" tabindex="', $context['tabindex']++, '" class="input_password" />
							<span id="smf_autov_pwverify_div" style="display: none;">
								<img id="smf_autov_pwverify_img" src="', $settings['images_url'], '/icons/field_valid.gif" alt="*" />
							</span>
						</dd>
					</dl>
				</fieldset>
				<span class="botslice"><span></span></span>
			</div>
			<div id="confirm_buttons">
				<input type="submit" name="regSubmit" value="', $txt['register'], '" tabindex="', $context['tabindex']++, '" class="button_submit" />
			</div>
		</form>
	</div>
	<p>', $txt['manual_registering_reg_screen_requirements_part1'], '<a href="', $scripturl, '?action=help;area=logging_in#screen">', $txt['manual_registering_reg_screen_requirements_link_loginout_screen'], '</a>', $txt['manual_registering_reg_screen_requirements_part2'], '</p>
	<ul>
		<li>', $txt['manual_registering_email_activate'], '</li>
		<li>', $txt['manual_registering_admin_approve'], '</li>
	</ul>';
}

// Activating account page.
function template_manual_activating_account()
{
	// TODO : Write this.
}

// Logging in and out page.
function template_manual_logging_in_out()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<p>', $txt['manual_loginout_complete_reg_part1'], '<a href="', $scripturl, '?action=help;area=registration_screen">', $txt['manual_loginout_complete_reg_link_registering'], '</a>', $txt['manual_loginout_complete_reg_part2'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;area=logging_in#login">', $txt['manual_loginout_sec_login'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=logging_in#screen">', $txt['manual_loginout_login_screen'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=logging_in#quick">', $txt['manual_loginout_sub_quick_login'], '</a></li>
			</ol>
		</li>
		<li><a href="', $scripturl, '?action=help;area=logging_in#logout">', $txt['manual_loginout_logout'], '</a></li>
	</ol>
	<h2 class="section" id="login">', $txt['manual_loginout_sec_login'], '</h2>
	<p>', $txt['manual_loginout_login_desc'], '</p>
	<h3 class="section" id="screen">', $txt['manual_loginout_login_screen'], '</h3>
	<p>', $txt['manual_loginout_login_screen_desc_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_loginout_login_screen_desc_link_index_main'], '</a>', $txt['manual_loginout_login_screen_desc_part2'], '</p>
	<div class="help_sample">
		<form action="', $scripturl, '?action=help;area=logging_in" method="post" accept-charset="', $context['character_set'], '">
			<div class="tborder login">
				<h3 class="catbg"><span class="left"></span>
					<img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" class="icon" /> ', $txt['manual_loginout_login'], '
				</h3>
				<span class="upperframe"><span></span></span>
				<div class="roundframe">
					<dl>
						<dt>', $txt['manual_loginout_username'], ':</dt>
						<dd><input type="text" name="user" size="20" class="input_text" /></dd>
						<dt>', $txt['manual_loginout_password'], ':</dt>
						<dd><input type="password" name="passwrd" size="20" class="input_password" /></dd>
					</dl>
					<dl>
						<dt>', $txt['manual_loginout_how_long'], ':</dt>
						<dd><input type="text" name="cookielength" size="4" maxlength="4" class="input_text" /></dd>
						<dt>', $txt['manual_loginout_always'], ':</dt>
						<dd><input type="checkbox" name="cookieneverexp" class="input_check" /></dd>
					</dl>
					<p><input type="submit" value="', $txt['login'], '" class="button_submit" /></p>
					<div class="centertext smalltext"><a href="', $scripturl, '?action=help;area=password_reminders">', $txt['manual_loginout_forgot'], '?</a></div>
					<input type="hidden" name="hash_passwrd" value="" />
				</div>
				<span class="lowerframe"><span></span></span>
			</div>
		</form>
	</div>
	<p>', $txt['manual_loginout_login_screen_explanation'], '</p>
	<h3 class="section" id="quick">', $txt['manual_loginout_sub_quick_login'], '</h3>
	<p>', $txt['manual_loginout_although_many_forums_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_loginout_although_many_forums_link_index_main'], '</a>', $txt['manual_loginout_although_many_forums_part2'], '</p>
	<div class="help_sample">
		<form id="guest_form" action="', $scripturl, '?action=help;area=logging_in" method="post" accept-charset="', $context['character_set'], '">
			<input type="text" size="10" class="input_text" />
			<input type="password" size="10" class="input_text" />
			<select>
				<option>
					', $txt['manual_loginout_hour'], '
				</option>
				<option>
					', $txt['manual_loginout_day'], '
				</option>
				<option>
					', $txt['manual_loginout_week'], '
				</option>
				<option>
					', $txt['manual_loginout_mo'], '
				</option>
				<option selected="selected">
					', $txt['manual_loginout_forever'], '
				</option>
			</select>
			<input type="button" value="Login" class="button_submit" /><br />
			<div class="info">', $txt['manual_loginout_login_all'], '</div>
		</form>
	</div>
	<p>', $txt['manual_loginout_use_quick_login'], '</p>
	<h2 class="section" id="logout">', $txt['manual_loginout_logout'], '</h2>
	<p>', $txt['manual_loginout_logout_desc_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_loginout_logout_desc_link_index_main'], '</a>', $txt['manual_loginout_logout_desc_part2'], '</p>';
}

// Password reminders page.
function template_manual_password_reminders()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_loginout_reminder_desc_part1'], '<a href="', $scripturl, '?action=help;area=logging_in#screen">', $txt['manual_loginout_reminder_desc_link_screen'], '</a>', $txt['manual_loginout_reminder_desc_part2'], '</p>
	<div class="help_sample">
		<form action="', $scripturl, '?action=help;area=logging_in" method="post" accept-charset="', $context['character_set'], '">
			<div class="tborder login">
				<h3 class="catbg"><span class="left"></span>
					', $txt['manual_loginout_password_reminder'], '
				</h3>
				<span class="upperframe"><span></span></span>
				<div class="roundframe">
					<p class="smalltext centertext">', $txt['manual_loginout_q_explanation'], '</p>
					<dl>
						<dt>', $txt['manual_loginout_username_email'], ':</dt>
						<dd><input type="text" name="user" size="30" class="input_text" /></dd>
					</dl>
					<div class="centertext"><input type="submit" value="', $txt['manual_loginout_send'], '" class="button_submit" /></div>
				</div>
				<span class="lowerframe"><span></span></span>
			</div>
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		</form>
	</div>
	<p>', $txt['manual_loginout_reminder_explanation'], '</p>';
}

// Profile info page.
function template_manual_profile_info()
{
	// TODO : Write this.
}

// Profile summary page.
function template_manual_profile_info_summary()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_profile_profile_screen'], '</p>
	<p>', $txt['manual_profile_edit_profile_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_profile_edit_profile_link_index_main'], '</a>', $txt['manual_profile_edit_profile_part2'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;area=profile_summary#all">', $txt['manual_profile_available_to_all'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=profile_summary#info-all">', $txt['manual_profile_profile_info'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;area=modifying_profiles#owners">', $txt['manual_profile_sec_normal'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=modifying_profiles#edit-owners">', $txt['manual_profile_modify_profile'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=modifying_profiles#actions-owners">', $txt['manual_profile_actions'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;area=modifying_profiles#admins">', $txt['manual_profile_sec_settings'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=modifying_profiles#info-admins">', $txt['manual_profile_profile_info'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=modifying_profiles#edit-admins">', $txt['manual_profile_modify_profile'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=modifying_profiles#actions-admins">', $txt['manual_profile_actions'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="section" id="all">', $txt['manual_profile_available_to_all'], '</h2>
	<h3 class="section" id="info-all">', $txt['manual_profile_profile_info'], '</h3>
	<div class="help_sample">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-top: 1ex;">
				<tr>
					<td width="100%" valign="top">
						<table border="0" cellpadding="4" cellspacing="1" align="center" class="bordercolor">
							<tr class="titlebg">
								<td align="left" width="420" height="26"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp; ', $txt['manual_profile_username'], ':&nbsp;', $txt['manual_profile_login_name'], '</td>
								<td align="center" width="150">', $txt['manual_profile_pic_text'], '</td>
							</tr>
							<tr>
								<td class="windowbg" width="420" align="left">
									<table border="0" cellspacing="0" cellpadding="2" width="100%">
										<tr>
											<td><strong>', $txt['manual_profile_name'], ':</strong></td>
											<td>', $txt['manual_profile_screen_name'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_posts'], ':</strong></td>
											<td>', $txt['manual_profile_member_posts'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_position'], ':</strong></td>
											<td>', $txt['manual_profile_membergroup'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_date_reg'], ':</strong></td>
											<td>', $txt['manual_profile_date_time_reg'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_last_active'], ':</strong></td>
											<td>', $txt['manual_profile_date_time_active'], '</td>
										</tr>
										<tr>
											<td colspan="2">
												<hr size="1" width="100%" class="hrcolor" />
											</td>
										</tr>
										<tr>
											<td><strong>', $txt['icq'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['aim'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['msn'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['yim'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_email'], ':</strong></td>
											<td><a href="mailto:', $txt['manual_profile_email_user'], '" class="board">', $txt['manual_profile_email_user'], '</a></td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_website'], ':</strong></td>
											<td><a href="http://www.simplemachines.org/" target="_blank" class="new_win"></a></td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_status'], ':</strong></td>
											<td><em><a href="', $scripturl, '?action=help;area=sending_pms" title="', $txt['manual_profile_pm'], ' (', $txt['manual_profile_online'], ')  "><img src="', $settings['images_url'], '/useron.gif" border="0" align="middle" alt="', $txt['manual_profile_online'], '" /></a> <span class="smalltext">', $txt['manual_profile_online'], '</span></em></td>
										</tr>
										<tr>
											<td colspan="2">
												<hr size="1" width="100%" class="hrcolor" />
											</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_gender'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_age'], ':</strong></td>
											<td>', $txt['manual_profile_n_a'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_location'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_local_time'], ':</strong></td>
											<td>', $txt['manual_profile_current_date_time'], '</td>
										</tr>
										<tr>
											<td><strong>', $txt['manual_profile_language'], ':</strong></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="2">
												<hr size="1" width="100%" class="hrcolor" />
											</td>
										</tr>
										<tr>
											<td colspan="2" height="25">
												<table border="0">
													<tr>
														<td><strong>', $txt['manual_profile_sig'], ':</strong></td>
													</tr>
													<tr>
														<td colspan="2"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td class="windowbg" valign="middle" align="center" width="150"><br />
								<br /></td>
							</tr>
							<tr class="titlebg">
								<td colspan="2" align="left">', $txt['manual_profile_other_info'], ':</td>
							</tr>
							<tr>
								<td class="windowbg2" colspan="2" align="left"><a href="', $scripturl, '?action=help;area=profile_summary#all" class="board">', $txt['manual_profile_send_pm'], '</a><br />
								<br />
								<a href="', $scripturl, '?action=help;area=profile_summary#all" class="board">', $txt['manual_profile_show_member_posts'], '</a><br />
								<a href="', $scripturl, '?action=help;area=profile_summary#all" class="board">', $txt['manual_profile_show_member_stats'], '</a><br />
								<br /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table><br />
	</div>
	<ul>
		<li>', $txt['manual_profile_summary_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary#owners">', $txt['manual_profile_summary_link_owners'], '</a>', $txt['manual_profile_summary_part2'], '</li>
		<li>', $txt['manual_profile_hide_email'], '</li>
		<li>', $txt['manual_profile_empty_part1'], '<a href="', $scripturl, '?action=help;area=modifying_profiles">', $txt['manual_profile_empty_link_owners'], '</a>', $txt['manual_profile_empty_part2'], '</li>
		<li>', $txt['manual_profile_send_member_pm_part1'], '<a href="', $scripturl, '?action=help;area=sending_pms">', $txt['manual_profile_send_member_pm_link_pm'], '</a>', $txt['manual_profile_send_member_pm_part2'], '</li>
		<li>', $txt['manual_profile_show_last_posts'], '</li>
		<li>', $txt['manual_profile_show_member_stats2'], '</li>
	</ul>';
}

// Profile show posts page.
function template_manual_profile_info_posts()
{
	// TODO : Write this.
}

// Profile show stats page.
function template_manual_profile_info_stats()
{
	// TODO : Write this.
}

// Modify profile page.
function template_manual_modify_profile()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_profile_normal_desc'], '</p>
	<ul>
		<li>', $txt['manual_profile_account_related'], '</li>
		<li>', $txt['manual_profile_forum_profile_info'], '</li>
		<li>', $txt['manual_profile_look_layout'], '</li>
	</ul>
		<div class="help_sample">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-top: 1ex;">
				<tr>
					<td width="180" valign="top">
						<table border="0" cellpadding="4" cellspacing="1" class="bordercolor" width="170">
							<tr>
								<td class="catbg">', $txt['manual_profile_profile_info2'], '</td>
							</tr>
							<tr class="windowbg2">
								<td class="windowbg"><a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_summary2'], '</a><br />
								<a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_show_stats'], '</a><br />
								<a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_show_posts'], '</a><br />
								<br /></td>
							</tr>
							<tr>
								<td class="catbg">', $txt['manual_profile_modify_own_profile'], '</td>
							</tr>
							<tr class="windowbg2">
								<td class="windowbg"><a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_acct_settings'], '</a><br />
								<a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_forum_profile'], '</a><br />
								<strong><a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_look_and_layout'], '</a></strong><br />
								<a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_notify_email'], '</a><br />
								<a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_pm_options1'], '</a><br />
								<br /></td>
							</tr>
							<tr>
								<td class="catbg">', $txt['manual_profile_actions'], '</td>
							</tr>
							<tr class="windowbg2">
								<td class="windowbg"><a href="', $scripturl, '?action=help;area=modifying_profiles" style="font-size: x-small;" class="board">', $txt['manual_profile_delete_account'], '</a><br />
								<br /></td>
							</tr>
						</table>
					</td>
					<td width="100%" valign="top">
						<form action="', $scripturl, '?action=help;area=profile_summary" method="post" accept-charset="', $context['character_set'], '">
							<table border="0" width="85%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">
								<tr class="titlebg">
									<td height="26" align="left">&nbsp;<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" border="0" align="top" />&nbsp; ', $txt['manual_profile_edit_profile1'], '</td>
								</tr>
								<tr>
									<td class="windowbg" height="25" align="left"><span class="smalltext"><br />
									', $txt['manual_profile_look_layout_explanation'], '<br />
									<br /></span></td>
								</tr>
								<tr>
									<td class="windowbg2" align="left">
										<table border="0" width="100%" cellpadding="3">
											<tr>
												<td colspan="2" width="40%"><strong>', $txt['manual_profile_current_theme'], ':</strong>&nbsp;', $txt['manual_profile_board_default'], '&nbsp;<a href="', $scripturl, '?action=help;area=modifying_profiles" class="board">(', $txt['manual_profile_change'], ')</a></td>
											</tr>
											<tr>
												<td colspan="2">
													<hr width="100%" size="1" class="hrcolor" />
												</td>
											</tr>
											<tr>
												<td width="40%"><strong>', $txt['manual_profile_time_format'], ':</strong><br />
												<a href="', $scripturl, '/index.php?action=helpadmin;help=time_format" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['manual_profile_help'], '" border="0" align="left" style="padding-right: 1ex;" /></a> <span class="smalltext">', $txt['manual_profile_caption_date'], '</span></td>
												<td><select style="margin-bottom: 4px;">
													<option selected="selected">
														(', $txt['manual_profile_date_option_select'], ')
													</option>
													<option>
														', $txt['manual_profile_date_option_1'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_2'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_3'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_4'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_5'], '
													</option>
												</select><br />
												<input type="text" value="" size="30" class="input_text" /></td>
											</tr>
											<tr>
												<td width="40%">
													<strong>', $txt['manual_profile_time_offset'], ':</strong>
													<div class="smalltext">
														', $txt['manual_profile_offset_hours'], '
													</div>
												</td>
												<td class="smalltext"><input type="text" size="5" maxlength="5" value="0" class="input_text" /><br />
												<em>(', $txt['manual_profile_forum_time'], ')</em></td>
											</tr>
											<tr>
												<td colspan="2">
													<hr width="100%" size="1" class="hrcolor" />
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<br />
													<table width="100%" cellspacing="0" cellpadding="3">
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_board_descriptions'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_show_child'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_no_ava'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_no_sig'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_return_to_topic'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_recent_posts'], '</td>
														</tr>
														<tr>
															<td width="28"><input type="checkbox" class="input_check" /></td>
															<td>', $txt['manual_profile_recent_pms'], '</td>
														</tr>
														<tr>
															<td colspan="2">', $txt['manual_profile_first_day_week'], '
															<select>
																<option selected="selected">
																	', $txt['manual_profile_sun'], '
																</option>
																<option>
																	', $txt['manual_profile_mon'], '
																</option>
															</select></td>
														</tr>
														<tr>
															<td colspan="2">', $txt['manual_profile_quick_reply'], ': <select>
																<option selected="selected">
																	', $txt['manual_profile_not_at_all'], '
																</option>
																<option>
																	', $txt['manual_profile_off_default'], '
																</option>
																<option>
																	', $txt['manual_profile_on_default'], '
																</option>
															</select></td>
														</tr>
														<tr>
															<td colspan="2">', $txt['manual_profile_quick_mod'], '&nbsp; <select>
																<option selected="selected">
																	', $txt['manual_profile_no_quick_mod'], '.
																</option>
																<option>
																	', $txt['manual_profile_check_quick_mod'], '.
																</option>
																<option>
																	', $txt['manual_profile_icon_quick_mod'], '.
																</option>
															</select></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<hr width="100%" size="1" class="hrcolor" />
												</td>
											</tr>
											<tr>
												<td align="right" colspan="2"><input type="button" value="', $txt['manual_profile_change_profile'], '" class="button_submit" /></td>
											</tr>
										</table><br />
									</td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table><br />
	</div>
	<ul>
		<li>', $txt['manual_profile_notify_email_prefs'], '</li>
		<li>', $txt['manual_profile_pm_options_part1'], '<a href="', $scripturl, '?action=help;area=sending_pms">', $txt['manual_profile_pm_options_link_pm'], '</a>', $txt['manual_profile_pm_options_part2'], '</li>
	</ul>
	<h3 class="section" id="actions-owners">', $txt['manual_profile_sub_actions'], '</h3>
	<ul>
		<li>', $txt['manual_profile_confirm_delete_acct'], '</li>
	</ul>
	<h2 class="section" id="admins">', $txt['manual_profile_sec_settings'], '</h2>
	<p>', $txt['manual_profile_settings_desc'], '</p>
		<div>
		<div style="width: 180px; float: left; border: none;">
			<table border="0" cellpadding="4" cellspacing="1" class="bordercolor" width="170">
				<tr>
					<td class="catbg">', $txt['manual_profile_profile_info'], '</td>
				</tr>
				<tr class="windowbg2">
					<td class="windowbg"><strong><a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_summary2'], '</a></strong><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_stats'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_posts'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_track_user'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_track_ip'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_permissions'], '</a><br />
					<br /></td>
				</tr>
				<tr>
					<td class="catbg">', $txt['manual_profile_sub_modify_profile'], '</td>
				</tr>
				<tr class="windowbg2">
					<td class="windowbg"><a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_acct_settings'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_forum_profile'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_look_and_layout'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_notify_email'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_pm_options1'], '</a><br />
					<br /></td>
				</tr>
				<tr>
					<td class="catbg">', $txt['manual_profile_actions'], '</td>
				</tr>
				<tr class="windowbg2">
					<td class="windowbg"><a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_ban_user'], '</a><br />
					<a href="', $scripturl, '?action=help;area=modifying_profiles#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_delete_account'], '</a><br />
					<br /></td>
				</tr>
			</table>
		</div><br />
		<div style="margin: -1.8em 20px 0 200px;">
			<h3 class="section" id="info-admins">', $txt['manual_profile_sub_profile_info'], '</h3>
			<ul>
				<li>', $txt['manual_profile_sub_track_user'], '</li>
				<li>', $txt['manual_profile_sub_track_ip'], '</li>
				<li>', $txt['manual_profile_sub_show_permissions'], '</li>
			</ul>
			<h3 class="section" id="edit-admins">', $txt['manual_profile_sub_modify_profile'], '</h3>
			<ul>
				<li>', $txt['manual_profile_sub_acct_settings'], '</li>
				<li>', $txt['manual_profile_sub_forum_profile_info'], '</li>
			</ul>
			<h3 class="section" id="actions-admins">', $txt['manual_profile_sub_actions2'], '</h3>
			<ul>
				<li>', $txt['manual_profile_sub_ban_user'], '</li>
				<li>', $txt['manual_profile_sub_delete_acct'], '</li>
			</ul>
		</div>
	</div><br clear="all" />';

}

// Modify profile account settings page.
function template_manual_modify_profile_settings()
{
	// TODO : Write this.
}

// Modify forum profile page.
function template_manual_modify_profile_forum()
{
	// TODO : Write this.
}

// Modify profile look and layout page.
function template_manual_modify_profile_look()
{
	// TODO : Write this.
}

// Modify profile notifications page.
function template_manual_modify_profile_notify()
{
	// TODO : Write this.
}

// Modify profile personal messages page.
function template_manual_modify_profile_pm()
{
	// TODO : Write this.
}

// Modify profile edit buddies page.
function template_manual_modify_profile_buddies()
{
	// TODO : Write this.
}

// Modify profile group membership page.
function template_manual_modify_profile_groups()
{
	// TODO : Write this.
}

// Posting screen page.
function template_manual_posting_screen()
{
	// TODO : Write this.
}

// Posting topics page.
function template_manual_posting_topics()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_posting_forum_about_part1'], '<a href="', $scripturl, '?action=help;area=bbcode">', $txt['manual_posting_forum_about_link_bbcref'], '</a>', $txt['manual_posting_forum_about_part2'], '<a href="', $scripturl, '?action=help;area=smileys">', $txt['manual_posting_forum_about_link_bbcref_smileysref'], '</a>', $txt['manual_posting_forum_about_part3'], '</p>
	<p>', $txt['manual_posting_please_note'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;area=posting_topics#basics">', $txt['manual_posting_sec_posting_basics'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=posting_topics#newtopic">', $txt['manual_posting_starting_topic'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#newpoll">', $txt['manual_posting_start_poll'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#calendar">', $txt['manual_posting_post_event'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#reply">', $txt['manual_posting_replying'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#quote">', $txt['manual_posting_quote_post'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#modify">', $txt['manual_posting_modify_delete'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;area=posting_topics#standard">', $txt['manual_posting_sec_posting_options'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=posting_topics#messageicon">', $txt['manual_posting_sub_message_icon'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#bbc">', $txt['manual_posting_sub_bbc'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#smileys">', $txt['manual_posting_sub_smileys'], '</a></li>
			</ol>
		</li>
		<li><a href="', $scripturl, '?action=help;area=posting_topics#tags">', $txt['manual_posting_sec_tags'], '</a></li>
		<li>
			<a href="', $scripturl, '?action=help;area=posting_topics#additional">', $txt['manual_posting_sec_additional_options'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=posting_topics#notify">', $txt['manual_posting_notify'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#return">', $txt['manual_posting_return'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#nosmileys">', $txt['manual_posting_no_smiley'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=posting_topics#attachments">', $txt['manual_posting_sub_attach'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="section" id="basics">', $txt['manual_posting_sec_posting_basics'], '</h2>
	<h3 class="section" id="newtopic">', $txt['manual_posting_starting_topic'], '</h3>
	<p>', $txt['manual_posting_starting_topic_desc_part1'], '<a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_posting_starting_topic_desc_link_index_message'], '</a>', $txt['manual_posting_starting_topic_desc_part2'], '<a href="', $scripturl, '?action=help;area=posting_topics#standard">', $txt['manual_posting_starting_topic_desc_link_index_message_standard'], '</a>', $txt['manual_posting_starting_topic_desc_part3'], '</p>
	<div class="help_sample">
			<form action="', $scripturl, '?action=help;area=posting_topics" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
				<table width="100%" align="center" cellpadding="0" cellspacing="3">
					<tr>
						<td valign="bottom" colspan="2"><span class="nav"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_posting_forum_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_posting_cat_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=message_index" class="nav">', $txt['manual_posting_board_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><em>', $txt['manual_posting_start_topic'], '</em></strong></span></td>
					</tr>
				</table>
				<table border="0" width="100%" align="center" cellspacing="1" cellpadding="3" class="bordercolor">
					<tr class="titlebg">
						<td>', $txt['manual_posting_start_topic'], '</td>
					</tr>
					<tr>
						<td class="windowbg">
							<table border="0" cellpadding="3" width="100%">
								<tr class="windowbg">
									<td colspan="2" align="center"><a href="', $scripturl, '?action=help;area=posting_topics#standard">', $txt['manual_posting_std_options'], '&nbsp;', $txt['manual_posting_omit_clarity'], '</a></td>
								</tr>
								<tr>
									<td align="right"><strong>', $txt['manual_posting_subject'], ':</strong></td>
									<td><input type="text" name="subject" size="80" maxlength="80" tabindex="1" class="input_text" /></td>
								</tr>
								<tr>
									<td valign="top" align="right"></td>
									<td>
									<textarea class="editor" name="message" rows="12" cols="60" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);" tabindex="2">
</textarea></td>
								</tr>
								<tr class="windowbg">
									<td colspan="2" align="center"><a href="', $scripturl, '?action=help;area=posting_topics#additional">', $txt['manual_posting_sec_additional_options'], '&nbsp;', $txt['manual_posting_omit_clarity'], '</a></td>
								</tr>
								<tr>
									<td align="center" colspan="2"><span class="smalltext"><br />
									', $context['browser']['is_firefox'] ? $txt['manual_posting_shortcuts_firefox'] : $txt['manual_posting_shortcuts'], '</span><br />
									<input type="button" accesskey="s" tabindex="3" value="', $txt['manual_posting_posts'], '" class="button_submit" /> <input type="button" accesskey="p" tabindex="4" value="', $txt['manual_posting_preview'], '" class="button_submit" /></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form><br />
	</div>
	<ul>
		<li>', $txt['manual_posting_nav_tree'], '</li>
		<li>', $txt['manual_posting_spell_check'], '</li>
	</ul>
	<h3 class="section" id="newpoll">', $txt['manual_posting_start_poll'], '</h3>
	<p>', $txt['manual_posting_poll_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#newtopic">', $txt['manual_posting_poll_desc_link_newtopic'], '</a>', $txt['manual_posting_poll_desc_part2'], '</p>
	<p>', $txt['manual_posting_poll_options'], '</p>
	<p>', $txt['manual_posting_poll_note'], '</p>
	<h3 class="section" id="calendar">', $txt['manual_posting_post_event'], '</h3>
	<p>', $txt['manual_posting_event_desc_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_posting_event_desc_link_index_main'], '</a>', $txt['manual_posting_event_desc_part2'], '</p>
	<h3 class="section" id="reply">', $txt['manual_posting_replying'], '</h3>
	<p>', $txt['manual_posting_replying_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#newtopic">', $txt['manual_posting_replying_desc_link_newtopic'], '</a>', $txt['manual_posting_replying_desc_part2'], '</p>
	<p>', $txt['manual_posting_quick_reply_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#bbc">', $txt['manual_posting_quick_reply_link_bbc'], '</a>', $txt['manual_posting_quick_reply_part2'], '<a href="', $scripturl, '?action=help;area=posting_topics#smileys">', $txt['manual_posting_quick_reply_link_bbc_smileys'], '</a>', $txt['manual_posting_quick_reply_part3'], '</p>
	<h3 class="section" id="quote">', $txt['manual_posting_quote_post'], '</h3>
	<p>', $txt['manual_posting_quote_desc'], '</p>
	<ul>
		<li>', $txt['manual_posting_quote_both_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#bbc">', $txt['manual_posting_quote_both_link_bbc'], '</a>', $txt['manual_posting_quote_both_part2'], '</li>
		<li>', $txt['manual_posting_quote_independant_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#bbcref">', $txt['manual_posting_quote_independant_link_bbcref'], '</a>', $txt['manual_posting_quote_independant_part2'], '</li>
	</ul>
	<h3 class="section" id="modify">', $txt['manual_posting_modify_delete'], '</h3>
	<p>', $txt['manual_posting_modify_desc'], '</p>
	<p>', $txt['manual_posting_delete_desc'], '</p>
	<h2 class="section" id="standard">', $txt['manual_posting_sec_posting_options'], '</h2>
	<div class="help_sample">
			<br />
			<script type="text/javascript">
//<![CDATA[
			function showimage()
			{
					document.images.icons.src = "', $settings['images_url'], '/post/" + document.forms.postmodify.icon.options[document.forms.postmodify.icon.selectedIndex].value + ".gif";
					document.images.icons.src ="', $settings['images_url'], '/post/" + document.forms.postmodify.icon.options[document.forms.postmodify.icon.selectedIndex].value + ".gif";
			}
			var currentSwap = false;
			function swapOptions()
			{
					document.getElementById("postMoreExpand").src = smf_images_url + "/" + (currentSwap ? "collapse.gif" : "expand.gif");
					document.getElementById("postMoreExpand").alt = currentSwap ? "-" : "+";
					document.getElementById("postMoreOptions").style.display = currentSwap ? "" : "none";
					if (document.getElementById("postAttachment"))
								document.getElementById("postAttachment").style.display = currentSwap ? "" : "none";
					if (document.getElementById("postAttachment2"))
								document.getElementById("postAttachment2").style.display = currentSwap ? "" : "none";
					currentSwap = !currentSwap;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;area=posting_topics" method="post" accept-charset="', $context['character_set'], '" name="postmodify" style="margin: 0;" id="postmodify">
				<table border="0" width="100%" align="center" cellspacing="1" cellpadding="3" class="bordercolor">
					<tr>
						<td class="windowbg">
							<table border="0" cellpadding="3" width="100%">
								<tr>
									<td align="right"><strong>', $txt['manual_posting_msg_icon'], ':</strong></td>
									<td><select name="icon" id="icon" onchange="showimage();">
										<option value="xx" selected="selected">
											', $txt['manual_posting_standard_icon'], '
										</option>
										<option value="thumbup">
											', $txt['manual_posting_thumb_up_icon'], '
										</option>
										<option value="thumbdown">
											', $txt['manual_posting_thumb_down_icon'], '
										</option>
										<option value="exclamation">
											', $txt['manual_posting_exc_pt_icon'], '
										</option>
										<option value="question">
											', $txt['manual_posting_q_mark_icon'], '
										</option>
										<option value="lamp">
											', $txt['manual_posting_lamp_icon'], '
										</option>
										<option value="smiley">
											', $txt['manual_posting_smiley_icon'], '
										</option>
										<option value="angry">
											', $txt['manual_posting_angry_icon'], '
										</option>
										<option value="cheesy">
											', $txt['manual_posting_cheesy_icon'], '
										</option>
										<option value="grin">
											', $txt['manual_posting_grin_icon'], '
										</option>
										<option value="sad">
											', $txt['manual_posting_sad_icon'], '
										</option>
										<option value="wink">
											', $txt['manual_posting_wink_icon'], '
										</option>
									</select> <img src="', $settings['images_url'], '/post/xx.gif" name="icons" border="0" hspace="15" alt="" id="icons" /></td>
								</tr>
								<tr>
									<td align="right"></td>
									<td valign="middle">
										<script type="text/javascript">
//<![CDATA[
										function bbc_highlight(something, mode)
										{
													something.style.backgroundImage = "url(" + smf_images_url + "/bbc/" + (mode ? "bbc_hoverbg.gif)" : "bbc_bg.gif)");
										}
//]]>
</script>
										<a href="javascript:void(0);" onclick="surroundText(\'[b]\', \'[/b]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/bold.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_bold_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[i]\', \'[/i]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/italicize.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_italicize_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[u]\', \'[/u]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/underline.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_underline_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[s]\', \'[/s]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/strike.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_strike_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[glow=red,2,300]\', \'[/glow]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/glow.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_glow_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[shadow=red,left]\', \'[/shadow]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/shadow.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_shadow_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[move]\', \'[/move]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/move.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_move_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[pre]\', \'[/pre]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/pre.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_pre_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[left]\', \'[/left]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/left.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_left_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[center]\', \'[/center]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/center.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_center_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[right]\', \'[/right]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/right.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_right_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[hr]\', \'\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/hr.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_hr_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[size=10pt]\', \'[/size]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/size.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_size_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[font=Verdana]\', \'[/font]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/face.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_face_example'], '" /></a>
										<select onchange="surroundText(\'[color=\'+this.options[this.selectedIndex].value+\']\', \'[/color]\', document.forms.postmodify.message); this.selectedIndex = 0;" style="margin-bottom: 1ex; margin-left: 2ex;">
											<option value="" selected="selected">
												', $txt['manual_posting_Change_Color'], '
											</option>
											<option value="Black">
												', $txt['manual_posting_color_black'], '
											</option>
											<option value="Red">
												', $txt['manual_posting_color_red'], '
											</option>
											<option value="Yellow">
												', $txt['manual_posting_color_yellow'], '
											</option>
											<option value="Pink">
												', $txt['manual_posting_color_pink'], '
											</option>
											<option value="Green">
												', $txt['manual_posting_color_green'], '
											</option>
											<option value="Orange">
												', $txt['manual_posting_color_orange'], '
											</option>
											<option value="Purple">
												', $txt['manual_posting_color_purple'], '
											</option>
											<option value="Blue">
												', $txt['manual_posting_color_blue'], '
											</option>
											<option value="Beige">
												', $txt['manual_posting_color_beige'], '
											</option>
											<option value="Brown">
												', $txt['manual_posting_color_brown'], '
											</option>
											<option value="Teal">
												', $txt['manual_posting_color_teal'], '
											</option>
											<option value="Navy">
												', $txt['manual_posting_color_navy'], '
											</option>
											<option value="Maroon">
												', $txt['manual_posting_color_maroon'], '
											</option>
											<option value="LimeGreen">
												', $txt['manual_posting_color_lime'], '
											</option>
										</select><br />
										<a href="javascript:void(0);" onclick="surroundText(\'[flash=200,200]\', \'[/flash]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/flash.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_flash_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[img]\', \'[/img]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/img.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_img_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[url]\', \'[/url]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/url.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_url_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[email]\', \'[/email]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/email.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_email_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[ftp]\', \'[/ftp]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/ftp.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_ftp_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[table]\', \'[/table]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/table.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_table_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[tr]\', \'[/tr]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tr.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_tr_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[td]\', \'[/td]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/td.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_td_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[sup]\', \'[/sup]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sup.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_sup_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[sub]\', \'[/sub]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sub.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_sub_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[tt]\', \'[/tt]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tele.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_tele_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" />
										<a href="javascript:void(0);" onclick="surroundText(\'[code]\', \'[/code]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/code.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_code_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[quote]\', \'[/quote]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/quote.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_quote_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[list][li]\', \'[/li][li][/li][/list]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/list.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_list_example'], '" /></a>
									</td>
								</tr>
								<tr>
									<td align="right"></td>
									<td valign="middle">
										<a href="javascript:void(0);" onclick="replaceText(\' :)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/smiley.gif" align="bottom" alt="', $txt['manual_posting_smiley_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ;)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/wink.gif" align="bottom" alt="', $txt['manual_posting_wink_code'], '" border="0" /></a>
										<a href="javascript:void(0);" onclick="replaceText(\' :D\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cheesy.gif" align="bottom" alt="', $txt['manual_posting_cheesy_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ;D\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/grin.gif" align="bottom" alt="', $txt['manual_posting_grin_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' &gt;:(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/angry.gif" align="bottom" alt="', $txt['manual_posting_angry_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/sad.gif" align="bottom" alt="', $txt['manual_posting_sad_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :o\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/shocked.gif" align="bottom" alt="', $txt['manual_posting_shocked_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' 8)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cool.gif" align="bottom" alt="', $txt['manual_posting_cool_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ???\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/huh.gif" align="bottom" alt="', $txt['manual_posting_huh_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ::)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/rolleyes.gif" align="bottom" alt="', $txt['manual_posting_rolleyes_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :P\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/tongue.gif" align="bottom" alt="', $txt['manual_posting_tongue_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-[\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/embarrassed.gif" align="bottom" alt="', $txt['manual_posting_embarrassed_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-X\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/lipsrsealed.gif" align="bottom" alt="', $txt['manual_posting_lipsrsealed_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-\\\\\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/undecided.gif" align="bottom" alt="', $txt['manual_posting_undecided_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-*\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/kiss.gif" align="bottom" alt="', $txt['manual_posting_kiss_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :\\\'(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cry.gif" align="bottom" alt="', $txt['manual_posting_cry_code'], '" border="0" /></a><br />
									</td>
								</tr>
								<tr>
									<td valign="top" align="right"></td>
									<td>
									<textarea class="editor" name="message" rows="12" cols="60" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);" tabindex="2">
</textarea></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form><br />
	</div>
	<h3 class="section" id="messageicon">', $txt['manual_posting_sub_message_icon'], '</h3>
	<p>', $txt['manual_posting_msg_icon_dropdown'], '</p>
	<h3 class="section" id="bbc">', $txt['manual_posting_sub_bbc'], '</h3>
	<p>', $txt['manual_posting_bbc_desc'], '</p>
	<p>', $txt['manual_posting_bbc_ref_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#bbcref">', $txt['manual_posting_bbc_ref_link_bbcref'], '</a>', $txt['manual_posting_bbc_ref_part2'], '</p>
	<h3 class="section" id="smileys">', $txt['manual_posting_sub_smileys'], '</h3>
	<p>', $txt['manual_posting_smiley_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#nosmileys">', $txt['manual_posting_smiley_desc_link_nosmileys'], '</a>', $txt['manual_posting_smiley_desc_part2'], '</p>
	<p>', $txt['manual_posting_smiley_ref_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#smileysref">', $txt['manual_posting_smiley_ref_link_smileysref'], '</a>', $txt['manual_posting_smiley_ref_part2'], '</p>
	<h2 class="section" id="tags">', $txt['manual_posting_sec_tags'], '</h2>
	<p>', $txt['manual_posting_tags_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#bbcref">', $txt['manual_posting_tags_desc_link_bbcref'], '</a>', $txt['manual_posting_tags_desc_part2'], '</p>
	<p>', $txt['manual_posting_note_tags'], '</p>
	<h2 class="section" id="additional">', $txt['manual_posting_sec_additional_options'], '</h2>
	<p>', $txt['manual_posting_sec_additional_options_desc'], '</p>
	<div class="help_sample">
			<br />
			<script type="text/javascript">
//<![CDATA[
			var currentSwap = false;
			function swapOptions()
			{
						document.getElementById("postMoreExpand").src = smf_images_url + "/" + (currentSwap ? "collapse.gif" : "expand.gif");
						document.getElementById("postMoreExpand").alt = currentSwap ? "-" : "+";
						document.getElementById("postMoreOptions").style.display = currentSwap ? "" : "none";
						if (document.getElementById("postAttachment"))
								document.getElementById("postAttachment").style.display = currentSwap ? "" : "none";
						if (document.getElementById("postAttachment2"))
								document.getElementById("postAttachment2").style.display = currentSwap ? "" : "none";
						currentSwap = !currentSwap;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;area=posting_topics" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
				<table border="0" width="100%" align="center" cellspacing="1" cellpadding="3" class="bordercolor">
					<tr>
						<td class="windowbg">
							<table border="0" cellpadding="3" width="100%">
								<tr>
									<td colspan="2" style="padding-left: 5ex;"><a href="javascript:swapOptions();"><img src="', $settings['images_url'], '/expand.gif" alt="+" border="0" id="postMoreExpand" name="postMoreExpand" /></a> <a href="javascript:swapOptions();" class="board"><strong>', $txt['manual_posting_sec_additional_options'], '...</strong></a></td>
								</tr>
								<tr>
									<td></td>
									<td>
										<div id="postMoreOptions">
											<table width="80%" cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="smalltext"><input type="checkbox" class="input_check" />&nbsp;', $txt['manual_posting_notify'], '</td>
												</tr>
												<tr>
													<td class="smalltext"><input type="checkbox" class="input_check" />&nbsp;', $txt['manual_posting_return'], '</td>
												</tr>
												<tr>
													<td class="smalltext"><input type="checkbox" class="input_check" />&nbsp;', $txt['manual_posting_no_smiley'], '</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								<tr id="post', $txt['manual_posting_attach'], 'ment2">
									<td align="right" valign="top"><strong>', $txt['manual_posting_attach'], ':</strong></td>
									<td class="smalltext"><input type="file" size="48" name="attachment[]" class="input_file" /><br />
									<input type="file" size="48" name="attachment[]" class="input_file" /><br />
									', $txt['manual_posting_allowed_types'], '<br />
									', $txt['manual_posting_max_size'], '</td>
								</tr>
								<tr>
									<td align="center" colspan="2">
										<script type="text/javascript">
//<![CDATA[
										swapOptions();
//]]>
</script> <span class="smalltext"><br />
										', $context['browser']['is_firefox'] ? $txt['manual_posting_shortcuts_firefox'] : $txt['manual_posting_shortcuts'], '</span><br />
										<input type="button" accesskey="s" tabindex="3" value="', $txt['manual_posting_posts'], '" class="button_submit" /> <input type="button" accesskey="p" tabindex="4" value="', $txt['manual_posting_preview'], '" class="button_submit" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form><br />
	</div>
	<h3 class="section" id="notify">', $txt['manual_posting_sub_notify'], '</h3>
	<p>', $txt['manual_posting_notify_desc'], '</p>
	<h3 class="section" id="return">', $txt['manual_posting_sub_return'], '</h3>
	<p>', $txt['manual_posting_return_desc'], '</p>
	<h3 class="section" id="nosmileys">', $txt['manual_posting_sub_no_smiley'], '</h3>
	<p>', $txt['manual_posting_no_smiley_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#smileysref">', $txt['manual_posting_no_smiley_desc_link_smileysref'], '</a>', $txt['manual_posting_no_smiley_desc_part2'], '</p>
	<h3 class="section" id="attachments">', $txt['manual_posting_sub_attach'], '</h3>
	<p>', $txt['manual_posting_attach_desc_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#modify">', $txt['manual_posting_attach_desc_link_modify'], '</a>', $txt['manual_posting_attach_desc_part2'], '</p>
	<ul>
		<li>', $txt['manual_posting_attach_desc2'], '</li>
		<li>', $txt['manual_posting_most_forums_attach'], '</li>
	</ul>';
}

// Quoting posts page.
function template_manual_quoting_posts()
{
	// TODO : Write this.
}

// Modifying posts page.
function template_manual_modifying_posts()
{
	// TODO : Write this.
}

function template_manual_smileys()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_posting_smileys_help_desc'], '</p>
	<p>', $txt['manual_posting_smiley_parse'], '</p>
	<table cellspacing="1" cellpadding="3" class="table_grid">
		<thead>
			<tr>
				<th class="catbg first_th">', $txt['manual_posting_smileys_help_name'], '</th>
				<th class="catbg">', $txt['manual_posting_smileys_help_img'], '</th>
				<th class="catbg last_th">', $txt['manual_posting_smileys_help_code'], '</th>
			</tr>
		</thead>
		<tbody>';

	$alternate = false;
	foreach ($context['smileys'] as $smiley)
	{
		echo '
			<tr class="windowbg', $alternate ? '2' : '', '">
				<td>', $smiley['name'], '</td>
				<td>', $smiley['to'], '</td>
				<td>', $smiley['from'], '</td>
			</tr>';
		$alternate = !$alternate;
	}

	echo '
		</tbody>
	</table>';
}

function template_manual_bbcode()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_posting_sub_smf_bbc_desc'], '</p>
	<table class="bordercolor" cellspacing="1" cellpadding="3">
		<tr>
			<th class="catbg">', $txt['manual_posting_header_name'], '</th>
			<th class="catbg">', $txt['manual_posting_header_button'], '</th>
			<th class="catbg">', $txt['manual_posting_header_code'], '</th>
			<th class="catbg">', $txt['manual_posting_header_output'], '</th>
			<th class="catbg">', $txt['manual_posting_header_comments'], '</th>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_bold'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/bold.gif" alt="', $txt['manual_posting_bbc_bold'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_bold_code'], '</td>
			<td><strong>', $txt['manual_posting_bold_output'], '</strong></td>
			<td>', $txt['manual_posting_bold_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_italic'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/italicize.gif" alt="', $txt['manual_posting_bbc_italic'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_italic_code'], '</td>
			<td><em>', $txt['manual_posting_italic_output'], '</em></td>
			<td>', $txt['manual_posting_italic_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_underline'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/underline.gif" alt="', $txt['manual_posting_bbc_underline'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_underline_code'], '</td>
			<td><span class="underline">', $txt['manual_posting_underline_output'], '</span></td>
			<td>', $txt['manual_posting_underline_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_strike'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/strike.gif" alt="', $txt['manual_posting_bbc_strike'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_strike_code'], '</td>
			<td><del>', $txt['manual_posting_strike_output'], '</del></td>
			<td>', $txt['manual_posting_strike_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_glow'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/glow.gif" alt="', $txt['manual_posting_bbc_glow'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_glow_code'], '</td>
			<td>
				<div style="filter: Glow(color=red, strength=2); width: 30px;">
					', $txt['manual_posting_glow_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_glow_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_shadow'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/shadow.gif" alt="', $txt['manual_posting_bbc_shadow'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_shadow_code'], '</td>
			<td>
				<div style="filter: Shadow(color=red, direction=240); width: 30px;">
					', $txt['manual_posting_shadow_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_shadow_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_move'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/move.gif" alt="', $txt['manual_posting_bbc_move'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_move_code'], '</td>
			<td>', $context['browser']['is_ie'] ? '<marquee>' . $txt['manual_posting_move_output'] . '</marquee>' : '', '</td>
			<td>', $txt['manual_posting_move_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_pre'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/pre.gif" alt="', $txt['manual_posting_bbc_pre'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>[pre]Simple<br />
			&nbsp;&nbsp;Machines<br />
			&nbsp;&nbsp;&nbsp;&nbsp;Forum[/pre]</td>
			<td>
				<pre>
Simple
  Machines
    Forum
</pre>
			</td>
			<td>', $txt['manual_posting_pre_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_left'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/left.gif" alt="', $txt['manual_posting_bbc_left'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_left_code'], '</td>
			<td>
				<p align="left">', $txt['manual_posting_left_output'], '</p>
			</td>
			<td>', $txt['manual_posting_left_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_centered'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/center.gif" alt="', $txt['manual_posting_bbc_centered'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_centered_code'], '</td>
			<td>
				<span class="centertext">
					', $txt['manual_posting_centered_output'], '
				</span>
			</td>
			<td>', $txt['manual_posting_centered_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_right'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/right.gif" alt="', $txt['manual_posting_bbc_right'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_right_code'], '</td>
			<td>
				<p align="right">', $txt['manual_posting_right_output'], '</p>
			</td>
			<td>', $txt['manual_posting_right_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_rtl'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_rtl_code'], '</td>
			<td>
				<div dir="rtl">
					', $txt['manual_posting_rtl_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_rtl_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_ltr'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_ltr_code'], '</td>
			<td>
				<div dir="ltr">
					', $txt['manual_posting_ltr_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_ltr_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_hr'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/hr.gif" alt="', $txt['manual_posting_bbc_hr'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_hr_code'], '</td>
			<td>
				<hr />
			</td>
			<td>', $txt['manual_posting_hr_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_size'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_size_code'], '</td>
			<td><span style="font-size: 10pt;">', $txt['manual_posting_size_output'], '</span></td>
			<td>', $txt['manual_posting_size_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_font'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_font_code'], '</td>
			<td><span style="font-family: Verdana;">', $txt['manual_posting_font_output'], '</span></td>
			<td>', $txt['manual_posting_font_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_color'], '</td>
			<td><select>
				<option value="" selected="selected">
					', $txt['manual_posting_Change_Color'], '
				</option>
				<option value="Black">
					', $txt['manual_posting_color_black'], '
				</option>
				<option value="Red">
					', $txt['manual_posting_color_red'], '
				</option>
				<option value="Yellow">
					', $txt['manual_posting_color_yellow'], '
				</option>
				<option value="Pink">
					', $txt['manual_posting_color_pink'], '
				</option>
				<option value="Green">
					', $txt['manual_posting_color_green'], '
				</option>
				<option value="Orange">
					', $txt['manual_posting_color_orange'], '
				</option>
				<option value="Purple">
					', $txt['manual_posting_color_purple'], '
				</option>
				<option value="Blue">
					', $txt['manual_posting_color_blue'], '
				</option>
				<option value="Beige">
					', $txt['manual_posting_color_beige'], '
				</option>
				<option value="Brown">
					', $txt['manual_posting_color_brown'], '
				</option>
				<option value="Teal">
					', $txt['manual_posting_color_teal'], '
				</option>
				<option value="Navy">
					', $txt['manual_posting_color_navy'], '
				</option>
				<option value="Maroon">
					', $txt['manual_posting_color_maroon'], '
				</option>
				<option value="LimeGreen">
					', $txt['manual_posting_color_lime'], '
				</option>
			</select></td>
			<td>', $txt['manual_posting_color_code'], '</td>
			<td><span style="color: red;">', $txt['manual_posting_color_output'], '</span></td>
			<td>', $txt['manual_posting_color_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_flash'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/flash.gif" alt="', $txt['manual_posting_bbc_flash'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_flash_code'], '</td>
			<td><a href="http://somesite/somefile.swf" class="board new_win" target="_blank">', $txt['manual_posting_flash_output'], '</a></td>
			<td>', $txt['manual_posting_flash_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td rowspan="2">', $txt['manual_posting_bbc_img'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/img.gif" alt="', $txt['manual_posting_bbc_img'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_img_top_code'], '</td>
			<td><img src="', $settings['images_url'], '/on.gif" alt="" /></td>
			<td rowspan="2">', $txt['manual_posting_img_top_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_img_bottom_code'], '</td>
			<td><img src="', $settings['images_url'], '/on.gif" width="48" height="48" alt="" /></td>
		</tr>
		<tr class="windowbg2">
			<td rowspan="2">', $txt['manual_posting_bbc_url'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/url.gif" alt="', $txt['manual_posting_bbc_url'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_url_code'], '</td>
			<td><a href="http://somesite" class="board new_win" target="_blank">', $txt['manual_posting_url_output'], '</a></td>
			<td rowspan="2">', $txt['manual_posting_url_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_url_bottom_code'], '</td>
			<td><a href="http://somesite" class="board new_win" target="_blank">', $txt['manual_posting_url_bottom_output'], '</a></td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_email'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/email.gif" alt="', $txt['manual_posting_bbc_email'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_email_code'], '</td>
			<td><a href="mailto:someone@somesite" class="board">', $txt['manual_posting_email_output'], '</a></td>
			<td>', $txt['manual_posting_email_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td rowspan="2">', $txt['manual_posting_bbc_ftp'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/ftp.gif" alt="', $txt['manual_posting_bbc_ftp'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_ftp_code'], '</td>
			<td><a href="ftp://somesite/somefile" class="board new_win" target="_blank">', $txt['manual_posting_ftp_output'], '</a></td>
			<td rowspan="2">', $txt['manual_posting_ftp_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_ftp_bottom_code'], '</td>
			<td><a href="ftp://somesite/somefile" class="board new_win" target="_blank">', $txt['manual_posting_ftp_bottom_output'], '</a></td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_table'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/table.gif" alt="', $txt['manual_posting_bbc_table'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_table_code'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_table_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_row'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_row_code'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_row_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td rowspan="2">', $txt['manual_posting_bbc_column'], '</td>
			<td rowspan="2">*</td>
			<td>', $txt['manual_posting_column_code'], '</td>
			<td>
				<table>
					<tr>
						<td valign="top">', $txt['manual_posting_column_output'], '</td>
					</tr>
				</table>
			</td>
			<td rowspan="2">', $txt['manual_posting_column_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>[table][tr][td]SMF[/td]<br />
			[td]Bulletin[/td][/tr]<br />
			[tr][td]Board[/td]<br />
			[td]Code[/td][/tr][/table]</td>
			<td>
				<table>
					<tr>
						<td valign="top">SMF</td>
						<td valign="top">Bulletin</td>
					</tr>
					<tr>
						<td valign="top">Board</td>
						<td valign="top">Code</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_sup'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sup.gif" alt="', $txt['manual_posting_bbc_sup'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_sup_code'], '</td>
			<td><sup>', $txt['manual_posting_sup_output'], '</sup></td>
			<td>', $txt['manual_posting_sup_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_sub'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sub.gif" alt="', $txt['manual_posting_bbc_sub'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_sub_code'], '</td>
			<td><sub>', $txt['manual_posting_sub_output'], '</sub></td>
			<td>', $txt['manual_posting_sub_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_tt'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tele.gif" alt="', $txt['manual_posting_bbc_tt'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_tt_code'], '</td>
			<td><tt>', $txt['manual_posting_tt_output'], '</tt></td>
			<td>', $txt['manual_posting_tt_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_code'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/code.gif" alt="', $txt['manual_posting_bbc_code'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_code_code'], '</td>
			<td>
				<div class="codeheader">
					Code:
				</div>
				<div class="code">
					<span style="color: #0000BB;">&lt;?php phpinfo</span><span style="color: #007700;">();</span> <span style="color: #0000BB;">?&gt;</span>
				</div>
			</td>
			<td>', $txt['manual_posting_code_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td rowspan="2">', $txt['manual_posting_bbc_quote'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/quote.gif" alt="', $txt['manual_posting_bbc_quote'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_quote_code'], '</td>
			<td>
				<div class="', $txt['manual_posting_quote_output'], 'header">
					Quote
				</div>
				<blockquote>
					', $txt['manual_posting_quote_output'], '
				</blockquote>
			</td>
			<td rowspan="2">', $txt['manual_posting_quote_comment'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_quote_buttom_code'], '</td>
			<td>
				<div class="', $txt['manual_posting_quote_buttom_output'], 'header">
					Quote from: author
				</div>
				<blockquote>
					', $txt['manual_posting_quote_buttom_output'], '
				</blockquote>
			</td>
		</tr>
		<tr class="windowbg">
			<td rowspan="2">', $txt['manual_posting_bbc_list'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/list.gif" alt="', $txt['manual_posting_bbc_list'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_list_code'], '</td>
			<td>', $txt['manual_posting_list_output'], '</td>
			<td rowspan="2">', $txt['manual_posting_list_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_list_buttom_code'], '</td>
			<td>', $txt['manual_posting_list_buttom_output'], '</td>
		</tr>
		<tr class="windowbg2">
			<td>', $txt['manual_posting_bbc_abbr'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_abbr_code'], '</td>
			<td><abbr title="exempli gratia">', $txt['manual_posting_abbr_output'], '</abbr></td>
			<td>', $txt['manual_posting_abbr_comment'], '</td>
		</tr>
		<tr class="windowbg">
			<td>', $txt['manual_posting_bbc_acro'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_acro_code'], '</td>
			<td><acronym title="Simple Machines Forum">', $txt['manual_posting_acro_output'], '</acronym></td>
			<td>', $txt['manual_posting_acro_comment'], '</td>
		</tr>
	</table>';
}

// WYSIWYG page.
function template_manual_wysiwyg()
{
	// TODO : Write this.
}

// Personal messages page.
function template_manual_pm_messages()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<p>', $txt['manual_pm_community'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;area=sending_pms#pm">', $txt['manual_pm_sec_pm'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=sending_pms#description">', $txt['manual_pm_pm_desc'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=sending_pms#reading">', $txt['manual_pm_reading'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;area=sending_pms#interface">', $txt['manual_pm_sec_pm2'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=sending_pms#starting">', $txt['manual_pm_start_reply'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="section" id="pm">', $txt['manual_pm_sec_pm'], '</h2>
	<h3 class="section" id="description">', $txt['manual_pm_pm_desc'], '</h3>
	<p>', $txt['manual_pm_pm_desc_1'], '</p>
	<p>', $txt['manual_pm_pm_desc_2'], '</p>
	<p>', $txt['manual_pm_pm_desc_3'], '</p>
	<h3 class="section" id="reading">', $txt['manual_pm_reading'], '</h3>
	<p>', $txt['manual_pm_reading_desc_part1'], '<a href="', $scripturl, '?action=help;area=logging_in">', $txt['manual_pm_reading_desc_link_loginout'], '</a>', $txt['manual_pm_reading_desc_part2'], '<a href="', $scripturl, '?action=help;area=sending_pms#interface">', $txt['manual_pm_reading_desc_link_loginout_interface'], '</a>', $txt['manual_pm_reading_desc_part3'], '</p>
	<h2 class="section" id="interface">', $txt['manual_pm_sec_pm2'], '</h2>
	<p>', $txt['manual_pm_pm_desc2_part1'], '<a href="', $scripturl, '?action=help;area=message_view">', $txt['manual_pm_pm_desc2_link_index_message'], '</a>', $txt['manual_pm_pm_desc2_part2'], '</p>
	<div class="help_sample">
			<script type="text/javascript">
//<![CDATA[
			var currentSort = false;
			function sortLastPM()
			{
					document.getElementById("sort-arrow").src = smf_images_url + "/" + (currentSort ? "sort_up.gif" : "sort_down.gif");
					document.getElementById("sort-arrow").alt = "";
					currentSort = !currentSort;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;area=sending_pms" method="post" accept-charset="', $context['character_set'], '">
				<table border="0" width="100%" cellspacing="0" cellpadding="3">
					<tr>
						<td valign="bottom"><span class="nav"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=board_index" class="nav">', $txt['manual_pm_forum_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=sending_pms#interface" class="nav">', $txt['manual_pm_personal_msgs'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;area=sending_pms#interface" class="nav">', $txt['manual_pm_inbox'], '</a></strong></span></td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
					<td width="125" valign="top">
						<table border="0" cellpadding="4" cellspacing="1" class="bordercolor" width="100">
							<tr>
								<td class="catbg">', $txt['manual_pm_messages'], '</td>
							</tr>
							<tr class="windowbg">
								<td class="smalltext" style="padding-bottom: 2ex;">
								', $txt['manual_pm_new_msg'], '<br /><br />
								<strong>', $txt['manual_pm_inbox'], '</strong><br />
								', $txt['manual_pm_outbox'], '<br />
							</td>
						</tr>
					</table>
					<br />
				</td>
				<td valign="top">
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="bordercolor" align="center">
						<tr>
							<td>
								<table border="0" width="100%" cellspacing="1" class="bordercolor">
									<tr class="titlebg">
										<td>&nbsp;</td>
										<td style="width: 32ex;"><a href="javascript:sortLastPM();">', $txt['manual_pm_date'], '&nbsp; <img id="sort-arrow" src="', $settings['images_url'], '/sort_up.gif" alt="" border="0" name="sort-arrow" /></a></td>
										<td width="46%"><a href="', $scripturl, '?action=help;area=sending_pms#interface">', $txt['manual_pm_subject2'], '</a></td>
										<td><a href="', $scripturl, '?action=help;area=sending_pms#interface">', $txt['manual_pm_from'], '</a></td>
										<td align="center" width="24"><input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" /></td>
									</tr>
									<tr class="windowbg">
										<td align="center" width="2%"><img src="' . $settings['images_url'] . '/icons/pm_read.gif" style="margin-right: 4px;" alt="" /></td>
										<td>', $txt['manual_pm_date_and_time'], '</td>
										<td><a href="', $scripturl, '?action=help;area=sending_pms#interface" class="board">', $txt['manual_pm_subject'], '</a></td>
										<td>', $txt['manual_pm_another_member'], '</td>
										<td align="center"><input type="checkbox" class="input_check" /></td>
									</tr>
									<tr>
										<td class="windowbg" style="padding: 2px;" align="right" colspan="6"></td>
									</tr>
									<tr>
										<td colspan="6" class="catbg" height="25">
											<div class="floatleft"><strong>', $txt['manual_pm_pages'], ':</strong> [<strong>1</strong>]</div>
											<div class="floatright">&nbsp;<input type="button" value="', $txt['manual_pm_delete_selected'], '" class="button_submit" /></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table><br />
				</td>
			</tr></table>
			<br />
			</form>
	</div>
	<ul>
		<li>', $txt['manual_pm_nav_tree'], '</li>
		<li>', $txt['manual_pm_delete_button'], '</li>
		<li>', $txt['manual_pm_outbox_button'], '</li>
		<li>', $txt['manual_pm_new_msg2_part1'], '<a href="', $scripturl, '?action=help;area=sending_pms#newtopic">', $txt['manual_pm_new_msg2_link_posting_newtopic'], '</a>', $txt['manual_pm_new_msg2_part2'], '</li>
		<li>', $txt['manual_pm_reload'], '</li>
		<li>', $txt['manual_pm_sort_by'], '</li>
		<li>', $txt['manual_pm_main_subject'], '</li>
		<li>', $txt['manual_pm_page_nos'], '</li>
	</ul>
	<h3 class="section" id="starting">', $txt['manual_pm_start_reply'], '</h3>
	<p>', $txt['manual_pm_how_to_start_reply_part1'], '<a href="', $scripturl, '?action=help;area=logging_in">', $txt['manual_pm_how_to_start_reply_link_loginout'], '</a>', $txt['manual_pm_how_to_start_reply_part2'], '</p>
	<ul>
		<li>', $txt['manual_pm_msg_link_part1'], '<a href="', $scripturl, '?action=help;area=sending_pms#interface">', $txt['manual_pm_msg_link_link_interface'], '</a>', $txt['manual_pm_msg_link_part2'], '</li>
		<li>', $txt['manual_pm_click_name_part1'], '<a href="', $scripturl, '?action=help;area=profile_summary#info-all">', $txt['manual_pm_click_name_link_profile_info-all'], '</a>', $txt['manual_pm_click_name_part2'], '</li>
		<li>', $txt['manual_pm_click_im_icon'], '</li>
		<li>', $txt['manual_pm_click_pm_icon_part1'], '<a href="', $scripturl, '?action=help;area=sending_pms#info-all">', $txt['manual_pm_click_pm_icon_link_profile_info-all'], '</a>', $txt['manual_pm_click_pm_icon_part2'], '</li>
		<li>', $txt['manual_pm_reply_msg_part1'], '<a href="', $scripturl, '?action=help;area=posting_topics#reply">', $txt['manual_pm_reply_msg_link_posting_reply'], '</a>', $txt['manual_pm_reply_msg_part2'], '</li>
	</ul>';
}

// Personal message actions page.
function template_manual_pm_actions()
{
	// TODO : Write this.
}

// Personal message preferences page.
function template_manual_pm_preferences()
{
	// TODO : Write this.
}

// The search help page.
function template_manual_searching()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_searching_you_have_arrived'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;area=searching#starting">', $txt['manual_searching_sec_search'], '</a></li>
		<li>
			<a href="', $scripturl, '?action=help;area=searching#syntax">', $txt['manual_searching_sec_syntax'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=searching#quotes">', $txt['manual_searching_sub_quotes'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;area=searching#searching">', $txt['manual_searching_sec_simple_adv'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;area=searching#simple">', $txt['manual_searching_sub_simple'], '</a></li>
				<li><a href="', $scripturl, '?action=help;area=searching#advanced">', $txt['manual_searching_sub_adv'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="section" id="starting">', $txt['manual_searching_sec_search'], '</h2>
	<p>', $txt['manual_searching_search_desc_part1'], '<a href="', $scripturl, '?action=help;area=main_menu">', $txt['manual_searching_search_desc_link_index_main'], '</a>', $txt['manual_searching_search_desc_part2'], '</p>
	<h2 class="section" id="syntax">', $txt['manual_searching_sec_syntax'], '</h2>
	<p>', $txt['manual_searching_syntax_desc'], '</p>
	<h3 class="section" id="quotes">', $txt['manual_searching_sub_quotes'], '</h3>
	<p>', $txt['manual_searching_quotes_desc'], '</p>
	<h2 class="section" id="searching">', $txt['manual_searching_sec_simple_adv'], '</h2>
	<h3 class="section" id="simple">', $txt['manual_searching_sub_simple'], '</h3>
	<p>', $txt['manual_searching_simple_desc'], '</p>
	<h3 class="section" id="advanced">', $txt['manual_searching_sub_adv'], '</h3>
	<p>', $txt['manual_searching_adv_desc'], '</p>
	<div class="help_sample">
		<form id="searchform" action="', $scripturl, '?action=help;area=searching" method="post" accept-charset="', $context['character_set'], '">
			<h3 class="catbg"><span class="left"></span>
				', !empty($settings['use_buttons']) ? '<img src="' . $settings['images_url'] . '/buttons/search.gif" alt="" />' : '', $txt['manual_searching_search_param'], '
			</h3>
			<fieldset id="advanced_search">
				<span class="upperframe"><span></span></span>
				<div class="roundframe">
					<input type="hidden" name="advanced" value="1" />
					<span class="enhanced">
						<strong>', $txt['manual_searching_search_for'], ':</strong>
						<input type="text" name="search" size="40" class="input_text" />
						<select name="searchtype">
							<option value="1">', $txt['manual_searching_match_all'], '</option>
							<option value="2">', $txt['manual_searching_match_any'], '</option>
						</select>
					</span>
					<dl id="search_options">
						<dt>', $txt['manual_searching_by_user'], ':</dt>
						<dd><input id="userspec" type="text" name="userspec" size="40" class="input_text" /></dd>
						<dt>', $txt['manual_searching_search_order'], ':</dt>
						<dd>
							<select>
								<option selected="selected">
									', $txt['manual_searching_relevant_first'], '
								</option>
								<option>
									', $txt['manual_searching_big_first'], '
								</option>
								<option>
									', $txt['manual_searching_small_first'], '
								</option>
								<option>
									', $txt['manual_searching_recent_first'], '
								</option>
								<option>
									', $txt['manual_searching_oldest_first'], '
								</option>
							</select>
						</dd>
						<dt class="options">', $txt['manual_searching_options'], ':</dt>
						<dd class="options">
							<label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['manual_searching_show_results'], '</label><br />
							<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['manual_searching_subject_only'], '</label>
						</dd>
						<dt class="between">', $txt['manual_searching_msg_age'], ': </dt>
						<dd>', $txt['manual_searching_between'], ' <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="4" class="input_text" />&nbsp;', $txt['manual_searching_and'], '&nbsp;<input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="4" class="input_text" /> ', $txt['manual_searching_days'], '</dd>
					</dl>
				</div>
				<span class="lowerframe"><span></span></span>
			</fieldset>
			<fieldset>
				<span class="upperframe"><span></span></span>
				<div class="roundframe">
					<h4 class="titlebg"><span class="left"></span>
						', $txt['manual_searching_choose'], '
					</h4>
					<div class="flow_auto" id="searchBoardsExpand">
						<ul class="floatleft">
							<li class="category">
								<span>', $txt['manual_searching_cat'], '</span>
								<ul>
									<li class="board" style="margin-', $context['right_to_left'] ? 'right' : 'left' , ': 1em;">
										<label for="brd2"><input type="checkbox" id="brd2" name="brd[2]" value="2" class="input_check" /> ', $txt['manual_searching_another_board'], '</label>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="floatright">
							<li class="category">
								<span>', $txt['manual_searching_cat'], '</span>
								<ul>
									<li class="board" style="margin-', $context['right_to_left'] ? 'right' : 'left' , ': 1em;">
										<label for="brd1"><input type="checkbox" id="brd1" name="brd[1]" value="1" class="input_check" /> ', $txt['manual_searching_board_name'], '</label>
									</li>
								</ul>
							</li>
						</ul>
					</div><br />
					<div>
						<input type="checkbox" name="all" id="check_all" value="" checked="checked" onclick="invertAll(this, this.form, \'brd\');" class="input_check" />
						<label for="check_all">', $txt['manual_searching_check_all'], '</label>
					</div>
				</div>
				<span class="lowerframe"><span></span></span>
			</fieldset>
			<div><input type="submit" name="submit" value="', $txt['manual_searching_search'], '" class="button_submit" /></div>
		</form>
	</div>
	<ul>
		<li>', $txt['manual_searching_nav_tree'], '</li>
		<li>', $txt['manual_searching_three_options_part1'], '<a href="', $scripturl, '?action=help;area=searching#syntax">', $txt['manual_searching_three_options_link_syntax'], '</a>', $txt['manual_searching_three_options_part2'], '</li>
		<li>', $txt['manual_searching_wildcard'], '</li>
		<li>', $txt['manual_searching_results_as_messages'], '</li>
		<li>', $txt['manual_searching_message_age'], '</li>
		<li>', $txt['manual_searching_which_board'], '</li>
		<li>', $txt['manual_searching_search_button'], '</li>
	</ul>';
}

// Memberlist page.
function template_manual_memberlist()
{
	// TODO : Write this.
}

// Calendar page.
function template_manual_calendar()
{
	// TODO : Write this.
}

// BBC reference page.
function template_manual_bbc_ag()
{
	// TODO : Write this.
}

// BBC reference page.
function template_manual_bbc_hq()
{
	// TODO : Write this.
}

// BBC reference page.
function template_manual_bbc_rz()
{
	// TODO : Write this.
}

?>