<?php

require_once("../bj_config.php");

$admin_thisfile = (basename_withpath($_SERVER['REQUEST_URI']) == "admin") ? "index.php" : basename_withpath($_SERVER['REQUEST_URI']);

require("admin-functions.php");

validate_session();

run_bj_forms();
?>