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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `bj_comments`
-- 

INSERT INTO `bj_comments` (`ID`, `post_ID`, `author_name`, `author_email`, `author_url`, `author_IP`, `posted_on`, `status`, `user_id`, `content`) VALUES 
(2, 55, 'Your Mom', 'your.mom@gmail.com', 'http://beepboopbop.com', '127.0.0.1', '2006-10-28 03:46:13', 'normal', 0, 'This is your mother! I''m demanding you be quiet, now!');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_entries`
-- 

CREATE TABLE `bj_entries` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- 
-- Dumping data for table `bj_entries`
-- 

INSERT INTO `bj_entries` (`ID`, `title`, `shortname`, `content`, `author`, `posted`, `ptype`, `section`, `comment_count`, `comments_open`, `tags`, `meta`) VALUES 
(39, 'Super-duper FunTest', 'super-duper-funtest', 'Yes. An amazing test.\r\n\r\nIt all began with a super-super-fun test, that one Sunny Day&trade;...', 'Mark', '2006-10-18 16:55:32', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', ''),
(27, 'Beepboopbop!', 'beepboopbop', '<p>Strange. I keep writing pointless posts.</p><p>&quot;Do you think it&#39;s strange?&quot; <a href="http://tbomonline.com">Test</a>. </p>', 'Mark', '2006-10-02 14:10:33', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(58, 'Sidenotes Test', 'sidenotes-test', 'This is a sidenote. The sidenotes feature is implemented in this skin only, but you can <strike>steal</strike> borrow it from us if you want.', 'Mark', '2006-12-20 18:18:47', 'public', 10, 0, 1, 'a:2:{i:0;s:1:"6";i:1;s:2:"33";}', ''),
(35, 'Eight', 'eight', 'Yes. Fun.', 'Mark', '2006-10-08 13:38:37', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(56, 'Hm', 'hm', 'Entries, entries everywhere.\r\n\r\nLovely.', 'Mark', '2006-12-09 13:20:19', 'public', 2, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(38, 'kapow', 'kapow', '<p>Very blank. </p>', 'Mark', '2006-10-08 13:39:13', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(42, 'Blah blah title', 'blah-blah-title', 'Wtf? No content?', 'Mark', '2006-10-23 19:47:13', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(43, 'Thirteen', 'thirteen', 'Thirteeeeeen posts.\r\n\r\nWoo.', 'Mark', '2006-10-23 20:24:31', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(44, '42', '42', '42 was a while back.\r\n<ol>\r\n<li>List list.</li>\r\n<li>Cool list.</li>\r\n<li>List...<br /><br />With a shift-enter in it.</li>\r\n</ol>\r\nStop the list there.', 'Mark', '2006-10-23 20:25:16', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', ''),
(46, 'WOOOOOOO', 'wooooooo', 'Here we go.', 'Mark', '2006-10-23 20:26:40', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"1";}', ''),
(49, 'Ahaha', 'ahaha', 'It''s working. It just may be working.\r\n\r\nI can''t wait.', 'Mark', '2006-11-08 16:33:49', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(51, 'I just deleted a post.', 'i-just-deleted-a-post', 'Shame on me. :(\r\n\r\nIt was because I was making a global ajax deleter.', 'Mark', '2006-12-02 19:15:08', 'public', 2, 0, 1, 'a:0:{}', ''),
(55, 'test post', 'test-post', 'I''m seeing if it''ll add to tags.', 'Mark', '2006-12-06 17:20:40', 'public', 1, 2, 1, 'a:1:{i:0;s:1:"1";}', ''),
(54, 'Home Post', 'home-post', 'Congratulations! On your browser is a brand spankin'' new copy of Blackjack, an easy-to-use content management system. For those more experienced with the software, consider this your "go-ahead" message: set up your website as you please. If you''re a first-timer, introductions are definately in order; keep on reading, patient user.\r\n\r\nI (the person who is writing this) am Mark Graham, the writer of Blackjack. When I came up with the idea to make this, it was pretty scetchy, but as I continued developing it, a pretty solid idea about what to include and what not to include was made.\r\n\r\nAs easy as we tried to make Blackjack, you still may encounter some problems, which seems to be expected- we get them all of the time too. :-) So that''s why we set up a <a href="" title="Link to Forum">forum</a> and a <a href="" title="Wiki Link">wiki</a>, so you can obtain all of that knowledge I''m sure you''re yearning to get.\r\n\r\nThe layout of the website, called a "skin", right now is the default one. We''ve added a bunch of special web-developer specific elements which you can use as a jumpstart to your own customized website. If you''re the more adventurous sort of person, or just think the default skin isn''t good enough for what you want out of Blackjack (we''re not offended), you may be in the mood to create your own skin. We''ve made a <a href="" title="External link to our skinning guide. Sexy.">skinning guide</a> for that.\r\n\r\nWell, I suppose that''s all of the basic information you''ll need to get your site off of the ground; remember, if you ever need any help, don''t hesitate to ask the Blackjack community or browse through the wiki (we provided the links to those earlier in this entry, remember?). Have fun with your new site! :-)', 'Mark', '2006-12-03 17:00:50', 'public', 4, 0, 1, 'a:1:{i:0;s:1:"2";}', ''),
(57, 'Busy', 'busy', 'I''ve become very busy lately. I''ve got about three projects for the internet- I have to work on Blackjack, a content management system, reskin tBoM, and do some super-secret BS01 work.\r\n\r\nI''ve become really glad about tBoM''s new skin right now- a while back, we created a skin like the front page that looked much like the front page- a bar on the left, forum layout on the right- pretty traditional. We opened it for viewing to a few certain people, and, to the honest, they didn''t like it. At all.\r\n\r\n__More__\r\n\r\nSo we decided, what the heck, let''s reskin it. And I''ve already put up <a href="http://www.flickr.com/photos/bomepsilon/301057575/">photos of it</a>. And I can agree with those people who didn''t like the skin- the simplicity of having the "leftbar" at the top just seems to....work. :D\r\n\r\nToday is my birthday, yes.  I''m currently writing this at about 11:49 in the morning, but quite a bit has already happened. We''re going to look for a black DS Lite, because no stores near us have one. Odds are likely we''ll have to drive all the way out to New Jersey just for the black model. Oh well.\r\n\r\nI really haven''t blogged much lately. I mean, my last entry was five days ago. I suppose it''s because I haven''t come up with much to write about. I''ve written on a good slice of political causes, quizzes, links, rants, blah blah blah- I just need to be more aware of what I can write about. There has to be something. I just need to find it.\r\n\r\n<a href="http://epsilon.blogs.tbomonline.com/tag/to-whom-it-may-concern/">To Whom it May Concern</a>. Whoo. I''ve written TONS of these, on paper, each a page long, in my last period class. It really is a waste of time. So I just write. Write about the class, the kids, world events, a bunch of crap. That one post contains one of my first. I suppose I can type up one of them, revise it a bit. Yeah, I may actually do that.\r\n\r\nI''m not quite sure how I should close this. So, um, yeah. It''s over.\r\n\r\nUpdate: <a href="http://epsilon.blogs.tbomonline.com/2006/11/25/busy-busy-busy/#comment-1161">Read this</a>.', 'Mark', '2006-12-09 13:22:55', 'public', 1, 0, 1, 'a:3:{i:0;s:2:"15";i:1;s:1:"2";i:2;s:1:"1";}', ''),
(59, 'For your Thoughts', 'for-your-thoughts', 'If an emo cuts his wrists in a forest, and no one''s around to hear it, does it make a sound?', 'Mark', '2006-12-22 17:17:45', 'public', 10, 0, 1, 'a:1:{i:0;s:2:"33";}', ''),
(60, 'The Code is...Messy!', 'the-code-ismessy', 'Oh noes! Messy code!\r\n\r\nThe last thing I want is messy code.\r\n\r\nI need to clean this up.', 'Mark', '2006-12-23 13:22:52', 'public', 1, 0, 1, 'a:1:{i:0;s:1:"6";}', ''),
(61, 'About Entry', 'about-entry', 'This is a test Blackjack site. As you can see, it''s kind of empty at the moment, so you may want to stop by later and check out our site at its best.', 'Mark', '2006-12-23 16:17:36', 'public', 11, 0, 1, 'a:1:{i:0;s:1:"6";}', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_options`
-- 

CREATE TABLE `bj_options` (
  `ID` bigint(20) NOT NULL auto_increment,
  `option_name` varchar(64) NOT NULL default '',
  `option_value` longtext NOT NULL,
  `option_description` tinytext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `bj_options`
-- 

INSERT INTO `bj_options` (`ID`, `option_name`, `option_value`, `option_description`) VALUES 
(1, 'active_plugins', 'a:1:{s:12:"gravatar.php";b:1;}', ''),
(4, 'sitename', 'BlackJack Test Site', 'CMS Title'),
(5, 'siteurl', 'http://localhost/blackjack/', 'URL'),
(6, 'db_version', '0.1', ''),
(7, 'current_skin', 'twentyone', ''),
(8, 'default_section', '4', ''),
(10, 'entries_per_page', '10', ''),
(11, 'modules', 'a:3:{s:10:"txtmod_num";i:1;s:10:"rssmod_num";i:1;s:7:"modules";a:2:{s:4:"txt1";a:3:{s:5:"title";s:11:"Text Widget";s:7:"content";s:50:"This is a text widget.\r\n\r\nMulti paragraph support.";s:7:"display";a:1:{s:8:"sections";s:30:"a:2:{i:0;s:1:"1";i:1;s:1:"2";}";}}s:4:"rss1";a:3:{s:5:"title";s:10:"RSS Widget";s:3:"url";s:30:"http://localhost/blackjack/rss";s:7:"display";a:1:{s:8:"sections";s:30:"a:2:{i:0;s:1:"2";i:1;s:1:"4";}";}}}}', 'Sidebar modules.'),
(12, 'skin_settings', 'a:3:{s:18:"sidenotes_position";s:7:"sidebar";s:13:"sidenotes_tag";s:2:"33";s:13:"sidenotes_num";s:2:"10";}', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_sections`
-- 

CREATE TABLE `bj_sections` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `shortname` text NOT NULL,
  `handler` varchar(200) NOT NULL default '',
  `stylesheet` varchar(200) NOT NULL default '',
  `hidden` enum('yes','no') NOT NULL default 'no',
  `page_order` int(11) NOT NULL default '0',
  `last_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `bj_sections`
-- 

INSERT INTO `bj_sections` (`ID`, `title`, `shortname`, `handler`, `stylesheet`, `hidden`, `page_order`, `last_updated`) VALUES 
(1, 'Weblog', 'weblog', '', 'style.css', 'no', 2, '2006-12-23 18:11:39'),
(2, 'Portfolio', 'portfolio', '', 'style.css', 'no', 4, '2006-12-23 18:11:41'),
(4, 'Home', 'home', 'section-single.php', 'style.css', 'no', 0, '2006-12-23 18:11:34'),
(11, 'About', 'about', 'section-single.php', 'style.css', 'no', 1, '2006-12-23 18:11:36'),
(10, 'Sidenotes', 'sidenotes', '', 'style.css', 'yes', 10, '2006-12-23 18:11:43');

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_tags`
-- 

CREATE TABLE `bj_tags` (
  `ID` bigint(20) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL default '',
  `shortname` varchar(55) NOT NULL default '',
  `posts_num` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

-- 
-- Dumping data for table `bj_tags`
-- 

INSERT INTO `bj_tags` (`ID`, `name`, `shortname`, `posts_num`) VALUES 
(1, 'Test Tag', 'test-tag', 10),
(2, 'Nother Test', 'nother-test', 13),
(8, 'StikiNote', 'stikinote', 0),
(6, 'Blackjack', 'blackjack', 3),
(15, 'Cool-Cat Tag', 'cool-cat-tag', 1),
(33, 'Sidenotes', 'sidenotes', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `bj_users`
-- 

CREATE TABLE `bj_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `login` varchar(60) NOT NULL default '',
  `display_name` varchar(200) NOT NULL default '',
  `friendly_name` varchar(200) NOT NULL default '',
  `password` varchar(70) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `registered_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `activation_key` varchar(60) NOT NULL default '',
  `user_group` int(11) NOT NULL default '0',
  `about` tinytext NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `bj_users`
-- 

INSERT INTO `bj_users` (`ID`, `login`, `display_name`, `friendly_name`, `password`, `email`, `website`, `registered_on`, `activation_key`, `user_group`, `about`) VALUES 
(1, 'Epsilon', 'Mark', 'mark', '49f5e2f030706412667b36d2c65d2707', 'Kraahkanite@gmail.com', 'http://epsilon.blogs.tbomonline.com', '2006-08-15 11:53:14', '', 4, 'Epsilon is an extremely cool dude.\r\n\r\nWho wears socks.');
