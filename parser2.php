<?php

use App\Service\Migration;
use App\Service\Seeder;

require_once 'App/Autoloader.php';
Autoloader::register();

//(new Migration())->create('create_brands');
//(new Migration())->create('create_categories');
//(new Migration())->create('create_colours');
//(new Migration())->create('create_goods');

//(new Migration())->rollback(3);
//(new Migration())->migrate();
//(new Seeder())->seed('sizes');
//(new Seeder())->seed('orientations');
//(new \App\Service\GoodsParser())->prepareBase();
(new \App\Service\GoodsParser())->parseFile();

exit;
