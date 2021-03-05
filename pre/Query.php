<?php
require_once __DIR__. '/Preprocessing.php';
require_once __DIR__. '/Database.php';

class Query {
    public function __construct()
    {
        $database = new Database();
        $this->con = $database->connect();
        $this->preprocessing = new Preprocessing();
    }
    
    public function queryExpansion(string $query)
    {
        $query = array_map(
            function($term) {
                $sql = "SELECT `gugus_kata` FROM tbl_tesaurus WHERE `kata` = $term;";
                return $this->con->query($sql)->fetch(PDO::FETCH_ASSOC)[0];
            },
            array_keys($this->preprocessing->tokenizing($query))
        );
        print_r($query);
    }
}