<?php

use App\Models\Categories;

class CategoriesTableSeeder
{
    public array $categories = ['блин', 'гамаши', 'защита', 'клюшка', 'коньки', 'костюм', 'крюк', 'куртка', 'ловушка', 'маска',
        'нагрудник', 'нижнее белье', 'одежда', 'перчатки', 'подтяжки', 'пояс', 'раковина', 'ремень', 'рукоятка',
        'сандали', 'сетка', 'стакан', 'сумка', 'трубка', 'трусы', 'форма', 'шлем', 'шнурки', 'шорты', 'щитки',];

    public function seed(): void
    {
        foreach ($this->categories as $category) {
            $item = new Categories();
            $item->alias = $category;
            $item->parent_id = 0;
            $item->save();
        }
    }
}