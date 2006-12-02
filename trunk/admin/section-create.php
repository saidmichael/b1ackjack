<?php
$parent_file = "sections.php";
require("admin-head.php");
get_admin_header(); ?>
		<div id="wrapper">
			<h1><?php _e('Create a Section'); ?></h1>
<?php section_editor(); ?>
		</div>
<?php
get_admin_footer();
?>