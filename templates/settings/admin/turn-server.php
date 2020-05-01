<?php
/** @var array $_ */
/** @var \OCP\IL10N $l */
script('talk_bbb', ['admin/turn-server']);
style('talk_bbb', ['settings-admin']);
?>

<div id="turn_server" class="videocalls section">
	<h2><?php p($l->t('TURN server')) ?></h2>
	<p class="settings-hint"><?php p($l->t('The TURN server is used to proxy the traffic from participants behind a firewall.')); ?></p>

	<div class="turn-servers">
	</div>
</div>
