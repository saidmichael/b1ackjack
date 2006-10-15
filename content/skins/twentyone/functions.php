<?php

# Our settings are all in here.
$TO_settings = unserialize(load_option('TO_settings'));

function TO_body_class() {
	global $time,$user; ?>
 class="blackjack m<?php echo date('m',$time); ?> d<?php echo date('d',$time); ?> y<?php echo date('Y',$time); ?> h<?php echo date('h',$time); ?><?php
	if(is_front()) { ?> front_page<?php }
	if(is_entry()) { ?> entry entry_<?php echo bj_shortname(bj_clean_string($_GET['name'],array())); }
	if(is_section()) { ?> section section_<?php echo bj_shortname(bj_clean_string($_GET['name'],array())); }
	if($user) { ?> loggedin<?php } ?>"<?php
}

function TO_post_class() {
	global $post,$i;
	echo"post p".$i." author-".bj_shortname(get_post_author());
	foreach(return_tags() as $tag) {
		echo" tag-".$tag['shortname'];
	}
}

?>