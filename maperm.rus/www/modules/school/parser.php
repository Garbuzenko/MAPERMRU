<?
header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/phpQuery.php';
$page = preg_replace('/[^a-z]/','',$_GET['p']);
$type = $page;
$page = 'http://permedu.ru/Pages/Public/Catalog.aspx?p='.$page;

$f = gzopen($page, 'r');
$data = gzread($f, 1000000);
gzclose($f);
$result = phpQuery::newDocumentHTML($data,'UTF8');
$a = $result->find('table[class=sortable]')->html();
$a = explode('</tr>',$a);
$col = count($a);

if ($col > 1) {
    for($i=1;$i<=$col;$i++) {
        $x = explode('</td>',$a[$i]);
        if (count($x) > 0) {
            $t = explode('<br>',$x[2]);
            $name = trim(strip_tags($x[1]));
            $phone = trim(strip_tags($t[0]));
            $director = trim(strip_tags($t[1]));
            $address = trim(strip_tags($x[3]));
            $pos = strpos($address,'г.');
            $address = trim(substr($address,$pos));
            $address = str_replace('"','',$address);
            
            $site = phpQuery::newDocumentHTML($x[4],'UTF8');
            $site = urldecode( $site->find('a')->attr('href') );
            if ($site == 'http://www.') $site = '';
            
            $addr = urlencode($address);
            $res = file_get_contents('http://geocode-maps.yandex.ru/1.x/?geocode='.$addr);
            $parse = simplexml_load_string( $res );
            $pos = $parse->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            if (!empty($pos)) {
               $lt = explode(' ',$pos);
               $lng = $lt[0];
               $lat = $lt[1];
            }
            
            if (!empty($phone)) {
                $val .= "('$name','$phone','$director','$address','$site','$lng','$lat','$type'),";
            }
        }
    }
    
    if (!empty($val)) {
       $val = substr($val,0,-1);
       $c = db_query("SELECT * FROM zkh_school WHERE type='$type' LIMIT 1");
       if ($c !== false) db_query("DELETE FROM zkh_school WHERE type='$type'","d");
       
       $t = db_query("INSERT INTO zkh_school (name,phone,director,address,site,lng,lat,type) VALUES $val","i");
       if ($t !== true) send_email($mainemail,$mainemail,'Ошибка при записи в базу',$t);
    }
}