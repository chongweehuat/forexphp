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
            if($data[11]=='nan')$data[11]=0;
            if($data[12]=='nan')$data[12]=0;

            $sql="SELECT * FROM magicTrade where login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";
                
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            $tpnow=0;

            if(empty($row)){
                
                $sql="INSERT INTO magicTrade (login,magicnumber,pair,opentype,opencount,openlot,openprofit,openpnl,minPrice,maxPrice,nsl,ntp,bottomPrice,topPrice,point,bid,ask,lastupdate,note) VALUES ";
                $sql.="({$data[1]},{$data[2]},'{$data[3]}','{$data[4]}',{$data[5]},{$data[6]},{$data[7]},{$data[8]},{$data[9]},{$data[10]},{$data[11]},{$data[12]},{$data[13]},{$data[14]},{$data[15]},{$data[16]},{$data[17]},'$now','')";			
                $mysqli->query($sql);
            }else{
                if($row['opentype']=='buy' && intval($data[5])>0 && $row['topPrice']>0){
                    if($row['ULimit']>0 && $row['topPrice']>=$row['ULimit'] && $row['LPrice']<$row['LLimit']){
                        $sql1="update magicTrade set LPrice=LLimit where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }
                    
                    if($row['ULimit2']>0 && $row['topPrice']>=$row['ULimit2'] && $row['LPrice']<$row['LLimit2']){
                        $sql1="update magicTrade set LPrice=LLimit2 where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }

                    if($row['ULimit3']>0 && $row['topPrice']>=$row['ULimit3'] && $row['LPrice']<$row['LLimit3']){
                        $sql1="update magicTrade set LPrice=LLimit3 where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }
                }
                if($row['opentype']=='sell' && intval($data[5])>0 && $row['bottomPrice']>0){
                    if($row['LLimit']>0 && $row['bottomPrice']<=$row['LLimit'] && $row['UPrice']>$row['ULimit']){
                        $sql1="update magicTrade set UPrice=ULimit where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }
                    
                    if($row['LLimit2']>0 && $row['bottomPrice']<=$row['LLimit2'] && $row['UPrice']>$row['ULimit2']){
                        $sql1="update magicTrade set UPrice=ULimit2 where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }

                    if($row['LLimit3']>0 && $row['bottomPrice']<=$row['LLimit3'] && $row['UPrice']>$row['ULimit3']){
                        $sql1="update magicTrade set UPrice=ULimit3 where id={$row['id']}";
                        $mysqli->query($sql1);         
                    }
                }
                if(empty($data[5]) && $row['bottomPrice']>0 && $row['topPrice']>0){
                    $sql1="update magicTrade set bottomPrice=0,topPrice=0 where id={$row['id']}";
                    $mysqli->query($sql1);        
                }
                $tpnow=$row['tpnow'];
                $sql="update magicTrade set ";
                $sql.="opencount={$data[5]},";
                $sql.="openlot={$data[6]},";
                $sql.="openprofit={$data[7]},";
                $sql.="openpnl={$data[8]},";
                $sql.="minPrice={$data[9]},";
                $sql.="maxPrice={$data[10]},";
                $sql.="nsl={$data[11]},";
                $sql.="ntp={$data[12]},";
                if($tpnow==1)$sql.="tpnow=0,";
                $sql.="bottomPrice={$data[13]},";
                $sql.="topPrice={$data[14]},";
                $sql.="point={$data[15]},";
                $sql.="bid={$data[16]},";
                $sql.="ask={$data[17]},";
                
                $sql.="lastupdate='$now'";
                $sql.=" where  login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";			
                $mysqli->query($sql);        
            }
            //echo $sql;
            //file_put_contents("debug.log",$sql);
            //file_put_contents("debug.log",var_export($row,1));

            $sql="SELECT * FROM magicTrade where login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";
                    
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();
            
            echo "@{$row['LPrice']}@{$row['UPrice']}@{$row['LLimit']}@{$row['ULimit']}@{$tpnow}@{$row['LLimit2']}@{$row['ULimit2']}@{$row['LLimit3']}@{$row['ULimit3']}@{$row['dGap']}@{$row['dLot']}@{$row['dMax']}@{$row['dCount']}@{$row['dNew']}";  

        }

    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
    
}
