<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>adjcs28</title>
<head>
<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
if(date('w')>0 && date('w')<6) echo '<meta http-equiv="refresh" content="5" />';	

//$mysqli = new mysqli("localhost", "fbsonline", "30Fu45y7qjsH9dze", "fbsonline");
//$mysqli = new mysqli("localhost", "my369forex", "30Fu45y7qjsH9dze", "my369forex");
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
$sparams="";
$spair="";
if(isset($_GET['pair'])){
    $sparams="?pair={$_GET['pair']}";
    $spair="&pair={$_GET['pair']}";
}

echo "<center>";
if(isset($_GET['pair'])){
	$sql="SELECT buyPrice,sellPrice,dayCount from AdjCs28 where pair='{$_GET['pair']}'";
	$res = $mysqli->query($sql);
	$obj = $res->fetch_object();
	echo $_GET['pair'];
	echo " ";
	echo "Buy:";
	echo $obj->buyPrice;
	echo " Sell:";
	echo $obj->sellPrice;
	echo " Day:";
	echo $obj->dayCount;
	echo " ";
}
$sql="SELECT lastupdate from AdjCs28 limit 1";
$res = $mysqli->query($sql);
$obj = $res->fetch_object();	
echo "<a href=chartcs28.php{$sparams} target=chartcs28>{$obj->lastupdate}</a> ";
echo "</center>";	
echo "<center><table width=90% style='font-size:80%'>";
echo "<tr>";
echo "<td align=right>#</td>";
echo "<td><a href=?>Pair</a></td>";
echo "<td align=right><a href=?od=1{$spair}>Pos%</a></td>";
echo "<td align=right>Move</td>";
echo "<td align=right><a href=?od=2{$spair}>D5%</a></td>";
echo "<td align=right>D5M</td>";
echo "<td align=right>Dir</td>";
//echo "<td align=right>Last Update</td>";	
echo "<td align=right>LLW</td>";
echo "<td align=right>LW</td>";
echo "<td align=right>POS</td>";
echo "<td align=right><a href=?od=6{$spair}>DIF</a></td>";
echo "<td align=right>LDIF</td>";	
echo "<td align=right>RT</td>";	
echo "<td align=right>D</td>";	
	
//echo "<td align=right>H1</td>";
//echo "<td align=right>H2</td>";
//echo "<td align=right>H4</td>";
echo "<td align=right>H8</td>";
echo "<td align=right>H12</td>";
echo "<td align=right>H16</td>";
echo "<td align=right>H20</td>";


echo "<td align=right>D5</td>";
echo "<td align=right>D10</td>";
echo "<td align=right>D20</td>";
echo "<td align=right>D30</td>";
echo "<td align=right>D60</td>";
echo "<td align=right>D200</td>";
echo "<td align=right>D300</td>";
echo "<td align=right><a href=?od=3{$spair}>M120</a></td>";
echo "<td align=right><a href=?od=5{$spair}>H</a></td>";
	
echo "<td align=right>M5X</td>";
echo "<td align=right>H1X</td>";
echo "<td align=right><a href=?od=12{$spair}>H24</a></td>";
echo "<td align=right><a href=?od=7{$spair}>PipsH24</a></td>";
echo "<td align=right>PipsH1</td>";
echo "<td align=right>AvrgH1</td>";
echo "<td align=right><a href=?od=4{$spair}>PipsH1/AvrgH1</a></td>";
	
	
echo "</tr>";

$od='pair';
if(isset($_GET['od']) && $_GET['od']==1)$od='pospct';	
if(isset($_GET['od']) && $_GET['od']==2)$od='d5pospct';
if(isset($_GET['od']) && $_GET['od']==3)$od='pctm120';
if(isset($_GET['od']) && $_GET['od']==4)$od='pipsh1/avrgH1';
if(isset($_GET['od']) && $_GET['od']==5)$od='d100max/d100min';	
if(isset($_GET['od']) && $_GET['od']==6)$od='(lwpriceclose-priceclose)/(buyPrice-sellPrice)';
if(isset($_GET['od']) && $_GET['od']==7)$od='pipsh24';
if(isset($_GET['od']) && $_GET['od']==9)$od='m1pipsdown60';
if(isset($_GET['od']) && $_GET['od']==12)$od='pcth24';
$sql="SELECT * from AdjCs28 order by $od desc";
if(isset($_GET['pair'])){
	if(isset($_GET['dir'])){
		$pair=$_GET['pair'];
		$sql="update AdjCs28 set pair_dir={$_GET['dir']} where pair='$pair'";
		$mysqli->query($sql);
	}
	if(isset($_GET['buy'])){
		$pair=$_GET['pair'];
		$sql="update AdjCs28 set buyPrice={$_GET['buy']} where pair='$pair'";
		$mysqli->query($sql);
	}
	if(isset($_GET['sell'])){
		$pair=$_GET['pair'];
		$sql="update AdjCs28 set sellPrice={$_GET['sell']} where pair='$pair'";
		$mysqli->query($sql);
	}
	if(isset($_GET['day'])){
		$pair=$_GET['pair'];
		$sql="update AdjCs28 set dayCount={$_GET['day']} where pair='$pair'";
		$mysqli->query($sql);
	}
	$cur1=substr($_GET['pair'],0,3);
	$cur2=substr($_GET['pair'],3,3);
	$sql="SELECT * from AdjCs28 where (LOCATE('$cur1',pair)>0 or LOCATE('$cur2',pair)>0) order by $od desc";
	
}
//file_put_contents('debug.log',$sql);
$res = $mysqli->query($sql);

$n=0;
$pcTotal=0;
$moveTotal=0;
$d5pcTotal=0;
$d5moveTotal=0;
$topPct=0;
$topMv=0;
$d5topPct=0;
$d5topMv=0;
$m5CrossCount=0;
$h1CrossCount=0;

while($row = $res->fetch_assoc()){
	$n++;

    $m5CrossCount+=$row['m5CrossCount'];  
    $h1CrossCount+=$row['h1CrossCount'];  

	$c1=substr($row['pair'],0,3);
	$c2=substr($row['pair'],3,3);

    if($topPct==0){
        $topPct=$row['pospct'];
        $topMv=$row['pospct']-50;
    }
    $bottomPct=$row['pospct'];
    $bottomMv=$row['pospct']-50;

    if($d5topPct==0){
        $d5topPct=$row['d5pospct'];
        $d5topMv=$row['d5pospct']-50;
    }
    $d5bottomPct=$row['d5pospct'];
    $d5bottomMv=$row['d5pospct']-50;

    if($row['h1CrossCount']>=15){
        echo "<tr bgcolor='yellow'>";
    }else{
	
        if($n%2)echo "<tr bgcolor='#ccf2cb'>";
        else{
            echo "<tr>";
        }
    }
	
	echo "<td align=right>".$n.".</td>";

	if(isset($_GET['pair'])){
		if($c1==$cur1)$c1="<span style='color:blue;font-weight: 900;font-size:120%;'>$c1</span>";
		if($c2==$cur1)$c2="<span style='color:blue;font-weight: 900;font-size:120%;'>$c2</span>";
		if($c1==$cur2)$c1="<span style='color:red;font-weight: 900;font-size:120%;'>$c1</span>";
		if($c2==$cur2)$c2="<span style='color:red;font-weight: 900;font-size:120%;'>$c2</span>";
	}
	
	$title="B{$row['buyPrice']} S{$row['sellPrice']}";
    echo "<td><a href=?pair={$row['pair']} title='$title'>{$c1}{$c2}</a></td>";
        
    $color='red';
    if($row['pospct']>50)$color='blue';
	echo "<td align=right style='color:$color;'>".number_format($row['pospct'],2)."</td>";

    $pcTotal+=$row['pospct'];

    echo "<td align=right style='color:$color;'>".number_format($row['pospct']-50,2)."</td>";

    $moveTotal+=$row['pospct']-50;

    $color='red';
    if($row['d5pospct']>50)$color='blue';
	echo "<td align=right style='color:$color;'>".number_format($row['d5pospct'],2)."</td>";

    $d5pcTotal+=$row['d5pospct'];

    echo "<td align=right style='color:$color;'>".number_format($row['d5pospct']-50,2)."</td>";

    $d5moveTotal+=$row['d5pospct']-50;

	if($row['pair_dir']>0){
		echo "<td align=right>".number_format($row['pair_dir'])."</td>";
	}else{	
		$pair_dir=0;
		$pos=100*($row['priceclose']-$row['buyPrice'])/($row['sellPrice']-$row['buyPrice']);
		if($pos>=90)$pair_dir=2;
		elseif($pos<=10)$pair_dir=1;
		echo "<td align=right>*".number_format($pair_dir)."</td>";
	}
	
	//echo "<td align=right>".$row['last_dir'].' '.substr($row['last_dir_check'],5)."</td>";
	
	if($row['buyPrice']>0 && $row['sellPrice']>0){
		$posll=100*($row['llwpriceclose']-$row['buyPrice'])/($row['sellPrice']-$row['buyPrice']);
		$color='';
		if($posll<=10 || $posll>=90)$color='red';
		echo "<td align=right style='color:$color;'>".number_format($posll)."</td>";
		
		$pos0=100*($row['lwpriceclose']-$row['buyPrice'])/($row['sellPrice']-$row['buyPrice']);
		$color='';
		if($pos0<=10 || $pos0>=90)$color='red';
		echo "<td align=right style='color:$color;'>".number_format($pos0)."</td>";
		
		$pos=100*($row['priceclose']-$row['buyPrice'])/($row['sellPrice']-$row['buyPrice']);
		$color='';
		if($pos<=10 || $pos>=90)$color='red';
		echo "<td align=right style='color:$color;'>".number_format($pos)."</td>";
		$color='';
		$diff=$pos-$pos0;
		if(($diff>=20 && $pos>=100) || ($diff<=-20 && $pos<=0))$color='red';
		echo "<td align=right style='color:$color;'>".number_format($diff)."</td>";
		
		$diffll=$pos-$posll;
		echo "<td align=right>".number_format($diffll)."</td>";
		
		$pairdif[$row['pair']]=$diff;
		$pairdifll[$row['pair']]=$diffll;
	}else{
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
	}
	
	$ratio=100*($row['sellPrice']-$row['buyPrice'])/($row['d100max']-$row['d100min']);
	$color='';
	if($ratio>=48)$color='red';
	echo "<td style='color:$color;'>".number_format($ratio)."</td>";
	
	echo "<td align=right>".number_format($row['dayCount'])."</td>";
	
    //renderData($row['pcth1']);
    //renderData($row['pcth2']);
    //renderData($row['pcth4']);
    renderData($row['pcth8']);
    renderData($row['pcth12']);
    renderData($row['pcth16']);
    renderData($row['pcth20']);
    

    renderData($row['pctd5']);
    renderData($row['pctd10']);
    renderData($row['pctd20']);
    renderData($row['pctd30']);
    renderData($row['pctd60']);
    renderData($row['pctd200']);
    renderData($row['pctd300']);
	renderData($row['pctm120']);
	
    echo "<td align=right>".number_format((100*$row['d100max']/$row['d100min'])-100)."</td>";
	
    echo "<td align=right>".number_format($row['m5CrossCount'])."</td>";
    echo "<td align=right>".number_format($row['h1CrossCount'])."</td>";
	renderData($row['pcth24']);
    echo "<td align=right>".number_format($row['pipsh24'])."</td>";
    echo "<td align=right>".number_format($row['pipsh1'])."</td>";
	echo "<td align=right>".number_format($row['avrgh1'])."</td>";
	if($row['avrgh1']>0)echo "<td align=right>".number_format(100*$row['pipsh1']/$row['avrgh1'])."</td>";
	
	//echo "<td align=right>".number_format($row['pipsh1'])."</td>";
	//echo "<td align=right>".number_format($row['m1pipsdown60'])."</td>";
	//echo "<td align=right>".number_format($row['m1pipsup60'])."</td>";
	
    echo "</tr>";

	if(!isset($aCur[$c1]))$aCur[$c1]=0;
	if(!isset($aCur[$c2]))$aCur[$c2]=0;
	$aCur[$c1]+=$row['pcth24'];
	$aCur[$c2]+=100-$row['pcth24'];

	$aPair[$row['pair']]=0;

}

if(!isset($_GET['pair'])){
	asort($aCur);

	echo "<tr>";
	echo "<td colspan=40 align=center>";
	foreach($aCur as $cur=>$v){
		echo "$cur:$v ";	
	}
	echo "</td>";
	echo "</tr>";
	foreach($aPair as $p=>$v){
		$aPair[$p]=$aCur[substr($p,0,3)]-$aCur[substr($p,3,3)];
	}
	asort($aPair);
	echo "<td colspan=40 align=center>";
	$n=0;
	foreach($aPair as $p=>$v){
		$n++;
		echo "$p:";
		echo number_format($v);
		if($n==14)echo "<br>";
		else echo " ";	
	}
	echo "</td>";
	echo "</tr>";
}
echo "</table></center>";

function renderData($r){
    if(isset($dir) && $dir=="D"){
        $color='red';
    }else{
        $color='blue';
    }
    $bc='';
    if($r<=10 || $r>=90)$bc='#C2F784';
    if($r<=5 || $r>=95)$bc='#FFF47D';
    if($r<=2 || $r>=98)$bc='#FFBF86';
    echo "<td align=right style='color:$color;background-color:$bc;'>".number_format($r,2)."</td>";
}
