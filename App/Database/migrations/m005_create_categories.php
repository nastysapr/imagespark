<?php

class CreateCategories
{
    public function up(): string
    {
        return "CREATE TABLE categories (
            id int AUTO_INCREMENT,
            alias varchar(255) NOT NULL,
            parent_id int,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE categories";
    }
}
