<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

$offset = intval($_GET['offset']);
$getname = bj_clean_string($_GET['name'],array(),'mysql=true');
switch($_GET['req']) {
	case 'section' :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$getname."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = 'offset='.$offset;
		if(!empty($section['tags'])) { # Are we filtering by any tags?
			$query_string .= '&tag='.$section['tags'];
		}
		$posts = get_posts($query_string);
		
		if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/section.php')) {
			include(BJPATH . 'content/skins/' . current_skinname() . '/section.php');
		}
		else {
			include(BJPATH . 'content/skins/' . current_skinname() . '/index.php');
		}
		break;
	case 'entry' :
		$query_string = 'limit=1&shortname='.$getname;
		$posts = get_posts($query_string);
		if(!$posts) {
			load_404_instead();
		}
		if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/entry.php')) {
			include(BJPATH . 'content/skins/' . current_skinname() . '/entry.php');
		}
		else {
			include(BJPATH . 'content/skins/' . current_skinname() . '/index.php');
		}
		break;
	case 'tag' :
		$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$getname."' LIMIT 1");
		if(!$tag) {
			load_404_instead();
		}
		$query_string = 'offset='.$offset.'&tag='.$tag['ID'];
		$posts = get_posts($query_string);
		if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/tag.php')) {
			include(BJPATH . 'content/skins/' . current_skinname() . '/tag.php');
		}
		else {
			include(BJPATH . 'content/skins/' . current_skinname() . '/index.php');
		}
		break;
	default :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".load_option('default_section')."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = 'offset='.$offset;
		if(!empty($section['tags'])) { # Are we filtering by any tags?
			$query_string .= '&tag='.$section['tags'];
		}
		$posts = get_posts($query_string);
		include(BJPATH . 'content/skins/' . current_skinname() . '/index.php');
}

?>