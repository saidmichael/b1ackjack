<?php


#Function: validate_session(Reverse)
#Description: Checks if the user is logged into the admin panel.
#			  If not, you get redirected to the login screen.
#			  Reverse is for the login page.
function validate_session($reverse=false) {
	global $user,$admin_thisfile;
	if($reverse == true) {
		if(isset($user['ID'])) {
			@header("Location: ".load_option('siteurl')."admin/index.php");
		}
	}
	else {
		if(!isset($user['ID'])) {
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

#Function: do_editorform(Entry)
#Destiption: Processes the editor form. The post field defaults to
#			 an array with just the keys for a new post. 
function do_editorform($entry = array('ID'=>'0','title'=>'','shortname'=>'','content'=>'','content'=>'','author'=>'','posted'=>'','ptype'=>'','section'=>'','comment_count'=>0,'comments_open'=>1,'tags'=>'','meta'=>'a:0:{}')) {
	ob_start(); ?>
			<form name="edit-<?php echo $entry['ID']; ?>" action="" method="post">
				<div class="column width25">
					<div class="c-ontent">
						<div class="tblock">
							<h3><?php _e('Discussion'); ?></h3>
							<p><label for="comments_open"><input type="checkbox" name="comments_open" id="comments_open"<?php bj_checked($entry['comments_open'],1); ?> /> <?php _e('Enable Comments'); ?></label></p>
						</div>
						<div class="tblock">
							<h3><label for="section"><?php _e('Section'); ?></label></h3>
							<p>
								<select name="section" id="section" class="width100">
					<?php
						$sections = return_sections();
						foreach($sections as $section) { ?>
									<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],$entry['section']); ?>><?php echo $section['title']; ?></option>
<?php
						} ?>
								</select>
							</p>
						</div>
						<div class="tblock">
							<h3><?php _e('Tags'); ?></h3>
							<ul class="altrows taglist" id="tags">
								<li id="headings" class="editortaginsert alt"></li>
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
						</div>
						<div class="tblock">
							<h3><?php _e('Post Type'); ?></h3>
							<p><label for="public_post"><input type="radio" name="ptype" value="public" id="public_post"<?php bj_checked(get_entry_type(),'public'); ?> /> <?php _e('Public'); ?></label><br />
							<label for="draft_post"><input type="radio" name="ptype" value="draft" id="draft_post"<?php bj_checked(get_entry_type(),'draft'); ?> /> <?php _e('Draft'); ?></label></p>
						</div>
						<div class="tblock">
							<h3><label for="author"><?php _e('Author'); ?></label></h3>
							<p>
								<select name="author" id="author" class="width100">
<?php
					$authors = get_users('equ=>=&group=2');
					foreach($authors as $author) { ?>
									<option value="<?php echo $author['display_name']; ?>"<?php bj_selected(get_entry_author(),$author['display_name']); ?>><?php echo $author['display_name']; ?></option>
<?php
					}
					unset($i); ?>
								</select>
							</p>
						</div>
<?php
					if($entry['posted'] != "") { ?>
						<div class="tblock">
							<h3><?php _e('Edit Timestamp'); ?></h3>
							<p><input type="checkbox" id="editstamp" name="editstamp" value="yes" /> <label for="editstamp"><?php _e('Edit timestamp?'); ?></label><br />
							<select name="stamp_month">
								<option value="01"<?php bj_selected(get_entry_date('m'),'01'); ?>><?php _e('January'); ?></option>
								<option value="02"<?php bj_selected(get_entry_date('m'),'02'); ?>><?php _e('February'); ?></option>
								<option value="03"<?php bj_selected(get_entry_date('m'),'03'); ?>><?php _e('March'); ?></option>
								<option value="04"<?php bj_selected(get_entry_date('m'),'04'); ?>><?php _e('April'); ?></option>
								<option value="05"<?php bj_selected(get_entry_date('m'),'05'); ?>><?php _e('May'); ?></option>
								<option value="06"<?php bj_selected(get_entry_date('m'),'06'); ?>><?php _e('June'); ?></option>
								<option value="07"<?php bj_selected(get_entry_date('m'),'07'); ?>><?php _e('July'); ?></option>
								<option value="08"<?php bj_selected(get_entry_date('m'),'08'); ?>><?php _e('August'); ?></option>
								<option value="09"<?php bj_selected(get_entry_date('m'),'09'); ?>><?php _e('September'); ?></option>
								<option value="10"<?php bj_selected(get_entry_date('m'),'10'); ?>><?php _e('October'); ?></option>
								<option value="11"<?php bj_selected(get_entry_date('m'),'11'); ?>><?php _e('November'); ?></option>
								<option value="12"<?php bj_selected(get_entry_date('m'),'12'); ?>><?php _e('December'); ?></option>
							</select> 
							<input type="text" name="stamp_date" maxlength="2" size="2" value="<?php entry_date('d'); ?>" class="aligncenter" /> 
							<input type="text" name="stamp_year" maxlength="4" size="4" value="<?php entry_date('Y'); ?>" class="aligncenter" /> <?php _e('on'); ?> 
							<input type="text" name="stamp_hour" maxlength="2" size="2" value="<?php entry_date('H'); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_min" maxlength="2" size="2" value="<?php entry_date('i'); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_sec" maxlength="2" size="2" value="<?php entry_date('s'); ?>" class="aligncenter" />
							</p>
						</div>
<?php
					}
					run_actions('end_editor_sidebar'); ?>
					</div>
				</div>
				<div class="column width75">
					<div class="c-ontent">
						<div class="tblock">
							<p><label for="title"><?php _e('Title'); ?></label><br class="blank" /><input type="text" name="title" id="title" value="<?php echo formatted_for_editing($entry['title']); ?>" class="width100 largeinput" /></p>
						</div>
						<div class="tblock">
							<p><?php siteinfo('siteurl'); ?>entry/<input type="text" name="shortname" id="shortname" value="<?php echo $entry['shortname']; ?>" class="width25" /> <var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><span class="blank"><?php _e('?'); ?></span></var></p>
						</div>
						<div class="tblock" id="editor">
<?php bj_editbar(); ?>
							<textarea name="content" id="textarea"><?php echo formatted_for_editing($entry['content']); ?></textarea>
						</div>
<?php
					run_actions('end_editor_main');
					if($entry['ID'] == "0") { ?>
						<input type="hidden" name="new-entry-send" value="yes" />
<?php
					} else { ?>
						<input type="hidden" name="edit-entry-send" value="yes" />
						<input type="hidden" name="edit-entry-id" value="<?php echo $entry['ID']; ?>" />
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
				</div>
			</form>
<?php
	$content = run_filters('entry_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: bj_editbar()
#Description: Blackjack editbar.
function bj_editbar() {
	ob_start(); ?>
							<div id="editbar">
								<div class="alignleft buttons">
									<a href="#" class="button strong" rel="<strong>$</strong>"><span><?php _e('Strong'); ?></span></a>
									<a href="#" class="button em" rel="<em>$</em>"><span><?php _e('Em'); ?></span></a>
									<a href="#" class="button img" rel="<img src='$' alt='' />"><span><?php _e('Img'); ?></span></a>
									<a href="#" class="button bq newline" rel="<blockquote>$</blockquote>"><span><?php _e('BQ'); ?></span></a>
									<a href="#" class="button ul newline" rel="<ol>
	<li>$</li>
</ol>"><span><?php _e('Ol'); ?></span></a>
									<a href="#" class="button ol newline" rel="<ul>
	<li>$</li>
</ul>"><span><?php _e('Ul'); ?></span></a>
									<a href="#" class="moresep hr" rel="$

"><span><?php _e('More'); ?></span></a>
									<div class="clear"></div>
								</div>
								<div class="alignright updown">
									<a href="#" class="more" /><span><?php _e('More'); ?></span></a>
									<a href="#" class="less" /><span><?php _e('Less'); ?></span></a>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div id="nonJSedit"></div>
<?php
	$text = run_filters('bj_editbar',ob_get_contents());
	ob_end_clean();
	echo $text;
}

#Function: tag_editor(Tag)
#Description: Edit for tags. Is meant to display for either
#			  the main page, where the tag is added, or the
#			  edit page.
function tag_editor($tag = array('ID'=>'0','name'=>'','shortname'=>'','posts_num'=>'')) {
	ob_start();
	global $inline; ?>
			<form name="edit-<?php echo $tag['ID']; ?>" action="" method="post" id="tagform">
				<p><label for="name"><?php _e('Tag Name'); ?></label><br />
				<input type="text" name="name" id="name" value="<?php echo formatted_for_editing($tag['name']); ?>" class="largeinput text100" /></p>
				<p><label for="shortname"><var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><?php _e('Shortname'); ?></var></label><br />
				<input type="text" name="shortname" id="shortname" value="<?php echo $tag['shortname']; ?>" class="text100" /></p>
<?php
				if($tag['ID'] != '0') { ?>
				<input type="hidden" name="edit-tag-send" value="yes" />
				<input type="hidden" name="edit-tag-id" value="<?php echo $tag['ID']; ?>" />
<?php
				}
				else { ?>
				<input type="hidden" name="new-tag-send" value="yes" />
<?php
				} ?>
				<div class="submit">
					<input type="submit" name="save" value="<?php _e('Save'); ?>" />
				</div>
			</form>
<?php
			if($inline) { ?>
			<script type="text/javascript">
			$('tagform').onsubmit = function(){
				blackJack.ajaxAdd('tags.php?req=ajaxadd',this);
				return false;
			}
			</script>
<?php
			}
	$content = run_filters('tag_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: section_editor(Section)
#Description: Editor form for sections. It works like the
#			  post form where it defaults with an empty post.
function section_editor($section = array('ID'=>'0','title'=>'','shortname'=>'','handler'=>'','stylesheet'=>'style.css','hidden'=>'','page_order'=>'0','last_updated'=>'')) {
	global $inline;
	ob_start(); ?>
			<form name="edit-<?php echo $section['ID']; ?>" action="" method="post" id="sectionform">
				<p>
					<label for="title"><?php _e('Title'); ?></label><br />
					<input type="text" name="title" id="title" value="<?php echo formatted_for_editing($section['title']); ?>" class="text100 largeinput" />
				</p>
				<p>
					<label for="shortname"><var title="<?php _e('This is the friendly URL name. Leave this blank and it\'ll be taken directly from the title.'); ?>"><?php _e('Shortname'); ?></var></label><br />
					<input type="text" name="shortname" id="shortname" value="<?php echo $section['shortname']; ?>" class="text100" />
				</p>
				<div class="column width50">
					<div class="c-ontent">
						<p>
							<label for="page_order"><?php _e('Menu Order'); ?></label><br />
							<input type="text" name="page_order" id="page_order" value="<?php echo $section['page_order']; ?>" class="text100" />
						</p>
					</div>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p>
							<label for="hidden"><?php _e('Hidden?'); ?></label><br />
							<select name="hidden" id="hidden" class="width100">
								<option value="no"<?php bj_selected($section['hidden'],'no'); ?>><?php _e('No'); ?></option>
								<option value="yes"<?php bj_selected($section['hidden'],'yes'); ?>><?php _e('Yes'); ?></option>
							</select>
						</p>
					</div>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p>
							<label for="handler"><var title="<?php _e('A handler is a special PHP file within the current theme that can be used to parse PHP before the section template is run. Useful for advanced users.'); ?>"><?php _e('Handler'); ?></var></label><br />
<?php
						$skin_files = FileFolderList(BJTEMPLATE); ?>
							<select name="handler" id="handler" class="width100">
								<option value=""<?php bj_selected($section['handler'],''); ?>><?php _e('None'); ?></option>
<?php
						foreach($skin_files['files'] as $num=>$file) {
							$data = parse_file_info($file,array('Handler'));
							if($data['Handler'] != '') { ?>
								<option value="<?php echo basename($file); ?>"<?php bj_selected($section['handler'],basename($file)); ?>><?php echo $data['Handler']; ?></option>
<?php
							}
						} ?>
							</select>
						</p>
					</div>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p>
							<label for="stylesheet"><?php _e('Stylesheet'); ?></label><br />
							<select name="stylesheet" id="stylesheet" class="width100">
<?php
						$css_files = glob(BJTEMPLATE.'/*.css');
						if(is_array($css_files)) {
							foreach($css_files as $num=>$file) { ?>
								<option value="<?php echo basename($file); ?>"<?php bj_selected($section['stylesheet'],basename($file)); ?>><?php echo basename($file); ?></option>
<?php
							}
						} ?>
							</select>
						</p>
					</div>
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
					<input type="submit" name="save" value="<?php _e('Save'); ?>" />
				</div>
			</form>
<?php
	if($inline) { ?>
			<script type="text/javascript">
			$('sectionform').onsubmit = function(){
				blackJack.ajaxAdd('sections.php?req=ajaxadd',this);
				return false;
			}
			</script>
<?php
	}
	$content = run_filters('section_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: comment_editor(ID)
#Description: Comment editor. Uses the Editbar.
function comment_editor($comment=array('ID'=>0,'post_ID'=>0,'author_name'=>'','author_email'=>'','author_url'=>'','author_IP'=>'','posted_on'=>'','status'=>'','user_id'=>0,'content'=>'')) {
	ob_start(); ?>
			<form name="edit-<?php echo $comment['ID']; ?>" action="" method="post" id="commentform">
				<div class="column width33">
					<div class="c-ontent">
						<p><label for="author_name"><?php _e('Name:'); ?></label><br />
						<input type="text" name="author_name" value="<?php echo formatted_for_editing($comment['author_name']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="column width33">
					<div class="c-ontent">
						<p><label for="author_email"><?php _e('Email:'); ?></label><br />
						<input type="text" name="author_email" value="<?php echo formatted_for_editing($comment['author_email']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="column width33">
					<div class="c-ontent">
						<p><label for="author_url"><?php _e('URL:'); ?></label><br />
						<input type="text" name="author_url" value="<?php echo formatted_for_editing($comment['author_url']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="clear"></div>
				<div class="column width25">
					<div class="c-ontent">
						<div class="tblock">
							<h3><?php _e('Status'); ?></h3>
							<p><input type="radio" name="status" id="status_normal" value="normal"<?php bj_checked($comment['status'],'normal'); ?> /> <label for="status_normal"><?php _e('Normal'); ?></label><br />
							<input type="radio" name="status" id="status_hidden" value="hidden"<?php bj_checked($comment['status'],'hidden'); ?> /> <label for="status_hidden"><?php _e('Hidden'); ?></label><br />
							<input type="radio" name="status" id="status_spam" value="spam"<?php bj_checked($comment['status'],'spam'); ?> /> <label for="status_spam"><?php _e('Spam'); ?></label></p>
						</div>
						<div class="tblock">
							<h3><?php _e('Edit Timestamp'); ?></h3>
							<p><input type="checkbox" id="editstamp" name="editstamp" value="yes" /> <label for="editstamp"><?php _e('Edit timestamp?'); ?></label><br />
							<select name="stamp_month">
								<option value="01"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'01'); ?>><?php _e('January'); ?></option>
								<option value="02"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'02'); ?>><?php _e('February'); ?></option>
								<option value="03"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'03'); ?>><?php _e('March'); ?></option>
								<option value="04"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'04'); ?>><?php _e('April'); ?></option>
								<option value="05"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'05'); ?>><?php _e('May'); ?></option>
								<option value="06"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'06'); ?>><?php _e('June'); ?></option>
								<option value="07"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'07'); ?>><?php _e('July'); ?></option>
								<option value="08"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'08'); ?>><?php _e('August'); ?></option>
								<option value="09"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'09'); ?>><?php _e('September'); ?></option>
								<option value="10"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'10'); ?>><?php _e('October'); ?></option>
								<option value="11"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'11'); ?>><?php _e('November'); ?></option>
								<option value="12"<?php bj_selected(get_entry_date('m',$comment['posted_on']),'12'); ?>><?php _e('December'); ?></option>
							</select> 
							<input type="text" name="stamp_date" maxlength="2" size="2" value="<?php entry_date('d',$comment['posted_on']); ?>" class="aligncenter" /> 
							<input type="text" name="stamp_year" maxlength="4" size="4" value="<?php entry_date('Y',$comment['posted_on']); ?>" class="aligncenter" /> <?php _e('on'); ?> 
							<input type="text" name="stamp_hour" maxlength="2" size="2" value="<?php entry_date('H',$comment['posted_on']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_min" maxlength="2" size="2" value="<?php entry_date('i',$comment['posted_on']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_sec" maxlength="2" size="2" value="<?php entry_date('s',$comment['posted_on']); ?>" class="aligncenter" />
							</p>
						</div>
					</div>
				</div>
				<div class="column width75">
					<div class="c-ontent">
						<div class="tblock" id="editor">
<?php bj_editbar(); ?>
							<textarea name="content" id="textarea"><?php echo formatted_for_editing($comment['content']); ?></textarea>
						</div>
						<div class="submit">
							<input type="hidden" name="edit-comment-send" value="yes" />
							<input type="hidden" name="edit-comment-id" value="<?php echo $comment['ID']; ?>" />
							<input type="submit" class="submit" value="<?php _e('Save'); ?>" />
						</div>
					</div>
				</div>
			</form>
<?php
	$content = run_filters('comment_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: run_bj_forms()
#Description: Carries out the actions for already-existing forms.
function run_bj_forms() {
	if(isset($_POST['new-entry-send'])) {
		bj_new_entry();
	}
	elseif(isset($_POST['edit-entry-send'])) {
		bj_edit_entry($_POST['edit-entry-id']);
	}
	elseif(isset($_POST['new-section-send'])) {
		bj_new_section();
	}
	elseif(isset($_POST['edit-section-send'])) {
		bj_edit_section($_POST['edit-section-id']);
	}
	elseif(isset($_POST['new-tag-send'])) {
		bj_new_tag();
	}
	elseif(isset($_POST['edit-comment-send'])) {
		bj_edit_comment($_POST['edit-comment-id']);
	}
	elseif(isset($_POST['edit-tag-send'])) {
		bj_edit_tag($_POST['edit-tag-id']);
	}
	elseif(isset($_POST['skin-edit-send'])) {
		bj_save_skin();
	}
	elseif(isset($_POST['skin-newfile-send'])) {
		bj_skin_newfile();
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
		<script language="javascript" type="text/javascript" src="../jscripts/blackjack.js.php"></script>
<?php run_actions('admin_header'); ?>
	</head>
	<body>
		<div id="top">
			<div class="alignleft sitename">
				<h1><a href="<?php siteinfo('siteurl'); ?>"><?php siteinfo('sitename'); ?></a></h1>
			</div>
			<p class="alignright userinfo">
				<a href="profile.php" class="profile"><?php echo $user['display_name']; ?></a>
				<a href="login.php?req=logout" class="logout"><?php _e('Logout'); ?></a>
			</p>
			<div class="clear"></div>
		</div>
		<ul id="menu">
<?php
foreach($menu as $item) {
	if($user['user_group'] >= $item[1]) { ?>
			<li<?php echo ($parent_file == $item[2]) ? " class=\"active\"" : ""; ?>><a href="<?php echo $item[2]; ?>"><?php echo $item[0]; ?></a></li>
<?php
	}
}
?>
		</ul>
		<ul id="submenu">
<?php
if(isset($submenu[$parent_file])) {
	if(count($submenu[$parent_file]) > 1) {
		foreach($submenu[$parent_file] as $subitem) {
			if($user['user_group'] >= $subitem[1]) {
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
