<?php

class Database {
    public function connect() : object
    {
        try {
            $dsn = 'mysql:host=127.0.0.1;dbname=db_search_engine';
            $username = 'root';
            $password = '';
            $pdo = new PDO($dsn, $username, $password);
            echo "Connected successfully\n";
            return $pdo;
        }
        catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}