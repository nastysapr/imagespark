<?php $this->render('layout/header', [], $breadcrumbs) ?>

<h1>Товары</h1>
<br>

<form>
    <select class="form-select" aria-label="Default select example" name="category_id">
        <?php
        $select = 0;
        if (isset($pageData['filters']['category_id'])) {
            $select = $pageData['filters']['category_id'];
        } ?>

        <option value="0" <?php if (!$select) { ?>selected<?php } ?>>Категория товара</option>

        <?php foreach ($pageData['categories'] as $category) {
            if ($category->parent_id == 0) { ?>
                <option value="<?= $category->id ?>" <?php if ($select === $category->id) { ?> selected <?php } ?>>
                    <?= mb_convert_case($category->alias, MB_CASE_UPPER) ?></option>

                <?php foreach ($pageData['categories'] as $subcategory) {
                    if ($subcategory->parent_id == $category->id) { ?>
                        <option value="<?= $subcategory->id ?>"
                            <?php if ($select == $subcategory->id) { ?>
                                selected
                            <?php } ?>
                        > --- <?= mb_convert_case($subcategory->alias, MB_CASE_UPPER) ?></option>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </select>


    <br/>
    <select class="form-select" aria-label="Default select example" name="brand_id">
        <?php $select = 0;
        if (isset($pageData['filters']['brand_id'])) {
            $select = $pageData['filters']['brand_id'];
        } ?>

        <option value="0">Бренд</option>
        <?php foreach ($pageData['brands'] as $brand) { ?>
            <option value="<?= $brand->id ?>" <?php if ($select === $brand->id) { ?> selected <?php } ?>><?= $brand->alias ?></option>
        <?php } ?>
    </select>
    <br>

    <select class="form-select" aria-label="Default select example" name="colour_id">
        <?php $select = 0;
         if (isset($pageData['filters']['colour_id'])) {
            $select = $pageData['filters']['colour_id'];
        } ?>

        <option value="0">Цвет</option>
        <?php foreach ($pageData['colours'] as $colour) { ?>
            <option value="<?= $colour->id ?>" <?php if ($select === $colour->id) { ?> selected <?php } ?>><?= $colour->alias ?></option>
        <?php } ?>
    </select>
    <br/>

    <select class="form-select" aria-label="Default select example" name="size">
        <?php $select = 0;
        if (isset($pageData['filters']['size'])) {
            $select = $pageData['filters']['size'];
        } ?>

        <option value="0">Размер</option>
        <?php foreach ($pageData['sizes'] as $size) { ?>
            <option value="<?= $size->id ?>" <?php if ($select === $size->id) { ?>
                selected
            <?php } ?>><?= $size->alias ?></option>
        <?php } ?>
    </select>
    <br>
    <button type="submit" class="btn btn-primary" value="Найти" name="search">Найти</button>
</form>
<br>

<?php if (empty($pageData['result'])) { ?>
    <error>По вашему запросу ничего не найдено</error>
<?php } ?>

<?php foreach ($pageData['result'] as $good) { ?>

    <?php if ($good->brand) { ?>
        <b><?= $good->brand ?> </b>
    <?php } ?>

    <?php if ($good->category) { ?>
        <b><?= $good->category ?> </b>
    <?php } ?>

    <?php if ($good->model) { ?>
        <b><?= $good->model ?> </b>
    <?php } ?>

    <?php if ($good->vendor_code) { ?>
        артикул: <?= $good->vendor_code ?>
    <?php } ?>

    <?php if ($good->size) { ?>
        размер: <?= $good->size ?>
    <?php } ?>

    <?php if ($good->colour) { ?>
        цвет: <?= $good->colour ?>
    <?php } ?>

    <?php if ($good->orientation) { ?>
        - <?= $good->orientation ?>
    <?php } ?>
    <br/>
<?php } ?>
<?php //$pageData['pager']->show()?>

<?php $this->render('layout/footer') ?>
