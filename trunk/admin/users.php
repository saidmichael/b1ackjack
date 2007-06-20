<?php
$parent_file = "users.php";
require("admin-head.php");
if(we_can('edit_users')) {
	switch($_GET['req']) {
		case 'ajaxdelete' :
			bj_delete_user(intval($_GET['id']));
			_e('User deleted.');
			break;
		case 'delete' :
			bj_delete_user(intval($_GET['id']));
			@header("Location: ".get_siteinfo('adminurl')."users.php?deleted=true");
			break;
		case 'edit' :
			if($_GET['id']) {
				if($_POST['edit-user-send']) {
					if($_POST['user_pass'] != $_POST['user_pass2'])
						add_bj_notice(_r('The new passwords did not match.'));
					else {
						$u['display_name'] = bj_clean_string($_POST['display_name']);
						$u['bj_group'] = intval($_POST['bj_group']);
						$u['user_email'] = bj_clean_string($_POST['user_email']);
						$u['user_url'] = bj_clean_string($_POST['user_url']);
						if(!empty($_POST['user_pass']) and !empty($_POST['user_pass2']))
							$u['user_pass'] = md5($_POST['user_pass']);
						run_actions('user_edit',$u);
						update_usermeta(intval($_POST['edit-user-id']),'description',bj_clean_string($_POST['user_description'],get_html_entry()));
						$query = "UPDATE ".$bj->db->users." SET";
						foreach($u as $k=>$v)
							$vals .= ", ".$k." = '".$v."'";
						$query .= substr($vals,1)." WHERE ID = '".intval($_POST['edit-user-id'])."' LIMIT 1;";
						$bj->db->query($query);
						$bj->cache->drop_cache('user',intval($_POST['edit-user-id']));
						$bj->cache->drop_cache('userlogin',$_POST['edit-user-login']);
						add_bj_notice(_r('User updated.'));
					}
				}
				$user = $bj->cache->get_user(intval($_GET['id']));
				if($user) {
					get_admin_header(); ?>
				<h2><?php printf(_r('Editing %1$s'),$user['user_login']); ?></h2>
				<form name="user-edit-<?php echo $user['ID']; ?>" action="" method="post">
					<p class="label"><label for="form_display_name"><?php _e('Display Name'); ?></label></p>
					<p><input type="text" name="display_name" id="form_display_name" class="text100" value="<?php echo formatted_for_editing($user['display_name']); ?>" /></p>
					<div class="column width50">
						<div class="c-ontent">
							<h3><?php _e('Account Data'); ?></h3>

							<p class="label"><label for="form_bj_group"><?php _e('Group'); ?></label></p>
							<p>
								<select name="bj_group" id="form_bj_group">
<?php
							$groups = get_groups();
							foreach($groups as $id=>$group) { ?>
									<option value="<?php echo $id; ?>"<?php bj_selected($id,$user['bj_group']); ?>><?php echo $group; ?></option>
<?php
							} ?>
								</select>
							</p>

							<p class="label"><label for="form_user_email"><?php _e('Email'); ?></label></p>
							<p><input type="text" name="user_email" id="form_user_email" value="<?php echo formatted_for_editing($user['user_email']); ?>" class="text100" /></p>

							<p class="label"><label for="form_user_url"><?php _e('Website <small>(Optional)</small>'); ?></label></p>
							<p><input type="text" name="user_url" id="form_user_url" value="<?php echo formatted_for_editing($user['user_url']); ?>" class="text100" /></p>
						</div>
					</div>
					<div class="column width50">
						<div class="c-ontent">
							<h3><?php _e('Personal Information'); ?></h3>

							<p class="label"><label for="form_user_description"><?php _e('Description <small>(Optional)</small>'); ?></label></p>
							<p><textarea name="user_description" id="form_description" rows="5" class="width100"><?php echo formatted_for_editing($user['description']); ?></textarea></p>

							<p class="label"><label for="form_user_pass"><?php _e('New Password'); ?></label></p>
							<p><input type="password" name="user_pass" id="form_user_pass" value="" class="text100" /></p>

							<p class="label"><label for="form_user_pass2"><?php _e('Once Again'); ?></label></p>
							<p><input type="password" name="user_pass2" id="form_user_pass2" value="" class="text100" /></p>
						</div>
					</div>
					<div class="clear"></div>
					<input type="hidden" name="edit-user-id" value="<?php echo intval($_GET['id']); ?>" />
					<input type="hidden" name="edit-user-login" value="<?php echo formatted_for_editing($user['user_login']); ?>" />
					<input type="hidden" name="edit-user-send" value="true" />
					<p class="submit">
						<input type="submit" name="submit" value="<?php _e('Edit User'); ?>" />
					</p>
				</form>			
<?php	
					get_admin_footer();
				}
			}
			break;
		case 'search' :
		case 'filtergroup' :
		default :
			$plus_this='';
			$extra_string='';
			if($_GET['req'] == 'filtergroup' && $_GET['group'] != '') {
				$extra_string = 'req=filtergroup&amp;group='.$groups[intval($_GET['group'])].'&amp;';
				$plus_this = '&group='.intval($_GET['group']);
			}
			$users = get_users();
			$i = 1;
			if($_GET['deleted'])
				add_bj_notice(_r('User deleted.'));
			get_admin_header(); ?>
			<h2><?php _e('Manage Users'); ?></h2>
			<table class="edit">
				<tr class="ths">
					<th class="width5"><?php _e('ID'); ?></th>
					<th class="width20"><?php _e('Name'); ?></th>
					<th class="width20"><?php _e('Email'); ?></th>
					<th class="width20"><?php _e('Website'); ?></th>
					<th class="width15"><?php _e('Group'); ?></th>
					<th colspan="2"><?php _e('Action'); ?></th>
				</tr>
<?php
			if($users) {
				foreach($users as $user) { ?>
				<tr class="<?php tablealt($i); ?>" id="user-<?php echo $user['ID']; ?>">
					<td class="aligncenter"><?php echo $user['ID']; ?></td>
					<td><?php echo $user['display_name']; ?></td>
					<td><a href="mailto:<?php echo $user['user_email']; ?>"><?php echo $user['user_email']; ?></a></td>
					<td><a href="<?php echo $user['user_url']; ?>"><?php echo $user['user_url']; ?></a></td>
					<td class="aligncenter"><?php echo get_group($user['bj_group']); ?></td>
					<td class="editbutton width10"><a href="users.php?req=edit&amp;id=<?php echo $user['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton width10"><a href="users.php?req=delete&amp;id=<?php echo $user['ID']; ?>" class="blockit deleteme" rel="users.php?req=ajaxdelete&amp;id=<?php echo $user['ID']; ?>$user-<?php echo $user['ID']; ?>"><?php _e('Delete'); ?></a></td>
				</tr>
<?php
					$i++;
				}
			} ?>
			</table>
			<h3><?php _e('Add a User'); ?></h3>
			<form name="adduser" action="" method="post">
				<p class="label"><label for="user_login"><?php _e('Login'); ?></label></p>
				<p><input type="text" name="user_login" id="user_login" value="" /></p>
				
				<p class="label"><label for="user_email"><?php _e('Email'); ?></label></p>
				<p><input type="text" name="user_email" id="user_email" value="" /></p>
				
				<p class="label"><label for="user_pass"><?php _e('Password'); ?></label></p>
				<p><input type="password" name="user_pass" id="user_pass" value="" /></p>
				
				<p class="label"><label for="user_url"><?php _e('Website'); ?></label></p>
				<p><input type="text" name="user_url" id="user_url" value="" /></p>
				
				<p class="label"><label for="bj_group"><?php _e('Group'); ?></label></p>
				<p>
					<select name="bj_group" id="bj_group">
<?php
			$groups = get_groups();
			foreach($groups as $id=>$group) { ?>
						<option value="<?php echo $id; ?>"><?php echo $group; ?></option>
<?php
			} ?>
					</select>
				</p>
				
				<input type="hidden" name="new-user-send" value="true" />
				<p class="submit"><input type="submit" value="<?php _e('Add User'); ?>" /></p>
			</form>
<?php
			get_admin_footer();
	}
}
else
	_e('You don\'t have permission to access this file.');
?>
