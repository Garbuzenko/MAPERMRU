<?
if ($_POST['action']=='ajax') {
    
        if ($_POST['act'] == "fast") {
             $s = clearData($_POST['search'], 'str', true );
             if ($s=='') exit();
             $a = db_query("SELECT id, address FROM zkh_homes WHERE address LIKE '$s%' ORDER BY address");
             if ( $a !== false ) {
             $sp = '';
             foreach ( $a as $b ) {
             $sp .= '<li class="address2">'.$b['address'].'</li>';
             }
             exit($sp);
             }
        }
       
        if ($_POST['act'] == "search") {
            
            $address = clearData($_POST['search'],'str',true);
    
            if ($address == '') exit();
        
            if ( !preg_match('/,/',$address) ) $w = " MATCH (address) AGAINST ('$address') ";
            else $w = " address='$address' ";
        
            $a = db_query("SELECT city, address, repair, dates FROM zkh_homes WHERE".$w);
            if ($a !== false) {
         
            foreach ($a as $h) {
            
            $qw = explode(';',$h['dates']);
            $date = $qw[0];
         
            $repair = "'".preg_replace('/; /', "','", $h['repair'])."'";
            $b = db_query("SELECT full FROM zkh_reduction WHERE reduction IN ($repair)");
            foreach ($b as $desc) {
                $reduction .= '<li>'.$desc['full'].'</li>';
            }
            
            $result .= '<p>'.$h['city'].' , ул. '.$h['address'].'<br />
            Ближайший капитальный ремонт; '.$date.' гг.<br />
            Виды работ: '.$reduction.'</p>';
         
            }
            
            exit($result);
           }
           
           else 
           exit('<h1>Поиск не дал результатов</h1>');
    
        }
    
    exit();
}