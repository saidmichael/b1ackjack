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

#Function: skin_header()
#Description: Skin header. Fool.
function skin_header() {
	global $bj_db,$bj_version;
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/header.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/header.php');
	}
}

#Function: skin_footer()
#Description: Skin footer. Fool.
function skin_footer() {
	global $bj_db,$bj_version;
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/footer.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/footer.php');
	}
}

#Function: get_sidebar()
#Description: Displays sidebar1 (if it exists).
if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/sidebar.php')) :
function skin_sidebar() {
	include(BJPATH . 'content/skins/' . current_skinname() . '/sidebar.php');
}
endif;

#Function: get_sidebar2()
#Description: Displays sidebar2 (if it exists).
if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/sidebar2.php')) :
function skin_sidebar2() {
	include(BJPATH . 'content/skins/' . current_skinname() . '/sidebar2.php');
}
endif;

#Function: load_404_instead()
#Description: Loads the 404 template.
function load_404_instead() {
	global $section,$tag,$four04;
	unset($section,$tag);
	$four04 = true;
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/404.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/404.php');
	}
	else {
		include(BJPATH . 'content/skins/' . current_skinname() . '/index.php');
	}
	die();
}

?>