<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
    echo '<meta http-equiv="refresh" content="15" />';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>MagicTrade <?php echo $_GET['login']; ?></title>
<head>
<?php
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    
    $now=date("Y-m-d H:i:s");

    $sql="SELECT * FROM magicTrade where login={$_GET['login']} order by pair,opentype,magicnumber";
		
    $res = $mysqli->query($sql);
    
    $html="<center><table  style='font-size:80%' width=90%>";
    $html.="<tr>";
    $html.="<td>#</td>";
    $html.="<td>ID</td>";
    
    $html.="<td>Mg</td>";
    $html.="<td>Pair</td>";
    $html.="<td>Dir</td>";
    $html.="<td align=right>N</td>";
    $html.="<td align=right>Lot</td>";
    $html.="<td align=right>PF</td>";
    $html.="<td align=right>PP</td>";
    
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>SL</td>";
    $html.="<td align=right>TP</td>";
    $html.="<td align=right>RRR</td>";
    $html.="<td align=right>LPrice</td>";
    $html.="<td align=right>UPrice</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>AH1</td>";
    $html.="<td align=right>dGap</td>";
    $html.="<td align=right>dLot</td>";
    $html.="<td align=right>XG</td>";
    $html.="<td align=right>dMax</td>";
    $html.="<td align=right>dC</td>";
    $html.="<td align=right>dN</td>";
    $html.="<td align=right>Bid</td>";
    $html.="<td align=right>Ask</td>";
    $html.="<td align=right>LL</td>";
    $html.="<td align=right>UL</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>Bottom</td>";
    $html.="<td align=right>Top</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td>Date</td>";
    $html.="<td>Note</td>";
    $html.="<td>Rmk</td>";
    $html.="</tr>";
    $n=0;
    $opencount=0;
    $openlot=0;
    $openprofit=0;
    $openpnl=0;
    $last_pair='';
    $pair_count=0;
    $tp_sum=0;
    $sl_sum=0;

    $sql1="SELECT * FROM account where login={$_GET['login']}";
    $res1 = $mysqli->query($sql1);
    $row1 = $res1->fetch_assoc();
    $html.="<tr><td colspan=115>";
    $html.=$_GET['login'];
    $html.=" / BAL:";
    $html.=number_format($row1['balance'],2);
    $html.=" / EQT:";
    $html.=number_format($row1['equity'],2);
    $html.=" / AF:";
    $html.=number_format($row1['account_float'],2);
    $html.=" / FM:";
    $html.=number_format($row1['margin_free'],2);
    $html.="</td></tr>";

    while($row = $res->fetch_assoc()){
        $sql2="SELECT * FROM AdjCs28 where pair='{$row['pair']}'";
        //echo $sql2;
        $res2 = $mysqli->query($sql2);
        $row2 = $res2->fetch_assoc();
        
        $n++;

        $opencount+=$row['opencount'];
        $openlot+=$row['openlot'];
        $openprofit+=$row['openprofit'];
        $openpnl+=$row['openpnl'];

        $sl_sum+=$row['nsl'];
        $tp_sum+=$row['ntp'];

        if(!isset($mgOpenCount[$row['magicnumber']]))$mgOpenCount[$row['magicnumber']]=0;
        if(!isset($mgOpenLot[$row['magicnumber']]))$mgOpenLot[$row['magicnumber']]=0;
        if(!isset($mgOpenProfit[$row['magicnumber']]))$mgOpenProfit[$row['magicnumber']]=0;
        if(!isset($mgOpenPnL[$row['magicnumber']]))$mgOpenPnL[$row['magicnumber']]=0;

        $mgOpenCount[$row['magicnumber']]+=$row['opencount'];
        $mgOpenLot[$row['magicnumber']]+=$row['openlot'];
        $mgOpenProfit[$row['magicnumber']]+=$row['openprofit'];
        $mgOpenPnL[$row['magicnumber']]+=$row['openpnl'];

        if($last_pair!=$row['pair']){
            $last_pair=$row['pair'];
            if($pair_count>0)$html.="<tr><td></td></tr>";
            $pair_count++;
        }
        
        if($row['opencount']==0)$html.="<tr bgcolor=#FFFF00>";
        else $html.="<tr>";
        $html.="<td>";
        $html.=$n;
        $html.="</td>";
        $html.="<td>";
        $html.="<a href=/mgEdit.php?id={$row['id']} target='mgEdit'>";
        $html.=$row['id'];
        $html.="</a>";
        $html.="</td>";
        
        $html.="<td>";
        $html.=$row['magicnumber'];
        $html.="</td>";
        $url="/addnew.php?login={$row['login']}&pair={$row['pair']}&xdir={$row['opentype']}&nmagic={$row['magicnumber']}";
        $html.="<td><a href=$url target='addnew'>";
        $html.=$row['pair'];
        $html.="</a></td>";
        $html.="<td>";
        $html.=$row['opentype'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['opencount'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['openlot'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['openprofit'];
        $html.=" / ";
        $html.=number_format(100*$row['openprofit']/$row1['equity'],1);
        $html.="%</td>";
        $html.="<td align=right>";
        if($row['openpnl']>0)$html.="<a href=/mgTpNow.php?id={$row['id']} target=tpnow>";
        $html.=$row['openpnl'];
        if($row['openpnl']>0)$html.="</a>";
        $html.="</td>";

        if($row['opentype']=="buy"){
            $gap0=($row['bid']-$row['minPrice'])/$row['point'];
        }else{
            $gap0=($row['maxPrice']-$row['ask'])/$row['point'];    
        }

        $color="blue";
        if($gap0<0)$color="red";
        $html.="<td align=right style='color:$color'>";
        if($row['opencount']>0)$html.=number_format($gap0);
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['nsl'];
        $html.=" / ";
        $html.=number_format(100*$row['nsl']/$row1['equity'],1);
        $html.="%</td>";
        $html.="<td align=right>";
        $html.=number_format($row['ntp'],2);
        $html.=" / ";
        $html.=number_format(100*$row['ntp']/$row1['equity'],1);
        $html.="%</td>";
        $html.="<td align=right>";
        if($row['nsl']>0)$html.=number_format($row['ntp']/$row['nsl']);
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['LPrice'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['UPrice'];
        $html.="</td>";
        if($row['opentype']=="buy"){
            $gap=($row['bid']-$row['LPrice'])/$row['point'];
        }else{
            $gap=($row['UPrice']-$row['ask'])/$row['point'];    
        }
        
        $color="blue";
        if($gap<=200)$color="red";
        $html.="<td align=right style='color:$color'>";
        if($row['UPrice']>0)$html.=number_format($gap);
        $html.="</td>";

        $html.="<td align=right>";
        if(isset($row2['avrghh1']))$html.=$row2['avrghh1'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['dGap'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['dLot'];
        $html.="</td>";

        $color="blue";
        if(($gap/$row['dGap'])>$row['dNew'])$color="red";
        $html.="<td align=right style='color:$color'>";
        if($row['UPrice']>0)$html.=number_format($gap/$row['dGap']);
        $html.="</td>";
       
        $html.="<td align=right>";
        $html.=$row['dMax'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['dCount'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['dNew'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['bid'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['ask'];
        $html.="</td>";

        $color="blue";
        if($row['opentype']=="buy"){
            if($row['ULimit']>0 && $row['ULimit']<=$row['topPrice'])$color="green";
            if($row['ULimit2']>0 && $row['ULimit2']<=$row['topPrice'])$color="gold";
            if($row['ULimit3']>0 && $row['ULimit3']<=$row['topPrice'])$color="red";
        }else{
            if($row['LLimit']>0 && $row['LLimit']>=$row['bottomPrice'])$color="green";
            if($row['LLimit2']>0 && $row['LLimit2']>=$row['bottomPrice'])$color="gold";
            if($row['LLimit3']>0 && $row['LLimit3']>=$row['bottomPrice'])$color="red";
        }
        $html.="<td align=right style='color:$color'>";
        $html.=$row['LLimit'];
        if($row['LLimit2'])$html.='<br>'.$row['LLimit2'];
        if($row['LLimit3'])$html.='<br>'.$row['LLimit3'];
        $html.="</td>";
        $html.="<td align=right style='color:$color'>";
        $html.=$row['ULimit'];
        if($row['ULimit2'])$html.='<br>'.$row['ULimit2'];
        if($row['ULimit3'])$html.='<br>'.$row['ULimit3'];
        $html.="</td>";

        if($row['opentype']=="buy"){
            $gap=($row['ULimit']-$row['bid'])/$row['point'];
        }else{
            $gap=($row['ask']-$row['LLimit'])/$row['point'];
        }
        $html.="<td align=right>";
        if($row['ULimit'] || $row['LLimit'])$html.=number_format($gap);
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['bottomPrice'];
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['topPrice'];
        $html.="</td>";

        if($row['opentype']=="buy"){
            $gap=($row['topPrice']-$row['bid'])/$row['point'];
        }else{
            $gap=($row['ask']-$row['bottomPrice'])/$row['point'];
        }
        $html.="<td align=right>";
        if($row['opencount']>0)$html.=number_format($gap);
        $html.="</td>";

        $html.="<td>";
        $html.=substr($row['lastupdate'],5);
        $html.="</td>";

        $html.="<td>";
        $html.=$row['note'];
        $html.="</td>";

        $html.="<td>";
        $html.=$row['remark'];
        $html.="</td>";
        
        $html.="</tr>";
    }
    $html.="<tr>";
    $html.="<td align=right colspan=6>";
    $html.=$opencount;
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($openlot,2);
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($openprofit,2);
    $html.=" / ";
    $html.=number_format(100*$openprofit/$row1['equity'],1);
    $html.="%</td>";
    $html.="<td align=right>";
    $html.=number_format($openpnl,2);
    $html.="</td>";
    $html.="<td align=right colspan=2>";
    $html.=number_format($sl_sum,2);
    $html.=" / ";
    $html.=number_format(100*$sl_sum/$row1['equity'],1);
    $html.="%</td>";
    $html.="<td align=right>";
    $html.=number_format($tp_sum,2);
    $html.=" / ";
    $html.=number_format(100*$tp_sum/$row1['equity'],1);
    $html.="%</td>";
    $html.="</tr>";
 
    foreach($mgOpenCount as $mg=>$n){
        $html.="<tr>";
        $html.="<td align=right colspan=5>";
        $html.=$mg;
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$n;
        $html.="</td>"; 
        $html.="<td align=right>";
        $html.=number_format($mgOpenLot[$mg],2);
        $html.="</td>";
        $html.="<td align=right>";
        $html.=number_format($mgOpenProfit[$mg],2);
        $html.=" / ";
        $html.=number_format(100*$mgOpenProfit[$mg]/$row1['equity'],1);
        $html.="%</td>";
        $html.="<td align=right>";
        $html.=number_format($mgOpenPnL[$mg],2);
        $html.="</td>";
        $html.="</tr>";
    }

    $html.="</table></center>";

    echo $html;

    echo "<center>Avoid level entry , single entry only on border of support or resistance with SL<br>";
    echo "<iframe src=/pairIndex.php?od=2&pair=GBPJPY height=400 width=1200 title='pairIndex GBPJPY'>";
    echo "</center>";
}