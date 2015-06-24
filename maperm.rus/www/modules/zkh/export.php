<?
$a = db_query("SELECT address, found, wear, dates, lng, lat FROM zkh_homes WHERE lng!=''");
$file = $path.$com.'/files/'.$com.'.csv';
$csv_title = "Адрес;Построен;Степень износа;Дата ремонта, гг;Долгота;Широта\r\n";
    foreach ($a as $h) {
     if ($h['wear'] == 0) $h['wear'] = '';
     if ($h['found'] == 0) $h['found'] = '';
     else $h['found'] = $h['found'].' г.';
        
     $d = explode(';',$h['dates']);
     $date = $d[0];
     $h['address'] = iconv('utf-8','windows-1251',$h['address']);   
        
     $val .= $h['address'].';'.$h['found'].';'.$h['wear'].';'.$date.';'.$h['lng'].';'.$h['lat']."\r\n";
    }

file_put_contents($file,$csv_title.$val);