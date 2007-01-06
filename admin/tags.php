<?php
$parent_file = "sections.php";
require("admin-head.php");
switch($_GET['req']) {
	case "edit" :
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$tags = return_all_tags('limit=1&id='.$id);
		get_admin_header();
		if($tags) {
			foreach($tags as $tag) { ?>
			<h2><?php printf(_r('Editing %1$s'),wptexturize($tag['name'])); ?></h2>
<?php
				tag_editor($tag);
			}
		}
		get_admin_footer();
		break;
	case "ajaxadd" :
		if($_POST['name']) {
			$saved = bj_new_tag(true);
			if(!$_GET['li']) { ?>
					<td class="aligncenter"><?php echo $saved['ID']; ?></td>
					<td><span id="latest_id" class="tag-<?php echo $saved['ID']; ?>"></span><?php echo $saved['name']; ?></td>
					<td class="aligncenter"><a href="posts.php?req=filtertag&amp;tag=<?php echo $saved['ID']; ?>"><?php echo $saved['posts_num']; ?></a></td>
					<td class="editbutton"><a href="tags.php?req=edit&amp;id=<?php echo $saved['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="tags.php?req=delete&amp;id=<?php echo $saved['ID']; ?>" class="blockit deleteme" rel="tags.php?req=ajaxdelete&amp;id=<?php echo $tag['ID']; ?>$tag-<?php echo $tag['ID']; ?>$<?php _e('Are you sure you wish to delete this tag?'); ?>"><?php _e('Delete'); ?></a></td>
<?php
			}
			else { ?>
					<label for="tag-<?php echo $saved['ID']; ?>"><span id="latest_id" class="fading-tag-<?php echo $saved['ID']; ?>"></span><input type="checkbox" id="tag-<?php echo $saved['ID']; ?>" name="tags[<?php echo $saved['ID']; ?>]" checked="checked" /> <?php echo $saved['name']; ?></label>
<?php
				
			}
		}
		break;
	case "delete" :
		if(isset($_GET['id'])) {
			bj_delete_tag(intval($_GET['id']));
			@header("Location: ".load_option('siteurl')."admin/tags.php");
		}
		break;
	case "ajaxdelete" :
		if(isset($_GET['id'])) {
			bj_delete_tag(intval($_GET['id']));
			echo '<strong class="error">'._r('Tag deleted.').'</strong>';
		}
		break;
	default :
		get_admin_header();
		$tags = return_all_tags(); ?>
			<h2><?php _e('Tags'); ?></h2>
			<div id="ajaxmessage"></div>
			<div class="column width25">
				<div class="c-ontent">
					<div class="tblock">
						<h3><?php _e('Add a Tag'); ?></h3>
<?php 
$inline = true;
tag_editor(); ?>
					</div>
				</div>
			</div>
			<div class="column width75">
				<div class="c-ontent">
					<table class="edit" id="tags" cellspacing="2">
						<tr id="headings">
							<th class="width5"><?php _e('ID'); ?></th>
							<th class="width50"><?php _e('Name'); ?></th>
							<th class="width15"><?php _e('Posts'); ?></th>
							<th class="width15">&nbsp;</th>
							<th class="width15">&nbsp;</th>
						</tr>
<?php
				$i = 0;
				foreach($tags as $tag) { ?>
						<tr<?php tablealt($i); ?> id="tag-<?php echo $tag['ID']; ?>">
							<td class="aligncenter"><?php echo $tag['ID']; ?></td>
							<td><?php echo wptexturize($tag['name']); ?></td>
							<td class="aligncenter"><a href="entries.php?req=filtertag&amp;tag=<?php echo $tag['ID']; ?>"><?php echo $tag['posts_num']; ?></a></td>
							<td class="editbutton"><a href="tags.php?req=edit&amp;id=<?php echo $tag['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
							<td class="editbutton"><a href="tags.php?req=delete&amp;id=<?php echo $tag['ID']; ?>" class="blockit deleteme" rel="tags.php?req=ajaxdelete&amp;id=<?php echo $tag['ID']; ?>$tag-<?php echo $tag['ID']; ?>$<?php _e('Are you sure you wish to delete this tag?'); ?>"><?php _e('Delete'); ?></a></td>
						</tr>
<?php
				$i++;
				} ?>
					</table>
				</div>
			</div>
<?php		
		get_admin_footer();
}
?>