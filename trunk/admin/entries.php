<?php
$parent_file = "entries.php";
require("admin-head.php");
if(we_can('edit_entries')) {
	switch($_GET['req']) {
		case "edit" :
			if(isset($_GET['id'])) {
				get_admin_header();
				$bj->query->setLimit(0,1);
				$bj->query->setID(intval($_GET['id']));
				$entries = $bj->query->fetch();
				if($entries) {
					foreach($entries as $entry) { ?>
			<h2><?php printf(_r('Editing &#8220;%1$s&#8221;'),get_entry_title()); ?></h2>
<?php do_editorform($entry); ?>
<?php
					}
				}
				get_admin_footer();
			}
			break;
	
		case "delete" :
			if(isset($_GET['id'])) {
				bj_delete_entry(intval($_GET['id']));
				@header("Location: ".get_siteinfo('adminurl')."entries.php?deleted=true");
			}
			break;
			
		case "ajaxdelete" :
			if(isset($_GET['id'])) {
				bj_delete_entry(intval($_GET['id']));
				_e('Entry deleted.');
			}
			break;
		case "search" :
		case "filtertag" :
		case "filtersection" :
		default :
			if($_GET['deleted'] == 'true')
				add_bj_notice(_r('Entry deleted.'));
			get_admin_header();
?>
			<h2><?php _e('Manage Entries'); ?></h2>
<?php
			$drafts = $bj->db->get_rows("SELECT ID,title FROM ".$bj->db->entries." WHERE ptype = 'draft' ORDER BY ID DESC");
			if($drafts) { ?>
			<div class="drafts">
				<h3><?php _e('Drafts'); ?></h3>
<?php
				foreach($drafts as $draft) {
					$title = (!empty($draft['title'])) ? $draft['title'] : sprintf(_r('Entry #%1$s'),$draft['ID']);
					$draft_string .= "<a href=\"entries.php?req=edit&amp;id=".$draft['ID']."\">".$title."</a>, ";
				} ?>
				<p><?php echo preg_replace("{, $}","",$draft_string); ?></p>
			</div>
<?php
			} ?>
			<div class="page-options">
				<div class="column width33 searchbox">
					<form method="get" action="entries.php">
						<label for="s"><?php _e('Search:'); ?></label><br />
						<input type="hidden" name="req" value="search" />
						<input type="text" name="s" id="s" value="<?php echo bj_clean_string($_GET['s']); ?>" />
						<input type="submit" value="<?php _e('Search'); ?>" />
					</form>
				</div>
				<div class="column width33 tagfilter">
<?php
				$tags = return_all_tags();
				if(is_array($tags)) { ?>
					<form method="get" action="entries.php">
						<label for="tag"><?php _e('Filter by Tag:'); ?></label><br />
						<input type="hidden" name="req" value="filtertag" />
						<select name="tag" id="tag">
<?php
					foreach($tags as $tag) { ?>
							<option value="<?php echo $tag['ID']; ?>"<?php bj_selected($tag['ID'],intval($_GET['tag'])); ?>><?php echo $tag['name']; ?></option>
<?php
					} ?>
						</select>
						<input type="submit" value="<?php _e('Show'); ?>" />
					</form>
<?php
				} ?>
				</div>
				<div class="column width33 sectionfilter">
<?php
				$sections = return_sections();
				if(is_array($sections)) { ?>
					<form method="get" action="entries.php">
						<label for="section"><?php _e('Filter by Section:'); ?></label><br />
						<input type="hidden" name="req" value="filtersection" />
						<select name="section" id="section">
<?php
					foreach($sections as $section) { ?>
							<option value="<?php echo $section['ID']; ?>"<?php bj_selected($section['ID'],intval($_GET['section'])); ?>><?php echo $section['title']; ?></option>
<?php
					} ?>
						</select>
						<input type="submit" value="<?php _e('Show'); ?>" />
					</form>
<?php
				} ?>
				</div>
				<div class="clear"></div>
			</div>
			<table class="edit" id="entries" cellspacing="2">
				<tr class="ths">
					<th class="width5"><?php _e('ID'); ?></th>
					<th class="width20"><?php _e('Title'); ?></th>
					<th class="width20"><?php _e('Posted'); ?></th>
					<th class="width25"><?php _e('Tags'); ?></th>
					<th class="width10"><?php _e('Status'); ?></th>
					<th colspan="2"><?php _e('Action'); ?></th>
				</tr>
<?php
				$bj->query->setLimit(intval($_GET['offset']),16);
				if($_GET['req'] == 'filtertag')
					$bj->query->setTags(intval($_GET['tag']));
				elseif($_GET['req'] == 'filtersection')
					$bj->query->setSection(intval($_GET['section']));
				elseif(is_search()) {
					$bj->query->setPtype('public');
					$bj->query->setSearch(bj_clean_string($_GET['s']));
				}
				else
					$bj->query->setPtype('public');
				$entries = $bj->query->fetch();
				if($entries) {
					foreach($entries as $entry) { thru_loop(); ?>
				<tr class="<?php tablealt($i); ?>" id="entry-<?php entry_ID(); ?>">
					<td class="aligncenter"><?php entry_ID(); ?></td>
					<td><?php entry_title(); ?></td>
					<td><?php entry_date("M jS Y, h:i a"); ?></td>
					<td><?php entry_tags(", ","","","admin=true"); ?></td>
					<td class="capitalize aligncenter"><?php _e(get_entry_type()); ?></td>
					<td class="editbutton width10"><a href="entries.php?req=edit&amp;id=<?php entry_ID(); ?>" class="blockit"><?php _e('Edit'); ?></a></td>
					<td class="editbutton width10"><a href="entries.php?req=delete&amp;id=<?php entry_ID(); ?>" class="blockit deleteme" rel="entries.php?req=ajaxdelete&amp;id=<?php entry_ID(); ?>$entry-<?php entry_ID(); ?>"><?php _e('Delete'); ?></a></td>
				</tr>
<?php
					}
				}
				else { ?>
				<tr>
					<td colspan="7"><?php _e('No entries found.'); ?></td>
				</tr>
<?php
				} ?>
			</table>
			<div class="navigation">
			<?php $bj->query->prev_page(_r('&laquo; Newer'),'<div class="alignleft">','</div>'); ?>
			<?php $bj->query->next_page(_r('Older &raquo;'),'<div class="alignright">','</div>'); ?>
			</div>
<?php
		get_admin_footer();
	}
}
else {
	_e('You don\'t have permission to access this file.');
}
?>
