<?
header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/functions.php';

$db = mysql_connect ($db_host,$db_user,$db_pass);
mysql_select_db ($db_name, $db);
mysql_query('SET NAMES utf-8',$db);          
mysql_query('SET CHARACTER SET utf-8',$db);  
mysql_query('SET COLLATION_CONNECTION="utf-8"',$db);

$empty_inn = 1;
$levels = array(1,3,4,5,6,7);

$a = c_query("SELECT MAX(sign_date) AS date FROM budget_contracts LIMIT 1");
if ($a == false) exit();
$dates = every_day($a[0]['date']);

if ($dates !== false) {
    
    foreach ($levels as $lev) {
        foreach($dates as $date) {
        $url = 'http://openapi.clearspending.ru/restapi/v3/contracts/search/?customerregion=59&budgetlevel=0'.$lev.'&daterange='.$date;
        $j = @file_get_contents($url);
           if (!preg_match('/Not Found/is',$http_response_header[0])) {
               $r = json_decode($j,true);
               if (count($r['contracts']['data']) > 0) {
                foreach ($r['contracts']['data'] as $d) {
                    
                     $product = '';
                     $okpd = '';
                     $okpd_name = '';
                     $val = '';
                     $val2 = '';
                     $val3 = '';
                    
                    foreach($d['products'] as $prod) {
                        $product .= '||'.$prod['name'];
                        $okpd .= '||'.$prod['OKPD']['code'];
                        $okpd_name .= '||'.$prod['OKPD']['name'];
                    }
                    
                    $product = substr($product,2);
                    $okpd = substr($okpd,2);
                    $okpd_name = substr($okpd_name,2);
                    
                    $product = str_replace(array("\r\n", "\r", "\n", "\t", ';'), ' ', $product);
                    $okpd_name = str_replace(array("\r\n", "\r", "\n", "\t", ';'), ' ', $okpd_name);
                    $s_inn = $d['suppliers'][0]['inn'];
                    
                    if (empty($s_inn)) {
                        $s_inn = $empty_inn;
                        $empty_inn++;
                    }
                    
                    $address = $d['suppliers'][0]['factualAddress'];
                    if (preg_match('/Страна: Российская Федерация; ОКАТО:/',$address)) {
                      $address = preg_replace('/Страна: Российская Федерация; ОКАТО: [0-9]+; Почтовый индекс: [0-9]+; Субъект РФ:/is','',$address);
                      $address = preg_replace('/Город:|Населенный пункт:|Улица:|Дом:|Офис: [0-9]+/is','',$address);
                      $address = str_replace(';',',',$address);
                      $address =  trim(str_replace('-,','',$address));
                      $address = substr($address,0,-1);
                    }
                    
                    $val .= "('".$d['customer']['regNum']."','".clearData($d['customer']['fullName'])."','".$d['customer']['inn']."','".$d['customer']['kpp']."'),";
                    
                    $val2 .= "('".$s_inn."','".$d['suppliers'][0]['kpp']."','".$d['suppliers'][0]['legalForm']['code']."',
                    '".clearData($d['suppliers'][0]['legalForm']['singularName'])."','".clearData($address)."',
                    '".$d['suppliers'][0]['contactPhone']."','".$d['suppliers'][0]['contactEMail']."',
                    '".clearData($d['suppliers'][0]['contactInfo']['lastName'].' '.$d['suppliers'][0]['contactInfo']['firstName'].' '.$d['suppliers'][0]['contactInfo']['middleName'])."',
                    '".clearData($d['suppliers'][0]['organizationName'])."'),";
                    
                    $val3 .= "('".$d['regNum']."','".clearData($product)."','".$d['price']."','".$d['regionCode']."',
                    '".$okpd."','".clearData($okpd_name)."','".substr($d['publishDate'],0,10)."','".substr($d['signDate'],0,10)."',
                    '".$d['customer']['regNum']."','".$s_inn."','".$d['economic_sectors'][0]['code']."',
                    '".clearData($d['economic_sectors'][0]['name'])."','".$lev."'),";
                    
                    /*
                    $data[] = array(
                    'c_regnum' => $d['customer']['regNum'],
                    'c_kpp' => $d['customer']['kpp'],
                    'c_inn' => $d['customer']['inn'],
                    'c_name' => $d['customer']['fullName'],
                    's_inn' => $d['suppliers'][0]['inn'],
                    's_kpp' => $d['suppliers'][0]['kpp'],
                    's_name' => $d['suppliers'][0]['organizationName'],
                    's_code' => $d['suppliers'][0]['legalForm']['code'],
                    's_code_name' => $d['suppliers'][0]['legalForm']['singularName'],
                    's_address' => $address,
                    's_phone' => $d['suppliers'][0]['contactPhone'],
                    's_email' => $d['suppliers'][0]['contactEMail'],
                    's_contact_face' => $d['suppliers'][0]['contactInfo']['lastName'].' '.$d['suppliers'][0]['contactInfo']['firstName'].' '.$d['suppliers'][0]['contactInfo']['middleName'],
                    'contract_regnum' => $d['regNum'],
                    'contract_product' => $product,
                    'contract_price' => $d['price'],
                    'region_code' => $d['regionCode'],
                    'contract_okpd' => $okpd,
                    'contract_okpd_name' => $okpd_name,
                    'contract_publish_date' => substr($d['publishDate'],0,10),
                    'contract_sign_date' => substr($d['signDate'],0,10),
                    'contract_economic_sectors' => $d['economic_sectors'][0]['code'],
                    'contract_economic_sectors_name' => $d['economic_sectors'][0]['name'],
                    'contract_budget_level' => $lev
                    );
                    */
                    
                }
               //print_r($data);
               $val = substr($val,0,-1);
               $val2 = substr($val2,0,-1);
               $val3 = substr($val3,0,-1);
               
               $t = c_query("INSERT INTO budget_customer (regnum,name,inn,kpp) VALUES $val","q");
               if ($t !== true) send_email($mainemail,$mainemail,'Ошибка при записи в базу','Парсер модуля budget.<br />'.$t);
               
               $t = c_query("INSERT INTO budget_suppliers (inn,kpp,code,code_name,address,phone,email,contact_face,name) VALUES $val2","q");
               if ($t !== true) send_email($mainemail,$mainemail,'Ошибка при записи в базу','Парсер модуля budget.<br />'.$t);
               
               $t = c_query("INSERT INTO budget_contracts (regnum,product,price,region_code,okpd,okpd_name,publish_date,sign_date,regnum_customer,inn_suppliers,economic_sectors,economic_sectors_name,budget_level) VALUES $val3","q");
               if ($t !== true) send_email($mainemail,$mainemail,'Ошибка при записи в базу','Парсер модуля budget.<br />'.$t);
                  
               }
           }
        }
    }
}

mysql_query("ALTER IGNORE TABLE budget_customer ADD UNIQUE INDEX (regnum)");
mysql_query("DROP INDEX regnum ON budget_customer");

mysql_query("ALTER IGNORE TABLE budget_suppliers ADD UNIQUE INDEX (inn)");
mysql_query("DROP INDEX inn ON budget_suppliers");

$y = c_query("SELECT id, address FROM budget_suppliers WHERE lng=''");
if ($y !== false) {
    foreach ($y as $f) {
        $addr = urlencode($f['address']);
        $res = file_get_contents('http://geocode-maps.yandex.ru/1.x/?geocode='.$addr);
        $parse = simplexml_load_string( $res );
        $pos = $parse->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        if (!empty($pos)) {
          $lt = explode(' ',$pos);
          $lng = $lt[0];
          $lat = $lt[1];
        }
        
        $b = c_query("UPDATE budget_suppliers SET lng='$lng',lat='$lat' WHERE id=".$f['id'],"q");
    }
}

$td = date('d-m-Y');
$file = $path.'budget/files/budget_'.$td.'.csv';
function b_level($lev) {
    $b_levels = array(
    1 => 'федеральный бюджет',
    3 => 'местный бюджет',
    4 => 'бюджет Пенсионного фонда Российской Федерации',
    5 => 'бюджет Федерального фонда обязательного медицинского страхования',
    6 => 'бюджет Фонда социального страхования Российской Федерации',
    7 => 'бюджет территориального государственного внебюджетного фонда'
    );
    return $b_levels[$lev];
}


$a = c_query("SELECT 
budget_contracts.product,
budget_contracts.price,
budget_contracts.inn_suppliers,
budget_contracts.budget_level,
budget_customer.name 
FROM budget_contracts 
JOIN budget_customer ON budget_contracts.regnum_customer = budget_customer.regnum");

$b = c_query("SELECT inn,address,name,lng,lat FROM budget_suppliers WHERE lng!=''");
foreach ($b as $g) {
    $sup[$g['inn']] = array(
    'address' => str_replace(';',',', iconv('utf-8','windows-1251',$g['address']) ),
    'name' => str_replace(';',',', iconv('utf-8','windows-1251',$g['name']) ),
    'lng' => $g['lng'],
    'lat' => $g['lat']
    );
}

$csv_title = "Уровень бюджета;Заказчик;Выполненные работы;Цена, руб;Исполнитель;Адрес исполнителя;Долгота;Широта\r\n";
foreach ($a as $r) {
$r['name'] = str_replace(';',',', iconv('utf-8','windows-1251',$r['name']) );
$r['product'] = str_replace(';',',', iconv('utf-8','windows-1251',$r['product']) );
$level = b_level($r['budget_level']);

$val4 .= $level.';'.$r['name'].';'.$r['product'].';'.$r['price'].';'.$sup[$r['inn_suppliers']]['name'].';'.$sup[$r['inn_suppliers']]['address'].';'.$sup[$r['inn_suppliers']]['lng'].';'.$sup[$r['inn_suppliers']]['lat']."\r\n";
}

file_put_contents($file,$csv_title.$val4);