<?php
$parent_file = "sections.php";
require("admin-head.php");
if(we_can('edit_sections')) {
	switch($_GET['req']) {
		case "edit" :
			$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
			get_admin_header(); ?>
			<h2><?php printf(_r('Editing %1$s'),$section['title']); ?></h2>
<?php section_editor($section); ?>
<?php
			get_admin_footer();
			break;
		case "delete" :
			if(isset($_GET['id'])) {
				$bj_db->query("DELETE FROM `".$bj_db->sections."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
				@header("Location: ".load_option('siteurl')."admin/sections.php?deleted=true");
			}
			break;
			
		case "ajaxdelete" :
			if(isset($_GET['id'])) {
				$bj_db->query("DELETE FROM `".$bj_db->sections."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
				_e('Section deleted.');
			}
			break;
		default :
			get_admin_header();
			$sections = return_sections(); ?>
			<h2><?php _e('Edit Sections'); ?></h2>
			<div id="ajaxmessage"><?php if($_GET['deleted'] == "true") { echo '<strong class="error">'._r('Section deleted.').'</strong>'; } ?></div>
			<table class="edit" cellspacing="2">
				<tr>
					<th class="width5 table"><?php _e('ID'); ?></th>
					<th class="width25 table"><?php _e('Title'); ?></th>
					<th class="width20 table"><?php _e('Last Updated'); ?></th>
					<th class="width10 table"><?php _e('Hidden?'); ?></th>
					<th class="width10 table">&nbsp;</th>
					<th class="width10 table">&nbsp;</th>
					<th class="width10 table">&nbsp;</th>
				</tr>
<?php
			$i = 0;
			$tags = return_all_tags();
			foreach($sections as $section) { ?>
				<tr<?php tablealt($i); ?> id="section-<?php echo $section['ID']; ?>">
					<td class="aligncenter"><?php echo $section['ID']; ?></td>
					<td><?php echo $section['title']; ?></td>
					<td><?php post_date("M jS Y, h:i a",$section['last_updated']); ?></td>
					<td class="capitalize aligncenter"><?php _e($section['hidden']); ?></td>
					<td class="editbutton"><a href="posts.php?req=filtersection&amp;section=<?php echo $section['ID']; ?>" class="blockit"><?php _e('View Posts'); ?></a></td>
					<td class="editbutton"><a href="sections.php?req=edit&amp;id=<?php echo $section['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="sections.php?req=delete&amp;id=<?php echo $section['ID']; ?>" class="blockit" onclick="ajaxDelete('sections.php?req=ajaxdelete&amp;id=<?php echo $section['ID']; ?>','section-<?php echo $section['ID']; ?>','<?php _e('Are you sure you wish to delete this section?'); ?>');return false;"><?php _e('Delete'); ?></a></td>
				</tr>
<?php
				$i++;
			} ?>
			</table>
<?php
			get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}
?>