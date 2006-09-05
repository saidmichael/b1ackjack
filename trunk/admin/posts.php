<?php
$parent_file = "posts.php";
require("admin-head.php");
switch($_GET['req']) {
	case "edit" :
		if(isset($_GET['id'])) {
			$posts = get_posts('id='.intval($_GET['id']).'&limit=1');
			foreach($posts as $post) { start_post(); ?>
		<div id="wrapper">
			<h1><?php printf(_r('Editing %1$s'),$post['title']); ?></h1>
			<form name="edit-<?php echo $post['ID']; ?>" action="" method="post">
				<div class="column width25">
					<h2><?php _e('Tags'); ?></h2>
					<ul class="altrows taglist">
					<?php
						$ourtags = return_all_tags();
						$ti = 0;
						foreach($ourtags as $tag) { ?>
						<li<?php tablealt($ti); ?>><label for="category-<?php echo $tag['ID']; ?>"><input type="checkbox" id="category-<?php echo $tag['ID']; ?>" name="categories[<?php echo $tag['ID']; ?>]"<?php
							if(is_array(return_tags())) { foreach(return_tags() as $chtag) {
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
					<div class="submit">
<?php
						switch($post['type']) {
							case "public" : ?>
						<input type="submit" name="savecont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="saveme" value="<?php _e('Save Post'); ?>" style="font-weight:bold;" />
<?php
								break;
							case "mod" : ?>
						<input type="submit" name="savecont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="saveme" value="<?php _e('Save Post'); ?>" /> 
						<input type="submit" name="approve" value="<?php _e('Approve'); ?>" style="font-weight:bold;" />
<?php
								break;
							default : ?>
						<input type="submit" name="savecont" value="<?php _e('Save And Continue Editing'); ?>" /> 
						<input type="submit" name="saveme" value="<?php _e('Save Post'); ?>" /> 
						<input type="submit" name="publish" value="<?php _e('Publish'); ?>" style="font-weight:bold;" />
<?php
						} ?>
					</div>
				</div>
			</form>
		</div>
<?php		}
		}	
		break;
	
	case "delete" :
		
		break;
		
	default :
?>
		<div id="wrapper">
			<h1><?php _e('Manage Posts'); ?></h1>
			<table class="edit" cellspacing="2">
				<tr>
					<th class="width5 table"><?php _e('ID'); ?></th>
					<th class="width25 table"><?php _e('Title'); ?></th>
					<th class="width20 table"><?php _e('Posted On'); ?></th>
					<th class="width20 table"><?php _e('Tags'); ?></th>
					<th class="width10 table"><?php _e('Type'); ?></th>
					<th class="width10 table">&nbsp;</th>
					<th class="width10 table">&nbsp;</th>
				</tr>
<?php			$posts = get_posts('limit=16');
				foreach($posts as $post) { start_post(); ?>
				<tr<?php tablealt($i); ?>>
					<td class="aligncenter"><?php echo $post['ID']; ?></td>
					<td><?php echo $post['title']; ?></td>
					<td><?php post_date("M dS Y, h:i a"); ?></td>
					<td><?php echo_tags(", ","","","admin=true"); ?></td>
					<td class="capitalize aligncenter"><?php echo $post['type']; ?></td>
					<td class="editbutton"><a href="posts.php?req=edit&amp;id=<?php echo $post['ID']; ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton"><a href="posts.php?req=delete&amp;id=<?php echo $post['ID']; ?>" class="blockit"><?php _e('Delete'); ?></a></td>
				</tr>
<?php			} ?>
			</table>
			<h3 class="gothere"><a href="post-write.php"><?php _e('Create a Post'); ?> &gt;&gt;</a></h3>
		</div>
<?php
}
require("admin-foot.php");
?>