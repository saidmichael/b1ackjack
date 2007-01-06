<?php
# MySQL Configuration
define('DB_NAME','blackjack'); # Database name
define('DB_USER','root'); # Database user
define('DB_PASS',''); # Database user's password
define('DB_HOST','localhost'); # The host. Localhost should do just fine.

define('BJ_REWRITE',true); # This will make links turn into pretty URLs if uncommented.


define('BJ_LANG','en'); # Use this to localize Blackjack. Drop the main language
					  # file in the content/langs/ folder.
					  # You can add a "langs.php" file to your theme.

/* Table Prefix: Useful for multiple installations.
			     Just letters and underscores, if you can. */
$table_prefix = "bj_";


# Okay, that should be everything. Now stop editing and go install me. :) */

define('BJPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

require_once('bj_init.php');

?>