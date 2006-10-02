<?php

#Function: echo_tags(Between[, Before[, After[, Extra]]])
#Description: Outputs the tags for a post. Can be used only in a loop. 
function echo_tags($between='',$before='',$after='',$extra=false) {
	$tags = return_tags();
	if($extra) {
		parse_str($extra,$args);
	}
	if(!empty($tags[0])) {
		foreach($tags as $tag) {
			if($args['nolink'] != "true") { $start_link = ($args['admin'] == "true") ? "<a href=\"tags.php?req=edit&amp;id=".$tag['ID']."\">" : "<a href=\"index.php?tag=".$tag['ID']."\">"; }
			$text .= $before.$start_link.$tag['name']."</a>".$after.$between;
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
		echo"<li class=\"list_tag\">".$before."<a href=\"index.php?tag=".$tag['ID']."\">".$tag['name']."</a>".$after."</li>";
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

#Function: get_posts(Stuff)
#Description: Grabs posts based on the "stuff" given.
function get_posts($q) {
	global $bj_db,$i,$newer,$older;
	parse_str($q,$stuff);
	$num_off = (isset($_GET['offset'])) ? intval($_GET['offset']) : 0; //So the offset will update itself automatically.
	$i = 0;
	$newer = $num_off-(isset($stuff['limit'])) ? $stuff['limit'] : 10;
	$older = $num_off+(isset($stuff['limit'])) ? $stuff['limit'] : 10;
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
	if(isset($stuff['tag'])) {
		$query .= " AND (";
		$tags = explode(",",$stuff['tag']);
		foreach($tags as $num=>$category) {
			if($num != 0) {
				$query .= " OR ";
			}
			if(substr($category,0,1) == "-") {
				$query .= "`tags` NOT REGEXP '".substr($category,1)."'";
			}
			else {
				$query .= "`tags` REGEXP '".$category."'";
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
		$limit = (isset($stuff['limit'])) ? $stuff['limit'] : 10;
		$query .= " LIMIT ".$offset.",".$limit;
		return $bj_db->get_rows($query,"ASSOC");
	}
	else {
		if($stuff['inclimit'] == "yes") {
			$offset = (isset($stuff['offset'])) ? $stuff['offset'] : $num_off;
			$limit = (isset($stuff['limit'])) ? $stuff['limit'] : 10;
			$query .= " LIMIT ".$offset.",".$limit;
		}
		return mysql_num_rows($bj_db->query($query));
	}
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

?>