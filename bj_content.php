<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

$__name_vars = explode('/',bj_clean_string($_GET['load']));
$__offset = ($__name_vars[2] == 'page') ? intval($__name_vars[3]) : 0;
if($__name_vars[2] == 'rss') {
	require('rss.php');
	die();
}
switch($__name_vars[0]) {
	case 'section' :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$__name_vars[1]."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = run_filters('qstring_section','type=public&section='.$section['ID'].'&offset='.$__offset);
		$entries = get_entries($query_string);
		
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '') {
			include(BJTEMPLATE .'/'.$section['handler']);
		}
		if(file_exists(BJTEMPLATE .'/section.php')) {
			include(BJTEMPLATE . '/section.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'archive' :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$__name_vars[1]."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		if(file_exists(BJTEMPLATE .'/archive.php')) {
			include(BJTEMPLATE . '/archive.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'entry' :
		$query_string = run_filters('qstring_entry','limit=1&shortname='.$__name_vars[1]);
		$entries = get_entries($query_string);
		if(!$entries) {
			load_404_instead();
		}
		if(file_exists(BJTEMPLATE . '/entry.php')) {
			include(BJTEMPLATE . '/entry.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'tag' :
		$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$__name_vars[1]."' LIMIT 1");
		if(!$tag) {
			load_404_instead();
		}
		$query_string = run_filters('qstring_tag','type=public&offset='.$__offset.'&tag='.$tag['ID']);
		$entries = get_entries($query_string);
		if(file_exists(BJTEMPLATE . '/tag-'.$tag['ID'].'.php')) {
			include(BJTEMPLATE . '/tag-'.$tag['ID'].'.php');
		}
		elseif(file_exists(BJTEMPLATE . '/tag.php')) {
			include(BJTEMPLATE . '/tag.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'author' :
		$authors = get_users(run_filters('qstring_author','equ=>=&group=2&f_name='.$__name_vars[1]));
		if(!$authors) {
			load_404_instead();
		}
		if(file_exists(BJTEMPLATE .'/author.php')) {
			include(BJTEMPLATE . '/author.php');
		}
		else {
			_e('Hey, you! Tell the webmaster to make an author.php in their theme so you can view this page!');
		}
		break;
	case 'search' :
		$query_string = run_filters('qstring_search','type=public&search='.$stext.'&offset='.$__offset);
		$entries = get_entries($query_string);
		if(file_exists(BJTEMPLATE .'/search.php')) {
			include(BJTEMPLATE .'/search.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	default :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `ID` = '".load_option('default_section')."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = run_filters('qstring_section','type=public&section='.$section['ID'].'&offset='.$__offset);
		$entries = get_entries($query_string);
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '') {
			include(BJTEMPLATE .'/'.$section['handler']);
		}
		if(file_exists(BJTEMPLATE .'/section.php')) {
			include(BJTEMPLATE . '/section.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
}

?>
