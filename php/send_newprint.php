<?php
require $_SERVER['DOCUMENT_ROOT'].'/php/sessions.php';
include $_SERVER['DOCUMENT_ROOT'].'/php/config.php';

class prints{    
    function __construct(){
    }
    function get_userid($user){
        $query ="SELECT `UID` FROM `userbase` WHERE `username` = '$user'";
        if ($stmt = $this->mysqlquery($query))
        {   
            $uid = $stmt['UID'];
        }   
        return $uid ;
    }
    function upgrade_balance($mysqli,$uid,$value,$__MULTIPLICATOR){
        $updatevalue = $value*$__MULTIPLICATOR;
        $query ="UPDATE credit SET value = value + $updatevalue WHERE userid = '$uid'";
        if (mysqli_query($mysqli,$query))
        {   
            return true ;
        }           
    }
    
    public function filter($string){
        return filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }            
    private function mysqlquery($query){
  
        $connect = mysqli_connect(
                MYSQLI_HOST, 
                MYSQLI_USER, 
                MYSQLI_PASS, 
                MYSQLI_BASE);
        
        if(!$connect){
            echo mysqli_error($connect);
            echo 'NO CONNECTION TO DATABASE';
        }
        elseif ($connect) { 
            //echo 'DB OK';   
            return mysqli_fetch_array(mysqli_query($connect, $query));
        }
        else{
            echo 'UNDEFINED ERROR';
        }
    }
    public function get_print($post){
        $datetime = date("Y-m-d H:i:s");        
        $data['customer'] = $this->filter($post['customer']);
        $data['operator'] = $_SESSION['user'];
        $data['pricecat'] = $this->filter($post['pricecat']);
        $data['weight'] = $this->filter($post['weight']);
        $data['datetime'] = $datetime;  
        $data['printer'] = $this->filter($post['printer']);      
        $data['filament'] = $this->filter($post['filament']);      
        $data['description'] = $this->filter($post['description']);
        $_SESSION['post']['price'] = $data['pricecat']; 
        return $data;
        
    }
    
    public function get_price($post){
        $data = $this->get_print($post);
        $calc = "SELECT `pricepergramm` FROM `ranks` WHERE `RID` = '".$data['pricecat']."' LIMIT 1";      
        $weight = $data['weight'];
        $price = $this->mysqlquery($calc)['0'];
        $calculated = $weight * $price;
        $calculated = round($calculated/100, 2);        
        return $calculated;
    }
    public function save_print($mysqli,$post){
        $data = $this->get_print($post);
        $query_history =
            "   INSERT INTO `history` 
                (`username`, `operator`, `weight`, `pricecat`, `price`, `filament`, `printer`, `printedat`, `description`) 
        VALUES  ('".
                $data['customer']."', '".
                $data['operator']."', '".
                $data['weight']."', '".
                $data['pricecat']."', '".
                $this->get_price($post)."', '".
                $data['filament']."', '".
                $data['printer']."', CURRENT_TIMESTAMP, '".$data['description']."');";
        if($mysqli->query($query_history)){
            ////////////////////////////////////////////
            // Hinzugüfen von Guthaben für den Operator            
            $uid = $this->get_userid($data['operator']);
            if($this->upgrade_balance($mysqli,$uid,$data['weight'],1)){
                $url = "/?s=history";
                header("Location: $url");
            }
        }else{
            echo 'error';
        }
    }
}

if (isset($_POST['submit'])){
    $print = new prints;
    $print->save_print($mysqli,$_POST);
    
} elseif (isset($_POST['calc'])){
    $prints = new prints;
    $price = $prints->get_price($_POST);
    $data = $prints->get_print($_POST);
    $url = "/index.php?s=newprint&p=$price&c=".$data['customer']."&op=".$data['operator']."&w=".$data['weight']."&pc=".$data['pricecat']."&f=".$data['filament']."&pr=".$data['printer']."&d=".$data['description'];    
    header("Location: $url");
}