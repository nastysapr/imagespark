<?php

class m003_create_articles extends Migration
{
    public function up(): bool
    {
        $sql = "CREATE TABLE articles1 (
            id int AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            text TEXT NOT NULL,
            date date NOT NULL,
            author varchar(255) NOT NULL,
            PRIMARY KEY (id))";

        return $this->execute($sql);
    }

    public function down(): bool
    {
        $sql = "DROP TABLE articles1";

        return $this->execute($sql);
    }
}
