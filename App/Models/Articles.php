<?php
namespace App\Models;

class Articles extends Model
{
    public string $table = 'articles';
    public array $breadcrumbs = ['/articles' => 'Список статей'];
    public string $genitive = 'статьи';
    public string $plural = 'статей';
}