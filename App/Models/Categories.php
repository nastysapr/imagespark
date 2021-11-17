<?php

namespace App\Models;

class Categories extends Model
{
    public string $table = 'categories';

    public function addSubcategories(string $data): void
    {
        $categories = (new Categories())->findAll();
        $result = [];
        foreach ($categories as $category) {
            $result[] = $category->alias;
        }

        foreach ($categories as $category) {
            $pattern = '/(?P<category>[а-я\s]*' . $category->alias . '[а-я.\s]*)/iu';
            if (preg_match($pattern, $data, $matches)) {
                $matches['category'] = mb_strtolower(trim($matches['category'], " "));

                if (strcasecmp($matches['category'], $category->alias) && !in_array($matches['category'], $result)) {
                    $newCat = new Categories();
                    $newCat->alias = $matches['category'];
                    $newCat->parent_id = $category->id;
                    $newCat->save();
                    //$result[] = $newCat->alias;
                }

                break;
            }
        }
    }
}