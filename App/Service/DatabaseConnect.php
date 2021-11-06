<?php

class DatabaseConnect
{
    public $dbh;
    private static $instance;

    function __construct()
    {
        $connectParams = new Connect();

        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnect();
        }
        return self::$instance->dbh;
    }

}