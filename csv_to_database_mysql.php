<?php

// if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
//     echo 'We don\'t have mysqli!!!';
// } else {
//     echo 'Phew we have it!';
// }

try {
    $dsn = 'mysql:host=127.0.0.1;dbname=db_search_engine';
    $username = 'root';
    $password = '';
    // $options = array(
    //     PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    // ); 

    $pdo = new PDO($dsn, $username, $password);
    echo "Connected successfully\n";

    $sql = "INSERT INTO tbl_dokumen (url, title, description, kategori) VALUES (:url, :title, :description, :kategori)";
    //$data = ['kemenpar','nativeindonesia','pesonatravel','twisata','visitingjogja'];
    $data = [
        'data-air terjun', 
        'data-batik', 
        'data-bukit', 
        'data-candi', 
        'data-danau', 
        'data-gua', 
        'data-gunung',
        'data-kebun',
        'data-kuliner',
        'data-pantai',
        'data-waduk',
        'data-wahana'
    ];
    foreach ($data as $value) {
        $file = fopen("./data/kategori/{$value}.csv","r") or die ("Can't open file!!");
        insert($pdo, $sql, $file);    
        fclose($file);
    }
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function insert($pdo, $sql, $file) {
    // $count = 1;
    while ($lines = fgetcsv($file)) {    
        // echo $count."\t".$lines[0]."\n".$lines[1]."\n".$lines[2];
        // $count++;
        if ($lines[0] != '' && $lines[1] != '' && $lines[2] != '') {
            # code...
            $data = [
                'url' => $lines[0],
                'title' => $lines[1],
                'description' => $lines[2],
                'kategori' => $lines[3],
            ];
            $pdo->prepare($sql)->execute($data);
            // $count++;
        }
        
        // if ($count > 50) {
        //     break;
        // }        
    }
}
?>