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
    "seeder:",
    "help:"
];
$options = getopt(0, $longopts);

if (isset($options['help'])) {
    echo "Migration:    --migration=create --param=new_migration_name\n";
    echo "              --migration=migrate\n";
    echo "              --migration=rollback --param=[all, (int) count]\n";
    echo "Seeder:       --seeder=[users, news, articles]  --count=(int)'\n";
    echo "Simple content generator:  --name=[news, articles] --count=\n";

    return;
}

if (isset($options['name']) && !in_array($options['name'], ['news', 'articles']) ||
    isset($options['migration']) && !in_array($options['migration'], ['create', 'rollback', 'migrate']) ||
    isset($options['seeder']) && !in_array($options['seeder'], ['users', 'news', 'articles'])) {
    echo "Expected parameter";

    return;
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
    var_dump($options['seeder']);
    (new Seeder())->seed($options['seeder'], $options['count']);
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

