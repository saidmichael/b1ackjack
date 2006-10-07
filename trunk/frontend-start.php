<?php

require "bj_config.php";

$getname = bj_clean_string($_GET['name'],array(),'mysql=true');
$offset = (isset($_GET['offset'])) ? intval($_GET['offset']) : 0;

run_actions('init_frontend');

#Run the content.
require('bj_content.php');

?>