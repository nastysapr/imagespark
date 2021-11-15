<?php

class CreateArticles
{
    public function up(): string
    {
        return "CREATE TABLE articles1 (
            id int AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            text TEXT NOT NULL,
            date date NOT NULL,
            author varchar(255) NOT NULL,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE articles1";
    }
}
