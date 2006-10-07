		</div>
		<div id="footer">
			<p><?php printf(_r('Powered by <a href="http://ceeps.blogs.tbomonline.com/section/blackjack/">Blackjack %1$s</a> - %2$s Queries'),$bj_version,$bj_db->querycount());print_r($bj_db->queries); ?></p>
		</div>
		<?php run_actions('site_footer'); ?>
	</body>
</html>