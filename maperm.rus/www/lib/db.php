<?
$db = mysql_connect ($db_host,$db_user,$db_pass);
mysql_select_db ($db_name, $db);
mysql_query('SET NAMES windows-1251',$db);          
mysql_query('SET CHARACTER SET windows-1251',$db);  
mysql_query('SET COLLATION_CONNECTION="windows-1251"',$db);

function db_query($sql,$type='s') {
    
    $a = mysql_query($sql);
    
    switch($type) {

         case 's':
         
         if ( mysql_num_rows($a) > 0 ) {
            $result = array();
                while ($b = mysql_fetch_assoc($a)) {
                    $result[] = $b;
                }
         }
         
         else
         $result = false;
         
         break;

         case 'u':
         if (mysql_affected_rows() > 0) $result = true;
         else $result = mysql_error();
         break;
         
         case 'i':
         if (mysql_affected_rows() > 0) $result = true;
         else $result = mysql_error();
         break;
         
         case 'd':
         if (mysql_affected_rows() > 0) $result = true;
         else $result = mysql_error();
         break;
    }
    
    return $result;
}
?>