<?php
$parent_file = "skins.php";
require("admin-head.php");
if(we_can('edit_skins')) {
	switch($_GET['req']) {
		case 'ajaxadd' :
			$saved = bj_skin_newfile(true); ?>
								<a href="skin-editor.php?skin=<?php echo $saved['skin']; ?>&amp;file=<?php echo $saved['name']; ?>"><?php echo $saved['name']; ?></a><span id="latest_id" class="fader-<?php echo bj_shortname($saved['name']); ?>"></span>
<?php
			break;
		default :
			$bj_skins = get_usable_skins(true);
			if(isset($_GET['skin'])) {
				$skin_name = bj_clean_string($_GET['skin']);
			}
			else {
				$skin_name = current_skinname();
			}

			#Does our skin exist?
			if(!isset($bj_skins[$skin_name])) {
				$errors[] = _r('Skin not found.');
			}


			if(isset($_GET['file'])) {
				$skin_file = bj_clean_string($_GET['file']);
			}
			else {
				$skin_file = 'style.css';
			}

			#Does our file exist?
			if(!isset($bj_skins[$skin_name][$skin_file])) {
				$errors[] = _r('File not found.');
			}

			if(isset($errors[0])) {
				die(implode('<br />',$errors));
			}

			get_admin_header(); ?>
			<h2><?php _e('Switch Skin'); ?></h2>
			<form name="skinswitch" action="skin-editor.php" method="get">
				<p>
					<select name="skin">
<?php
			foreach($bj_skins as $dir=>$files) {
				$skin_data = parse_file_info(BJPATH.'content/skins/'.$dir.'/style.css',array('Skin Name')); ?>
						<option value="<?php echo $dir; ?>"<?php bj_selected($skin_name,$dir); ?>><?php echo $skin_data['Skin Name']; ?></option>
<?php
			} ?>
					</select>
					<input type="submit" class="inlinesubmit" value="<?php _e('Go'); ?>" />
				</p>
			</form>
			<h2><?php printf(_r('Editing File: <code>%1$s</code>'),$skin_file); ?></h2>
			<div class="column width25">
				<div class="c-ontent">
					<div class="tblock">
						<ul class="altrows">
							<li id="headings"></li>
<?php
			$i = 0;
			foreach($bj_skins[$skin_name] as $file=>$val) {
				if($file != 'screenshot.png') { ?>
							<li<?php tablealt($i); ?>><a href="skin-editor.php?skin=<?php echo $skin_name; ?>&amp;file=<?php echo $file; ?>"><?php echo $file; ?></a></li>
<?php
					$i++;	
				}
			} ?>
						</ul>
						<form name="newfile" id="newfile" action="" method="post">
							<p><input type="text" name="skin-newfile-file" value="<?php _e('New File Here'); ?>" class="text100 skin-newfile" /></p>
							<input type="hidden" name="skin-newfile-skin" value="<?php echo $skin_name; ?>" />
							<input type="hidden" name="skin-newfile-send" value="yes" />
						</form>
						<script type="text/javascript">
						$('newfile').onsubmit = function(){
							blackJack.ajaxAdd('skin-editor.php?req=ajaxadd',this);
							return false;
						}
						</script>
					</div>
				</div>
			</div>
			<div class="column width75">
				<div class="c-ontent">
					<form name="skinedit" action="" method="post">
<?php
			$filepath = BJPATH.'content/skins/'.$skin_name.'/'.$skin_file;
			$handle = fopen($filepath,'r');
			$text = fread($handle,filesize($filepath));
			fclose($handle); ?>
						<div class="tblock">
							<textarea name="content" class="width100" rows="22"><?php echo formatted_for_editing($text); ?></textarea>
						</div>
						<div class="submit">
							<input type="hidden" name="skin-edit-uniqid" value="<?php echo $skin_name; ?>" />
							<input type="hidden" name="skin-edit-file" value="<?php echo $skin_file; ?>" />
							<input type="hidden" name="skin-edit-send" value="yes" />
							<input type="submit" class="submit" value="<?php _e('Save'); ?>" />
						</div>
					</form>
				</div>
			</div>
<?php
			get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
} ?>
