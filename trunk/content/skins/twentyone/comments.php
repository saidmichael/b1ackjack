					<h3 class="responses-title" id="comments"><?php printf(_r('%1$s %2$s on %3$s'), '<span id="comments">' . get_comments_number() . '</span>', (1 == $entry['comment_count']) ? _r('Comment'): _r('Comments'), get_entry_title() ); ?></h3>
<?php
if($comments) { ?>
					<ul class="commentlist">
<?php
	$i = 0;
	foreach($comments as $comment) {
		$i++; ?>
						<li id="comment-<?php comment_ID(); ?>" class="<?php TO_comment_class($i); ?>">
							<div class="counter"><a href="<?php entry_permalink(); ?>#comment-<?php comment_ID(); ?>" title="<?php _e('Permanent link to this comment.'); ?>"><?php echo $i; ?></a></div>
							<h4 class="author-name"><?php comment_author_url(); ?></h4>
							<div class="comment-meta">
								<span class="meta-date"><a href="<?php entry_permalink(); ?>#comment-<?php comment_ID(); ?>" title="<?php _e('Permanent link to this comment.'); ?>"><?php printf(_r('Posted on %1$s'),return_comment_date('M dS, Y')); ?></a></span>
								<span class="comment-edit"><?php edit_comment_link('<span class="blank">'._r('(e)').'</span>'); ?></span>
							</div>
							<div class="comment-content">
								<?php comment_text(); ?>
							</div>
						</li>
<?php
	} ?>
					</ul>
<?php
}
else { ?>
					<p class="nocomments"><?php _e('No Comments'); ?></p>
<?php
} ?>
					<h3 class="respond-title" id="respond"><?php _e('Respond'); ?></h3>
<?php
if(commenting_is_disabled()) { ?>
					<p><?php _e('Commenting is currently disabled.'); ?></p>
<?php
}
else { ?>
					<form action="<?php siteinfo('siteurl'); ?>admin/new-comment.php" method="post" id="commentform">
<?php
	if($bj->user) { ?>
						<p class="logininfo"><?php printf(_r('Logged in as %1$s. (<a href="%2$s">Logout</a>)'),'<a href="'.get_siteinfo('siteurl').'admin/profile.php">'.$bj->user['display_name'].'</a>',get_siteinfo('siteurl').'admin/login.php?req=logout'); ?></p>
						<input type="hidden" name="author_name" value="1" />
						<input type="hidden" name="author_email" value="1" />
						<input type="hidden" name="author_url" value="1" />
<?php
	}
	else { ?>
						<p class="info"><?php _e('All fields (except the URL) are required. You may use the following (X)HTML:'); ?><br />
						<code class="xhtml"><?php comments_allowed_html(); ?></code></p>
						<p class="author_name"><input type="text" name="author_name" id="author_name" value="" /> <label for="author_name"><?php _e('Name'); ?></label></p>
						<p class="author_email"><input type="text" name="author_email" id="author_email" value="" /> <label for="author_email"><?php _e('Email (Hidden)'); ?></label></p>
						<p class="author_url"><input type="text" name="author_url" id="author_url" value="" /> <label for="author_url"><?php _e('URL'); ?></label></p>
<?php
	} ?>
						<p class="content"><textarea name="content"></textarea></p>
						<input type="hidden" name="sendcomment" value="1" />
						<input type="hidden" name="post_id" value="<?php entry_ID(); ?>" />
						<p class="submit">
							<input type="submit" name="submit" id="submit" value="<?php _e('Send Comment'); ?>" />
						</p>
					</form>
<?php
} ?>
