<?php
// Version: 2.0 RC2; index

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0 RC2';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = false;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '><head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />
	<meta name="keywords" content="', $context['meta_keywords'], '" />
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// The ?rc2 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?rc2" />
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/print.css?rc2" media="print" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="' . $scripturl . '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// We'll have to use the cookie to remember the header...
	if ($context['user']['is_guest'])
	{
		$options['collapse_header'] = !empty($_COOKIE['upshrink']);
		$options['collapse_header_ic'] = !empty($_COOKIE['upshrinkIC']);
	}

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'firefox', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?rc2"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?rc2"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
<div id="mainframe"', !empty($settings['forum_width']) ? ' style="width: ' . $settings['forum_width'] . '"' : '', '>
	 <div id="header">
		 <div id="head-l">
			 <div id="head-r">
				 <div id="userarea" class="clearfix">';
	if (!empty($context['user']['avatar']))
					echo '<div id="my-avatar" class="clearfix">'.$context['user']['avatar']['image'].'</div>';
	
		// If the user is logged in, display stuff like their name, new messages, etc.
	if ($context['user']['is_logged'])
	{
		echo '', $txt['hello_member'], ' <b>', $context['user']['name'], '</b><br />';

		// Is the forum in maintenance mode?
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '<b>', $txt['maintain_mode_on'], '</b><br />';

		// Are there any members waiting for approval?
		if (!empty($context['unapproved_members']))
			echo '', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '<br />';

		echo '
				<a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a><br />
				<a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a><br />', $context['current_time'], '<br />';
		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '<a href="', $scripturl, '?action=moderate;area=reports">', sprintf($txt['mod_reports_waiting'], $context['open_mod_reports']), '</a>';
	}
	// Otherwise they're a guest - so politely ask them to register or login.
	else
	{
		echo ' ', sprintf($txt['welcome_guest'], $txt['guest_title']), '<br />', $context['current_time'], '<br />
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/sha1.js"></script>';
	}
	echo '
	</div>';
  echo '
		 <div id="searcharea">';
			  echo '
		  <form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
		  <input class="inputbox" type="text" name="search" value="', $txt['search'], '..." onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['search'], '...\';" />';

	  // Search within current topic?
		 if (!empty($context['current_topic']))
		 echo '<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
  
	  // If we're on a certain board, limit it to this board ;).
		  elseif (!empty($context['current_board']))
		 echo '<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
		echo '
	 </form>';
		echo '
  </div>';

  // Show a random news item? (or you could pick one from news_lines...)
	if (false && !empty($settings['enable_news'])){
		echo '<div id="news">
		  <b>', $txt['news'], ':</b> ', $context['random_news_line'], '</div>';}
  echo '
		<a href="'.$scripturl.'" title=""><span id="logo"> </span></a>';
		  echo '
		</div>
	 </div>
  </div>
<div id="tbar-l">
	<div id="tbar-r">
		<div id="toolbar">
	',template_menu(),'
	  </div>
	</div>
</div>
	  <div id="bodyarea-l">
		  <div id="bodyarea-r">
			  <div id="bodyarea">';
				theme_linktree2();

}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

		 echo '
		</div>
	</div>
 </div>';


	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
 <div id="footer">
		 <div id="foot-l">
			 <div id="foot-r">
				 <div id="footerarea">
					  <div class="right"><a id="button_xhtml" href="http://validator.w3.org/check/referer" target="_blank" class="new_win" title="', $txt['valid_xhtml'], '"><small>XHTML</small></a> | <a id="button_rss" href="', $scripturl , '?type=rss;action=.xml" class="new_win"><small>RSS</small></a> | <a id="button_wap2" href="', $scripturl , '?wap2" class="new_win"><small>WAP2</small></a> | ', theme_copyright(), '
			</div>
							 <div class="left"><b>Mobile</b> 2009, by <a href="http://www.jpr62.com" title=""  target="_blank">Crip</a>
		  </div>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p class="smalltext" id="show_loadtime">', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	  echo '
			 </div>
		 </div>
	 </div>
 </div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	 </div>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree2()
{
	global $context, $settings, $options;

	echo '
		 <div id="linktree">';
  
	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '">' . $tree['name'] . '</a>' : $tree['name'];

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo '&nbsp;&#187;&nbsp;';
	}

	echo '
	</div>';
}

function theme_linktree()
{
	return;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div id="navigation">
			 <div class="navLeft">
					<div class="navRight">
			<ul>';
			foreach ($context['menu_buttons'] as $act => $button)
				echo '<li><a ', $button['active_button'] ? ' class="current"' : '' , ' href="', $button['href'], '">', $button['title'], '</a></li>';

	echo '
			</ul>
		</div>
  </div>
</div>';

}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// Right to left menu should be in reverse order.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '<li' . (isset($value['active']) ? ' class="active"' : '') . '><a href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . (isset($value['active']) ? '<em>' : '') . $txt[$value['text']] . (isset($value['active']) ? '</em>' : '') . '</span></a></li>';

	// No buttons? No button strip either.
	if (empty($buttons))
		return;
	
	// Make the last one, as easy as possible.
	$list_item = array('<li>', '<li class="active">');
	$active_item = array('<li class="last">', '<li class="active last">');

	$buttons[count($buttons) - 1] = str_replace($list_item, $active_item, $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', $direction != 'top' ? '_bottom' : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul class="reset clearfix">
				', implode('', $buttons), '
			</ul>
		</div>';
}

?>