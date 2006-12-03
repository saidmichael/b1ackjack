<?php
$parent_file = "posts.php";
require("admin-head.php");
get_admin_header(); ?>
			<h2><?php _e('Write a Post'); ?></h2>
<?php do_editorform(); ?>
<?php
get_admin_footer();
?>