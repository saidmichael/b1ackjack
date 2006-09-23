<?php
$parent_file = "posts.php";
require("admin-head.php");
switch($_GET['req']) {
	case "edit" :
		if(isset($_GET['id'])) {
			$posts = get_posts('id='.intval($_GET['id']).'&limit=1');
			foreach($posts as $post) { start_post(); ?>
		<div id="wrapper">
			<h1><?php printf(_r('Editing %1$s'),$post['title']); ?></h1>
<?php do_editorform($post); ?>
		</div>
<?php		}
		}	
		break;
	
	case "delete" :
		
		break;
		
	default :
?>
		<div id="wrapper">
			<h1><?php _e('Manage Posts'); ?></h1>
<?php
			$drafts = $bj_db->get_rows("SELECT `ID`,`title` FROM `".$bj_db->posts."` WHERE `ptype` = 'draft' ORDER BY `ID` DESC","ASSOC");
			if($drafts) { ?>
			<div class="drafts">
				<h2><?php _e('Drafts'); ?></h2>
<?php
				foreach($drafts as $draft) {
					$draft_string .= "<a href=\"posts.php?req=edit&amp;id=".$draft['ID']."\">".$draft['title']."</a>, ";
				}
				echo "<p>".preg_replace("{, $}","",$draft_string)."</p>"; ?>
			</div>
<?php
			} ?>
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
				<tr<?php tablealt($i); ?>>
					<td class="aligncenter"><?php echo $post['ID']; ?></td>
					<td><?php echo $post['title']; ?></td>
					<td><?php post_date("M dS Y, h:i a"); ?></td>
					<td><?php echo_tags(", ","","","admin=true"); ?></td>
					<td class="capitalize aligncenter"><?php echo $post['ptype']; ?></td>
					<td class="editbutton"><a href="posts.php?req=edit&amp;id=<?php echo $post['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="posts.php?req=delete&amp;id=<?php echo $post['ID']; ?>" class="blockit"><?php _e('Delete'); ?></a></td>
				</tr>
<?php			} ?>
			</table>
			<h3 class="gothere"><a href="post-write.php"><?php _e('Create a Post'); ?> &gt;&gt;</a></h3>
		</div>
<?php
}
require("admin-foot.php");
?>