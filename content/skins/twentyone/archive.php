<?php skin_header(); ?>
			<div id="content">
				<div id="primary">
					<div class="page-head">
						<h2><?php printf(_r('Archive for %1$s'),$section['title']); ?></h2>
					</div>
<?php get_archive(); ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer(); ?>
