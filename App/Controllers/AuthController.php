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

        $this->view->render('authorization', $formData);
    }

    /**
     * Выход пользователя из закрытой части
     */
    public function logout()
    {
        $this->auth->logout();
        $this->redirect('/');
    }
}