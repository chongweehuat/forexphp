<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$now=date("Y-m-d H:i:s");
//$mysqli = new mysqli("localhost", "fbsonline", "30Fu45y7qjsH9dze", "fbsonline");
//$mysqli = new mysqli("localhost", "my369forex", "30Fu45y7qjsH9dze", "my369forex");
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");

if(isset($_POST['result']))$result=$_POST['result'];
//file_put_contents('debug2.log',var_export($result,1));
$aresult1=explode('*',$result);
$aresults=explode('#',$aresult1[0]);

$datetime="";
foreach($aresults as $result){
    $arst=explode('|',$result);
    //file_put_contents('debug1.log',var_export($arst,1));
    if(count($arst)>2){
        
        $sql="insert into AdjCs28Log (pair,datetime,priceclose) values ('{$arst[0]}', '{$arst[1]}',{$arst[2]})";
        //file_put_contents('debug.log',$sql);
        $res=$mysqli->query($sql);
        if($res==false){
            $sql="update AdjCs28Log set priceclose={$arst[2]} where pair='{$arst[0]}' and datetime='{$arst[1]}'";
            $res=$mysqli->query($sql);
            //file_put_contents('debug.log',var_export($res,1));
        }
        
    }
}