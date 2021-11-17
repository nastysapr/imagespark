<?php

use App\Models\Orientations;

class OrientationsTableSeeder
{
    public array $orientation = ['L', 'R'];

    public function seed(): void
    {
        foreach ($this->orientation as $value) {
            $item = new Orientations();
            $item->alias = $value;
            $item->save();
        }
    }
}