<?php

class m001_create_users extends Migration
{
    public function up(): bool
    {
        $sql = "CREATE TABLE users1 (
            id int AUTO_INCREMENT,
            login varchar(255) NOT NULL,
            full_name varchar(255) NOT NULL,
            birthday date NOT NULL,
            email varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            description varchar(255),
            role varchar(45) NOT NULL,   
            PRIMARY KEY (id))";

       return $this->execute($sql);
    }

    public function down(): bool
    {
        $sql = "DROP TABLE users1";

        return $this->execute($sql);
    }

}