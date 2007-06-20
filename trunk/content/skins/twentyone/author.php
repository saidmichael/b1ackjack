<?php skin_header(); ?>

			<div id="content">
				<div id="primary">
<?php
			foreach($authors as $author) { ?>
					<div class="page-head">
						<h2><?php printf(_r('<span>Author: </span>%1$s'),get_author_name()); ?></h2>
					</div>
					<div class="entry-content">
<?php
				if(get_author_desc() == '') { ?>
						<p><?php _e('This user has provided no biographical data.'); ?></p>
<?php
				}
				else {
					author_desc();
				} ?>
					</div>
<?php
			} ?>
				</div>
			</div>
<?php skin_sidebar(); ?>
<?php skin_footer(); ?>
