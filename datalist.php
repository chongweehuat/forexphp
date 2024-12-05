<?php

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM pairs_data_h1 where pair='{$_GET['pair']}' and price_max>0 order by dt";         
        $res = $mysqli->query($sql);

        $html="<center><table  style='font-size:80%' width=90%>";
        $html.="<tr>";
        $html.="<td>Date</td>";
        $html.="<td>Price</td>";
        $html.="<td>Pips</td>";
        $html.="</tr>";

        $price_max=0;
        while($row = $res->fetch_assoc()){
            $html.="<tr>";
            $html.="<td>{$row['dt']}</td>";
            $html.="<td>{$row['price_max']}</td>";
            $html.="<td>";
            $html.=number_format(1000*($row['price_max']-$price_max));
            $html.="</td>";
            $html.="</tr>";    
            $price_max=$row['price_max'];
        }
        $html.="</table></center>";
        echo $html;
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}