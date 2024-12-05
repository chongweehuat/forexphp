'<meta http-equiv="refresh" content="15" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>Take Profit</title>
<head>
<?php

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM takeprofit where login='{$_GET['login']}' order by id desc limit 30";         
        $res = $mysqli->query($sql);

        $html="<center><table  style='font-size:80%' width=90%>";
        $html.="<tr>";
        $html.="<td>Date</td>";
        $html.="<td align=right>openProfit</td>";
        $html.="<td align=right>maxProfit</td>";
        $html.="<td align=right>openProfitLot</td>";
        $html.="<td align=right>maxProfitLot</td>";
        $html.="<td align=right>maxOpenProfitLot</td>";
        $html.="<td align=right><a href=/tpdatalist.php target=tpdata>topProfitLot</a></td>";
        $html.="<td align=right>Pair</td>";
        $html.="</tr>";

        while($row = $res->fetch_assoc()){
            $html.="<tr>";
            $html.="<td>{$row['datetime']}</td>";
            $html.="<td align=right>".number_format($row['openProfit'],2)."</td>";
            $html.="<td align=right>".number_format($row['maxProfit'],2)."</td>";
            $html.="<td align=right>".number_format($row['openProfitLot'],2)."</td>";
            $html.="<td align=right>".number_format($row['maxProfitLot'],2)."</td>";
            $html.="<td align=right>".number_format($row['maxOpenProfitLot'],2)."</td>";
            $html.="<td align=right>".number_format($row['topProfitLot'],2)."</td>";
            $html.="<td align=right>".$row['pair']."</td>";
            $html.="</tr>";    
            
        }
        $html.="</table></center>";
        echo $html;
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}