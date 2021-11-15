<?php

class CreateBrands
{
    public function up(): string
    {
        return "CREATE TABLE brands (
            id int AUTO_INCREMENT,
            alias varchar(255) NOT NULL,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE brands";
    }
}
