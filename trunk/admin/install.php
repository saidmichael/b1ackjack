<?php

if(!file_exists('../bj_config.php'))
	die('It appears there is no bj_config.php file. Try opening up <strong>bj_config_sample.php</strong>, filling out the information, and renaming it as bj_config.php. Then revisit this page and let me do my work.');

require "../bj_config.php";
function run_actions() { } #Quick and dirty fix for the fact the db class uses run_actions.
require_once "../core/class_db.php";

switch($_POST['stage']) {
case "2" : ?>
<html>
	<head>
		<title>Blackjack Installation: Parte due</title>
	</head>
	<body>
		<h1>Blackjack Installation: Parte due</h1>
		<p>Thanks for the information. Please wait while we attempt to complete the necessary processes for the database information to be inserted.</p>
		<hr />
		<p>Creating tables...</p>
<?php
	$bj->db->query("CREATE TABLE `".$bj->db->comments."` (
  `ID` bigint(20) NOT NULL auto_increment,
  `post_ID` bigint(20) NOT NULL default '0',
  `author_name` tinytext NOT NULL,
  `author_email` varchar(100) NOT NULL default '',
  `author_url` varchar(100) NOT NULL default '',
  `author_IP` varchar(100) NOT NULL default '',
  `posted_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(100) NOT NULL default 'normal',
  `user_id` bigint(20) NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;");
	$bj->db->query("INSERT INTO `".$bj->db->comments."` (`ID`, `post_ID`, `author_name`, `author_email`, `author_url`, `author_IP`, `posted_on`, `status`, `user_id`, `content`) VALUES (1, 1, 'Michael Ryan', 'Kraahkanite@gmail.com', 'http://epsilon.blogs.tbomonline.com', '127.0.0.1', '".date('Y-m-d H:i:s')."', 'normal', 0, 'Hello there; this is your first comment to verify the success of the installation.');");
	echo '<p>Comments</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->entries."` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `shortname` text NOT NULL,
  `content` longtext NOT NULL,
  `author` bigint(20) NOT NULL,
  `posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `ptype` varchar(200) NOT NULL default 'public',
  `section` bigint(20) NOT NULL default '0',
  `comment_count` bigint(20) NOT NULL default '0',
  `comments_open` tinyint(1) NOT NULL default '1',
  `tags` text NOT NULL,
  `meta` varchar(225) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	$bj->db->query("INSERT INTO `".$bj->db->entries."` (`ID`, `title`, `shortname`, `content`, `author`, `posted`, `ptype`, `section`, `comment_count`, `comments_open`, `tags`, `meta`) VALUES (1, 'Home Post', 'home-post', 'Congratulations! On your browser is a brand spankin'' new copy of Blackjack, an easy-to-use content management system. For those more experienced with the software, consider this your \"go-ahead\" message: set up your website as you please. If you''re a first-timer, introductions are definately in order; keep on reading, patient user.\r\n\r\nI (the person who is writing this) am Michael Ryan, the writer of Blackjack. When I came up with the idea to make this, it was pretty scetchy, but as I continued developing it, a pretty solid idea about what to include and what not to include was made.\r\n\r\nAs easy as we tried to make Blackjack, you still may encounter some problems, which seems to be expected- we get them all of the time too. :-) So that''s why we set up a <a href=\"\" title=\"Link to Forum\">forum</a> and a <a href=\"\" title=\"Wiki Link\">wiki</a>, so you can obtain all of that knowledge I''m sure you''re yearning to get.\r\n\r\nThe layout of the website, called a \"skin\", right now is the default one. We''ve added a bunch of special web-developer specific elements which you can use as a jumpstart to your own customized website. If you''re the more adventurous sort of person, or just think the default skin isn''t good enough for what you want out of Blackjack (we''re not offended), you may be in the mood to create your own skin. We''ve made a <a href=\"\" title=\"External link to our skinning guide. Sexy.\">skinning guide</a> for that.\r\n\r\nWell, I suppose that''s all of the basic information you''ll need to get your site off of the ground; remember, if you ever need any help, don''t hesitate to ask the Blackjack community or browse through the wiki (we provided the links to those earlier in this entry, remember?). Have fun with your new site! :-)', 1, '".date('Y-m-d H:i:s')."', 'public', 1, 0, 1, 'a:1:{i:0;s:1:\"1\";}', 'a:0:{}');");
	echo'<p>Entries</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->options."` (
  `ID` bigint(20) NOT NULL auto_increment,
  `option_name` varchar(64) NOT NULL default '',
  `option_value` longtext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	$bj->db->query("INSERT INTO `".$bj->db->options."` (`ID`, `option_name`, `option_value`) VALUES 
(1, 'sitename', '".htmlspecialchars(strip_tags($_POST['sitename']))."'),
(2, 'db_version', '1'),
(3, 'current_skin', 'twentyone'),
(4, 'default_section', '1'),
(5, 'entries_per_page', '10'),
(6, 'enable_commenting', '1');");
	echo'<p>Options</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->sections."` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `shortname` text NOT NULL,
  `handler` varchar(200) NOT NULL default '',
  `stylesheet` varchar(200) NOT NULL default '',
  `hidden` enum('yes','no') NOT NULL default 'no',
  `page_order` int(11) NOT NULL default '0',
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	$bj->db->query("INSERT INTO `".$bj->db->sections."` (`ID`, `title`, `shortname`, `handler`, `stylesheet`, `hidden`, `page_order`, `last_updated`) VALUES (1, 'Front Page', 'front-page', '', 'style.css', 'no', 0, '".date('Y-m-d H:i:s')."');");
	echo'<p>Sections</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->tags."` (
  `ID` bigint(20) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL default '',
  `shortname` varchar(55) NOT NULL default '',
  `posts_num` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	$bj->db->query("INSERT INTO `bj_tags` (`ID`, `name`, `shortname`, `posts_num`) VALUES 
(1, 'First Tag!', 'first-tag', 1);");
	echo'<p>Tags</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->usermeta."` (
  `umeta_id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL default '0',
  `meta_key` varchar(255) default NULL,
  `meta_value` longtext,
  PRIMARY KEY  (`umeta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	echo'<p>User meta</p>';
	flush();
	
	$bj->db->query("CREATE TABLE `".$bj->db->users."` (
  `ID` bigint(20) NOT NULL auto_increment,
  `user_login` varchar(60) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_nicename` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_url` varchar(100) NOT NULL,
  `user_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL,
  `user_status` int(11) NOT NULL default '0',
  `display_name` varchar(250) NOT NULL,
  `bj_group` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
	$bj->db->query("INSERT INTO `".$bj->db->users."` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`, `bj_group`) VALUES (1, '".htmlspecialchars(strip_tags($_POST['user_login']))."', '".md5($_POST['user_pass'])."', '', '".htmlspecialchars(strip_tags($_POST['user_email']))."', '', '".date('Y-m-d H:i:s')."', '', 0, '".htmlspecialchars(strip_tags($_POST['user_login']))."', 3);");
	echo'<p>Users</p>';
	flush();

	?>
	
	Done! Now make your site and leave me be. Fool.
	</body>
</html>
<?php
	break;
default : ?>
<html>
	<head>
		<title>Blackjack Installation</title>
	</head>
	<body>
		<form name="stage1" action="" method="post">
			<h1>Blackjack Installation: Phase One</h1>
			<p>Thanks for thinking of Blackjack as a totally awesome CMS you'd want to install. Of course, to be viewing this page, you renamed the bj_config_sample.php file, and we're hoping  you filled out the necessary details in it.</p>
			<p>Now that we've taken care of that, let's get on to making your database and setting up your Blackjack installation for production.</p>
			<hr />
			<p><label for="sitename">Site name:</label> <input type="text" name="sitename" value="" id="sitename" /></p>
			<p><label for="user_login">Login:</label> <input type="text" name="user_login" value="" id="user_login" /></p>
			<p><label for="user_email">Email:</label> <input type="text" name="user_email" value="" id="user_email" /></p>
			<p><label for="user_pass">Password:</label> <input type="password" name="user_pass" value="" id="user_pass" /></p>
			<hr />
			<p>...Um, wait. That's it.</p>
			<input type="hidden" name="stage" value="2" />
			<p><input type="submit" name="submit" value="Submit" /></p>
		</form>
	</body>
</html>
<?php
}

?>
