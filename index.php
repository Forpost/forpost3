<?php
/**
 * Main entry point to application.
 *
 * @package: Forpost3
 * @version: 3.2.0
 * @author: Dmitriy Yuriev <coolkid00@gmail.com>
 * @license: http://www.gnu.org/licenses/agpl.txt GNU Affero General Public License
*/


define('START_TIME', microtime(true));
define('FORPOST_VALID', true);
define('ROOT_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

require_once ROOT_DIR . '/system/bootstrap.php';

$app = new ApplicationController();
$app->getFrontController()->run();
