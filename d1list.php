<?php
include('gua64.php');

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM pairs_data_d1 where pair='{$_GET['pair']}' order by dt desc limit 20";         
        $res = $mysqli->query($sql);

        $html="<center>";
        $html.="<table width=80%>";
        $html.="<tr>";
        $html.="<td align=right>Date</td>";
        $html.="<td align=right>High</td>";
        $html.="<td align=right>Open</td>";
        $html.="<td align=right>Close</td>";
        $html.="<td align=right>Low</td>";
        $html.="<td align=right>Top</td>";
        $html.="<td align=right>Bottom</td>";
        $html.="<td align=right>WD</td>";
        $html.="<td align=right>Binary</td>";
        $html.="<td>挂象 1</td>";
        $html.="<td>挂象 2</td>";
        $html.="<td>挂象 3</td>";
        $html.="<td>挂象 4</td>";
        $html.="<td align=right>序号 1</td>";
        $html.="<td align=right>序号 2</td>";
        $html.="<td align=right>序号 3</td>";
        $html.="<td align=right>序号 4</td>";
        $html.="</tr>";
        while($row = $res->fetch_assoc()){
            $html.="<tr>";
            $html.="<td align=right>";
            $html.=substr($row['dt'],0,10);
            $html.="</td>";
            $html.="<td align=right>";
            $html.=number_format($row['price_high'],3);
            $html.="</td>";
            $html.="<td align=right>";
            $html.=number_format($row['price_open'],3);
            $html.="</td>";
            $html.="<td align=right>";
            $html.=number_format($row['price_close'],3);
            $html.="</td>";
            $html.="<td align=right>";
            $html.=number_format($row['price_low'],3);
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$row['hour_top'];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$row['hour_bottom'];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=date('w',strtotime($row['dt']));
            $html.="</td>";
            $binary=$row['h41'].$row['h42'].$row['h43'].$row['h44'].$row['h45'].$row['h46'];
            $html.="<td align=right>";
            $html.=$binary;
            $html.="</td>";
            $yijin=$aGua64[$binary];
            $html.="<td>";
            $html.=$yijin[0];
            $html.="</td>";
            $html.="<td>";
            $html.=$yijin[1];
            $html.="</td>";
            $html.="<td>";
            $html.=$yijin[2];
            $html.="</td>";
            $html.="<td>";
            $html.=$yijin[3];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$yijin[4];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$yijin[5];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$yijin[6];
            $html.="</td>";
            $html.="<td align=right>";
            $html.=$yijin[7];
            $html.="</td>";
            $html.="</tr>";
        }
        $html.="</table>";
        $html.="</center>";
        echo $html;
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}