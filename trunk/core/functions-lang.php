<?php

if(BJ_LANG != "") {
	if(file_exists(BJPATH."content/langs/".BJ_LANG.".php")) {
		require BJPATH."content/langs/".BJ_LANG.".php";
	}
	foreach(return_plugins() as $plugin=>$null) {
		if(file_exists(BJPATH."content/langs/plug_ext/".urlencode($plugin)."-".BJ_LANG.".php")) {
			require BJPATH."content/langs/plug_ext/".urlencode($plugin)."-".BJ_LANG.".php";
		}
	}
}

#Function: _e(Text)
#Description: Prints based upon a language.
function _e($text) {
	echo _r($text);
}

#Function: _r(Text)
#Description: Returns based upon a language.
function _r($text) {
	global $lang;
	if(isset($lang[$text])) {
		return $lang[$text];
	}
	else {
		return $text;
	}
}

?>