<?php

require_once BJPATH . 'core/functions-general.php';

function load_plugins() {
	global $bj;
	$bj->vars->plugins = array();
	$ffl = FileFolderList(BJPATH . 'content/plugins');
	if($ffl['files']) {
		foreach($ffl['files'] as $file) {
			$extension = explode('.',$file);
			$info = parse_file_info($file,array('Disable'));
			if(end($extension) == 'php' and empty($info['Disable'])) {
				$bj->vars->plugins[basename($file,'.php')] = true;
				require_once($file);
			}
		}
	}
}

function plugin_is_enabled($name) {
	global $bj;
	if($bj->vars->plugins[$name])
		return true;
	return false;
}

function add_action($hook, $functionname) {
	global $bj;
	$bj->vars->actions[$hook][] = array('function_name'=>$functionname);
	return true;
}

function remove_action($hook, $functionname) {
	global $bj;
	if(isset($bj->vars->actions[$hook])) {
		foreach($bj->vars->actions[$hook] as $null=>$info) {
			if($info['function_name'] == $functionname) {
				unset($bj->vars->actions[$hook][$null]);
				return true;
			}
		}
	}
	return false;
}

function run_actions($hookname, $tofilter='') {
	global $bj;
	$bj->vars->actions_run[$hookname] = true;
	$todo = $bj->vars->actions[$hookname];
	if ($todo)
		foreach ($todo as $null => $info)
			$tofilter = call_user_func_array($info['function_name'], array($tofilter, $hookname));
	return $tofilter;
}

function actions_were_run($hookname) {
	global $bj;
	return (isset($bj->vars->actions_run[$hookname])) ? true : false;
}

?>
