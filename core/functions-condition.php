<?php

#Function: is_front()
function is_front() {
	if(is_404()) return false;
	global $bj,$section;
	if(($bj->vars->load[0] == 'section' && $section['ID'] == load_option('default_section')) or $bj->vars->load[0] == 'page')
		return true;
	elseif(empty($bj->vars->load[0]))
		return true;
	return false;
}

#Function: is_entry()
function is_entry($woo='') {
	if(is_404()) return false;
	global $bj,$entries;
	if($woo == '')
		if($bj->vars->load[0] == 'entry' and $entries)
			return true;
	else
		if($bj->vars->load[0] == 'entry')
			if($entries)
				foreach($entries as $entry)
					 if($entry['ID'] == $woo)
						return true;
	return false;
}

#Function: is_section()
function is_section($name='') {
	if(is_404()) return false;
	global $bj,$section;
	if($name == '') {
		if($bj->vars->load[0] == 'section' and $section)
			return true;
		elseif(($bj->vars->load[0] == '' or $bj->vars->load[0] == 'page') and $section['ID'] == load_option('default_section') and $section)
			return true;
	}
	else {
		if($bj->vars->load[0] == 'section' and $section['ID'] == $name and $section)
			return true;
		elseif(($bj->vars->load[0] == '' or $bj->vars->load[0] == 'page') and $name == load_option('default_section') and $section)
			return true;
	}
	return false;
}

#Function: is_archive()
function is_archive($id='') {
	if(is_404()) return false;
	global $bj,$section;
	if($id == '')
		if($bj->vars->load[0] == 'archive' and $section)
			return true;
	else
		if($bj->vars->load[0] == 'archive' and $id == $section['ID'])
			return true;	
	return false;
}

#Function: entry_in_section(sID)
function entry_in_section($id) {
	if(is_404()) return false;
	global $bj,$entry,$entries;
	if($entry and $entry['section'] == $id)
		return true;
	elseif(!$entry and $entries and is_entry())
		if($entries[0]['section'] == $id)
			return true;
	return false;
}	

#Function: is_tag()
function is_tag($name='') {
	if(is_404()) return false;
	global $bj,$tag;
	if($name == '')
		if($bj->vars->load[0] == 'tag')
			return true;
	else
		if($bj->vars->load[0] == 'tag' && $tag['name'] == $name)
			return true;
	return false;
}

function is_author($name='') {
	if(is_404()) return false;
	global $bj,$author;
	if($name == '')
		if($bj->vars->load[0] == 'author')
			return true;
	else
		if($bj->vars->load[0] == 'author' and $author['display_name'] == $name)
			return true;
	return false;
}

#Function: section_is_handled_by()
function section_is_handled_by($handler) {
	global $section;
	return ($section['handler'] == $handler);
}

#Function: is_search()
function is_search() {
	if(is_404()) return false;
	global $bj;
	if($bj->vars->load[0] == 'search' && $bj->vars->load[1] != '')
		return true;
	elseif(is_admin() and $_GET['req'] == 'search' and !empty($_GET['s']))
		return true;
	return false;
}

#Function: is_admin()
function is_admin() {
	if(is_404()) return false;
	global $admin_thisfile;
	if($admin_thisfile != '')
		return true;
	return false;
}

#Function: is_404()
function is_404() {
	global $four04;
	return ($four04);
}

#Function: commenting_is_disabled()
#Description: Checks if commenting is disabled. Can be used for entries only.
function commenting_is_disabled() {
	global $entry;
	return ($entry['comments_open'] == 0 or load_option('disable_commenting') == 1);
}

function rewrite_is_enabled() {
	return (defined('BJ_REWRITE') and !is_admin());
}

?>
