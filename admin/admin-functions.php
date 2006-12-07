<?php


#Function: validate_session(Reverse)
#Description: Checks if the user is logged into the admin panel.
#			  If not, you get redirected to the login screen.
#			  Reverse is for the login page.
function validate_session($reverse=false) {
	global $user,$admin_thisfile;
	if($reverse == true) {
		if(isset($user->ID)) {
			@header("Location: ".load_option('siteurl')."admin/index.php");
		}
	}
	else {
		if(!isset($user->ID)) {
			@header("Location: ".load_option('siteurl')."admin/login.php?redir=".urlencode($admin_thisfile.(isset($_SERVER['QUERY_STRING'])) ? urlencode($admin_thisfile."?".$_SERVER['QUERY_STRING']) : ""));
			die();
		}
	}
}

#Function: fancy_altrows(Content[, Args])
#Description: Allows for stylized lists, because we're too lazy to
#			  just do them by hand.
function fancy_altrows($rows) {
	if(is_array($rows)) {
		$i = 0;
		foreach($rows as $text=>$url) { ?>
					<li<?php tablealt($i); ?>><?php if($url != "") { ?><a href="<?php echo $url; ?>"><?php echo $text; ?></a><?php }else{ echo $text; } ?></li>
<?php		$i++;
		}
	}
}

#Function: do_editorform(Post)
#Destiption: Processes the editor form. The post field defaults to
#			 an array with just the keys for a new post. 
function do_editorform($post = array('ID'=>'0','title'=>'','shortname'=>'','content'=>'','content'=>'','author'=>'','posted'=>'','ptype'=>'','section'=>'','comment_count'=>'','tags'=>'')) {
	ob_start(); ?>
			<form name="edit-<?php echo $post['ID']; ?>" action="" method="post">
				<div class="column width25">
					<h3><label for="section"><?php _e('Section'); ?></label></h3>
					<p>
						<select name="section" id="section" class="width100">
					<?php
						$sections = return_sections();
						foreach($sections as $section) { ?>
							<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],$post['section']); ?>><?php echo $section['title']; ?></option>
<?php
						} ?>
						</select>
					</p>
					<h3><?php _e('Tags'); ?></h3>
					<ul class="altrows taglist">

					<?php
						$ourtags = return_all_tags();
						$return_tags = return_tags();
						$ti = 0;
						foreach($ourtags as $tag) { ?>
						<li<?php tablealt($ti); ?>><label for="tag-<?php echo $tag['ID']; ?>"><input type="checkbox" id="tag-<?php echo $tag['ID']; ?>" name="tags[<?php echo $tag['ID']; ?>]"<?php
							if(is_array($return_tags)) { foreach($return_tags as $chtag) {
								bj_checked($tag['ID'],$chtag['ID']);
							} } ?> /> <?php echo $tag['name']; ?></label></li>
<?php					$ti++; }
						unset($ti); ?>
					</ul>
					<h3><label for="shortname"><var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><?php _e('Shortname'); ?></var></label></h3>
					<p><input type="text" name="shortname" id="shortname" value="<?php echo $post['shortname']; ?>" class="width90" /></p>
					<h3><?php _e('Post Type'); ?></h3>
					<p><label for="public_post"><input type="radio" name="ptype" value="public" id="public_post"<?php bj_checked(get_post_type(),'public'); ?> /> <?php _e('Public'); ?></label><br />
					<label for="draft_post"><input type="radio" name="ptype" value="draft" id="draft_post"<?php bj_checked(get_post_type(),'draft'); ?> /> <?php _e('Draft'); ?></label></p>
					<h3><?php _e('Post Author'); ?></h3>
					<p><select name="author" class="width100">
<?php
					$authors = get_users('gop=>=&group=2');
					foreach($authors as $author) { start_post(); ?>
						<option value="<?php echo $author['display_name']; ?>"<?php bj_selected(get_post_author(),$author['display_name']); ?>><?php echo $author['display_name']; ?></option>
<?php
					}
					unset($i); ?>
					</select></p>
<?php
					if($post['posted'] != "") { ?>
					<h3><?php _e('Edit Timestamp'); ?></h3>
					<p><?php printf(_r('Timestamp is %1$s on %2$s.'),get_post_date('F jS, Y'),get_post_date('H:i:s')); ?></p>
					<p><input type="checkbox" id="editstamp" name="editstamp" value="yes"> <label for="editstamp"><?php _e('Edit timestamp?'); ?></label><br />
					<select name="stamp_month">
						<option value="01"<?php bj_selected(get_post_date('m'),'01'); ?>><?php _e('January'); ?></option>
						<option value="02"<?php bj_selected(get_post_date('m'),'02'); ?>><?php _e('February'); ?></option>
						<option value="03"<?php bj_selected(get_post_date('m'),'03'); ?>><?php _e('March'); ?></option>
						<option value="04"<?php bj_selected(get_post_date('m'),'04'); ?>><?php _e('April'); ?></option>
						<option value="05"<?php bj_selected(get_post_date('m'),'05'); ?>><?php _e('May'); ?></option>
						<option value="06"<?php bj_selected(get_post_date('m'),'06'); ?>><?php _e('June'); ?></option>
						<option value="07"<?php bj_selected(get_post_date('m'),'07'); ?>><?php _e('July'); ?></option>
						<option value="08"<?php bj_selected(get_post_date('m'),'08'); ?>><?php _e('August'); ?></option>
						<option value="09"<?php bj_selected(get_post_date('m'),'09'); ?>><?php _e('September'); ?></option>
						<option value="10"<?php bj_selected(get_post_date('m'),'10'); ?>><?php _e('October'); ?></option>
						<option value="11"<?php bj_selected(get_post_date('m'),'11'); ?>><?php _e('November'); ?></option>
						<option value="12"<?php bj_selected(get_post_date('m'),'12'); ?>><?php _e('December'); ?></option>
					</select> 
					<input type="text" name="stamp_date" maxlength="2" size="2" value="<?php post_date('d'); ?>" class="aligncenter" /> 
					<input type="text" name="stamp_year" maxlength="4" size="4" value="<?php post_date('Y'); ?>" class="aligncenter" /> <?php _e('on'); ?> 
					<input type="text" name="stamp_hour" maxlength="2" size="2" value="<?php post_date('H'); ?>" class="aligncenter" /> :
					<input type="text" name="stamp_min" maxlength="2" size="2" value="<?php post_date('i'); ?>" class="aligncenter" /> :
					<input type="text" name="stamp_sec" maxlength="2" size="2" value="<?php post_date('s'); ?>" class="aligncenter" />
						</p>
<?php
					}
					run_actions('end_editor_sidebar'); ?>
				</div>
				<div class="column width75">
					<p><label for="title"><?php _e('Title'); ?></label><br class="blank" /><input type="text" name="title" id="title" value="<?php echo $post['title']; ?>" class="width100 largeinput" /></p>
					<div id="editor">
						<div id="editbar">
							<div class="alignleft buttons">
								<a href="#" onclick="simpleTag('textarea','strong','<?php _e('Bold Text Here'); ?>',false);return false;" class="strong"><span><?php _e('Strong'); ?></span></a>
								<a href="#" onclick="simpleTag('textarea','em','<?php _e('Italic Text Here'); ?>',false);return false;" class="em" /><span><?php _e('Em'); ?></span></a>
								<a href="#" onclick="linkTag('textarea','<?php _e('Link Text Here'); ?>','<?php _e('Link URL Here'); ?>');return false;" class="link" /><span><?php _e('Link'); ?></span></a>
								<a href="#" onclick="imgTag('textarea','<?php _e('Image\'s Alternate Text'); ?>','<?php _e('Imager URL Here'); ?>');return false;" class="img" /><span><?php _e('Img'); ?></span></a>
								<a href="#" onclick="simpleTag('textarea','blockquote','<?php _e('Quoted Text Here'); ?>',true);return false;" class="bq" /><span><?php _e('BQ'); ?></span></a>
								<a href="#" onclick="listTag('textarea','ul','<?php _e('List Item'); ?>');return false;" class="ul" /><span><?php _e('Ol'); ?></span></a>
								<a href="#" onclick="listTag('textarea','ol','<?php _e('List Item'); ?>');return false;" class="ol" /><span><?php _e('Ul'); ?></span></a>
								<div class="clear"></div>
							</div>
							<div class="alignright updown">
								<a href="#" onclick="moreheight('textarea',50);return false;" class="more" /><span><?php _e('More'); ?></span></a>
								<a href="#" onclick="lessheight('textarea',50);return false;" class="less" /><span><?php _e('Less'); ?></span></a>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						<div id="nonJSedit"></div>
						<textarea name="content" id="textarea"><?php echo formatted_for_editing($post['content']); ?></textarea>
					</div>
<?php
					run_actions('end_editor_main');
					if($post['ID'] == "0") { ?>
					<input type="hidden" name="new-post-send" value="yes" />
<?php
					} else { ?>
					<input type="hidden" name="edit-post-send" value="yes" />
					<input type="hidden" name="edit-post-id" value="<?php echo $post['ID']; ?>" />
<?php
					} ?>
					<div class="submit">
<?php
					if($post['ID'] != "0") { ?>
						<input type="submit" name="save-del" value="<?php _e('Delete Post'); ?>" class="delete" />
<?php
					} ?>
						<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="save" value="<?php _e('Save Post'); ?>" style="font-weight:bold;" />
					</div>
				</div>
			</form>
<?php
	$content = run_filters('post_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: tag_editor(Tag)
#Description: Edit for tags. Is meant to display for either
#			  the main page, where the tag is added, or the
#			  edit page.
function tag_editor($tag = array('ID'=>'0','name'=>'','shortname'=>'','posts_num'=>'')) {
	
}

#Function: section_editor(Section)
#Description: Editor form for sections. It works like the
#			  post form where it defaults with an empty post.
function section_editor($section = array('ID'=>'0','title'=>'','shortname'=>'','handler'=>'','tags'=>'','hidden'=>'','page_order'=>'','last_updated'=>'')) {
	ob_start(); ?>
			<form name="edit-<?php echo $section['ID']; ?>" action="" method="post">
				<div class="column width75">
					<p>
						<label for="title"><?php _e('Title'); ?></label><br />
						<input type="text" name="title" id="title" value="<?php echo $section['title']; ?>" class="width90 largeinput" />
					</p>
				</div>
				<div class="column width25">
					<p>
						<label for="page_order"><?php _e('Menu Order'); ?></label><br />
						<input type="text" name="page_order" id="page_order" value="<?php echo $section['page_order']; ?>" class="width90" />
					</p>
				</div>
				<div class="clear"></div>
				<div class="column width33">
					<h3><label for="shortname"><var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><?php _e('Shortname'); ?></var></label></h3>
					<p><input type="text" name="shortname" id="shortname" value="<?php echo $section['shortname']; ?>" class="width90" /></p>
				</div>
				<div class="column width33">
					<h3><label for="hidden"><?php _e('Hidden?'); ?></label></h3>
					<p>
						<select name="hidden" id="hidden" class="width90">
							<option value="yes"<?php bj_selected($section['hidden'],'yes'); ?>><?php _e('Yes'); ?></option>
							<option value="no"<?php bj_selected($section['hidden'],'no'); ?>><?php _e('No'); ?></option>
						</select>
					</p>
				</div>
				<div class="column width33">
					<h3><label for="handler"><var title="<?php _e('A section handler is a special PHP file within the current theme that can be used to parse PHP or deviate from the default section template. Useful for advanced skinners.'); ?>"><?php _e('Section Handler'); ?></var></label></h3>
					<p>
<?php
						$skin_files = FileFolderList(BJTEMPLATE); ?>
						<select name="handler" id="handler" class="width90">
							<option value=""<?php bj_selected($section['handler'],''); ?>><?php _e('Default Handler'); ?></option>
<?php
						foreach($skin_files['files'] as $num=>$file) {
							$data = parse_file_info($file,array('Template Name'));
							if($data['Template Name'] != '') { ?>
							<option value="<?php echo basename($file); ?>"<?php bj_selected($section['handler'],basename($file)); ?>><?php echo $data['Template Name']; ?></option>
<?php
							}
						} ?>
						</select>
					</p>
				</div>
				<div class="clear"></div>
<?php
					if($section['ID'] == "0") { ?>
				<input type="hidden" name="new-section-send" value="yes" />
<?php
					} else { ?>
				<input type="hidden" name="edit-section-send" value="yes" />
				<input type="hidden" name="edit-section-id" value="<?php echo $section['ID']; ?>" />
<?php
					} ?>
				<div class="submit">
<?php
					if($section['ID'] != "0") { ?>
					<input type="submit" name="save-del" value="<?php _e('Delete Section'); ?>" class="delete" />
<?php
					} ?>
					<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
					<input type="submit" name="save" value="<?php _e('Save'); ?>" style="font-weight:bold;" />
				</div>
			</form>
<?php
	$content = run_filters('section_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: run_bj_forms()
#Description: Carries out the actions for already-existing forms.
function run_bj_forms() {
	if(isset($_POST['new-post-send'])) {
		bj_new_post();
	}
	elseif(isset($_POST['edit-post-send'])) {
		bj_edit_post($_POST['edit-post-id']);
	}
	elseif(isset($_POST['new-section-send'])) {
		bj_new_section();
	}
	elseif(isset($_POST['edit-section-send'])) {
		bj_edit_section($_POST['edit-section-id']);
	}
}

#Function: tablealt()
#Description: Just provides a class="alt" for the table row.
function tablealt($i) {
	echo ($i%2 == 0) ? "" : " class=\"alt\"";
}

#Function: get_admin_header()
#Description: Outputs the admin header.
function get_admin_header() {
	global $user,$parent_file,$admin_thisfile,$bj_db,$menu,$submenu; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _e('Blackjack Admin Panel'); ?></title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="../jscripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/blackjack.js"></script>
<?php run_actions('admin_header'); ?>
	</head>
	<body>
		<div id="top">
			<div class="alignleft userinfo">
				<h1><a href="<?php siteinfo('siteurl'); ?>"><?php siteinfo('sitename'); ?></a></h1>
				<p><?php printf(_r('Logged in as %1$s'),$user->display_name); ?></p>
			</div>
			<p class="alignright lightpart">
				<a href="profile.php" class="profile"><?php _e('Profile'); ?></a>
				<a href="login.php?req=logout" class="logout"><?php _e('Logout'); ?></a>
			</p>
			<div class="clear"></div>
		</div>
		<ul id="menu">
<?php
foreach($menu as $item) {
	if($user->user_group >= $item[1]) { ?>
			<li<?php echo ($parent_file == $item[2]) ? " class=\"active\"" : ""; ?>><a href="<?php echo $item[2]; ?>"><?php echo $item[0]; ?></a></li>
<?php
	}
}
?>
		</ul>
		<p class="blank"><?php _e('Submenu:'); ?></p>
		<ul id="submenu">
<?php
if(isset($submenu[$parent_file])) {
	foreach($submenu[$parent_file] as $subitem) {
		if($user->user_group >= $subitem[1]) {
			$str='';
			if($admin_thisfile.'?plug='.$subitem[3] == basename($_SERVER['REQUEST_URI']) and $subitem[3] != '') {
				$str = " class=\"active\"";
			}
			elseif($admin_thisfile == $subitem[2] and $subitem[3] == '' and $_GET['plug'] == '') {
				$str = " class=\"active\"";
			} ?>
			<li<?php echo $str; ?>><a href="<?php echo $subitem[2];echo ($subitem[3] != '') ? '?plug='.$subitem[3] : ''; ?>"><?php echo $subitem[0]; ?></a></li>
<?php
		}
	}
}
?>
		</ul>
		<hr />
		<div id="wrapper">
<?php
}

#Function: get_admin_footer()
#Description: Outputs the admin footer.
function get_admin_footer() {
	global $bj_db,$bj_version; ?>
		</div>
<?php run_actions('admin_footer'); ?>
		<hr />
		<div id="footer">
			<p><?php printf(_r('Blackjack %1$s - %2$s Queries'),$bj_version,$bj_db->querycount()); ?></p>
		</div>
	</body>
</html>
<?php
}
?>