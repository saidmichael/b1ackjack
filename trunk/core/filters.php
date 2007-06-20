<?php


//wptexturize()
add_action('entry_title','wptexturize');
add_action('entry_content','wptexturize');
add_action('entry_snippet','wptexturize');
add_action('comment_text','wptexturize');
add_action('comment_snippet','wptexturize');
add_action('author_description','wptexturize');
add_action('pagetitle','wptexturize');

//wpautop()
add_action('entry_content','wpautop');
add_action('entry_snippet','wpautop');
add_action('comment_text','wpautop');
add_action('author_about','wpautop');

add_action('admin_content_start','do_bj_notices');
?>
