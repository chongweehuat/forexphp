<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>pairIndex <?php if(isset($_GET['pair'])) echo $_GET['pair']; ?></title>
<head>
<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
//if(date('w')>0 && date('w')<6) echo '<meta http-equiv="refresh" content="15" />';	

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
$sparams="";
$spair="";
if(isset($_GET['pair'])){
    $sparams="?pair={$_GET['pair']}";
    $spair="&pair={$_GET['pair']}";
}

echo "<center><table width=90% style='font-size:80%'>";
echo "<tr>";
echo "<td align=right>#</td>";
echo "<td><a href=?>Pair</a></td>";

echo "<td align=right><a href=?od=12{$spair}>H24</a></td>";
echo "<td align=right><a href=?od=7{$spair}>PipsH24</a></td>";
echo "<td align=right>PipsH1</td>";
echo "<td align=right>AvrgH1</td>";
echo "<td align=right><a href=?od=4{$spair}>PipsH1/<br>AvrgH1</a></td>";
echo "<td align=right>AvrgHH1</td>";
echo "<td align=right>HeightH1</td>";
echo "<td align=right>HeightH1/AvrgHH1</td>";
echo "<td align=right>AvrgD1</td>";
echo "<td align=right><a href=?od=1{$spair}>HeightD1</a></td>";
echo "<td align=right><a href=?od=2{$spair}>HeightD1/<br>AvrgD1</a></td>";
echo "<td align=right>d30MinPrice</td>";
echo "<td align=right>d30MaxPrice</td>";
echo "<td align=right>d30 %</td>";	
echo "<td align=right>MaxM60U</td>";
echo "<td align=right>MaxM60D</td>";
echo "<td align=right>Trend</td>";	
echo "</tr>";

$od='pair';
if(isset($_GET['od']) && $_GET['od']==1)$od='heightD1';
if(isset($_GET['od']) && $_GET['od']==2)$od='heightD1/avrgD1';
if(isset($_GET['od']) && $_GET['od']==4)$od='pipsh1/avrgH1';
if(isset($_GET['od']) && $_GET['od']==7)$od='pipsh24';
if(isset($_GET['od']) && $_GET['od']==12)$od='pcth24 desc';
$sql="SELECT * from AdjCs28 order by $od desc";
if(isset($_GET['pair'])){
    $cur1=substr($_GET['pair'],0,3);
	$cur2=substr($_GET['pair'],3,3);
	$sql="SELECT * from AdjCs28 where (LOCATE('$cur1',pair)>0 or LOCATE('$cur2',pair)>0) order by $od";
}    
$res = $mysqli->query($sql);

$n=0;
$momentumC1=0;
$momentumC2=0;

$strengthC1=0;
$strengthC2=0;

$strengthD1C1=0;
$strengthD1C2=0;

$h24Pair=0;
$momentumPair=0; 
$strengthPair=0;   
$strengthD1Pair=0;  
while($row = $res->fetch_assoc()){
	$n++;

	$c1=substr($row['pair'],0,3);
	$c2=substr($row['pair'],3,3);

    $momentum=0; 
    $strength=0;   
    $strengthD1=0;  
    if($row['avrgh1']>0){
        $momentum=100*$row['pipsh1']/$row['avrgh1'];
        if($c1==$cur1 || $c2==$cur1)$momentumC1+=$momentum;
        if($c1==$cur2 || $c2==$cur2)$momentumC2+=$momentum;

        $strength=100*$row['heighth1']/$row['avrghh1'];
        if($c1==$cur1 || $c2==$cur1)$strengthC1+=$strength;
        if($c1==$cur2 || $c2==$cur2)$strengthC2+=$strength;

        $strengthD1=100*$row['heightd1']/$row['avrgd1'];
        if($c1==$cur1 || $c2==$cur1)$strengthD1C1+=$strengthD1;
        if($c1==$cur2 || $c2==$cur2)$strengthD1C2+=$strengthD1;

    }
    if($row['pair']==$_GET['pair']){
        $h24Pair=$row['pcth24'];
        $momentumPair=$momentum; 
        $strengthPair=$strength;   
        $strengthD1Pair=$strengthD1;
    }    

    $html="";
    if($n%2)$html="<tr bgcolor='#ccf2cb'>";
    else{
        $html="<tr>";
    }
    if($row['pair']==$_GET['pair'])$html="<tr bgcolor='yellow'>";
    echo $html;
    
	echo "<td align=right>".$n.".</td>";

	if(isset($_GET['pair'])){
		if($c1==$cur1)$c1="<span style='color:blue;font-weight: 900;font-size:120%;'>$c1</span>";
		if($c2==$cur1)$c2="<span style='color:blue;font-weight: 900;font-size:120%;'>$c2</span>";
		if($c1==$cur2)$c1="<span style='color:red;font-weight: 900;font-size:120%;'>$c1</span>";
		if($c2==$cur2)$c2="<span style='color:red;font-weight: 900;font-size:120%;'>$c2</span>";
	}
	
    echo "<td><a href=?pair={$row['pair']}>{$c1}{$c2}</a></td>";
        
    echo "<td align=right>".number_format($row['pcth24'])."</td>";
    echo "<td align=right>".number_format($row['pipsh24'])."</td>";
    echo "<td align=right>".number_format($row['pipsh1'])."</td>";
	echo "<td align=right>".number_format($row['avrgh1'])."</td>";
	if($row['avrgh1']>0){
        echo "<td align=right>".number_format($momentum)."</td>";
    }
    echo "<td align=right>".number_format($row['avrghh1'])."</td>";
    echo "<td align=right>".number_format($row['heighth1'])."</td>";	
    echo "<td align=right>".number_format($strength)."</td>";
    echo "<td align=right>".number_format($row['avrgd1'])."</td>";
    echo "<td align=right>".number_format($row['heightd1'])."</td>";	
    echo "<td align=right>".number_format($strengthD1)."</td>";
    echo "<td align=right>".number_format($row['d30MinPrice'],5)."</td>";	
    echo "<td align=right>".number_format($row['d30MaxPrice'],5)."</td>";
    echo "<td align=right>".number_format(100*($row['priceclose']-$row['d30MinPrice'])/($row['d30MaxPrice']-$row['d30MinPrice']),2)."</td>";
    echo "<td align=right>".number_format($row['MaxM60U'])."</td>";
    echo "<td align=right>".number_format($row['MaxM60D'])."</td>";
    echo "<td align=right>".number_format($row['MaxM60U']-$row['MaxM60D']);
    if($row['MaxM60U']>$row['MaxM60D'])echo "<span style='color:red;font-weight: 900;'>U</span>";
    else echo 'D';
    echo "</td>";	
    echo "</tr>";

}
echo "</table></center>";
$momentumC1=$momentumC1/7;
$momentumC2=$momentumC2/7;
$strengthC1=$strengthC1/7;
$strengthC2=$strengthC2/7;
$netMomentum=$momentumC2-$momentumC1;
$netStrength=$strengthC2-$strengthC1;
$netStrengthD1=$strengthD1C2-$strengthD1C1;
$html="<center><table width=60%>";
$html.="<tr><td>";
$html.="Momentum:</td><td align=right>$cur1 :</td><td align=right>".number_format($momentumC1)."</td><td align=right>$cur2 :</td><td align=right>".number_format($momentumC2)."</td><td align=right>Net:</td><td align=right>".number_format($netMomentum)."<td align=right>".number_format($momentumPair);
$html.="</td></tr>";
$html.="<tr><td>";
$html.=" Strength:</td><td align=right>$cur1 :</td><td align=right>".number_format($strengthC1)."</td><td align=right>$cur2 :</td><td align=right>".number_format($strengthC2)."</td><td align=right>Net:</td><td align=right>".number_format($netStrength)."<td align=right>".number_format($strengthPair)."<td align=right>".number_format($h24Pair)."%";
$html.="</td></tr>";
$html.="<tr><td>";
$html.=" D1 Strength:</td><td align=right>$cur1 :</td><td align=right>".number_format($strengthD1C1)."</td><td align=right>$cur2 :</td><td align=right>".number_format($strengthD1C2)."</td><td align=right>Net:</td><td align=right>".number_format($netStrengthD1)."<td align=right>".number_format($strengthD1Pair);
$html.="</td></tr>";
$html.="</table></center>";
echo $html;

