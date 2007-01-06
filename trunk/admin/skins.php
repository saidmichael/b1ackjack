<?php
$parent_file = "skins.php";
require("admin-head.php");
if(we_can('edit_skins')) {
	switch($_GET['req']) {
		case 'activate' :
			if(isset($_GET['uniqid'])) {
				$id = bj_clean_string($_GET['uniqid']);
				update_option('current_skin',$id);
				@header('Location: skins.php?updated=true');
			}
			break;
		default :
			get_admin_header();
			$skin_data = parse_file_info(get_siteinfo('static_stylesheet'),array('Skin Name','Skin URL','Version','Description','Author Name','Author URL'));
			$bj_skins = get_usable_skins();
?>
			<h2><?php _e('Current Skin'); ?></h2>
			<p class="alignleft"><img src="<?php siteinfo('skinurl'); ?>screenshot.png" alt="<?php _e('Screenshot'); ?>" width="150" height="105" />&nbsp;</p>
			<p><strong><?php printf(_r('<a href="%1$s">%2$s %3$s</a> by <a href="%4$s">%5$s</a>'),$skin_data['Skin URL'],$skin_data['Skin Name'],$skin_data['Version'],$skin_data['Author URL'],$skin_data['Author Name']); ?></strong></p>
			<p><?php echo $skin_data['Description']; ?></p>
			<div class="clear"></div>
			<h2><?php _e('Skins'); ?></h2>
			<table class="edit">
				<tr id="headings">
					<th class="width20"><?php _e('Skin'); ?></th>
					<th class="width50"><?php _e('Description'); ?></th>
					<th class="width15"><?php _e('Version'); ?></th>
					<th class="width20">&nbsp;</th>
				</tr>
<?php
			if(count($bj_skins) < 1) { ?>
				<tr>
					<td colspan="4" class="aligncenter"><?php _e('No inactive skins.'); ?></td>
				</tr>
<?php
			}
			else {
				$i = 0;
				foreach($bj_skins as $name=>$skin) {
					$skin_data = parse_file_info(BJPATH.'content/skins/'.$name.'/style.css',array('Skin Name','Skin URL','Version','Description','Author Name','Author URL')); ?>
				<tr<?php tablealt($i); ?>>
					<td class="aligncenter"><strong><?php echo $skin_data['Skin Name']; ?></strong></td>
					<td><?php echo $skin_data['Description']; ?></td>
					<td class="aligncenter"><?php echo $skin_data['Version']; ?></td>
					<td class="editbutton"><a href="skins.php?req=activate&amp;uniqid=<?php echo $name; ?>" class="blockit"><?php _e('Activate'); ?></a></td>
				</tr>
<?php
					$i++;
				}
			}
?>
			</table>
<?php
			get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
} ?>
