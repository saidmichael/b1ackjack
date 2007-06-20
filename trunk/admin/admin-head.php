<?php

require_once("../bj_init.php");
$admin_thisfile = (basename_withpath($_SERVER['REQUEST_URI']) == "admin") ? "index.php" : basename_withpath($_SERVER['REQUEST_URI']);
require("admin-functions.php");
require("menu.php");
validate_session();

$bj->vars->admin_notices = array();

run_bj_forms();

if(isset($_GET['plug'])) {
	if(isset($submenu['plugs'][$_GET['plug']]) && $submenu['plugs'][$_GET['plug']]['file'] == $admin_thisfile) {
		$parent_file = $submenu['plugs'][$_GET['plug']]['file'];
		call_user_func_array($submenu['plugs'][$_GET['plug']]['hook'], array());
		die();
	}
	else
		die(sprintf(_r('The plugin page \'%1$s\' does not exist.'),$_GET['plug']));
}

?>
