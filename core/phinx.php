<?php

declare(strict_types=1);

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent;
use Vesp\Services\Migration;

define('BASE_DIR', dirname(__DIR__) . '/');
require BASE_DIR . 'core/vendor/autoload.php';
Env::loadFile(BASE_DIR . 'core/.env');

$eloquent = new Eloquent();
$config = $eloquent->getConnection()->getConfig();

return [
    'paths' => [
        'migrations' => BASE_DIR . 'core/db/migrations',
        'seeds' => BASE_DIR . 'core/db/seeds',
    ],
    'migration_base_class' => Migration::class,
    'environments' => [
        'default_migration_table' => $config['prefix'] . 'migrations',
        'default_environment' => 'dev',
        'dev' => [
            'name' => $config['database'],
            'connection' => $eloquent->getConnection()->getPdo(),
        ],
    ],
];
