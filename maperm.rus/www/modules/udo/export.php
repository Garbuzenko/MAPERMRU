<?
$arr = db_query("
SELECT 
dou.name, 
dou.trend,
dou_suppliers.supplier,
dou_suppliers.address,
dou_suppliers.phone,
dou_suppliers.director,
dou_suppliers.site,
dou_suppliers.lng,
dou_suppliers.lat 
FROM dou 
JOIN dou_suppliers ON dou.supplier_id = dou_suppliers.id");
$file = $path.$com.'/files/'.$com.'.csv'; 
$csv_title = "Название кружка;Направление;Поставщик;Адрес;Телефон;Директор;Сайт;Долгота;Широта\r\n";
foreach ($arr as $ap) {
  $ap['name'] = iconv('utf-8','windows-1251',$ap['name']);
  $ap['trend'] = iconv('utf-8','windows-1251',$ap['trend']);
  $ap['supplier'] = iconv('utf-8','windows-1251',$ap['supplier']);
  $ap['address'] = iconv('utf-8','windows-1251',$ap['address']);
  $ap['director'] = iconv('utf-8','windows-1251',$ap['director']);
  $ap['site'] = iconv('utf-8','windows-1251',$ap['site']);
  $val .= $ap['name'].';'.$ap['trend'].';'.$ap['supplier'].';'.$ap['address'].';'.$ap['phone'].';'.$ap['director'].';'.$ap['site'].';'.$ap['lng'].';'.$ap['lat']."\r\n";
}

file_put_contents($file,$csv_title.$val);