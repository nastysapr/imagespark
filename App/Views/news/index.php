<?php $this->render('layout/header', [], $breadcrumbs) ?>

<h1>Новости</h1>
<br>
<div class="container px-4" id="featured-3">
    <div class="row g-4 row-cols-5 row-cols-lg-2">
<?php foreach ($pageData['items'] as $news) {?>
        <div class="feature col">
            <h3><?=$news->title?></h3>
            <p><?=$news->getShortText()?></p>
            <a href="/news/<?=$news->id ?>">Читать далее</a>
        </div>
<?php }?>
    </div>
</div>
<br>
<?php $pageData['pager']->show()?>

<?php $this->render('layout/footer')?>

