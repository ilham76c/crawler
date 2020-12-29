<?php
$domain_extension = array("");
$url = explode(".", parse_url("http://www.kemenparekraf.go.id/")["host"]);
<<<<<<< HEAD
print_r($url);

// if (preg_match("/{$c}/i", $a, $m)) {
//     echo var_dump($m);
// }
$seed = array("http://www.kemenparekraf.go.id/","https://www.twisata.com/","https://visitingjogja.com/","https://pesona.travel/");
foreach ($seed as $url) {
    $new_str = preg_replace(array("/\A(www.)+/","/(\.(go|sch|edu|org|com|id))*\z/"), '', parse_url($url)["host"]);
    echo $new_str."\n";
}


// $new_str = preg_replace("/\A(www.)/", '', $new_str);
// echo $new_str."\n";

//$param = "ì§ - DIY ê´ê´ ì ë³´ í¬í¸";
$ch = 'namun tak ada jalan';
//$ch = 'ì§ - DIY ê´ê´ ì ë³´ í¬í¸';
    if (ctype_alpha($ch)) {
        echo 'Accept';
    } else {
        echo 'Reject';
    }
=======

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
>>>>>>> 2285af54b3508b91b34cd219f1163efa163f09a6
