<?php

/*
 * ************************
 * Global Functions
 * ************************
 */

#Function: echo_all_tags(Extra)
#Description: Lists all tags in existance.
function echo_all_tags($before='',$after='') {
	$tags = return_all_tags();
	if($tags) {
		foreach($tags as $tag) {
			if(defined('BJ_REWRITE')) {
				echo"<li class=\"list_tag\">".$before."<a href=\"".get_siteinfo('siteurl')."tag/".$tag['shortname']."\">".$tag['name']."</a>".$after."</li>";
			}
			else {
				echo"<li class=\"list_tag\">".$before."<a href=\"".get_siteinfo('siteurl')."index.php?req=tag&amp;name=".$tag['shortname']."\">".$tag['name']."</a>".$after."</li>";
			}
		}
	}
}

#Function: return_all_tags()
#Description: Get my tags!
function return_all_tags() {
	global $bj;
	return $bj->cache->get_tags();
}

/*
 * ************************
 * Entry Functions
 * ************************
 */

#Outputs the tags for an entry.
function entry_tags($between=', ',$before='',$after='',$extra=false) {
	$tags = get_entry_tags();
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		foreach($tags as $tag) {
			if($args['admin'] == "true")
				$start_link = "<a href=\"tags.php?req=edit&amp;id=".$tag['ID']."\">";
			else {
				if(defined('BJ_REWRITE'))
					$start_link = "<a href=\"".get_siteinfo('siteurl')."tag/".$tag['shortname']."\">";
				else
					$start_link = "<a href=\"".get_siteinfo('siteurl')."index.php?load=tag/".$tag['shortname']."\">";
			}
			$text .= $before.$start_link.$tag['name']."</a>".$after.$between;
		}
		echo preg_replace('{'.$between.'$}', '', $text);
	}
}

#Outputs the tags for a post, RSS-style.
function rss_tags($between='',$before='',$after='') {
	$tags = get_entry_tags();
	if(!empty($tags[0])) {
		$text = '';
		foreach($tags as $tag) {
			$text .= $before.'<dc:subject>'.$tag['name'].'</dc:subject>'.$after.$between;
		}
		echo preg_replace('{'.$between.'$}', '', $text);
	}
}

function get_entry_tags() {
	global $entry;
	static $done = 0;
	static $alltags = array();
	if($done == 0) {
		$thesetags = return_all_tags();
		if($thesetags)
			foreach($thesetags as $tag)
				$alltags[$tag['ID']] = $tag;
		$done = 1;
	}
	
	$tags = unserialize($entry['tags']);
	$retarr = array();
	
	if(!empty($tags[0])) {
		foreach($tags as $ID) {
			#This checks if the information about a tag was already retrieved.
			#After all, why do extra queries?
			if(isset($alltags[$ID]))
				$retarr[] = $alltags[$ID];
		}
		return $retarr;
	}
	else
		return array();
}

#Prepares variables and such for each entry.
function thru_loop() {
	global $i;
	$i++;
	run_actions('thru_loop');
}

function entry_ID() {
	echo get_entry_ID();
}
function get_entry_ID() {
	global $entry;
	return run_actions('entry_id',$entry['ID']);
}

function entry_title() {
	echo get_entry_title();
}
function get_entry_title() {
	global $entry;
	return run_actions('entry_title',$entry['title']);
}

function entry_permalink() {
	echo get_entry_permalink();
}
function get_entry_permalink() {
	global $entry;
	if(defined('BJ_REWRITE'))
		return get_siteinfo('siteurl').'entry/'.$entry['shortname'];
	else
		return get_siteinfo('siteurl').'index.php?load=entry/'.$entry['shortname'];		
}
function entry_author() {
	echo get_entry_author();
}
function get_entry_author() {
	global $entry,$bj;
	$user = $bj->cache->get_user($entry['author']);
	return run_actions('entry_author',$user['display_name']);
}

function entry_author_page() {
	echo get_entry_author_page();
}
function get_entry_author_page() {
	global $entry,$bj;
	$user = $bj->cache->get_user($entry['author']);
	$load = (defined('BJ_REWRITE')) ? '' : '?load=';
	return run_actions('entry_author_page','<a href="'.get_siteinfo('siteurl').$load.'author/'.$user['ID'].'">'.$user['display_name'].'</a>');
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
	if(we_can('edit_entries')) { ?><a href="<?php siteinfo('adminurl'); ?>entries.php?req=edit&amp;id=<?php entry_ID(); ?>"><?php echo $text; ?></a><?php }
}

#Function: entry_content()
#Description: Echos the content.
function entry_content() {
	echo get_entry_content();
}
function get_entry_content() {
	global $entry;
	$content = str_replace(run_actions('snippet_separator','__More__'),'<div class="more-separator" id="more-'.get_entry_ID().'"></div>',$entry['content']);
	return run_actions('entry_content',$content);
}

#Function: entry_snippet()
#Description: Returns the snippet of the entry.
function entry_snippet() {
	echo get_entry_snippet();
}
function get_entry_snippet() {
	global $entry;
	$content = explode(run_actions('snippet_separator','__More__'),$entry['content']);
	$content = $content[0];
	return run_actions('entry_snippet',$content);
}

#Function: more_link(Text)
#Description: Outputs a link to the full content. Super!
function more_link($text='Read More',$before='',$after='') {
	global $entry;
	if(strpos($entry['content'],run_actions('snippet_separator','__More__'))) { echo $before; ?><a href="<?php entry_permalink(); ?>#more-<?php entry_ID(); ?>"><?php echo $text; ?></a><?php echo $after;
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
<a href="<?php entry_permalink(); ?>#comments"><?php echo $comments_text; ?></a><?php
}

#Function: get_comments_number()
#Description: Returns the number of comments.
function get_comments_number() {
	global $entry;
	return $entry['comment_count'];
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
	$tags = get_entry_tags();
	if(in_array($id,$tags)) {
		return true;
	}
	return false;
}

#Function: next_post_link(Text[, Before[, After]])
#Description: Next post link.
function next_page_link($text,$before='',$after='') {
	global $bj;
	$bj->query->next_page($text,$before,$after);
}

#Function: prev_post_link(Text[, Before[, After]])
#Description: Previous post link.
function prev_page_link($text,$before='',$after='') {
	global $bj;
	$bj->query->prev_page($text,$before,$after);
}

function get_entrymeta($key='') {
	global $entry;
	$meta = unserialize($entry['meta']);
	$key = bj_shortname($key);
	if($key == '')
		return $meta;
	else
		return $meta[$key];		
}

/*
 * ************************
 * Comments
 * ************************
 */
 
 #Function: get_comments(Stuff[, Extra])
#Description: Modeled after get_posts().
function get_comments($q,$extra=false) {
	global $bj;
	parse_str($q,$stuff);	
	$query = "SELECT * FROM ".$bj->db->comments." WHERE";
	if(isset($stuff['id'])) {
		$query .= " `ID` = '".intval($stuff['id'])."'";
	} else {
		$query .= " `ID` != '0'"; #Just a filler so each value can start with "AND".
	}
	if(isset($stuff['postid'])) {
		$query .= " AND `post_ID` = '".intval($stuff['postid'])."'";
	}
	if(isset($stuff['posted_on'])) {
		$query .= " AND `posted_on` = '".$stuff['posted_on']."'";
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
	if(isset($stuff['content'])) {
		$query .= " AND `content` = '".$stuff['content']."'";
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
		return $bj->db->get_rows($query);
	}
	else {
		return mysql_num_rows($bj->db->query($query));
	}
}

#Function: get_comment()
#Description: Shorthand for returning the first item in get_comments().
function get_comment($args,$extra=false) {
	$comments = get_comments($args,$extra);
	return $comments[0];
}

#Function: comment_ID()
#Description: Returns the ID of the comment, filters applied.
function comment_ID() {
	echo return_comment_ID();
}
function return_comment_ID() {
	global $comment;
	return run_actions('comment_ID',$comment['ID']);
}

#Function: comment_name()
#Description: The author of the comment.
function comment_name() {
	echo return_comment_name();
}
function return_comment_name() {
	global $comment;
	return run_actions('commenter_name',$comment['author_name']);
}

#Function: comment_url()
#Description: The commenter's URL, if it exists.
function comment_url() {
	echo return_comment_url();
}
function return_comment_url() {
	global $comment;
	return run_actions('commenter_url',$comment['author_url']);
}

#Function: comment_email()
#Description: The commenter's email.
function comment_email() {
	echo return_comment_email();
}
function return_comment_email() {
	global $comment;
	return run_actions('commenter_email',$comment['author_email']);
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
	return run_actions('comment_author_url',$text);
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
	return run_actions('comment_text',$comment['content']);
}

#Function: edit_comment_link(Text)
#Description: Link to editing comments.
function edit_comment_link($text='Edit') {
	if(we_can('edit_comments')) { ?><a href="<?php siteinfo('adminurl'); ?>comments.php?req=edit&amp;id=<?php comment_ID(); ?>"><?php echo $text; ?></a><?php }
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
	return run_actions('comment_snippet',$text);
}

#Function comment_postid()
#Description: The post that the comment was posted in.
function comment_postid() {
	global $comment;
	return $comment['post_ID'];
}

#Function: comments_allowed_html()
#Description: Grabs the comment's allowed HTML and outputs it in an HTML-like form.
function comments_allowed_html() {
	echo get_comments_allowed_html();
}
function get_comments_allowed_html() {
	$output = '';
	foreach(get_html_comment() as $tag=>$attrs) {
		$output .= '&lt;'.$tag;
		foreach ($attrs as $attr=>$null) {
			$output .= ' '.$attr.'=""';
		}
		$output .= '&gt; ';
	}
	return $output;
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
	ob_start();
	foreach($sections as $section) {
		if($section['hidden'] == 'no') { ?>
<li class="section-li section-<?php echo bj_shortname($section['title']); echo ($section['this'] == 1) ? ' current-section' : ''; ?>"><a href="<?php echo get_section_permalink($section); ?>"><?php echo $section['title']; ?></a></li>
<?php
		}
	}
	$contents = ob_get_contents();
	ob_end_clean();
	echo run_actions('sections_echod',$contents);
}

#Function: return_sections()
#Description: Returns all sections in use. Also checks the current section.
function return_sections() {
	global $bj;
	$sections = $bj->cache->get_sections();
	$i = 0;
	if($sections) {
		foreach($sections as $section) {
			$sections[$i]['title'] = wptexturize($section['title']);
			if(is_section($section['ID']) or entry_in_section($section['ID']) or is_archive($section['ID']))
				$sections[$i]['this'] = 1;
			else
				$sections[$i]['this'] = 0;
			$i++;
		}
		return run_actions('sections_array',$sections);
	}
	return run_actions('sections_array',array());
}

#Function: get_section_permalink(This section)
#Description: Section permalink. I've been getting lazy when it comes to
#			  descriptions, haven't I?
function get_section_permalink($section) {
	if(load_option('default_section') == $section['ID'])
		return get_siteinfo('siteurl');
	else {
		$load = (defined('BJ_REWRITE')) ? '' : '?load=';
		return get_siteinfo('siteurl').$load.'section/'.$section['shortname'];
	}
}

#Function: section_archive_link(Shortname)
#Description: Links to the archive page of the section.
function section_archive_link($shortname) {
	if(defined('BJ_REWRITE'))
		return get_siteinfo('siteurl').'archive/'.$shortname;
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
	return run_actions('section_shortname',$section['shortname']);
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
	return run_actions('author_name',$author['display_name']);
}

#Function: author_ID()
#Description: Author's internal ID.
function author_ID() {
	echo get_author_ID();
}
function get_author_ID() {
	global $author;
	return run_actions('author_ID',$author['ID']);
}

#Function: author_login()
#Description: Author's login name.
function author_login() {
	echo get_author_login();
}
function get_author_login() {
	global $author;
	return run_actions('author_login',$author['login']);
}

#Function: author_email()
#Description: Author's Email.
function author_email() {
	echo get_author_email();
}
function get_author_email() {
	global $author;
	return run_actions('author_email',$author['email']);
}

#Function: author_site()
#Description: Author's website.
function author_site() {
	echo get_author_site();
}
function get_author_site() {
	global $author;
	return run_actions('author_site',$author['website']);
}

#Function: author_registered(Format)
#Description: Author's registration date in a certain format.
function author_registered($format='') {
	echo get_author_registered($format);
}
function get_author_registered($format='') {
	global $author;
	return run_actions('author_registered',get_entry_date($format,$author['registered_on']));
}

#Function: author_desc()
#Description: The author's profile.
function author_desc() {
	echo get_author_desc();
}
function get_author_desc() {
	global $author;
	return run_actions('author_description',get_usermeta($author['ID'],'description'));
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
	global $section,$tag,$entries,$author;
	if(is_section())
		$title = $section['title'];
	elseif(is_tag())
		$title = $tag['name'];
	elseif(is_entry())
		foreach($entries as $entry)
			$title = $entry['title'];
	elseif(is_archive())
		$title = sprintf(_r('Archive for %1$s'),$section['title']);
	elseif(is_author())
		$title = $author['display_name'];
	elseif(is_404())
		$title = _r('404');
	return run_actions('pagetitle',$title);
}

#Function: get_archive()
#Description: Returns a dynamic archive.
function get_archive() {
	global $bj,$entries,$entry,$section;
	$load = (defined('BJ_REWRITE')) ? '' : '?load='; ?>
						<div id="archive">
							<div id="archive_yearly">
								<ul>
<?php
				$yearly = $bj->db->get_rows("SELECT DISTINCT YEAR(`posted`) FROM `".$bj->db->entries."` WHERE `section` = '".$section['ID']."' ORDER BY `posted` DESC");
				$yeer = '';
				foreach($yearly as $num=>$year) {
					$active = '';
					if($year['YEAR(`posted`)'] == $bj->vars->load[2] or ($num == 0 and $bj->vars->load[2] == '')) {
						$active = ' active';
						$yeer = $year['YEAR(`posted`)'];
					} ?>
									<li class="yearly_item<?php echo $active; ?>"><a href="<?php echo get_siteinfo('siteurl').$load; ?>archive/<?php echo $bj->vars->load[1]; ?>/<?php echo $year['YEAR(`posted`)']; ?>"><?php echo $year['YEAR(`posted`)']; ?></a></li>
<?php
				}
?>
								</ul>
							</div>
							<div id="archive_monthly">
								<ul>
<?php
				$monthly = $bj->db->get_rows("SELECT DISTINCT MONTH(`posted`) FROM `".$bj->db->entries."` WHERE YEAR(`posted`) ='".$yeer."' AND `section` = '".$section['ID']."' ORDER BY `posted` DESC");
				$mooth = '';
				foreach($monthly as $num=>$month) {
					$active = '';
					if($month['MONTH(`posted`)'] == $bj->vars->load[3] or ($num == 0 and $bj->vars->load[3] == '')) {
						$active = ' active';
						$mooth = $month['MONTH(`posted`)'];
					} ?>
									<li class="monthly_item<?php echo $active; ?>"><a href="<?php echo get_siteinfo('siteurl').$load; ?>archive/<?php echo $bj->vars->load[1]; ?>/<?php echo $yeer; ?>/<?php echo $month['MONTH(`posted`)']; ?>"><?php echo date('M',mktime(0,0,0,$month['MONTH(`posted`)'],1,$yeer)); ?></a></li>
<?php
				}
?>
								</ul>
							</div>
							<div id="archive_entries">
								<ul>
<?php
				$bj->query->setLimit(false,false);
				$bj->query->setYear($yeer);
				$bj->query->setMonth($mooth);
				$bj->query->setSection($section['ID']);
				$bj->query->setPtype('public');
				$entries = $bj->query->fetch();
				if($entries) {
					foreach($entries as $num=>$entry) { ?>
									<li class="entry_item item_<?php echo $num+1; ?>"><a href="<?php entry_permalink(); ?>"><?php entry_title(); ?></a></li>
<?php
					}
				}
				else { ?>
									<li class="entry_item none"><?php _e('No entries found.'); ?></li>
<?php
				} ?>
								</ul>
							</div>
						</div>
<?php
}

function bj_feed_head() { ?>
<link rel="alternate" type="application/rss+xml" title="<?php printf(_r('Feed for %1$s'),get_bj_title()); ?>" href="<?php siteinfo('feedurl'); ?>" />
<?php
}

function bj_signup_link() {
	global $bj;
	if($bj->user) { ?><a href="<?php siteinfo('adminurl'); ?>index.php"><?php _e('Admin'); ?></a><?php }
	else { ?><a href="<?php siteinfo('adminurl'); ?>register.php"><?php _e('Register'); ?></a><?php }
}

?>
