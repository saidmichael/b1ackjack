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
			<h2><?php printf(_r('Editing %1$s'),return_title()); ?></h2>
<?php do_editorform($post); ?>
<?php			}
				get_admin_footer();
			}
			break;
	
		case "delete" :
			if(isset($_GET['id'])) {
				$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
				$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".intval($_GET['id'])."' LIMIT 1");
				@header("Location: ".load_option('siteurl')."admin/posts.php?deleted=true");
			}
			break;
			
		case "ajaxdelete" :
			if(isset($_GET['id'])) {
				$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
				$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".intval($_GET['id'])."' LIMIT 1");
				_e('Post deleted.');
			}
			break;
		
		case "search" :
		case "filtertag" :
		case "filtersection" :
		default :
			get_admin_header();
?>
			<h2><?php _e('Manage Posts'); ?></h2>
			<div id="ajaxmessage"><?php if($_GET['deleted'] == "true") { echo '<strong class="error">'._r('Post deleted.').'</strong>'; } ?></div>
<?php
			$drafts = $bj_db->get_rows("SELECT `ID`,`title` FROM `".$bj_db->posts."` WHERE `ptype` = 'draft' ORDER BY `ID` DESC","ASSOC");
			if($drafts) { ?>
			<div class="drafts">
				<h3><?php _e('Drafts'); ?></h3>
<?php
				foreach($drafts as $draft) {
					$draft_string .= "<a href=\"posts.php?req=edit&amp;id=".$draft['ID']."\">".$draft['title']."</a>, ";
				} ?>
				<p><?php echo preg_replace("{, $}","",$draft_string); ?></p>
			</div>
<?php
			} ?>
			<div class="page-options">
				<div class="column width33 searchbox">
					<form method="get" action="posts.php">
						<label for="s"><?php _e('Search:'); ?></label><br />
						<input type="hidden" name="req" value="search" />
						<input type="text" name="s" id="s" value="" />
						<input type="submit" class="inlinesubmit" value="<?php _e('Search'); ?>" />
					</form>
				</div>
				<div class="column width33 tagfilter">
<?php
				$tags = return_all_tags('orderby=ID');
				if(is_array($tags)) { ?>
					<form method="get" action="posts.php">
						<label for="tag"><?php _e('Filter by Tag:'); ?></label><br />
						<input type="hidden" name="req" value="filtertag" />
						<select name="tag" id="tag">
<?php
					foreach($tags as $tag) { ?>
							<option value="<?php echo $tag['ID']; ?>"<?php bj_selected($tag['ID'],intval($_GET['tag'])); ?>><?php echo $tag['name']; ?></option>
<?php
					} ?>
						</select>
						<input type="submit" class="inlinesubmit" value="<?php _e('Show'); ?>" />
					</form>
<?php
				} ?>
				</div>
				<div class="column width33 sectionfilter">
<?php
				$sections = return_sections();
				if(is_array($sections)) { ?>
					<form method="get" action="posts.php">
						<label for="section"><?php _e('Filter by Section:'); ?></label><br />
						<input type="hidden" name="req" value="filtersection" />
						<select name="section" id="section">
<?php
					foreach($sections as $section) { ?>
							<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],intval($_GET['section'])); ?>><?php echo $section['title']; ?></option>
<?php
					} ?>
						</select>
						<input type="submit" class="inlinesubmit" value="<?php _e('Show'); ?>" />
					</form>
<?php
				} ?>
				</div>
				<div class="clear"></div>
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
<?php
				$query_string = 'limit=16&type=public';
				if($_GET['req'] == 'filtertag') {
					$query_string .= '&tag='.intval($_GET['tag']);
				}
				elseif($_GET['req'] == 'filtersection') {
					$query_string .= '&section='.intval($_GET['section']);
				}
				if(is_search()) {
					$query_string .= '&search='.bj_clean_string($_GET['s']);
				}
				$posts = get_posts($query_string);
				if($posts) {
					foreach($posts as $post) { start_post(); ?>
				<tr<?php tablealt($i); ?> id="post-<?php echo_ID(); ?>">
					<td class="aligncenter"><?php echo_ID(); ?></td>
					<td><?php echo_title(); ?></td>
					<td><?php post_date("M jS Y, h:i a"); ?></td>
					<td><?php echo_tags(", ","","","admin=true"); ?></td>
					<td class="capitalize aligncenter"><?php _e(get_post_type()); ?></td>
					<td class="editbutton"><a href="posts.php?req=edit&amp;id=<?php echo_ID(); ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="posts.php?req=delete&amp;id=<?php echo_ID(); ?>" class="blockit" onclick="ajaxDelete('posts.php?req=ajaxdelete&amp;id=<?php echo_ID(); ?>','post-<?php echo_ID(); ?>','<?php _e('Are you sure you wish to delete this post? Comments made to this post will be deleted as well.'); ?>');return false;"><?php _e('Delete'); ?></a></td>
				</tr>
<?php
					}
				}
				else { ?>
				<tr>
					<td colspan="7"><?php _e('No posts found.'); ?></td>
				</tr>
<?php
				} ?>
			</table>
			<div class="navigation">
			<?php prev_page_link(_r('&laquo; Newer'),'<div class="alignleft">','</div>','num=16'); ?>
			<?php next_page_link(_r('Older &raquo;'),'<div class="alignright">','</div>','num=16'); ?>
			</div>
<?php
		get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}
?>