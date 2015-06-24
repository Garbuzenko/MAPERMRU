<?
$file = $path.$com.'/files/'.$com.'.csv';
function b_level($lev) {
    $levels = array(
    1 => '����������� ������',
    3 => '������� ������',
    4 => '������ ����������� ����� ���������� ���������',
    5 => '������ ������������ ����� ������������� ������������ �����������',
    6 => '������ ����� ����������� ����������� ���������� ���������',
    7 => '������ ���������������� ���������������� ������������� �����'
    );
    return $levels[$lev];
}


$a = db_query("SELECT 
budget_contracts.product,
budget_contracts.price,
budget_contracts.inn_suppliers,
budget_contracts.budget_level,
budget_customer.name 
FROM budget_contracts 
JOIN budget_customer ON budget_contracts.regnum_customer = budget_customer.regnum");

$b = db_query("SELECT inn,address,name,lng,lat FROM budget_suppliers WHERE lng!=''");
foreach ($b as $g) {
    $sup[$g['inn']] = array(
    'address' => str_replace(';',',', iconv('utf-8','windows-1251',$g['address']) ),
    'name' => str_replace(';',',', iconv('utf-8','windows-1251',$g['name']) ),
    'lng' => $g['lng'],
    'lat' => $g['lat']
    );
}

$csv_title = "������� �������;��������;����������� ������;����, ���;�����������;����� �����������;�������;������\r\n";
foreach ($a as $r) {
$r['name'] = str_replace(';',',', iconv('utf-8','windows-1251',$r['name']) );
$r['product'] = str_replace(';',',', iconv('utf-8','windows-1251',$r['product']) );
$level = b_level($r['budget_level']);

$val .= $level.';'.$r['name'].';'.$r['product'].';'.$r['price'].';'.$sup[$r['inn_suppliers']]['name'].';'.$sup[$r['inn_suppliers']]['address'].';'.$sup[$r['inn_suppliers']]['lng'].';'.$sup[$r['inn_suppliers']]['lat']."\r\n";
}

file_put_contents($file,$csv_title.$val);