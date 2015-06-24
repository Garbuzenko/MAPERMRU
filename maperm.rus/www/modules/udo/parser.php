<?
header('Content-Type: text/html; charset=utf-8');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/phpQuery.php';
$url = 'http://do.permedu.ru/KidsCardPortal/ServiceList.aspx';

for ($y = 1; $y <= 143; $y++) {
    
    $val = '';
    $val2 = '';
    
    $data = file_get_contents($url.'?page='.$y);
    $result = phpQuery::newDocumentHTML($data);
    $a = $result->find('tbody > tr')->html();
    $h = explode('<td class="first_cell">0</td>', $a);
    $col = count($h);

    for ($i = 1; $i < $col; $i++) {
        $t = explode('</td>', $h[$i]);

        $name = clearData($t[0]);
        $supplier = clearData($t[1]);
        $trend = clearData($t[2]);

        $supplier_url = phpQuery::newDocumentHTML($t[1]);
        $supplier_url = $supplier_url->find('a')->attr('href');

        $sup = file_get_contents('http://do.permedu.ru/KidsCardPortal/' . $supplier_url);
        $info = phpQuery::newDocumentHTML($sup);
        $info = $info->find('p')->html();
        $f = explode('<br>', $info);
        $address = clearData(str_replace('Адрес: ', '', $f[1]));
        $phone = clearData(str_replace('Телефон: ', '', $f[2]));
        $director = clearData(str_replace('Директор: ', '', $f[4]));
        $site = clearData(str_replace('Сайт: ', '', $f[5]));

        $val .= "('$name','$trend','$address'),";

        $suppliers[$address] = array(
            'supplier' => $supplier,
            'address' => $address,
            'phone' => $phone,
            'director' => $director,
            'site' => $site);
    }

    foreach ($suppliers as $s) {
        $val2 .= "('" . $s['supplier'] . "','" . $s['address'] . "','" . $s['phone'] . "','" . $s['director'] . "','" . $s['site'] . "'),";
    }

    if (!empty($val)) {
        $val = substr($val, 0, -1);
        $val2 = substr($val2, 0, -1);

        $c = db_query("INSERT INTO dou (name,trend,supplier_id) VALUES $val", "i");
        if ($c !== true)
            exit($c);

        $c = db_query("INSERT INTO dou_suppliers (supplier,address,phone,director,site) VALUES $val2",
            "i");
        if ($c !== true)
            exit($c);
    }

}

mysql_query("ALTER IGNORE TABLE dou_suppliers ADD UNIQUE INDEX (address)");

$a = db_query("SELECT id, address FROM dou_suppliers");
if ($a !== false) {
    mysql_query("DROP INDEX address ON dou_suppliers");
    foreach ($a as $id) {
        
        $addr = urlencode($id['address']);
        $res = file_get_contents('http://geocode-maps.yandex.ru/1.x/?geocode='.$addr);
        $parse = simplexml_load_string( $res );
        $pos = $parse->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        if (!empty($pos)) {
          $lt = explode(' ',$pos);
          $lng = $lt[0];
          $lat = $lt[1];
        }
        
        $b = db_query("UPDATE dou SET supplier_id=".$id['id']." WHERE supplier_id='".$id['address']."'","u");
        if ($b !== true) exit($b);
        
        $c = db_query("UPDATE dou_suppliers SET lng='$lng', lat='$lat' WHERE id=".$id['id'],"u");
        if ($c !== true) exit($c);
    }
}