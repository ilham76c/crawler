<?php

function connect()
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

function createQuery(string $str)
{
    $str = array_map(
        function($value) {
            return trim($value);
        },
        explode(',',$str)
    );
    $query = '';
    foreach ($str as $key => $value) {
        $query .= 
        " 
        title like '%$value%' and  description like '%$value%' ";        
        
        if (!($key === array_key_last($str))) {
            $query .= "or";
        }
    }
    
    return $query;
}
function recall(string $query, string $tbl)
{
    $con = connect();
    $sql = "SELECT COUNT(url) FROM $tbl WHERE $query";
    //echo $sql;
    $result = $con->query($sql)->fetchColumn();
    echo "\n $result \n";

}
function createQuery2(string $query1, string $query2)
{
    $query1 = array_map(
        function($value) {
            return trim($value);
        },
        explode(',',$query1)
    );
    $query2 = array_map(
        function($value) {
            return trim($value);
        },
        explode(',',$query2)
    );
    $sql = "";
    
    foreach ($query1 as $value1) {
        foreach ($query2 as $value2) {
            $sql .= 
            " 
            title like '%$value1%' and  description like '%$value2%' or
            title like '%$value1%' and  title like '%$value2%' or
            description like '%$value1%' and  description like '%$value2%' or
            description like '%$value1%' and title like '%$value2%' or 
            ";
        }
    }    
    foreach ($query2 as $key2 => $value2) {
        foreach ($query1 as $key1 => $value1) {
            $sql .= 
            " 
            title like '%$value2%' and  description like '%$value1%' or
            title like '%$value2%' and  title like '%$value1%' or
            description like '%$value2%' and  description like '%$value1%' or
            description like '%$value2%' and title like '%$value1%' ";
            if ($key2 === array_key_last($query2) && $key1 === array_key_last($query1)) {
                $sql .= "";
            }
            else {
                $sql .= "or";
            }
        }        
    }
    return $sql;
}
function main()
{
    echo "Tesaurus 1 : ";
    $t1 = trim(fgets(STDIN));
    echo $t1."\n";
    echo "Tesaurus 2 : ";
    $t2 = trim(fgets(STDIN));
    echo $t2."\n";
    recall(createQuery2($t1, $t2), "tbl_dokumen");
    recall(createQuery2($t1, $t2), "tbl_hasil");
    echo "\n";
    //recall(createQuery("$t1,$t2"), "tbl_dokumen");
    //recall(createQuery("$t1,$t2"), "tbl_hasil");
}
main();
#formatString('rekreasi,  wisata  , liburan');

#title like '%sarana%' or 

#description like '%wahana%' or 

// 'permainan, 	mainan,atraksi,pertunjukan,tontonan
// tempat, 	letak,lokasi,posisi,situs';
// title like '%tempat%' and  description like '%permainan%';
// title like '%tempat%' and  title like '%permainan%';
// description like '%tempat%' and  description like '%permainan%';
// description like '%tempat%' and title like '%permainan%';

