<?php

use App\Service\ContentGenerator;
use App\Service\Migration;
use App\Service\Seeder;

require_once 'App/Autoloader.php';
Autoloader::register();

$longopts = [
    "name:",
    "count:",
    "migration:",
    "param:",
    "seeder:"
];
$options = getopt(0, $longopts);
if (isset($options['name']) && !in_array($options['name'], ['news', 'articles']) ||
    isset($options['migration']) && !in_array($options['migration'], ['create', 'rollback', 'migrate']) ||
    isset($options['seeder']) && $options['seeder'] !== 'seed') {
    echo 'Unexpected parameter';

    return 0;
}

$start = microtime(true);
/**
 * Миграция
 * --migration=create --param=
 */
if (isset($options['migration'])) {
    $method = $options['migration'];
    $param = "";
    if (isset($options['param'])) {
        $param = $options['param'];
    }

    (new Migration())->$method($param);
}

/**
 * Вызов сидера
 */
if (isset($options['seeder'])) {
    (new Seeder())->seed();
}

/**
 * Генератор контента
 * --name=news --count=3000
 */
if (isset($options['name'])) {
    if (is_numeric($options['count']) && $options['count'] > 0 && $options['name']) {
        new ContentGenerator($options['name'], $options['count']);
    }
}
$time = microtime(true) - $start;
echo "Time: " . $time . "\n";
