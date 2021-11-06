<?php

class Controller
{
    public $view;
    public $errors;
    public $request;
    public $validate;
    public $auth;
    public int $limit = 10; //количество выводимых новостей/статей на странице
    public string $action;
    public ?int $id = null;
    public string $model;
    public string $viewIndex;
    public string $viewDetail;
    public string $viewRedactor;
    public string $section;

    public function __construct(array $params)
    {
        $this->view = new View();
        $this->errors = new Errors();
        $this->request = new Request();
        $this->validate = new Validate();
        $this->auth = new Authorization();

        if (isset($params['id'])) {
            $this->id = $params['id'];
        }

        if (isset($params['entity']) && $params['entity'] !== 'users') {
            $this->viewIndex = $params['entity'] . '/index';
            $this->viewRedactor = 'redactor';
            $this->viewDetail = 'reader';
            $this->section = $params['entity'];
            $this->model = ucfirst($params['entity']);
        }

        if (isset($params['action'])) {
            $this->action = $params['action'];
        }
    }

    /**
     * вызов детальной страницы новости/статьи/пользователя
     */
    public function read(): void
    {
        $pageData['item'] = new $this->model($this->id);
        $pageData['type'] = $this->model;
        $pageData['action'] = 'read';

        $this->view->render($this->viewDetail, $pageData);
    }

    /**
     * Редактирование/создание новости/статьи
     */
    public function edit(): void
    {
        $item = new $this->model($this->id);

        $pageData['item'] = $item;
        $pageData['action'] = $this->action;

        $pageData['form_data'] = $this->request->getFormData();
        $errors = [];

        if (!empty($pageData['form_data'])) {
            $validateMethod = strtolower($this->model);
            $errors = $this->validate->$validateMethod($pageData['form_data']);

            foreach ($pageData['form_data'] as $attribute => $value) {
                $item->$attribute = $value;
            }

            if (empty($errors)) {
                if (!$this->id) {
                    $item->id = $item->calcId();
                }

                $item->save();
                $this->redirect($item->getUrl());
            }

        }

        $pageData['entity'] = $item;
        $pageData['errors'] = $errors;

        $this->view->render($this->viewRedactor, $pageData);
    }

    /**
     * Удаляет сущность
     */
    public function delete(): void
    {
        (new $this->model($this->id))->delete();
        $this->redirect(strtolower($this->section));
    }

    /**
     * Перенаправляет пользователя на новую страницу
     */
    public function redirect(string $url, int $code = 303): void
    {
        header('Location:' . $url, true, $code);
    }

}