<?php
global $skin_settings,$hide_archive;
?>
			<hr />
			<div id="sidebar">
				<ul class="modules">
<?php
if($skin_settings['sidenotes_position'] == 'sidebar' and $skin_settings['sidenotes_tag'] != '' and $skin_settings['sidenotes_num'] != '') {
	$bj->query->saveas('default');
	$bj->query->setLimit(0,$skin_settings['sidenotes_num']);
	$bj->query->setTags($skin_settings['sidenotes_tag']);
	$entries = $bj->query->fetch();
	if($entries) { ?>
					<li class="module module_sidenotes">
						<h3><?php _e('Sidenotes'); ?></h3>
						<ul>
<?php
		foreach($entries as $entry) { start_entry(); ?>
							<li class="sidenote">
								<a href="<?php echo_permalink(); ?>"><strong><?php echo_title(); ?></strong></a>
								<span class="dash">-</span>
								<span class="text"><?php echo str_replace(array('<p>','</p>'),'',return_snippet()); ?></span>
								<span class="permalink"><?php comments_link('0','1','%','#'); ?></span>
								<span class="entry-edit"><?php edit_entry_link(_r('(e)')); ?></span>
							</li>
<?php
		} ?>
						</ul>
					</li>
<?php
	}
}
if(is_section() and !$hide_archive) { ?>
					<li class="module module_archive">
						<h3><?php _e('Archive'); ?></h3>
						<p><?php printf(_r('Check out the <a href="%1$s">archive</a> for this section.'),get_siteinfo('siteurl').'archive/'.$section['shortname']); ?>
					</li>
<?php
} ?>
				</ul>
			</div>
