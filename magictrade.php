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
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $now=date("Y-m-d H:i:s");

    $sql="SELECT * FROM account_magic_trade where login={$_GET['login']} order by pair,tp_mode,magicnumber";
		
    $res = $mysqli->query($sql);
    
    $html="<center><table  style='font-size:80%' width=90%>";
    $html.="<tr>";
    $html.="<td>#</td>";
    $html.="<td>ID</td>";
    $html.="<td>Account</td>";
    $html.="<td>Mg</td>";
    $html.="<td>Pair</td>";
    $html.="<td>Dir</td>";
    $html.="<td align=right>N</td>";
    $html.="<td align=right>Lot</td>";
    $html.="<td align=right>PF</td>";
    $html.="<td align=right>PP</td>";
    $html.="<td align=right>W1</td>";
    $html.="<td align=right>D1</td>";
    $html.="<td align=right>H4</td>";
    $html.="<td align=right>H1</td>";
    $html.="<td align=right>M15</td>";
    $html.="<td align=right>H</td>";
    $html.="<td align=right>*</td>";
    $html.="<td align=right>TPS</td>";
    $html.="<td align=right>C</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>SL</td>";
    $html.="<td align=right>TP</td>";
    $html.="<td align=right>R</td>";
    $html.="<td align=right>LPrice</td>";
    $html.="<td align=right>UPrice</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>Dif</td>";
    $html.="<td align=right>Entry</td>";
    $html.="<td align=right>Lot</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td align=right>Pips</td>";
    $html.="<td align=right>RE</td>";
    $html.="<td align=right>MF</td>";
    $html.="<td align=right>Gap</td>";
    $html.="<td>Date</td>";
    $html.="<td>Remark</td>";
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
    while($row = $res->fetch_assoc()){
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
        $html.="<a href=/magicEdit.php?id={$row['id']} target='magicEdit'>";
        $html.=$row['id'];
        $html.="</a>";
        $html.="</td>";
        $html.="<td>";
        $html.=$row['login'];
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
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['openpnl'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['w1Height'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['d1Height'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['h4Height'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['h1Height'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['m15Height'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['tpRatio'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['tp_mode'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['tp_pips'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=number_format($row['h4Height']/$row['m15Height'],1);
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
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['ntp'];
        $html.="</td>";
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
        if($gap>=200)$color="red";
        $html.="<td align=right style='color:$color'>";
        if($row['UPrice']>0)$html.=number_format($gap);
        $html.="</td>";

        $color="blue";
        if(($gap-$gap0)>=200)$color="red";
        $html.="<td align=right style='color:$color'>";
        if($row['opencount']>0)$html.=number_format($gap-$gap0);
        $html.="</td>";

        $color="blue";
        
        if($row['opentype']=="buy"){
            if($row['entryPrice']>$row['minPrice'])$color="red";
        }else{
            if($row['entryPrice']<$row['maxPrice'])$color="red";
        }
        if($row['entryPrice']<0)$color="red; font-weight: 900; font-size: 16px;";
        $html.="<td align=right style='color:$color'>";
        $html.=$row['entryPrice'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['entryVol'];
        $html.="</td>";

        $html.="<td align=right>";
        if($row['opencount']==0 && $row['entryPrice']>0){
            if($row['opentype']=="buy")$html.=number_format(($row['ask']-$row['entryPrice'])/$row['point']);
            else $html.=number_format(($row['entryPrice']-$row['bid'])/$row['point']);
        }
        $html.="</td>";

        $html.="<td align=right>";
        $html.=$row['rePips'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['reVol'];
        $html.="</td>";
        $html.="<td align=right>";
        $html.=$row['maxfactor'];
        $html.="</td>";

        if($row['opencount']>0 && $row['rePips']>0){
            if($row['opentype']=="buy")$gap=($row['minPrice']-$row['ask'])/$row['point'];
            else $gap=($row['bid']-$row['maxPrice'])/$row['point'];
        }
        $color="blue";    
        if($gap>=$row['rePips'])$color='red';
        $html.="<td align=right style='color:$color'>";
        if($row['opencount']>0)$html.=number_format($gap);
        $html.="</td>";

        $html.="<td>";
        $html.=$row['lastupdate'];
        $html.="</td>";

        $html.="<td>";
        $html.=$row['remark'];
        $html.="</td>";
        
        $html.="</tr>";
    }
    $html.="<tr>";
    $html.="<td align=right colspan=7>";
    $html.=$opencount;
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($openlot,2);
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($openprofit,2);
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($openpnl,2);
    $html.="</td>";
    $html.="<td align=right colspan=11>";
    $html.=number_format($sl_sum,2);
    $html.="</td>";
    $html.="<td align=right>";
    $html.=number_format($tp_sum,2);
    $html.="</td>";
    $html.="</tr>";
 
    foreach($mgOpenCount as $mg=>$n){
        $html.="<tr>";
        $html.="<td align=right colspan=6>";
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
        $html.="</td>";
        $html.="<td align=right>";
        $html.=number_format($mgOpenPnL[$mg],2);
        $html.="</td>";
        $html.="</tr>";
    }

    $html.="</table></center>";

    echo $html;
}