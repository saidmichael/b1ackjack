<?php skin_header(); ?>

			<div id="content">
				<div id="primary">
<?php
			require BJTEMPLATE.'/foreach.php';
			comments_template(); ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer(); ?>
