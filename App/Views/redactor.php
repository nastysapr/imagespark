<?php (new View)->render('layout/header');
$entity = $pageData['entity'];
?>

    <div class="form">
        <form action="" method="post" class="detail">
            <table>
                <tr>
                    <td><label>Заголовок</label></td>
                    <td><input type="text" name="title" id="title" class="right"
                               value="<?= isset($entity->title) ? htmlspecialchars($entity->title) : '' ?>"/>
                    </td>
                    <td style="width:200px">
                        <?php if (isset($pageData['errors']['title'])) { ?>
                            <error><?= "Заполните поле" ?></error>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Автор</label></td>
                    <td><input type="text" name="author" id="author" class="right"
                               value=<?= $entity->getAuthor() ?>/>
                    </td>
                    <td>
                        <?php if (isset($pageData['errors']['author'])) { ?>
                            <error><?= "Заполните поле" ?></error>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><label>Дата публикации</label></td>
                    <td><input type="date" name="date" class="right"
                               value=<?= $entity->setDate() ?>/>
                    </td>
                    <td><?php if (isset($pageData['errors']['date'])) { ?>
                            <error><?= "Выберите другую дату" ?></error>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td>
                        <label>Текст</label>
                    </td>
                    <td><textarea name="text" class="right"
                                  id="text"><?= (isset($entity->text)) ? htmlspecialchars($entity->text) : '' ?></textarea>
                    </td>
                    <td><?php if (isset($pageData['errors']['text'])) { ?>
                            <error><?= 'Текст' . ((isset($pageData['dictionary'][$pageData['entity']])) ? ' ' . $pageData['dictionary'][$pageData['entity']] : '') . 'не заполнен' ?></error>
                        <?php } ?></td>
                </tr>
            </table>
            <button type="submit" class="btn btn-primary" value="Сохранить" name="save">Сохранить</button>
        </form>
    </div>

<?php (new View)->render('layout/footer')?>