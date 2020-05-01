<?php
declare(strict_types=1);
/**
 * @author Joachim Bauch <mail@joachim-bauch.de>
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

namespace OCA\Talk\Settings\Admin;


use OCA\Talk\Config;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IInitialStateService;
use OCP\Settings\ISettings;

class SignalingServer implements ISettings {

	/** @var Config */
	private $config;
	/** @var IInitialStateService */
	private $initialStateService;

	public function __construct(Config $config,
								IInitialStateService $initialStateService) {
		$this->config = $config;
		$this->initialStateService = $initialStateService;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$this->initialStateService->provideInitialState('talk', 'signaling_servers', [
			'servers' => $this->config->getSignalingServers(),
			'secret' => $this->config->getSignalingSecret(),
			'hideWarning' => $this->config->getHideSignalingWarning(),
		]);
		return new TemplateResponse('talk_bbb', 'settings/admin/signaling-server', [], '');
	}
	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection(): string {
		return 'talk';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority(): int {
		return 75;
	}

}
