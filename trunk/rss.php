<?php

require_once('bj_config.php');

$posts = get_posts('');
$lastpost = get_posts('limit=1');

header('Content-type: text/xml; charset=iso-8859-1', true);

echo '<?xml version="1.0" encoding="iso-8859-1"?'.'>'; ?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	<?php run_actions('rss_namespace'); ?>
>
	<channel>
		<title><?php siteinfo('sitename'); ?></title>
		<link><?php siteinfo('siteurl'); ?></link>
		<description><?php printf('The main feed for %1$s.',get_siteinfo('sitename')); ?></description>
		<pubDate><?php post_date('D, d M Y H:i:s +0000',$lastpost[0]['posted']); ?></pubDate>
		<generator>http://ceeps.blogs.tbomonline.com/section/blackjack/</generator>
		<language><?php siteinfo('rss_language'); ?></language>
		<?php run_actions('rss_head'); ?>
		<?php if($posts) { foreach($posts as $post) { start_post(); ?>

		<item>
			<title><?php echo_title(); ?></title>
			<link><?php echo_permalink(); ?></link>
			<pubDate><?php post_date('D, d M Y H:i:s +0000'); ?></pubDate>
			<comments><?php echo_permalink(); ?>#comments</comments>
			<dc:creator><?php post_author(); ?></dc:creator>
			<?php rss_tags(); ?>
			<description><![CDATA[<?php echo_content(); ?>]]></description>
		</item>
		<?php } } ?>
	</channel>
</rss>