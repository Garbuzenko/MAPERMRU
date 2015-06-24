<?
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/lib/db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/phpQuery.php';
$agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36';
$cookie = $_SERVER['DOCUMENT_ROOT'].'/modules/avito/files/cookie.txt';
$txt = $_SERVER['DOCUMENT_ROOT'].'/modules/avito/files/avito.txt';
$info = array();

function dist($d) {
    if (!empty($d)) {
        $dist = array(
       'Дзержинский' => 1,
       'Индустриальный' => 2,
       'Кировский' => 3,
       'Ленинский' => 4,
       'Мотовилихинский' => 5,
       'Новые Ляды' => 6,
       'Орджоникидзевский' => 7,
       'Свердловский' => 8
        );
       return $dist[$d];
    }
}

function dist2($d) {
    if (!empty($d)) {
        $dist = array(
        1 => 'Дзержинский',
        2 => 'Индустриальный',
        3 => 'Кировский',
        4 => 'Ленинский',
        5 => 'Мотовилихинский',
        6 => 'Новые Ляды',
        7 => 'Орджоникидзевский',
        8 => 'Свердловский'
        );
       return $dist[$d];
    }
}

file_put_contents($txt,'');
file_put_contents($cookie,'');

$ch = curl_init();
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);

curl_setopt($ch, CURLOPT_URL, 'https://m.avito.ru/perm/kvartiry/sdam');
$content = curl_exec($ch);
$result = phpQuery::newDocument($content);

$col_ap = $result->find('div[class=nav-helper-content nav-helper-text]')->text();
$col_ap = round( str_replace(' ','',$col_ap) / 20 ) - 5;
sleep(20);
for ($p=1;$p<=$col_ap;$p++) {
    
curl_setopt($ch, CURLOPT_URL, 'https://m.avito.ru/perm/kvartiry/sdam?p='.$p);
$content = curl_exec($ch);
$result = phpQuery::newDocument($content);

$a = array();
$a[] = $result->find('article[class=b-item ]')->html();
$a[] = $result->find('article[class=b-item item-highlight]')->html();

foreach ($a as $ap) {
    $h = explode('</a>',$ap);
    $col = count($h);
    if ($col > 0) {
       for ($i=0;$i<$col;$i++) {
          if (trim($h[$i]) != '') {
            $result = phpQuery::newDocument($h[$i]);
            $link = $result->find('a[class=item-link]')->attr('href');
            
            $ap_h = $result->find('span[class=header-text]')->text();
            $c = explode(',',$ap_h);
            $rooms = intval( substr($c[0],0,1) );
            $area = intval( $c[1] );
            $lev = explode('/',$c[2]);
            $level = intval($lev[0]);
            $levels = intval($lev[1]);
            
            $price = trim ($result->find('div[class=item-price ]')->text() );
            $price = iconv('utf-8','windows-1251',$price);
            if ( substr($price,-5) == 'месяц' ) $type = 1;
            else if ( substr($price,-5) == 'сутки' ) $type = 2;
            else $type = 0;
            
            $price = preg_replace('/[^0-9]/','',$price);
            $price = intval($price);
            
            $dist = $result->find('span[class=info-text info-metro-district]')->text();
            $dist = iconv('utf-8','windows-1251',$dist);
            $dist = str_replace('р-н','',$dist);
            $dist = trim($dist);
            $dist = intval( dist($dist) );
            
            $address = $result->find('span[class=info-address info-text]')->text();
            $address = iconv('utf-8','windows-1251',$address);
            $address = preg_replace('/\s+/',' ',$address);
          
            $img = $result->find('span[class=pseudo-img]')->attr('style');
            $img = str_replace('background-image: url(','http:',$img);
            $img = substr($img,0,-2);
            
            if (!preg_match('/пермь/is',$address)) $addr = 'Пермь, '.$address;
            else $addr = $address;
            
            $addr = urlencode($addr);
            curl_setopt($ch, CURLOPT_URL, 'http://geocode-maps.yandex.ru/1.x/?geocode='.$addr);
            $res = curl_exec($ch);
            $parse = simplexml_load_string( $res );
            $pos = $parse->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            if (!empty($pos)) {
               $lt = explode(' ',$pos);
               $lng = $lt[0];
               $lat = $lt[1];
            }
            
            //$date = trim($result->find('div[class=info-date info-text]')->text());
            if ( $dist != 0 && !empty($price) ) {
                
                //$val .= "($dist,'$address',$rooms,$area,$level,$levels,$price,'$img','$url','$lng','$lat',$type),";
                //$df++;
                
                $info[] = array(
                'district' => $dist,
                'address' => $address,
                'rooms' => $rooms,
                'area' => $area,
                'level' => $level,
                'levels' => $levels,
                'type' => $type,
                'price' => $price,
                'img' => $img,
                'lng' => $lng,
                'lat' => $lat,
                'url' => $link
                );
            }
            
          }
       }
    }
}

if (count($info) >= 40) {
        $in = serialize($info)."\r\n";
        $f = fopen($txt,'a+');
        fwrite($f,$in);
        fclose($f);
        $info = array();
}

$s = mt_rand(20,28);
sleep($s);

}
curl_close($ch);

$td = date('d-m-Y');
$file = $_SERVER['DOCUMENT_ROOT'].'/modules/avito/files/avito_'.$td.'.csv';
$csv_title = "Район;Адрес;Комнат;Площадь;Этаж;Этажей в доме;Цена;Срок сдачи;Фото;url;Долгота;Широта\r\n";

$arr = file($txt);
foreach ($arr as $ar) {
    $app = unserialize($ar);
    foreach ($app as $ap) {
    if ($ap['type']==1) $ap['type'] = 'месяц';
    else if ($ap['type']==2) $ap['type'] = 'сутки';
    else $ap['type'] = '';
    
    $val .= dist2($ap['district']).';'.$ap['address'].';'.$ap['rooms'].';'.$ap['area'].';'.$ap['level'].';'.$ap['levels'].';'.$ap['price'].';'.$ap['type'].';'.$ap['img'].';'.$ap['url'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
    }
}

file_put_contents($file,$csv_title.$val);
?>