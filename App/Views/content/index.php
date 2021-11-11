<?php $this->render('layout/header', [], $breadcrumbs); ?>

<?php if ($pageData['model'] === 'App\Models\News') { ?>
    <h1>Новости</h1>
<?php } else { ?>
    <h1>Статьи</h1>
<?php } ?>

    <br>
<?php foreach ($pageData['items'] as $item) { ?>
    <a href="/<?= $item->table ?>/<?= $item->id ?>"><b><?= $item->title ?></b></a>
    <p><?= $item->getShortText() ?></p>
<?php } ?>

<?php if ($pageData['pager']) {
    $pageData['pager']->show();
} else { ?>
    <p>Актуальных новостей нет</p>
<?php } ?>

<?php $this->render('layout/footer') ?>