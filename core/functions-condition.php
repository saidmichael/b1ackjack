<?php

#Function: is_front()
function is_front() {
	if(is_404()) return false;
	if($_GET['req'] == 'section' && $_GET['name'] == load_option('default_section')) {
		return true;
	}
	elseif(!isset($_GET['req'])) {
		return true;
	}
	else {
		return false;
	}
}

#Function: is_entry()
function is_entry($woo='') {
	if(is_404()) return false;
	if($woo == '') {
		if($_GET['req'] == 'entry') {
			return true;
		}
	}
	else {
		if($_GET['req'] == 'entry' && $_GET['name'] == $woo) {
			return true;
		}
	}
}

#Function: is_section()
function is_section($name='') {
	if(is_404()) return false;
	global $section;
	if($name == '') {
		if($_GET['req'] == 'section') {
			return true;
		}
	}
	else {
		if($_GET['req'] == 'section' && $_GET['name'] == $name) {
			return true;
		}
		elseif($_GET['req'] == '' && $name == load_option('default_section')) {
			return true;
		}
	}
}

#Function: entry_in_section(sID)
function entry_in_section($id) {
	global $entry,$entries;
	if($entry and ($entry['section'] == $id)) {
		return true;
	}
	elseif(!$entry and $entries and $_GET['req'] == 'entry') {
		foreach($entries as $entry) {
			if($entry['section'] == $id) {
				return true;
			}
		}
	}
	return false;
}	

#Function: is_tag()
function is_tag($name='') {
	if(is_404()) return false;
	global $tag;
	if($name == '') {
		if($_GET['req'] == 'tag') {
			return true;
		}
	}
	else {
		if($_GET['req'] == 'tag' && $_GET['name'] == $name) {
			return true;
		}
	}
}

#Function: is_search()
function is_search() {
	if(is_404()) return false;
	if($_GET['req'] == 'search' && $_GET['s'] != '') {
		return true;
	}
}

#Function: is_admin()
function is_admin() {
	if(is_404()) return false;
	global $admin_thisfile;
	if($admin_thisfile != '') {
		return true;
	}
}

#Function: is_404()
function is_404() {
	global $four04;
	if($four04) {
		return true;
	}
}

?>