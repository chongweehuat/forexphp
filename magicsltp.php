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

            $sql="SELECT * FROM account_magic_trade where login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";
                
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            $m15Height=0;
            $m15Count=0;
            $tpRatio=0;
            if(isset($data[22]) && $data[22]>0)$m15Height=$data[22];
            if(isset($data[23]) && $data[23]>0)$m15Count=$data[23];
            if(isset($data[24]) && $data[24]>0)$tpRatio=$data[24];

            if(empty($row)){
                if($data[4]=="buy"){
                    $UPrice=$data[14]+$data[17]*$data[13];
                    $LPrice=$data[14]-$data[18]*2*$data[13];
                }else{
                    $UPrice=$data[15]+$data[18]*2*$data[13];
                    $LPrice=$data[15]-$data[17]*$data[13];
                }
                $UPrice=max($UPrice,$data[10]);
                if($data[9]>0)$LPrice=min($LPrice,$data[9]);

                $sql="INSERT INTO account_magic_trade (login,magicnumber,pair,opentype,opencount,openlot,openprofit,openpnl,minPrice,maxPrice,nsl,ntp,point,bid,ask,lastupdate,w1Height,d1Height,h4Height,rePips,tp_mode,UPrice,LPrice,m15Height,h1Height,m15Count) VALUES ";
                $sql.="({$data[1]},{$data[2]},'{$data[3]}','{$data[4]}',{$data[5]},{$data[6]},{$data[7]},{$data[8]},{$data[9]},{$data[10]},{$data[11]},{$data[12]},{$data[13]},{$data[14]},{$data[15]},'$now',{$data[16]},{$data[17]},{$data[18]},$m15Height,{$data[20]},$UPrice,$LPrice,$m15Height,{$data[19]},$m15Count)";			
                $mysqli->query($sql);
            }else{
                $sql="update account_magic_trade set ";
                $sql.="opencount={$data[5]},";
                $sql.="openlot={$data[6]},";
                $sql.="openprofit={$data[7]},";
                $sql.="openpnl={$data[8]},";
                $sql.="minPrice={$data[9]},";
                $sql.="maxPrice={$data[10]},";
                $sql.="nsl={$data[11]},";
                $sql.="ntp={$data[12]},";
                $sql.="point={$data[13]},";
                $sql.="bid={$data[14]},";
                $sql.="ask={$data[15]},";
                $sql.="w1Height={$data[16]},";
                $sql.="d1Height={$data[17]},";
                $sql.="h4Height={$data[18]},";
                $sql.="h1Height={$data[19]},";
                $sql.="m15Height=$m15Height,";
                $sql.="m15Count=$m15Count,";
                $sql.="tpRatio=$tpRatio,";
                if($m15Height>0)$sql.="rePips=$m15Height,";
                else $sql.="rePips={$data[19]},";
                //$sql.="tp_mode={$data[20]},";
                $sql.="tp_pips={$data[21]},";
                $sql.="lastupdate='$now'";
                $sql.=" where  login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";			
                $mysqli->query($sql);        
            }
            //echo $sql;
            //file_put_contents("debug.log",$sql);
            //file_put_contents("debug.log",var_export($row,1));

            $sql="SELECT * FROM account_magic_trade where login={$data[1]} and magicnumber={$data[2]} and pair='{$data[3]}' and opentype='{$data[4]}'";
                    
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            $reVol=$row['reVol'];
            if($row['opencount']>=3)$reVol=min(2,$row['maxfactor'])*$row['reVol'];
            if($row['opencount']>=6)$reVol=min(3,$row['maxfactor'])*$row['reVol'];
            if($row['opencount']>=9)$reVol=min(6,$row['maxfactor'])*$row['reVol'];
            
            echo "@{$row['LPrice']}@{$row['UPrice']}@{$row['entryPrice']}@{$row['entryVol']}@{$row['rePips']}@$reVol@{$row['tp_mode']}";  

        }

    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
    
}
