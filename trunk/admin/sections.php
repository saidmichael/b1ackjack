<?php
$parent_file = "sections.php";
require("admin-head.php");
if(we_can('edit_sections')) {
	switch($_GET['req']) {
		case "edit" :
			$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `ID` = ".intval($_GET['id'])." LIMIT 1");
			get_admin_header(); ?>
		<div id="wrapper">
			<h1><?php printf(_r('Editing %1$s'),$section['title']); ?></h1>
<?php section_editor($section); ?>
		</div>
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
			#Attach this for ajax deleting.
			function add_ajax_fun() { ?>
		<script language="javascript" type="text/javascript">
		confirmus = function(text,xml,thing){
			document.getElementById("ajaxmessage").innerHTML="<strong class=\"error\">" + text +"</strong>";
		};
		deleteSection = function(id){
			var j00sure = confirm("<?php _e('Are you sure you wish to delete this section?'); ?>");
			if(j00sure) {
				var delCall = new Ajax('sections.php?req=ajaxdelete&id='+id,{onComplete:confirmus});
				delCall.request();
				var hideThis = new Fx.Opacity('section-'+id,{duration:750});
				hideThis.custom(1, 0.2);
			}
		};
		</script>
<?php
			}
			add_action('admin_header','add_ajax_fun');
			get_admin_header();
			$sections = return_sections(); ?>
		<div id="wrapper">
			<h1><?php _e('Edit Sections'); ?></h1>
			<div id="ajaxmessage"><?php if($_GET['deleted'] == "true") { echo '<strong class="error">'._r('Section deleted.').'</strong>'; } ?></div>
			<table class="edit" cellspacing="2">
				<tr>
					<th class="width5 table"><?php _e('ID'); ?></th>
					<th class="width25 table"><?php _e('Title'); ?></th>
					<th class="width20 table"><?php _e('Last Updated'); ?></th>
					<th class="width20 table"><?php _e('Filter by Tags'); ?></th>
					<th class="width10 table"><?php _e('Hidden?'); ?></th>
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
					<td><?php
					$output = '';
					if($tags) {
						foreach($tags as $tag) {
							if(preg_match('/"'.$tag['ID'].'"/',$section['tags'])) {
								$output .= '<a href="tags.php?req=edit&amp;id='.$tag['ID'].'">'.$tag['name'].'</a>, ';
							}
						}
						echo preg_replace('{, $}','',$output);
					} ?></td>
					<td class="capitalize aligncenter"><?php _e($section['hidden']); ?></td>
					<td class="editbutton"><a href="sections.php?req=edit&amp;id=<?php echo $section['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="sections.php?req=delete&amp;id=<?php echo $section['ID']; ?>" class="blockit" onclick="deleteSection(<?php echo $section['ID']; ?>);return false;"><?php _e('Delete'); ?></a></td>
				</tr>
<?php
				$i++;
			} ?>
			</table>
		</div>
<?php
			get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}
?>