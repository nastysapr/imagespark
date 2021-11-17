<?php

namespace App\Controllers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Colours;
use App\Models\Goods;
use App\Models\Orientations;
use App\Models\Sizes;

class GoodsController extends Controller
{
    public string $model = 'App\Models\Goods';
    public string $section = 'goods';

    public function index(): void
    {
        $pageData = [];
        $pageData['result'] = [];

        $pageData['categories'] = (new Categories())->findAll();
        $pageData['colours'] = (new Colours())->findAll();
        $pageData['brands'] = (new Brands())->findAll();
        $pageData['sizes'] = (new Sizes())->findAll();
        $pageData['orientation'] = (new Orientations())->findAll();

        $filterParams = $this->request->getFilterParams();
        if ($filterParams) {
            $pageData['filters'] = $filterParams;

            foreach ($filterParams as $key => $value) {
                if (!$value) {
                    unset($filterParams[$key]);
                }
            }
            unset($filterParams['search']);

            $goods = (new Goods)->findAll(0, 0, 'goods', $filterParams);

            foreach ($goods as $good) {
                if ($good->category_id) {
                    $good->category = $this->aliasByObjectPK($pageData['categories'], $good->category_id);
                }

                if ($good->brand_id) {
                    $good->brand = $this->aliasByObjectPK($pageData['brands'], $good->brand_id);
                }

                if ($good->colour_id) {
                    $good->colour = $this->aliasByObjectPK($pageData['colours'],$good->colour_id);
                }

                if ($good->size_id) {
                    $good->size = $this->aliasByObjectPK($pageData['sizes'], $good->size_id);
                }

                if ($good->orientation_id) {
                    $good->orientation = $this->aliasByObjectPK($pageData['orientation'], $good->orientation_id);
                }

                $pageData['result'][] = $good;
            }
        }

        $this->view->render('goods/index', $pageData, ['' => 'Cписок товаров']);
    }

    public function aliasByObjectPK(array $array, int $primaryKey): ?string
    {
        foreach ($array as $item) {
            if ($item->id == $primaryKey) {
                return $item->alias;
            }
        }
        return null;
    }
}