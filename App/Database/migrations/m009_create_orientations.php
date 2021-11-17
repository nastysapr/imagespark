<?php

class CreateOrientations
{
    public function up(): string
    {
        return "CREATE TABLE orientations (
            id int AUTO_INCREMENT,
            alias char NOT NULL,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE orientations";
    }
}

