<?php

//$seed = array('https://infobatik.id/');//,'http://batik-s128.com/','https://infobatik.id/');
// $telah_dikunjungi = array();
// $sedang_dikunjungi = array();
// $akan_dikunjungi = array();
$buffer = array();
$frontier = array();
$count = 0;
// function file_get_contents_curl($url) {
// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_HEADER, 0);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($ch, CURLOPT_URL, $url);

// 	$data = curl_exec($ch);
// 	curl_close($ch);

// 	return $data;
// }
function getTitle($url) {
	//$html = file_get_contents_curl($url);
	// $html = file_get_contents($url);
	// $doc = new DOMDocument();
	// @$doc->loadHTML($html);
	// $nodes = $doc->getElementsByTagName('title');
	// if ($nodes->length > 0) {
	// 	return $nodes->item(0)->nodeValue;
	// }
	// return null;
	$doc = new DOMDocument;
	@$doc->loadHTMLFile($url);

	$nodes = $doc->getElementsByTagName('title');
	if ($nodes->length > 0) {
		return $nodes->item(0)->nodeValue;
	}
	return null;
}
// function getMeta($url) {
// 	$html = file_get_contents_curl($url);
// 	$doc = new DOMDocument();
// 	@$doc->loadHTML($html);
// 	$metas = $doc->getElementsByTagName('meta');

// 	$arrayMeta = array();
// 	for ($i=0; $i < $metas->length; $i++) { 
// 		$meta = $metas->item($i);
// 		if ($meta->getAttribute('name') == 'description'){
// 			return $meta->getAttribute('content');			
// 		}	
// 	}
// 	return null;
// }
function getMetaDescription($url) {
	@$tags = get_meta_tags($url);
	if (!empty($tags['description'])) {
		return $tags['description'];
	}
	return null;
}
function getMetaTitle($url) {
	@$tags = get_meta_tags($url);
	if (!empty($tags['title'])) {
		return $tags['title'];
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
	if (!empty($domain['host'])) {
		return preg_replace(array('/\A(www.)+/','/(\.(go|sch|edu|org|com|id))*\z/'), '', $domain['host']);
	}
	return null;
	// $domain = parse_url($url);
	// if (!empty($domain['host'])) {
	// 	$parse_host = explode('.', $domain['host']);
	// 	return sizeof($parse_host) == 3 ? $parse_host[1].'.'.$parse_host[2] : $domain['host'];
	// }	
	// return null;
}

function getLinkTags($url) {
    $dom = new DOMDocument();
	@$dom->loadHTMLFile($url);
    return $dom->getElementsByTagName('a');	    
}
function cekData($fp, $url) {
	$title = getTitle($url);
	$description = getMetaDescription($url);
	# cek apakah url memiliki metag description dan title	
	if (!is_null($title) && !is_null($description)) {				
		fputcsv($fp, array($url, $title, $description));		
		$GLOBALS['count']++;
		echo "   ".$GLOBALS['count']." => \033[1;36m".$url."\033[0m \n";
		return true;
	}	
	return false;
}

function scrapeUrl($fp, $url,$host) {
    $linkTags = getLinkTags($url);	
    
	if ($linkTags) {
	    foreach ($linkTags as $tag) {	 
			# cek tag 'href' null atau tidak               
			@$href = $tag->attributes->getNamedItem('href')->value;
	        if (!empty($href)) {	        	
	        	# cek isi tag 'href' adalah url valid
	        	if (filter_var($href,FILTER_VALIDATE_URL)) {
	        		# cek host dari 'href' dengan host url utama
	        		if (parseHost($href) == $host) {	        			
	        			$href = parseUrl($href);
	        			# cek apakah url dari tag 'href' sudah di ditemukan sebelumnya		
	        			if (!in_array($href, $GLOBALS['buffer']) && !in_array($href, $GLOBALS['frontier'])) {
							# cek apakah meta description dan title tidak bernilai null
							if (cekData($fp, $href)) {
								array_push($GLOBALS['frontier'], $href);
								if ($GLOBALS['count'] == 281) {
									break;
								}
							}																																																		
	        			}
	        		}	        						    
				}
	        }	        			
	    }		
    }	    
    if ($GLOBALS['count'] < 281 && count($GLOBALS['frontier']) != 0) {
        $url = array_shift($GLOBALS['frontier']);
        array_push($GLOBALS['buffer'], $url);
        scrapeUrl($fp, $url, parseHost($url));
    }	
}

$seed = array('https://www.nativeindonesia.com/');

$fp = fopen(__DIR__.'/crawl_data.csv', 'c');
foreach ($seed as $url) {	
    $GLOBALS['count'] = 0;
	$GLOBALS['frontier'] = $GLOBALS['buffer'] = array();	
	if (cekData($fp, $url)) {
		array_push($GLOBALS['buffer'], $url);					
	}	
    scrapeUrl($fp, $url, parseHost($url));
}
fclose($fp);
?>