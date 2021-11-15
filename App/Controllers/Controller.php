<?php

namespace App\Controllers;

use App\Service\Authorization;
use App\Service\Errors;
use App\Service\Request;
use App\Service\Validate;
use App\Service\View;

class Controller
{
    public View $view;
    public Errors $errors;
    public Request $request;
    public Validate $validate;
    public Authorization $auth;
    public int $limit = 10; //количество выводимых новостей/статей на странице

    public string $action;
    public string $section;
    public ?int $id = null;

    public function __construct(array $params)
    {
        $this->view = new View();
        $this->errors = new Errors();
        $this->request = new Request();
        $this->validate = new Validate();
        $this->auth = new Authorization();

        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Вызов детальной страницы новости/статьи/пользователя
     */
    public function read(): void
    {
        $item = (new $this->model)->findByPK($this->id);
        if (!$item) {
            $this->errors->notFound();
        }
        $pageData['item'] = $item;
        $pageData['model'] = $this->model;
        $pageData['action'] = 'read';

        $this->view->render($this->viewDetail, $pageData);
    }

    /**
     * Редактирование/создание новости/статьи/пользователя
     */
    public function edit(): void
    {
        $item = (new $this->model);
        if ($this->id) {
            $item = $item->findByPK($this->id);
            if (!$item) {
                $this->errors->notFound();
            }
            $action = 'Редактирование ' . $item->genitive;
        } else {
            $action = 'Создание ' . $item->genitive;
        }

        $item->breadcrumbs = array_merge($item->breadcrumbs, ['' => $action]);

        $pageData['item'] = $item;
        $pageData['action'] = $this->action;

        $pageData['form_data'] = $this->request->getFormData();


        $errors = [];

        if (!empty($pageData['form_data'])) {
            if ($item->id) {
                $pageData['form_data']['id'] = $item->id;
            }

            $validateMethod = $item->table;
            $errors = $this->validate->$validateMethod($pageData['form_data']);

            if (isset($pageData['form_data']['password'])) {
                $pageData['form_data']['password'] = password_hash($pageData['form_data']['password'], PASSWORD_BCRYPT);
            }

            foreach ($pageData['form_data'] as $attribute => $value) {
                $item->$attribute = $value;
            }

            if (empty($errors)) {
                $item->id = $item->save();
                if ($item->id) {
                    $this->redirect($item->getUrl());
                }

                echo 'Ошибка сохранения данных';
            }
        }

        $pageData['errors'] = $errors;

        $this->view->render($this->viewRedactor, $pageData, $item->breadcrumbs);
    }

    /**
     * Удаляет сущность
     */
    public function delete(): void
    {
        $item = (new $this->model)->findByPK($this->id);

        if (!$item) {
            $this->errors->notFound();
        }

        $item->id = $this->id;
        $item->delete();

        $this->redirect('/' . strtolower($this->section));
    }

    /**
     * Перенаправляет пользователя на новую страницу
     */
    public function redirect(string $url, int $code = 303): void
    {
        header('Location:' . $url, true, $code);
    }

}