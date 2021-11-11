<?php $this->render('layout/header', [], $breadcrumbs);
$item = $pageData['item']; ?>

<div class="header">
    <label class="right"><?= $item->date ?></label>
    <h1><?= $item->title ?></h1>
</div>
<br>
<div class="text">
    <p>
        <?= $item->getText() ?>
    </p>
</div>
<br/>
<span class="right"><i><?= $item->author ?></i></span>
<br/>

<?php if ($pageData['model'] === 'App\Models\News') { ?>
    <a href="/news">К списку новостей</a>
<?php } else { ?>
    <a href="/articles">К списку статей</a>
<?php } ?>

<?php $this->render('layout/footer') ?>
