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
function do_editorform($post = array('ID'=>'0','title'=>'','shortname'=>'','content'=>'','content'=>'','author'=>'','posted'=>'','type'=>'','parent'=>'','comment_count'=>'','tags'=>'')) { ?>
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
								if($tag['ID'] == $chtag['ID']) { ?> checked="checked"<?php }
							} } ?> /> <?php echo $tag['name']; ?></label></li>
<?php					$ti++; }
						unset($ti); ?>
					</ul>
					<h2><label for="shortname"><?php _e('Shortname'); ?></label></h2>
					<p><input type="text" name="shortname" id="shortname" value="<?php echo $post['shortname']; ?>" class="width100" /></p>
					<h2><?php _e('Post Type'); ?></h2>
<?php
					$public='';
					$private='';
					$inmod='';
					$checked = " checked=\"checked\"";
					switch($post['type']) {
						case "public" : $public = $checked; break;
						case "draft" : $private = $checked; break;
						case "mod" : $inmod = $checked;
					} ?>
					<p><label for="public_post"><input type="radio" name="ptype" value="public" id="public_post"<?php echo $public; ?> /> <?php _e('Public'); ?></label><br />
					<label for="draft_post"><input type="radio" name="ptype" value="draft" id="draft_post"<?php echo $private; ?> /> <?php _e('Draft'); ?></label><br />
					<label for="mod_post"><input type="radio" name="ptype" value="mod" id="mod_post"<?php echo $inmod; ?> /> <?php _e('In Moderation'); ?></label></p>
					<h2><?php _e('Post Author'); ?></h2>
					<select name="author" class="width100">
<?php
					$authors = get_users('gop=>=&group=2');
					foreach($authors as $author) { start_post(); ?>
						<option value="<?php echo $author['display_name']; ?>"<?php if($post['author'] == $author['display_name']) { ?> selected="selected"<?php } ?>><?php echo $author['display_name']; ?></option>
<?php
					}
					unset($i); ?>
					</select>
				</div>
				<div class="column width75">
					<p><label for="title"><?php _e('Title'); ?></label><input type="text" name="title" id="title" value="<?php echo $post['title']; ?>" class="width100 largeinput" /></p>
					<textarea name="content" id="textarea"><?php formatted_for_editing($post['content']); ?></textarea>
<?php
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
						switch($post['type']) {
							case "public" : ?>
						<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="save" value="<?php _e('Save Post'); ?>" style="font-weight:bold;" />
<?php
								break;
							case "mod" : ?>
						<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="save-me" value="<?php _e('Save Post'); ?>" /> 
						<input type="submit" name="save-approve" value="<?php _e('Approve'); ?>" style="font-weight:bold;" />
<?php
								break;
							default : ?>
						<input type="submit" name="save-cont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="save-me" value="<?php _e('Save Post'); ?>" /> 
						<input type="submit" name="save-publish" value="<?php _e('Publish'); ?>" style="font-weight:bold;" />
<?php
						} ?>
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

#Function tablealt()
#Description: Just provides a class="alt" for the table row.
function tablealt($i) {
	echo ($i%2 == 0) ? "" : " class=\"alt\"";
}

?>