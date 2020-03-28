<?php
$seed = array("http://www.kemenparekraf.go.id/","https://www.twisata.com/","https://visitingjogja.com/","https://pesona.travel/");
$telah_dikunjungi = array();
$sedang_dikunjungi = array();
$akan_dikunjungi = array();

function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}
function getTitle($url) {
	$html = file_get_contents_curl($url);
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	$nodes = $doc->getElementsByTagName("title");
	if ($nodes->length > 0) {
		return $nodes->item(0)->nodeValue;
	}
	return null;
}
function getMeta($url) {
	$html = file_get_contents_curl($url);
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	$metas = $doc->getElementsByTagName('meta');

	$arrayMeta = array();
	for ($i=0; $i < $metas->length; $i++) { 
		$meta = $metas->item($i);
		if ($meta->getAttribute('name') == 'description'){
			return $meta->getAttribute('content');			
		}	
	}
	return null;
}
function getMetaDescription($url) {
	@$tags = get_meta_tags($url);
	if (!empty($tags['description'])) {
		return $tags['description'];
	}
	return null;
}
function parseUrl($url) {
	$domain = parse_url($url);	
	return empty($domain['path']) ? $domain['scheme'].'://'.$domain['host'].'/' : $domain['scheme'].'://'.$domain['host'].$domain['path'];	
}
/*function parseHost($url) {	
	$domain = parse_url($url);
	if (!empty($domain['host'])) {
		$parse_host = explode('.', $domain['host']);
		if (sizeof($parse_host) == 3) {
			return $parse_host[1].'.'.$parse_host[2];
		}
		elseif (sizeof($parse_host) == 4) {
			return $parse_host[1].'.'.$parse_host[2].'.'.$parse_host[3];
		}
		else {
			return $domain['host'];
		}
		//return sizeof($parse_host) == 3 ? $parse_host[1].'.'.$parse_host[2] : $domain['host'];
	}	
	return null;
}*/
function parseHost($url) {	
	$domain = parse_url($url);
	// if (!empty($domain['host'])) {
	// 	$parse_host = explode('.', $domain['host']);
	// 	return sizeof($parse_host) == 3 ? $parse_host[1].'.'.$parse_host[2] : $domain['host'];
	// }	
	// return null;
	return preg_replace(array("/\A(www.)+/","/(\.(go|sch|edu|org|com|id))*\z/"), '', $domain["host"]);
}
function scrapeUrl($url,$host) {
	//global $seed, $buffer, $frontier;
	$list_url = array();
	
	$dom = new DOMDocument();
	@$dom->loadHTMLFile($url);

	$linkTags = $dom->getElementsByTagName('a');	
	
	if ($linkTags) {
	    foreach ($linkTags as $tag) {	 
	    	# cek tag 'href' null atau tidak               
	        if (!empty($tag->attributes->getNamedItem('href')->value)) {
	        	$href = $tag->attributes->getNamedItem('href')->value;
	        	# cek isi tag 'href' adalah url valid
	        	if (filter_var($href,FILTER_VALIDATE_URL)) {
	        		# cek host dari 'href' dengan host url utama
	        		if (parseHost($href) == $host) {	        			
	        			$href = parseUrl($href);
	        			# cek apakah url dari tag 'href' sudah di ditemukan sebelumnya		
	        			if (!in_array($href, $list_url) && !in_array($href, $GLOBALS['telah_dikunjungi']) && !in_array($href, $GLOBALS['sedang_dikunjungi']) && !in_array($href, $GLOBALS['akan_dikunjungi'])) {
	        				$list_url[] = $href;
	        			}
	        		}	        						    
				}
	        }	        			
	    }		
	}	
	return $list_url;
}
function crawl($fp, $url, $host) {		
	array_push($GLOBALS['telah_dikunjungi'], $url);
	echo sizeof($GLOBALS['telah_dikunjungi']).' => '.$url.PHP_EOL;
	fputcsv($fp, array($url));
	$GLOBALS['akan_dikunjungi'] = array_merge($GLOBALS['akan_dikunjungi'], scrapeUrl($url, $host));	
	if (!empty($GLOBALS['sedang_dikunjungi'])) {				
		crawl($fp, array_shift($GLOBALS['sedang_dikunjungi']), $host);
	}
	if (!empty($GLOBALS['akan_dikunjungi'])) {
		$GLOBALS['sedang_dikunjungi'] = $GLOBALS['akan_dikunjungi'];
		$GLOBALS['akan_dikunjungi'] = array();
		crawl($fp, array_shift($GLOBALS['sedang_dikunjungi']), $host);	
	}
}
function crawl2($fp, $url, $host) {
	$GLOBALS['telah_dikunjungi'] = array();
	$GLOBALS['sedang_dikunjungi'] = array();
	$GLOBALS['akan_dikunjungi'] = array();
	$count = 0;
	array_push($GLOBALS['akan_dikunjungi'], $url);	
	while (!empty($GLOBALS['akan_dikunjungi'])) {	
		$GLOBALS['sedang_dikunjungi'] = $GLOBALS['akan_dikunjungi'];
		$GLOBALS['akan_dikunjungi'] = array();
		while (!empty($GLOBALS['sedang_dikunjungi'])) {
			$url = array_shift($GLOBALS['sedang_dikunjungi']);
			array_push($GLOBALS['telah_dikunjungi'], $url);
			$GLOBALS['akan_dikunjungi'] = array_merge($GLOBALS['akan_dikunjungi'], scrapeUrl($url, $host));	
			/*echo "   ".sizeof($GLOBALS['telah_dikunjungi'])." => \033[1;36m".$url."\033[0m \n";*/
			$title = getTitle($url);
			$description = getMetaDescription($url);
			if (!is_null($title) && !is_null($description)) {
				fputcsv($fp, array($url, $title, $description));
				$count++;
				echo "   ".$count." => \033[1;36m".$url."\033[0m \n";								
			}						
			if ($count == 500) {							
				break 2;
			}
		}		
	}	
}
function is_connected($url) {
   	return checkdnsrr('php.net') ? true : false;
}

function main() {	
	global $seed;
	$fp = fopen(__DIR__."/crawl_data.csv", "w");
	foreach ($seed as $url) {
		if (is_connected($url)) {
			crawl2($fp, $url, parseHost($url));		
		}else {
			//echo 'Tidak ada koneksi internet!!'.PHP_EOL;
			echo "\033[91m   Tidak ada koneksi internet!! \033[0m\n";
		}				
		/*array_push($GLOBALS['telah_dikunjungi'], $url);
		$GLOBALS['akan_dikunjungi'] = array_merge($GLOBALS['akan_dikunjungi'], scrapeUrl($url, parseHost($url)));		
		while (!empty($GLOBALS['akan_dikunjungi'])) {
			$url_to_srcape = array_shift($GLOBALS['akan_dikunjungi']);						
			array_push($GLOBALS['telah_dikunjungi'], $url_to_srcape);
			$GLOBALS['akan_dikunjungi'] = array_merge($GLOBALS['akan_dikunjungi'], scrapeUrl($url_to_srcape, parseHost($url_to_srcape)));						
			echo sizeof($GLOBALS['telah_dikunjungi']).' => '.$url_to_srcape.PHP_EOL;
		}*/
	}	
	fclose($fp);
}


$time_pre = "\033[36m   Start\t: " . date("h:i:sa \n");
main();
$time_post = "   Finish\t: " . date("h:i:sa \033[0m \n");

echo $time_pre;
echo $time_post;
?>