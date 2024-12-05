<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
$now=date("Y-m-d H:i:s");
//$mysqli = new mysqli("localhost", "fbsonline", "30Fu45y7qjsH9dze", "fbsonline");
//$mysqli = new mysqli("localhost", "my369forex", "30Fu45y7qjsH9dze", "my369forex");
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");

if(isset($_POST['result']))$result=$_POST['result'];
$aresult=explode('*',$result);
$arst=explode('|',$aresult[0]);
//file_put_contents('debug1.log',var_export($arst,1));
if(count($arst)>3){
	$magicnumber=0;
	if(isset($arst[14]))$magicnumber=$arst[14];
    $sql="update account_trade set ";
    $sql.="ncount={$arst[2]},";
    $sql.="nfloat={$arst[3]},";
	$sql.="maxfloat={$arst[4]},";
	$sql.="gappips={$arst[5]},";
	$sql.="opencount={$arst[6]},";
	$sql.="openlot={$arst[7]},";
	$sql.="curxcount={$arst[9]},";
	$sql.="inpxcount={$arst[10]},";
	$sql.="inprepips={$arst[11]},";
	$sql.="gappercent={$arst[12]},";
	$sql.="inpopenpercent={$arst[13]},";
    $sql.="lastupdate='{$now}'";
    $sql.=" where login={$arst[0]} and pair='{$arst[1]}' and opentype='{$arst[8]}' and magicnumber=$magicnumber";
    //file_put_contents('debug.log',$sql);
    $mysqli->query($sql);
}