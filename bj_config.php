<?php
# MySQL Configuration
define('DB_NAME','blackjack'); # Database name
define('DB_USER','root'); # Database user
define('DB_PASS',''); # Database user's password
define('DB_HOST','localhost'); # The host. Localhost should do just fine.


define('BJ_LANG',''); # Use this to localize Blackjack. Drop the language
					  # file in the content/langs/ folder.

/* Table Prefix: Useful for multiple installations.
			     Just letters and underscores, if you can. */
$table_prefix = "bj_";


# Okay, that should be everything. Now stop editing and go install me. :) */

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors',1);

define('BJPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

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
	
//We're good with MySQL, proceed.
require_once(BJPATH . 'core/classes/class_db.php');

$bj_db->prefix = $table_prefix;
$bj_db->tags = $bj_db->prefix.'tags';
$bj_db->comments = $bj_db->prefix.'comments';
$bj_db->options = $bj_db->prefix.'options';
$bj_db->posts = $bj_db->prefix.'posts';
$bj_db->sections = $bj_db->prefix.'sections';
$bj_db->users = $bj_db->prefix.'users';

//Load core files.
//Development: Add them as they come.
require(BJPATH . 'core/kses.php');
require(BJPATH . 'core/functions-general.php');
require(BJPATH . 'core/functions-formatting.php');
require(BJPATH . 'core/functions-user.php');
require(BJPATH . 'core/functions-lang.php');
require(BJPATH . 'core/functions-plugins.php');
require(BJPATH . 'core/functions-template.php');
require(BJPATH . 'core/functions-editing.php');
require(BJPATH . 'core/functions-skins.php');
require(BJPATH . 'core/version.php');
require(BJPATH . 'core/rss/rss_fetch.inc');

$time = time(); //Just so a second doesn't pass and it sets a different time.

load_plugins(); //Load plugins (of course).

$user = get_user_info();

run_actions('init');

?>