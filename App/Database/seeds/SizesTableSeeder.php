<?php

use App\Models\Sizes;

class SizesTableSeeder
{
    public array $sizes = [
        'XXL', 'XL', 'L', 'M', 'S',
        '170', '160', '150', '140', '130', '120',
        '12', '11.5', '11', '10.5', '10', '9.5', '9', '8.5', '8', '7.5', ' 6.5', '6',
        '39', '38', '37', '36', '35', '34', '54', '52', '50', '48', '46', '44', '42', '40',
    ];

    public function seed(): void
    {
        foreach ($this->sizes as $size) {
            $item = new Sizes();
            $item->alias = $size;
            $item->save();
        }
    }
}