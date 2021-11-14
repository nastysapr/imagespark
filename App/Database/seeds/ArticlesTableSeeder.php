<?php

use App\Service\ContentGenerator;

class ArticlesTableSeeder
{
    public function seed(int $count): void
    {
        new ContentGenerator('articles', $count);
    }

}