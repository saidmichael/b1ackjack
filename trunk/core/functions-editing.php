<?php

#Function: bj_edit_post(Post ID)
#Description: Edits a post and handles where the user goes.
function bj_edit_post($id=0) {
	global $bj_db,$bj_html_post;
	$id = intval($id);
	$former = $bj_db->get_item("SELECT * FROM `".$bj_db->posts."` WHERE `ID` = '".$id."' LIMIT 1");
	if($id == 0 || !$former) {
		return false;
	}
	$_POST['title'] = bj_clean_string($_POST['title'],array(),'mysql=true');
	$_POST['shortname'] = bj_clean_string($_POST['shortname'],array(),'mysql=true');
	$_POST['content'] = bj_clean_string($_POST['content'],$bj_html_post);
	$tag_string = "";
	if(is_array($_POST['tags'])) {
		foreach($_POST['tags'] as $tag=>$on) {
			$tag_string .= $tag.',';
		}
	}
	$_POST['tags'] = preg_replace('{,$}','',$tag_string);
	#Now let's build our update query.
	$query = "UPDATE `".$bj_db->posts."` SET `ID` = '".$id."'";
	foreach($_POST as $key=>$value) {
		if(isset($former[$key]) && $key != 'ID') {
			if($former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
	}
	$query .= " WHERE `ID` = ".$id." LIMIT 1";
	$bj_db->query($query);
	
	if(isset($_POST['save'])) {
		@header("Location: ".load_option('siteurl')."admin/posts.php");
	}
	elseif(isset($_POST['save-cont'])) {
		@header("Location: ".load_option('siteurl')."admin/posts.php?req=edit&id=".$id);
	}
	
	die();
}

#Function: bj_clean_string(String, Allowed HTML[, Args])
#Description: Cleans anything within the string using kses,
#			  mysql_real_escape_string, and a few others.
function bj_clean_string($string,$allowed_html=array(),$q=false) {
	global $bj_db;
	if($q) {
		parse_str($q,$args);
	}
	$string = str_replace("eval(","&#101;&#118;al(",$string); # Does this even do anything?
	$string = bj_kses($string,$allowed_html);
	if(isset($args['mysql'])) { # Is this for insertion in a SQL database?
		$string = $bj_db->escape($string);
	}
	return $string;
}

?>