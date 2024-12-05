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
        $sql="SELECT * FROM account_magic_trade where id={$_GET['id']}";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        $UPrice=$_POST['uprice'];
        $LPrice=$_POST['lprice'];
        if($_POST['entryprice']!=$row['entryPrice'] && $_POST['entryprice']>0){
            
            if($row['opentype']=="buy"){
                $UPrice=$_POST['entryprice']+$row['d1Height']*$row['point'];
                $LPrice=$_POST['entryprice']-$row['h4Height']*1.3*$row['point'];
            }else{
                $UPrice=$_POST['entryprice']+$row['h4Height']*1.3*$row['point'];
                $LPrice=$_POST['entryprice']-$row['d1Height']*$row['point'];
            }
            $UPrice=max($UPrice,$_POST['uprice']);
            if($_POST['lprice']>0)$LPrice=min($LPrice,$_POST['lprice']);
        }

        $sql="update account_magic_trade set ";
        $sql.="UPrice=$UPrice,";
        $sql.="LPrice=$LPrice,";
        $sql.="entryPrice={$_POST['entryprice']},";
        $sql.="entryVol={$_POST['entryvol']},";
        $sql.="rePips={$_POST['rePips']},";
        $sql.="reVol={$_POST['reVol']},";
        $sql.="maxfactor={$_POST['maxfactor']},";
        $sql.="tp_mode={$_POST['tp_mode']},";
        $sql.="remark='{$_POST['remark']}'";
        $sql.=" where id={$_GET['id']}";
        //echo $sql;
        $res = $mysqli->query($sql);
    }

    $sql="SELECT * FROM account_magic_trade where id={$_GET['id']}";
    $res = $mysqli->query($sql);
    $row = $res->fetch_assoc();

    if(isset($_POST['copyid']) && $_POST['copyid']>0){
        $sql="select * from account_magic_trade where id={$_POST['copyid']}";
        $res = $mysqli->query($sql);
        $row_copy = $res->fetch_assoc();
        $row['UPrice']=$row_copy['UPrice'];
        $row['LPrice']=$row_copy['LPrice'];
        $row['entryPrice']=$row_copy['entryPrice'];
        $row['entryVol']=$row_copy['entryVol'];
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
    $html.="<tr><td align=right><a href=/magicdelete.php target=delete >Remark</a> :</td><td><input type=text name=remark size=30 value={$row['remark']}></td></tr>";
    $html.="<tr><td align=right>Lot :</td><td>{$row['openlot']} Count : {$row['opencount']}</td></tr>";
    $html.="<tr><td align=right>Min Price :</td><td>{$row['minPrice']} Max Price : {$row['maxPrice']}</td></tr>";
    $html.="<tr><td align=right>USD :</td><td>SL: $sl TP: $tp</td></tr>";
    $html.="<tr><td align=right>Bid :</td><td>{$row['bid']} Ask : {$row['ask']}</td></tr>";
    $html.="<tr><td align=right>Gap :</td><td>SL:".number_format($sl_gap)." TP: ".number_format($tp_gap)."</td></tr>";
    $html.="<tr><td align=right>Pips :</td><td>M15: {$row['m15Height']} H1: {$row['h1Height']} H4 : {$row['h4Height']}</td></tr>";
    $html.="<tr><td align=right></td><td>D1: {$row['d1Height']} W1: {$row['w1Height']}</td></tr>";
    $html.="<tr><td align=right>COPY ID :</td><td><input type=text name=copyid value=0></td></tr>";
    $html.="<tr><td align=right>U Price :</td><td><input type=text name=uprice value={$row['UPrice']}></td></tr>";
    $html.="<tr><td align=right>L Price :</td><td><input type=text name=lprice value={$row['LPrice']}></td></tr>";
    $html.="<tr><td align=right>Entry Price :</td><td><input type=text name=entryprice value={$row['entryPrice']}></td></tr>";
    $html.="<tr><td align=right>Entry Lot :</td><td><input type=text name=entryvol value={$row['entryVol']}></td></tr>";
    $html.="<tr><td align=right>RE Pips :</td><td><input type=text name=rePips value={$row['rePips']}></td></tr>";
    $html.="<tr><td align=right>RE Lot :</td><td><input type=text name=reVol value={$row['reVol']}></td></tr>";
    $html.="<tr><td align=right>Max Factor :</td><td><input type=text name=maxfactor value={$row['maxfactor']}></td></tr>";
    $html.="<tr><td align=right>TP Mode :</td><td><input type=text name=tp_mode value={$row['tp_mode']}></td></tr>";
    $html.="<tr><td colspan=2 align=center><input type=submit name=Submit></td></tr>";
    $html.="</table>";
    $html.="</form></center>";

    echo $html;
}