<?php

#Function: bj_edit_post(Post ID)
#Description: Edits a post and handles where the user goes.
function bj_edit_post($id=0) {
	global $bj_db,$bj_html_post;
	if(we_can('edit_posts')) {
		run_actions('pre_post_edit');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj_db->posts."` WHERE `ID` = '".$id."' LIMIT 1");
		if($id == 0 || !$former) {
			return false;
		}
		if(isset($_POST['save-del'])) {
			$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".intval($_GET['id'])."' LIMIT 1");
			$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".intval($_GET['id'])."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/posts.php?deleted=true");
			die();
		}
		$epost['title'] = bj_clean_string($_POST['title'],array(),'mysql=true');
		$epost['shortname'] = bj_clean_string($_POST['shortname'],array(),'mysql=true');
		$epost['content'] = bj_clean_string($_POST['content'],$bj_html_post);
		$epost['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$epost['author'] = $_POST['author'];
		$tag_string = "";
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string .= $tag.',';
			}
		}
		$epost['tags'] = preg_replace('{,$}','',$tag_string);
		#Change date handling.
		if($_POST['editstamp'] == "yes") {
			$epost['posted'] = intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']);
		}
		run_filters('post_edit',$epost);
		#Now let's build our update query.
		$query = "UPDATE `".$bj_db->posts."` SET `ID` = '".$id."'";
		foreach($epost as $key=>$value) {
			if(isset($former[$key])) {
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
}

#Function: bj_new_post()
#Description: Creates a post and handles where the user goes.
function bj_new_post() {
	global $bj_db,$bj_html_post,$time;
	if(we_can('write_posts')) {
		run_actions('pre_post_new');
		$epost['title'] = bj_clean_string($_POST['title'],array(),'mysql=true');
		$epost['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($epost['title']) : bj_clean_string($_POST['shortname'],array(),'mysql=true');
		$epost['content'] = bj_clean_string($_POST['content'],$bj_html_post);
		$epost['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$epost['author'] = $_POST['author'];
		$tag_string = "";
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string .= $tag.',';
			}
		}
		$epost['tags'] = preg_replace('{,$}','',$tag_string);
		run_filters('post_new',$epost);
		#Now let's build our insert query.
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj_db->posts."`";
		foreach($epost as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`,`posted`".$keys.")";
		$query .= " VALUES ('','".date('Y-m-d H:i:s',$time)."'".$values.")";
		$bj_db->query($query);
	
		if(isset($_POST['save'])) {
			@header("Location: ".load_option('siteurl')."admin/posts.php");
		}
		elseif(isset($_POST['save-cont'])) {
			$saved = $bj_db->get_item("SELECT `ID` FROM `".$bj_db->posts."` WHERE `title` = '".$epost['title']."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/posts.php?req=edit&id=".$saved['ID']);
		}
		die();
	}
}

#Function: bj_clean_string(String, Allowed HTML[, Args])
#Description: Cleans anything within the string using kses,
#			  mysql_real_escape_string, and a few others.
function bj_clean_string($string,$allowed_html=array(),$q=false) {
	global $bj_db;
	if($q) {
		parse_str($q,$args);
	}
	if(get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	$string = str_replace(array('\'','"'),array('&#039;','&#034;'),$string);
	$string = bj_kses($string,$allowed_html);
	if(isset($args['mysql'])) { # Is this for insertion in a SQL database?
		$string = $bj_db->escape($string);
	}
	return $string;
}

#Function: bj_checked(Value One, Value Two)
#Description: Checks if Value One and Value Two are equal.
#			  If so, checked="checked" will be produced.
function bj_checked($value1,$value2) {
	if($value1 == $value2) {
		echo " checked=\"checked\"";
	}
}

#Function: bj_selected(Value One, Value Two)
#Description: Checks if Value One and Value Two are equal.
#			  If so, selected="selected" will be produced.
function bj_selected($value1,$value2) {
	if($value1 == $value2) {
		echo " selected=\"selected\"";
	}
}

#Function: bj_shortname(Title)
#Description: Converts the title into a friendlier name.
function bj_shortname($title) {
	
    $title = strtolower($title);
    $title = str_replace(
		array(
			"å",
			"ø",
			" "
		),
		array(
			"aa",
			"o",
			"-"
		),
	$title);
	$title = preg_replace(
		array(
			'/&.+?;/',
			'/[^a-z0-9 _-]/',
			'/\s+/',
			'|-+|'
		),
		array(
			'',
			'',
			'',
			'-'),
	$title);
    $title = trim($title, '-');
    if(empty($title)) {
		$title = '-';
	}

    return $title;
}

?>