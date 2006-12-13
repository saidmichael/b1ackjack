<?php
$parent_file = "index.php";
require("admin-head.php");
if(we_can('view_frontpage')) {
	get_admin_header();
?>
			<h2><?php _e('Front Page'); ?></h2>
			<div class="column width33">
				<div class="c-ontent">
					<div class="tblock">
						<h3><?php _e('Welcome'); ?></h3>
						<p><?php _e('Welcome to Blackjack, the powerful content management system that focuses on usability, easy modification, and management tools to suit the user&#8217;s needs. Use these tools to get started on your website:'); ?></p>
						<ul class="altrows">
<?php			 fancy_altrows(
					array(_r('Write an Entry')=>"entry-write.php",
						  _r('Create a Section')=>"section-create.php",
						  _r('Change the Website&#8217;s Skin')=>"skins.php"
					)); ?>
						</ul>
					</div>
					<div class="tblock">
						<h3><?php _e('Statistics'); ?></h3>
						<p><?php printf(
					_r('Right now, there are <a href="entries.php">%1$s entries</a>, <a href="sections.php">%2$s sections</a>, <a href="comments.php">%3$s comments</a>, and <a href="tags.php">%4$s tags</a>.'),
					get_entries('num=yes'),
					mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->sections."`")),
					get_comments('num=yes'),
					mysql_num_rows($bj_db->query("SELECT * FROM `".$bj_db->tags."`"))
					); ?></p>
					</div>
				</div>
			</div>
			<div class="column width33">
				<div class="c-ontent">
					<div class="tblock">
						<h3><?php _e('Version Check'); ?></h3>
<?php			$version = @file_get_contents("http://ceeps.blogs.tbomonline.com/bj-versioncheck/?v=".$bj_version);
				if(!$version) { ?>
						<p><?php _e('Version check is temporarily offline.'); ?></p>
<?php
				}
				else {
					switch($version) {
						case 'good' : ?>
						<p><?php _e('Your current version of Blackjack is <strong>up-to-date</strong>. When a new version comes out, you will get a notification in this spot.'); ?></p>
<?php
						break;
						case 'security' : ?>
						<p><?php _e('<strong class="error"><big>Security alert!</big></strong> Your version of Blackjack has been replaced by a newer version that fixes a major security hole. It is recommended you <a href="http://ceeps.blogs.tbomonline.com/section/blackjack/"><strong>upgrade immediately</strong></a>.'); ?></p>
<?php
						break;
						case 'bad' : ?>
						<p><?php _e('<strong class="error">Warning!</strong> Your version of Blackjack is currently <strong>out of date</strong>. Please update your version of Blackjack at the <a href="http://ceeps.blogs.tbomonline.com/section/blackjack/"><strong>Blackjack Minisite</strong></a>.'); ?></p>
<?php				
						break;
					}
				} ?>
					</div>
					<div class="tblock">
						<h3><?php _e('Blackjack News'); ?></h3>
						<ul class="altrows">
<?php			$rss = @fetch_rss("http://ceeps.blogs.tbomonline.com/section/blackjack/feed/");
				$rss->items = array_slice($rss->items,0,6);
				foreach($rss->items as $item) {
					$bjnews[$item['title']] = $item['link'];
				}
				fancy_altrows($bjnews); ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="column width33">
				<div class="c-ontent">
					<div class="tblock">
						<h3><?php _e('Recent Entries'); ?></h3>
<?php			$entries = get_entries('type=public&sortby=posted&limit=5');
				if(!$entries) { ?><p><?php _e('None found.'); ?></p><?php }
				else { ?>
						<ul class="altrows">
<?php			foreach($entries as $entry) {
					$bjentries[return_title()] = "entries.php?req=edit&amp;id=".return_ID();
				}
				fancy_altrows($bjentries); ?>
						</ul>
<?php			} ?>
					</div>
					<div class="tblock">
						<h3><?php _e('Recent Comments'); ?></h3>
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
					</div>
				</div>
			</div>
<?php run_actions('end_frontpage'); ?>
<?php
	get_admin_footer();
}
else {
	_e('You don\'t have permission to access this file.');
}?>