<?php

if(!file_exists('../bj_config.php'))
	die('It appears there is no bj_config.php file. Try opening up <strong>bj_config_sample.php</strong>, filling out the information, and renaming it as bj_config.php. Then revisit this page and let me do my work.');

require "../bj_init.php";
?>
<html>
	<head>
		<title>Blackjack Upgrade</title>
	</head>
	<body>
		<h1>Blackjack: Upgra...tion?</h1>
		<hr />
<?php
if($_GET['do']) {
	if(get_siteinfo('db_version') != $bj->vars->db_version) {
		$i = get_siteinfo('db_version')+1;
		while($i < $bj->vars->db_version) {
			call_user_func('bj_upgrade_'.$i);
			echo '<p>Upgraded to '.$i.'...</p>';
			$i++;
		}
		echo '<p>Done! That should settle upgrading for now. Just rename me or hide me from public view for now, until you\'ll need me again.</p>';
	}
	else
		echo'<p>Um, what? You\'re already at the latest version, silly goose!</p>';
}
else { ?>
		<p>This file will upgrade you from your current database setup to your files' version. Be warned; it may take a while, so you have to go to the link below to start the upgrade process.</p>
		<p><a href="upgrade.php?do=true">CLICK HERE</a>.</p>
<?php
} ?>
	</body>
</html>
