		</div>
		<hr />
		<div id="footer">
			<p><?php printf(_r('Powered by <a href="%1$s">Blackjack</a> - %2$s Queries'),'http://code.google.com/p/b1ackjack/',$bj->db->querycount()); ?></p>
			<p><?php printf(_r('Feed me: <a href="%1$s" class="%2$s"><span class="%3$s">RSS</span></a>'),get_siteinfo('feedurl'),'rss-link','blank'); ?></p>
		</div>
		<?php run_actions('site_footer'); ?>
	</body>
</html>
