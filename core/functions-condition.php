<?php

#Function: is_front()
function is_front() {
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
		elseif($_GET['req'] == '' && $section['shortname'] == load_option('default_section')) {
			return true;
		}
	}
}

?>