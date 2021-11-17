<?php

use App\Models\Brands;

class BrandsTableSeeder
{
    public array $brands = ['BAUER', 'CCM', 'Easton', 'Graf', 'GRIT', 'Jofa', 'KOHO', 'Mad Guy',
        'Montreal', 'NBH', 'OAKLEY', 'OXDOG', 'Pallas', 'RBK', 'Reebok', 'SSM', 'Salming', 'Sher-Wood',
        'TPS', 'Tackla', 'Torspo', 'Vegum', 'Viking', 'Wall', 'TAC'];

    public function seed(): void
    {
        foreach ($this->brands as $brand) {
            $item = new Brands();
            $item->alias = $brand;
            $item->save();
        }
    }

}