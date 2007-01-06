<?php

#Function: bj_edit_post(Post ID)
#Description: Edits a post and handles where the user goes.
function bj_edit_entry($id=0) {
	global $bj_db,$bj_html_post;
	if(we_can('edit_entries')) {
		run_actions('pre_entry_edit');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj_db->entries."` WHERE `ID` = '".$id."' LIMIT 1");
		#Does this post exist? If not, do nothing.
		if($id == 0 || !$former) {
			return false;
		}
		if(isset($_POST['save-del'])) {
			$bj_db->query("DELETE FROM `".$bj_db->entries."` WHERE `ID` = '".$id."' LIMIT 1");
			$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".$id."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/entries.php?deleted=true");
			die();
		}
		$post['title'] = bj_clean_string($_POST['title']);
		$post['shortname'] = (bj_clean_string($_POST['shortname']) == '') ? bj_shortname($post['title']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$post['content'] = bj_clean_string($_POST['content'],get_html_entry());
		$post['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$post['author'] = bj_clean_string($_POST['author']);
		$post['comments_open'] = (empty($_POST['comments_open'])) ? 0 : 1;
		$tag_string = array();
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string[] = $tag.'';
				#If the post is public and the tag was added in this edit.
				if(!in_array($tag.'',unserialize($former['tags']),true) and $post['ptype'] == 'public') {
					$bj_db->query("UPDATE `".$bj_db->tags."` SET `posts_num` = posts_num + 1 WHERE `ID` = ".$tag);
				}
			}
		}
		#Was a tag removed?
		foreach(unserialize($former['tags']) as $tag) {
			if(!in_array($tag,$tag_string,true) and $post['ptype'] == 'public') {
				$bj_db->query("UPDATE `".$bj_db->tags."` SET `posts_num` = posts_num - 1 WHERE `ID` = ".$tag);
			}
		}
		$post['tags'] = serialize($tag_string);
		$post['section'] = bj_clean_string($_POST['section']);
		#Change date handling.
		if($_POST['editstamp'] == "yes") {
			$post['posted'] = intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']);
		}
		$post = run_filters('entry_edit',$post);
		#Now let's build our update query.
		$query = "UPDATE `".$bj_db->entries."` SET `ID` = '".$id."'";
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
			@header("Location: ".load_option('siteurl')."admin/entries.php");
		}
		elseif(isset($_POST['save-cont'])) {
			@header("Location: ".load_option('siteurl')."admin/entries.php?req=edit&id=".$id);
		}
	
		die();
	}
}

#Function: bj_new_entry()
#Description: Creates a post and handles where the user goes.
function bj_new_entry() {
	global $bj_db,$bj_html_post;
	if(we_can('write_entries')) {
		run_actions('pre_entry_new');
		$post = array();
		$post['title'] = bj_clean_string($_POST['title']);
		$post['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($post['title']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$post['content'] = bj_clean_string($_POST['content'],get_html_entry());
		$post['ptype'] = (isset($_POST['ptype'])) ? $_POST['ptype'] : 'draft';
		$post['author'] = bj_clean_string($_POST['author']);
		$post['comments_open'] = (empty($_POST['comments_open'])) ? 0 : 1;
		$tag_string = array();
		if(is_array($_POST['tags'])) {
			foreach($_POST['tags'] as $tag=>$on) {
				$tag_string[] = $tag.'';
				if($post['ptype'] == 'public') {
					$bj_db->query("UPDATE `".$bj_db->tags."` SET `posts_num` = posts_num + 1 WHERE `ID` = ".$tag);
				}
			}
		}
		$post['tags'] = serialize($tag_string);
		$post['section'] = bj_clean_string($_POST['section']);
		$post = run_filters('entry_new',$post);
		#Now let's build our insert query.
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj_db->entries."`";
		foreach($post as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`,`posted`".$keys.")";
		$query .= " VALUES ('','".date('Y-m-d H:i:s',time())."'".$values.")";
		$bj_db->query($query);
	
		if(isset($_POST['save'])) {
			@header("Location: ".load_option('siteurl')."admin/entries.php");
		}
		elseif(isset($_POST['save-cont'])) {
			$saved = $bj_db->get_item("SELECT `ID` FROM `".$bj_db->entries."` WHERE `title` = '".$post['title']."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/entries.php?req=edit&id=".$saved['ID']);
		}
		die();
	}
}

#Function: bj_delete_entry(ID)
#Description: Kill, kill!
function bj_delete_entry($id=0) {
	global $bj_db;
	if(we_can('edit_entries')) {
		$bj_db->query("DELETE FROM `".$bj_db->entries."` WHERE `ID` = '".$id."' LIMIT 1");
		$bj_db->query("DELETE FROM `".$bj_db->comments."` WHERE `post_ID` = '".$id."' LIMIT 1");
		run_filters('entry_deleted',$id);
		return true;
	}
	return false;
}

#Function: bj_new_section()
#Description: Creates a section.
function bj_new_section($inline=false) {
	global $bj_db;
	if(we_can('edit_sections')) {
		run_actions('pre_new_section');
		#Prevent the system from calling bj_new_section() twice on ajax.
		if(!$inline and $_GET['req'] == 'ajaxadd') {
			return false;
		}
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($section['title']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section['stylesheet'] = bj_clean_string($_POST['stylesheet']);
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
		if($inline) {
			return $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `title` = '".$section['title']."' LIMIT 1");
		}
		else {
			@header("Location: ".load_option('siteurl')."admin/sections.php");
			die();
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
		if($id == 0 or !$former) {
			return false;
		}
		if(isset($_POST['save-del'])) {
			$bj_db->query("DELETE FROM `".$bj_db->sections."` WHERE `ID` = '".$id."' LIMIT 1");
			@header("Location: ".load_option('siteurl')."admin/sections.php?deleted=true");
			die();
		}
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($section['title']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['last_updated'] = date('Y-m-d H:i:s',time());
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section['stylesheet'] = bj_clean_string($_POST['stylesheet']);
		$section = run_filters('section_edit',$section);
		#Query query.
		$query = "UPDATE `".$bj_db->sections."` SET `ID` = '".$id."'";
		foreach($section as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj_db->query($query);
		@header("Location: ".load_option('siteurl')."admin/sections.php");
		die();
	}
}

#Function: bj_delete_section(ID)
#Description: Deletes our special little section.
function bj_delete_section($id=0) {
	global $bj_db;
	if(we_can('edit_sections')) {
		$bj_db->query("DELETE FROM `".$bj_db->sections."` WHERE `ID` = '".$id."' LIMIT 1");
		$bj_db->query("DELETE FROM `".$bj_db->entries."` WHERE `section` = '".$id."'");
		run_filters('section_deleted',$id);
		return true;
	}
	return false;
}

#Function: bj_new_tag(Inline)
#Description: Inserts a new tag. Can either be ajax'd or a regular header redirect.
function bj_new_tag($inline=false) {
	global $bj_db;
	if(we_can('edit_tags') and $_POST['name']) {
		run_actions('pre_new_tag');
		#Prevent the system from calling bj_new_tag() twice.
		if(!$inline and $_GET['req'] == 'ajaxadd') {
			return false;
		}
		$tag['name'] = bj_clean_string($_POST['name']);
		$tag['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($tag['name']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$tag['posts_num'] = 0;
		$tag = run_filters('tag_new',$tag);
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj_db->tags."`";
		foreach($tag as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`".$keys.")";
		$query .= " VALUES (''".$values.")";
		$bj_db->query($query);
		if($inline) {
			return $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `name` = '".$tag['name']."' LIMIT 1");
		}
		else {
			@header("Location: ".load_option('siteurl')."admin/tags.php");
			die();
		}
	}
}

#Function: bj_edit_tag(ID)
#Description: Edit our tag. Return our user.
function bj_edit_tag($id = 0) {
	global $bj_db;
	if(we_can('edit_tags')) {
		run_actions('pre_edit_tag');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `ID` = '".$id."' LIMIT 1");
		if($id == 0 or !$former) {
			return false;
		}
		$tag['name'] = bj_clean_string($_POST['name']);
		$tag['shortname'] = (empty($_POST['shortname'])) ? bj_shortname($tag['name']) : bj_shortname(bj_clean_string($_POST['shortname']));
		$tag['posts_num'] = 0;
		$tag = run_filters('tag_edit',$tag);
		$query = "UPDATE `".$bj_db->tags."` SET `ID` = '".$id."'";
		foreach($tag as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj_db->query($query);
		@header("Location: ".load_option('siteurl')."admin/tags.php");
		die();
	}
}

#Function: bj_delete_tag(ID)
#Description: Gone with the tag.
function bj_delete_tag($id=0) {
	global $bj_db;
	if(we_can('edit_tags')) {
		$bj_db->query("DELETE FROM `".$bj_db->tags."` WHERE `ID` = '".$id."' LIMIT 1");
		run_filters('tag_deleted',$id);
		return true;
	}
	return false;
}

#Function: bj_edit_comment(ID)
#Description: Edits the comment.
function bj_edit_comment($id=0) {
	global $bj_db;
	if(we_can('edit_comments')) {
		run_actions('pre_edit_comment');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj_db->comments."` WHERE `ID` = '".$id."' LIMIT 1");
		if($id == 0 or !$former) {
			return false;
		}
		$comment['author_name'] = bj_clean_string($_POST['author_name']);
		$comment['author_email'] = bj_clean_string($_POST['author_email']);
		$comment['author_url'] = bj_clean_string($_POST['author_url']);
		$comment['status'] = bj_clean_string($_POST['status']);
		$comment['content'] = bj_clean_string($_POST['content'],get_html());
		if($_POST['editstamp'] == "yes") {
			$post['posted_on'] = intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']);
		}
		$comment = run_filters('comment_edit',$comment);
		$query = "UPDATE `".$bj_db->comments."` SET `ID` = '".$id."'";
		foreach($comment as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj_db->query($query);
		@header("Location: ".load_option('siteurl')."admin/comments.php");
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

?>
