<?php

declare(strict_types=1);

use App\Controllers\Web\Users;
use Vesp\Helpers\Env;

define('BASE_DIR', dirname(__DIR__) . '/');
require_once BASE_DIR . 'core/vendor/autoload.php';
Env::loadFile(BASE_DIR . 'core/.env');

$app = DI\Bridge\Slim\Bridge::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->any('/web/users[/{id:\d+}]', [Users::class, 'process']);

try {
    $app->run();
} catch (Throwable $e) {
    http_response_code($e->getCode());
    echo json_encode($e->getMessage());
}
