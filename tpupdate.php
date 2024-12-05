<?php

if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
        date_default_timezone_set('Asia/Kuala_Lumpur');        
        $adata=explode('|',$_POST['result']);
        $now=strtotime(str_replace('.','-',$adata[8]));

        //file_put_contents("test.log",$adata[8].(string)$now);

        $j=date("j",$now);
        $month_section=1;
        if($j>10 && $j<=20)$month_section=2;
        if($j>20)$month_section=3;
        
        $week_day=date("w",$now);
        
        $day_hour=intval(date("H",$now));
        
        try{
            $sql="select * from tpdata ";
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="`day_hour`=$day_hour";
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();

            $max_profitlot=max($row['close_profitlot'],$row['max_profitlot']);
            $max_pips=max($row['close_pips'],$row['max_pips']);

            $max_profitlot2=max($row['close_profitlot2'],$row['max_profitlot2']);
            $max_pips2=max($row['close_pips2'],$row['max_pips2']);

            $max_profitlot3=max($row['close_profitlot3'],$row['max_profitlot3']);
            $max_pips3=max($row['close_pips3'],$row['max_pips3']);

            $min_fd_no=1;
            if($max_profitlot>$max_profitlot2){
                if($max_profitlot2>$max_profitlot3)$min_fd_no=3;
                else $min_fd_no=2;
            }

            if($min_fd_no==1){
                $last_profitlot=max($adata[2],$row['last_profitlot']);
                $last_pips=max($adata[5],$row['last_pips']);
            }
            if($min_fd_no==2){
                $last_profitlot=max($adata[2],$row['last_profitlot2']);
                $last_pips=max($adata[5],$row['last_pips2']);
            }
            if($min_fd_no==3){
                $last_profitlot=max($adata[2],$row['last_profitlot3']);
                $last_pips=max($adata[5],$row['last_pips3']);
            }

            $sql="update tpdata set ";

            $sql.="max_profitlot=$max_profitlot, ";
            $sql.="max_pips=$max_pips, ";

            $sql.="max_profitlot2=$max_profitlot2, ";
            $sql.="max_pips2=$max_pips2, ";

            $sql.="max_profitlot3=$max_profitlot3, ";
            $sql.="max_pips3=$max_pips3, ";
            
            if($min_fd_no==1){
                $sql.="last_profitlot=$last_profitlot,";
                $sql.="last_pips=$last_pips ";
            }
            if($min_fd_no==2){
                $sql.="last_profitlot2=$last_profitlot,";
                $sql.="last_pips2=$last_pips ";
            }
            if($min_fd_no==3){
                $sql.="last_profitlot3=$last_profitlot,";
                $sql.="last_pips3=$last_pips ";
            }
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="`day_hour`=$day_hour";
            $res = $mysqli->query($sql);

            $now=date("Y-m-d H:i:s");
            $sql="insert into takeprofit (datetime,login,openProfit,openProfitLot,maxProfit,maxProfitLot,maxOpenProfitLot,topProfitLot,pair) values ('$now',{$adata[0]},{$adata[1]},{$adata[2]},{$adata[3]},{$adata[4]},{$adata[5]},{$adata[6]},'{$adata[7]}') ";
            file_put_contents("test.log",$sql);
            $res = $mysqli->query($sql);

            $sql="select * from tpdata ";
            $sql.="where ";
            $sql.="month_section=$month_section and ";
            $sql.="week_day=$week_day and ";
            $sql.="`day_hour`=$day_hour";
            $res = $mysqli->query($sql);
            $row = $res->fetch_assoc();
            //file_put_contents("test.log",$sql);
            $pl=1000;
            $plrt=100;
            $pp=160;
            $pprt=30;
            if($min_fd_no==1){
                $close_pips=$row['close_pips'];
                $max_pips=$row['max_pips'];
                $last_pips=$row['last_pips'];
                $close_profitlot=$row['close_profitlot'];
                $max_profitlot=$row['max_profitlot'];
                $last_profitlot=$row['last_profitlot'];
            }
            if($min_fd_no==2){
                $close_pips=$row['close_pips2'];
                $max_pips=$row['max_pips2'];
                $last_pips=$row['last_pips2'];
                $close_profitlot=$row['close_profitlot2'];
                $max_profitlot=$row['max_profitlot2'];
                $last_profitlot=$row['last_profitlot2'];
            }
            if($min_fd_no==3){
                $close_pips=$row['close_pips3'];
                $max_pips=$row['max_pips3'];
                $last_pips=$row['last_pips3'];
                $close_profitlot=$row['close_profitlot3'];
                $max_profitlot=$row['max_profitlot3'];
                $last_profitlot=$row['last_profitlot3'];
            }
            if($close_pips>0){
                $pl=max(100,$close_profitlot*0.8);
                $plrt=max(10,($max_profitlot-$close_profitlot)/2);
                $plrt=max($plrt,$pl*0.1);
                if($last_profitlot>$max_profitlot){
                    $plrt=$max_profitlot*0.1;
                }

                $pp=max(100,$close_pips*0.8);
                $pprt=max(10,($max_pips-$close_pips)/2);
                $pprt=max($pprt,$pp*0.1);
                if($last_pips>$max_pips){
                    $plrt=$max_pips*0.1;
                }
            }else{
                $pl=max(100,$last_profitlot*0.8);
                $plrt=max(10,($max_profitlot-$last_profitlot)/2);
                $plrt=max($plrt,$pl*0.1);
                if($last_profitlot>$max_profitlot){
                    $plrt=$max_profitlot*0.1;
                }

                $pp=max(100,$last_pips*0.8);
                $pprt=max(10,($max_pips-$last_pips)/2);
                $pprt=max($pprt,$pp*0.1);
                if($last_pips>$max_pips){
                    $plrt=$max_pips*0.1;
                }
            }
            
            echo "@$pl@$plrt@$pp@$pprt";
        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }

        
    }
}    