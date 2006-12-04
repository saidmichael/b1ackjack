-- 
-- Database: `blackjack`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_comments`
-- 

CREATE TABLE `bj_comments` (
  `ID` bigint(20) NOT NULL auto_increment,
  `post_ID` bigint(20) NOT NULL default '0',
  `author_name` tinytext NOT NULL,
  `author_email` varchar(100) NOT NULL default '',
  `author_url` varchar(100) NOT NULL default '',
  `author_IP` varchar(100) NOT NULL default '',
  `posted_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('normal','hidden','spam') NOT NULL default 'normal',
  `user_id` bigint(20) NOT NULL default '0',
  `content` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `bj_comments`
-- 

INSERT INTO `bj_comments` VALUES (1, 1, 'Bob', 'Bob@bob.com', 'http://beepboopbop.com', '127.0.0.1', '2006-08-21 13:04:17', 'normal', 0, '<p>Test comment.</p>');
INSERT INTO `bj_comments` VALUES (2, 46, 'Your Mom', 'your.mom@gmail.com', 'http://beepboopbop.com', '127.0.0.1', '2006-10-28 03:46:13', 'normal', 0, '<p>This is your mother! I''m demanding you be quiet, now!</p>');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_options`
-- 

CREATE TABLE `bj_options` (
  `option_id` bigint(20) NOT NULL auto_increment,
  `option_name` varchar(64) NOT NULL default '',
  `option_value` longtext NOT NULL,
  `option_description` tinytext NOT NULL,
  PRIMARY KEY  (`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `bj_options`
-- 

INSERT INTO `bj_options` VALUES (1, 'active_plugins', 'a:1:{s:12:"gravatar.php";b:1;}', '');
INSERT INTO `bj_options` VALUES (4, 'sitename', 'BlackJack Test Site', 'CMS Title');
INSERT INTO `bj_options` VALUES (5, 'siteurl', 'http://localhost/blackjack/', 'URL');
INSERT INTO `bj_options` VALUES (6, 'db_version', '0.1', '');
INSERT INTO `bj_options` VALUES (7, 'current_skin', 'twentyone', '');
INSERT INTO `bj_options` VALUES (8, 'default_section', 'fun-section', '');
INSERT INTO `bj_options` VALUES (10, 'entries_per_page', '10', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_posts`
-- 

CREATE TABLE `bj_posts` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `shortname` text NOT NULL,
  `content` longtext NOT NULL,
  `author` text NOT NULL,
  `posted` datetime NOT NULL default '0000-00-00 00:00:00',
  `ptype` enum('public','draft','mod') NOT NULL default 'public',
  `section` bigint(20) NOT NULL default '0',
  `comment_count` bigint(20) NOT NULL default '0',
  `comments_open` tinyint(1) NOT NULL default '1',
  `tags` text NOT NULL,
  `meta` varchar(225) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- 
-- Dumping data for table `bj_posts`
-- 

INSERT INTO `bj_posts` VALUES (39, 'Super-duper FunTest', 'super-duper-funtest', '<p>Yes. An amazing test.</p><p>It all began with a super-super-fun test, that one Sunny Day&trade;... </p>', 'Mark', '2006-10-18 16:55:32', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (27, 'Beepboopbop!', 'beepboopbop', '<p>Strange. I keep writing pointless posts.</p><p>&quot;Do you think it&#39;s strange?&quot; <a href="http://tbomonline.com">Test</a>. </p>', 'Mark', '2006-10-02 14:10:33', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (28, '"YOUR FAT"', 'your-fat', '<p>Yes.</p><p>He actually said &quot;YOUR FAT&quot;.</p><p>It&#39;s kind of sad, really: Normally, you&#39;d expect that they&#39;d make more sense about something like that. But <em>nooooo</em>. They said &quot;YOUR FAT&quot;.</p><p>It&#39;s a shame. </p>', 'Mark', '2006-10-02 18:11:36', 'public', 2, 0, 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (31, 'Guuuuah', 'guuuuah', '<p>Kapow!</p><p>Action.e </p>', 'Mark', '2006-10-08 13:37:39', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (32, 'Moose.', 'moose', '<p>??? </p>', 'Mark', '2006-10-08 13:37:49', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (33, 'This is a fun test', 'this-is-a-fun-test', '<p>Wtf? Weird.<br /></p>', 'Mark', '2006-10-08 13:38:01', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (34, 'Mooooooooose.', 'mooooooooose', '<p>Quack. </p>', 'Mark', '2006-10-08 13:38:16', 'public', 2, 0, 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (35, 'Eight', 'eight', '<p>Yes. Fun. </p>', 'Mark', '2006-10-08 13:38:37', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (36, 'Nine', 'nine', '<p>Ten.</p><p>Wahooo! </p>', 'Mark', '2006-10-08 13:38:50', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (37, '???', 'questionmark', '<p>Yoyo. </p>', 'Mark', '2006-10-08 13:39:06', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (38, 'kapow', 'kapow', '<p>Very blank. </p>', 'Mark', '2006-10-08 13:39:13', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (42, 'Blah blah title', 'blah-blah-title', '', 'Mark', '2006-10-23 19:47:13', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (43, 'Thirteen', 'thirteen', '<p>Thirteeeeeen posts.</p><p>Woo. </p>', 'Mark', '2006-10-23 20:24:31', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (44, '42', '42', '<p>42 was a while back.</p><ol><li>List list.</li><li>Cool list.</li><li>List...<br /><br />With a shift-enter in it.</li></ol><p>Stop the list there. </p>', 'Mark', '2006-10-23 20:25:16', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (46, 'WOOOOOOO', 'wooooooo', '<p>Here we go. </p>', 'Mark', '2006-10-23 20:26:40', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', '');
INSERT INTO `bj_posts` VALUES (48, 'Kikabow!', 'kikabow', '<p>Let&#39;s add a draft entry for the heck of it. </p>', 'Mark', '2006-10-23 20:29:46', 'public', 2, 0, 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (49, 'Ahaha', 'ahaha', '<p>It&#39;s working. It just may be working.</p><p>I can&#39;t wait. </p>', 'Mark', '2006-11-08 16:33:49', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (51, 'I just deleted a post.', 'i-just-deleted-a-post', '<p>Shame on me. :(</p><p>It was because I was making a global ajax deleter. </p>', 'Mark', '2006-12-02 19:15:08', 'public', 2, 0, 1, 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}', '');
INSERT INTO `bj_posts` VALUES (54, 'Beepboopbop...', 'beepboopbop', '<p>Tee hee.</p><p>Section with a single post.&nbsp;</p>', 'Mark', '2006-12-03 17:00:50', 'public', 4, 0, 1, 'a:1:{i:0;s:1:"1";}', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_sections`
-- 

CREATE TABLE `bj_sections` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `shortname` text NOT NULL,
  `handler` varchar(200) NOT NULL default '',
  `hidden` enum('yes','no') NOT NULL default 'no',
  `page_order` int(11) NOT NULL default '0',
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `bj_sections`
-- 

INSERT INTO `bj_sections` VALUES (1, 'Fun Section', 'fun-section', '', 'no', 0, '2006-12-01 01:14:08');
INSERT INTO `bj_sections` VALUES (2, 'Super-Fun Section', 'super-fun-section', '', 'no', 1, '2006-12-01 20:46:15');
INSERT INTO `bj_sections` VALUES (4, 'Section with a Single Post', 'section-with-a-single-post', 'section-single.php', 'no', 2, '2006-12-03 17:18:11');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_tags`
-- 

CREATE TABLE `bj_tags` (
  `ID` bigint(20) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL default '',
  `shortname` varchar(55) NOT NULL default '',
  `parent` bigint(20) NOT NULL default '0',
  `posts_num` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `bj_tags`
-- 

INSERT INTO `bj_tags` VALUES (1, 'Test Tag', 'test-tag', 0, 1);
INSERT INTO `bj_tags` VALUES (2, 'Nother Test', 'nother-test', 0, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_users`
-- 

CREATE TABLE `bj_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `login` varchar(60) NOT NULL default '',
  `display_name` varchar(200) NOT NULL default '',
  `password` varchar(70) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `rte` enum('true','false') NOT NULL default 'true',
  `registered_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `activation_key` varchar(60) NOT NULL default '',
  `login_key` varchar(60) NOT NULL default '',
  `user_group` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `bj_users`
-- 

INSERT INTO `bj_users` VALUES (1, 'Epsilon', 'Mark', 'c2aadac2ca30ca8aadfbe331ae180d28', 'Kraahkanite@gmail.com', 'http://epsilon.blogs.tbomonline.com', 'true', '2006-08-15 11:53:14', '', 'b7c5546f4bd28c736f3ca1a034901534', 4);