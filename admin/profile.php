<?php
$parent_file = "users.php";
require("admin-head.php");
if(we_can('edit_profile')) {
	if($_POST['edit-user-send']) {
		if($_POST['user_pass'] != $_POST['user_pass2'])
			add_bj_notice(_r('The new passwords did not match.'));
		else {
			$u['display_name'] = bj_clean_string($_POST['display_name']);
			if(we_can('edit_users')) $u['bj_group'] = intval($_POST['bj_group']);
			$u['user_email'] = bj_clean_string($_POST['user_email']);
			$u['user_url'] = bj_clean_string($_POST['user_url']);
			if(!empty($_POST['user_pass']) and !empty($_POST['user_pass2']))
				$u['user_pass'] = md5($_POST['user_pass']);
			run_actions('user_edit',$u);
			update_usermeta($bj->user['ID'],'description',bj_clean_string($_POST['user_description'],get_html_entry()));
			$query = "UPDATE ".$bj->db->users." SET";
			foreach($u as $k=>$v)
				$vals .= ", ".$k." = '".$v."'";
			$query .= substr($vals,1)." WHERE ID = '".$bj->user['ID']."' LIMIT 1;";
			$bj->db->query($query);
			$bj->cache->drop_cache('user',$bj->user['ID']);
			$bj->cache->drop_cache('userlogin',$bj->user['user_login']);
			add_bj_notice(_r('User updated.'));
		}
	}
	get_admin_header(); ?>
				<h2><?php _e('Your Profile'); ?></h2>
				<form name="user-edit-<?php echo $bj->user['ID']; ?>" action="" method="post">
					<p class="label"><label for="form_display_name"><?php _e('Display Name'); ?></label></p>
					<p><input type="text" name="display_name" id="form_display_name" class="text100" value="<?php echo formatted_for_editing($bj->user['display_name']); ?>" /></p>
					<div class="column width50">
						<div class="c-ontent">
							<h3><?php _e('Account Data'); ?></h3>

<?php
	if(we_can('edit_users')) { ?>
							<p class="label"><label for="form_bj_group"><?php _e('Group'); ?></label></p>
							<p>
								<select name="bj_group" id="form_bj_group">
<?php
							$groups = get_groups();
							foreach($groups as $id=>$group) { ?>
									<option value="<?php echo $id; ?>"<?php bj_selected($id,$bj->user['bj_group']); ?>><?php echo $group; ?></option>
<?php
							} ?>
								</select>
							</p>
<?php
	} ?>

							<p class="label"><label for="form_user_email"><?php _e('Email'); ?></label></p>
							<p><input type="text" name="user_email" id="form_user_email" value="<?php echo formatted_for_editing($bj->user['user_email']); ?>" class="text100" /></p>

							<p class="label"><label for="form_user_url"><?php _e('Website <small>(Optional)</small>'); ?></label></p>
							<p><input type="text" name="user_url" id="form_user_url" value="<?php echo formatted_for_editing($bj->user['user_url']); ?>" class="text100" /></p>
						</div>
					</div>
					<div class="column width50">
						<div class="c-ontent">
							<h3><?php _e('Personal Information'); ?></h3>

							<p class="label"><label for="form_user_description"><?php _e('Description <small>(Optional)</small>'); ?></label></p>
							<p><textarea name="user_description" id="form_description" rows="5" class="width100"><?php echo formatted_for_editing($bj->user['description']); ?></textarea></p>

							<p class="label"><label for="form_user_pass"><?php _e('New Password'); ?></label></p>
							<p><input type="password" name="user_pass" id="form_user_pass" value="" class="text100" /></p>

							<p class="label"><label for="form_user_pass2"><?php _e('Once Again'); ?></label></p>
							<p><input type="password" name="user_pass2" id="form_user_pass2" value="" class="text100" /></p>
						</div>
					</div>
					<div class="clear"></div>
					<input type="hidden" name="edit-user-id" value="<?php echo $bj->user['ID']; ?>" />
					<input type="hidden" name="edit-user-login" value="<?php echo formatted_for_editing($bj->user['user_login']); ?>" />
					<input type="hidden" name="edit-user-send" value="true" />
					<p class="submit">
						<input type="submit" name="submit" value="<?php _e('Edit User'); ?>" />
					</p>
				</form>			
<?php	
	get_admin_footer();
}
else
	_e('You don\'t have permission to access this file.');
?>
