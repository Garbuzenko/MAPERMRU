<?
function clearData($data,$type='str',$encoding=false) {
    
    if ($encoding == true)
    $data = iconv('utf-8','windows-1251',$data);
    
    switch($type) {

         case 'str':
         return mysql_real_escape_string(trim(stripslashes(strip_tags($data))));
         break;

         case 'int':
         return intval($data);
         break;
         
         case 'get':
         return preg_replace('/[^a-z0-9-]/','',$data);
         break;
    }
}

function encrypt_pass($pass) {
    if ($pass != '') {
        $pass = sha1( md5( trim( $pass ) ) );
        $pass = md5( $pass.'ertgnmjjoihufrtxdzs' );
    }
    return $pass;
}

function chek_email($email) {
   $result = true; 
   $email = strtolower( trim( $email ) );
        
   if (!preg_match("/^[a-z0-9\.\-_]+@[a-z0-9\-]{2,20}\.([a-z0-9\-]+\.)*?[a-z]{2,25}$/is", $email))
   $result = false;
   
   return $result;
}

function send_email($adminmail,$email,$subject,$message) {
    $headers = '';
    $headers.="Content-Type: text/html; charset=windows-1251\r\n";
    $headers.="From: 7nebo-sport.ru <".$adminmail.">\r\n";
    $headers.="Reply-To: <".$email.">\r\n";
    //$headers.="X-Mailer: PHP/".phpversion()."\r\n";
    
    $message = str_replace('\n','<br />',$message);
    $message = stripslashes($message);
    //$message = nl2br($message);
    
    mail($email,$subject,$message,$headers);
}

function transformDate($date) {
     
     $y = substr($date,0,4);
     $m = substr($date,5,2);
     $d = substr($date,8,2);
     $time = substr($date,11,5);
     
      $arr_m = array(
     '01' => 'января',
     '02' => 'февраля',
     '03' => 'марта',
     '04' => 'апреля',
     '05' => 'мая',
     '06' => 'июня',
     '07' => 'июля',
     '08' => 'августа',
     '09' => 'сентября',
     '10' => 'октября',
     '11' => 'ноября',
     '12' => 'декабря');
    
     $m = $arr_m[$m];
     
     if (!empty($time)) $time = " в ".$time;
     
     $date = $d." ".$m." ".$y." г.".$time;
     return $date;
}

function save_img($oldname,$tmpname,$name,$format,$uploaddir,$width) {

        if ( !preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/', $oldname) ) 
        return 'Допускаются только файлы формата jpg, gif и png';
        
        $filename = $oldname;
        $uploadfile = $_SERVER['DOCUMENT_ROOT']."/" . $uploaddir . $filename;

            move_uploaded_file($tmpname, $uploadfile);
            
            if ( !file_exists($uploadfile) )
            return 'Не удалось загрузить файл на сервер';
            
            $size = getimagesize($uploadfile);
            $new_name = $name . "." . $format;
            
            if (preg_match('/[.](PNG)|(png)$/', $filename))
             $im = imagecreatefrompng($uploadfile);

            if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename))
             $im = imagecreatefromjpeg($uploadfile);
               
            if (preg_match('/[.](GIF)|(gif)$/', $filename))
             $im = imagecreatefromgif($uploadfile);
            
            if ( $size[0] > $width ) 
            $height = floor($size[1] * ($width / $size[0]));
            
            else {
                $width = $size[0];
                $height = $size[1];
            }
                
            $new_image = imagecreatetruecolor($width, $height);
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            imagecopyresampled($new_image, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                
            if ( $format == 'jpg' )
            imagejpeg($new_image, $_SERVER['DOCUMENT_ROOT']."/" . $uploaddir . $new_name);
            
            else
            imagepng($new_image, $_SERVER['DOCUMENT_ROOT']."/" . $uploaddir . $new_name);
            
            unlink($uploadfile);
            
            return true;
}

function select_class($img,$class_w,$class_h) {
    $size = getimagesize($img);
    if ( $size[0] > $size[1] ) $class = $class_w;
    else $class = $class_h;
    
    return $class;
}

function get_content($module,$cache) {
    global $isIndex;
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/modules/'.$module.'/index.php')) $way = './modules/'.$module.'/index.php';
    else return false;
    
    ob_start();
    require_once $way;
    $content = ob_get_clean();
    
    if ($cache == true)
    add_cache($module,$content);
    
    return $content;
}

function get_cache($module,$cachetime) {
    $result = false;
    $cachetime = 3600 * $cachetime;
    $file = $_SERVER['DOCUMENT_ROOT'].'/modules/'.$module.'/'.md5($module).'.txt';
    
    if ( file_exists( $file ) && ( time() - @filemtime( $file ) ) < $cachetime )
    $result = file_get_contents($file);
    
    return $result;
}

function add_cache($module,$content) {
    $file = $_SERVER['DOCUMENT_ROOT'].'/modules/'.$module.'/'.md5($module).'.txt';
    $fopen = fopen( $file, 'w+' );
    fwrite( $fopen, $content );
    fclose( $fopen );
}

function file_download($file) {
  if (file_exists($file)) {
    
    if (ob_get_level()) {
      ob_end_clean();
    }
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();
  }
}

function compress($buffer) {
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = preg_replace('/\/\/.*\r\n/', '', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}

function combine($format,$ver,$name) {
    if ($format == 'js') $j = file_get_contents(PATH.'/'.$format.'/jquery.'.$format);
    $s = file_get_contents(PATH.'/'.$format.'/main.'.$format);
    //if (empty($s)) return false;
    if ($handle = opendir(PATH.'/modules')) {
       while (false !== ($file = readdir($handle))) { 
           if ($file!='.' && $file!='..')
           $mod[] = $file;
       }
       closedir($handle);
       $col = count($mod);
       if ($col > 0) {
          for($i=0;$i<$col;$i++) {
             $f = PATH.'/modules/'.$mod[$i].'/'.$format.'/'.$name.'.'.$format;
             if (file_exists($f) && filesize($f) > 0) 
                $s .= file_get_contents($f);
          }
       }
    }
    
    $s = compress($s);
    if ($format == 'js') $s = $j.'$(document).ready(function(){'.$s.'});';
    file_put_contents(PATH.'/'.$format.'/'.$name.$ver.'.'.$format,$s);
    file_put_contents(PATH.'/'.$format.'/'.$format.'.txt',$ver);
    return $name.$ver.'.'.$format;
}

function get_file($format,$ver,$update) {
    if ($format=='css') $name = 'style';
    else $name = 'scripts';
    $v = file_get_contents(PATH.'/'.$format.'/'.$format.'.txt');
    
    if ( ($ver == 0 && $v == 0) || $update==true) {
         $s = combine($format,$ver,$name);
         return $s;
    }
    
    if ($update==false) {
        if ($ver!=$v) {
           $s = combine($format,$ver,$name);
           if ($s!==false) {
              @unlink(PATH.'/'.$format.'/'.$name.$v.'.'.$format);
              return $s;
           }
        }
    }
    
    return $name.$v.'.'.$format;
}

function c_query($sql,$type='s') {
    
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

         case 'q':
         if (mysql_affected_rows() > 0) $result = true;
         else $result = mysql_error();
         break;
    }
    
    return $result;
}

function every_day($date) {
    
    $h = explode('-',$date);
    $y = $h[0];
    $m = intval($h[1]);
    $d = intval($h[2]);
    
    $y2 = date('Y');
    $m2 = date('n');
    $d2 = date('j');
    
    $md = array(
     1 => 31,
     2 => 28,
     3 => 31,
     4 => 30,
     5 => 31,
     6 => 30,
     7 => 31,
     8 => 31,
     9 => 30,
     10 => 31,
     11 => 30,
     12 => 31
    );
    
    $d++;
    
    if ($d > $md[$m]) {
          $d = 1;
          $m++;
          if ($m > 12) $y++;
    }
    
    $a=mktime(0,0,0,$m,$d,$y);
    $b=mktime(1,0,0,$m2,$d2,$y2);
    while ($a<=$b) {
    $t = date('d.m.Y',$a);
    $c[] = $t.'-'.$t;
    $a+=86400;
    }
    
    if (count($c) > 0) return $c;
    else return false;
}

function get_csv($module,$all=false) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/modules/'.$module.'/files/*.csv';
    $f = glob($path);
    foreach ($f as $file) { 
    
    $t = explode('_',basename($file));
    $date = str_replace('.csv','',$t[1]);
    
    if ($all == true) $d[ basename($file) ] = str_replace('-','.',$date);
    else $d[] = $date;
    
    }
    
    if (count($d) > 0) {
        if ($all == true) return $d;
        else {$md = max($d);return array($module.'_'.$md.'.csv',str_replace('-','.',$md));}
    }
    else return false;
}