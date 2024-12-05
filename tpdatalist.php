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
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $now=date("Y-m-d H:i:s");
        
        $j=date("j");
        $month_section=1;
        if($j>10 && $j<=20)$month_section=2;
        if($j>20)$month_section=3;
        
        $week_day=date("w");

        $sql="SELECT * FROM tpdata order by month_section,week_day,`day_hour`";         
        $res = $mysqli->query($sql);

        $html="<center><table  style='font-size:80%' width=90%>";
        $html.="<tr>";
        $html.="<td>#</td>";
        $html.="<td align=right><a href='#today'>month_section</a></td>";
        $html.="<td align=right>week_day</td>";
        $html.="<td align=right>day_hour</td>";
        $html.="<td align=right>last_profitlot</td>";
        $html.="<td align=right>last_pips</td>";
        $html.="<td align=right>close_profitlot</td>";
        $html.="<td align=right>close_pips</td>";
        $html.="<td align=right>max_profitlot</td>";
        $html.="<td align=right>max_pips</td>";
        $html.="</tr>";

        $markToday=0;
        while($row = $res->fetch_assoc()){
            if($row['month_section']==$month_section && $row['week_day']==$week_day)$markToday++;
            if($markToday==1)$html.="<tr id='today'>";
            else $html.="<tr>";
            $html.="<td>".$row['id']."</td>";
            $html.="<td align=right>".$row['month_section']."</td>";
            $html.="<td align=right>".$row['week_day']."</td>";
            $html.="<td align=right>".$row['day_hour']."</td>";
            $html.="<td align=right>".number_format($row['last_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['last_pips'],2)."</td>";
            $html.="<td align=right>".number_format($row['close_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['close_pips'],2)."</td>";
            $html.="<td align=right>".number_format($row['max_profitlot'],2)."</td>";
            $html.="<td align=right>".number_format($row['max_pips'],2)."</td>";
            if($row['last_profitlot2']>0){
                $html.="<tr>";
                $html.="<td colspan=5 align=right>".number_format($row['last_profitlot2'],2)."</td>";
                $html.="<td align=right>".number_format($row['last_pips2'],2)."</td>";
                $html.="<td align=right>".number_format($row['close_profitlot2'],2)."</td>";
                $html.="<td align=right>".number_format($row['close_pips2'],2)."</td>";
                $html.="<td align=right>".number_format($row['max_profitlot2'],2)."</td>";
                $html.="<td align=right>".number_format($row['max_pips2'],2)."</td>";
                $html.="</tr>";
            } 
            if($row['last_profitlot3']>0){
                $html.="<tr>";
                $html.="<td colspan=5 align=right>".number_format($row['last_profitlot3'],2)."</td>";
                $html.="<td align=right>".number_format($row['last_pips3'],2)."</td>";
                $html.="<td align=right>".number_format($row['close_profitlot3'],2)."</td>";
                $html.="<td align=right>".number_format($row['close_pips3'],2)."</td>";
                $html.="<td align=right>".number_format($row['max_profitlot3'],2)."</td>";
                $html.="<td align=right>".number_format($row['max_pips3'],2)."</td>";
                $html.="</tr>";
            }            
            $html.="</tr>";    
                
        }
        $html.="</table></center>";
        echo $html;
    } catch(Exception $e) {
        file_put_contents("debug.log", var_export($e,1));
    }
        
}