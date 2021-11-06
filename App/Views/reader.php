<?php (new View)->render('layout/header');
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

<?php if ($pageData['type'] === 'News') { ?>
    <a href="/news">К списку новостей</a>
<?php } else { ?>
    <a href="/articles">К списку статей</a>
<?php } ?>

<?php (new View)->render('layout/footer') ?>
