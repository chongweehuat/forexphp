<?php

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM pairs_data_h1 where pair='{$_GET['pair']}' order by dt";         
        $res = $mysqli->query($sql);

        $maxprice=0;
        while($row = $res->fetch_assoc()){
           if($row['price_high']>$maxprice){
                $maxprice=$row['price_high'];
           }
           if($row['dt']>'2023-01-31' && $row['price_high']==$maxprice){
                $sql="update pairs_data_h1 set price_max=$maxprice where id={$row['id']}";
                $res1 = $mysqli->query($sql);
           }     
        }
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}