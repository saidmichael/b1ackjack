<?php
$parent_file = "sections.php";
require("admin-head.php");
get_admin_header(); ?>
			<h2><?php _e('Create a Section'); ?></h2>
<?php section_editor(); ?>
<?php
get_admin_footer();
?>