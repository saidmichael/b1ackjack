<?php
$parent_file = "index.php";
require("admin-head.php");
if(we_can('view_frontpage')) {
	get_admin_header();
?>
		<div id="wrapper">
			<h1><?php _e('Front Page'); ?></h1>
			<div class="column width33">
				<h2><?php _e('Welcome'); ?></h2>
				<p><?php _e('Welcome to Blackjack, the powerful content management system that focuses on usability, easy modification, and management tools to suit the user&#8217;s needs. Use these tools to get started on your website:'); ?></p>
				<ul class="altrows">
<?php			 fancy_altrows(
					array(_r('Write a Post')=>"post-write.php",
						  _r('Create a Section')=>"section-create.php",
						  _r('Change the Website&#8217;s Theme')=>"themes.php"
					)); ?>
				</ul>
				<h2><?php _e('Statistics'); ?></h2>
				<p><?php printf(
					_r('Right now, there are <a href="posts.php">%1$s posts</a>, <a href="sections.php">%2$s sections</a>, <a href="comments.php">%3$s comments</a>, and <a href="tags.php">%4$s tags</a>.'),
					get_posts('num=yes'),
					mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->sections."`")),
					get_comments('num=yes'),
					mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->tags."`"))
					); ?></p>
			</div>
			<div class="column width33">
				<h2><?php _e('Version Check'); ?></h2>
<?php			$version = @file_get_contents("http://ceeps.blogs.tbomonline.com/bj-versioncheck/?v=".$bj_version);
				if(!$version) { ?>
				<p><?php _e('Version check is temporarily offline.'); ?></p>
<?php
				}
				else {
					if($version == "good") { ?>
				<p><?php _e('Your current version of Blackjack is <strong>up-to-date</strong>. When a new version comes out, you will get a notification in this spot.'); ?></p>
<?php				} else { ?>
				<p><?php _e('<strong class="error">Warning!</strong> Your version of Blackjack is currently <strong>out of date</strong>. Please update your version of Blackjack at the <a href="http://ceeps.blogs.tbomonline.com/blackjack/"><strong>Blackjack Minisite</strong></a>.'); ?></p>
<?php				}
				} ?>
				<h2><?php _e('Blackjack News'); ?></h2>
				<ul class="altrows">
<?php			$rss = @fetch_rss("http://ceeps.blogs.tbomonline.com/section/blackjack/feed/");
				$rss->items = array_slice($rss->items,0,6);
				foreach($rss->items as $item) {
					$bjnews[$item['title']] = $item['link'];
				}
				fancy_altrows($bjnews); ?>
				</ul>
			</div>
			<div class="column width33">
				<h2><?php _e('Recent Posts'); ?></h2>
<?php			$posts = get_posts('type=public&sortby=posted&limit=5');
				if(!$posts) { ?><p><?php _e('None found.'); ?></p><?php }
				else { ?>
				<ul class="altrows">
<?php			foreach($posts as $post) {
					$bjposts[return_title()] = "posts.php?req=edit&amp;id=".return_ID();
				}
				fancy_altrows($bjposts); ?>
				</ul>
<?php			} ?>
				<h2><?php _e('Recent Comments'); ?></h2>
<?php			$comments = $bj_db->get_rows("SELECT * FROM `".$bj_db->comments."` WHERE `status` = 'normal' ORDER BY `posted_on` ASC LIMIT 0,6","ASSOC");
				if(!$comments) { ?><p><?php _e('None found.'); ?></p><?php }
				else { ?>
				<ul class="altrows">
<?php			foreach($comments as $comment) {
					$bjcomments[return_comment_name().": ".bj_excerpt(return_comment_text(),7)."&#8230;"] = "comments.php?req=edit&amp;id=".return_comment_ID();
				}
				fancy_altrows($bjcomments); ?>
				</ul>
<?php			}
				?>
				<?php run_actions('end_frontpage'); ?>
			</div>
		</div>
<?php
	get_admin_footer();
}
else {
	_e('You don\'t have permission to access this file.');
}?>