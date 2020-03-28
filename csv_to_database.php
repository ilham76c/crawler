<?php

// if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
//     echo 'We don\'t have mysqli!!!';
// } else {
//     echo 'Phew we have it!';
// }

try {
    $dsn = 'mysql:host=127.0.0.1;dbname=test';
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
$file = fopen("crawl_data500.csv","r");

$count = 0;
while (! feof($file)) {
    $arr = fgetcsv($file);
    echo $arr[0]."\n";
}

fclose($file);
?>