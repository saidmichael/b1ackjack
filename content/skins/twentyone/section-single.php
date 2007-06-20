<?php
/*
 Handler: Single Post
 */
$hide_archive = 1;
?>
<?php skin_header(); ?>

			<div id="content">
				<div id="primary" class="section-single">
<?php
				if($entries) { ?>
					<div class="page-head">
						<h2><?php
						if(is_tag()) {
							printf(_r('<span>Tag Archive: </span>%1$s'),wptexturize($tag['name']));
						}
						elseif(is_section()) {
							echo wptexturize($section['title']);
						} ?></h2>
					</div>
<?php
					foreach($entries as $entry) { start_entry(); //$entry should stay $entry. Don't change. ?>
					<div class="entry-content">
						<?php echo_content(); ?>
					</div>
<?php
					}
				}
				else { ?>
					<div class="page-head">
						<h2><?php _e('Not Found'); ?></h2>
					</div>
					<div class="entry-content">
						<p><?php _e('Looks like you\'re out of luck: whatever you were looking for isn\'t here. Luckily, there are tons of tools around the website to help you find what you seek- a good deal of which, in fact, are right on this page.'); ?></p>
					</div>
<?php
				} ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer();
die(); ?>
