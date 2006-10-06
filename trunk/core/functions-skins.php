<?php

#Function: get_usable_skins()
#Description: Returns an array filled with the available skins.
function get_usable_skins() {
	$all_skins = array();
	foreach(glob(BJPATH . 'content/skins/*') as $skin) {
		if(is_dir($skin)) {
			$skin_dirname = end(explode("/",$skin));
			foreach(glob($skin.'/{*.css,*.php}', GLOB_BRACE) as $file) {
				$handle = fopen($file,"r");
				$all_skins[$skin_dirname][end(explode("/",$file))] = fread($handle,filesize($file));
				fclose($handle);
			}
			# Is this theme missing some required files?
			if(!isset($all_skins[$skin_dirname]['style.css'])
				|| !isset($all_skins[$skin_dirname]['index.php'])) {
				unset($all_skins[$skin_dirname]);
			}
		}
	}
	print_r($all_skins);
}

#Function: current_skinname()
#Description: Returns the active skin name.
function current_skinname() {
	$sname = load_option('current_skin');
	if(!is_dir(BJPATH . 'content/skins/' . $sname)
		|| !file_exists(BJPATH . 'content/skins/' . $sname . '/index.php')
		|| !file_exists(BJPATH . 'content/skins/' . $sname . '/style.css')) {
		$sname = 'twentyone';
	}
	return $sname;
}

#Function: skin_load(Template)
#Description: Loads a skin file.
function skin_load($load='index') {
	global $bj_db;
	$name = current_skinname();
	$getname = bj_clean_string($_GET['name'],array(),'mysql=true');
	$offset = (isset($_GET['offset'])) ? intval($_GET['offset']) : 0;
	switch($load) {
		case 'section' :
			$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$getname."' LIMIT 1");
			$query_string = 'offset='.$offset;
			if(!empty($section['tags'])) { # Are we filtering by any tags?
				$query_string .= '&tag='.$section['tags'];
			}
			$posts = get_posts($query_string);
			
			if(file_exists(BJPATH . 'content/skins/' . $name . '/section.php')) {
				include(BJPATH . 'content/skins/' . $name . '/section.php');
			}
			else {
				include(BJPATH . 'content/skins/' . $name . '/index.php');
			}
			break;
			
		case 'entry' :
			$query_string = 'limit=1&shortname='.$getname;
			$posts = get_posts($query_string);
			
			if(file_exists(BJPATH . 'content/skins/' . $name . '/entry.php')) {
				include(BJPATH . 'content/skins/' . $name . '/entry.php');
			}
			else {
				include(BJPATH . 'content/skins/' . $name . '/index.php');
			}
			break;
			
		case 'tag' :
			$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$getname."' LIMIT 1");
			$query_string = 'offset='.$offset.'&tag='.$tag['ID'];
			$posts = get_posts($query_string);
			
			if(file_exists(BJPATH . 'content/skins/' . $name . '/tag.php')) {
				include(BJPATH . 'content/skins/' . $name . '/tag.php');
			}
			else {
				include(BJPATH . 'content/skins/' . $name . '/index.php');
			}
			break;
		default :
			$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".load_option('default_section')."' LIMIT 1");
			$query_string = 'offset='.$offset;
			if(!empty($section['tags'])) { # Are we filtering by any tags?
				$query_string .= '&tag='.$section['tags'];
			}
			$posts = get_posts($query_string);
			
			include(BJPATH . 'content/skins/' . $name . '/index.php');
	}
}

#Function: skin_header()
#Description: Skin header. Fool.
function skin_header() {
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/header.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/header.php');
	}
}

#Function: skin_footer()
#Description: Skin footer. Fool.
function skin_footer() {
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/footer.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/footer.php');
	}
}

?>