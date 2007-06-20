<?php
$parent_file = "index.php";
require("admin-head.php");
if(we_can('view_frontpage')) {
	get_admin_header();
?>
			<h2><?php _e('Moonbase'); ?></h2>
			<div class="column width50">
				<div class="c-ontent">
					<h3><?php _e('Quick Links'); ?></h3>
					<ul class="altrows">
<?php					fancy_altrows(
					array('<a href="entry-write.php">'._r('Write an Entry').'</a>',
						'<a href="sections.php">'._r('Create a Section').'</a>',
						'<a href="skins.php">'._r('Change the Website&#8217;s Skin').'</a>',
						'<a href="settings.php">'._r('Tweak Some Settings').'</a>'
					)); ?>
					</ul>
					<h3><?php _e('Statistics'); ?></h3>
					<p><?php printf(
					_r('Right now, there are <a href="%1$s">%2$s entries</a>, <a href="%3$s">%4$s sections</a>, <a href="%5$s">%6$s comments</a>, and <a href="%7$s">%8$s tags</a>.'),
					'entries.php',
					count($bj->cache->get_entries()),
					'sections.php',
					count(return_sections()),
					'comments.php',
					get_comments('num=yes'),
					'tags.php',
					count(return_all_tags())
					); ?></p>
				</div>
			</div>
			<div class="column width50">
				<div class="c-ontent">
					<h3><?php _e('Recent Entries'); ?></h3>
<?php			
			$bj->query->setLimit(0,5);
			$bj->query->setPtype('public');
			$entries = $bj->query->fetch();
			if(!$entries) { ?>
					<p><?php _e('None found.'); ?></p>
<?php
			}
			else { ?>
					<ul class="altrows">
<?php				foreach($entries as $entry)
					$bjentries[] = '<a href="entries.php?req=edit&amp;id='.get_entry_ID().'">'.get_entry_title().'</a>';
				fancy_altrows($bjentries); ?>
					</ul>
<?php			} ?>
					<h3><?php _e('Latest Comments'); ?></h3>
<?php			$comments = get_comments('status=normal&sortby=posted_on&order=DESC',' LIMIT 0,6');
			if(!$comments) { ?>
					<p><?php _e('None found.'); ?></p>
<?php
			}
			else { ?>
					<ul class="altrows">
<?php
				$in_mod = mysql_num_rows($bj->db->query("SELECT * FROM `".$bj->db->comments."` WHERE `status` = 'hidden'"));
				if($in_mod > 0) { ?>
						<li class="dude_important"><a href="comments.php?mod=mod"><strong><?php printf(_r('%1$s Awaiting Moderation'),$in_mod); ?></strong></a></li>
<?php
				}
				foreach($comments as $comment)
					$bjcomments[] = '<a href="comments.php?req=edit&amp;id='.return_comment_ID().'">'.return_comment_name().': '.return_comment_snippet(9).'</a>';
				fancy_altrows($bjcomments); ?>
					</ul>
<?php			}
				?>
				</div>
			</div>
<?php
	run_actions('end_frontpage');
	get_admin_footer();
}
else
	_e('You don\'t have permission to access this file.');
?>
