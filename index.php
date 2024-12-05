<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
//$mysqli = new mysqli("localhost", "mt5_user", "dthX81_9", "mt5_account_info");
//$mysqli = new mysqli("mysql", "my369forex", "30Fu45y7qjsH9dze", "my369forex");	
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");	

//$mysqli = new mysqli("localhost", "trade", "89961810", "trade");
$ip_address=$_SERVER['REMOTE_ADDR'];
$sql="select id from account_user where ip_address='$ip_address' and enable_access=1";
$res = $mysqli->query($sql);

$row = $res->fetch_assoc();

if($row==NULL){
	header("Location: /register.php"); 
	exit();
}
	
if(isset($_GET['id'])){
	if(isset($_GET['remark'])){
		$sql="update account set remark='{$_GET['remark']}' where id={$_GET['id']}";
		$mysqli->query($sql);
	}
}
if(isset($_GET['login'])){
	if(isset($_GET['pair']) && isset($_GET['magic'])){
		$sql="update account_trade set ncount=0 where login={$_GET['login']} and pair='{$_GET['pair']}' and magicnumber={$_GET['magic']}";
		$mysqli->query($sql);
	}
}	

$sql="SELECT * FROM account  order by name";	
$res = $mysqli->query($sql);

echo '<meta http-equiv="refresh" content="15" />';	

?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>fx.my369</title>
<head>
<?php

echo "<center>";
echo "<table style='font-size:80%'>";
echo "<tr>";
echo "<td align=right>#</td>";
echo "<td><a href=/ target=vps>Account</a></td>";
echo "<td><a href=/vps.php target=vps>V</a></td>";
echo "<td>";
echo "<a href=/cs28.php target=pp>P</a> ";
//echo "<a href=http://fx.my369.site/chartcs8.php target=cs8>C</a> ";
echo "<a href=/chartmv8.php target=mv8>M</a> ";
//echo "<a href=http://fx.my369.site/chartcs28.php target=cs28>28</a> ";
echo "<a href=/adjcs28.php target=adjcs28>S</a> ";
echo "<a href=/db_index.php target=db>D</a> ";
echo "</td>";
echo "<td><a href=/pp.php target=pp>Remark</a></td>";
echo "<td align=right><a href=/rpt.php target=rpt>Balance</a></td>";
echo "<td align=right><a href=/cs8.php target=cs8>Equity</a></td>";
echo "<td align=right><a href=/topcs8.php target=topcs8>Float</a></td>";
echo "<td align=right>Float %</td>";
echo "<td align=right>Min %</td>";
echo "<td align=right>Max %</td>";
echo "<td align=right>Now<br>Open</td>";
echo "<td align=right>Max<br>Open</td>";
echo "<td align=right><a href=/pairIndex.php?od=1&pair=GBPJPY target=pairIndex>Free<br>Margin</a></td>";
echo "<td align=right>FM %</td>";
echo "<td align=right>Lots</td>";
echo "<td align=right>+Lots</td>";
echo "<td align=right>+ve<br>Count</td>";
echo "<td align=right>+ve<br>Float</td>";
echo "<td align=right>+ve Float<br>Per Lot</td>";
echo "<td align=right>%</td>";
echo "<td align=right>Max<br>Positive<br>Float</td>";
echo "<td align=right>%</td>";
echo "<td align=right><a href=/approve.php target=_blank>Last Update</a></td>";
echo "</tr>";
$n=0;

$tBal=0;
$tEqty=0;
$tFloat=0;
$topen=0;
$tfm=0;
$name='';

$xtBal=0;
$xtEqty=0;
$xtFloat=0;
$xtopen=0;
$xtfm=0;
	
$tlot=0;	

while($row = $res->fetch_assoc()){
	$n++;
		
	if($name!='' && $name!=$row["name"]){
		echo "<tr>";
		echo "<td></td>";
		echo "<td colspan=4>".substr($name,0,1)."</td>";
		echo "<td align=right>".number_format($xtBal,2)."</td>";
		echo "<td align=right>".number_format($xtEqty,2)."</td>";
		echo "<td align=right>".number_format($xtFloat,2)."</td>";
		echo "<td align=right>".number_format(100*$xtFloat/$xtBal,2)."%</td>";
		echo "<td align=right colspan=3>".number_format($xtopen,0)."</td>";
		echo "<td align=right colspan=2>".number_format($xtfm,2)."</td>";
		echo "<td align=right colspan=1>".number_format(100*$xtfm/$xtEqty,2)."</td>";
		echo "</tr>";
		$xtBal=0;
		$xtEqty=0;
		$xtFloat=0;
		$xtopen=0;
		$xtfm=0;
	}
	
	$aip=explode(".",$row['ip_address']);
	$acompany=explode(' ',$row['company']);
	$platform=substr($row['platform'],2).' '.$acompany[0];
	echo "<tr>";
	echo "<td align=right>".$n.".</td>";
	echo "<td><a href=https://www.fxblue.com/users/{$row['login']} target=_blank>{$row['login']}</a></td>";
	if(isset($aip[3]))echo "<td>{$aip[3]}</td>";
	else echo "<td>X</td>";
	echo "<td>{$platform}</td>";
	echo "<td><a href=?id={$row['id']}&remark={$row['remark']}>*{$row['remark']}</a></td>";
	echo "<td align=right><a href=/magictrade.php?login={$row['login']} target=magictrade title={$row['terminal_path']}>".number_format($row['balance'],2)."</a></td>";
	echo "<td align=right><a href=/mgTradeList.php?login={$row['login']} target=mgtrade{$row['login']} title={$row['terminal_path']}>".number_format($row['equity'],2)."</a></td>";
	echo "<td align=right>".number_format($row['account_float'],2)."</td>";

	$xtBal+=$row['balance'];
	$xtEqty+=$row['equity'];
	$xtFloat+=$row['account_float'];
	$xtopen+=$row["open_count"];
	$xtfm+=$row["margin_free"];

	$tBal+=$row['balance'];
	$tEqty+=$row['equity'];
	$tFloat+=$row['account_float'];
	$topen+=$row["open_count"];
	$tfm+=$row["margin_free"];
	
	$tlot+=$row["total_volume"];
	
	if(isset($_GET['login']) && $row['login']==$_GET['login'])$float_percent=0-100*$row['account_float']/$row['equity'];
	
	echo "<td align=right>".number_format(100*$row['account_float']/$row['balance'],2)."%</td>";
	echo "<td align=right>".number_format(100*$row["min_float"]/$row['balance'],2)."</td>";
	echo "<td align=right>".number_format(100*$row["max_float"]/$row['balance'],2)."</td>";
	echo "<td align=right><a href=?open={$row['login']}>".number_format($row["open_count"])."</a></td>";
	echo "<td align=right>".number_format($row["max_open_count"])."</td>";
	echo "<td align=right>".number_format($row["margin_free"],2)."</td>";
	echo "<td align=right>".number_format(100*$row["margin_free"]/$row['equity'],2)."</td>";
	echo "<td align=right>".$row["total_volume"]."</td>";
	echo "<td align=right>".$row["positive_volume"]."</td>";
	echo "<td align=right>".$row["positive_count"]."</td>";
	echo "<td align=right><a href=/tp.php?login={$row['login']} target=tp{$row['login']}>".$row["positive_float"]."</a></td>";
	if($row["positive_volume"]<>0)echo "<td align=right>".number_format($row["positive_float"]/$row["positive_volume"],2)."</td>";
	else echo "<td align=right></td>";
	echo "<td align=right>".number_format(100*$row["positive_float"]/$row["balance"],2)."</td>";
	echo "<td align=right>".$row["max_positive_float"]."</td>";
	echo "<td align=right>".number_format(100*$row["max_positive_float"]/$row["balance"],2)."</td>";
	echo "<td align=right>".$row["last_update"]."</td>";
	echo "</tr>";
	
	$name=$row["name"];
}

echo "<tr>";
echo "<td></td>";
echo "<td colspan=4>".substr($name,0,1)."</td>";
echo "<td align=right>".number_format($xtBal,2)."</td>";
echo "<td align=right>".number_format($xtEqty,2)."</td>";
echo "<td align=right>".number_format($xtFloat,2)."</td>";
echo "<td align=right>".number_format(100*$xtFloat/$xtBal,2)."%</td>";
echo "<td align=right colspan=3>".number_format($xtopen,0)."</td>";
echo "<td align=right colspan=2>".number_format($xtfm,2)."</td>";
echo "<td align=right colspan=1>".number_format(100*$xtfm/$xtEqty,2)."</td>";
echo "<td align=right colspan=1>".number_format($tlot,2)."</td>";	
echo "</tr>";

echo "<tr>";
echo "<td align=right colspan=6>".number_format($tBal,2)."</td>";
echo "<td align=right>".number_format($tEqty,2)."</td>";
echo "<td align=right>".number_format($tFloat,2)."</td>";
echo "<td align=right>".number_format(100*$tFloat/$tBal,2)."%</td>";
echo "<td align=right colspan=3>".number_format($topen,0)."</td>";
echo "<td align=right colspan=2>".number_format($tfm,2)."</td>";
echo "<td align=right colspan=1>".number_format(100*$tfm/$tEqty,2)."</td>";
echo "</tr>";
echo "</table>";
	
if(isset($_GET['open'])){
	echo "<table style='font-size:80%'>";
	echo "<tr>";
	echo "<td align=right>#</td>";
	echo "<td>Pair</td>";
	echo "<td>Dir</td>";
	echo "<td align=right>Lots</td>";
	echo "<td align=right>Count</td>";
	echo "<td align=right>Profit</td>";
	echo "<td align=right>height</td>";
	echo "<td align=right>gap</td>";
	echo "<td align=right>hgap</td>";
	echo "<td align=right>re</td>";
	echo "<td align=right>tp</td>";
	echo "<td align=right>d1Percent</td>";
	echo "<td align=right>D60</td>";
	echo "<td align=right>D300</td>";
	echo "<td align=right>M120</td>";
	echo "<td align=right>pc</td>";
	echo "<td align=right>xcount</td>";
	echo "<td align=right>re_xcount</td>";
	
	echo "<td align=right>tp_xcount</td>";
	
	echo "<td align=right>BT Buy</td>";
	echo "<td align=right>BT Sell</td>";
	echo "<td align=right>H24</td>";
	echo "<td>Last Update</td>";
	echo "<td>Remark</td>";
	echo "</tr>";
	$sql="SELECT * FROM account_open where login={$_GET['open']} order by tpGap";	
	//echo $sql;
	$res = $mysqli->query($sql);
	$n=0;
	$openVolume=0;
	$openCount=0;
	$openProfit=0;
	while($row = $res->fetch_assoc()){
		$sql="SELECT * from AdjCs28 where pair='{$row['pair']}'";
		$res1 = $mysqli->query($sql);
		$row1 = $res1->fetch_assoc();
			
		$n++;
		$openVolume+=$row['openVolume'];
		$openCount+=$row['openCount'];
		$openProfit+=$row['openProfit'];
		echo "<tr>";
		echo "<td align=right>".$n.".</td>";
		
		$bgcolor="";
		$color="";
		if($row['entry_enable']==0 && $row1['pipsh24']>=900 && ($row['d1Percent']<10 || $row['d1Percent']>90)){
			$color="style='color:red;font-weight:900;'";
			$bgcolor="white";
		}
		echo "<td $color bgcolor='$bgcolor'>".$row['pair']."</td>";
		echo "<td>".$row['dir']."</td>";
		echo "<td align=right>".$row['openVolume']."</td>";
		echo "<td align=right>".$row['openCount']."</td>";
		echo "<td align=right>".$row['openProfit']."</td>";
		$point=100000;
		if(substr($row['pair'],3,3)=="JPY"){
			$point=1000;
		}		
		echo "<td align=right>".number_format(($row['maxOpenPrice']-$row['minOpenPrice'])*$point)."</td>";
		
		$reEntry=false;
		if($row['dir']=='buy'){
			if($row1['priceclose']<$row['minOpenPrice'])$reEntry=true;
		}else{
			if($row1['priceclose']>$row['maxOpenPrice'])$reEntry=true;
		}
		
		echo "<td align=right>".$row['tpGap']."</td>";
		echo "<td align=right>".$row['gap1']."</td>";
		
		echo "<td align=right>".$row['re_pips']."</td>";
		
		echo "<td align=right>".$row['tp_pips']."</td>";
		
		$bgcolor="";
		$color="";
		if(($row['d1Percent']<10 || $row['d1Percent']>90) && $row1['pipsh24']>=900){
			$color="style='color:yellow;font-weight:900;'";
			$bgcolor="red";
		}
		echo "<td align=right $color bgcolor='$bgcolor'>".$row['d1Percent']."</td>";
		echo "<td align=right>".$row1['pctd60']."</td>";
		echo "<td align=right>".$row1['pctd300']."</td>";
		echo "<td align=right>".$row1['pctm120']."</td>";
		
		$bgcolor="";
		$color="";
		if($row['entry_enable']>0){
			$color="style='color:white;font-weight:900;'";
			$bgcolor="green";
		}
		echo "<td align=right $color bgcolor='$bgcolor'>".$row['entry_pc']."</td>";
		echo "<td align=right>".$row['xm1count']."</td>";
		echo "<td align=right>".$row['re_xcount']."</td>";
		
		echo "<td align=right>".$row['tp_xcount']."</td>";
		
		
		echo "<td align=right>".number_format($row['backtest_buy'],2)."</td>";
		echo "<td align=right>".number_format($row['backtest_sell'],2)."</td>";
		
		$bgcolor="";
		$color="";
		if($row1['pipsh24']>=900){
			$color="style='color:red;font-weight:900;'";
			$bgcolor="yellow";
		}
		echo "<td align=right $color bgcolor='$bgcolor'>".number_format($row1['pipsh24'],2)."</td>";
		echo "<td>".$row['lastupdate']."</td>";
		echo "<td>".$row['remark']."</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td align=right colspan=4>".$openVolume."</td>";
	echo "<td align=right>".$openCount."</td>";
	echo "<td align=right>".number_format($openProfit,2)."</td>";
	echo "</tr>";
	echo "</table>";
}
	
if(isset($_GET['login'])){
	echo "<table style='font-size:80%'>";
	echo "<tr>";
	echo "<td align=right>#</td>";
	echo "<td>Pair</td>";
	echo "<td>MG</td>";
	echo "<td>Dir</td>";
	echo "<td align=right>Lots</td>";
	echo "<td align=right>Count</td>";
	echo "<td align=right>Max</td>";
	echo "<td align=right>Float</td>";
	echo "<td align=right>Max</td>";
	echo "<td align=right>Gap</td>";
	echo "<td align=right>InpGap</td>";
	echo "<td align=right>XCount</td>";
	echo "<td align=right>InpCount</td>";
	echo "<td align=right>openPercent</td>";
	echo "<td align=right>gapPercent</td>";
	echo "<td align=right>H1</td>";
	echo "<td align=right>H2</td>";
	echo "<td align=right>H4</td>";
	echo "<td align=right>H24</td>";
	echo "<td align=right>D5</td>";
	echo "<td align=right>D300</td>";
	echo "<td>Last Update</td>";
	echo "</tr>";
	$sql="SELECT * FROM account_trade where login={$_GET['login']} and ncount>0 order by gappips desc";	
	$res = $mysqli->query($sql);
	$n=0;
	while($row = $res->fetch_assoc()){
		$sql="SELECT * from AdjCs28 where pair='{$row['pair']}'";
		$res1 = $mysqli->query($sql);
		$row1 = $res1->fetch_assoc();
			
		$n++;
		echo "<tr>";
		echo "<td align=right>".$n.".</td>";
		echo "<td><a href=/?login={$_GET['login']}&pair={$row['pair']}&magic={$row['magicnumber']}>".$row['pair']."</a></td>";
		echo "<td>".$row['magicnumber']."</td>";
		echo "<td>".$row['opentype']."</td>";
		echo "<td align=right>".$row['openlot']."</td>";
		echo "<td align=right>".$row['ncount']."</td>";
		echo "<td align=right>".$row['opencount']."</td>";
		echo "<td align=right>".$row['nfloat']."</td>";
		echo "<td align=right>".$row['maxfloat']."</td>";
		echo "<td align=right>".$row['gappips']."</td>";
		echo "<td align=right>".$row['inprepips']."</td>";
		echo "<td align=right>".$row['curxcount']."</td>";
		echo "<td align=right>".$row['inpxcount']."</td>";
		echo "<td align=right>".$row['inpopenpercent']."</td>";
		echo "<td align=right>".$row['gappercent']."</td>";
		echo "<td align=right>".$row1['pcth1']."</td>";
		echo "<td align=right>".$row1['pcth2']."</td>";
		echo "<td align=right>".$row1['pcth4']."</td>";
		echo "<td align=right>".$row1['pcth24']."</td>";
		echo "<td align=right>".$row1['pctd5']."</td>";
		if($row1['pctd300']>98 || $row1['pctd300']<2)echo "<td align=right style='color:purple;font-weight:900;'>".$row1['pctd300']."</td>";
		else echo "<td align=right>".$row1['pctd300']."</td>";
		echo "<td>".$row['lastupdate']."</td>";
		echo "</tr>";
	}
	echo "</table>";
}

echo "</center>";
