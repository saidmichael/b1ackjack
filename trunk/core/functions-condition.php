<?php

#Function: is_front()
function is_front() {
	if(is_404()) return false;
	global $__name_vars,$section;
	if($__name_vars[0] == 'section' && $section['ID'] == load_option('default_section')) {
		return true;
	}
	elseif(!isset($__name_vars[0])) {
		return true;
	}
	else {
		return false;
	}
}

#Function: is_entry()
function is_entry($woo='') {
	global $__name_vars,$entries;
	if(is_404()) return false;
	if($woo == '') {
		if($__name_vars[0] == 'entry') {
			return true;
		}
	}
	else {
		if($__name_vars[0] == 'entry') {
			foreach($entries as $entry) {
				 if($entry['ID'] == $woo) {
					return true;
				}
			}
		}
	}
}

#Function: is_section()
function is_section($name='') {
	if(is_404()) return false;
	global $__name_vars,$section;
	if($name == '') {
		if($__name_vars[0] == 'section') {
			return true;
		}
		elseif($__name_vars[0] == '' && $section['ID'] == load_option('default_section')) {
			return true;
		}
	}
	else {
		if($__name_vars[0] == 'section' && $section['ID'] == $name) {
			return true;
		}
		elseif($__name_vars[0] == '' && $name == load_option('default_section')) {
			return true;
		}
	}
}

#Function: is_archive_of(ID)
function is_archive_of($id) {
	global $__name_vars,$section;
	if($__name_vars[0] == 'archive' and $id == $section['ID']) {
		if($section['shortname'] == $__name_vars[1]) {
			return true;
		}
	}		
	return false;
}

#Function: entry_in_section(sID)
function entry_in_section($id) {
	global $__name_vars,$entry,$entries;
	if($entry and ($entry['section'] == $id)) {
		return true;
	}
	elseif(!$entry and $entries and $__name_vars[0] == 'entry') {
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
	global $__name_vars,$tag;
	if($name == '') {
		if($__name_vars[0] == 'tag') {
			return true;
		}
	}
	else {
		if($__name_vars[0] == 'tag' && $tag['name'] == $name) {
			return true;
		}
	}
	return false;
}

#Function: section_is_handled_by()
function section_is_handled_by($handler='') {
	if(is_404()) return false;
	global $section;
	if($handler != '' and $section['handler'] == $handler) {
		return true;
	}
	return false;
}

#Function: is_archive()
function is_archive($id=0) {
	if(is_404()) return false;
	global $section,$__name_vars;
	if($id == 0 and $__name_vars[0] == 'archive') {
		return true;
	}
	elseif($id != 0 and $__name_vars[0] == 'archive' and $section['ID'] == $id) {
		return true;
	}
	return false;
}

#Function: is_search()
function is_search() {
	if(is_404()) return false;
	global $__name_vars;
	if($__name_vars[0] == 'search' && $__name_vars[1] != '') {
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
