<?php

# Our settings are all in here.
$skin_settings = load_option('skin_settings');

function TO_body_class() {
	global $user,$entries,$section,$tag; ?>
blackjack twentyone m<?php echo date('m',time()); ?> d<?php echo date('d',time()); ?> y<?php echo date('Y',time()); ?> h<?php echo date('h',time()); ?><?php
	if(is_front()) { ?> frontpage<?php }
	if(is_entry()) { ?> single-entry entry-<?php echo bj_shortname(bj_clean_string($_GET['name']));
		foreach($entries as $entry) { start_entry();
			echo ' entry-author-'.bj_shortname($entry['author']);
		}
	}
	if(is_section()) { ?> section section-<?php echo $section['shortname']; }
	if(section_is_handled_by('section-single.php')) { ?> single-post<?php }
	if(is_tag()) { ?> tag tag-<?php echo $tag['shortname']; }
	if($user) { ?> loggedin<?php }
	run_actions('TO_body_class');
}

function TO_post_class() {
	global $i,$skin_settings; ?>
entry p<?php echo $i; ?> fresh_<?php entry_freshness(); ?> author-<?php echo bj_shortname(get_entry_author()); ?> m<?php entry_date('m'); ?> d<?php entry_date('d'); ?> y<?php entry_date('Y'); ?> h<?php entry_date('h');
	if(in_tag($skin_settings['sidenotes_tag'])) { ?> bj_sidenote<?php }
	foreach(return_tags() as $tag) {
		echo" tag-".$tag['shortname'];
	}
	echo ($i%2 == 0) ? " alt" : "";
	run_actions('TO_post_class');
}

function TO_freshness_title() {
	if(get_entry_freshness() == _r('fresh')) {
		_e('Posted in the last three days.');
	}
	elseif(get_entry_freshness() == _r('good')) {
		_e('Posted in the past week.');
	}
	else {
		_e('Older than a week.');
	}
}

#This adds a tag filter for sidenotes directly to the query string.
function remove_sidenotes($query_string) {
	global $skin_settings;
	if($skin_settings['sidenotes_position'] == 'inline' or $skin_settings['sidenotes_tag'] == '' or $skin_settings['sidenotes_num'] == '') {
		return $query_string;
	}
	parse_str($query_string,$args);
	if(isset($args['tag'])) {
		$args['tag'] .= ',-'.$skin_settings['sidenotes_tag'];
	}
	else {
		$args['tag'] = '-'.$skin_settings['sidenotes_tag'];
	}
	$final = '';
	foreach($args as $key=>$arg) {
		$final .= '&'.$key.'='.$arg;
	}
	return substr($final,1);
}
add_filter('qstring_section','remove_sidenotes');

?>