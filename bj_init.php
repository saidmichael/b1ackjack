<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors',1);

// Fix for IIS, which doesn't set REQUEST_URI
if ( empty( $_SERVER['REQUEST_URI'] ) ) {
	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME']; // Does this work under CGI?
	
	// Append the query string if it exists and isn't null
	if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if ( !extension_loaded('mysql') )
	die( 'You need to install MySQL in order to run Blackjack.' );
	
#Require all files first.
require(BJPATH . 'core/class_db.php');
require(BJPATH . 'core/version.php');
require(BJPATH . 'core/functions-plugins.php');
require(BJPATH . 'core/filters.php');
require(BJPATH . 'core/kses.php');
require(BJPATH . 'core/functions-general.php');
require(BJPATH . 'core/functions-formatting.php');
require(BJPATH . 'core/functions-user.php');
require(BJPATH . 'core/functions-skins.php');
require(BJPATH . 'core/functions-lang.php');
require(BJPATH . 'core/functions-template.php');
require(BJPATH . 'core/functions-editing.php');
require(BJPATH . 'core/functions-condition.php');
require(BJPATH . 'core/rss/rss_fetch.inc');

define('BJTEMPLATE',BJPATH.'content/skins/'.current_skinname());

#Grab our user.
if(isset($_COOKIE[$bj_db->prefix.'pass']) and isset($_COOKIE[$bj_db->prefix.'id'])) {
	$user = $bj_db->get_item("SELECT * FROM `".$bj_db->users."` WHERE `ID` = ".intval($_COOKIE[$bj_db->prefix.'id'])." AND `password` = '".bj_clean_string($_COOKIE[$bj_db->prefix.'pass'])."' LIMIT 1");
}

#Plugins and template files.
load_plugins();
if(file_exists(BJTEMPLATE . '/functions.php')) {
	require_once(BJTEMPLATE . '/functions.php');
}

if(load_option('db_version') != $bj_version) {
	echo('The database\'s Blackjack version is not equal to that of your software copy. It is likely that you may need to upgrade so you can get your site running once more.');
	die();
}

run_actions('init');

?>
