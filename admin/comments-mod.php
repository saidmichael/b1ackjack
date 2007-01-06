<?php
$parent_file = "entries.php";
require("admin-head.php");
if(we_can('edit_comments')) {
		get_admin_header();
		$comments = get_comments('status=hidden'); ?>
			<h2><?php _e('Unmoderated Comments'); ?></h2>
			<div id="ajaxmessage"></div>
<?php
		if($comments) { ?>
			<ol id="commentlist">
<?php
			foreach($comments as $comment) { ?>
				<li id="comment-<?php comment_ID(); ?>" class="comment<?php echo $oddcom; ?>">
					<div class="comment-options">
						<span class="author-name"><?php comment_author_url(); ?></span> (<span class="author-email"><a href="mailto:<?php comment_email(); ?>"><?php comment_email(); ?></a></span> &#8212; <span class="author-ip"><a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput=<?php echo $comment['author_IP']; ?>"><?php echo $comment['author_IP']; ?></a></span>) <?php printf(_r('on %1$s:'),return_comment_date('M dS, Y')); ?>
					</div>
					<div class="comment-content">
						<?php comment_text(); ?>
					</div>
					<div class="comment-options">
						<a href="comments.php?req=edit&amp;id=<?php comment_ID(); ?>"><?php _e('Edit'); ?></a> &#8212; 
						<a href="comments.php?req=delete&amp;id=<?php comment_ID(); ?>" class="deleteme" rel="comments.php?req=ajaxdelete&amp;id=<?php comment_id(); ?>$comment-<?php comment_ID(); ?>$<?php _e('Are you sure you wish to delete this comment?'); ?>"><?php _e('Delete'); ?></a> &#8212; 
						<a href="comments.php?req=status&amp;to=normal&amp;id=<?php comment_ID(); ?>"><?php _e('Approve'); ?></a> &#8212; 
						<a href="comments.php?req=status&amp;to=spam&amp;id=<?php comment_ID(); ?>"><?php _e('Spam'); ?></a>
					</div>
				</li>
<?php
				if($oddcom == '') { $oddcom = ' alt'; } else { $oddcom = ''; }
			} ?>
			</ul>
<?php
		}
		else { ?>
			<p><?php _e('No comments here. :-)'); ?></p>
<?php
		}
		get_admin_footer();
}
else {
	_e('You don\'t have permission to access this file.');
}

?>
