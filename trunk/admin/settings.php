<?php
$parent_file = "settings.php";
require("admin-head.php");
if(we_can('edit_settings')) {
	if(isset($_POST['settings-send'])) {
		$saved = array(
			'sitename'=>bj_clean_string($_POST['sitename']),
			'entries_per_page'=>bj_clean_string($_POST['entries_per_page']),
			'default_section'=>intval($_POST['default_section']),
			'enable_commenting'=>intval($_POST['enable_commenting'])
		);
		run_actions('settings_edit');
		foreach($saved as $key=>$option)
			if(load_option($key) != $option)
				update_option($key,$option);
		add_bj_notice(_r('Settings saved.'));
	}
	get_admin_header(); ?>
			<h2><?php _e('General Settings'); ?></h2>
			<form name="settings" action="" method="post">
				<p class="label"><label for="sitename"><?php _e('Site Name'); ?></label></p>
				<p><input type="text" name="sitename" id="sitename" value="<?php siteinfo('sitename'); ?>" /></p>
				<p class="label"><label for="entries_per_page"><?php _e('Entries per Page'); ?></label></p>
				<p><input type="text" name="entries_per_page" id="entries_per_page" class="aligncenter" value="<?php echo load_option('entries_per_page'); ?>" size="2" maxlength="2" /></p>
				<p class="label"><label for="default_section"><?php _e('Default Section'); ?></label></p>
				<p>
					<select name="default_section" id="default_section">
<?php
	$sections = return_sections();
	foreach($sections as $section) { ?>
						<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],load_option('default_section')); ?>><?php echo $section['title']; ?></option>
<?php
	} ?>
					</select>
				</p>
				<p class="label"><input type="checkbox" name="enable_commenting" id="enable_commenting" value="1" <?php bj_checked(load_option('enable_commenting'),1); ?> /> <label for="enable_commenting"><?php _e('Enable Commenting'); ?></label>
				<input type="hidden" name="settings-send" value="true" />
				<p class="submit">
					<input type="submit" name="submit" value="<?php _e('Submit'); ?>" />
				</p>
			</form>
<?php
	get_admin_footer();
}
else
	_e('You don\'t have permission to access this file.');
?>
