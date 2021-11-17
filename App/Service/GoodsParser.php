<?php

namespace App\Service;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Colours;
use App\Models\Goods;
use App\Models\Orientations;
use App\Models\Sizes;

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
        $seeder->seed('orientations');

        //Получение подкатегорий
        $handle = fopen($this->sourceFile, 'r');
        $categories = new Categories();

        while (($data = fgetcsv($handle)) !== FALSE) {
            $data = current(str_replace('"', '', $data));
            $categories->addSubcategories($data);
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

        $regColoursString = implode('|', array_map(function ($value) {
            return $value['alias'];
        }, $regColours));
        $regColoursString = str_replace('/', '\/', $regColoursString);

        $categories = (new Categories())->findAll();
        $categoriesList = [];
        foreach ($categories as $category) {
            $categoriesList[] = ['alias' => $category->alias, 'id' => $category->id, 'parentId' => $category->parent_id];
        }

        $orientation = (new Orientations())->findAll();

        $sizes = (new Sizes())->findAll();

        $handle = fopen('test.csv', 'r');
        $result = fopen('result.csv', 'w');

        while (($data = fgetcsv($handle)) !== FALSE) {
            $data = current(str_replace('"', '', $data));
            $data = str_replace('  ', ' ', $data);
            $item = new Goods;
//            $item->colour_id = null;
//            $item->brand_id = null;
//var_dump($item);
            //Поиск цвета
            $pattern = '/(?P<colour>[а-я\.-]+\/[а-я\.-]+\/*[а-я\.-]*)/u';
            if (preg_match($pattern, $data, $matches)) {
                $key = array_search('разноцветный', array_map(function ($value) {
                    return $value['alias'];
                }, $regColours));
                $item->colour_id = $regColours[$key]['id'];
            } else {
                $pattern = '/\s(?P<colour>' . $regColoursString . ')[\s|.|$]/u';
                if (preg_match($pattern, $data, $matches)) {
                    $key = array_search($matches['colour'], array_map(function ($value) {
                        return $value['alias'];
                    }, $regColours));
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
            //$pattern = '/(?P<category>' . $regCategoriesString . ')/iu';
            $pattern = '/(?P<category>(\s*[а-я]+\s*){1,})/iu';
            if (preg_match($pattern, $data, $matches)) {
                $matches['category'] = trim($matches['category'], ' ');

                $key = array_search(mb_strtolower($matches['category']), array_map(function ($value) {
                    return $value['alias'];
                }, $categoriesList));

                if ($key !== 'false') {
                    //var_dump($categoriesList[$key]['id']);
                    $item->category_id = $categoriesList[$key]['id'];
                    $data = str_ireplace($matches['category'], '', $data);
                    //$data = preg_replace($pattern, '', $data);

                    //заполнение ориентации для клюшек
                    $parentId = $categoriesList[$key]['parentId'];
                    $key = array_search('клюшка', array_map(function ($value) {
                        return $value['alias'];
                    }, $categoriesList));
                    $needle = $categoriesList[$key]['id'];

                    if ($parentId == $needle || $item->category_id == $needle) {
                        //var_dump($needle);
                        $pattern = '/(?P<orientation>[L|R])$/';
                        if (preg_match($pattern, $data, $matches)) {
                            $key = array_search($matches['orientation'], array_map(function ($value) {
                                return $value->alias;
                            }, $orientation));
                            $item->orientation_id = ($orientation[$key])->id;
//var_dump($item->orientation_id);
                            $data = preg_replace($pattern, '', $data);
                        }
                    }

                    $data = trim($data, " ");
                }
            }

            //Поиск размера
            $pattern = '/-\s*(?P<size>[A-Z]+|[\d.,]+)\s*$/u';
            if (preg_match($pattern, $data, $matches)) {
                $key = array_search($matches['size'], array_map(function ($value) {
                    return $value->alias;
                }, $sizes));
//var_dump($sizes);
                if ($key) {
                    $item->size_id = ($sizes[$key])->id;
                }

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
            if ($item->category_id) {
                $item->save();
            } else {
                fputcsv($result, [$data], "\n");
            }
        }

        fclose($result);
        ini_set('auto_detect_line_endings', FALSE);
    }
}