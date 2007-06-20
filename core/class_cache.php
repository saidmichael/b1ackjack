<?php

class bj_cache { #Hey, it rhymes!
	var $cache_on = true;
	var $refresh_every = 120; #In minutes
	var $cache_path = false;

	function bj_cache() {
		$this->cache_path = BJPATH . 'content/cache/'; #I don't know either; putting it at the declaration just
								#Doesn't seem to work with BJPATH.
		if(!defined('CACHE_ON') or CACHE_ON === false or !is_writable($this->cache_path))
			$this->cache_on = false;
		else
			$this->polish_caches();
	}

	function polish_caches() {
		require_once(BJPATH . 'core/functions-general.php');
		$refresh_seconds = $this->refresh_every*60;
		$files = FileFolderList($this->cache_path);
		if($files['files'])
			foreach($files['files'] as $file)
				if(time() - filemtime($file) > $refresh_seconds)
					@unlink($file);
	}
	
	function get_comments($entry=0,$use_cache=true) {
		global $bj;
		if($entry == 0) {
			if($use_cache and $this->cache_on and file_exists($this->cache_path.'comments'))
				return $this->read_cache($this->cache_path.'comments');
			else {
				$all_comments = array();
				$comments = $bj->db->get_rows('SELECT * FROM '.$bj->db->comments.' ORDER BY posted_on ASC');
				if($comments)
					foreach($comments as $comment)
						$all_comments[$comment['post_ID']][] = $comment;
				if($this->cache_on and $comments)
					$this->write_cache($this->cache_path.'comments',$all_comments);
				return $all_comments;
			}
		}
		else {
			if($use_cache and $this->cache_on and file_exists($this->cache_path.'comments-'.$entry))
				return $this->read_cache($this->cache_path.'comments-'.$entry);
			else {
				$comments = $bj->db->get_rows('SELECT * FROM '.$bj->db->comments.' WHERE post_ID = \''.$entry.'\' ORDER BY posted_on ASC');
				if($this->cache_on and $comments)
					$this->write_cache($this->cache_path.'comments-'.$entry,$comments);
				return $comments;
			}
		}
	}
	
	function get_entries($type='all',$id=0,$use_cache=true) {
		global $bj;
		#What do we want to get?
		switch($type) {
		case 'all' :
			$extra = '';
			$cache_key = '';
			break;
		case 'tags' :
			$extra = ' WHERE tags REGEXP \'"'.$id.'"\' AND ptype = \'public\' ORDER BY posted DESC';
			$cache_key = '-tag-'.$id;
			break;
		case 'section' :
			$extra = ' WHERE section = \''.$id.'\' AND ptype = \'public\' ORDER BY posted DESC';
			$cache_key = '-section-'.$id;
			break;
		case 'author' :
			$extra = ' WHERE author = \''.$id.'\' AND ptype = \'public\' ORDER BY posted DESC';
			$cache_key = '-author-'.$id;
			break;
		#Custom get type. Allows for query manipulation.
		default :
			$extra = run_actions('query_extra_'.$type,'');
			$cache_key = $type;
		}
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'entries'.$cache_key))
			return $this->read_cache($this->cache_path.'entries'.$cache_key);
		else {
			$entries = $bj->db->get_rows('SELECT * FROM '.$bj->db->entries.$extra);
			if($this->cache_on and $entries)
				$this->write_cache($this->cache_path.'entries'.$cache_key,$entries);
			return $entries;
		}
	}
	
	function get_options($use_cache=true) {
		global $bj;
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'options'))
			return $this->read_cache($this->cache_path.'options');
		else {
			$newopts = array();
			$options = $bj->db->get_rows('SELECT * FROM '.$bj->db->options.' ORDER BY ID ASC');
			if($options)
				foreach($options as $option)
					$newopts[$option['option_name']] = $option['option_value'];
			if($this->cache_on and $newopts != array())
				$this->write_cache($this->cache_path.'options',$newopts);
			return $newopts;
		}
	}
	
	function get_sections($use_cache=true) {
		global $bj;
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'sections'))
			return $this->read_cache($this->cache_path.'sections');
		else {
			$sections = $bj->db->get_rows('SELECT * FROM '.$bj->db->sections.' ORDER BY page_order ASC');
			if($this->cache_on and $sections)
				$this->write_cache($this->cache_path.'sections',$sections);
			return $sections;
		}
	}
	
	function get_section($section,$use_cache=true) {
		global $bj;
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'section-'.$section))
			return $this->read_cache($this->cache_path.'section-'.$section);
		else {
			$tmp_section = $bj->db->get_item('SELECT * FROM '.$bj->db->sections.' WHERE ID = \''.$section.'\' LIMIT 1');
			if($this->cache_on and $tmp_section)
				$this->write_cache($this->cache_path.'section-'.$section,$tmp_section);
			return $tmp_section;
		}
	}
	
	function get_tags($use_cache=true) {
		global $bj;
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'tags'))
			return $this->read_cache($this->cache_path.'tags');
		else {
			$tmp_tags = $bj->db->get_rows('SELECT * FROM '.$bj->db->tags.' ORDER BY name ASC');
			if($this->cache_on and $tmp_tags)
				$this->write_cache($this->cache_path.'tags',$tmp_tags);
			return $tmp_tags;
		}
	}
	
	function get_user($id,$use_cache=true) {
		global $bj;
		$id = intval($id);
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'user-'.$id))
			return $this->read_cache($this->cache_path.'user-'.$id);
		else {
			$tmp_user = $bj->db->get_item("SELECT * FROM `".$bj->db->users."` WHERE `ID` = ".$id." LIMIT 1");
			if($tmp_user) {
				$metadata = $bj->db->get_rows("SELECT * FROM `".$bj->db->usermeta."` WHERE `user_id` = '".$tmp_user['ID']."'");
				if($metadata) {
					foreach($metadata as $data) {
						if(!isset($tmp_user[$data['meta_key']])) {
							$tmp_user[$data['meta_key']] = $data['meta_value'];
							$tmp_user['meta'][$data['meta_key']] = $data['meta_value'];
						}
					}
				}
				if($this->cache_on)
					$this->write_cache($this->cache_path.'user-'.$id,$tmp_user);
			}
			else
				$tmp_user = false;
			return $tmp_user;
		}
	}
	
	function get_user_bylogin($login,$use_cache=true) {
		global $bj;
		$login = bj_clean_string($login);
		if($use_cache and $this->cache_on and file_exists($this->cache_path.'userlogin-'.bj_shortname($login)))
			return $this->read_cache($this->cache_path.'userlogin-'.$login);
		else {
			$tmp_user = $bj->db->get_item("SELECT * FROM ".$bj->db->users." WHERE user_login = '".$login."' LIMIT 1");
			if($tmp_user) {
				$metadata = $bj->db->get_rows("SELECT * FROM `".$bj->db->usermeta."` WHERE `user_id` = '".$tmp_user['ID']."'");
				if($metadata) {
					foreach($metadata as $data) {
						if(!isset($tmp_user[$data['meta_key']])) {
							$tmp_user[$data['meta_key']] = $data['meta_value'];
							$tmp_user['meta'][$data['meta_key']] = $data['meta_value'];
						}
					}
				}
				if($this->cache_on)
					$this->write_cache($this->cache_path.'userlogin-'.bj_shortname($login),$tmp_user);
			}
			else
				$tmp_user = false;
			return $tmp_user;
		}
	}
	
	function read_cache($location) {
		$handle = fopen($location, "r");
		$contents = fread($handle, filesize($location));
		fclose($handle);
		return unserialize($contents);
	}
	
	function write_cache($location,$contents) {
		$handle = fopen($location, "w+");
		fwrite($handle,serialize($contents));
		fclose($handle);
	}
	
	function drop_cache($type,$id=0) {
		switch($type) {
		case 'user' :
			$file = 'user-'.$id;
			break;
		case 'userlogin' :
			$file = 'userlogin-'.bj_shortname($id);
			break;
		case 'options' :
			$file = 'options';
			break;
		case 'tags' :
			$file = 'tags';
			break;
		case 'sections' :
			$file = 'sections';
			break;
		}
		
		if(file_exists($this->cache_path.$file))
			@unlink($this->cache_path.$file);
	}
	
	function drop_caches($type,$id=0) {
		switch($type) {
		case 'section' :
			$files = array($this->cache_path.'section-'.$id,$this->cache_path.'sections');
			break;
		case 'entries' :
			$files = glob($this->cache_path.'entries*');
			break;
		case 'users' :
			$files = glob($this->cache_path.'user-*');
		}
		if(is_array($files))
			foreach($files as $file)
				@unlink($file);
	}
}

$bj->cache = new bj_cache();

?>
