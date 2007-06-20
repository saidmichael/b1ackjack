<?php

require "bj_init.php";

if($_POST['sendcomment'] and $_POST['post_id'] and $_POST['author_name'] and $_POST['author_email'] and $_POST['author_url'] and $_POST['content'] and get_siteinfo('enable_commenting') == 1) {
	$comment = bj_new_comment();
	if($_SERVER['HTTP_REFERER'] and substr($_SERVER['HTTP_REFERER'],0,strlen(load_option('siteurl'))) == load_option('siteurl'))
		@header('Location: '.$_SERVER['HTTP_REFERER'].'#comment-'.$comment['ID']);
}
elseif(get_siteinfo('enable_commenting') == 0)
	_e('Commenting has been disabled sitewide.');
elseif($_POST == array())
	_e('It appears no Postdata can be found. Oh well.');
else
	_e('It appears you\'re missing a required field for adding a new comment.');

?>
