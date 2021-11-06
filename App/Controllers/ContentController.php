<?php

/**
 * Контроллер для работы со статьями и новостями
 */
class ContentController extends Controller
{
    public string $viewIndex = 'content/index';
    public string $viewDetail = 'content/reader';
    public string $viewRedactor = 'content/redactor';

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->model = ucfirst($this->section);
    }

    /**
     * Отображает перечень новостей/статей в разделе новостей/статей
     */
    public function index(): void
    {
        $items = new $this->model();
        $where = '';

        if ($this->model === 'News') {
            $where = "date";
        }

        $offset = $this->request->get('page', 1);

        $pageData['items'] = $items->findAll($offset, $this->limit, $where);
        $pageData['model'] = $this->model;

        $pager = new Pager($this->limit, $items->count());
        $pager->setButtons();
        $pageData['pager'] = $pager;

        $this->view->render($this->viewIndex, $pageData);
    }
}