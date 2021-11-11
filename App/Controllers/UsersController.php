<?php
namespace App\Controllers;

use App\Models\User;
use App\Service\Authorization;

/**
 * Контроллер для работы с учетными записями пользователей
 */
class UsersController extends Controller
{
    public string $model = 'App\Models\User';
    public string $viewDetail = 'users/detail';
    public string $viewRedactor = 'users/detail';
    public string $section = 'users';

    /**
     * Отображает список зарегистрированных пользователей только для пользователя,
     * у которого есть права администратора
     */
    public function index(): void
    {
        $pageData['users'] = (new User())->findAll();
        sort($pageData['users']);

        $this->view->render('users/index', $pageData, ['' => 'Список пользователей']);
    }

    /**
     * Меняет роль пользователя (админ\пользователь)
     */
    public function switchRole()
    {
        $user = (new User())->findByPK($this->id);
        if (!$user) {
            $this->errors->notFound();
        }

        if ($user->role === Authorization::ROLE_ADMIN) {
            $user->role = Authorization::ROLE_USER;
        } else {
            $user->role = Authorization::ROLE_ADMIN;
        }

        $user->id = $this->id;
        $user->save();

        $this->redirect('/users');
    }

}