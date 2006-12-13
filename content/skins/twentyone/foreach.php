<?php
				if($entries) {
					#If there's more than one user, output the user.
					$mtou = (get_users('gop=>=&group=2&num=yes') > 1) ? true : false; ?>
					<div class="navigation">
					<?php prev_page_link(_r('&laquo; Newer Posts'),'<div class="alignleft">','</div>'); ?>
					<?php next_page_link(_r('Older Posts &raquo;'),'<div class="alignright">','</div>'); ?>
					</div>
<?php
					if(!is_entry()) { ?>
					<div class="page-head">
						<h2><?php
						if(is_tag()) {
							printf(_r('<span>Tag Archive: </span>%1$s'),wptexturize($tag['name']));
						}
						elseif(is_section() or is_front()) {
							printf(_r('<span>Browsing Section: </span>%1$s'),wptexturize($section['title']));
						} ?></h2>
					</div>
<?php
					}
					foreach($entries as $entry) { start_entry(); //$entry should stay $entry. Don't change.
						if($single) { ?>
					<div class="entry-content">
						<?php echo_content(); ?>
					</div>
<?php
						}
						else { ?>
					<div class="<?php TO_post_class(); ?>" id="post-<?php echo_ID(); ?>">
						<div class="entry-head">
							<h3 class="entry-title"><a href="<?php echo_permalink(); ?>"><?php echo_title(); ?></a></h3>
							<div class="entry-meta">
								<span class="meta-date"><?php printf(_r('Published %1$s'),get_entry_date()); ?></span>
								<?php if($mtou) { ?><span class="meta-author"><?php printf(_r('by %1$s'),get_entry_author()); ?></span><?php echo"\n\t\t\t\t\t\t\t\t"; } ?><span class="meta-comments"><?php comments_link(_r('0 <span>Comments</span>'),_r('1 <span>Comment</span>'),_r('% <span>Comments</span>'),_r('<span>Closed</span>')); ?></span>
								<span class="entry-edit"><?php edit_entry_link(_r('Edit')); ?></span>
								<span class="meta-tags"><?php _e('Tags:'); ?> <?php echo_tags(); ?></span>
							</div>
						</div>
						<div class="entry-content">
							<?php
								if(is_entry()) {
									echo_content();
								}
								else {
									echo_snippet();
								} ?>
						</div>
					</div>
<?php
						}
					} ?>
					<div class="navigation">
					<?php prev_page_link(_r('&laquo; Newer Posts'),'<div class="alignleft">','</div>'); ?>
					<?php next_page_link(_r('Older Posts &raquo;'),'<div class="alignright">','</div>'); ?>
					</div>
<?php
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