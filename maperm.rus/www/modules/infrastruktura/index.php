<?
$layer = '';
$obj = '';
$f = file('http://an-rakurs.ru/gtfytftftftf.php');
if (count($f) > 0) {
    foreach($f as $b) {
    $h = explode(';',$b);
    
    $name = '<div class="baloon"><div><img src="'.$h[6].'" /></div><br />Адрес: ул. '.$h[0].'<br />Микрорайон: '.$h[5].'<br />Этажей: '.$h[1]
    .'<br />Подъездов: '.$h[2].'<br />Срок сдачи: '.$h[3].'<br />Застройщик: '.$h[4].'</div>';
    $obj .= "{'style':'default#buildingsIcon','name':'".$name."','description':'','type':'Placemark','points':{'lng':".$h[7].",'lat':".$h[8]."}},";
}

   $obj = substr($obj,0,-1);
   $layer = "{'name':'new','center':{'lng':56.28552,'lat':58.01741,'zoom':11},'styles':[],'objects':[".$obj."]}";
}

list($csv,$date) = get_csv($module);

if (file_exists(PATH.'/modules/'.$module.'/config.php') && filesize(PATH.'/modules/'.$module.'/config.php') > 0)
require_once './modules/'.$module.'/config.php';

require_once './modules/'.$module.'/tmp.inc.php';

$td = date('d-m-Y');
$file = $path.$module.'/files/'.$module.'_'.$td.'.csv';
$csv_title = "Адрес;Этажей в доме;Подъездов;Срок сдачи;Застройщик;Микрорайон;Фото;Долгота;Широта\r\n";
$t = file_get_contents('http://an-rakurs.ru/gtfytftftftf.php');
file_put_contents($file,$csv_title.$t);