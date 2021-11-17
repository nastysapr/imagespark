<?php

namespace App\Models;

class Colours extends Model
{
    public string $table = 'colours';

    public function addMultiColours(string $data): void
    {
        //$colours = new Colours()
        $pattern = '/(?P<colour>[а-я\.-]+\/[а-я\.-]+\/*[а-я\.-]*)/u';
        if (preg_match($pattern, $data, $matches)) {
            $colour = (new Colours());
            $result = $colour->findAll(0, 0, $matches['colour'], 'alias');
            if (!$result) {
                $colour->alias = $matches['colour'];
                $colour->save();
            }
        }
    }
}