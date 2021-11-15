<?php

class CreateColours
{
    public function up(): string
    {
        return "CREATE TABLE colours (
            id int AUTO_INCREMENT,
            alias varchar(255) NOT NULL,
            alias_english varchar(255),
            alias_short varchar(255),
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE colours";
    }
}
