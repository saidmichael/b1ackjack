<?php


//wptexturize()
add_filter('entry_title','wptexturize');
add_filter('entry_content','wptexturize');
add_filter('entry_snippet','wptexturize');
add_filter('comment_text','wptexturize');
add_filter('comment_snippet','wptexturize');
add_filter('author_about','wptexturize');
add_filter('pagetitle','wptexturize');

//wpautop()
add_filter('entry_content','wpautop');
add_filter('entry_snippet','wpautop');
add_filter('comment_text','wpautop');
add_filter('author_about','wpautop');
?>
