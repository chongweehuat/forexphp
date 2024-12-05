<?php

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM pairs_data_d1 where pair='{$_GET['pair']}' order by dt";         
        $res = $mysqli->query($sql);

        while($row = $res->fetch_assoc()){
            $date=substr($row['dt'],0,10);
            $sql="select * from pairs_data_h4 where pair='{$_GET['pair']}' and substring(dt,1,10)='$date'";
            $res1 = $mysqli->query($sql);
            $h4=[-1,-1,-1,-1,-1,-1];
            $n=0;
            while($row1 = $res1->fetch_assoc()){
                $h4[$n]=0;
                if($row1['price_open']<$row1['price_close'])$h4[$n]=1;
                $n++;
            }
            $sql="update pairs_data_d1 set h41={$h4[0]},h42={$h4[1]},h43={$h4[2]},h44={$h4[3]},h45={$h4[4]},h46={$h4[5]} where id={$row['id']}";
            $res2 = $mysqli->query($sql);
        }
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}