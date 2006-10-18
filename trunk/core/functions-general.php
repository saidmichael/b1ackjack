<?php

#Function: load_option(Option Name)
#Description: Loads an option's value.
function load_option($name) {
	global $bj_db;
	static $optionsbuffer = array();
	if(isset($optionsbuffer[$name])) {
		return $optionsbuffer[$name]['option_value'];
	}
	else {
		$option = $bj_db->get_item("SELECT `option_value` FROM `".$bj_db->options."` WHERE `option_name` = '".$name."' LIMIT 1","ASSOC");
		$optionsbuffer[$name] = $option;
		return $option['option_value'];
	}
}

#Function: siteinfo(Option Name)
#Description:Wrapper for get_siteinfo().
function siteinfo($name) {
	echo get_siteinfo($name);
}

#Function: get_siteinfo(Option Name)
#Description: A few choice options for the site.
function get_siteinfo($name) {
	switch($name) {
		case 'stylesheet' :
			return load_option('siteurl').'content/skins/'.current_skinname().'/style.css';
			break;
		case 'skinurl' :
			return load_option('siteurl').'content/skins/'.current_skinname().'/';
			break;
		case 'rss_language' :
			if(BJ_LANG != '') {
				return BJ_LANG;
			}
			else {
				return 'en-us';
			}
			break;
		default :
			return load_option($name);
	}
}

#Function: create_option(Option Name[, Value[, Description]])
#Description: Creates an option.
function create_option($name,$value='',$description='') {
	global $bj_db;
	$bj_db->query("INSERT INTO `".$bj_db->options."` (`option_name`,`option_value`,`option_description`) VALUES ('".$name."','".$value."','".$description."');");
}

#Function: update_option(Option Name[, Value[, Description]])
#Description: Updates an option. It will also create it if the option
#			  doesn't exist.
function update_option($name,$value='',$description='') {
	global $bj_db;
	if(!load_option($name)) {
		create_option($name,$value,$description);
	} else {
		$bj_db->query("UPDATE `".$bj_db->options."` SET `option_value` = '".$value."', `option_description` = '".$description."' WHERE `option_name` = '".$name."' LIMIT 1;");
	}
}

#Function: delete_option(Option Name)
#Description: Deletes an option.
function delete_option($name) {
	global $bj_db;
	$bj_db->query("DELETE FROM `".$bj_db->options."` WHERE `option_name` = '".$name."' LIMIT 1");
}

#Function: basename_withpath(Path)
#Description: Grabs the filename. Useful if you want to strip the query string.
function basename_withpath($url) {
	$path = explode("?",$url);
	return basename($path[0]);
}

#Function: bj_sendmail(Sending To, Subject, Body[, From[, Content Type[, Charset]]]
#Description: Needs to be created.
		

?>