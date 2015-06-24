<? 
$arr = file($path.$com.'/files/'.$com.'.txt');
$file = $path.$com.'/files/'.$com.'.csv';
$csv_title = "Район;Адрес;Комнат;Площадь;Этаж;Этажей в доме;Цена;Срок сдачи;Фото;url;Долгота;Широта\r\n";
foreach ($arr as $ar) {
    $app = unserialize($ar);
    foreach ($app as $ap) {
    if ($ap['type']==1) $ap['type'] = 'месяц';
    else if ($ap['type']==2) $ap['type'] = 'сутки';
    else $ap['type'] = '';
    
    $val .= dist($ap['district']).';'.$ap['address'].';'.$ap['rooms'].';'.$ap['area'].';'.$ap['level'].';'.$ap['levels'].';'.$ap['price'].';'.$ap['type'].';'.$ap['img'].';'.$ap['url'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
    }
}

file_put_contents($file,$csv_title.$val);
?>