<?php

class View
{
    public function render($view, $data = [])
    {
        $pageData = [];
        $pageData = $data;
        $pageData['app_name'] = Config::get()->value('app_name');

        $auth = new Authorization();

        $path = Config::get()->value('paths.root') . Config::get()->value('paths.views');

        include $path . $view . '.php';
    }

    public function notFound()
    {
        echo '<a href="/"><img src="/images/404.png"></a>';
    }

    public function forbidden()
    {
        $this->render('layout/header');
        echo "<h1>Доступ запрещен</h1>";
        $this->render('layout/footer');
    }
}