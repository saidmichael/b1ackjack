<?php

require "bj_config.php";

switch($_GET['req']) {
	case 'section' :
		skin_load('section');
		break;
	case 'entry' :
		skin_load('entry');
		break;
	case 'tag' :
		skin_load('tag');
		break;
	default :
		skin_load('index');
}

run_actions('init_frontend');

?>