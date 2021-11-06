<?php (new View)->render('layout/header')?>

<?php if ($pageData['model'] === 'News') { ?>
    <h1>Новости</h1>
<?php } else { ?>
    <h1>Статьи</h1>
<?php } ?>

<br>
<?php foreach ($pageData['items'] as $article) {?>
    <a href="/articles/<?=$article->id ?>"><b><?=$article->title ?></b></a>
    <p><?=$article->getShortText()?></p>
<?php }?>

<?php $pageData['pager']->show()?>

<?php (new View)->render('layout/footer')?>