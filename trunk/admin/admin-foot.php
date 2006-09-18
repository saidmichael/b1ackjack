<?php run_actions('admin_footer'); ?>
		<div id="footer">
			<p><?php printf(_r('Blackjack %1$s - %2$s Queries'),$bj_version,$bj_db->querycount()); ?></p>
		</div>
	</body>
</html>