<?php use App\Service\Authorization;

$this->render('layout/header', [], $breadcrumbs); ?>

<table id="users">
    <tr>
        <th>Фамилия Имя Отчество</th>
        <th>Описание</th>
        <th style="width:105px">Операции</th>
    </tr>

    <?php
    foreach ($pageData['users'] as $user) { ?>
        <tr>
            <td><?= $user->full_name ?></td>
            <td><?= $user->description ?></td>
            <td>
                <a href="/users/<?= $user->id ?>">
                    <i class="bi bi-person-lines-fill"></i>
                </a>
                <a href="/users/<?= $user->id ?>/edit">
                    <i class="bi bi-vector-pen"></i>
                </a>
                <a href="/users/<?= $user->id ?>/delete">
                    <i class="bi bi-trash-fill"></i>
                </a>
                <a href="/users/<?= $user->id ?>/role"><?php if ($user->role === Authorization::ROLE_ADMIN) { ?>
                        <i class="bi bi-person-check"></i>
                    <?php } else { ?>
                        <i class="bi bi-person"></i>
                    <?php } ?>
                </a>

                </div>
            </td>
        </tr>
    <?php } ?>
</table>
<br/>
<a href="/users/add">
    Создать нового пользователя
</a>

<?php $this->render('layout/footer') ?>
