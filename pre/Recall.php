<?php
require_once __DIR__. '/Preprocessing.php';
require_once __DIR__. '/Database.php';

class Recall {

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->connect();
        $this->preprocessing = new Preprocessing();
    }
    
    public function createConditionQuery(string $query) : string
    {
        $query = $this->preprocessing->tokenizing($this->preprocessing->stopwordRemoval($query));
        $condition = "";
        $idx = 1;
        foreach ($query as $value) {
            $condition .= "CONCAT(title, ' ', description) LIKE '%$value%'";
            if (count($query) != $idx) {
                $condition .=  " AND ";
            }            
            $idx++;
        }
        return $condition;
    }    

    public function getRelevanDatabase(string $sentence)
    {
        $sql = "SELECT `url`, CONCAT(title, ' ', description) AS `isi` FROM tbl_dokumen  WHERE ".$this->createConditionQuery($sentence).";";
        print_r($this->con->query($sql)->fetchAll());
        return count($this->con->query($sql)->fetchAll());
    }

    public function getRelevanHasil(string $sentence)
    {
        $sql = "SELECT `url`, CONCAT(title, ' ', description) AS `isi` FROM tbl_hasil  WHERE ".$this->createConditionQuery($sentence).";";
        return count($this->con->query($sql)->fetchAll());
    }
    public function getRecall(string $sentence)
    {        
        echo "Dokumen relevan di database : " . $this->getRelevanDatabase($sentence) . PHP_EOL;
        echo "Dokumen relevan di hasil    : " . $this->getRelevanHasil($sentence) . PHP_EOL;
        //return $this->con->query($sql)->fetchAll();
    }
}