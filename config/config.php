<?php

use App\Controllers\AuthController;
use App\Controllers\ContentController;
use App\Controllers\UsersController;
use App\Middleware\AdminMiddleware;
use App\Middleware\UserMiddleware;
use App\Service\Route;

$entities = '(?P<controller>users|news|articles)';

return [
    'app_name' => 'School',
    'paths' => [
        'root' => __DIR__ . '/../',
        'views' => 'App/Views/',
    ],

    'routes' => [
        '/(?P<section>)' => new Route(),
        '/(?P<section>users)' => new Route(UsersController::class, 'index', ['GET'], AdminMiddleware::class),
        '/(?P<section>users)/(?P<id>\d+)' => new Route(UsersController::class, 'read', ['GET'], AdminMiddleware::class),
        '/(?P<section>users)/(?P<id>\d+)/(?P<action>edit)' => new Route(UsersController::class, 'edit', ['GET', 'POST'], AdminMiddleware::class),
        '/(?P<section>users)/(?P<action>add)' => new Route(UsersController::class, 'edit', ['GET', 'POST'], AdminMiddleware::class),
        '/(?P<section>users)/(?P<id>\d+)/(?P<action>delete)' => new Route(UsersController::class, 'delete', ['GET'], AdminMiddleware::class),
        '/(?P<section>users)/(?P<id>\d+)/(?P<action>role)' => new Route(UsersController::class, 'switchRole', ['GET'], AdminMiddleware::class),

        '/(?P<section>news|articles)' => new Route(ContentController::class),
        '/(?P<section>news|articles)/(?P<id>\d+)' => new Route(ContentController::class, 'read', ['GET']),
        '/(?P<section>news|articles)/(?P<action>add)' => new Route(ContentController::class, 'edit', ['GET', 'POST'], UserMiddleware::class),
        '/(?P<section>news|articles)/(?P<id>\d+)/(?P<action>edit)' => new Route(ContentController::class, 'edit', ['GET', 'POST'], AdminMiddleware::class),
        '/(?P<section>login)' => new Route(AuthController::class, 'login', ['GET', 'POST']),
        '/(?P<section>logout)' => new Route(AuthController::class, 'logout', ['GET']),
    ]
];

//
//        '/^\/' . '(?P<controller>users)' . '$/' => 'index',
//        '/^\/' . '(?P<controller>news|articles)' . '\/index$/' => 'index',
//        '/^\/' . $entities . '\/(?P<id>\d+)$/' => 'read',
//        '/^\/' . $entities . '\/(?P<id>\d+)\/edit$/' => 'edit',
//        '/^\/' . '(?P<controller>users)' . '\/(?P<id>\d+)\/role$/' => 'role',
//        '/^\/' . $entities . '\/(?P<id>\d+)\/delete$/' => 'delete',
//        '/^\/logout$/' => 'logout',
//        '/^\/login$/' => 'login',
//        '/^\/$/' => 'main',
//    ],



/* news articles
 * 1. Добавить сущности: новости и статьи. Поля: заголовок, текст, юзер, который создал и дата публикации.
 Отличие новости в том, что в списке на внешке она отображается только один день,
 потом становится доступна только по прямой ссылке.

2. Добавляем "внешку". Т.е. появляется доступный даже для гостей интерфейс,
в котором можно просмотреть списки новостей и статей, а также перейти на детальную страницу,
чтобы прочитать публикацию полностью.

3. В списке выводим заголовок и краткий текст статьи \ новости. Не более 100 символов,
но так, чтобы последнее слово не прерывалось посередине, а выводилось полностью.
Сторонних модулей для обрезки при этом не использовать, написать свой велосипед.

4. Сделать генератор контента, как минимум по 25 публикаций в каждом разделе.

5. * Задание повышенной сложности: сделать пагинацию с выводом по 10 шт. на одной странице.

Дополнение к заданию. Генератор кода должен генерировать не по 25 сущностей, а 1 миллион.
 */

/*
/users/add - добавление
/users/1 - просмотр
/users/1/edit - редактирование
/users/1/delete - удаление
 */


/*[
    'method' => ['GET', 'POST'],
    'pattern' => '/^\/' . $entities .'\/(add)$/',
    'params' => '?controller=$1&action=$2',
],
[
    'method' => ['GET'],
    'pattern' => '/^\/$/',
    'params' => '?controller=MainPage&action=index',
],
[
    'method' => ['GET', 'POST'],
    'pattern' => '/^\/' . $entities .'\/(\d+)\/(edit)$/',
    'params' => '?controller=$1&id=$2&action=$3',
],
'/^\/' . $entities .'\/(list)$/' => 'controller/action',
'/^\/' . $entities .'\/(list)/page/(\d+)$/' => 'controller/action/page',
'/^\/' . $entities .'\/(\d+)$/' => 'controller/id',
'/^\/' . $entities .'\/(\d+)\/(edit)$/' => 'edit',
'/^\/' . $entities .'\/(\d+)\/(delete)$/' => 'controller/id/delete',*/