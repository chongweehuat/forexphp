<?php

//file_put_contents("debug.log",var_export(explode('|',$_POST['result']),1));

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        date_default_timezone_set('Asia/Kuala_Lumpur');
        $now=date("Y-m-d H:i:s");

        $data=explode('|',$_POST['result']);

        if(count($data)>9){
            
            $sql="SELECT * FROM account_magic_trade where login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";
                
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            if(empty($row)){
                $sql="INSERT INTO account_magic_trade (login,magicnumber,pair,opentype,opencount,openlot,openprofit,minPrice,maxPrice,point,bid,ask,lastupdate) VALUES ";
                $sql.="({$data[1]},{$data[2]},'{$data[3]}','{$data[4]}',{$data[5]},{$data[6]},{$data[7]},{$data[8]},{$data[9]},{$data[10]},{$data[11]},{$data[12]},'$now')";			
                $mysqli->query($sql);
            }else{
                $sql="update account_magic_trade set ";
                $sql.="opencount={$data[5]},";
                $sql.="openlot={$data[6]},";
                $sql.="openprofit={$data[7]},";
                $sql.="minPrice={$data[8]},";
                $sql.="maxPrice={$data[9]},";
                $sql.="point={$data[10]},";
                $sql.="bid={$data[11]},";
                $sql.="ask={$data[12]},";

                $sql.="entryPrice=0,";
                $sql.="entryVol=0,";
                $sql.="nsl=0,";
                $sql.="ntp=0,";

                $sql.="rePips={$data[13]},";
                $sql.="reVol={$data[14]},";
                $sql.="tp={$data[15]},";
                $sql.="sl={$data[16]},";
                $sql.="openpnl={$data[17]},";

                if($data[4]=="buy"){
                    $Uprice=$data[8]+$data[16]*$data[10];
                    $LPrice=$data[9]-$data[15]*$data[10];
                }else{
                    $Uprice=$data[9]+$data[15]*$data[10];
                    $LPrice=$data[8]-$data[16]*$data[10];
                }

                $sql.="UPrice=$Uprice,";
                $sql.="LPrice=$LPrice,";

                $sql.="lastupdate='$now'";
                $sql.=" where  login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";			
                $mysqli->query($sql);        
            }
            
        }

    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
    
}
