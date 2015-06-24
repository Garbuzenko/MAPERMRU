<?
header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/db.php';
$td = date('d-m-Y');
$a = array('pharmacy' => 341461, 'video' => 341479);
foreach($a as $k=>$v) {
    $val = '';
    $val2 = '';
    $c = file('http://opendata.permkrai.ru/opendata/LoadData/SerializedHandler.ashx?direction='.$v.'&format=CSV&action=exportfile&startpos=0&endpos=1000');
    $col = count($c);
    
    for ($i=1;$i<$col;$i++) {
    $h = explode(';',iconv('windows-1251','utf-8',$c[$i]));
    
    if ($k == 'pharmacy') {
        $name = trim(mysql_real_escape_string($h[0]));
        $address = trim($h[3]);
        $phone = trim($h[4]);
        $director = trim($h[6]);
        $ges = $h[5];
        $lng = $h[2];
        $lat = $h[1];
        $district = '';
    }
    
    else {
        $name = trim(mysql_real_escape_string($h[0]));
        $address = trim($h[5]);
        $phone = '';
        $director = '';
        $ges = '';
        $lng = $h[2];
        $lat = $h[1];
        $district = trim(str_replace('район','',$h[4]));
    }
    
    $val .= "('$name','$address','$district','$phone','$director','$ges','$lng','$lat','$k'),";
    }

    if (!empty($val)) {
    $val = substr($val,0,-1);
    
    $r = db_query("SELECT * FROM zkh_opendata WHERE type='$k' LIMIT 1");
    if ($r !== false) db_query("DELETE FROM zkh_opendata WHERE type='$k'","d");
    
    db_query("INSERT INTO zkh_opendata (name,address,district,phone,director,ges,lng,lat,type) VALUES $val","i");
    }
    
    
    $arr = db_query("SELECT * FROM zkh_opendata WHERE type='".$k."'");
    $file = $_SERVER['DOCUMENT_ROOT'].'/modules/'.$k.'/files/'.$k.'_'.$td.'.csv';
    if ($k == 'pharmacy') $csv_title = "Название аптеки;Адрес;Телефон;Директор;Долгота;Широта\r\n";
    else $csv_title = "Название;Расположение;Район;Долгота;Широта\r\n";
    
     foreach ($arr as $ap) {
       $ap['name'] = iconv('utf-8','windows-1251',$ap['name']);
       $ap['address'] = iconv('utf-8','windows-1251',$ap['address']);
       $ap['director'] = iconv('utf-8','windows-1251',$ap['director']);
       $ap['district'] = iconv('utf-8','windows-1251',$ap['district']);
       
       if ($k == 'pharmacy') $val2 .= $ap['name'].';'.$ap['address'].';'.$ap['phone'].';'.$ap['director'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
       else $val2 .= $ap['name'].';'.$ap['address'].';'.$ap['district'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
     }

   file_put_contents($file,$csv_title.$val2);
}