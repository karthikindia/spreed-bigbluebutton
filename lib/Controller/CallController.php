<?php
declare(strict_types=1);
/**
 * @copyright Copyright (c) 2016 Lukas Reschke <lukas@statuscode.ch>
 * @copyright Copyright (c) 2016 Joas Schilling <coding@schilljs.com>
 *
 * @author Lukas Reschke <lukas@statuscode.ch>
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Talk\Controller;

if ((@include_once __DIR__ . '/../../vendor/autoload.php')===false) {
	throw new Exception('Cannot include autoload. Did you run install dependencies using composer?');
}

use OCA\Talk\Participant;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\IRequest;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;

class CallController extends AEnvironmentAwareController {

	/** @var ITimeFactory */
	private $timeFactory;

	public function __construct(string $appName,
								IRequest $request,
								ITimeFactory $timeFactory) {
		parent::__construct($appName, $request);
		$this->timeFactory = $timeFactory;
	}

	/**
	 * @PublicPage
	 * @RequireParticipant
	 * @RequireReadWriteConversation
	 * @RequireModeratorOrNoLobby
	 *
	 * @return DataResponse
	 */
	public function getPeersForCall(): DataResponse {
		$timeout = $this->timeFactory->getTime() - 30;
		$result = [];
		$participants = $this->room->getParticipantsInCall();
		foreach ($participants as $participant) {
			if ($participant->getLastPing() < $timeout) {
				// User is not active in call
				continue;
			}

			$result[] = [
				'userId' => $participant->getUser(),
				'token' => $this->room->getToken(),
				'lastPing' => $participant->getLastPing(),
				'sessionId' => $participant->getSessionId(),
			];
		}

		return new DataResponse($result);
	}

	/**
	 * @PublicPage
	 * @RequireParticipant
	 * @RequireReadWriteConversation
	 * @RequireModeratorOrNoLobby
	 *
	 * @param int|null $flags
	 * @return DataResponse
	 */
	public function joinCall(?int $flags): DataResponse {

	$sessionId = $this->participant->getSessionId();

	if ($sessionId === '0') {
		return new DataResponse([], Http::STATUS_NOT_FOUND);
	}

	$url = '';

	$config = \OC::$server->getConfig();
	$appConfig = $config->getSystemValue('spreed');
	if (!array_key_exists('bbb_server', $appConfig) || !array_key_exists('bbb_secret', $appConfig)) {
		return new DataResponse($url);
	}

    $bbb = new BigBlueButton();
    $token = $this->room->getToken();

    # checking if meeting already exists
    $getMeetingInfoParams = new GetMeetingInfoParameters($token, 'IAmAModerator');
    $response = $bbb->getMeetingInfo($getMeetingInfoParams);
    if ($response->getReturnCode() == 'FAILED') {
      $createMeetingParams = new CreateMeetingParameters($token, $this->room->getName());
      $createMeetingParams = $createMeetingParams->setModeratorPassword('IAmAModerator')->setAttendeePassword('IAmAnAttendee');
      $response = $bbb->createMeeting($createMeetingParams);
      if ($response->getReturnCode() == 'FAILED') return new DataResponse($url);
    }

    $password = ($this->participant->getParticipantType() <= 2) ? 'IAmAModerator' : 'IAmAnAttendee';
    $displayname = $this->participant->getDisplayName();
    $joinMeetingParams = new JoinMeetingParameters($token, $displayname , $password);
    # https://github.com/bigbluebutton/bigbluebutton-api-php/wiki/Full-Usage-Sample
    $joinMeetingParams->setRedirect(true);
    $joinMeetingParams->setJoinViaHtml5(true);
    $url = $bbb->getJoinMeetingURL($joinMeetingParams);

    // we only change the flags after we successfully obtained the BBB url
    if ($flags === null) {
      // Default flags: user is in room with audio/video.
      $flags = Participant::FLAG_IN_CALL | Participant::FLAG_WITH_AUDIO | Participant::FLAG_WITH_VIDEO;
    }

    $this->room->changeInCall($this->participant, $flags);

		return new DataResponse($url);
	}

	/**
	 * @PublicPage
	 * @RequireParticipant
	 *
	 * @return DataResponse
	 */
	public function leaveCall(): DataResponse {
		$sessionId = $this->participant->getSessionId();
		if ($sessionId === '0') {
			return new DataResponse([], Http::STATUS_NOT_FOUND);
		}

		$this->room->changeInCall($this->participant, Participant::FLAG_DISCONNECTED);

		return new DataResponse();
	}

}
