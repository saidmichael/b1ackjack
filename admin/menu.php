<?php

$menu[] = array(_r('Front Page'),1,'index.php');
$menu[] = array(_r('Sections'),3,'sections.php');
$menu[] = array(_r('Tags'),3,'tags.php');
$menu[] = array(_r('Entries'),2,'entries.php');
$menu[] = array(_r('Look &amp; Feel'),4,'skins.php');
$menu[] = array(_r('Options'),4,'options.php');
$menu = run_filters('admin_menu',$menu);

$submenu['entries.php'][] = array(_r('Manage'),2,'entries.php');
$submenu['entries.php'][] = array(_r('Write'),2,'entry-write.php');
$submenu['entries.php'][] = array(_r('Comments'),2,'comments.php');
$in_mod = mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->comments."` WHERE `status` = 'hidden'"));
$submenu['entries.php'][] = array(sprintf(_r('Moderation (%1$s)'),$in_mod),2,'comments-mod.php');
$submenu['sections.php'][] = array(_r('Manage'),2,'sections.php');
$submenu['sections.php'][] = array(_r('Create'),2,'section-create.php');
$submenu['comments.php'][] = array(_r('Blacklisted'),2,'comments-blacklist.php');
$submenu = run_filters('admin_submenu',$submenu);

?>