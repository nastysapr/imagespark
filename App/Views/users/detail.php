<?php $this->render('layout/header', [], $breadcrumbs);
$user = $pageData['item']; ?>

    <div class="form">
        <?php if ($pageData['action'] == 'read'){ ?>
        <form action="/" method="post" class="detail">
            <fieldset disabled="disabled">
                <?php }else{ ?>
                <form action="" method="post" class="detail">
                    <?php } ?>
                    <table>
                        <tr>
                            <td><label>Login</label></td>
                            <td><input type="text" name="login" id="login" class="right"
                                       value="<?= htmlspecialchars($user->login) ?>"/></td>
                            <td style="width:200px">
                                <?php if (isset($pageData['errors']['login'])) { ?>
                                    <error><?= "Такой пользователь уже существует" ?></error>
                                <?php } ?>
                                <?php if (isset($pageData['errors']['chars'])) { ?>
                                <error><?= "Только латинские буквы и цифры" ?></error>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td><label>E-mail</label></td>
                            <td><input type="text" name="email" id="email" class="right"
                                       value="<?= htmlspecialchars($user->email) ?>"/></td>
                            <td>
                                <?php if (isset($pageData['errors']['email'])) { ?>
                                    <error><?= "Невалидный адрес электронной почты" ?></error>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td><label>Password</label></td>
                            <td><input type="password" name="password" id="password" class="right"
                                       value=""/>
                            </td>
                            <td><?php if (isset($pageData['errors']['password'])) { ?>
                                    <error><?= "Пароли не совпадают или очень короткий пароль" ?></error>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td><label>Password confirm</label></td>
                            <td><input type="password" name="password_confirm" class="right" id="password_confirm"
                                       value=""/></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td><label>ФИО</label></td>
                            <td><input type="text" name="full_name" id="full_name" class="right"
                                       value="<?= htmlspecialchars($user->full_name) ?>"/></td>
                            <td><?php if (isset($pageData['errors']['full_name'])) { ?>
                                    <error><?= "Некорректные данные" ?></error>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td><label>Дата рождения</label></td>
                            <td><input type="date" name="birthday" class="right"
                                       value="<?php if (!$user->birthday) {
                                           echo date('Y-m-d');
                                       } else {
                                           echo htmlspecialchars($user->birthday);
                                       } ?>"/></td>
                            <td><?php if (isset($pageData['errors']['birthday'])) { ?>
                                    <error><?= "Выберите другую дату" ?></error>
                                <?php } ?></td>
                        </tr>

                        <tr>
                            <td><label>Описание</label></td>
                            <td><textarea name="description" class="right"
                                          id="description"><?= htmlspecialchars($user->description) ?></textarea></td>
                            <td></td>
                        </tr>
                    </table>

                    <?php if ($pageData['action'] == 'read'){ ?>
            </fieldset>
            <a href="/users">Вернуться в раздел</a>
            <?php }
            else { ?>
                <button type="submit" class="btn btn-primary" value="Сохранить" name="save">Сохранить</button>
            <?php } ?>
        </form>
    </div>

<?php $this->render('layout/footer') ?>