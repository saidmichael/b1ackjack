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
		case "ajaxadd" :
			$saved = bj_new_section(true); ?>
					<td class="aligncenter"><?php echo $saved['ID']; ?></td>
					<td><span id="latest_id" class="section-<?php echo $saved['ID']; ?>"></span><?php echo $saved['title']; ?></td>
					<td><?php entry_date("M jS Y, h:i a",$saved['last_updated']); ?></td>
					<td class="capitalize aligncenter"><?php _e($saved['hidden']); ?></td>
					<td class="editbutton"><a href="entries.php?req=filtersection&amp;section=<?php echo $saved['ID']; ?>" class="blockit"><?php _e('View Entries'); ?></a></td>
					<td class="editbutton"><a href="sections.php?req=edit&amp;id=<?php echo $saved['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="sections.php?req=delete&amp;id=<?php echo $saved['ID']; ?>" class="blockit deleteme" rel="sections.php?req=ajaxdelete&amp;id=<?php echo $saved['ID']; ?>$section-<?php echo $saved['ID']; ?>$<?php _e('Are you sure you wish to delete this section? Posts under it will be deleted as well.'); ?>"><?php _e('Delete'); ?></a></td>
<?php
			break;
		case "delete" :
			if(isset($_GET['id'])) {
				bj_delete_section(intval($_GET['id']));
				@header("Location: ".load_option('siteurl')."admin/sections.php");
			}
			break;
		case "ajaxdelete" :
			if(isset($_GET['id'])) {
				bj_delete_section(intval($_GET['id']));
				echo '<strong class="error">'._r('Section deleted.').'</strong>';
			}
			break;
		default :
			get_admin_header();
			$sections = return_sections(); ?>
			<h2><?php _e('Edit Sections'); ?></h2>
			<div id="ajaxmessage"></div>
			<div class="column width25">
				<div class="c-ontent">
					<h3><?php _e('Add a Section'); ?></h3>
<?php 
$inline = true;
section_editor(); ?>
				</div>
			</div>
			<div class="column width75">
				<div class="c-ontent">
					<table class="edit" cellspacing="2">
						<tr id="headings">
							<th class="width5"><?php _e('ID'); ?></th>
							<th class="width25"><?php _e('Title'); ?></th>
							<th class="width20"><?php _e('Last Updated'); ?></th>
							<th class="width10"><?php _e('Hidden?'); ?></th>
							<th class="width10"><?php _e('Default?'); ?></th>
							<th class="width10">&nbsp;</th>
							<th class="width10">&nbsp;</th>
						</tr>
<?php
			$i = 0;
			$tags = return_all_tags();
			foreach($sections as $section) { ?>
						<tr<?php tablealt($i); ?> id="section-<?php echo $section['ID']; ?>">
							<td class="aligncenter"><?php echo $section['ID']; ?></td>
							<td><?php echo $section['title']; ?></td>
							<td><?php entry_date("M jS Y, h:i a",$section['last_updated']); ?></td>
							<td class="capitalize aligncenter"><?php _e($section['hidden']); ?></td>
							<td class="aligncenter"><?php echo ($section['ID'] == load_option('default_section')) ? _r('Yes') : _r('No'); ?></td>
							<td class="editbutton"><a href="sections.php?req=edit&amp;id=<?php echo $section['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
							<td class="editbutton"><a href="sections.php?req=delete&amp;id=<?php echo $section['ID']; ?>" class="blockit deleteme" rel="sections.php?req=ajaxdelete&amp;id=<?php echo $section['ID']; ?>$section-<?php echo $section['ID']; ?>$<?php _e('Are you sure you wish to delete this section? Posts under it will be deleted as well.'); ?>"><?php _e('Delete'); ?></a></td>
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
}
else {
	_e('You don\'t have permission to access this file.');
}
?>