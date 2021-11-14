<?php

use App\Service\ContentGenerator;

class NewsTableSeeder
{
    public function seed(int $count): void
    {
        new ContentGenerator('news', $count);
    }

}