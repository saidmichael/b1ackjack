<?php

require_once('bj_config.php');

if(!file_exists(dirname(__FILE__).'/bj_config.php'))
	die('It appears there is no bj_config.php file. Try opening up <strong>bj_config_sample.php</strong>, filling out the information, and renaming it as bj_config.php. Then revisit this page and let me do my work.');

error_reporting(E_ALL^E_NOTICE);

// Fix for IIS, which doesn't set REQUEST_URI
if ( empty( $_SERVER['REQUEST_URI'] ) ) {
	$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME']; // Does this work under CGI?
	
	// Append the query string if it exists and isn't null
	if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
}

if (!extension_loaded('mysql'))
	die('You need to install MySQL in order to run Blackjack.');

#Require all files first.
require_once(BJPATH . 'core/class_db.php');
require_once(BJPATH . 'core/class_cache.php');
require_once(BJPATH . 'core/class_entry.php');
require_once(BJPATH . 'core/version.php');
require_once(BJPATH . 'core/functions-compat.php');
require_once(BJPATH . 'core/functions-plugins.php');

load_plugins();
run_actions('plugins_loaded');

require_once(BJPATH . 'core/filters.php');
require_once(BJPATH . 'core/kses.php');
require_once(BJPATH . 'core/functions-general.php');
require_once(BJPATH . 'core/functions-formatting.php');
require_once(BJPATH . 'core/functions-user.php');
require_once(BJPATH . 'core/functions-skins.php');
require_once(BJPATH . 'core/class_locale.php');
require_once(BJPATH . 'core/functions-template.php');
require_once(BJPATH . 'core/functions-editing.php');
require_once(BJPATH . 'core/functions-condition.php');
require_once(BJPATH . 'core/rss/rss_fetch.inc');

if(!blackjack_is_installed())
	die('It appears you haven\'t installed me yet.');

define('BJHASH',run_actions('bjhash',md5($bj->db->prefix)));

define('BJTEMPLATE',BJPATH.'content/skins/'.current_skinname());

if(!$bj->vars->passcookie)
	$bj->vars->passcookie = $bj->db->prefix."pass_".BJHASH;
if(!$bj->vars->usercookie)
	$bj->vars->usercookie = $bj->db->prefix."user_".BJHASH;

#Grab our user.
if(isset($_COOKIE[$bj->vars->passcookie]) and isset($_COOKIE[$bj->vars->usercookie])) {
	$bj->user = $bj->cache->get_user(intval($_COOKIE[$bj->vars->usercookie]));
	if($bj->user and md5($bj->user['user_pass']) != bj_clean_string($_COOKIE[$bj->vars->passcookie]))
		unset($bj->user);
}

#Plugins and template files.
if(file_exists(BJTEMPLATE . '/functions.php'))
	require_once(BJTEMPLATE . '/functions.php');

run_actions('init');

?>
