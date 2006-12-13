<?php
$parent_file = "entries.php";
require("admin-head.php");
get_admin_header(); ?>
			<h2><?php _e('Write an Entry'); ?></h2>
<?php do_editorform(); ?>
<?php
get_admin_footer();
?>