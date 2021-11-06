<?php

class MainController extends Controller
{
    /**
     * отображает главную страницу
     */
    public function index(): void
    {
        $news = new News();
        $pageData['news'] = $news->getGroup(1, 3);

        $this->view->render('mainPage', $pageData);
    }
}