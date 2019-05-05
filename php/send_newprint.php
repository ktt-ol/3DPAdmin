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
        }else{
            $uid = NULL;
        }  
        return $uid ;
    }
    function get_username($userid){
        $query ="SELECT `username` FROM `userbase` WHERE `UID` = '$user' LIMIT 1";
        if ($stmt = mysqlquery($query))
        {   
            $username = $stmt['username'];
        }else{
            $username = NULL;
        }
        return $username ;
    }
    function update_balance($mysqli,$uid,$value,$__MULTIPLICATOR){
        $updatevalue = $this->filter($value)*$this->filter($__MULTIPLICATOR);
        if($value>0){
            $query ="UPDATE credit SET value = value + $updatevalue WHERE userid = '$uid'";
        }elseif($value<=0){
            $query ="UPDATE credit SET value = value - $updatevalue WHERE userid = '$uid'";
        }
        if (mysqli_query($mysqli,$query))
        {   
            return true ;
        }           
    }
    function update_filament($mysqli,$FID,$value){
        $updatevalue = $this->filter($value);
        $query ="UPDATE filament SET weight = weight - $updatevalue WHERE FID = '$FID'";
        if (mysqli_query($mysqli,$query))
        {   
            return true ;
        } 
    }
    function update_user_lastprint($mysqli,$uid){
        date_default_timezone_set('Europe/Berlin');
        $timestamp = date('Y-m-d H:i:s');
        $uid = $this->filter($uid);
        $query ="UPDATE `userbase` SET `lastprint` = CURRENT_TIMESTAMP WHERE `userbase`.`UID` = $uid;";
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
    public function get_multiplier($FID){
        $query = "SELECT `multiplier` FROM `filament` WHERE `FID` = $FID LIMIT 1";
        $result = $this->mysqlquery($query);
        return $result['0'];
    }
    public function get_price($post){
        $data = $this->get_print($post);
        $calc = "SELECT `pricepergramm` FROM `ranks` WHERE `RID` = '".$data['pricecat']."' LIMIT 1";      
        $weight = $data['weight'];
        $price = $this->mysqlquery($calc)['0'];
        $multiplier = $this->get_multiplier($data['filament']);

        $calculated = $weight * $price * $multiplier;
        $calculated = round($calculated/100, 2);        
        return $calculated;
    }

    public function save_print($mysqli,$post,$is_creditprint = 0){
        $data = $this->get_print($post);
        if($is_creditprint == 1){
            $query_history =
            "   INSERT INTO `history` 
                (`username`, `operator`, `weight`, `pricecat`, `price`, `filament`, `printer`, `printedat`, `description`,`is_creditprint`) 
                VALUES  ('".
                $data['customer']."', '".
                $data['operator']."', '".
                $data['weight']."', '".
                $data['pricecat']."', '".
                $this->get_price($post)."', '".
                $data['filament']."', '".
                $data['printer']."', CURRENT_TIMESTAMP, '".
                $data['description']."', '1');";
        }else{
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
        }
        if($mysqli->query($query_history)){
            if($is_creditprint == 1){
                ////////////////////////////////////////////
                // Remove Credits from the Operators-Account   
                $data['weight'] = $data['weight'] - 2*$data['weight'];
                $uid = $this->get_userid($data['operator']);

                if(  $this->update_balance($mysqli,$uid,$data['weight'],$this->get_multiplier($data['filament']))&& 
                        $this->update_user_lastprint($mysqli,$uid) &&
                        $this->update_filament($mysqli,$data['filament'],$data['weight']))
                {
                    $url = "/?s=history"; 
                    header("Location: $url"); 
                }
            } else {
                ////////////////////////////////////////////
                // Add Credits to the Operators-Account      
                $uid = $this->get_userid($data['operator']);
                $c_id = $this->get_userid($data['customer']);
                if($uid != $c_id){
                    if(  $this->update_balance($mysqli,$uid,$data['weight'],$this->get_multiplier($data['filament']))&& 
                        $this->update_user_lastprint($mysqli,$uid) &&
                        $this->update_filament($mysqli,$data['filament'],$data['weight']))
                    {
                        $url = "/?s=history"; 
                        header("Location: $url"); 
                    }
                }else{
                    if($this->update_user_lastprint($mysqli,$uid))
                    {
                        $url = "/?s=history"; 
                        header("Location: $url"); 
                    }
                }
            }           
        }else{
            echo 'error';
        }
    }
}

if (isset($_POST['submit'])){
    $print = new prints;
    if(isset($_GET['creditprint']) && $print->filter(@$_GET['creditprint'])==true){
        $creditprint=1;
    }
    else
    {
        $creditprint=0;
    }
    $print->save_print($mysqli,$_POST,$creditprint);
    
} elseif (isset($_POST['calc'])){
    $prints = new prints;
    $price = $prints->get_price($_POST);
    $data = $prints->get_print($_POST);
    if(isset($_GET['creditprint']) && $prints->filter(@$_GET['creditprint'])==true){
        $url = "/index.php?s=creditprint&p=$price&c=".$data['customer']."&op=".$data['operator']."&w=".$data['weight']."&pc=".$data['pricecat']."&f=".$data['filament']."&pr=".$data['printer']."&d=".$data['description'];    
    }
    else
    {
        $url = "/index.php?s=newprint&p=$price&c=".$data['customer']."&op=".$data['operator']."&w=".$data['weight']."&pc=".$data['pricecat']."&f=".$data['filament']."&pr=".$data['printer']."&d=".$data['description'];    
    }
    header("Location: $url");
}