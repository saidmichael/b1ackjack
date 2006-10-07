<?php

#Function: get_user_info()
#Description: Returns the information of a user based on their cookie.
function get_user_info() {
	global $bj_db;
	if(isset($_COOKIE['bj_auth'])) {
		$user = $bj_db->get_rows("SELECT * FROM `".$bj_db->users."` WHERE `login_key` = '".bj_clean_string($_COOKIE['bj_auth'],array(),'mysql=true')."' LIMIT 1","OBJECT");
		return $user;
	}
}
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
	if(isset($args['email'])) {
		$query .= " AND `email` = '".$args['email']."'";
	}
	if(isset($args['rte'])) {
		$query .= " AND `rte` = '".$args['rte']."'";
	}
	$equ = (isset($args['gop'])) ? $args['gop'] : '=';
	if(isset($args['group'])) {
		$query .= " AND `user_group` ".$equ." '".intval($args['group'])."'";
	}
	if(isset($args['sortby'])) {
		$query .= " ORDER BY `".$args['sortby']."`";
	}
	else {
		$query .= " ORDER BY `ID`";
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
		return $bj_db->get_rows($query,"ASSOC");
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

#Function: we_can(Capability)
#Description: Determines if a user can carry out an action based on Capability.
function we_can($str) {
	global $user;
	function we_can_check($int) {
		global $user;
		return ($user->user_group >= $int) ? true : false;
	}
	switch($str) {
		case 'view_frontpage' :
			$check = we_can_check(1);
			break;
		case 'create_posts' :
			$check = we_can_check(2);
			break;
		case 'edit_posts' :
			$check = we_can_check(2);
			break;
		case 'write_posts' :
			$check = we_can_check(2);
			break;
		default:
			$check = true;
	}
	return $check;
}

?>