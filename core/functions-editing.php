<?php

function bj_edit_entry($id,$entry=array()) {
	global $bj;
	if(we_can('edit_entries')) {
		run_actions('pre_entry_edit');
		$id = intval($id);
		$retquery = new bj_entries(0,1);
		$retquery->setID($id);
		$former = $retquery->fetch();
		if(!$former)
			return false;
		else
			$former = $former[0];
		$tag_string = array();
		if(is_array($entry['tags'])) {
			foreach($entry['tags'] as $tag=>$on) {
				$tag_string[] = intval($tag).'';
				#If the post is public and the tag was added in this edit.
				if(!in_array($tag.'',unserialize($former['tags']),true))
					$bj->db->query("UPDATE ".$bj->db->tags." SET posts_num = posts_num + 1 WHERE ID = ".$tag);
			}
		}
		#Was a tag removed?
		foreach(unserialize($former['tags']) as $tag)
			if(!in_array($tag,$tag_string,true))
				$bj->db->query("UPDATE ".$bj->db->tags." SET posts_num = posts_num - 1 WHERE ID = ".$tag);
		$bj->cache->drop_cache('tags');
		$entry['tags'] = serialize($tag_string);
		if($entry['meta'] and is_array($entry['meta']))
			foreach($entry['meta'] as $key=>$value)
				if(empty($value))
					unset($entry['meta'][$key]);
		$retquery->restore();
		$retquery->setLimit(false,false);
		$retquery->fromCache(false);
		$retquery->setTitle($entry['title']);
		$entries = $retquery->fetch();
		if($entries)
			$entry['shortname'] .= '-'.count($entries);
		$entry['meta'] = serialize(bj_clean_deep($entry['meta']));
		$entry = run_actions('entry_edit',$entry);
		#Now let's build our update query.
		$query = "UPDATE ".$bj->db->entries." SET ID = '".$id."'";
		foreach($entry as $key=>$value)
			if(isset($former[$key]) and $former[$key] != $value)
				$query .= ", ".$key." = '".$value."'";
		$query .= " WHERE ID = ".$id." LIMIT 1";
		$bj->db->query($query);
		$bj->cache->drop_caches('entries');
		return $entry;
	}
}

#Function: bj_new_entry()
#Description: Creates a post and handles where the user goes.
function bj_new_entry($entry=array()) {
	global $bj;
	if(we_can('write_entries')) {
		run_actions('pre_entry_new');
		$tag_string = array();
		if(is_array($entry['tags'])) {
			foreach($entry['tags'] as $tag=>$on) {
				$tag_string[] = intval($tag).'';
				$bj->db->query("UPDATE ".$bj->db->tags." SET posts_num = posts_num + 1 WHERE ID = ".$tag);
			}
		}
		$bj->cache->drop_cache('tags');
		$entry['tags'] = serialize($tag_string);
		if($entry['meta'] and is_array($entry['meta']))
			foreach($entry['meta'] as $key=>$value)
				if(empty($value))
					unset($entry['meta'][$key]);
		$entry['meta'] = serialize(bj_clean_deep($entry['meta']));
		$retquery = new bj_entries(false,false,false);
		$retquery->setTitle($entry['title']);
		$entries = $retquery->fetch();
		#Prevent duplicate shortnames, thus breaking the tubes.
		if($entries)
			$entry['shortname'] .= '-'.count($entries);
		$entry = run_actions('entry_new',$entry);
		#Now let's build our insert query.
		$keys = ''; $values = '';
		$query = "INSERT INTO ".$bj->db->entries;
		foreach($entry as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= " (`ID`".$keys.")";
		$query .= " VALUES (''".$values.")";
		$bj->db->query($query);
		$bj->cache->drop_caches('entries');
		$retquery->restore();
		$retquery->setLimit(0,1);
		$retquery->fromCache(false);
		$retquery->setShortname($entry['shortname']);
		$entries = $retquery->fetch();
		return $entries[0];
	}
}

#Function: bj_delete_entry(ID)
#Description: Kill, kill!
function bj_delete_entry($id=0) {
	global $bj;
	if(we_can('edit_entries')) {
		$query = new bj_entries(0,1,false);
		$query->setID($id);
		$entry = $query->fetch();
		if($entry[0]) {
			run_actions('deleting_entry',$id);
			$bj->db->query("DELETE FROM `".$bj->db->entries."` WHERE `ID` = '".$id."' LIMIT 1");
			foreach(unserialize($entry[0]['tags']) as $tag)
				$bj->db->query("UPDATE `".$bj->db->tags."` SET `posts_num` = posts_num - 1 WHERE `ID` = ".$tag);
			$bj->db->query("DELETE FROM `".$bj->db->comments."` WHERE `post_ID` = '".$id."' LIMIT 1");
			$bj->cache->drop_caches('entries');
			return true;
		}
	}
	return false;
}

#Function: bj_new_section()
#Description: Creates a section.
function bj_new_section() {
	global $bj;
	if(we_can('edit_sections')) {
		run_actions('pre_new_section');
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = bj_shortname($section['title']);
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section['stylesheet'] = bj_clean_string($_POST['stylesheet']);
		$section = run_actions('section_new',$section);
		#Query query.
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj->db->sections."`";
		foreach($section as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`,`last_updated`".$keys.")";
		$query .= " VALUES ('','".date('Y-m-d H:i:s')."'".$values.")";
		$bj->db->query($query);
		$bj->cache->drop_cache('sections');
		return $bj->db->get_item('SELECT * FROM '.$bj->db->sections.' WHERE title = \''.$section['title'].'\' LIMIT 1');
	}
}

#Function: bj_edit_section(ID)
#Description: Edit a section. Go from there.
function bj_edit_section($id=0) {
	global $bj;
	if(we_can('edit_sections')) {
		run_actions('pre_edit_section');
		$id = intval($id);
		$former = $bj->db->get_item("SELECT * FROM ".$bj->db->sections." WHERE ID = '".$id."' LIMIT 1");
		if(!$former) {
			return false;
		}
		$section['title'] = bj_clean_string($_POST['title']);
		$section['shortname'] = bj_shortname($section['title']);
		$section['hidden'] = bj_clean_string($_POST['hidden']);
		$section['last_updated'] = date('Y-m-d H:i:s',time());
		$section['page_order'] = (empty($_POST['page_order'])) ? 0 : intval($_POST['page_order']);
		$section['handler'] = bj_clean_string($_POST['handler']);
		$section['stylesheet'] = bj_clean_string($_POST['stylesheet']);
		$section = run_actions('section_edit',$section);
		#Query query.
		$query = "UPDATE `".$bj->db->sections."` SET `ID` = '".$id."'";
		foreach($section as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj->db->query($query);
		$bj->cache->drop_caches('section',$id);
	}
}

#Function: bj_delete_section(ID)
#Description: Deletes our special little section.
function bj_delete_section($id=0) {
	global $bj;
	if(we_can('edit_sections')) {
		$bj->db->query("DELETE FROM `".$bj->db->sections."` WHERE `ID` = '".$id."' LIMIT 1");
		$bj->db->query("DELETE FROM `".$bj->db->entries."` WHERE `section` = '".$id."'");
		$bj->cache->drop_caches('section',$id);
		run_actions('section_deleted',$id);
		return true;
	}
	return false;
}

#Function: bj_new_tag(Inline)
#Description: Inserts a new tag. Can either be ajax'd or a regular header redirect.
function bj_new_tag() {
	global $bj;
	if(we_can('edit_tags') and $_POST['name']) {
		run_actions('pre_new_tag');
		$tag['name'] = bj_clean_string($_POST['name']);
		$tag['shortname'] = bj_shortname($tag['name']);
		$tag['posts_num'] = 0;
		$tag = run_actions('tag_new',$tag);
		$keys = ''; $values = '';
		$query = "INSERT INTO `".$bj->db->tags."`";
		foreach($tag as $key=>$value) {
			$keys .= ", `".$key."`";
			$values .= ", '".$value."'";
		}
		$query .= "(`ID`".$keys.")";
		$query .= " VALUES (''".$values.")";
		$bj->db->query($query);
		return $bj->db->get_item("SELECT * FROM `".$bj->db->tags."` WHERE `name` = '".$tag['name']."' LIMIT 1");
	}
}

#Function: bj_edit_tag(ID)
#Description: Edit our tag. Return our user.
function bj_edit_tag($id = 0) {
	global $bj;
	if(we_can('edit_tags')) {
		run_actions('pre_edit_tag');
		$id = intval($id);
		$former = $bj_db->get_item("SELECT * FROM `".$bj->db->tags."` WHERE `ID` = '".$id."' LIMIT 1");
		if(!$former)
			return false;
		$tag['name'] = bj_clean_string($_POST['name']);
		$tag['shortname'] = bj_shortname($tag['name']);
		$tag['posts_num'] = 0;
		$tag = run_actions('tag_edit',$tag);
		$query = "UPDATE `".$bj->db->tags."` SET `ID` = '".$id."'";
		foreach($tag as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj->db->query($query);
		$bj->db->drop_cache('tags');
	}
}

#Function: bj_delete_tag(ID)
#Description: Gone with the tag.
function bj_delete_tag($id=0) {
	global $bj;
	if(we_can('edit_tags')) {
		$bj->db->query("DELETE FROM `".$bj->db->tags."` WHERE `ID` = '".$id."' LIMIT 1");
		$bj->cache->drop_cache('tags');
		run_actions('tag_deleted',$id);
		return true;
	}
	return false;
}

#Function: bj_new_comment()
#Description: Creates a comment. Also increments the post ownership comment count by one.
function bj_new_comment($force_addition=false,$check_for_user=true) {
	global $bj;
	run_actions('pre_new_comment');
	$entry = get_entry('id='.intval($_POST['post_id']));
	if(!$entry or (load_option('disable_commenting') == 1 and $entry['comments_open'] == 0 and !$force_addition)) {
		return false;
	}
	if($bj->user and $check_for_user) {
		$comment['user_id'] = $bj->user['ID'];
		$comment['author_name'] = $bj->user['display_name'];
		$comment['author_email'] = $bj->user['email'];
		$comment['author_url'] = $bj->user['website'];
	}
	else {
		$comment['author_name'] = bj_clean_string($_POST['author_name']);
		$comment['author_email'] = bj_clean_string($_POST['author_email']);
		$comment['author_url'] = bj_clean_string($_POST['author_url']);
	}
	$comment['status'] = 'normal';
	$comment['content'] = bj_clean_string($_POST['content'],get_html_comment());
	$comment['posted_on'] = date('Y-m-d H:i:s');
	$comment['post_id'] = intval($_POST['post_id']);
	$comment['author_IP'] = $_SERVER['REMOTE_ADDR'];
	$comment = run_actions('comment_new',$comment);
	#Now let's build our insert query.
	$keys = ''; $values = '';
	$query = "INSERT INTO `".$bj->db->comments."`";
	foreach($comment as $key=>$value) {
		$keys .= ", `".$key."`";
		$values .= ", '".$value."'";
	}
	$query .= "(`ID`".$keys.")";
	$query .= " VALUES (''".$values.")";
	$bj->db->query($query);
	$bj->db->query("UPDATE `".$bj->db->entries."` SET `comment_count` = comment_count + 1 WHERE `ID` = ".$comment['post_id']);
	$bj->cache->drop_caches('entries');
	return get_comment('posted_on='.urlencode($comment['posted_on']).'&author='.urlencode($comment['author_name']));
}
	
	

#Function: bj_edit_comment(ID)
#Description: Edits the comment.
function bj_edit_comment($id=0) {
	global $bj;
	if(we_can('edit_comments')) {
		run_actions('pre_edit_comment');
		$id = intval($id);
		$former = get_comment('id='.$id);
		if(!$former)
			return false;
		$comment['author_name'] = bj_clean_string($_POST['author_name']);
		$comment['author_email'] = bj_clean_string($_POST['author_email']);
		$comment['author_url'] = bj_clean_string($_POST['author_url']);
		$comment['status'] = bj_clean_string($_POST['status']);
		$comment['content'] = bj_clean_string($_POST['content'],get_html_comment());
		$comment['posted_on'] = intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']);
		$comment = run_actions('comment_edit',$comment);
		$query = "UPDATE `".$bj->db->comments."` SET `ID` = '".$id."'";
		foreach($comment as $key=>$value) {
			if(isset($former[$key]) and $former[$key] != $value) {
				$query .= ", `".$key."` = '".$value."'";
			}
		}
		$query .= " WHERE `ID` = ".$id." LIMIT 1";
		$bj->db->query($query);
		return get_comment('id='.$id);
	}
}

#Function: bj_delete_comment(ID)
#Description: Deleted.
function bj_delete_comment($id) {
	global $bj;
	$comment = get_comment('id='.$id);
	if($comment) {
		$bj->db->query("DELETE FROM `".$bj->db->comments."` WHERE `ID` = '".$id."' LIMIT 1");
		$bj->db->query("UPDATE `".$bj->db->entries."` SET `comment_count` = comment_count - 1 WHERE `ID` = ".$comment['post_ID']);
		$bj->cache->drop_caches('entries');
	}
}

#Function: bj_clean_string(String, Allowed HTML[, Args])
#Description: Cleans anything within the string using kses,
#			  mysql_real_escape_string, and a few others.
function bj_clean_string($string,$allowed_html=array()) {
	global $bj;
	if(get_magic_quotes_gpc())
		$string = stripslashes($string);
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
	$string = $bj->db->escape($string);
	return run_actions('clean_string',$string);
}

function bj_clean_deep($array,$allowed_html=array()) {
	foreach($array as $key=>$bit)
		$array[$key] = bj_clean_string($bit,$allowed_html);
	return $array;
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
