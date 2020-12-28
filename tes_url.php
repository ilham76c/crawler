<?php
$headers = @get_headers('https://github.com/');
print_r($headers);
// echo strpos($headers[0],'OK');
if(strpos($headers[0],'200')) {
    echo 'true';    
}else {
    echo 'false';
}

?>