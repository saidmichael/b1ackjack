<?php skin_header(); ?>

			<div id="content">
				<div id="primary">
<?php
			if($posts) {
				require BJTEMPLATE.'/foreach.php';
			}
			else { ?>
					<div class="page-head">
						<h2><?php _e('Not Found'); ?></h2>
					</div>
					<div class="entry-content">
						<p><?php _e('Looks like you\'re out of luck: whatever you were looking for isn\'t here. Luckily, there are tons of tools around the website to help you find what you seek- in fact, a good deal of which are right on this page.'); ?></p>
					</div>
<?php
			} ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer(); ?>