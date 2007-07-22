<?php


#Function: validate_session(Reverse)
#Description: Checks if the user is logged into the admin panel.
#			  If not, you get redirected to the login screen.
#			  Reverse is for the login page.
function validate_session($reverse=false) {
	global $bj,$admin_thisfile;
	if($reverse == true) {
		if($bj->user) {
			@header("Location: ".get_siteinfo('adminurl')."index.php");
		}
	}
	else {
		if(is_null($bj->user)) {
			@header("Location: ".get_siteinfo('adminurl')."login.php?redir=".urlencode($admin_thisfile.(isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : ""));
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
		foreach($rows as $text) { $i++; ?>
					<li class="<?php tablealt($i); ?>"><?php echo $text; ?></li>
<?php
		}
	}
}

#Function: do_editorform(Entry)
#Destiption: Processes the editor form. The post field defaults to
#			 an array with just the keys for a new post. 
function do_editorform($entry = array('ID'=>'0','title'=>'','shortname'=>'','content'=>'','content'=>'','author'=>'','posted'=>'','ptype'=>'public','section'=>'','comment_count'=>0,'comments_open'=>1,'tags'=>'','meta'=>'a:0:{}','meta'=>'a:0:{}')) {
	global $bj;
	ob_start();
	if($entry['posted'] == '')
		$entry['posted'] = date('Y-m-d H:i:s'); #Fix for new posts. ?>
			<form name="edit-<?php echo $entry['ID']; ?>" action="" method="post" class="editform">
				<div class="column width25">
					<div class="c-ontent">
						<p class="label"><label for="comments_open"><?php _e('Discussion'); ?></label></p>
						<p><label for="comments_open"><input type="checkbox" name="comments_open" id="comments_open"<?php bj_checked($entry['comments_open'],1); ?> /> <?php _e('Enable Comments'); ?></label></p>
						
						<p class="label"><label for="section"><?php _e('Section'); ?></label></p>
						<p>
							<select name="section" id="section">
<?php
						$sections = return_sections();
						foreach($sections as $section) { ?>
								<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],$entry['section']); ?>><?php echo $section['title']; ?></option>
<?php
						} ?>
							</select>
						</p>
						
						<p class="label"><?php _e('Tags'); ?></p>
						<p class="editortaginsert"></p>
						<ul class="taglist">
							<li id="headings"></li>
					<?php
						$tags = return_all_tags();
						$return_tags = get_entry_tags();
						if($tags) {
							foreach($tags as $tag) { ?>
							<li><label for="tag-<?php echo $tag['ID']; ?>"><input type="checkbox" id="tag-<?php echo $tag['ID']; ?>" name="tags[<?php echo $tag['ID']; ?>]"<?php
								foreach($return_tags as $chtag) {
									bj_checked($tag['ID'],$chtag['ID']);
								} ?> /> <?php echo $tag['name']; ?></label></li>
<?php
							}
						} ?>
						</ul>
						
						<p class="label"><label for="public_entry"><?php _e('Entry Status'); ?></label></p>
						<p>
							<label for="public_entry"><input type="radio" name="ptype" value="public" id="public_entry"<?php bj_checked($entry['ptype'],'public'); ?> /> <?php _e('Public'); ?></label><br />
							<label for="draft_entry"><input type="radio" name="ptype" value="draft" id="draft_entry"<?php bj_checked($entry['ptype'],'draft'); ?> /> <?php _e('Draft'); ?></label>
						</p>
<?php
					run_actions('end_editor_sidebar'); ?>
					</div>
				</div>
				<div class="column width75">
					<div class="c-ontent">
						<p class="label"><label for="title"><?php _e('Title'); ?></label></p>
						<p><input type="text" name="title" id="title" value="<?php echo formatted_for_editing($entry['title']); ?>" class="width100" /></p>
						<p class="label"><label for="stamp_month"><?php _e('Date and Time'); ?></label></p>
						<p>
							<select name="stamp_month" id="stamp_month">
<?php
					for($int=1;$int <= 12;$int++) {
						$num = (strlen($int) == 1) ? '0'.$int : $int; ?>
								<option value="<?php echo $num; ?>"<?php bj_selected(get_entry_date('m',$entry['posted']),$num); ?>><?php echo $bj->locale->month[$num]; ?></option>
<?php
					} ?>
							</select> 
							<input type="text" name="stamp_date" maxlength="2" size="2" value="<?php entry_date('d',$entry['posted']); ?>" class="aligncenter" /> 
							<input type="text" name="stamp_year" maxlength="4" size="4" value="<?php entry_date('Y',$entry['posted']); ?>" class="aligncenter" /> <?php _e('on'); ?> 
							<input type="text" name="stamp_hour" maxlength="2" size="2" value="<?php entry_date('H',$entry['posted']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_min" maxlength="2" size="2" value="<?php entry_date('i',$entry['posted']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_sec" maxlength="2" size="2" value="<?php entry_date('s',$entry['posted']); ?>" class="aligncenter" />
						</p>
						<div id="editor">
							<p class="label"><label for="textarea"><?php _e('Content'); ?></label></p>
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
						<p class="submit">
<?php
					if($entry['ID'] != "0") { ?>
							<input type="submit" name="save-del" value="<?php _e('Delete'); ?>" class="button_deleteme" />
<?php
					} ?>
							<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
							<input type="submit" name="save" value="<?php _e('Save'); ?>" />
						</p>
					</div>
				</div>
				<div class="clear"></div>
<?php
	if(is_array(load_option('custom_fields'))) { ?>
				<div id="custom_fields">
					<h3><?php _e('Custom Fields'); ?></h3>
					<table class="edit">
						<tr id="metaheadings" class="ths">
							<th class="width40"><?php _e('Key'); ?></th>
							<th class="width60"><?php _e('Value'); ?></th>
						</tr>
<?php
		$i = 1;
		$meta = unserialize($entry['meta']); #With convenience in mind. :D
		foreach(load_option('custom_fields') as $key) { ?>
						<tr id="entry-meta-<?php echo bj_shortname($key); ?>" class="<?php tablealt($i); ?>">
							<td class="aligncenter">
								<?php echo $key; ?>
							</td>
							<td>
								<textarea name="meta[<?php echo bj_shortname($key); ?>]" class="metatextarea width100"><?php echo formatted_for_editing($meta[bj_shortname($key)]); ?></textarea>
							</td>
						</tr>
<?php
			$i++;
		} ?>
					</table>
				</div>
<?php
	} ?>
			</form>
<?php
	$content = run_actions('entry_editor',ob_get_contents());
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
									<a href="#" class="button ol newline" rel="<ol>
	<li>$</li>
</ol>"><span><?php _e('Ol'); ?></span></a>
									<a href="#" class="button ul newline" rel="<ul>
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
	$text = run_actions('bj_editbar',ob_get_contents());
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
			<form name="edit-<?php echo $tag['ID']; ?>" action="" method="post" id="tagform" class="editform">
				<p class="label"><label for="name"><?php _e('Tag Name'); ?></label></p>
				<p><input type="text" name="name" id="name" value="<?php echo formatted_for_editing($tag['name']); ?>" class="text100" /></p>
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
				<p class="submit">
					<input type="submit" name="save" value="<?php _e('Save'); ?>" />
				</p>
			</form>
<?php
			if($inline) { ?>
			<script type="text/javascript">
			$('tagform').onsubmit = function(){
				blackJack.ajaxAdd('tags.php?req=ajaxadd',this,'headings');
				return false;
			}
			</script>
<?php
			}
	$content = run_actions('tag_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: section_editor(Section)
#Description: Editor form for sections. It works like the
#			  post form where it defaults with an empty post.
function section_editor($section = array('ID'=>'0','title'=>'','shortname'=>'','handler'=>'','stylesheet'=>'style.css','hidden'=>'','page_order'=>'0','last_updated'=>'')) {
	global $inline;
	ob_start(); ?>
			<form name="edit-<?php echo $section['ID']; ?>" action="" method="post" id="sectionform" class="editform">
				<div class="c-ontent">
					<p class="label"><label for="title"><?php _e('Title'); ?></label></p>
					<p><input type="text" name="title" id="title" value="<?php echo formatted_for_editing($section['title']); ?>" class="text100" /></p>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p class="label"><label for="page_order"><?php _e('Menu Order'); ?></label></p>
						<p><input type="text" name="page_order" id="page_order" value="<?php echo $section['page_order']; ?>" class="text100" /></p>
					</div>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p class="label"><label for="hidden"><?php _e('Hidden?'); ?></label></p>
						<p>
							<select name="hidden" id="hidden" class="width100">
								<option value="no"<?php bj_selected($section['hidden'],'no'); ?>><?php _e('No'); ?></option>
								<option value="yes"<?php bj_selected($section['hidden'],'yes'); ?>><?php _e('Yes'); ?></option>
							</select>
						</p>
					</div>
				</div>
				<div class="column width50">
					<div class="c-ontent">
						<p class="label"><label for="handler" title="<?php _e('A handler is a special PHP file within the current skin that can be used to parse PHP before the section template file is loaded.'); ?>" class="cowtip"><?php _e('Handler'); ?></label></p>
						<p>
<?php
						$skin_files = FileFolderList(BJTEMPLATE); ?>
							<select name="handler" id="handler" class="width100">
								<option value=""<?php bj_selected($section['handler'],''); ?>><?php _e('None'); ?></option>
<?php
						foreach($skin_files['files'] as $num=>$file) {
							$data = parse_file_info($file,array('Handler'));
							if(!empty($data['Handler']) and end(explode('.',$file)) == 'php') { ?>
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
						<p class="label"><label for="stylesheet"><?php _e('Stylesheet'); ?></label></p>
						<p>
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
				<p class="submit">
<?php
					if($section['ID'] != "0") { ?>
					<input type="submit" name="save-del" value="<?php _e('Delete'); ?>" class="button_deleteme" />
<?php
					} ?>
					<input type="submit" name="save" value="<?php _e('Save'); ?>" />
				</p>
			</form>
<?php
	if($inline) { ?>
			<script type="text/javascript">
			$('sectionform').onsubmit = function(){
				blackJack.ajaxAdd('sections.php?req=ajaxadd',this,'headings');
				return false;
			}
			</script>
<?php
	}
	$content = run_actions('section_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

#Function: comment_editor(ID)
#Description: Comment editor. Uses the Editbar.
function comment_editor($comment=array('ID'=>0,'post_ID'=>0,'author_name'=>'','author_email'=>'','author_url'=>'','author_IP'=>'','posted_on'=>'','status'=>'','user_id'=>0,'content'=>'')) {
	global $bj;
	ob_start(); ?>
			<form name="edit-<?php echo $comment['ID']; ?>" action="" method="post" id="commentform" class="editform">
				<div class="column width33">
					<div class="c-ontent">
						<p class="label"><label for="author_name"><?php _e('Name:'); ?></label></p>
						<p><input type="text" name="author_name" value="<?php echo formatted_for_editing($comment['author_name']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="column width33">
					<div class="c-ontent">
						<p class="label"><label for="author_email"><?php _e('Email:'); ?></label></p>
						<p><input type="text" name="author_email" value="<?php echo formatted_for_editing($comment['author_email']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="column width33">
					<div class="c-ontent">
						<p class="label"><label for="author_url"><?php _e('URL:'); ?></label></p>
						<p><input type="text" name="author_url" value="<?php echo formatted_for_editing($comment['author_url']); ?>" class="text100" /></p>
					</div>
				</div>
				<div class="clear"></div>
				<div class="column width25">
					<div class="c-ontent">
						<p class="label"><?php _e('Status'); ?></p>
						<p>
							<input type="radio" name="status" id="status_normal" value="normal"<?php bj_checked($comment['status'],'normal'); ?> /> <label for="status_normal"><?php _e('Normal'); ?></label><br />
							<input type="radio" name="status" id="status_hidden" value="hidden"<?php bj_checked($comment['status'],'hidden'); ?> /> <label for="status_hidden"><?php _e('Hidden'); ?></label>
						</p>

						<p class="label"><?php _e('Edit Timestamp'); ?></p>
						<p>
							<select name="stamp_month" id="stamp_month">
<?php
					for($int=1;$int <= 12;$int++) {
						$num = (strlen($int) == 1) ? '0'.$int : $int; ?>
								<option value="<?php echo $num; ?>"<?php bj_selected(get_entry_date('m',$comment['posted_on']),$num); ?>><?php echo $bj->locale->month[$num]; ?></option>
<?php
					} ?>
							</select> 
							<input type="text" name="stamp_date" maxlength="2" size="2" value="<?php entry_date('d',$comment['posted_on']); ?>" class="aligncenter" /> 
							<input type="text" name="stamp_year" maxlength="4" size="4" value="<?php entry_date('Y',$comment['posted_on']); ?>" class="aligncenter" /> <?php _e('on'); ?> 
							<input type="text" name="stamp_hour" maxlength="2" size="2" value="<?php entry_date('H',$comment['posted_on']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_min" maxlength="2" size="2" value="<?php entry_date('i',$comment['posted_on']); ?>" class="aligncenter" /> :
							<input type="text" name="stamp_sec" maxlength="2" size="2" value="<?php entry_date('s',$comment['posted_on']); ?>" class="aligncenter" />
						</p>
					</div>
				</div>
				<div class="column width75">
					<div class="c-ontent">
						<div id="editor">
<?php bj_editbar(); ?>
							<textarea name="content" id="textarea"><?php echo formatted_for_editing($comment['content']); ?></textarea>
						</div>
						<p class="submit">
							<input type="hidden" name="edit-comment-send" value="yes" />
							<input type="hidden" name="edit-comment-id" value="<?php echo $comment['ID']; ?>" />
							<input type="submit" class="submit" value="<?php _e('Save'); ?>" />
						</p>
					</div>
				</div>
			</form>
<?php
	$content = run_actions('comment_editor',ob_get_contents());
	ob_end_clean();
	echo $content;
}

function add_bj_notice($message) {
	global $bj;
	$bj->vars->admin_notices[] = $message;
}

function do_bj_notices() {
	global $bj; ?>
				<div id="messages">
<?php
	foreach($bj->vars->admin_notices as $message)
		echo '<p>'.$message.'</p>'; ?>
				</div>
<?php
}

function run_bj_forms() {
	global $bj;
	run_actions('bj_forms');
	if(isset($_POST['new-entry-send'])) {
		$comments_open = (empty($_POST['comments_open'])) ? 0 : 1;
		$saved = bj_new_entry(array('title'=>bj_clean_string($_POST['title']),'shortname'=>bj_shortname(bj_clean_string($_POST['title'])),'content'=>bj_clean_string($_POST['content'],get_html_entry()),'ptype'=>bj_clean_string($_POST['ptype']),'author'=>$bj->user['ID'],'comments_open'=>$comments_open,'tags'=>$_POST['tags'],'section'=>intval($_POST['section']),'posted'=>intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']),'meta'=>$_POST['meta']));
		if(isset($_POST['save']))
			@header("Location: ".get_siteinfo('adminurl')."entries.php");
		elseif(isset($_POST['save-cont']))
			@header("Location: ".get_siteinfo('adminurl')."entries.php?req=edit&id=".$saved['ID']);
		die();
	}
	elseif(isset($_POST['edit-entry-send'])) {
		if(isset($_POST['save-del'])) {
			bj_delete_entry(intval($_POST['edit-entry-id']));
			@header("Location: ".get_siteinfo('adminurl')."entries.php?deleted=true");
		}
		else {
			$comments_open = (empty($_POST['comments_open'])) ? 0 : 1;
			bj_edit_entry(intval($_POST['edit-entry-id']),array('title'=>bj_clean_string($_POST['title']),'shortname'=>bj_shortname(bj_clean_string($_POST['title'])),'content'=>bj_clean_string($_POST['content'],get_html_entry()),'ptype'=>bj_clean_string($_POST['ptype']),'comments_open'=>$comments_open,'tags'=>$_POST['tags'],'section'=>bj_clean_string($_POST['section']),'posted'=>intval($_POST['stamp_year']).'-'.intval($_POST['stamp_month']).'-'.intval($_POST['stamp_date']).' '.intval($_POST['stamp_hour']).':'.intval($_POST['stamp_min']).':'.intval($_POST['stamp_sec']),'meta'=>$_POST['meta']));
			if(isset($_POST['save']))
				@header("Location: ".get_siteinfo('adminurl')."entries.php");
			elseif(isset($_POST['save-cont']))
				@header("Location: ".get_siteinfo('adminurl')."entries.php?req=edit&id=".intval($_POST['edit-entry-id']));
		}
		die();
	}
	elseif(isset($_POST['new-section-send']) and $_GET['req'] != 'ajaxadd') {
		bj_new_section();
		@header("Location: ".get_siteinfo('adminurl')."sections.php");
		die();
	}
	elseif(isset($_POST['edit-section-send'])) {
		if(isset($_POST['save-del'])) {
			bj_delete_section(intval($_POST['edit-section-id']));
			@header("Location: ".get_siteinfo('adminurl')."sections.php?deleted=true");
		}
		else {
			bj_edit_section($_POST['edit-section-id']);
			@header("Location: ".get_siteinfo('adminurl')."sections.php");
		}
		die();
	}
	elseif(isset($_POST['new-tag-send']) and $_GET['req'] != 'ajaxadd') {
		bj_new_tag();
		@header("Location: ".get_siteinfo('adminurl')."tags.php");
		die();
	}
	elseif(isset($_POST['edit-comment-send'])) {
		bj_edit_comment($_POST['edit-comment-id']);
		@header("Location: ".get_siteinfo('adminurl')."comments.php");
		die();
	}
	elseif(isset($_POST['edit-tag-send'])) {
		bj_edit_tag($_POST['edit-tag-id']);
		@header("Location: ".get_siteinfo('adminurl')."tags.php");
		die();
	}
	elseif(isset($_POST['skin-edit-send']))
		bj_save_skin();
	elseif(isset($_POST['skin-newfile-send']))
		bj_skin_newfile();
	elseif(isset($_POST['new-user-send']))
		bj_new_user(array('user_login'=>bj_clean_string($_POST['user_login']),'user_nicename'=>bj_shortname(bj_clean_string($_POST['user_login'])),'display_name'=>bj_clean_string($_POST['user_login']),'user_email'=>bj_clean_string($_POST['user_email']),'user_url'=>bj_clean_string($_POST['user_url']),'user_pass'=>md5(bj_clean_string($_POST['user_pass'])),'bj_group'=>intval($_POST['bj_group'])));
}

#Function: tablealt()
#Description: Just provides a class="alt" for the table row.
function tablealt($i) {
	echo ($i%2 == 0) ? " alt" : "";
}

#Function: get_admin_header()
#Description: Outputs the admin header.
function get_admin_header() {
	global $parent_file,$admin_thisfile,$bj,$menu,$submenu; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php _e('Blackjack Admin Panel'); ?></title>
		<link rel="stylesheet" href="blackjack.css" type="text/css" media="screen" />
		<script language="javascript" type="text/javascript" src="../jscripts/mootools.js"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/posteditor.js.php"></script>
		<script language="javascript" type="text/javascript" src="../jscripts/blackjack.js.php"></script>
<?php run_actions('admin_header'); ?>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="start">
					<h1><a href="<?php siteinfo('siteurl'); ?>"><?php siteinfo('sitename'); ?></a></h1>
					<ul id="userinfo">
						<li class="profile"><a href="profile.php"><?php echo $bj->user['display_name']; ?></a></li>
						<li class="logout"><a href="login.php?req=logout"><?php _e('Logout'); ?></a></li>
					</ul>
				</div>
				<hr />
				<ul id="menu">
<?php
	foreach($menu as $file=>$item) {
		if($bj->user['bj_group'] >= $item[1]) { ?>
					<li class="<?php echo bj_shortname($item[0]); echo ($parent_file == $file) ? " active" : ""; ?>"><a href="<?php echo $file; ?>"><?php echo $item[0]; ?></a></li>
<?php
		}
	} ?>
				</ul>
				<div class="clear"></div>
<?php
	if(isset($submenu[$parent_file]) and count($submenu[$parent_file]) > 1) { ?>
				<ul id="submenu">
<?php
		foreach($submenu[$parent_file] as $subitem) {
			if($bj->user['bj_group'] >= $subitem[1]) {
				$str='';
				if(($admin_thisfile.'?plug='.$subitem[3] == basename($_SERVER['REQUEST_URI']) and $subitem[3] != '')
				or ($admin_thisfile == $subitem[2] and $subitem[3] == '' and $_GET['plug'] == ''))
					$str = " class=\"active\""; ?>
					<li<?php echo $str; ?>><a href="<?php echo $subitem[2];echo (!empty($subitem[3])) ? '?plug='.$subitem[3] : ''; ?>"><?php echo $subitem[0]; ?></a></li>
<?php
			}
		} ?>
				</ul>
<?php
	} ?>
			</div>
			<hr />
			<div id="content">
<?php
	if(load_option('db_version') != $bj->vars->db_version)
		add_bj_notice(sprintf(_r('The database structure in Blackjack has changed in your new version. Please <a href="%1$s">upgrade</a> to get your site running.'),'upgrade.php'));
	run_actions('admin_content_start');
}

#Function: get_admin_footer()
#Description: Outputs the admin footer.
function get_admin_footer() {
	global $bj; ?>
			</div>
<?php run_actions('admin_footer'); ?>
			<hr />
			<div id="footer">
				<p><?php printf(_r('Blackjack %1$s'),$bj->vars->version); ?></p>
				<p><?php printf(_r('%1$s Queries'),$bj->db->querycount()); ?></p>
			</div>
		</div>
	</body>
</html>
<?php
} ?>
