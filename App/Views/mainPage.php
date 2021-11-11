<?php $this->render('layout/header') ?>
    <h2>Последние новости</h2>
<?php
if ($pageData['news']) { ?>
    <ul>
        <?php foreach ($pageData['news'] as $news) { ?>
            <li>
                <a href="/news/<?= $news->id ?>"><b><?= $news->title ?></b></a>
                <p><?= $news->getShortText() ?></p>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
    <h2>Все новости</h2><a href="/news">Читать далее...</a>
    <br/>

<?php if ($auth->check()) { ?>
    <a href="/news/add">Создать новость</a>
<?php } ?>

    <h2>Все статьи</h2><a href="/articles">Читать далее...</a>
    <br/>

<?php if ($auth->check()) { ?>
    <a href="/articles/add">Создать статью</a>
<?php } ?>

    <br/>

<?php if ($auth->isAdmin()) { ?>
    <h2>Пользователи</h2><a href="/users">Перейти в закрытый раздел</a>
<?php } ?>
<?php $this->render('layout/footer') ?>
