<?php
$parent_file = "comments.php";
require("admin-head.php");
if(we_can('edit_comments')) {
		#Attach this for ajax love.
		function add_ajax_love() { ?>
		<script language="javascript" type="text/javascript">
		confirmus = function(text,xml,thing){
			document.getElementById("ajaxmessage").innerHTML="<strong class=\"error\">" + text +"</strong>";
		};
		deleteComment = function(id){
			var j00sure = confirm("<?php _e('Are you sure you wish to delete this comment?'); ?>");
			if(j00sure) {
				var delCall = new Ajax('comments.php?req=ajaxdelete&id='+id,{onComplete:confirmus});
				delCall.request();
				var hideThis = new Fx.Opacity('comment-'+id,{duration:750});
				hideThis.custom(1, 0.2);
			}
		};
		</script>
<?php
		}
		add_action('admin_header','add_ajax_love');
		get_admin_header();
		$comments = get_comments('status=hidden'); ?>
		<div id="wrapper">
			<h1><?php _e('Unmoderated Comments'); ?></h1>
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
						<a href="comments.php?req=delete&amp;id=<?php comment_ID(); ?>" onclick="deleteComment(<?php comment_ID(); ?>);return false;"><?php _e('Delete'); ?></a> &#8212; 
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
		} ?>
		</div>
<?php
		get_admin_footer();
}
else {
	_e('You don\'t have permission to access this file.');
}

?>