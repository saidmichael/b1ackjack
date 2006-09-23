-- Host: localhost
-- Generation Time: Sep 07, 2006 at 05:57 PM
-- Server version: 4.1.9
-- PHP Version: 4.3.10
-- 
-- Database: `blackjack`
-- Change the values as needed.
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `bj_comments`
-- 

INSERT INTO `bj_comments` VALUES (1, 1, 'Bob', 'Bob@bob.com', 'http://bob.com', '127.0.0.1', '2006-08-21 13:04:17', 'normal', 0, 'Test comment.');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `bj_options`
-- 

INSERT INTO `bj_options` VALUES (1, 'active_plugins', 'a:0:{}', '');
INSERT INTO `bj_options` VALUES (4, 'sitename', 'BlackJack Test Site', 'CMS Title');
INSERT INTO `bj_options` VALUES (5, 'siteurl', 'http://localhost/blackjack/', 'URL');

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
  `parent` bigint(20) NOT NULL default '0',
  `comment_count` bigint(20) NOT NULL default '0',
  `tags` text NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `bj_posts`
-- 

INSERT INTO `bj_posts` VALUES (1, 'Test Post', 'test-post', '<p>Funfuntest.</p> <p>&raquo; Wheee.</p><p>bobob&nbsp;</p>', 'Mark', '2006-08-21 10:05:20', 'public', 0, 0, '2');
INSERT INTO `bj_posts` VALUES (2, 'More Fun', 'more-fun', 'yeehaw', 'Mark', '2006-08-21 09:40:12', 'public', 0, 0, '2');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_sections`
-- 

CREATE TABLE `bj_sections` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `static` enum('yes','no') NOT NULL default 'no',
  `filter` varchar(200) NOT NULL default '',
  `hidden` enum('yes','no') NOT NULL default 'no',
  `page_order` int(11) NOT NULL default '0',
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `bj_sections`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `bj_tags`
-- 

CREATE TABLE `bj_tags` (
  `ID` bigint(20) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL default '',
  `parent` bigint(20) NOT NULL default '0',
  `posts_num` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `bj_tags`
-- 

INSERT INTO `bj_tags` VALUES (1, 'Test Tag', 0, 1);
INSERT INTO `bj_tags` VALUES (2, 'Nother Test', 0, 1);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `bj_users`
-- 

INSERT INTO `bj_users` VALUES (1, 'Epsilon', 'Mark', 'c2aadac2ca30ca8aadfbe331ae180d28', 'Kraahkanite@gmail.com', 'http://epsilon.blogs.tbomonline.com', 'true', '2006-08-15 11:53:14', '', 'fe676d63fc4c1f7c00435891e70a4de9', 4);

-- I think the password is "canada".