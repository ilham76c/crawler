<?php
$domain_extension = array("");
$url = explode(".", parse_url("http://www.kemenparekraf.go.id/")["host"]);
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