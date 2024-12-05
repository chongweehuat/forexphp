<?php

//if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
                
        try{
            for($month_section=1; $month_section<=3; $month_section++){
                for($week_day=1; $week_day<=5; $week_day++){
                    for($day_hour=0; $day_hour<=23; $day_hour++){
                        $sql="insert into tpdata (`month_section`,`week_day`,`day_hour`) values ($month_section,$week_day,$day_hour) ";
                        //file_put_contents("test.log",$sql);
                        //$res = $mysqli->query($sql);
                    }
                }
            }    
            
        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }

        
    }
//}    