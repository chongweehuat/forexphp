<?php

if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
                
        try{
            for($month_section=1; $month_section<=3; $month_section++){
                for($week_day=6; $week_day<=6; $week_day++){
                    for($day_section=1; $day_section<=6; $day_section++){
                        $sql="insert into tpref (month_section,week_day,day_section) values ($month_section,$week_day,$day_section) ";
                        //file_put_contents("test.log",$sql);
                        //$res = $mysqli->query($sql);
                    }
                }
            }    
            
        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }

        
    }
}    