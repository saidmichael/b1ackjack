<?php

function TO_body_class() { ?>
 class="blackjack<?php
	if(is_front()) { ?> front_page<?php }
	if(is_entry()) { ?> entry entry-<?php echo bj_clean_string($_GET['name'],array()); } ?>"<?php
}

?>