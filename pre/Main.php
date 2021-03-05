<?php

include 'Query.php';
include 'Recall.php';

$recall = new Recall();
$query = new Query();

while (true) {
    echo "Query Pencarian: ";
    $query = trim(fgets(STDIN));
    $recall->getRecall($query);
    echo "\n";
}
