<?php

#Function: echo_tags(Between[, Before[, After[, Extra]]])
#Description: Outputs the tags for a post. Can be used only in a loop. 
function echo_tags($between=', ',$before='',$after='',$extra=false) {
	$tags = return_tags();
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		foreach($tags as $tag) {
			if($args['admin'] == "true") {
				$start_link = "<a href=\"tags.php?req=edit&amp;id=".$tag['ID']."\">";
			}
			else {
				if(defined('BJ_REWRITE')) {
					$start_link = "<a href=\"".load_option('siteurl')."tag/".$tag['shortname']."/\">";
				}
				else {
					$start_link = "<a href=\"".load_option('siteurl')."index.php?req=tag&amp;name=".$tag['shortname']."\">";
				}
			}
			$text .= $before.$start_link.$tag['name']."</a>".$after.$between;
		}
		echo preg_replace('{'.$between.'$}', '', $text);
	}
}

#Function: rss_tags(Between[, Before[, After[, Extra]]])
#Description: Outputs the tags for a post, RSS-style.
function rss_tags($between='',$before='',$after='',$extra=false) {
	$tags = return_tags();
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		$text = '';
		foreach($tags as $tag) {
			$text .= $before.'<dc:subject>'.$tag['name'].'</dc:subject>'.$after.$between;
		}
		echo preg_replace('{'.$between.'$}', '', $text);
	}
}

#Function: return_tags(Extra)
#Description: Returns the tags for a post. Can be used only in a loop. 
function return_tags($extra=false) {
	global $post,$posts,$bj_db;
	$tags = explode(",",$post['tags']);
	$retarr = array();
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		foreach($tags as $ID) {
			//This checks if the information about a tag was already retrieved.
			//After all, why do extra queries?
			if(isset($posts['tagbuffer'][$ID])) {
				$arr = $posts['tagbuffer'][$ID];
			}
			else {
				$arr = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `ID` = '".$ID."' LIMIT 1");
				$posts['tagbuffer'][$ID] = $arr;
			}
			$retarr[] = $arr;
		}
		return $retarr;
	}
	else {
		return array();
	}
}

#Function: echo_all_tags(Extra)
#Description: Lists all tags in existance.
function echo_all_tags($extra=false) {
	global $bj_db;
	if($extra) { parse_str($extra,$args); }
	$query = "SELECT * FROM `".$bj_db->tags."` WHERE `ID` != '0'";
	if(isset($args['depth'])) {
		if($args['depth'] == "flat") {
			$query .= " AND `parent` = '0'";
		}
	}
	if(isset($args['parent'])) {
		$query .= " AND `parent` = '".intval($args['parent'])."'";
	}
	if(isset($args['orderby'])) {
		$query .= " ORDER BY `".$args['sortby']."`";
	}
	if(isset($args['before'])) {
		$before = $args['before'];
	}
	if(isset($args['after'])) {
		$after = $args['after'];
	}
	$tags = $bj_db->get_rows($query,"ASSOC");
	foreach($tags as $tag) {
		if(defined('BJ_REWRITE')) {
			echo"<li class=\"list_tag\">".$before."<a href=\"".load_option('siteurl')."tag/".$tag['shortname']."/\">".$tag['name']."</a>".$after."</li>";
		}
		else {
			echo"<li class=\"list_tag\">".$before."<a href=\"".load_option('siteurl')."index.php?req=tag&amp;name=".$tag['shortname']."\">".$tag['name']."</a>".$after."</li>";
		}
	}
}

#Function: return_all_tags(Extra)
#Description: Returns all tags in existence.
function return_all_tags($extra=false) {
	global $bj_db;
	if($extra) { parse_str($extra,$args); }
	$query = "SELECT * FROM `".$bj_db->tags."` WHERE `ID` != '0'";
	if(isset($args['depth'])) {
		if($args['depth'] == "flat") {
			$query .= " AND `parent` = '0'";
		}
	}
	if(isset($args['parent'])) {
		$query .= " AND `parent` = '".intval($args['parent'])."'";
	}
	if(isset($args['orderby'])) {
		$query .= " ORDER BY `".$args['sortby']."`";
	}
	if(isset($args['before'])) {
		$before = $args['before'];
	}
	if(isset($args['after'])) {
		$after = $args['after'];
	}
	$tags = $bj_db->get_rows($query,"ASSOC");
	foreach($tags as $tag) {
		$thesetags[] = $tag;
	}
	return $thesetags;
}

#Function: post_author()
#Description: Wrapper for get_post_author().
function post_author() {
	echo get_post_author();
}

#Function: get_post_author()
#Description: Post author returned. Loop-only.
function get_post_author() {
	global $post;
	return run_filters('post_author',$post['author']);
}

#Function: get_posts(Stuff)
#Description: Grabs posts based on the "stuff" given.
function get_posts($q) {
	global $bj_db,$i,$newer,$older;
	parse_str($q,$stuff);
	$num_off = (isset($_GET['offset'])) ? intval($_GET['offset']) : 0; //So the offset will update itself automatically.
	$i = 0;
	$newer = $num_off-(isset($stuff['limit'])) ? $stuff['limit'] : load_option('entries_per_page');
	$older = $num_off+(isset($stuff['limit'])) ? $stuff['limit'] : load_option('entries_per_page');
	$query = "SELECT * FROM ".$bj_db->posts." WHERE";
	if(isset($stuff['id'])) {
		$query .= " `ID` = '".intval($stuff['id'])."'";
	} else {
		$query .= " `ID` != '0'"; #Just a filler so each value can start with "AND".
	}
	if(isset($stuff['second'])) {
		$query .= " AND SECOND(`posted`) = '".$stuff['second']."'";
	}
	if(isset($stuff['minute'])) {
		$query .= " AND MINUTE(`posted`) = '".$stuff['minute']."'";
	}
	if(isset($stuff['hour'])) {
		$query .= " AND HOUR(`posted`) = '".$stuff['hour']."'";
	}
	if(isset($stuff['day'])) {
		$query .= " AND DAYOFMONTH(`posted`) = '".$stuff['day']."'";
	}
	if(isset($stuff['month'])) {
		$query .= " AND MONTH(`posted`) = '".$stuff['month']."'";
	}
	if(isset($stuff['year'])) {
		$query .= " AND YEAR(`posted`) = '".$stuff['year']."'";
	}
	if(isset($stuff['shortname'])) {
		$query .= " AND `shortname` = '".$stuff['shortname']."'";
	}
	if(isset($stuff['tag'])) {
		$query .= " AND (";
		$tags = explode(",",$stuff['tag']);
		foreach($tags as $num=>$tag) {
			if($num != 0) {
				$query .= " OR ";
			}
			if(substr($tag,0,1) == "-") {
				$query .= "`tags` NOT REGEXP '".substr($tag,1)."'";
			}
			else {
				$query .= "`tags` REGEXP '".$tag."'";
			}
		}
		$query .= ")";
	}
	if(isset($stuff['type'])) {
		$query .= " AND `ptype` = '".$stuff['type']."'";
	}
	if(isset($stuff['author'])) {
		$query .= " AND `author` = '".$stuff['author']."'";
	}
	if(isset($stuff['sortby'])) {
		$query .= " ORDER BY `".$stuff['sortby']."`";
	}
	else {
		$query .= " ORDER BY `posted`";
	}
	if(isset($stuff['order'])) {
		$query .= " ".$stuff['order'];
	}
	else {
		$query .= " DESC";
	}
	#Limit and offset.
	if($stuff['num'] != "yes") {
		$offset = (isset($stuff['offset'])) ? $stuff['offset'] : $num_off;
		$limit = (isset($stuff['limit'])) ? $stuff['limit'] : load_option('entries_per_page');
		$query .= " LIMIT ".$offset.",".$limit;
		return $bj_db->get_rows($query,"ASSOC");
	}
	else {
		if($stuff['inclimit'] == "yes") {
			$offset = (isset($stuff['offset'])) ? $stuff['offset'] : $num_off;
			$limit = (isset($stuff['limit'])) ? $stuff['limit'] : load_option('entries_per_page');
			$query .= " LIMIT ".$offset.",".$limit;
		}
		return mysql_num_rows($bj_db->query($query));
	}
}

#Function: entry_edit_link([Text[, Before[, After]]])
#Description: Will display an edit link if the user is logged in and can edit posts.
function edit_entry_link($text='Edit',$before='',$after='') {
	if(we_can('edit_posts')) { ?><a href="<?php siteinfo('siteurl'); ?>admin/posts.php?req=edit&amp;id=<?php echo_ID(); ?>"><?php echo $text; ?></a><?php }
}

#Function: echo_ID()
#Description: Wrapper for return_ID().
function echo_ID() {
	echo return_ID();
}

#Function: return_ID()
#Description: Outputs the ID. Loop-only.
function return_ID() {
	global $post;
	return run_filters('post_id',$post['ID']);
}

#Function: get_post_date(Date Format[, Date Resource])
#Description: Creates the date from a mysql datetime format. Can be used in the
#			  loop or, if the resource is defined, outside of it. Your choice. :)
function get_post_date($format='F jS, Y',$res=false) {
	if(!$res) {
		global $post;
		$res = $post['posted'];
	}
	$time = mktime(substr($res,11,2),substr($res,14,2),substr($res,17,2),substr($res,5,2),substr($res,8,2),substr($res,0,4));
	return date($format,$time);
}

#Function: post_date(Date Format[, Date Resource])
#Description: Wrapper for get_post_date().
function post_date($format='F jS, Y',$res=false) {
	echo get_post_date($format,$res);
}

#Function: post_stuff()
#Description: Prepares variables and such for each post.
function start_post() {
	global $i;
	$i++;
}

#Function: return_title()
#Description: Returns the title. Can only be used in the loop.
function return_title() {
	global $post;
	$title = wptexturize($post['title']);
	return run_filters('post_title',$title);
}

#Function: echo_content()
#Description: Echos the content.
function echo_content() {
	global $post;
	$content = wptexturize($post['content']);
	echo run_filters('post_content',$content);
}

#Function: echo_title()
#Description: Wrapper for return_title().
function echo_title() {
	echo return_title();
}

#Function: echo_permalink()
#Description: Wrapper for return_permalink().
function echo_permalink() {
	echo return_permalink();
}

#Function: return_permalink()
#Description: Outputs the permalink.
function return_permalink() {
	global $post;
	if(defined('BJ_REWRITE')) {
		return load_option('siteurl').'entry/'.$post['shortname'].'/';
	}
	else {
		return load_option('siteurl').'index.php?req=entry&amp;name='.$post['shortname'];
	}
		
}

#Function: comments_link(No Comments[, One Comment[, Multiple Comments[, Comments Closed]]])
#Description: Comments link. Loop-only, kids!
function comments_link($none='0 Comments',$one='1 Comment',$multi='% Comments',$closed='Closed') {
	global $post;
	if($post['comments_open'] == 0) {
		$comments_text = $closed;
	}
	else {
		switch($post['comment_count']) {
			case 0 : $comments_text = $none; break;
			case 1 : $comments_text = $one; break;
			default : $comments_text = str_replace('%',$post['comment_count'],$multi);
		}
	}
?>
<a href="<?php echo_permalink(); ?>#comments"><?php echo $comments_text; ?></a><?php
}

#Function: get_post_type()
#Description: The post type.
function get_post_type() {
	global $post;
	return $post['ptype'];
}

#Function: next_post_link(Text[, Before[, After]])
#Description: Next post link.
function next_page_link($text,$before='',$after='',$args='') {
	global $posts,$query_string;
	parse_str($args,$a);
	$num = (isset($a['num'])) ? intval($a['num']) : load_option('entries_per_page');
	$older = intval($_GET['offset']) + $num;
	$posts_string = 'num=yes';
	if(is_tag()) {
		global $tag;
		$posts_string .= '&tag='.$tag['ID'];
	}
	elseif(is_section()) {
		global $section;
		if(!empty($section['tags'])) {
			$posts_string .= '&tag='.$section['tags'];
		}
	}
	if(get_posts($posts_string) - $older > 0 && !is_entry()) {
		if(!is_admin()) {
			if(defined('BJ_REWRITE')) {
				if(is_section()) {
					$extra_string = 'section/'.$_GET['name'].'/';
				}
				elseif(is_tag()) {
					$extra_string = 'tag/'.$_GET['name'].'/';
				}
				echo $before.'<a href="'.load_option('siteurl').$extra_string.'page/'.$older.'/">'.$text.'</a>'.$after;
			}
			else {
				if(is_section()) {
					$extra_string = 'req=section&amp;name='.$_GET['name'];
				}
				elseif(is_tag()) {
					$extra_string = 'req=tag&amp;name='.$_GET['name'];
				}
				echo $before.'<a href="'.load_option('siteurl').'index.php?'.$extra_string.'&amp;offset='.$older.'">'.$text.'</a>'.$after;
			}
		}
		else {
			echo $before.'<a href="'.load_option('siteurl').'admin/posts.php?offset='.$older.'">'.$text.'</a>'.$after;
		}
	}
}

#Function: prev_post_link(Text[, Before[, After]])
#Description: Previous post link.
function prev_page_link($text,$before='',$after='',$args='') {
	global $posts,$query_string;
	parse_str($args,$a);
	$num = (isset($a['num'])) ? intval($a['num']) : load_option('entries_per_page');
	$offset = intval($_GET['offset']);
	$newer = $offset - $num;
	$extra_string = '';
	if($offset > 0 && !is_entry()) {
		if(!is_admin()) {
			if(defined('BJ_REWRITE')) {
				if(is_section()) {
					$extra_string = 'section/'.$_GET['name'].'/';
				}
				elseif(is_tag()) {
					$extra_string = 'tag/'.$_GET['name'].'/';
				}
				echo $before.'<a href="'.load_option('siteurl').$extra_string.'page/'.$newer.'/">'.$text.'</a>'.$after;
			}
			else {
				if(is_section()) {
					$extra_string = 'req=section&amp;name='.$_GET['name'];
				}
				elseif(is_tag()) {
					$extra_string = 'req=tag&amp;name='.$_GET['name'];
				}
				echo $before.'<a href="'.load_option('siteurl').'index.php?'.$extra_string.'&amp;offset='.$newer.'">'.$text.'</a>'.$after;
			}
		}
		else {
			echo $before.'<a href="'.load_option('siteurl').'admin/posts.php?offset='.$newer.'">'.$text.'</a>'.$after;
		}
	}
}

#Function: return_sections()
#Description: Returns all sections in use. Also checks the current section.
function return_sections() {
	global $bj_db;
	$sections = $bj_db->get_rows("SELECT * FROM `".$bj_db->sections."` ORDER BY `page_order` ASC","ASSOC");
	$i = 0;
	foreach($sections as $this_section) {
		if(is_section($this_section['shortname'])) {
			$sections[$i]['this'] = 1;
		}
		else {
			$sections[$i]['this'] = 0;
		}
		$i++;
	}
	return run_filters('sections_array',$sections);
}

#Function: echo_sections()
#Description: Returns the sections in a list.
function echo_sections() {
	$sections = return_sections();
	foreach($sections as $this_section) { ?>
<li class="section-li<?php echo ($this_section['this'] == 1) ? ' current-section' : ''; ?>"><a href="<?php echo get_section_permalink($this_section); ?>"><?php echo wptexturize($this_section['title']); ?></a></li>
<?php
	}
}

#Function: get_section_permalink(This section)
#Description: Section permalink. I've been getting lazy when it comes to
#			  descriptions, haven't I?
function get_section_permalink($section) {
	if(load_option('default_section') == $section['shortname']) {
		return load_option('siteurl');
	}
	else {
		if(defined('BJ_REWRITE')) {
			return load_option('siteurl').'section/'.$section['shortname'].'/';
		}
		else {
			return load_option('siteurl').'index.php?req=section&amp;name='.$section['shortname'];
		}
	}
}

#Function: section_shortname()
#Description: Wrapper for get_section_shortname().
function setion_shortname() {
	echo get_section_shortname();
}

#Function: get_section_shortname()
#Description: The shortname for a section. Only for sections (obviously).
function get_section_shortname() {
	global $section;
	return run_filters('section_shortname',$section['shortname']);
}

?>