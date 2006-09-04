<?php

require_once("../bj_config.php");

$admin_thisfile = (basename_withpath($_SERVER['REQUEST_URI']) == "admin") ? "index.php" : basename_withpath($_SERVER['REQUEST_URI']);

require("admin-functions.php");

validate_session();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _e('Blackjack Admin Panel'); ?></title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" />
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		tinyMCE.init({
			theme : "advanced",
			mode : "exact",
			elements : "textarea",
			extended_valid_elements : "a[href|target|name]",
			//plugins : "table",
			//theme_advanced_buttons1_add_before : "tablecontrols,separator",
			//theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", Theme specific setting CSS classes
			debug : false,
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resize_horizontal : false,
			theme_advanced_resizing : true
		});
		</script>
		<!-- /tinyMCE -->
	</head>
	<body>
		<div id="top">
			<div class="alignleft userinfo">
				<strong><?php echo load_option('sitename'); ?></strong> - <?php printf(_r('Logged in as <a href="profile.php">%1$s</a>'),$user->display_name); ?>
			</div>
			<div class="alignright lightpart">
				<a href="profile.php" class="profile"><?php _e('Profile'); ?></a>
				<a href="login.php?req=logout" class="logout"><?php _e('Logout'); ?></a>
			</div>
			<div class="c"></div>
		</div>
<?php
require("menu.php");
?>
