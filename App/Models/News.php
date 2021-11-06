<?php

class News extends Model
{
    public string $folder = 'news';
    public string $table = 'news';
    public string $title;
    public string $author;
    public string $text;
    public string $date;
}