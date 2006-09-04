<?php


#Function: validate_session(Reverse)
#Description: Checks if the user is logged into the admin panel.
#			  If not, you get redirected to the login screen.
#			  Reverse is for the login page.
function validate_session($reverse=false) {
	global $user,$admin_thisfile;
	if($reverse == true) {
		if(isset($user->ID)) {
			@header("Location: ".load_option('siteurl')."admin/index.php");
		}
	}
	else {
		if(!isset($user->ID)) {
			@header("Location: ".load_option('siteurl')."admin/login.php?redir=".urlencode($admin_thisfile.(isset($_SERVER['QUERY_STRING'])) ? urlencode($admin_thisfile."?".$_SERVER['QUERY_STRING']) : ""));
			die();
		}
	}
}

#Function: fancy_altrows(Content[, Args])
#Description: Allows for stylized lists, because we're too lazy to
#			  just do them by hand.
function fancy_altrows($rows) {
	if(is_array($rows)) {
		$i = 0;
		foreach($rows as $text=>$url) { ?>
					<li<?php tablealt($i); ?>><?php if($url != "") { ?><a href="<?php echo $url; ?>"><?php echo $text; ?></a><?php }else{ echo $text; } ?></li>
<?php		$i++;
		}
	}
}

#Function tablealt()
#Description: Just provides a class="alt" for the table row.
function tablealt($i) {
	echo ($i%2 == 0) ? "" : " class=\"alt\"";
}

?>