<?php skin_header(); ?>
<?php
			if($posts) { ?>
			<div class="navigation">
			<?php prev_page_link(_r('&laquo; Newer'),'<div class="alignleft">','</div>'); ?>
			<?php next_page_link(_r('Older &raquo;'),'<div class="alignright">','</div>'); ?>
			</div>
<?php
				foreach($posts as $post) { start_post(); //$post should stay $post. Don't change. ?>

			<div class="post" id="post-<?php echo_ID(); ?>">
				<div class="entry-head">
					<h2 class="entry-title"><a href="<?php echo_permalink(); ?>"><?php echo_title(); ?></a></h2>
					<div class="entry-meta">
						<span class="meta-date"><?php printf(_r('Published %1$s'),get_post_date()); ?></span>
						<span class="meta-comments"></span>
					</div>
				</div>
				<div class="entry-content"><?php echo_content(); ?></div>
			</div>
<?php
				}
			} ?>
<?php skin_footer(); ?>