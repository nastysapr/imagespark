<?php $this->render('layout/header', [], $breadcrumbs) ?>

<form class="form-inline" method="post">
    <div class="form-group row">
        <label for="inputLogin" class="col-sm-2 col-form-label">Логин</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="inputLogin" name="login"
                   value="<?= (isset($pageData['login'])) ? htmlspecialchars($pageData['login']) : '' ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Пароль</label>
        <div class="col-sm-5">
            <input type="password" class="form-control" name="password">
        </div>
    </div>
    <button type="submit" class="btn btn-primary" name="loginButton">Войти</button>
</form>
<?php if (isset($pageData['error'])) { ?>
    <br>
    <error><?= $pageData['error'] ?></error>
<?php } ?>

<?php $this->render('layout/footer') ?>
