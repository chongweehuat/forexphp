<?php

if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $now=date("Y-m-d H:i:s");
        $adata=explode('|',$_POST['result']);

        $j=date("j");
        $month_section=1;
        if($j>10 && $j<=20)$month_section=2;
        if($j>20)$month_section=3;
        
        $week_day=date("w");
        
        $h=intval(date("H"));
        $day_section=1;
        if($h>=4 && $h<=7)$day_section=2;
        if($h>=8 && $h<=11)$day_section=3;
        if($h>=12 && $h<=15)$day_section=4;
        if($h>=16 && $h<=19)$day_section=5;
        if($h>=20)$day_section=6;

        if($week_day>=6)$day_section=min($day_section,2);
        if($week_day==0){
            $week_day=6;
            $day_section=2;
        }
        if($week_day==1)$day_section=max($day_section,2);

        try{
            $sql="select * from tpref ";
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="day_section=$day_section";
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            $max_profitlot=max($row['close_profitlot'],$row['max_profitlot']);
            $max_pips=max($row['close_pips'],$row['max_pips']);
            $last_profitlot=max($adata[2],$row['last_profitlot']);
            $last_pips=max($adata[5],$row['last_pips']);

            $sql="update tpref set ";
            $sql.="max_profitlot=$max_profitlot, ";
            $sql.="max_pips=$max_pips, ";
            $sql.="last_profitlot=$last_profitlot,";
            $sql.="last_pips=$last_pips ";
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="day_section=$day_section";
            $res = $mysqli->query($sql);

            $sql="insert into takeprofit (datetime,login,openProfit,openProfitLot,maxProfit,maxProfitLot,maxOpenProfitLot,topProfitLot,pair) values ('$now',{$adata[0]},{$adata[1]},{$adata[2]},{$adata[3]},{$adata[4]},{$adata[5]},{$adata[6]},'{$adata[7]}') ";
            //file_put_contents("test.log",$sql);
            $res = $mysqli->query($sql);

            $sql="select * from tpref ";
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="day_section=$day_section";
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();
            if($row['max_pips']==0){
                $sql="select * from tpref ";
                $sql.="where ";
                $sql.="week_day=$week_day and ";
                $sql.="day_section=$day_section";
                $res = $mysqli->query($sql);
                $row = $res->fetch_assoc();
                if($row['max_pips']==0){
                    $sql="select * from tpref ";
                    $sql.="where ";
                    $sql.="day_section=$day_section";
                    $res = $mysqli->query($sql);
                    $row = $res->fetch_assoc();
                    if($row['max_pips']==0){
                        $sql="select * from tpref ";
                        $sql.="where ";
                        $sql.="max_pips>0";
                        $res = $mysqli->query($sql);
                        $row = $res->fetch_assoc();
                    }
                }
            }
            $pl=1000;
            $plrt=100;
            $pp=160;
            $pprt=30;
            if($row['close_pips']>0){
                $pl=max(60,$row['close_profitlot']*0.8);
                $plrt=max(6,($row['max_profitlot']-$row['close_profitlot'])/2);
                $plrt=max($plrt,$pl*0.1);
                if($row['last_profitlot']>$row['max_profitlot']){
                    $plrt=$row['max_profitlot']*0.1;
                }

                $pp=max(60,$row['close_pips']*0.8);
                $pprt=max(6,($row['max_pips']-$row['close_pips'])/2);
                $pprt=max($pprt,$pp*0.1);
                if($row['last_pips']>$row['max_pips']){
                    $plrt=$row['max_pips']*0.1;
                }
            }else{
                $pl=max(60,$row['last_profitlot']*0.8);
                $plrt=max(6,($row['max_profitlot']-$row['last_profitlot'])/2);
                $plrt=max($plrt,$pl*0.1);
                if($row['last_profitlot']>$row['max_profitlot']){
                    $plrt=$row['max_profitlot']*0.1;
                }

                $pp=max(60,$row['last_pips']*0.8);
                $pprt=max(6,($row['max_pips']-$row['last_pips'])/2);
                $pprt=max($pprt,$pp*0.1);
                if($row['last_pips']>$row['max_pips']){
                    $plrt=$row['max_pips']*0.1;
                }
            }
            
            echo "@$pl@$plrt@$pp@$pprt";
        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }

        
    }
}    