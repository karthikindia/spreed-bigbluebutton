<?php
/** @var array $_ */
/** @var \OCP\IL10N $l */
script('talk_bbb', ['admin/allowed-groups']);
style('talk_bbb', ['settings-admin']);
?>

<div class="videocalls section" id="allowed_groups">
	<h2><?php p($l->t('Limit to groups')) ?></h2>
	<p class="settings-hint"><?php p($l->t('When at least one group is selected, only people of the listed groups can be part of conversations.')); ?></p>
	<p class="settings-hint"><?php p($l->t('Guests can still join public conversations.')); ?></p>
	<p class="settings-hint"><?php p($l->t('Users that can not use Talk anymore will still be listed as participants in their previous conversations and also their chat messages will be kept.')); ?></p>
</div>
