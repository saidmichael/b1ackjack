<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

if($bj->vars->load[0] == 'entry') {
	$query = new bj_entries(0,1);
	$query->setShortname($bj->vars->load[1]);
	$entries = $query->fetch();
	if(!$entries)
		load_404_instead();
	$comments = $bj->cache->get_comments($entries[0]['ID']);
	header('Content-type: text/xml; charset=iso-8859-1', true);
	echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<rss version="2.0"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	<?php run_actions('rss_namespace'); ?>
>
	<channel>
		<title><?php echo run_actions('rss_title',sprintf(_r('%1$s - Feed: %2$s'),get_siteinfo('sitename'),get_bj_title())); ?></title>
		<link><?php siteinfo('siteurl'); ?></link>
		<description><?php echo run_actions('rss_description',sprintf(_r('%1$s Feed for the %2$s %3$s'),get_siteinfo('sitename'),get_bj_title(),ucfirst($bj->vars->load[0]))); ?></description>
		<pubDate><?php entry_date('D, d M Y H:i:s +0000',$entries[0]['posted']); ?></pubDate>
		<generator>http://code.google.com/p/b1ackjack/</generator>
		<language><?php siteinfo('rss_language'); ?></language>
<?php run_actions('rss_head'); ?>
<?php
	if($comments) {
		foreach($comments as $comment) { ?>

		<item>
			<title><?php comment_name(); ?></title>
			<link><?php echo get_siteinfo('siteurl').'entry/'.$bj->vars->load[1].'#comment-'.return_comment_ID(); ?></link>
			<pubDate><?php comment_date('D, d M Y H:i:s +0000'); ?></pubDate>
			<description><![CDATA[<?php comment_text(); ?>]]></description>
			<?php run_actions('rss_entry_'.$entries[0]['ID']); ?>
		</item>
<?php
		}
	} ?>
	</channel>
</rss>
<?php
}
else {
	$query = new bj_entries(0,load_option('entries_per_page'));
	if($bj->vars->load[0] == 'section') {
		$section = $bj->db->get_item("SELECT * FROM ".$bj->db->sections." WHERE shortname = '".$bj->vars->load[1]."' LIMIT 1");
		if(!$section)
			load_404_instead();
		$query->setSection($section['ID']);
		$hook_name = 'section_'.$section['ID'];
	}
	elseif($bj->vars->load[0] == 'tag') {
		$tag = $bj->db->get_item("SELECT * FROM ".$bj->db->tags." WHERE shortname = '".$bj->vars->load[1]."' LIMIT 1");
		if(!$tag)
			load_404_instead();
		$query->setTags($tag['ID']);
		$hook_name = 'tag_'.$tag['ID'];
	}

	$entries = $query->fetch();
	header('Content-type: text/xml; charset=iso-8859-1', true);
	echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n"; ?>
<rss version="2.0" 
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	<?php run_actions('rss_namespace'); ?>
>
	<channel>
		<title><?php echo run_actions('rss_title',sprintf(_r('%1$s - Feed: %2$s'),get_siteinfo('sitename'),get_bj_title())); ?></title>
		<link><?php siteinfo('siteurl'); ?></link>
		<description><?php echo run_actions('rss_description',sprintf(_r('%1$s Feed for the %2$s %3$s'),get_siteinfo('sitename'),get_bj_title(),ucfirst($bj->vars->load[0]))); ?></description>
		<pubDate><?php entry_date('D, d M Y H:i:s +0000',$entries[0]['posted']); ?></pubDate>
		<generator>http://code.google.com/p/b1ackjack/</generator>
		<language><?php siteinfo('rss_language'); ?></language><?php run_actions('rss_head'); ?>
<?php
	if($entries) {
		foreach($entries as $entry) { thru_loop(); ?>

		<item>
			<title><?php entry_title(); ?></title>
			<link><?php entry_permalink(); ?></link>
			<pubDate><?php entry_date('D, d M Y H:i:s +0000'); ?></pubDate>
<?php
			if(!commenting_is_disabled()) { ?>
			<comments><?php entry_permalink(); ?>#comments</comments>
<?php
			} ?>
			<dc:creator><?php entry_author(); ?></dc:creator>
			<?php rss_tags(); ?>
			<description><![CDATA[<?php entry_snippet(); ?>]]></description>
			<?php run_actions('rss_'.$hook_name); ?>
		</item>
<?php
		}
	} ?>
	</channel>
</rss>
<?php
}
?>
