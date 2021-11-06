<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/css.css">
    <title><?= Config::get()->value('app_name'); ?></title>
</head>
<body>
<div class="logo">
    <a href="/">
        <img src="/images/logo.png" alt="/">
    </a>
</div>
<?php if ($auth->check()) { ?>
<figure class="text-center">
        <i>Добро пожаловать, <?= $auth->greetings() ?>!</i>
</figure>
<?php } ?>

<div class="login">
    <?php if ($auth->check()) { ?>
        <a href="/logout">Logout</a>
    <?php } else { ?>
        <a href="/login">Login</a>
    <?php } ?>
</div>
<header></header>
<div class="breadcrumbs">
    <!--    --><?php //if ($pageData['action'] != 'main') { ?>
    <!--    <a href="/">Главная</a>-->
    <!--    --><?php //if ($pageData['action'] == 'read') {
    //        echo '→ Просмотр ';
    //        } else {
    //            if ($pageData['action'] == 'edit') {
    //                echo '→ Редактирование ';
    //            } else {
    //                echo '→ Создание ';
    //            }
    //        }
    //        echo $pageData['dictionary'][$pageData['entity']];
    //    }
    //    ?>
</div>