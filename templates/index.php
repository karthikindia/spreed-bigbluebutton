<?php
/** @var \OCP\IL10N $l */
/** @var array $_ */

script('talk_bbb', 'talk');

style('talk_bbb', 'merged');
if (($_['user_uid'] ?? '') !== '') {
	\OC::$server->getEventDispatcher()->dispatch('\OCP\Collaboration\Resources::loadAdditionalScripts');
}
