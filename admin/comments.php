<?php
$parent_file = "comments.php";
require("admin-head.php");
if(we_can('edit_comments')) {
	switch($_GET['req']) {
		case "edit" :
			
			break;
		case "delete" :
			$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `ID` = '".$id."' LIMIT 1");
			@header('Location: '.load_option('siteurl').'admin/comments.php');
			break;
		case "ajaxdelete" :
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
			$comment = $bj_db->get_item("SELECT * FROM `".$bj_db->comments."` WHERE `ID` = '".$id."' LIMIT 1");
			if($comment) {
				$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `ID` = '".$id."' LIMIT 1");
				printf(_r('The comment by %1$s was deleted.'),return_comment_name());
			}
			break;
		case "status" :
			if(isset($_GET['id'])) {
				switch($_GET['to']) {
					case 'hidden' :
						$bj_db->query("UPDATE `".$bj_db->comments."` SET `status` = 'hidden' WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
						break;
					case 'spam' :
						$bj_db->query("UPDATE `".$bj_db->comments."` SET `status` = 'spam' WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
						break;
					case 'normal' :
						$bj_db->query("UPDATE `".$bj_db->comments."` SET `status` = 'normal' WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
						break;
				}
				@header('Location: '.load_option('siteurl').'admin/comments.php');
			}
			break;
		case "search" :
		default :
			get_admin_header();
			if($_GET['req'] == 'search') {
				$comments = get_comments('status=normal&search='.bj_clean_string($_GET['s']));
			}
			else {
				$comments = get_comments('status=normal',' LIMIT 0,20');
			} ?>
			<h2><?php _e('Manage Comments'); ?></h2>
			<div class="page-options">
				<div class="column width50">
					<form method="get" action="comments.php">
						<label for="s"><?php _e('Search:'); ?></label><br />
						<input type="hidden" name="req" value="search" />
						<input type="text" name="s" id="s" value="" />
						<input type="submit" class="inlinesubmit" value="<?php _e('Search'); ?>" />
					</form>
				</div>
				<div class="clear"></div>
			</div>
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
						<a href="comments.php?req=delete&amp;id=<?php comment_ID(); ?>" onclick="ajaxDelete('comments.php?req=ajaxdelete&amp;id=<?php comment_id(); ?>,'comment-<?php comment_ID(); ?>','<?php _e('Are you sure you wish to delete this comment?'); ?>');return false;"><?php _e('Delete'); ?></a> &#8212; 
						<a href="comments.php?req=status&amp;to=hidden&amp;id=<?php comment_ID(); ?>"><?php _e('Unapprove'); ?></a> &#8212; 
						<a href="comments.php?req=status&amp;to=spam&amp;id=<?php comment_ID(); ?>"><?php _e('Spam'); ?></a> &#8212; 
						<a href="posts.php?req=edit&amp;id=<?php echo comment_postid(); ?>"><?php _e('Edit Post'); ?></a>
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
}
else {
	_e('You don\'t have permission to access this file.');
}

?>