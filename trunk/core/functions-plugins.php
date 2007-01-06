<?php
#Borrowed from AJ-Fork

function available_plugins() {
	$ffl = FileFolderList(BJPATH . 'content/plugins',1);
	$plugins = $ffl['files'];
	if (!empty($plugins))
		foreach ($plugins as $null => $pluginfile) {
			if (stristr($pluginfile, ".htaccess")) { continue; }
			
			$plugin_data = file_get_contents($pluginfile);
			$names = array(
				'Plugin Name',
				'Plugin URI',
				'Description',
				'Author',
				'Author URI',
				'Version',
				'Application'
			);
			$data = parse_file_info($plugin_data,$names);

			$available_plugins[] = array(
				'name'			=> $data['Plugin Name'],
				'uri'			=> $data['Plugin URI'],
				'description'	=> $data['Description'],
				'author'		=> $data['Author'],
				'author_uri'	=> $data['Author URI'],
				'version'		=> $data['Version'],
				'application'	=> $data['Application'],
				'file'			=> basename($pluginfile),
			);
		}
	else
		$available_plugins = array();
	return $available_plugins;
}

function load_plugins() {
	foreach (load_option('active_plugins') as $plugin_filename => $active) {
		$path = BJPATH . 'content/plugins/'.$plugin_filename;
		if (is_file($path))
			include($path);
		else
			disable_plugin($plugin_filename);
	}
}

function return_plugins() {
	$plug_arr = array();
	foreach (load_option('active_plugins') as $plugin_filename => $active) {
		$path = BJPATH . 'content/plugins/'.$plugin_filename;
		if (is_file($path))
			$plug_arr[$plugin_filename] = true;
		else
			disable_plugin($plugin_filename);
	}
	return $plug_arr;
}

function is_plugin_enabled($name) {
	$plugins = load_option('active_plugins');
	if($plugins[$name]) {
		return true;
	}
}

function enable_plugin($name) {
	$plugins = load_option('active_plugins');
	$plugins[$name] = true;
	update_option('active_plugins',serialize($plugins));
}

function disable_plugin($name) {
	$plugins = load_option('active_plugins');
	unset($plugins[$name]);
	update_option('active_plugins',serialize($plugins));
}



function add_action($hook, $functionname) {
	global $actions;
	$actions[$hook][$functionname] = true;
	return true;
}

function remove_action($hook, $functionname) {
	global $actions;
	if(isset($actions[$hook][$functionname])) {
		unset($actions[$hook][$functionname]);
		return true;
	}
	return false;
}

function run_actions($hookname) {
	global $actions;
	$todo = $actions[$hookname];
	if ($todo) {
		foreach ($todo as $action => $null)
			$buffer .= $action($tofilter, $hookname);
	}
	return $buffer;
}

function add_filter($hook, $functionname) {
	global $filters;
	$filters[$hook][$functionname] = true;
	return true;
}

function remove_filter($hook, $functionname) {
	global $filters;
	if(isset($filters[$hook][$functionname])) {
		unset($filters[$hook][$functionname]);
		return true;
	}
	return false;
}

function run_filters($hookname, $tofilter) {
	global $filters;
	$todo = $filters[$hookname];
	if ($todo) {
		foreach ($todo as $filter => $null)
			$tofilter = $filter($tofilter, $hookname);
	}
	return $tofilter;
}

?>
