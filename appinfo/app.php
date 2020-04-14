<?php
/**
 * @author Jan-Christoph Borchardt, http://jancborchardt.net
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

use OC\Security\CSP\ContentSecurityPolicy;

$config = \OC::$server->getConfig();

# Setting global variables needed by BBB PHP API
# Adding BBB server to allowed iframe domains
$appConfig = $config->getSystemValue('spreed');
if (is_array($appConfig) && array_key_exists('bbb_server', $appConfig) && array_key_exists('bbb_secret', $appConfig)) {
    putenv("BBB_SERVER_BASE_URL=" . $appConfig['bbb_server']);
    putenv("BBB_SECRET=" . $appConfig['bbb_secret']);
    $urlInfo = parse_url($appConfig['bbb_server']);
    $CSPManager = \OC::$server->getContentSecurityPolicyManager();
    $policy = new ContentSecurityPolicy();
	$policy->addAllowedFrameDomain('https://'.$urlInfo['host']);
	$CSPManager->addDefaultPolicy($policy);
}

$app = \OC::$server->query(\OCA\Talk\AppInfo\Application::class);
// For the navigation $l->t('Talk')
$app->register();
