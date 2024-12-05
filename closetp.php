<?php

if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
        date_default_timezone_set('Asia/Kuala_Lumpur');
        
        $adata=explode('|',$_POST['result']);

        $now=strtotime(str_replace('.','-',$adata[3]));

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
            
            $sql="update tpdata set ";
            $sql.="last_profitlot=0,";
            $sql.="last_pips=0, ";
            if($row['close_profitlot']<$adata[1]){
                $sql.="close_profitlot={$adata[1]},";
                $sql.="close_pips={$adata[2]} ";
            }else{
                if($row['close_profitlot2']<$adata[1]){
                    $sql.="close_profitlot2={$adata[1]},";
                    $sql.="close_pips2={$adata[2]} ";
                }else{
                    $sql.="close_profitlot3={$adata[1]},";
                    $sql.="close_pips3={$adata[2]} ";
                }
            }

            $sql.="where ";
            $sql.="id={$row['id']}";
            $res = $mysqli->query($sql);

        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }

        
    }
}    