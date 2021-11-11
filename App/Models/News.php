<?php
namespace App\Models;

class News extends Model
{
    public string $table = 'news';
    public array $breadcrumbs = ['/news' => 'Список новостей'];
    public string $genitive = 'новости';
    public string $plural = 'новостей';
}