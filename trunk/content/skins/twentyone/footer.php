		</div>
		<hr />
		<div id="footer">
			<p><?php printf(_r('Powered by <a href="http://ceeps.blogs.tbomonline.com/section/blackjack/">Blackjack %1$s</a> and <a href="http://epsilon.blogs.tbomonline.com">Twentyone</a> - %2$s Queries'),$bj_version,$bj_db->querycount()); ?></p>
			<p><?php printf(_r('Fed through <a href="%1$s" class="rss-link">RSS 2.0</a>'),get_siteinfo('feedurl')); ?></p>
		</div>
		<?php run_actions('site_footer'); ?>
	</body>
</html>