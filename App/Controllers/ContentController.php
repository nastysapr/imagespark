<?php
namespace App\Controllers;

use App\Service\Pager;

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
        $this->model = 'App\\Models\\' . ucfirst($this->section);
    }

    /**
     * Отображает перечень новостей/статей в разделе новостей/статей
     */
    public function index(): void
    {
        $items = new $this->model();
        $filter = '';

        if ($this->model === 'App\Models\News') {
            $filter = "date";
        }

        $offset = $this->request->get('page', 1);

        $pageData['items'] = $items->findAll($offset, $this->limit, $filter);
        $pageData['model'] = $this->model;

        $pager = null;
        if ($items->count()) {
            $pager = new Pager($this->limit, $items->count());
            $pager->setButtons();
        }

        $pageData['pager'] = $pager;

        $this->view->render($this->viewIndex, $pageData, ['' => 'Cписок ' . $items->plural]);
    }
}