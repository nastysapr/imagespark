<?php

/**
 * Отвечает за авторизацию пользователей
 */
class AuthController extends Controller
{
    public function login()
    {
        if ($this->auth->check()) {
            $this->redirect('/');
        }

        $formData = $this->request->getFormData();
        if ($formData) {
            $user = (new User())->findAndAuth($formData);
            if ($user) {
                $this->auth->login($user->id);
                $this->redirect('/');
            } else {
                $pageData['error'] = 'Ошибка! Логин или пароль неверные';
            }
        }

  //      $pageData = array_merge($pageData, $formData);
        $this->view->render('authorization', $formData);

    }

    /**
     * Отображает форму авторизации
     */
    public function loginGET(): void
    {

    }

    /**
     * Проверяет аутентификационные данные, авторизовывает пользователя в случае успеха
     */
    public
    function loginPOST(array $params)
    {

    }

    /**
     * Выход пользователя из закрытой части
     */
    public
    function logout()
    {
        $this->auth->logout();
        $this->redirect('/');
    }
}