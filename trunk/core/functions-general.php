<?php

function FileFolderList($path, $depth = 0, $current = '', $level=0) {
	if ($level==0 && !@file_exists($path))
		return false;
	if (is_dir($path)) {
		$handle = @opendir($path);
		if ($depth == 0 || $level < $depth)
			while($filename = @readdir($handle))
				if ($filename != '.' && $filename != '..')
					$current = @FileFolderList($path.'/'.$filename, $depth-1, $current, $level+1);
		@closedir($handle);
		$current['folders'][] = $path.'/'.$filename;
	} else
		if (is_file($path))
			$current['files'][] = $path;
	return $current;
}

#Function: parse_file_info(File Path[, Names])
#Description: Parses a file's text, searching for certain descriptions in
#			  this format: Name: Value.
function parse_file_info($file,$names=array()) {
	$data = array();
	$filetext = file_get_contents($file);
	foreach ($names as $name) {
		$new_name = array();
		preg_match("{".$name.":(.*)}i", $filetext, $new_name);
		$data[$name] = trim($new_name[1]);
	}
	return $data;
}

#Function: preg_in_array(Pattern,Array)
#Description: Originally by mattsch at gmail dot com (posted at php.net).
function preg_in_array($strPattern, $arrInput){
	$arrReturn = preg_grep($strPattern, $arrInput);
	return (count($arrReturn)) ? true : false;
}

#Function: maybe_unserialize(Possible Serialized Item)
#Descriptipn: Returns an unserialized item if success, or the string on failure.
function maybe_unserialize($possible) {
	if(false === @unserialize($possible))
		return $possible;
	else
		return unserialize($possible);
}

#Function: maybe_serialize(Possible Unserialized Item)
#Descriptipn: Returns an serialized item if success, or the string on failure.
function maybe_serialize($possible) {
	if(is_object($possible) or is_array($possible))
		return serialize($possible);
	else
		return $possible;
}

#Function: siteinfo(Option Name)
#Description: A few choice options for the site.
function siteinfo($name) {
	echo get_siteinfo($name);
}
function get_siteinfo($name) {
	global $section,$tag,$entries,$entry,$bj;
	switch($name) {
	case 'siteurl' :
		return $bj->domain.$bj->path;
		break;
	case 'adminurl' :
		return get_siteinfo('siteurl').ADMIN_DIR.'/';
		break;
	case 'stylesheet' :
		if(is_section() or is_archive($section['ID']))
			return section_stylesheet();
		else
			return get_siteinfo('static_stylesheet');
		break;
	case 'static_stylesheet' :
		return get_siteinfo('siteurl').'content/skins/'.current_skinname().'/style.css';
		break;
	case 'skinurl' :
		return get_siteinfo('siteurl').'content/skins/'.current_skinname().'/';
		break;
	case 'feedurl' :
		if(defined('BJ_REWRITE'))
			$extraurl = '';
		else
			$extraurl = '?load=';
		if(is_section() or is_archive())
			$prefix = 'section';
		elseif(is_entry())
			$prefix = 'entry';
		elseif(is_tag())
			$prefix = 'tag';
		if($prefix == 'entry')
			foreach($entries as $entry)
				$item = $entry;
		else
			$item = $$prefix;
		return get_siteinfo('siteurl').$extraurl.$prefix.'/'.$item['shortname'].'/rss';
		break;
	case 'rss_language' :
		return BJ_LANG;
		break;
	default :
		return load_option($name);
	}
}

function load_option($name) {
	global $bj;
	$options = $bj->cache->get_options();
	if(isset($options[$name]))
		return maybe_unserialize($options[$name]);
	return false;
}

function option_exists($name) {
	global $bj;
	$options = $bj->cache->get_options();
	if(isset($options[$name]))
		return true;
	return false;
}

function blackjack_is_installed() {
	global $bj;
	$bj->db->hide_errors();
	$check = $bj->db->query('SELECT * FROM '.$bj->db->options.' WHERE option_name = \'sitename\' LIMIT 1');
	$bj->db->show_errors();
	return $check;
}

function create_option($name,$value='') {
	global $bj;
	$bj->db->query("INSERT INTO `".$bj->db->options."` (`option_name`,`option_value`) VALUES ('".$name."','".run_actions('option_create_'.$name,maybe_serialize($value))."');");
	$bj->cache->drop_cache('options');
}

function update_option($name,$value='') {
	global $bj;
	if(option_exists($name)) {
		$bj->db->query("UPDATE `".$bj->db->options."` SET `option_value` = '".run_actions('option_update_'.$name,maybe_serialize($value))."' WHERE `option_name` = '".$name."' LIMIT 1;");
		$bj->cache->drop_cache('options');
	}
	else
		create_option($name,$value,$description);
}

#Function: delete_option(Option Name)
#Description: Deletes an option.
function delete_option($name) {
	global $bj;
	$bj->db->query("DELETE FROM `".$bj->db->options."` WHERE `option_name` = '".$name."' LIMIT 1");
}

#Function: add_submenu_page(Tab Name, File Name, URL Hook, Function Hook, User Level)
#Description: Adds a submenu page to any file. Useful for plugins.
function add_submenu_page($tabname,$filename,$urlhook,$funchook,$ulevel,$submenu) {
	global $menu;
	if(isset($menu[$filename])) {
		if(count($submenu[$filename]) < 2)
			$submenu[$filename][] = array(_r($menu[$filename][0]),$menu[$filename][1],$filename);
		$submenu[$filename][] = array(_r($tabname),$ulevel,$filename,$urlhook);
		$submenu['plugs'][$urlhook] = array(
			'file' => $filename,
			'hook' => $funchook);
	}
	return $submenu;
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
