<?php

class MainController extends Controller
{
    /**
     * Отображает главную страницу
     */
    public function index(): void
    {
        $news = new News();
        $pageData['news'] = $news->findAll(1, 3, "date");

        $this->view->render('mainPage', $pageData);
    }
}