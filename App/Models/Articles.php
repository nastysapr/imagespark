<?php

class Articles extends Model
{
    public string $folder = 'articles';
    public string $table = 'articles';
    public string $title;
    public string $author;
    public string $text;
    public string $date;
}