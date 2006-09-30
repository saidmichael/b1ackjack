<?php
$parent_file = "posts.php";
require("admin-head.php");
get_admin_header(); ?>
		<div id="wrapper">
			<h1><?php _e('Write a Post'); ?></h1>
<?php do_editorform(); ?>
		</div>
<?php
get_admin_footer();
?>