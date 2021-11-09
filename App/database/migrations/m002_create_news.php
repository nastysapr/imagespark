<?php

class m002_create_news extends Migration
{
    public function up(): bool
    {
        $sql = "CREATE TABLE news1 (
            id int AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            text TEXT NOT NULL,
            date date NOT NULL,
            author varchar(255) NOT NULL,
            INDEX (date),
            PRIMARY KEY (id))";

        return $this->execute($sql);
    }

    public function down(): bool
    {
        $sql = "DROP TABLE news1";

        return $this->execute($sql);
    }
}
