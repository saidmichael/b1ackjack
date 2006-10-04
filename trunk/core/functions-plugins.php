<?php
#Borrowed from AJ-Fork

function FileFolderList($path, $depth = 0, $current = '', $level=0) {
	if ($level==0 && !@file_exists($path))
		return false;
	if (is_dir($path)) {
		$handle = @opendir($path);
		if ($depth == 0 || $level < $depth)
			while($filename = @readdir($handle))
				if ($filename != '.' && $filename != '..')
					$current = @FileFolderList($path.'/'.$filename, $depth, $current, $level+1);
		@closedir($handle);
		$current['folder'][] = $path.'/'.$filename;
	} else
		if (is_file($path))
			$current['file'][] = $path;
	return $current;
}

function available_plugins() {
	$ffl = FileFolderList(BJPATH . 'content/plugins',1);
	$plugins = $ffl['file'];
	if (!empty($plugins))
		foreach ($plugins as $null => $pluginfile) {
			if (stristr($pluginfile, ".htaccess")) { continue; }
			
			$plugin_data = read_file_contents($pluginfile);
			preg_match("{Plugin Name:(.*)}i", $plugin_data, $plugin['name']);
			preg_match("{Plugin URI:(.*)}i", $plugin_data, $plugin['uri']);
			preg_match("{Description:(.*)}i", $plugin_data, $plugin['description']);
			preg_match("{Author:(.*)}i", $plugin_data, $plugin['author']);
			preg_match("{Author URI:(.*)}i", $plugin_data, $plugin['author_uri']);
			preg_match("{Version:(.*)}i", $plugin_data, $plugin['version']);
			preg_match("{Application:(.*)}i", $plugin_data, $plugin['application']);

			$application = trim($plugin['application'][1]);
				
			// Skip plugins designed for other systems
			if ($application && $application == 'Blackjack')
				continue;

			$available_plugins[] = array(
				'name'			=> trim($plugin['name'][1]),
				'uri'			=> trim($plugin['uri'][1]),
				'description'	=> trim($plugin['description'][1]),
				'author'		=> trim($plugin['author'][1]),
				'author_uri'	=> trim($plugin['author_uri'][1]),
				'version'		=> trim($plugin['version'][1]),
				'application'	=> trim($plugin['application'][1]),
				'file'			=> basename($pluginfile),
			);
		}
	else
		$available_plugins = array();
	return $available_plugins;
}

function load_plugins() {
	foreach (unserialize(load_option('active_plugins')) as $plugin_filename => $active) {
		$path = BJPATH . 'content/plugins/'.$plugin_filename;
		if (is_file($path))
			include($path);
		else
			disable_plugin($plugin_filename);
	}
}

function return_plugins() {
	$plug_arr = array();
	foreach (unserialize(load_option('active_plugins')) as $plugin_filename => $active) {
		$path = BJPATH . 'content/plugins/'.$plugin_filename;
		if (is_file($path))
			$plug_arr[$plugin_filename] = true;
		else
			disable_plugin($plugin_filename);
	}
	return $plug_arr;
}

function is_plugin_enabled($name) {
	$plugins = unserialize(load_option('active_plugins'));
	if($plugins[$name]) {
		return true;
	}
}

function enable_plugin($name) {
	$plugins = unserialize(load_option('active_plugins'));
	$plugins[$name] = true;
	update_option('active_plugins',serialize($plugins));
}

function disable_plugin($name) {
	$plugins = unserialize(load_option('active_plugins'));
	unset($plugins[$name]);
	update_option('active_plugins',serialize($plugins));
}



function add_action($hook, $functionname) {
	global $actions;
	$actions[$hook][] = array(
		'name' => $functionname,
	);
}

function run_actions($hookname) {
	global $actions;
	$todo = $actions[$hookname];
	if (!$todo)
		return false;
	foreach ($todo as $null => $action)
		$buffer .= $action['name']($hookname);
	return $buffer;
}

function add_filter($hook, $functionname) {
	global $filters;
	$filters[$hook][] = array(
		'name' => $functionname,
	);
}

function run_filters($hookname, $tofilter) {
	global $filters;
	$todo = $filters[$hookname];
	if ($todo) {
		foreach ($todo as $null => $filter)
			$tofilter = $filter['name']($tofilter, $hookname);
	}
	return $tofilter;
}

?>