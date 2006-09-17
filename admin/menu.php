<?php

$menu[] = array(_r('Front Page'),1,'index.php');
$menu[] = array(_r('Posts'),2,'posts.php');
$menu[] = array(_r('Sections'),3,'sections.php');
$menu[] = array(_r('Comments'),2,'comments.php');
$menu[] = array(_r('Look &amp; Feel'),4,'themes.php');
$menu[] = array(_r('Options'),4,'options.php');

$submenu['posts.php'][] = array(_r('Manage'),2,'posts.php');
$submenu['posts.php'][] = array(_r('Write'),2,'post-write.php');
$submenu['sections.php'][] = array(_r('Manage'),2,'sections.php');
$submenu['sections.php'][] = array(_r('Create'),2,'section-create.php');
$submenu['comments.php'][] = array(_r('Manage'),2,'comments.php');
$submenu['comments.php'][] = array(_r('In Moderation'),2,'comments-mod.php');

?>
		<ul id="menu">
<?php
foreach($menu as $item) {
	if($user->user_group >= $item[1]) { ?>
			<li<?php echo ($parent_file == $item[2]) ? " class=\"active\"" : ""; ?>><a href="<?php echo $item[2]; ?>"><?php echo $item[0]; ?></a></li>
<?php
	}
}
?>
		</ul>
		<ul id="submenu">
<?php
if(isset($submenu[$parent_file])) {
	foreach($submenu[$parent_file] as $subitem) {
		if($user->user_group >= $subitem[1]) { ?>
			<li<?php echo ($admin_thisfile == $subitem[2]) ? " class=\"active\"" : ""; ?>><a href="<?php echo $subitem[2]; ?>"><?php echo $subitem[0]; ?></a></li>
<?php
		}
	}
}
?>
		</ul>