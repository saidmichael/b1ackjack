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