<?
$arr = db_query("SELECT name,address,phone,director,site,lng,lat FROM zkh_school WHERE type='".$com."'");
$file = $path.$com.'/files/'.$com.'.csv'; 
$csv_title = "Детский сад;Адрес;Телефон;Заведующий;Сайт;Долгота;Широта\r\n";
foreach ($arr as $ap) {
    $ap['name'] = iconv('utf-8','windows-1251',$ap['name']);
    $ap['address'] = iconv('utf-8','windows-1251',$ap['address']);
    $ap['director'] = iconv('utf-8','windows-1251',$ap['director']);
    $ap['site'] = iconv('utf-8','windows-1251',$ap['site']);
  $val .= $ap['name'].';'.$ap['address'].';'.$ap['phone'].';'.$ap['director'].';'.$ap['site'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
}

file_put_contents($file,$csv_title.$val);