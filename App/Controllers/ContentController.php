<?php

/**
 * Контроллер для работы со статьями и новостями
 */
class ContentController extends Controller
{
    /**
     * Отображает перечень новостей/статей в разделе новостей/статей
     */
    public function index(): void
    {
        $items = new $this->model();

        $offset = $this->request->get('page', 1);

        $pageData['items'] = $items->getGroup($offset, $this->limit);

        $pager = new Pager($this->limit, $items->getCount());
        $pager->setButtons();
        $pageData['pager'] = $pager;

        $this->view->render($this->viewIndex, $pageData);
    }
}