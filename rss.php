<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

header('Content-type: text/xml; charset=iso-8859-1', true);

echo '<?xml version="1.0" encoding="iso-8859-1"?'.'>';

$extra_str = '';
$hook_name = '';
if(is_entry()) {
	$name = (!empty($name_vars[1])) ? bj_clean_string($name_vars[1]) : '';
	$entries = get_entries('limit=1&shortname='.$name);
	if(!$entries) {
		load_404_instead();
	}
	foreach($entries as $entry) {
		$extra_str .= '&postid='.$entry['ID'];
		$hook_name = 'entry_'.$entry['ID'];
	}
	$comments = get_comments('limit=10'.$extra_str); ?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	<?php run_actions('rss_namespace'); ?>
>
	<channel>
		<title><?php siteinfo('sitename'); ?></title>
		<link><?php siteinfo('siteurl'); ?></link>
		<description><?php printf('The main feed for %1$s.',get_siteinfo('sitename')); ?></description>
		<pubDate><?php entry_date('D, d M Y H:i:s +0000',$entries[0]['posted']); ?></pubDate>
		<generator>http://ceeps.blogs.tbomonline.com/section/blackjack/</generator>
		<language><?php siteinfo('rss_language'); ?></language>
		<?php run_actions('rss_head'); ?>
		<?php if($comments) { foreach($comments as $comment) { ?>

		<item>
			<title><?php comment_name(); ?></title>
			<link><?php echo get_siteinfo('siteurl').'entry/'.$name.'#comment-'.return_comment_ID(); ?></link>
			<pubDate><?php comment_date('D, d M Y H:i:s +0000'); ?></pubDate>
			<description><![CDATA[<?php comment_text(); ?>]]></description>
			<?php run_actions('rss_'.$hook_name); ?>
		</item>
		<?php } } ?>
	</channel>
</rss>
<?php
}
else {
	if(is_section() or is_front()) {
		$name = (!empty($name_vars[1])) ? bj_clean_string($name_vars[1]) : load_option('default_section');
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$name."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$extra_str .= '&section='.$section['ID'];
		$hook_name = 'section_'.$section['ID'];
	}
	elseif(is_tag()) {
		$name = (!empty($name_vars[1])) ? bj_clean_string($name_vars[1]) : '';
		$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$name."' LIMIT 1");
		if(!$tag) {
			load_404_instead();
		}
		$extra_str .= '&tag='.$tag['ID'];
		$hook_name = 'tag_'.$tag['ID'];
	}

	$entries = get_entries(''.$extra_str);
?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	<?php run_actions('rss_namespace'); ?>
>
	<channel>
		<title><?php siteinfo('sitename'); ?></title>
		<link><?php siteinfo('siteurl'); ?></link>
		<description><?php printf('The main feed for %1$s.',get_siteinfo('sitename')); ?></description>
		<pubDate><?php entry_date('D, d M Y H:i:s +0000',$entries[0]['posted']); ?></pubDate>
		<generator>http://ceeps.blogs.tbomonline.com/section/blackjack/</generator>
		<language><?php siteinfo('rss_language'); ?></language>
		<?php run_actions('rss_head'); ?>
		<?php if($entries) { foreach($entries as $entry) { start_entry(); ?>

		<item>
			<title><?php echo_title(); ?></title>
			<link><?php echo_permalink(); ?></link>
			<pubDate><?php entry_date('D, d M Y H:i:s +0000'); ?></pubDate>
			<comments><?php echo_permalink(); ?>#comments</comments>
			<dc:creator><?php entry_author(); ?></dc:creator>
			<?php rss_tags(); ?>
			<description><![CDATA[<?php echo_snippet(); ?>]]></description>
			<?php run_actions('rss_'.$hook_name); ?>
		</item>
		<?php } } ?>
	</channel>
</rss>
<?php
}
?>