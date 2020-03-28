<?php

$url = explode(".", parse_url("http://www.kemenparekraf.go.id/")["host"]);
print_r($url);


// if (preg_match("/{$c}/i", $a, $m)) {
//     echo var_dump($m);
// }
$new_str = "www.kemenparekraf.go.id";
$new_str = preg_replace(array("/\A(www.)+/","/(\.(go|sch|edu|org|com|id))*\z/"), '', $new_str);
echo $new_str."\n";

// $new_str = preg_replace("/\A(www.)/", '', $new_str);
// echo $new_str."\n";

?>