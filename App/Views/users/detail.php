<?php (new View)->render('layout/header');
$user = $pageData['item'];?>

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
                           value="<?= isset($user->login) ? htmlspecialchars($user->login) : ''?>"/></td>
                <td style="width:200px">
                    <?php if (isset($pageData['errors']['login'])) { ?>
                        <error><?= "Только латинские буквы и цифры" ?></error>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td><label>E-mail</label></td>
                <td><input type="text" name="email" id="email" class="right"
                           value="<?= isset($user->email) ? htmlspecialchars($user->email) : '' ?>"/></td>
                <td>
                    <?php if (isset($pageData['errors']['email'])) { ?>
                        <error><?= "Невалидный адрес электронной почты" ?></error>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td><label>Password</label></td>
                <td><input type="password" name="password" id="password" class="right"
                           value="<?= isset($user->password) ? htmlspecialchars($user->password) : '' ?>"/>
                </td>
                <td><?php if (isset($pageData['errors']['password'])) { ?>
                        <error><?= "Пароли не совпадают или очень короткий пароль" ?></error>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td><label>Password confirm</label></td>
                <td><input type="password" name="password_confirm" class="right" id="password_confirm"
                           value="<?= (isset($pageData['password_confirm'])) ? htmlspecialchars($pageData['password_confirm']) : ''?>"/></td>
                <td></td>
            </tr>

            <tr>
                <td><label>ФИО</label></td>
                <td><input type="text" name="full_name" id="full_name" class="right"
                           value="<?= (isset($user->full_name)) ? htmlspecialchars($user->full_name) : '' ?>"/></td>
                <td><?php if (isset($pageData['errors']['full_name'])) { ?>
                        <error><?= "Некорректные данные" ?></error>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td><label>Дата рождения</label></td>
                <td><input type="date" name="birthday" class="right"
                           value="<?= (isset($user->birthday)) ? htmlspecialchars($user->birthday) : '' ?>"/></td>
                <td><?php if (isset($pageData['errors']['birthday'])) { ?>
                        <error><?= "Выберите другую дату" ?></error>
                    <?php } ?></td>
            </tr>

            <tr>
                <td><label>Описание</label></td>
                <td><textarea name="description" class="right" id="description"><?= (isset($user->description)) ? htmlspecialchars($user->description) : ''?></textarea></td>
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

<?php (new View)->render('layout/footer')?>