<?php
$parent_file = "settings.php";
require("admin-head.php");
if(we_can('edit_settings')) {
	if($_POST['custom-fields-send']) {
		$fields = array();
		foreach($_POST['fields'] as $field)
			if(!empty($field))
				$fields[] = bj_clean_string($field);
		update_option('custom_fields',$fields);
		add_bj_notice(_r('Custom fields updated.'));
	}
	get_admin_header(); ?>
			<h2><?php _e('Custom Fields'); ?></h2>
			<p><?php printf(_r('Custom fields are extra data associated with <a href="%1$s">entries</a>, normally used to tweak how certain entries are displayed or interpreted. From this page, you can change which custom fields editors can input.'),'entries.php'); ?></p>
			<p><?php _e('Below, as you can see, are some input fields. To add new custom fields, just enter some text in a blank field. To remove a field, just delete its text.'); ?></p>
			<form name="customfields" action="" method="post">
<?php
	if(load_option('custom_fields')) {
		foreach(load_option('custom_fields') as $field) { ?>
				<p><input type="text" name="fields[]" value="<?php echo formatted_for_editing($field); ?>" /></p>
<?php
		}
	}
	else { ?>
				<p><?php _e('...Well, if there were any. You might want to add some first.'); ?></p>
<?php
	} ?>
				<h3><?php _e('New Fields'); ?></h3>
				<p><input type="text" name="fields[]" value="" /></p>
				<p><input type="text" name="fields[]" value="" /></p>
				<p><input type="text" name="fields[]" value="" /></p>
				<input type="hidden" name="custom-fields-send" value="true" />
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
