<?php skin_header(); ?>

			<div id="content">
				<div id="primary">
<?php
			if($posts) { ?>
					<div class="navigation">
					<?php prev_page_link(_r('&laquo; Newer Posts'),'<div class="alignleft">','</div>'); ?>
					<?php next_page_link(_r('Older Posts &raquo;'),'<div class="alignright">','</div>'); ?>
					</div>
<?php
				foreach($posts as $post) { start_post(); //$post should stay $post. Don't change. ?>

					<div class="<?php TO_post_class(); ?>" id="post-<?php echo_ID(); ?>">
						<div class="entry-head">
							<h2 class="entry-title"><a href="<?php echo_permalink(); ?>"><?php echo_title(); ?></a></h2>
							<div class="entry-meta">
								<span class="meta-date"><?php printf(_r('Published %1$s'),get_post_date()); ?></span>
								<span class="meta-comments"><?php comments_link(_r('0 <span>Comments</span>'),_r('1 <span>Comment</span>'),_r('% <span>Comments</span>'),_r('<span>Closed</span>')); ?></span>
								<span class="entry-edit"><?php edit_entry_link(_r('Edit')); ?></span>
								<span class="meta-tags"><?php _e('Tags:'); ?> <?php echo_tags(); ?></span>
							</div>
						</div>
						<div class="entry-content"><?php echo_content(); ?></div>
					</div>
<?php
				} ?>
					<div class="navigation">
					<?php prev_page_link(_r('&laquo; Newer Posts'),'<div class="alignleft">','</div>'); ?>
					<?php next_page_link(_r('Older Posts &raquo;'),'<div class="alignright">','</div>'); ?>
					</div>
<?php
			} ?>
				</div>
			</div>
<?php skin_footer(); ?>