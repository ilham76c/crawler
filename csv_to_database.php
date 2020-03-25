<?php
$file = fopen("crawl_data500.csv","r");

$count = 0;
while (! feof($file)) {
    print_r(fgetcsv($file));
    echo "\n".$count++;
}

fclose($file);
?>