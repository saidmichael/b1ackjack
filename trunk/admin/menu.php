<?php

$menu['index.php'] = array(_r('Moonbase'),1,'index.php');
$menu['sections.php'] = array(_r('Organize'),2,'sections.php');
$menu['entries.php'] = array(_r('Content'),1);
$menu['skins.php'] = array(_r('Look &amp; Feel'),3);
if(we_can('edit_users'))
	$menu['users.php'] = array(_r('Users'),3);
else
	$menu['profile.php'] = array(_r('Profile'),1);
$menu['settings.php'] = array(_r('Settings'),3);
$menu = run_actions('admin_menu',$menu);

$submenu['index.php'][] = array(_r('Front Page'),1,'index.php');
$submenu['sections.php'][] = array(_r('Sections'),2,'sections.php');
$submenu['sections.php'][] = array(_r('Tags'),2,'tags.php');
$submenu['entries.php'][] = array(_r('Manage'),1,'entries.php');
$submenu['entries.php'][] = array(_r('Write'),1,'entry-write.php');
$submenu['entries.php'][] = array(_r('Comments'),1,'comments.php');
$submenu['skins.php'][] = array(_r('Skins'),2,'skins.php');
#$submenu['skins.php'][] = array(_r('Skin Editor'),2,'skin-editor.php'); Maybe tomorrow.
if(we_can('edit_users')) {
	$submenu['users.php'][] = array(_r('Manage'),3,'users.php');
	$submenu['users.php'][] = array(_r('Profile'),1,'profile.php');
}
else
	$submenu['profile.php'][] = array(_r('Profile'),1,'profile.php');
$submenu['settings.php'][] = array(_r('Settings'),3,'settings.php');
$submenu['settings.php'][] = array(_r('Custom Fields'),3,'custom-fields.php');
$submenu = run_actions('admin_submenu',$submenu);

?>
