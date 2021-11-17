<?php

use App\Models\Colours;

class ColoursTableSeeder
{
    public array $colours = [
        [
            'alias' => 'красный',
            'alias_english' => 'red',
            'alias_short' => 'крас',
            'alias_feminitive' => 'красная',
            'alias_plural' => 'красные'
        ],
        [
            'alias' => 'черный',
            'alias_english' => 'black',
            'alias_short' => 'черн',
            'alias_feminitive' => 'черная',
            'alias_plural' => 'черные'
        ],
        [
            'alias' => 'белый',
            'alias_english' => 'white',
            'alias_short' => 'бел',
            'alias_feminitive' => 'белая',
            'alias_plural' => 'белые'
        ],
        [
            'alias' => 'синий',
            'alias_english' => 'blue',
            'alias_short' => 'син',
            'alias_feminitive' => 'синяя',
            'alias_plural' => 'синие'
        ],
        [
            'alias' => 'золото',
            'alias_english' => 'golden',
            'alias_short' => 'зол',
            'alias_feminitive' => 'золотая',
            'alias_plural' => 'золотые'
        ],
        [
            'alias' => 'серый',
            'alias_english' => 'gray',
            'alias_short' => 'сер',
            'alias_feminitive' => 'серая',
            'alias_plural' => 'серые'
        ],
        [
            'alias' => 'разноцветный',
        ]

    ];

    public function seed(): void
    {
        foreach ($this->colours as $colour) {
            $item = new Colours();
            foreach ($colour as $key => $value) {
                $item->$key = $value;
            }

            $item->save();
        }
    }
}