<?php

class DatabaseConnector
{
    private PDO $dbh;

    public function __construct(array $env)
    {
        $dsn = "mysql:host={$env['hostname']};dbname={$env['database']}";
        $this->dbh = new PDO($dsn, $env['username'], $env['password']);
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }
}
