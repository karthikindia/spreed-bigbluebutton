<?php
/** @var array $_ */
/** @var \OCP\IL10N $l */
script('talk_bbb', ['admin/commands']);
style('talk_bbb', ['settings-admin']);
?>

<div class="videocalls section" id="chat_commands">
	<h2><?php p($l->t('Commands')) ?></h2>
	<p class="settings-hint"><?php p($l->t('Specify commands the users can use in chats')); ?></p>

	<div class="commands">
	</div>
</div>
