<?php
//namespace App\Database\migrations;

class CreateUsers
{
    public function up(): string
    {
        return "CREATE TABLE users1 (
            id int AUTO_INCREMENT,
            login varchar(255) NOT NULL,
            full_name varchar(255) NOT NULL,
            birthday date NOT NULL,
            email varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            description varchar(255),
            role varchar(45) NOT NULL,   
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE users1";
    }
}
