<?php
$parent_file = "posts.php";
require("admin-head.php");
if(we_can('edit_posts')) {
	switch($_GET['req']) {
		case "edit" :
			if(isset($_GET['id'])) {
				get_admin_header();
				$posts = get_posts('id='.intval($_GET['id']).'&limit=1');
				foreach($posts as $post) { start_post(); ?>
		<div id="wrapper">
			<h1><?php printf(_r('Editing %1$s'),return_title()); ?></h1>
<?php do_editorform($post); ?>
		</div>
<?php			}
				get_admin_footer();
			}
			break;
	
		case "delete" :
			if(isset($_GET['id'])) {
				$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
				@header("Location: ".load_option('siteurl')."admin/posts.php");
				die();
			}
			break;
			
		case "ajaxdelete" :
			if(isset($_GET['id'])) {
				if(!$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1")) {
					_e('What are you doing? You just deleted this!');
				}
				else {
					_e('Post deleted.');
				}
			}
			break;
		
		default :
			#Attach this for ajax deleting.
			function add_ajax_fun() { ?>
		<script language="javascript" type="text/javascript">
		confirmus = function(text,xml,thing){
			document.getElementById("ajaxmessage").innerHTML="<strong class=\"error\">" + text +"</strong>";
		};
		deletePost = function(id){
			var j00sure = confirm("<?php _e('Are you sure you wish to delete this post?'); ?>");
			if(j00sure) {
				var delCall = new Ajax('posts.php?req=ajaxdelete&id='+id,{onComplete:confirmus});
				delCall.request();
				var hideThis = new Fx.Opacity('post-'+id,{duration:750});
				hideThis.custom(1, 0.2);
			}
		};
		</script>
<?php
			}
			add_action('admin_header','add_ajax_fun');
			get_admin_header();
?>
		<div id="wrapper">
			<h1><?php _e('Manage Posts'); ?></h1>
			<div id="ajaxmessage"></div>
<?php
			$drafts = $bj_db->get_rows("SELECT `ID`,`title` FROM `".$bj_db->posts."` WHERE `ptype` = 'draft' ORDER BY `ID` DESC","ASSOC");
			if($drafts) { ?>
			<div class="drafts">
				<h2><?php _e('Drafts'); ?></h2>
<?php
				foreach($drafts as $draft) {
					$draft_string .= "<a href=\"posts.php?req=edit&amp;id=".$draft['ID']."\">".$draft['title']."</a>, ";
				} ?>
				<p><?php echo preg_replace("{, $}","",$draft_string); ?></p>
			</div>
<?php
			} ?>
			<div class="navigation">
			<?php prev_page_link(_r('&laquo; Newer'),'<div class="alignleft">','</div>','num=16'); ?>
			<?php next_page_link(_r('Older &raquo;'),'<div class="alignright">','</div>','num=16'); ?>
			</div>
			<table class="edit" cellspacing="2">
				<tr>
					<th class="width5 table"><?php _e('ID'); ?></th>
					<th class="width25 table"><?php _e('Title'); ?></th>
					<th class="width20 table"><?php _e('Posted On'); ?></th>
					<th class="width20 table"><?php _e('Tags'); ?></th>
					<th class="width10 table"><?php _e('Type'); ?></th>
					<th class="width10 table">&nbsp;</th>
					<th class="width10 table">&nbsp;</th>
				</tr>
<?php			$posts = get_posts('limit=16&type=public');
				foreach($posts as $post) { start_post(); ?>
				<tr<?php tablealt($i); ?> id="post-<?php echo $post['ID']; ?>">
					<td class="aligncenter"><?php echo $post['ID']; ?></td>
					<td><?php echo_title(); ?></td>
					<td><?php post_date("M jS Y, h:i a"); ?></td>
					<td><?php echo_tags(", ","","","admin=true"); ?></td>
					<td class="capitalize aligncenter"><?php echo get_post_type(); ?></td>
					<td class="editbutton"><a href="posts.php?req=edit&amp;id=<?php echo $post['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="posts.php?req=delete&amp;id=<?php echo $post['ID']; ?>" class="blockit" onclick="deletePost(<?php echo $post['ID']; ?>);return false;"><?php _e('Delete'); ?></a></td>
				</tr>
<?php			} ?>
			</table>
			<h3 class="gothere"><a href="post-write.php"><?php _e('Create a Post'); ?> &gt;&gt;</a></h3>
		</div>
<?php
		get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}
?>