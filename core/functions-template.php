<?php

/*
 * ************************
 * Global Functions
 * ************************
 */

#Function: echo_all_tags(Extra)
#Description: Lists all tags in existance.
function echo_all_tags($extra=false) {
	global $bj_db;
	$tags = return_all_tags($extra);
	if($tags) {
		foreach($tags as $tag) {
			if(defined('BJ_REWRITE')) {
				echo"<li class=\"list_tag\">".$before."<a href=\"".load_option('siteurl')."tag/".$tag['shortname']."\">".$tag['name']."</a>".$after."</li>";
			}
			else {
				echo"<li class=\"list_tag\">".$before."<a href=\"".load_option('siteurl')."index.php?req=tag&amp;name=".$tag['shortname']."\">".$tag['name']."</a>".$after."</li>";
			}
		}
	}
}

#Function: return_all_tags(Extra)
#Description: Returns all tags in existence.
function return_all_tags($extra=false) {
	global $bj_db;
	if($extra) { parse_str($extra,$args); }
	$query = "SELECT * FROM `".$bj_db->tags."`";
	if(isset($args['id'])) {
		$query .= " WHERE `ID` = '".intval($args['id'])."'";
	}
	else {
		 $query .= " WHERE `ID` != '0'";
	}
	if(isset($args['orderby'])) {
		$query .= " ORDER BY `".$args['orderby']."`";
	}
	else {
		$query .= " ORDER BY `name`";
	}
	if(isset($args['order'])) {
		$query .= " ".$args['order'];
	}
	else {
		$query .= " ASC";
	}
	if(isset($args['offset'])) {
		$query .= " OFFSET ".intval($args['offset']);
	}
	if(isset($args['limit'])) {
		$query .= " LIMIT ".intval($args['limit']);
	}
	$tags = $bj_db->get_rows($query);
	return $tags;
}

/*
 * ************************
 * Post Functions
 * ************************
 */

#Function: get_entries(Stuff)
#Description: Grabs entries based on the "stuff" given.
function get_entries($q) {
	global $bj_db,$i,$__offset;
	parse_str($q,$stuff);
	$i = 0;
	$__offset = (isset($__offset)) ? $__offset : 0;
	$query = "SELECT * FROM ".$bj_db->entries." WHERE";
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
				$query .= "`tags` NOT REGEXP '\"".substr($tag,1)."\"'";
			}
			else {
				$query .= "`tags` REGEXP '\"".$tag."\"'";
			}
		}
		$query .= ")";
	}
	if(isset($stuff['section'])) {
		$query .= " AND `section` = '".$stuff['section']."'";
	}
	if(isset($stuff['type'])) {
		$query .= " AND `ptype` = '".$stuff['type']."'";
	}
	if(isset($stuff['author'])) {
		$query .= " AND `author` = '".$stuff['author']."'";
	}
	if(isset($stuff['search'])) {
		$search = str_replace('+',' ',$stuff['search']);
		$search = explode(' ',$search);
		$query .= " AND (((`title` LIKE '%".$search[0]."%') OR (`content` LIKE '%".$search[0]."%'))";
		for ( $i = 1; $i < count($search); $i++) {
			$query .= " OR ((`title` LIKE '%".$search[$i]."%') OR (`content` LIKE '%".$search[$i]."%'))";
		}
		$query .= ')';
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
	if($stuff['inclimit'] != "false") {
		$loffset = (isset($stuff['offset'])) ? $stuff['offset'] : $__offset;
		$limit = (isset($stuff['limit'])) ? $stuff['limit'] : load_option('entries_per_page');
		$query .= " LIMIT ".$loffset.",".$limit;
	}
	#Limit and offset.
	if($stuff['num'] == "yes") {
		return mysql_num_rows($bj_db->query($query));
	}
	else {
		return $bj_db->get_rows($query);
	}
}

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
					$start_link = "<a href=\"".load_option('siteurl')."tag/".$tag['shortname']."\">";
				}
				else {
					$start_link = "<a href=\"".load_option('siteurl')."index.php?load=tag/".$tag['shortname']."\">";
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
	global $entry,$entries,$bj_db;
	static $done = 0;
	static $alltags = array();
	if($done == 0) {
		$thesetags = return_all_tags();
		foreach($thesetags as $tag) {
			$alltags[$tag['ID']] = $tag;
		}
		$done = 1;
	}
	$tags = unserialize($entry['tags']);
	$retarr = array();
	
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		foreach($tags as $ID) {
			//This checks if the information about a tag was already retrieved.
			//After all, why do extra queries?
			if(isset($alltags[$ID])) {
				$retarr[] = $alltags[$ID];
			}
		}
		return $retarr;
	}
	else {
		return array();
	}
}

#Function: post_stuff()
#Description: Prepares variables and such for each post.
function start_entry() {
	global $i;
	$i++;
	run_actions('start_entry');
}

#Function: echo_ID()
#Description: Wrapper for return_ID().
function echo_ID() {
	echo return_ID();
}

#Function: return_ID()
#Description: Outputs the ID. Loop-only.
function return_ID() {
	global $entry;
	return run_filters('entry_id',$entry['ID']);
}

#Function: echo_title()
#Description: Wrapper for return_title().
function echo_title() {
	echo return_title();
}

#Function: return_title()
#Description: Returns the title. Can only be used in the loop.
function return_title() {
	global $entry;
	return run_filters('entry_title',$entry['title']);
}

#Function: echo_permalink()
#Description: Outputs the permalink.
function echo_permalink() {
	echo return_permalink();
}
function return_permalink() {
	global $entry;
	if(defined('BJ_REWRITE')) {
		return load_option('siteurl').'entry/'.$entry['shortname'];
	}
	else {
		return load_option('siteurl').'index.php?load=entry/'.$entry['shortname'];
	}
		
}

#Function: entry_author()
#Description: Entry author returned. Loop-only.
function entry_author() {
	echo get_entry_author();
}
function get_entry_author() {
	global $entry;
	return run_filters('entry_author',$entry['author']);
}

#Function: entry_date(Date Format[, Date Resource])
#Description: Creates the date from a mysql datetime format. Can be used in the
#			  loop or, if the resource is defined, outside of it. Your choice. :)
function entry_date($format='F jS, Y',$res=false) {
	echo get_entry_date($format,$res);
}
function get_entry_date($format='F jS, Y',$res=false) {
	if(!$res) {
		global $entry;
		$res = $entry['posted'];
	}
	$time = mktime(substr($res,11,2),substr($res,14,2),substr($res,17,2),substr($res,5,2),substr($res,8,2),substr($res,0,4));
	return date($format,$time);
}

#Function: edit_entry_link([Text[, Before[, After]]])
#Description: Will display an edit link if the user is logged in and can edit posts.
function edit_entry_link($text='Edit',$before='',$after='') {
	if(we_can('edit_entries')) { ?><a href="<?php siteinfo('siteurl'); ?>admin/entries.php?req=edit&amp;id=<?php echo_ID(); ?>"><?php echo $text; ?></a><?php }
}

#Function: echo_content()
#Description: Echos the content.
function echo_content() {
	echo return_content();
}
function return_content() {
	global $entry;
	$content = str_replace(run_filters('snippet_separator','__More__'),'<div class="more-separator"c id="more-'.return_ID().'"></div>',$entry['content']);
	return run_filters('entry_content',$content);
}

#Function: echo_snippet()
#Description: Returns the snippet of the entry.
function echo_snippet() {
	echo return_snippet();
}
function return_snippet() {
	global $entry;
	$content = explode(run_filters('snippet_separator','__More__'),$entry['content']);
	$content = $content[0];
	return run_filters('entry_snippet',$content);
}

#Function: more_link(Text)
#Description: Outputs a link to the full content. Super!
function more_link($text='Read More') {
	global $entry;
	if(strpos($entry['content'],run_filters('snippet_separator','__More__'))) { ?>
<a href="<?php echo_permalink(); ?>#more-<?php echo_ID(); ?>"><?php echo $text; ?></a>
<?php
	}
}

#Function: comments_link(No Comments[, One Comment[, Multiple Comments[, Comments Closed]]])
#Description: Comments link. Loop-only, kids!
function comments_link($none='0 Comments',$one='1 Comment',$multi='% Comments',$closed='Closed') {
	global $entry;
	if($entry['comments_open'] == 0) {
		$comments_text = $closed;
	}
	else {
		switch($entry['comment_count']) {
			case 0 : $comments_text = $none; break;
			case 1 : $comments_text = $one; break;
			default : $comments_text = str_replace('%',$entry['comment_count'],$multi);
		}
	}
?>
<a href="<?php echo_permalink(); ?>#comments"><?php echo $comments_text; ?></a><?php
}

#Function: get_entry_type()
#Description: The entry type.
function get_entry_type() {
	global $entry;
	return $entry['ptype'];
}

#Function: entry_freshness()
#Description: Displays the freshness. If it's within three days,
#			  it displays "new", within the week is "recent",
#			  and after that is "ancient". Good for styling.
function entry_freshness() {
	echo get_entry_freshness();
}
function get_entry_freshness() {
	global $entry;
	$ufresh = time() - get_entry_date('U');
	if($ufresh > (60*60*24*7)) { #Over a week?
		return _r('stale');
	}
	elseif((60*60*24*3) < $ufresh and $ufresh < (60*60*24*7)) { #Within a week?
		return _r('good');
	}
	elseif($ufresh < (60*60*24*3)) { #Within three days?
		return _r('fresh');
	}
}

#Function: in_tag(Tag ID)
#Description: Checks if an entry is tagged with a certain tag.
function in_tag($id=0) {
	$tags = return_tags();
	if(in_array($id,$tags)) {
		return true;
	}
	return false;
}

#Function: next_post_link(Text[, Before[, After]])
#Description: Next post link.
function next_page_link($text,$before='',$after='',$args='') {
	global $entries,$query_string,$__name_vars,$__offset;
	parse_str($args,$a);
	$num = (isset($a['num'])) ? intval($a['num']) : load_option('entries_per_page');
	$older = $__offset + $num;
	if(get_entries($query_string.'&num=yes&inclimit=false') - $older > 0 && !is_entry()) {
		if(!is_admin()) {
			if(is_section() and !is_front()) {
				$extra_string = 'section/'.$__name_vars[1].'/';
			}
			elseif(is_tag()) {
				$extra_string = 'tag/'.$__name_vars[1].'/';
			}
			elseif(is_search()) {
				$extra_string = 'search/'.$__name_vars[1].'/';
			}
			if(defined('BJ_REWRITE')) {	
				echo $before.'<a href="'.load_option('siteurl').$extra_string.'page/'.$older.'">'.$text.'</a>'.$after;
			}
			else {
				echo $before.'<a href="'.load_option('siteurl').'index.php?load='.$extra_string.'&amp;offset='.$older.'">'.$text.'</a>'.$after;
			}
		}
		else {
			if($_GET['req'] == 'filtertag' && $_GET['tag'] != '') {
				$extra_string = 'req=filtertag&amp;tag='.bj_clean_string($_GET['tag']).'&amp;';
			}
			elseif($_GET['req'] == 'filtersection' && $_GET['section'] != '') {
				$extra_string = 'req=filtersection&amp;section='.bj_clean_string($_GET['section']).'&amp;';
			}
			elseif(is_search()) {
				$extra_string = 'req=search&amp;s='.bj_clean_string($_GET['s']).'&amp;';
			}
			echo $before.'<a href="'.load_option('siteurl').'admin/entries.php?'.$extra_string.'offset='.$older.'">'.$text.'</a>'.$after;
		}
	}
}

#Function: prev_post_link(Text[, Before[, After]])
#Description: Previous post link.
function prev_page_link($text,$before='',$after='',$args='') {
	global $query_string,$__name_vars,$__offset;
	parse_str($args,$a);
	$__offset = (is_admin()) ? intval($_GET['offset']) : $__offset;
	$num = (isset($a['num'])) ? intval($a['num']) : load_option('entries_per_page');
	$newer = $__offset - $num;
	$extra_string = '';
	if($__offset > 0 && !is_entry()) {
		if(!is_admin()) {
			if(is_section()) {
				$extra_string = 'section/'.$__name_vars[1].'/';
			}
			elseif(is_tag()) {
				$extra_string = 'tag/'.$__name_vars[1].'/';
			}
			if(defined('BJ_REWRITE')) {
				echo $before.'<a href="'.load_option('siteurl').$extra_string.'page/'.$newer.'">'.$text.'</a>'.$after;
			}
			else {
				echo $before.'<a href="'.load_option('siteurl').'index.php?load='.$extra_string.'page/'.$newer.'">'.$text.'</a>'.$after;
			}
		}
		else {
			if($_GET['req'] == 'filtertag' && $_GET['tag'] != '') {
				$extra_string = 'req=filtertag&amp;tag='.$_GET['tag'].'&amp;';
			}
			elseif($_GET['req'] == 'filtersection' && $_GET['section'] != '') {
				$extra_string = 'req=filtersection&amp;section='.bj_clean_string($_GET['section']).'&amp;';
			}
			elseif(is_search()) {
				$extra_string = 'req=search&amp;s='.bj_clean_string($_GET['s']).'&amp;';
			}
			echo $before.'<a href="'.load_option('siteurl').'admin/entries.php?'.$extra_string.'offset='.$newer.'">'.$text.'</a>'.$after;
		}
	}
}

/*
 * ************************
 * Comments
 * ************************
 */
 
 #Function: get_comments(Stuff[, Extra])
#Description: Modeled after get_posts().
function get_comments($q,$extra=false) {
	global $bj_db,$entry;
	parse_str($q,$stuff);
	$query = "SELECT * FROM ".$bj_db->comments." WHERE";
	if(isset($stuff['id'])) {
		$query .= " `ID` = '".intval($stuff['id'])."'";
	} else {
		$query .= " `ID` != '0'"; #Just a filler so each value can start with "AND".
	}
	if(isset($stuff['postid'])) {
		$query .= " AND `post_ID` = '".intval($stuff['postid'])."'";
	}
	if(isset($stuff['second'])) {
		$query .= " AND SECOND(`posted_on`) = '".$stuff['second']."'";
	}
	if(isset($stuff['minute'])) {
		$query .= " AND MINUTE(`posted_on`) = '".$stuff['minute']."'";
	}
	if(isset($stuff['hour'])) {
		$query .= " AND HOUR(`posted_on`) = '".$stuff['hour']."'";
	}
	if(isset($stuff['day'])) {
		$query .= " AND DAYOFMONTH(`posted_on`) = '".$stuff['day']."'";
	}
	if(isset($stuff['month'])) {
		$query .= " AND MONTH(`posted_on`) = '".$stuff['month']."'";
	}
	if(isset($stuff['year'])) {
		$query .= " AND YEAR(`posted_on`) = '".$stuff['year']."'";
	}
	if(isset($stuff['status'])) {
		$query .= " AND `status` = '".$stuff['status']."'";
	}
	if(isset($stuff['author'])) {
		$query .= " AND `author_name` = '".$stuff['author']."'";
	}
	if(isset($stuff['search'])) {
		$search = str_replace('+',' ',$stuff['search']);
		$search = explode(' ',$search);
		$query .= " AND (((`author_name` LIKE '%".$search[0]."%') OR (`author_email` LIKE '%".$search[0]."%') OR (`author_url` LIKE '%".$search[0]."%') OR (`author_IP` LIKE '%".$search[0]."%') OR (`content` LIKE '%".$search[0]."%'))";
		for ( $i = 1; $i < count($search); $i++) {
			$query .= " OR ((`author_name` LIKE '%".$search[$i]."%') OR (`author_email` LIKE '%".$search[$i]."%') OR (`author_url` LIKE '%".$search[$i]."%') OR (`author_IP` LIKE '%".$search[$i]."%') OR (`content` LIKE '%".$search[$i]."%'))";
		}
		$query .= ')';
	}
	if(isset($stuff['sortby'])) {
		$query .= " ORDER BY `".$stuff['sortby']."`";
	}
	else {
		$query .= " ORDER BY `posted_on`";
	}
	if(isset($stuff['order'])) {
		$query .= " ".$stuff['order'];
	}
	else {
		$query .= " DESC";
	}
	if($extra) {
		$query .= $extra;
	}
	#Limit and offset.
	if($stuff['num'] != "yes") {
		return $bj_db->get_rows($query);
	}
	else {
		return mysql_num_rows($bj_db->query($query));
	}
}

#Function: comment_ID()
#Description: Returns the ID of the comment, filters applied.
function comment_ID() {
	echo return_comment_ID();
}
function return_comment_ID() {
	global $comment;
	return run_filters('comment_ID',$comment['ID']);
}

#Function: comment_name()
#Description: The author of the comment.
function comment_name() {
	echo return_comment_name();
}
function return_comment_name() {
	global $comment;
	return run_filters('commenter_name',$comment['author_name']);
}

#Function: comment_url()
#Description: The commenter's URL, if it exists.
function comment_url() {
	echo return_comment_url();
}
function return_comment_url() {
	global $comment;
	return run_filters('commenter_url',$comment['author_url']);
}

#Function: comment_email()
#Description: The commenter's email.
function comment_email() {
	echo return_comment_email();
}
function return_comment_email() {
	global $comment;
	return run_filters('commenter_email',$comment['author_email']);
}

#Function: comment_author_url()
#Description: Outputs a link, if applicable, with the author's name.
function comment_author_url() {
	echo get_comment_author_url();
}
function get_comment_author_url() {
	ob_start();
	if(return_comment_url() != '') { ?><a href="<?php comment_url(); ?>"><?php } comment_name(); if(return_comment_url() != '') { ?></a><?php }
	$text = ob_get_contents();
	ob_end_clean();
	return $text;
}

#Function: comment_date()
#Description: When the author wrote the comment.
function comment_date($format='F jS, Y') {
	echo return_comment_date($format);
}
function return_comment_date($format='F jS, Y') {
	global $comment;
	return get_entry_date($format,$comment['posted_on']);
}

#Function: comment_text()
#Description: What the commenter has to say.
function comment_text() {
	echo return_comment_text();
}
function return_comment_text() {
	global $comment;
	return run_filters('comment_text',$comment['content']);
}

#Function: comment_snippet()
#Description: A trimmed version of the comment. Defaults to 16 words.
function comment_snippet($words=15) {
	echo return_comment_snippet($words);
}
function return_comment_snippet($words=15) {
	global $comment;
	$prevtext = bj_kses($comment['content'],array());
	$text = explode(' ',$prevtext);
	$text = array_slice($text,0,$words);
	$text = implode(' ',$text);
	if($prevtext != $text) {
		$text .= '...';
	}
	return run_filters('comment_snippet',$text);
}

#Function comment_postid()
#Description: The post that the comment was posted in.
function comment_postid() {
	global $comment;
	return $comment['post_ID'];
}


/*
 * ************************
 * Sections
 * ************************
 */
 
 #Function: echo_sections()
#Description: Returns the sections in a list.
function echo_sections() {
	$sections = return_sections();
	foreach($sections as $this_section) { if($this_section['hidden'] != 'yes') { ?>
<li class="section-li<?php echo ($this_section['this'] == 1) ? ' current-section' : ''; ?>"><a href="<?php echo get_section_permalink($this_section); ?>"><?php echo $this_section['title']; ?></a></li>
<?php
	} }
}

#Function: return_sections()
#Description: Returns all sections in use. Also checks the current section.
function return_sections() {
	global $bj_db;
	$sections = $bj_db->get_rows("SELECT * FROM `".$bj_db->sections."` ORDER BY `page_order` ASC");
	$i = 0;
	if($sections) {
		foreach($sections as $section) {
			$sections[$i]['title'] = wptexturize($section['title']);
			if(is_section($section['ID']) or entry_in_section($section['ID']) or is_archive_of($section['ID'])) {
				$sections[$i]['this'] = 1;
			}
			else {
				$sections[$i]['this'] = 0;
			}
			$i++;
		}
		return run_filters('sections_array',$sections);
	}
	return run_filters('sections_array',array());
}

#Function: get_section_permalink(This section)
#Description: Section permalink. I've been getting lazy when it comes to
#			  descriptions, haven't I?
function get_section_permalink($section) {
	if(load_option('default_section') == $section['ID']) {
		return load_option('siteurl');
	}
	else {
		if(defined('BJ_REWRITE')) {
			return load_option('siteurl').'section/'.$section['shortname'];
		}
		return load_option('siteurl').'index.php?req=section&amp;name='.$section['shortname'];
	}
}

#Function: section_archive_link(Shortname)
#Description: Links to the archive page of the section.
function section_archive_link($shortname) {
	if(defined('BJ_REWRITE')) {
		return get_siteinfo('siteurl').'archive/'.$shortname;
	}
	return get_siteinfo('siteurl').'index.php?load=archive/'.$shortname;
}

#Function: section_stylesheet()
#Description: Returns the section's chosen stylesheet.
function section_stylesheet() {
	global $section;
	return get_siteinfo('siteurl').'content/skins/'.current_skinname().'/'.$section['stylesheet'];
}

#Function: section_shortname()
#Description: The shortname for a section. Only for sections (obviously).
function setion_shortname() {
	echo get_section_shortname();
}
function get_section_shortname() {
	global $section;
	return run_filters('section_shortname',$section['shortname']);
}

/*
 * ************************
 * Authors
 * Author functions- to be used within a special author loop.
 * ************************
 */

#Function: author_name()
#Description: Returns the author's name.
function author_name() {
	echo get_author_name();
}
function get_author_name() {
	global $author;
	return run_filters('author_name',$author['display_name']);
}

#Function: author_ID()
#Description: Author's internal ID.
function author_ID() {
	echo get_author_ID();
}
function get_author_ID() {
	global $author;
	return run_filters('author_ID',$author['ID']);
}

#Function: author_login()
#Description: Author's login name.
function author_login() {
	echo get_author_login();
}
function get_author_login() {
	global $author;
	return run_filters('author_login',$author['login']);
}

#Function: author_email()
#Description: Author's Email.
function author_email() {
	echo get_author_email();
}
function get_author_email() {
	global $author;
	return run_filters('author_email',$author['email']);
}

#Function: author_site()
#Description: Author's website.
function author_site() {
	echo get_author_site();
}
function get_author_site() {
	global $author;
	return run_filters('author_site',$author['website']);
}

#Function: author_registered(Format)
#Description: Author's registration date in a certain format.
function author_registered($format='') {
	echo get_author_registered($format);
}
function get_author_registered($format='') {
	global $author;
	return run_filters('author_registered',get_entry_date($format,$author['registered_on']));
}

#Function: about_author()
#Description: The author's profile.
function about_author() {
	echo get_about_author();
}
function get_about_author() {
	global $author;
	return run_filters('author_about',$author['about']);
}

/*
 * ************************
 * Misc
 * ************************
 */

#Function: bj_title()
#Description: Returns an entry, section, or tag name based on the location.
#			  Useful for page titles, hence the name.
function bj_title() {
	echo get_bj_title();
}
function get_bj_title() {
	global $section,$tag,$entries;
	if(is_section()) {
		$title = $section['title'];
	}
	elseif(is_tag()) {
		$title = $tag['name'];
	}
	elseif(is_entry()) {
		foreach($entries as $entry) {
			$title = $entry['title'];
		}
	}
	elseif(is_archive()) {
		$title = sprintf(_r('Archive for %1$s'),$section['title']);
	}
	elseif(is_404()) {
		$title = _r('404');
	}
	return run_filters('pagetitle',$title);
}

#Function: get_archive()
#Description: Returns a dynamic archive.
function get_archive() {
	global $__name_vars,$bj_db,$entries,$entry,$section; ?>
						<div id="archive">
						<div id="archive_yearly">
							<ul>
<?php
				$yearly = $bj_db->get_rows("SELECT DISTINCT YEAR(`posted`) FROM `".$bj_db->entries."` WHERE `section` = '".$section['ID']."' ORDER BY `posted` DESC");
				$yeer = '';
				foreach($yearly as $num=>$year) {
					$active = '';
					if($year['YEAR(`posted`)'] == $__name_vars[2] or ($num == 0 and $__name_vars[2] == '')) {
						$active = ' active';
						$yeer = $year['YEAR(`posted`)'];
					} ?>
								<li class="yearly_item<?php echo $active; ?>"><a href="<?php siteinfo('siteurl'); ?>archive/<?php echo $__name_vars[1]; ?>/<?php echo $year['YEAR(`posted`)']; ?>"><?php echo $year['YEAR(`posted`)']; ?></a></li>
<?php
				}
?>
							</ul>
						</div>
						<div id="archive_monthly">
							<ul>
<?php
				$monthly = $bj_db->get_rows("SELECT DISTINCT MONTH(`posted`) FROM `".$bj_db->entries."` WHERE YEAR(`posted`) ='".$yeer."' AND `section` = '".$section['ID']."' ORDER BY `posted` DESC");
				$mooth = '';
				foreach($monthly as $num=>$month) {
					$active = '';
					if($month['MONTH(`posted`)'] == $__name_vars[3] or ($num == 0 and $__name_vars[3] == '')) {
						$active = ' active';
						$mooth = $month['MONTH(`posted`)'];
					} ?>
								<li class="monthly_item<?php echo $active; ?>"><a href="<?php siteinfo('siteurl'); ?>archive/<?php echo $__name_vars[1]; ?>/<?php echo $yeer; ?>/<?php echo $month['MONTH(`posted`)']; ?>"><?php echo date('F',mktime(0,0,0,$month['MONTH(`posted`)'],1,$yeer)); ?></a></li>
<?php
				}
?>
							</ul>
						</div>
						<div id="archive_entries">
							<ul>
<?php
				$entries = get_entries('year='.$yeer.'&month='.$mooth.'&type=public&section='.$section['ID']);
				foreach($entries as $num=>$entry) { ?>
								<li class="entry_item"><a href="<?php echo_permalink(); ?>"><?php echo_title(); ?></a></li>
<?php
				} ?>
							</ul>
						</div>
					</div>
<?php
}

?>
