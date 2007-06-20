<?php
$parent_file = "entries.php";
require("admin-head.php");
if(we_can('edit_comments')) {
	switch($_GET['req']) {
		case "edit" :
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
			$comment = get_comment('id='.$id);
			if($comment) {
				get_admin_header(); ?>
			<h2><?php printf(_r('Comment by %1$s'),return_comment_name()); ?></h2>
<?php
				comment_editor($comment);
				get_admin_footer();
			}
			break;
		case "delete" :
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
			bj_delete_comment($id);
			@header('Location: '.load_option('siteurl').'admin/comments.php');
			break;
		case "ajaxdelete" :
			$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
			bj_delete_comment($id);
			printf(_r('The comment by %1$s was deleted.'),return_comment_name());
			break;
		case "status" :
			if(isset($_GET['id'])) {
				$bj->db->query("UPDATE `".$bj->db->comments."` SET `status` = '".bj_clean_string($_GET['to'])."' WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
				run_actions('comment_status_changed',bj_clean_string($_GET['to']));
				@header('Location: '.get_siteinfo('adminurl').'comments.php?changedto='.bj_clean_string($_GET['to']));
			}
			break;
		case "search" :
		default :
			if($_GET['changedto'])
				add_bj_notice(_r('The comment\'s status was successfully changed.'));
			get_admin_header();
			switch($_GET['mod']) {
			case 'mod' :
				$status = 'hidden';
				break;
			default :
				$status = run_actions('comment_status_filter','normal');
			}
			if($_GET['req'] == 'search')
				$comments = get_comments('status='.$status.'&search='.bj_clean_string($_GET['s']));
			elseif($_GET['req'] == 'entry')
				$comments = get_comments('status='.$status.'&postid='.intval($_GET['entry']));
			else
				$comments = get_comments('status='.$status,' LIMIT 0,20'); ?>
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
				<div class="column width50">
					<form method="get" action="comments.php">
						<label for="switch"><?php _e('Switch to:'); ?></label><br />
						<select name="mod" id="switch">
							<option value="normal"<?php bj_selected($status,'normal'); ?>><?php _e('Approved'); ?></option>
							<option value="mod"<?php bj_selected($status,'hidden'); ?>><?php _e('Unapproved'); ?></option>
						</select>
						<input type="submit" class="inlinesubmit" value="<?php _e('Show'); ?>" />
					</form>
				</div>
				<div class="clear"></div>
			</div>
<?php
			if($comments) { ?>
			<ol class="commentlist">
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
						<a href="comments.php?req=delete&amp;id=<?php comment_ID(); ?>" class="deleteme" rel="comments.php?req=ajaxdelete&amp;id=<?php comment_id(); ?>$comment-<?php comment_ID(); ?>"><?php _e('Delete'); ?></a> &#8212; 
<?php
					if($status == 'hidden') { ?>
						<a href="comments.php?req=status&amp;to=normal&amp;id=<?php comment_ID(); ?>"><?php _e('Approve'); ?></a> &#8212; 
<?php
					}
					else { ?>
						<a href="comments.php?req=status&amp;to=hidden&amp;id=<?php comment_ID(); ?>"><?php _e('Unapprove'); ?></a> &#8212; 
<?php
					} ?>
						<a href="entries.php?req=edit&amp;id=<?php echo comment_postid(); ?>"><?php _e('Edit Entry'); ?></a>
					</div>
				</li>
<?php
					if($oddcom == '') { $oddcom = ' alt'; } else { $oddcom = ''; }
				} ?>
			</ul>
<?php
			}
			else { ?>
			<p><?php _e('No comments found.'); ?></p>
<?php
			}
			get_admin_footer();
	}
}
else
	_e('You don\'t have permission to access this file.');

?>
