<?php

#Function: get_users(Extra)
function get_users($extra=false) {
	global $bj,$i;
	if($extra) {
		parse_str($extra,$args);
	}
	$i = 0;
	$query = "SELECT * FROM `".$bj->db->users."` WHERE";
	if(isset($args['id'])) {
		$query .= " `ID` = '".intval($args['id'])."'";
	}
	else {
		$query .= " `ID` != '0'";
	}
	if(isset($args['login'])) {
		$query .= " AND `user_login` = '".$args['login']."'";
	}
	if(isset($args['name'])) {
		$query .= " AND `display_name` = '".$args['name']."'";
	}
	if(isset($args['f_name'])) {
		$query .= " AND `user_nicename` = '".$args['f_name']."'";
	}
	if(isset($args['email'])) {
		$query .= " AND `user_email` = '".$args['email']."'";
	}
	$equ = (isset($args['equ'])) ? $args['equ'] : '=';
	if(isset($args['group'])) {
		$query .= " AND `bj_group` ".$equ." '".intval($args['group'])."'";
	}
	if(isset($args['search'])) {
		$search = str_replace('+',' ',$args['search']);
		$search = explode(' ',$search);
		$query .= " AND (((`display_name` LIKE '%".$search[0]."%') OR (`user_email` LIKE '%".$search[0]."%')  OR (`user_url` LIKE '%".$search[0]."%') OR (`about` LIKE '%".$search[0]."%'))";
		for ( $i = 1; $i < count($search); $i++) {
			$query .= " OR ((`display_name` LIKE '%".$search[$i]."%') OR (`user_email` LIKE '%".$search[$i]."%')  OR (`user_url` LIKE '%".$search[$i]."%') OR (`about` LIKE '%".$search[$i]."%'))";
		}
		$query .= ')';
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
		$offset = (isset($args['offset'])) ? intval($args['offset']) : 0;
		$limit = (isset($args['limit'])) ? intval($args['limit']) : 20;
		$query .= " LIMIT ".$offset.",".$limit;
		return $bj->db->get_rows($query);
	}
	else {
		if($args['inclimit'] == "true") {
			$offset = (isset($args['offset'])) ? intval($args['offset']) : 0;
			$limit = (isset($args['limit'])) ? intval($args['limit']) : 20;
			$query .= " LIMIT ".$offset.",".$limit;
		}
		return mysql_num_rows($bj->db->query($query));
	}
}

function get_user($extra=false) {
	$users = get_users($extra);
	if($users)
		return $users[0];
	return false;
}

function bj_new_user($user=array()) {
	global $bj;
	if(we_can('add_users')) {
		run_actions('pre_user_add');
		if(!$user['user_registered'])
			$user['user_registered'] = date('Y-m-d H:i:s');
		$user = run_actions('user_new',$user);
		$keys = ''; $values = '';
		$query = "INSERT INTO ".$bj->db->users." ";
		foreach($user as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".bj_clean_string($value)."'";
		}
		$query .= "(`ID`".$keys.")";
		$query .= " VALUES (''".$values.")";
		$bj->db->query($query);
		return get_user('login='.$user['user_login']);
	}
}

function bj_delete_user($id=0) {
	if(we_can('edit_users')) {
		global $bj;
		$user = $bj->cache->get_user($id);
		if($user) {
			run_actions('deleting_user',$id);
			$bj->db->query("DELETE FROM ".$bj->db->users." WHERE ID = '".$id."' LIMIT 1");
			$bj->cache->drop_cache('user',$id);
			return true;
		}
	}
	return false;
}

#Function: get_usermeta(User ID, Meta Key)
#Description: Get a usermeta entry.
function get_usermeta($uid,$key) {
	global $bj;
	$user = $bj->cache->get_user($uid);
	if($user and isset($user[$key]) and isset($user['meta'][$key]))
		return $user[$key];
	else
		return false;
}

#Function: add_usermeta(User ID, Meta Key, Meta Value)
#Description: Adds a usermeta entry.
function add_usermeta($uid,$key,$value) {
	global $bj;
	$bj->db->query("INSERT INTO ".$bj->db->usermeta." (umeta_id,user_id,meta_key,meta_value) VALUES ('','".$uid."','".$key."','".maybe_serialize($value)."');");
}

#Function: update_usermeta(User ID, Meta Key, Meta Value)
#Description: Updates a usermeta value.
function update_usermeta($uid,$key,$value) {
	global $bj;
	if(!get_usermeta($uid,$key))
		add_usermeta($uid,$key,$value);
	else
		$bj->db->query("UPDATE ".$bj->db->usermeta." SET meta_value = '".maybe_serialize($value)."' WHERE user_id = '".$uid."' LIMIT 1;");
}

#Function: we_can(Capability)
#Description: Determines if a user can carry out an action based on Capability.
function we_can($str) {
	switch($str) {
	case 'view_frontpage' :
		$check = we_can_check(1);
		break;
	case 'edit_entries' :
		$check = we_can_check(1);
		break;
	case 'write_entries' :
		$check = we_can_check(1);
		break;
	case 'edit_comments' :
		$check = we_can_check(1);
		break;
	case 'edit_sections' :
		$check = we_can_check(2);
		break;
	case 'edit_tags' :
		$check = we_can_check(1);
		break;
	case 'edit_skins' :
		$check = we_can_check(2);
		break;
	case 'edit_users' :
		$check = we_can_check(3);
		break;
	case 'edit_settings' :
		$check = we_can_check(3);
		break;
	case 'manage_plugins' :
		$check = we_can_check(3);
		break;
	case 'edit_profile' :
		$check = we_can_check(1);
		break;
	case 'add_users' :
		$check = we_can_check(3);
		break;
	default:
		$check = false;
	}
	return $check;
}
function we_can_check($int) {
	global $bj;
	return ($bj->user['bj_group'] >= $int) ? true : false;
}

#Function: get_groups()
#Description: Returns the group names.
function get_groups() {
	return run_actions('group_names',array(_r('Disabled'),_r('Journalist'),_r('Developer'),_r('Administrator')));
}

#Function: get_group()
#Description: Returns the group name, based on the group ID.
function get_group($id=4) {
	$groups = get_groups();
	return $groups[$id];
}

?>
