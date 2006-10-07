<html>
	<head>
		<title><?php siteinfo('sitename'); ?></title>
		<link rel="stylesheet" href="<?php siteinfo('stylesheet'); ?>" type="text/css" />
		<?php run_actions('site_header'); ?>
	</head>
	<body<?php TO_body_class(); ?>>
		<div id="page">
		<?php echo_sections(); ?>