<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>MagicEdit <?php echo $_GET['id']; ?></title>
<head>
<?php
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    //file_put_contents("debug.log",var_export($_POST,1));
    if(isset($_POST['uprice']) && $_POST['copyid']==0){
        $sql="SELECT * FROM magicTrade where id={$_GET['id']}";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        $UPrice=$_POST['uprice'];
        $LPrice=$_POST['lprice'];

        $ULimit=$_POST['ulimit'];
        $LLimit=$_POST['llimit'];
        $ULimit2=$_POST['ulimit2'];
        $LLimit2=$_POST['llimit2'];
        $ULimit3=$_POST['ulimit3'];
        $LLimit3=$_POST['llimit3'];

        $dGap=$_POST['dGap'];
        $dLot=$_POST['dLot'];
        $dMax=$_POST['dMax'];
        $dCount=$_POST['dCount'];
        $dNew=$_POST['dNew'];

        $sql="update magicTrade set ";
        $sql.="UPrice=$UPrice,";
        $sql.="LPrice=$LPrice,";
        $sql.="ULimit=$ULimit,";
        $sql.="LLimit=$LLimit,";
        $sql.="ULimit2=$ULimit2,";
        $sql.="LLimit2=$LLimit2,";
        $sql.="ULimit3=$ULimit3,";
        $sql.="LLimit3=$LLimit3,";
        $sql.="dGap=$dGap,";
        $sql.="dLot=$dLot,";
        $sql.="dMax=$dMax,";
        $sql.="dCount=$dCount,";
        $sql.="dNew=$dNew,";
        $sql.="remark='{$_POST['remark']}',";
        $sql.="note='{$_POST['note']}'";
        $sql.=" where id={$_GET['id']}";
        //echo $sql;
        $res = $mysqli->query($sql);
    }

    $sql="SELECT * FROM magicTrade where id={$_GET['id']}";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();

    if(isset($_POST['copyid']) && $_POST['copyid']>0){
        $sql="select * from magicTrade where id={$_POST['copyid']}";
        $res = $mysqli->query($sql);
        $row_copy = $res->fetch_assoc();
        $row['UPrice']=$row_copy['UPrice'];
        $row['LPrice']=$row_copy['LPrice'];
        $row['ULimit']=$row_copy['ULimit'];
        $row['LLimit']=$row_copy['LLimit'];
        $row['ULimit2']=$row_copy['ULimit2'];
        $row['LLimit2']=$row_copy['LLimit2'];
        $row['ULimit3']=$row_copy['ULimit3'];
        $row['LLimit3']=$row_copy['LLimit3'];
    }

    if($row['opentype']=="buy"){
        $sl_gap=($row['bid']-$row['LPrice'])/$row['point'];
        $tp_gap=($row['UPrice']-$row['bid'])/$row['point'];
    }else{
        $sl_gap=($row['UPrice']-$row['ask'])/$row['point'];
        $tp_gap=($row['ask']-$row['LPrice'])/$row['point'];     
    }

    $tp=$row['ntp'];
    $sl=$row['nsl'];

    $html="<center><form name=magicentry method=post>";
    $html.="<table>";
    $html.="<tr><td align=right>Account :</td><td>{$row['login']} Magic : {$row['magicnumber']} {$row['pair']} {$row['opentype']}</td></tr>";
    $html.="<tr><td align=right><a href=/mgDelete.php target=delete >Remark</a> :</td><td><input type=text name=remark size=30 value={$row['remark']}></td></tr>";
    $html.="<tr><td align=right>Lot :</td><td>{$row['openlot']} Count : {$row['opencount']}</td></tr>";
    $html.="<tr><td align=right>Min Price :</td><td>{$row['minPrice']} Max Price : {$row['maxPrice']}</td></tr>";
    $html.="<tr><td align=right>USD :</td><td>SL: $sl TP: $tp</td></tr>";
    $html.="<tr><td align=right>Bid :</td><td>{$row['bid']} Ask : {$row['ask']}</td></tr>";
    $html.="<tr><td align=right>Gap :</td><td>SL:".number_format($sl_gap)." TP: ".number_format($tp_gap)."</td></tr>";
    $html.="<tr><td align=right>COPY ID :</td><td><input type=text name=copyid value=0></td></tr>";
    $html.="<tr><td align=right>U Price :</td><td><input type=text name=uprice value={$row['UPrice']}></td></tr>";
    $html.="<tr><td align=right>L Price :</td><td><input type=text name=lprice value={$row['LPrice']}></td></tr>";
    $html.="<tr><td align=right>U Limit 1 :</td><td><input type=text id=ulimit name=ulimit value={$row['ULimit']}></td></tr>";
    $html.="<tr><td align=right>L Limit 1 :</td><td><input type=text id=llimit name=llimit value={$row['LLimit']}></td></tr>";
    $html.="<tr><td align=right>U Limit 2 :</td><td><input type=text id=ulimit2 name=ulimit2 value={$row['ULimit2']}></td></tr>";
    $html.="<tr><td align=right>L Limit 2 :</td><td><input type=text id=llimit2 name=llimit2 value={$row['LLimit2']}></td></tr>";
    $html.="<tr><td align=right>U Limit 3 :</td><td><input type=text id=ulimit3 name=ulimit3 value={$row['ULimit3']}></td></tr>";
    $html.="<tr><td align=right>L Limit 3 :</td><td><input type=text id=llimit3 name=llimit3 value={$row['LLimit3']}></td></tr>";
    $html.="<tr><td align=right>Gap :</td><td><input type=text id=dGap name=dGap value={$row['dGap']}></td></tr>";
    $html.="<tr><td align=right>Lot :</td><td><input type=text id=dLot name=dLot value={$row['dLot']}></td></tr>";
    $html.="<tr><td align=right>Max :</td><td><input type=text id=dMax name=dMax value={$row['dMax']}></td></tr>";
    $html.="<tr><td align=right>Count :</td><td><input type=text id=dCount name=dCount value={$row['dCount']}></td></tr>";
    $html.="<tr><td align=right>New :</td><td><input type=text id=dNew name=dNew value={$row['dNew']}></td></tr>";
    
    $html.="<tr><td align=right>Note :</td><td><textarea name=note rows=6 columns=30>{$row['note']}</textarea></td></tr>";
    $html.="<tr><td colspan=2 align=center><input type=submit name=Submit> ";
    $html.=" <button type=button onclick=clearFields()>Clear</button></td></tr>";
    $html.="</table>";
    $html.="</form></center>";
    $html.="<script>";
    $html.="function clearFields() {";
    $html.="document.getElementById('ulimit').value = '0';";
    $html.="document.getElementById('llimit').value = '0';";
    $html.="document.getElementById('ulimit2').value = '0';";
    $html.="document.getElementById('llimit2').value = '0';";
    $html.="document.getElementById('ulimit3').value = '0';";
    $html.="document.getElementById('llimit3').value = '0';";
    $html.="}";
    $html.="</script>";

    echo $html;
}