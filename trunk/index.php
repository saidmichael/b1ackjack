<?php

require "bj_config.php";

#Wow, empty.

run_actions('init_frontend');

#Run the content.
require('bj_content.php');

?>