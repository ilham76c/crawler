<?php
$domain_extension = array("");
$url = explode(".", parse_url("http://www.kemenparekraf.go.id/")["host"]);

print_r($url);
$new_str = "www.kemenparekraf.edu.id";
$c = ".go.id";
// if (preg_match("/{$c}/i", $a, $m)) {
//     echo var_dump($m);
// }
$ar = array(".com");
$new_str = preg_replace("/(\.(go|sch|edu|org|com|id))*\z/", '', $new_str);
echo $new_str."\n";

$new_str = preg_replace("/\A(www.)/", '', $new_str);
echo $new_str."\n";

?>