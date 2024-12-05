<?php

if(isset($_POST)){
    //file_put_contents("test.log",var_export(explode('#',$_POST['result']),1));

    $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
    if ($mysqli->connect_errno) {
        file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }else{
        $data=explode('#',$_POST['result']);
        $metadata=explode('|',$data[0]);
        try{
            $pair=$metadata[0];
            $timeframe=$metadata[1];
            $table_name="pairs_data_d1";
            if($timeframe==16385)$table_name="pairs_data_h1";
            if($timeframe==16388)$table_name="pairs_data_h4";
            foreach($data as $n=>$sprices){
                if($n>0){
                    $aprice=explode('|',$sprices);
                    if(count($aprice)>3){
                        $sql="insert into $table_name ";
                        $sql.="(pair,dt,price_open,price_high,price_low,price_close) values ";
                        $sql.="('$pair','{$aprice[1]}',{$aprice[2]},{$aprice[3]},{$aprice[4]},{$aprice[5]})";
                        //file_put_contents("debug.log",$sql);
                        $res = $mysqli->query($sql);
                    }
                }
            }
        } catch(Exception $e) {
            file_put_contents("debug.log", var_export($e,1));
        }
    }
}    