<?php
/*
 Template Name: Single Post
 */
?>
<?php skin_header(); ?>

			<div id="content">
				<div id="primary" class="section-single">
<?php
			$single = 1;
			require BJTEMPLATE.'/foreach.php'; ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer(); ?>