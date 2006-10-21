<?php
$parent_file = "comments.php";
require("admin-head.php");
if(we_can('edit_comments')) {
	switch($_GET['req']) {
		case "edit" :
			
			break;
		default :
			get_admin_header();
			get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}

?>