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
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ); 

    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Connected successfully\n";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$sql = "INSERT INTO tbl_dokumen (url, title, description) VALUES (:url, :title, :description)";

$file = fopen("crawl_data500.csv","r") or die ("Can't open file!!");
$count = 1;
while ($lines = fgetcsv($file)) {
    
    // echo $count."\t".$lines[0]."\n".$lines[1]."\n".$lines[2];
    // $count++;

    $data = [
        'url' => $lines[0],
        'title' => $lines[1],
        'description' => $lines[2],
    ];
    $pdo->prepare($sql)->execute($data);
}

fclose($file);

?>