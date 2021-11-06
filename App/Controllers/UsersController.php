<?php

/**
 * контроллер для работы с учетными записями пользователей
 */
class UsersController extends Controller
{
    public string $model = 'User';
    public string $section = '/users';
    public string $viewIndex = 'users/index';
    public string $viewDetail = 'users/detail';
    public string $viewRedactor = 'users/detail';
    /**
     * отображает список зарегистрированных пользователей только для пользователя,
     * у которого есть права администратора
     */
    public function index(): void
    {
        $pageData['users'] = (new User())->all();
        sort($pageData['users']);

        $this->view->render('users/index', $pageData);
    }

    /**
     * Меняет роль пользователя (админ\пользователь)
     */
    public function role()
    {

        $user = new User($this->id);
        if ($user->role === Authorization::ROLE_ADMIN) {
            $user->role = Authorization::ROLE_USER;
        } else {
            $user->role = Authorization::ROLE_ADMIN;
        }

        $user->save();

        $this->redirect('/users');
    }

}