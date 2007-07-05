<?php

require "bj_init.php";
$bj->vars->load = explode('/',bj_clean_string($_GET['load']));
$bj->vars->offset = ($bj->vars->load[2] == 'page') ? intval($bj->vars->load[3]) : 0;
if($bj->vars->load[2] == 'rss') {
	require('rss.php');
	die();
}
switch($bj->vars->load[0]) {
	case 'section' :
		$section = $bj->db->get_item("SELECT * FROM `".$bj->db->sections."` WHERE `shortname` = '".$bj->vars->load[1]."' LIMIT 1");
		if(!$section)
			load_404_instead();
		$bj->query->setLimit($bj->vars->offset,load_option('entries_per_page'));
		$bj->query->setSection($section['ID']);
		$entries = $bj->query->fetch();
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '')
			include(BJTEMPLATE .'/'.$section['handler']);
		if(file_exists(BJTEMPLATE .'/section.php'))
			include(BJTEMPLATE . '/section.php');
		else
			include(BJTEMPLATE . '/index.php');
		break;
	case 'archive' :
		$section = $bj->db->get_item("SELECT * FROM `".$bj->db->sections."` WHERE `shortname` = '".$bj->vars->load[1]."' LIMIT 1");
		if(!$section)
			load_404_instead();
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '')
			include(BJTEMPLATE .'/'.$section['handler']);
		if(file_exists(BJTEMPLATE .'/archive.php'))
			include(BJTEMPLATE . '/archive.php');
		else
			include(BJTEMPLATE . '/index.php');
		break;
	case 'entry' :
		$bj->query->setLimit(0,1);
		$bj->query->setShortname($bj->vars->load[1]);
		$entries = $bj->query->fetch();
		if(!$entries)
			load_404_instead();
		if(file_exists(BJTEMPLATE . '/entry.php'))
			include(BJTEMPLATE . '/entry.php');
		else
			include(BJTEMPLATE . '/index.php');
		break;
	case 'tag' :
		$tag = $bj->db->get_item("SELECT * FROM `".$bj->db->tags."` WHERE `shortname` = '".$bj->vars->load[1]."' LIMIT 1");
		if(!$tag)
			load_404_instead();
		$bj->query->setLimit($bj->vars->offset,load_option('entries_per_page'));
		$bj->query->setTags($tag['ID']);
		$entries = $bj->query->fetch();
		if(file_exists(BJTEMPLATE . '/tag-'.$tag['ID'].'.php'))
			include(BJTEMPLATE . '/tag-'.$tag['ID'].'.php');
		elseif(file_exists(BJTEMPLATE . '/tag.php'))
			include(BJTEMPLATE . '/tag.php');
		else
			include(BJTEMPLATE . '/index.php');
		break;
	case 'author' :
		$author = $bj->cache->get_user(intval($bj->vars->load[1]));
		if(!$author or $author['bj_group'] < 2)
			load_404_instead();
		if(file_exists(BJTEMPLATE .'/author.php'))
			include(BJTEMPLATE . '/author.php');
		else
			_e('Hey, you! Tell the webmaster to make an author.php in their skin so you can view this page!');
		break;
	case 'search' :
		$bj->query->setLimit($bj->vars->offset,load_option('entries_per_page'));
		$bj->query->setPtype('public');
		$bj->query->setSearch($bj->vars->load[1]);
		$entries = $bj->query->fetch();
		if(file_exists(BJTEMPLATE .'/search.php'))
			include(BJTEMPLATE .'/search.php');
		else
			include(BJTEMPLATE . '/index.php');
		break;
	default :
		$section = $bj->cache->get_section(load_option('default_section'));
		if(!$section or $bj->vars->load[0] != '')
			load_404_instead();
		$bj->query->setLimit($bj->vars->offset,load_option('entries_per_page'));
		$bj->query->setSection($section['ID']);
		$entries = $bj->query->fetch();
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '')
			include(BJTEMPLATE .'/'.$section['handler']);
		include(BJTEMPLATE . '/index.php');
}

?>
