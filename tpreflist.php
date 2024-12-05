'<meta http-equiv="refresh" content="15" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>Take Profit Reference</title>
<head>
<?php

$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    try{

        $sql="SELECT * FROM tpref order by month_section,week_day,day_section";         
        $res = $mysqli->query($sql);

        $html="<center><table  style='font-size:80%' width=90%>";
        $html.="<tr>";
        $html.="<td>#</td>";
        $html.="<td align=right>month_section</td>";
        $html.="<td align=right>week_day</td>";
        $html.="<td align=right>day_section</td>";
        $html.="<td align=right>last_profitlot</td>";
        $html.="<td align=right>last_pips</td>";
        $html.="<td align=right>close_profitlot</td>";
        $html.="<td align=right>close_pips</td>";
        $html.="<td align=right>max_profitlot</td>";
        $html.="<td align=right>max_pips</td>";
        $html.="</tr>";

        while($row = $res->fetch_assoc()){
            
            $html.="<tr>";
            $html.="<td>".$row['id']."</td>";
            $html.="<td align=right>".$row['month_section']."</td>";
            $html.="<td align=right>".$row['week_day']."</td>";
            $html.="<td align=right>".$row['day_section']."</td>";
            $html.="<td align=right>".number_format($row['last_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['last_pips'],2)."</td>";
            $html.="<td align=right>".number_format($row['close_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['close_pips'],2)."</td>";
            $html.="<td align=right>".number_format($row['max_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['max_pips'],2)."</td>";            
            $html.="</tr>";    
                
        }
        $html.="</table></center>";
        echo $html;
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}