<?php

# Our settings are all in here.
$skin_settings = load_option('skin_settings');

function TO_body_class() {
	global $bj,$entries,$section,$tag; ?>
blackjack twentyone m<?php echo date('m',time()); ?> d<?php echo date('d',time()); ?> y<?php echo date('Y',time()); ?> h<?php echo date('h',time()); ?><?php
	if(is_front()) { ?> frontpage<?php }
	if(is_entry()) { ?> single-entry entry-<?php echo $bj->vars->load[1];
	foreach($entries as $entry)
		echo ' entry-author-'.bj_shortname($entry['author']);
	}
	if(is_section()) { ?> section section-<?php echo $section['shortname']; }
	if(section_is_handled_by('section-single.php')) { ?> just-one-entry<?php }
	if(is_tag()) { ?> tag tag-<?php echo $tag['shortname']; }
	if($bj->user) { ?> loggedin<?php }
	run_actions('TO_body_class');
}

function TO_post_class() {
	global $i,$skin_settings; ?>
entry e<?php echo $i; ?> fresh-<?php entry_freshness(); ?> author-<?php echo bj_shortname(get_entry_author()); ?> m<?php entry_date('m'); ?> d<?php entry_date('d'); ?> y<?php entry_date('Y'); ?> h<?php entry_date('h');
	if(in_tag($skin_settings['sidenotes_tag'])) { ?> bj_sidenote<?php }
	foreach(get_entry_tags() as $tag)
		echo" tag-".$tag['shortname'];
	foreach(get_entrymeta() as $key=>$value)
		echo' meta-'.$key.'-'.bj_shortname($value);
	echo ($i%2 == 0) ? " alt" : "";
	run_actions('TO_post_class');
}

function TO_comment_class($i=0) {
	global $comment; ?>
comment c<?php echo $i; ?> author-<?php echo bj_shortname(return_comment_name()); ?> m<?php comment_date('m'); ?> d<?php comment_date('d'); ?> y<?php comment_date('Y'); ?> h<?php comment_date('h'); echo ($i%2 == 0) ? ' alt' : ''; if($comment['user_id'] != 0) { ?> byuser<?php } if(get_entry_author() == return_comment_name() and $comment['user_id'] != 0) { ?> byauthor<?php }
}

function TO_freshness_title() {
	if(get_entry_freshness() == _r('fresh'))
		_e('Posted in the last three days.');
	elseif(get_entry_freshness() == _r('good'))
		_e('Posted in the past week.');
	else
		_e('Older than a week.');
}

#This adds a tag filter for sidenotes.
function remove_sidenotes() {
	global $skin_settings,$bj;
	if($skin_settings['sidenotes_position'] == 'inline' or $skin_settings['sidenotes_tag'] == '' or $skin_settings['sidenotes_num'] == '')
		return;
	if(isset($bj->query->args['tags'])) {
		$tags = '-'.$bj->query->args['tags'];
		if(strpos($tags,'-'.$skin_settings['sidenotes_tag']))
			$tags = str_replace('-'.$skin_settings['sidenotes_tag'],'',$tags);
		$bj->query->args['tags'] = $tags;
	}
	if(isset($args['tag']))
		$args['tag'] .= ',-'.$skin_settings['sidenotes_tag'];
	else
		$args['tag'] = '-'.$skin_settings['sidenotes_tag'];
	$final = '';
	foreach($args as $key=>$arg)
		$final .= '&'.$key.'='.$arg;
	return substr($final,1);
}
add_action('prefetch','remove_sidenotes');

?>
