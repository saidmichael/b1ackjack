<html>
	<head>
		<title><?php siteinfo('sitename'); ?></title>
		<link rel="stylesheet" href="<?php siteinfo('stylesheet'); ?>" type="text/css" />
		<?php run_actions('site_header'); ?>
	</head>
	<body class="<?php TO_body_class(); ?>">
		<div id="page">
			<div id="header">
				<h1><a href="<?php siteinfo('siteurl'); ?>"><?php siteinfo('sitename'); ?></a></h1>
				<ul class="menu">
<?php
					echo_sections(); ?>
					<li class="admintab"><?php bj_signup_link(); ?></li>
				</ul>
			</div>
			<hr />