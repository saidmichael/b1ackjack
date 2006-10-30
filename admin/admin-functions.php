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
function do_editorform($post = array('ID'=>'0','title'=>'','shortname'=>'','content'=>'','content'=>'','author'=>'','posted'=>'','ptype'=>'','parent'=>'','comment_count'=>'','tags'=>'')) { ?>
			<form name="edit-<?php echo $post['ID']; ?>" action="" method="post">
				<div class="column width25">
					<h2><?php _e('Tags'); ?></h2>
					<ul class="altrows taglist">
					<?php
						$ourtags = return_all_tags();
						$return_tags = return_tags();
						$ti = 0;
						foreach($ourtags as $tag) { ?>
						<li<?php tablealt($ti); ?>><label for="category-<?php echo $tag['ID']; ?>"><input type="checkbox" id="category-<?php echo $tag['ID']; ?>" name="tags[<?php echo $tag['ID']; ?>]"<?php
							if(is_array($return_tags)) { foreach($return_tags as $chtag) {
								bj_checked($tag['ID'],$chtag['ID']);
							} } ?> /> <?php echo $tag['name']; ?></label></li>
<?php					$ti++; }
						unset($ti); ?>
					</ul>
					<h2><label for="shortname"><var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><?php _e('Shortname'); ?></var></label></h2>
					<p><input type="text" name="shortname" id="shortname" value="<?php echo $post['shortname']; ?>" class="width100" /></p>
					<h2><?php _e('Post Type'); ?></h2>
					<p><label for="public_post"><input type="radio" name="ptype" value="public" id="public_post"<?php bj_checked(get_post_type(),'public'); ?> /> <?php _e('Public'); ?></label><br />
					<label for="draft_post"><input type="radio" name="ptype" value="draft" id="draft_post"<?php bj_checked(get_post_type(),'draft'); ?> /> <?php _e('Draft'); ?></label></p>
					<h2><?php _e('Post Author'); ?></h2>
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
					<h2><?php _e('Edit Timestamp'); ?></h2>
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
					<p><label for="title"><?php _e('Title'); ?></label><input type="text" name="title" id="title" value="<?php echo bj_clean_string($post['title']); ?>" class="width100 largeinput" /></p>
					<textarea name="content" id="textarea"><?php echo $post['content']; ?></textarea>
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
						<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="save" value="<?php _e('Save Post'); ?>" style="font-weight:bold;" />
					</div>
				</div>
			</form>
<?php
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
}

#Function: tablealt()
#Description: Just provides a class="alt" for the table row.
function tablealt($i) {
	echo ($i%2 == 0) ? "" : " class=\"alt\"";
}

#Function: get_admin_header()
#Description: Outputs the admin header.
function get_admin_header() {
	global $user,$parent_file,$admin_thisfile,$bj_db; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _e('Blackjack Admin Panel'); ?></title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" />
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="../jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/mootools.js"></script>
		<script language="javascript" type="text/javascript">
		tinyMCE.init({
			theme : "advanced",
			mode : "exact",
			elements : "textarea",
			extended_valid_elements : "a[href|target|name]",
			debug : false,
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resize_horizontal : false,
			theme_advanced_resizing : true
		});
		window.onload = function() {
			new Tips($S('var'), {transitionStart:Fx.Transitions.sineIn,transitionEnd:Fx.Transitions.sineOut});
		};
		</script>
		<!-- /tinyMCE -->
<?php run_actions('admin_header'); ?>
	</head>
	<body>
		<div id="top">
			<div class="alignleft userinfo">
				<strong><?php echo load_option('sitename'); ?></strong> - <?php printf(_r('Logged in as <a href="profile.php">%1$s</a>'),$user->display_name); ?>
			</div>
			<div class="alignright lightpart">
				<a href="profile.php" class="profile"><?php _e('Profile'); ?></a>
				<a href="login.php?req=logout" class="logout"><?php _e('Logout'); ?></a>
			</div>
			<div class="c"></div>
		</div>
<?php
	require("menu.php");
}

#Function: get_admin_footer()
#Description: Outputs the admin footer.
function get_admin_footer() {
	global $bj_db,$bj_version; ?>
<?php run_actions('admin_footer'); ?>
		<div id="footer">
			<p><?php printf(_r('Blackjack %1$s - %2$s Queries'),$bj_version,$bj_db->querycount()); ?></p>
		</div>
	</body>
</html>
<?php
}
?>