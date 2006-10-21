<?php

function gravatar($email=false,$rating='PG',$size = 32,$default=false,$border=false) {
	$cach_location = get_siteinfo('siteurl').'content/cache/gravatars/'.md5($email).','.$size.'.png';
	$cach_path = BJPATH.'content/cache/gravatars/'.md5($email).','.$size.'.png';
	if($email) {
		$url_location = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($email)."&rating=".$rating."&size=".$size;
		if($default) {
			$url_location .= "&default=".urlencode($default);
		}
		if($border) {
			$url_location .= "&border=".$border;
		}
	}
	else {
		return false;
	}
		
	if(!file_exists($cach_path)) {
		$secondstomodify = 604801; //Make it greater to it will create a cache.
	}
	else {
		$secondstomodify = time() - filemtime($cach_path);
	}

	if(!file_exists($cach_path) || $secondstomodify > 604800) { //The time is seven days.
		@copy($url_location,$cach_path);
	}
	echo "
	<a href=\"http://gravatar.com\" title=\""._r('What is this?')."\">
	<img
		src=\"".$cach_location."\"
		alt=\"Gravatar\"
		class=\"gravatar\"
	/>
	</a>";
}

?>