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
		
		if(file_exists(BJTEMPLATE .'/section.php')) {
			include(BJTEMPLATE . '/section.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'entry' :
		$query_string = 'limit=1&shortname='.$getname;
		$posts = get_posts($query_string);
		if(!$posts) {
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
		$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$getname."' LIMIT 1");
		if(!$tag) {
			load_404_instead();
		}
		$query_string = 'offset='.$offset.'&tag='.$tag['ID'];
		$posts = get_posts($query_string);
		if(file_exists(BJTEMPLATE . '/tag.php')) {
			include(BJTEMPLATE . '/tag.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	default :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".load_option('default_section')."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		if($section['onepost'] != '') {
			$query_string = 'shortname='.$section['onepost'].'&limit=1';
			$posts = get_posts($query_string);
		}
		else {
			$query_string = 'offset='.$offset;
			if(!empty($section['tags'])) { # Are we filtering by any tags?
				$query_string .= '&tag='.$section['tags'];
			}
			$posts = get_posts($query_string);
		}
		include(BJTEMPLATE . '/index.php');
}

?>