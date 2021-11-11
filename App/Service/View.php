<?php
namespace App\Service;

class View
{
    public function render(string $view, array $data = null, array $breadcrumbs = null)
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
        $path = Config::get()->value('paths.root') . Config::get()->value('paths.views');
        include $path . '404.php';
    }

}