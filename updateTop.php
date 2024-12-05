<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$now=date("Y-m-d H:i:s");

$result="";
if(isset($_POST['result']))$result=$_POST['result'];
$aresult=explode('#',$result);
$arst=explode('|',$aresult[0]);

//file_put_contents("debug.log",$now.var_export($arst,1));

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");

$sql="insert into activePair (pair,datetime,direction,height,percent,xcount) values ('";
$sql.=$arst[0];
$sql.="','";
$sql.=$arst[1];
$sql.="','";
$sql.=$arst[2];
$sql.="',";
$sql.=$arst[3];
$sql.=",";
$sql.=$arst[4];
$sql.=",";
$sql.=$arst[5];
$sql.=")";

//file_put_contents("debug.log",$sql);

$mysqli->query($sql);