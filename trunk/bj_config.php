<?php
# MySQL Configuration
define('DB_NAME','blackjack'); # Database name
define('DB_USER','root'); # Database user
define('DB_PASS',''); # Database user's password
define('DB_HOST','localhost'); # The host. Localhost should do just fine.

#define('BJ_REWRITE',true); # This will make links turn into pretty URLs if uncommented.


define('BJ_LANG','en'); # Use this to localize Blackjack. Drop the main language
			# file in the content/langs/ folder.
			# You can also add a "langs.php" file to your theme.

/* Table Prefix: Useful for multiple installations.
		 Just letters and underscores, if you can. */
$table_prefix = "bj_";

/* Domain: The domain name of the website, e.g. http://localhost */
$bj->domain = 'http://localhost';

/* Path: The directory it resides in, e.g. /blackjack/ */
$bj->path = '/blackjack/';

/* Cache: Make everything go faster. You could switch this
	  to 'false', but things may not go as quickly. */
define('CACHE_ON',true);

# If, by some wild chance you don't think Blackjack's secure, you can move
# the admin directory somewhere else and name that directory here.
define('ADMIN_DIR','admin');

# Okay, that should be everything. Now stop editing and go install me. :) */

define('BJPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);

?>
