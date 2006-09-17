<?php
$parent_file = "posts.php";
require("admin-head.php"); ?>
		<div id="wrapper">
			<h1><?php _e('Write a Post'); ?></h1>
<?php do_editorform(); ?>
		</div>
<?php
require("admin-foot.php");
?>