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
			$bj_db->query("DELETE FROM `".$bj_db->posts."` WHERE `ID` = '".$id."' LIMIT 1");
			$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".$id."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/posts.php?deleted=true");
			die();
		}
		$post['title'] = bj_clean_string($_POST['title']);
		$post['shortname'] = (bj_clean_string($_POST['shortname']) == '') ? $id : bj_clean_string($_POST['shortname']);
		$post['content'] = bj_clean_string($_POST['content'],$bj_html_post);
		$post['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$post['author'] = bj_clean_string($_POST['author']);
		$tag_string = array();
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string[] = $tag.'';
			}
		}
		$post['tags'] = serialize($tag_string);
		$post['section'] = bj_clean_string($_POST['section']);
		#Change date handling.
		if($_POST['editstamp'] == "yes") {
			$post['posted'] = intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']);
		}
		$post = run_filters('post_edit',$post);
		#Now let's build our update query.
		$query = "UPDATE `".$bj_db->posts."` SET `ID` = '".$id."'";
		foreach($post as $key=>$value) {
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
	global $bj_db,$bj_html_post;
	if(we_can('write_posts')) {
		run_actions('pre_post_new');
		$post = array();
		$post['title'] = bj_clean_string($_POST['title']);
		$post['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($post['title']) : bj_clean_string($_POST['shortname']);
		$post['content'] = bj_clean_string($_POST['content'],$bj_html_post);
		$post['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$post['author'] = bj_clean_string($_POST['author']);
		$tag_string = array();
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string[] = $tag.'';
			}
		}
		$post['tags'] = serialize($tag_string);
		$post['section'] = bj_clean_string($_POST['section']);
		$post = run_filters('post_new',$post);
		#Now let's build our insert query.
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj_db->posts."`";
		foreach($post as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`,`posted`".$keys.")";
		$query .= " VALUES ('','".date('Y-m-d H:i:s',time())."'".$values.")";
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

#Function: bj_new_section()
#Description: Creates a section.
function bj_new_section() {
	global $bj_db;
	if(we_can('edit_sections')) {
		run_actions('pre_new_section');
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($section['title']) : bj_clean_string($_POST['shortname']);
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section = run_filters('section_new',$section);
		#Query query.
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj_db->sections."`";
		foreach($section as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`,`last_updated`".$keys.")";
		$query .= " VALUES ('','".date('Y-m-d H:i:s',time())."'".$values.")";
		$bj_db->query($query);
		if(isset($_POST['save'])) {
			@header("Location: ".load_option('siteurl')."admin/sections.php");
		}
		elseif(isset($_POST['save-cont'])) {
			$saved = $bj_db->get_item("SELECT `ID` FROM `".$bj_db->sections."` WHERE `title` = '".$section['title']."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/sections.php?req=edit&id=".$saved['ID']);
		}
		die();
	}
}

#Function: bj_edit_section(ID)
#Description: Edit a section. Go from there.
function bj_edit_section($id = 0) {
	global $bj_db;
	if(we_can('edit_sections')) {
		run_actions('pre_edit_section');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `ID` = '".$id."' LIMIT 1");
		if($id == 0 || !$former) {
			return false;
		}
		if(isset($_POST['save-del'])) {
			$bj_db->query("DELETE FROM `".$bj_db->sections."` WHERE `ID` = '".$id."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/sections.php?deleted=true");
			die();
		}
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($section['title']) : bj_clean_string($_POST['shortname']);
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['last_updated'] = date('Y-m-d H:i:s',time());
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section = run_filters('section_edit',$section);
		#Query query.
		$query = "UPDATE `".$bj_db->sections."` SET `ID` = '".$id."'";
		foreach($section as $key=>$value) {
			if(isset($former[$key])) {
				if($former[$key] != $value) {
					$query .= ", `".$key."` = '".$value."'";
				}
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj_db->query($query);
		if(isset($_POST['save'])) {
			@header("Location: ".load_option('siteurl')."admin/sections.php");
		}
		elseif(isset($_POST['save-cont'])) {
			@header("Location: ".load_option('siteurl')."admin/sections.php?req=edit&id=".$id);
		}
		die();
	}
}

#Function: bj_clean_string(String, Allowed HTML[, Args])
#Description: Cleans anything within the string using kses,
#			  mysql_real_escape_string, and a few others.
function bj_clean_string($string,$allowed_html=array()) {
	global $bj_db;
	if(get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	$content = str_replace(
		array(
			"<",
			">",
			"\"",
			"'",
			"&"),
		array(
			"&#60;",
			"&#62;",
			"&#34;",
			"&#39;",
			"&#38;"),
		$content);
	$string = bj_kses($string,$allowed_html);
	$string = $bj_db->escape($string);
	return run_filters('clean_string',$string);
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