<?php

#Function: get_usable_skins()
#Description: Returns an array filled with the available skins.
function get_usable_skins($default=false) {
	$all_skins = array();
	foreach(glob(BJPATH . 'content/skins/*') as $skin) {
		if(is_dir($skin)) {
			$skin_dirname = end(explode("/",$skin));
			foreach(glob($skin.'/{*.css,*.php,*.png}', GLOB_BRACE) as $file) {
				$all_skins[$skin_dirname][basename($file)] = true;
			}
			# Is this theme missing some required files?
			if(!isset($all_skins[$skin_dirname]['style.css'])
				or !isset($all_skins[$skin_dirname]['index.php'])) {
				unset($all_skins[$skin_dirname]);
			}
		}
	}
	if(!$default) {
		unset($all_skins[current_skinname()]);
	}
	return $all_skins;
}

#Function: current_skinname()
#Description: Returns the active skin name.
function current_skinname() {
	$sname = load_option('current_skin');
	if(!is_dir(BJPATH . 'content/skins/' . $sname)
		or !file_exists(BJPATH . 'content/skins/' . $sname . '/index.php')
		or !file_exists(BJPATH . 'content/skins/' . $sname . '/style.css')) {
		$sname = 'twentyone';
	}
	return $sname;
}

#Function: skin_header()
#Description: Skin header. Fool.
function skin_header() {
	global $bj_db,$bj_version,$entries,$entry,$getname,$name_vars,$section,$tag;
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/header.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/header.php');
	}
}

#Function: skin_footer()
#Description: Skin footer. Fool.
function skin_footer() {
	global $bj_db,$bj_version,$entries,$entry,$getname,$name_vars,$section,$tag;
	if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/footer.php')) {
		include(BJPATH . 'content/skins/' . current_skinname() . '/footer.php');
	}
}

#Function: get_sidebar()
#Description: Displays sidebar1 (if it exists).
if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/sidebar.php')) :
function skin_sidebar() {
	global $entries,$entry,$comments,$comment,$getname,$name_vars,$section,$tag;
	include(BJPATH . 'content/skins/' . current_skinname() . '/sidebar.php');
}
endif;

#Function: get_sidebar2()
#Description: Displays sidebar2 (if it exists).
if(file_exists(BJPATH . 'content/skins/' . current_skinname() . '/sidebar2.php')) :
function skin_sidebar2() {
	global $entries,$entry,$comments,$comment,$getname,$name_vars,$section,$tag;
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

#Function: bj_save_skin()
#Description: Saves the skin file.
function bj_save_skin() {
	if($_POST['skin-edit-uniqid'] and $_POST['skin-edit-file']) {
		$handle = fopen(BJPATH.'content/skins/'.bj_clean_string($_POST['skin-edit-uniqid']).'/'.bj_clean_string($_POST['skin-edit-file']),'w');
		if(stripslashes($_POST['content']) == '') {
			$content = '
'; #fread error protector.
		}
		else {
			$content = stripslashes($_POST['content']);
		}
		fwrite($handle,$content);
		fclose($handle);
	}
}

#Function: bj_skin_newfile()
#Description: Creates a File
function bj_skin_newfile($inline=false) {
	if($_GET['req'] == 'ajaxadd' and !$inline) {
		return false;
	}
	if($_POST['skin-newfile-file'] and $_POST['skin-newfile-skin']) {
		$handle = fopen(BJPATH.'content/skins/'.bj_clean_string($_POST['skin-newfile-skin']).'/'.bj_clean_string($_POST['skin-newfile-file']),'w+');
		fwrite($handle,'
');
		fclose($handle);
		if($inline) {
			return array('name'=>bj_clean_string($_POST['skin-newfile-file']),'skin'=>bj_clean_string($_POST['skin-newfile-skin']));
		}
	}
}

?>
