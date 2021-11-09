<?php

class Seeder
{
    protected PDO $dbh;

    public function __construct()
    {
        $connectParams = new Connect();
        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    public function seed(): void
    {
        $directory = __DIR__ . '/../database/seeds/';
        $iterator = new GlobIterator($directory . '*');

        foreach ($iterator as $fileName) {
            $className = str_replace('.php', '', $fileName->getFilename());
            (new $className())->seed();
        }
    }
}