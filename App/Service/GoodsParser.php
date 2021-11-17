<?php

namespace App\Service;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Colours;
use App\Models\Goods;

class GoodsParser
{
    public string $sourceFile = 'test.csv';

    public function prepareBase(): void
    {
        (new Migration())->migrate();
        $seeder = new Seeder();
        $seeder->seed('categories');
        $seeder->seed('colours');
        $seeder->seed('brands');
        $seeder->seed('sizes');

        //Получение подкатегорий и составных цветов из файла
        $handle = fopen($this->sourceFile, 'r');
        $categories = new Categories();
        $colours = new Colours();

        while (($data = fgetcsv($handle)) !== FALSE) {
            $data = current(str_replace('"', '', $data));
            $categories->addSubcategories($data);
            //$colours->addMultiColours($data);
        }
    }

    public function parseFile(): void
    {
        ini_set('auto_detect_line_endings', TRUE);

        $brands = (new Brands())->findAll();
        $regBrands = [];
        foreach ($brands as $brand) {
            $regBrands[] = ['id' => $brand->id, 'alias' => $brand->alias];
        }

        $regBrandsString = implode('|', array_map(function ($value) {
            return $value['alias'];
        }, $regBrands));

        $colours = (new Colours())->findAll();
        $regColours = [];
        foreach ($colours as $colour) {
            foreach ($colour->attributes as $attribute => $value) {
                if ($attribute === 'id' || !$value) {
                    continue;
                }
                $regColours[] = ['id' => $colour->id, 'alias' => $value];
            }
        }

        ksort($regColours);
        $regColoursString = implode('|', array_map(function ($value) {
            return $value['alias'];
        }, $regColours));
        $regColoursString = str_replace('/', '\/', $regColoursString);

        $categories = (new Categories())->findAll();
        $categoriesList = [];
        foreach ($categories as $category) {
            $categoriesList[] = ['alias' => $category->alias, 'id' => $category->id, 'parentId' => $category->parent_id];
        }

        $regCategoriesString = implode('|', array_map(function ($value) {
            return $value['alias'];
        }, $categoriesList));

        $handle = fopen('test.csv', 'r');
        $result = fopen('result.csv', 'w');

        while (($data = fgetcsv($handle)) !== FALSE) {
            $data = current(str_replace('"', '', $data));
            $data = str_replace('  ', ' ', $data);
            $item = new Goods;
            $item->colour_id = 0;
            $item->brand_id = 0;

            //Поиск цвета
            $pattern = '/(?P<colour>[а-я\.-]+\/[а-я\.-]+\/*[а-я\.-]*)/u';
            if (preg_match($pattern, $data, $matches)) {
                $item->colour_id = array_search('разноцветный', array_map(function ($value) {
                    return $value['alias'];}, $regColours));
            } else {
                $pattern = '/\s(?P<colour>' . $regColoursString . ')[\s|.|$]/u';
                if (preg_match($pattern, $data, $matches)) {
                    $key = array_search($matches['colour'], array_map(function ($value) {
                        return $value['alias'];                    }, $regColours));
                    if ($key !== 'false') {
                        $item->colour_id = $regColours[$key]['id'];
                        $data = str_ireplace($matches['colour'], '', $data);
                    }
                }
            }

            //Поиск артикля
            $pattern = '/(?P<vendorCode>\w{0,}\d{7,}\w{0,})/u';

            if (preg_match($pattern, $data, $matches)) {
                $item->vendor_code = $matches['vendorCode'];
                $data = preg_replace($pattern, '', $data);
            }

            //Поиск производителя
            $pattern = '/(?P<brand>' . $regBrandsString . ')/iu';
            if (preg_match($pattern, $data, $matches)) {
                $key = array_search($matches['brand'], array_map(function ($value) {
                    return $value['alias'];
                }, $regBrands));
                if ($key !== 'false') {
                    $item->brand_id = $regBrands[$key]['id'];
                    $data = str_ireplace($matches['brand'], '', $data);
                }
            }

            //Поиск категории
            $pattern = '/(?P<category>' . $regCategoriesString . ')/iu';
            if (preg_match($pattern, $data, $matches)) {
                $matches['category'] = mb_strtolower(trim($matches['category'], ' '));
                $key = array_search($matches['category'], array_map(function ($value) {
                    return $value['alias'];
                }, $categoriesList));

                if ($key !== 'false') {
                    $item->catalog_id = $categoriesList[$key]['id'];
                    //$data = str_ireplace($matches['category'], '', $data);
                    $data = preg_replace($pattern, '', $data);
                    //заполнение ориентации для клюшек
                    $parentId = $categoriesList[$key]['parentId'];
                    $needle = array_search('клюшка', array_map(function ($value) {
                        return $value['alias'];
                    }, $categoriesList));
                    if ($parentId == $needle || $item->catalog_id == $needle) {
                        $pattern = '/(?P<orientation>L|R)$/';
                        if (preg_match($pattern, $data, $matches)) {
                            $item->orientation = $matches['orientation'];
                            $data = preg_replace($pattern, '', $data);
                        }
                    }
                    $data = trim($data, " ");
                }
            }

            //Поиск размера
            $pattern = '/-\s*(?P<size>[A-Z]+|[\d.,]+)\s*$/u';
            if (preg_match($pattern, $data, $matches)) {
                $item->size = $matches['size'];
                $data = preg_replace($pattern, '', $data);
                $data = trim($data, " ");
            }

            //Расчет модели товаров
            $data = preg_replace('/\s*-$/', '', $data);
            $data = preg_replace('/^-/', '', $data);
            $data = preg_replace('/[\(\)]/', '', $data);
            $data = preg_replace('/s{2,}/', ' ', $data);
            $data = trim($data, ' ');

            $item->model = $data;
//var_dump($item);
            if ($item->catalog_id) {
  //              $item->save();
            } else {
                fputcsv($result, [$data], "\n");
            }
        }

        fclose($result);
        ini_set('auto_detect_line_endings', FALSE);
    }
}