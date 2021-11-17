<?php

use App\Models\Colours;
use App\Models\Goods;
use App\Service\Migration;
use App\Service\Seeder;
use App\Models\Brands;
use App\Models\Categories;

require_once 'App/Autoloader.php';
Autoloader::register();

//(new Migration())->create('create_brands');
//(new Migration())->create('create_categories');
//(new Migration())->create('create_colours');
//(new Migration())->create('create_goods');

//(new Migration())->migrate();
//(new Migration())->rollback(3);
//(new Seeder())->seed('categories');
//(new Seeder())->seed('colours');

//(new Migration())->rollback(2);
//(new \App\Service\GoodsParser())->prepareBase();
(new \App\Service\GoodsParser())->parseFile();
exit;
ini_set('auto_detect_line_endings', TRUE);

$brands = (new Brands())->findAll();
//var_dump($brands);
$regBrands = [];
foreach ($brands as $brand) {
    $regBrands[$brand->id] = $brand->alias;
}
//var_dump($regBrands);
$regBrandsString = implode('|', $regBrands);
//var_dump($regBrandsString);
//exit;
$categories = new Categories();
$colours = (new Colours())->findAll();
//$catList = $categories->findAll();

//прошла по файлу, получила подкатегории
//$handle = fopen('test.csv', 'r');
//while (($data = fgetcsv($handle)) !== FALSE) {
//    $data = current(str_replace('"', '', $data));
//    $categories->addSubcategories($data);
//}

//прошла по файлу, получила составные цвета
//$handle = fopen('test.csv', 'r');
//$colours = [];
//while (($data = fgetcsv($handle)) !== FALSE) {
//    $data = current(str_replace('"', '', $data));
//    $pattern = '/(?P<colour>[а-я\.-]+\/[а-я\.-]+\/*[а-я\.-]*)/u';
//    if (preg_match($pattern, $data, $matches)) {
//        if(!in_array($matches['colour'], $colours))
//        $colours[] = $matches['colour'];
//    }
//}
//
//foreach ($colours as $key => $value) {
//    $colour = new Colours();
//    $colour->alias = $value;
//    $colour->save();
//}
//(new Migration())->create('create_sizes');
exit;

$handle = fopen('test.csv', 'r');
$result = fopen('result.csv', 'w');

while (($data = fgetcsv($handle)) !== FALSE) {
    $data = current(str_replace('"', '', $data));
    $data = str_replace('  ', ' ', $data);
    $item = new Goods;
    //поиск цвета
    foreach ($colours as $colour) {
        foreach ($colour->attributes as $key => $value) {
            if ($key === 'id') {
                continue;
            }

            $pattern = '/\s(?P<colour>' . preg_quote($value, '/') . ')[\s|\$]/iu';
            if ($value && preg_match($pattern, $data, $matches)) {
                $item->colour_id = $colour->attributes['id'];
                $data = preg_replace($pattern, '', $data);
//                $data = str_replace($matches['colour'], '', $data);
                break;
            }
        }
    }
//поиск артикля
    $pattern = '/(?P<vendorCode>\w{0,}\d{7,}\w{0,})/u';

    if (preg_match($pattern, $data, $matches)) {
        $item->vendor_code = $matches['vendorCode'];
//        $data = str_replace($matches['vendorCode'], '', $data);
        $data = preg_replace($pattern, '', $data);
    }
    //поиск производителя
    $pattern = '/(?P<brand>' . $regBrandsString . ')/iu';
    if (preg_match($pattern, $data, $matches)) {
//TODO brand_id
        $key = array_search($matches['brand'], $regBrands);
        if ($key) {
            $item->brand = $key;
            $data = preg_replace($pattern, '', $data);
//            $data = str_ireplace($matches['brand'], '', $data);
            $data = trim($data, " ");
            //var_dump($data);
        }
    }
//поиск категории
    $pattern = '/(?P<category>[а-яА-Я\s]+)/iu';
    if (preg_match($pattern, $data, $matches)) {
        $category = (new Categories())->findAll(0, 0, mb_strtolower(trim($matches['category'], " ")), 'alias');
        if (!empty($category)) {
            $category = current($category);
            $item->catalog_id = $category->id;
            $data = str_ireplace(trim($matches['category'], " "), '', $data);

            //заполнение ориентации для клюшек
            if ($category->parent_id) {
                $parent = (new Categories())->findByPK($category->parent_id);
                if ($parent->alias === "клюшка") {
                    $pattern = '/-\s*(?P<orientation>L|R)$/u';
                    if (preg_match($pattern, $data, $matches)) {
                        $item->orientation = $matches['orientation'];
                        $data = preg_replace($pattern, '', $data);
//                        $data = str_ireplace($matches['orientation'], '', $data);
                    }
                }
            }
            $data = trim($data, " ");
        }
    }

//поиск размера
    $pattern = '/-\s*(?P<size>[A-Z]+|[\d.,]+)\s*$/u';
    if (preg_match($pattern, $data, $matches)) {
        $item->size = $matches['size'];
        $data = preg_replace($pattern, '', $data);
//        $data = str_ireplace($matches['size'], '', $data);
        $data = trim($data, " ");
    }

    //Расчет модели товаров
    $data = preg_replace('/\s*-$/', '', $data);
    $data = preg_replace('/^-/', '', $data);
    $data = preg_replace('/\(\)/', '', $data);
    $data = preg_replace('/s{2,}/', ' ', $data);
    $data = trim($data, ' ');
//    var_dump($data);
    $item->model = $data;

    if ($item->catalog_id) {
        $item->save();
    } else {
        fputcsv($result, [$data], "\n");
    }
}
fclose($result);
ini_set('auto_detect_line_endings', FALSE);

