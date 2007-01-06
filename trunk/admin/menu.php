<?php

$menu[] = array(_r('Front Page'),1,'index.php');
$menu[] = array(_r('Organize'),3,'sections.php');
$menu[] = array(_r('Entries'),2,'entries.php');
$menu[] = array(_r('Look &amp; Feel'),4,'skins.php');
if(we_can('edit_users')) {
	$menu[] = array(_r('Users'),4,'users.php');
}
else {
	$menu[] = array(_r('Profile'),1,'profile.php');
}
$menu[] = array(_r('Options'),4,'options.php');
$menu = run_filters('admin_menu',$menu);

$submenu['index.php'][] = array(_r('Front Page'),1,'index.php');
$submenu['sections.php'][] = array(_r('Sections'),3,'sections.php');
$submenu['sections.php'][] = array(_r('Tags'),3,'tags.php');
$submenu['entries.php'][] = array(_r('Manage'),2,'entries.php');
$submenu['entries.php'][] = array(_r('Write'),2,'entry-write.php');
$submenu['entries.php'][] = array(_r('Comments'),2,'comments.php');
$in_mod = mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->comments."` WHERE `status` = 'hidden'"));
$submenu['entries.php'][] = array(sprintf(_r('Moderation (%1$s)'),$in_mod),2,'comments-mod.php');
$submenu['skins.php'][] = array(_r('Skins'),3,'skins.php');
$submenu['skins.php'][] = array(_r('Skin Editor'),3,'skin-editor.php');
if(we_can('edit_users')) {
	$submenu['users.php'][] = array(_r('Manage'),4,'users.php');
	$submenu['users.php'][] = array(_r('Profile'),1,'profile.php');
}
else {
	$submenu['profile.php'][] = array(_r('Profile'),1,'profile.php');
}
$submenu = run_filters('admin_submenu',$submenu);

?>
