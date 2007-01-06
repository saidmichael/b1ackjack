<?php

#Function: get_users(Extra)
function get_users($extra=false) {
	global $bj_db,$i;
	if($extra) {
		parse_str($extra,$args);
	}
	$num_off = (isset($_GET['offset'])) ? intval($_GET['offset']) : 0; //So the offset will update itself automatically.
	$i = 0;
	$newer = $num_off-(isset($args['limit'])) ? $args['limit'] : 20;
	$older = $num_off+(isset($args['limit'])) ? $args['limit'] : 20;
	$query = "SELECT * FROM `".$bj_db->users."` WHERE";
	if(isset($args['id'])) {
		$query .= " `ID` = '".intval($args['id'])."'";
	}
	else {
		$query .= " `ID` != '0'";
	}
	if(isset($args['login'])) {
		$query .= " AND `login` = '".$args['login']."'";
	}
	if(isset($args['name'])) {
		$query .= " AND `display_name` = '".$args['name']."'";
	}
	if(isset($args['f_name'])) {
		$query .= " AND `friendly_name` = '".$args['f_name']."'";
	}
	if(isset($args['email'])) {
		$query .= " AND `email` = '".$args['email']."'";
	}
	$equ = (isset($args['equ'])) ? $args['equ'] : '=';
	if(isset($args['group'])) {
		$query .= " AND `user_group` ".$equ." '".intval($args['group'])."'";
	}
	if(isset($args['sortby'])) {
		$query .= " ORDER BY `".$args['sortby']."`";
	}
	else {
		$query .= " ORDER BY `display_name`";
	}
	if(isset($stuff['order'])) {
		$query .= " ".$stuff['order'];
	}
	else {
		$query .= " ASC";
	}
	#Limit and offset.
	if($args['num'] != "yes") {
		$offset = (isset($args['offset'])) ? intval($args['offset']) : $num_off;
		$limit = (isset($args['limit'])) ? intval($args['limit']) : 20;
		$query .= " LIMIT ".$offset.",".$limit;
		return $bj_db->get_rows($query);
	}
	else {
		if($args['inclimit'] == "yes") {
			$offset = (isset($args['offset'])) ? intval($args['offset']) : $num_off;
			$limit = (isset($args['limit'])) ? intval($args['limit']) : 20;
			$query .= " LIMIT ".$offset.",".$limit;
		}
		return mysql_num_rows($bj_db->query($query));
	}
}

#Function: bj_signup_link()
#Description: Outputs a link for a "register" or "admin".
function bj_signup_link() {
	global $user;
	if($user) { ?><a href="<?php siteinfo('siteurl'); ?>admin/index.php"><?php _e('Admin'); ?></a><?php }
	else { ?><a href="<?php siteinfo('siteurl'); ?>admin/register.php"><?php _e('Register'); ?></a><?php }
}

#Function: we_can(Capability)
#Description: Determines if a user can carry out an action based on Capability.
function we_can($str) {
	global $user;
	switch($str) {
		case 'view_frontpage' :
			$check = we_can_check(1);
			break;
		case 'edit_entries' :
			$check = we_can_check(2);
			break;
		case 'write_entries' :
			$check = we_can_check(2);
			break;
		case 'edit_comments' :
			$check = we_can_check(2);
			break;
		case 'edit_sections' :
			$check = we_can_check(3);
			break;
		case 'edit_tags' :
			$check = we_can_check(2);
			break;
		case 'edit_skins' :
			$check = we_can_check(3);
			break;
		case 'edit_users' :
			$check = we_can_check(4);
			break;
		default:
			$check = false;
	}
	return $check;
}
function we_can_check($int) {
	global $user;
	return ($user['user_group'] >= $int) ? true : false;
}

#Function: makeSalt([Size])
#Description: Makes a random password salt for the user. Copyright http://jaia-interactive.com,
#			  who created this function.
function makeSalt($size = 5) {
	srand((double)microtime() * 1000000);
	$salt = '';
	for($i = 0; $i < $size; $i++) {
		$salt .= chr(rand(40, 126));
	}
	return $salt;
}

?>