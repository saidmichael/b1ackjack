<?php

# Our settings are all in here.
$TO_settings = unserialize(load_option('TO_settings'));

function TO_body_class() {
	global $user,$posts; ?>
blackjack twentyone m<?php echo date('m',time()); ?> d<?php echo date('d',time()); ?> y<?php echo date('Y',time()); ?> h<?php echo date('h',time()); ?><?php
	if(is_front()) { ?> frontpage<?php }
	if(is_entry()) { ?> entry entry-<?php echo bj_shortname(bj_clean_string($_GET['name']));
	foreach($posts as $post) {
		echo ' b-author-'.bj_shortname(get_entry_author());
	}
	}
	if(is_section()) { ?> section section-<?php echo bj_shortname(bj_clean_string($_GET['name'])); }
	if($user) { ?> loggedin<?php }
	run_actions('TO_body_class');
}

function TO_post_class() {
	global $entry,$i; ?>
entry p<?php echo $i; ?> author-<?php echo bj_shortname(get_entry_author()); ?> m<?php entry_date('m'); ?> d<?php entry_date('d'); ?> y<?php entry_date('Y'); ?> h<?php entry_date('h');
	foreach(return_tags() as $tag) {
		echo" tag-".$tag['shortname'];
	}
	echo ($i%2 == 0) ? " alt" : "";
	run_actions('TO_post_class');
}

?>