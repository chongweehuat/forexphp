<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
    if(date('w')>0 && date('w')<6) echo '<meta http-equiv="refresh" content="15" />';
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>Pair Data</title>
<head>
<?php
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $now=date("Y-m-d H:i:s");

    $sql="SELECT * FROM pairs_data_d1 where pair='{$_GET['pair']}' and dt>='{$_GET['d1']}' and dt<='{$_GET['d2']}'";
	
    $res = $mysqli->query($sql);

    $aMinHeight = array_fill(1, 5, 99999999);
    $aMaxHeight = array_fill(1, 5, 0);

    $aMinNetHeight = array_fill(1, 5, 99999999);
    $aMaxNetHeight = array_fill(1, 5, 0);

    $aSumHeight=array_fill(1, 5, 0);
    $aSumNetHeight=array_fill(1, 5, 0);

    $aDayCount=array_fill(1, 5, 0);
    $aUpCount=array_fill(1, 5, 0);
    $aDownCount=array_fill(1, 5, 0);

    $aOpenMin = array_fill(1, 5, 99999999);
    $aOpenMax = array_fill(1, 5, 0);
    $aSumOpen = array_fill(1, 5, 0);

    $aCloseMin = array_fill(1, 5, 99999999);
    $aCloseMax = array_fill(1, 5, 0);
    $aSumClose = array_fill(1, 5, 0);

    $aTopPrice = array_fill(1,5, array_fill(0,24,0));
    $aBottomPrice = array_fill(1,5, array_fill(0,24,0));
    while($row = $res->fetch_assoc()){
        if($row['hour_top']==0 && $row['hour_bottom']==0){
            $dt1=$row['dt'];
            $dt2=substr($row['dt'],0,10).'24';
            $sql="select * from pairs_data_h1 where pair='{$row['pair']}' and dt>='$dt1' and dt<='$dt2'";
            $res1 = $mysqli->query($sql);
            $hour_top=0;
            $hour_bottom=0;
            $minPrice=999999;
            $maxPrice=0;
            while($row1 = $res1->fetch_assoc()){
                if($row1['price_high']>$maxPrice){
                    $maxPrice=$row1['price_high'];
                    $date = new DateTime($row1['dt']);
                    $hour_top=(int)$date->format('H');
                }
                if($row1['price_low']<$minPrice){
                    $minPrice=$row1['price_low'];
                    $date = new DateTime($row1['dt']);
                    $hour_bottom=(int)$date->format('H');
                }
            }
            $sql="update pairs_data_d1 set hour_top=$hour_top,hour_bottom=$hour_bottom  where id={$row['id']}";
            
            $res2 = $mysqli->query($sql);    
        }

        $date = new DateTime($row['dt']);
        $weekday = $date->format('N');

        $aTopPrice[$weekday][$row['hour_top']]++;
        $aBottomPrice[$weekday][$row['hour_bottom']]++;
        
        $point=0.00001;
        if($row['price_high']>10)$point=0.001;
        
        $aDayCount[$weekday]++;

        $height=($row['price_high']-$row['price_low'])/$point;
        if($height<$aMinHeight[$weekday])$aMinHeight[$weekday]=$height;
        if($height>$aMaxHeight[$weekday])$aMaxHeight[$weekday]=$height;
        $aSumHeight[$weekday]+=$height;

        $pc=100*($row['price_open']-$row['price_low'])/($row['price_high']-$row['price_low']);
        if($pc<$aOpenMin[$weekday])$aOpenMin[$weekday]=$pc;
        if($pc>$aOpenMax[$weekday])$aOpenMax[$weekday]=$pc;
        $aSumOpen[$weekday]+=$pc;

        $pc=100*($row['price_close']-$row['price_low'])/($row['price_high']-$row['price_low']);
        if($pc<$aCloseMin[$weekday])$aCloseMin[$weekday]=$pc;
        if($pc>$aCloseMax[$weekday])$aCloseMax[$weekday]=$pc;
        $aSumClose[$weekday]+=$pc;

        $height=abs($row['price_open']-$row['price_close'])/$point;
        if($height<$aMinNetHeight[$weekday])$aMinNetHeight[$weekday]=$height;
        if($height>$aMaxNetHeight[$weekday])$aMaxNetHeight[$weekday]=$height;
        $aSumNetHeight[$weekday]+=$height;

        if($row['price_open']<$row['price_close'])$aUpCount[$weekday]++;
        else $aDownCount[$weekday]++;

        
    }
    
    $html="<center><table  style='font-size:80%' width=90%>";
    $html.="<tr>";
    $html.="<td>WD</td>";
    $html.="<td>Min</td>";
    $html.="<td>Max</td>";
    $html.="<td>Avrg</td>";
    $html.="<td>Min Net</td>";
    $html.="<td>Max Net</td>";
    $html.="<td>Avrg</td>";
    $html.="<td>Up</td>";
    $html.="<td>Down</td>";
    $html.="<td>Open Min</td>";
    $html.="<td>Open Max</td>";
    $html.="<td>Open Avrg</td>";
    $html.="<td>Close Min</td>";
    $html.="<td>Close Max</td>";
    $html.="<td>Close Avrg</td>";
    $html.="</tr>";

    for($wd=1; $wd<=5; $wd++){
        
        $html.="<tr>";
        $html.="<td>";
        $html.=$wd;
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aMinHeight[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aMaxHeight[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aSumHeight[$wd]/$aDayCount[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aMinNetHeight[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aMaxNetHeight[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aSumNetHeight[$wd]/$aDayCount[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=$aUpCount[$wd];
        $html.="</td>";
        $html.="<td>";
        $html.=$aDownCount[$wd];
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aOpenMin[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aOpenMax[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aSumOpen[$wd]/$aDayCount[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aCloseMin[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aCloseMax[$wd]);
        $html.="</td>";
        $html.="<td>";
        $html.=number_format($aSumClose[$wd]/$aDayCount[$wd]);
        $html.="</td>";
        $html.="</tr>";
    }
    $html.="</table>";

    $html.="<table>";
    $html.="<tr>";
    $html.="<td>Hour</td>";
    $html.="<td>1T</td>";
    $html.="<td>1B</td>";
    $html.="<td>2T</td>";
    $html.="<td>2B</td>";
    $html.="<td>3T</td>";
    $html.="<td>3B</td>";
    $html.="<td>4T</td>";
    $html.="<td>4B</td>";
    $html.="<td>5T</td>";
    $html.="<td>5B</td>";
    $html.="</tr>";
    for($h=0; $h<24; $h++){
        $html.="<tr>";
        $html.="<td>$h</td>";
        $html.="<td>{$aTopPrice[1][$h]}</td>";
        $html.="<td>{$aBottomPrice[1][$h]}</td>";
        $html.="<td>{$aTopPrice[2][$h]}</td>";
        $html.="<td>{$aBottomPrice[2][$h]}</td>";
        $html.="<td>{$aTopPrice[3][$h]}</td>";
        $html.="<td>{$aBottomPrice[3][$h]}</td>";
        $html.="<td>{$aTopPrice[4][$h]}</td>";
        $html.="<td>{$aBottomPrice[4][$h]}</td>";
        $html.="<td>{$aTopPrice[5][$h]}</td>";
        $html.="<td>{$aBottomPrice[5][$h]}</td>";
        $html.="</tr>";
    }
    $html.="</table>";

    $html.="</center>";
       
    echo $html;
    
}