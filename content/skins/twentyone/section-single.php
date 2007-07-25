<?php
/*
 Handler: Single Post
 */
$hide_archive = 1;
skin_header(); ?>
			<div id="content">
				<div id="primary" class="section-single">
					<div class="page-head">
						<h2><?php echo wptexturize($section['title']); ?></h2>
					</div>
<?php
					foreach($entries as $entry) { thru_loop(); //$entry should stay $entry. Don't change. ?>
					<div class="entry-content">
						<?php entry_content(); ?>
					</div>
<?php
					} ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer();
die(); ?>
