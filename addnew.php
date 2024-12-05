<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>Add New <?php echo $_GET['login']; ?></title>
<head>
<?php
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    $html="<center>";
    $html.="Account :</td><td>{$_GET['login']} Magic : {$_GET['nmagic']} {$_GET['pair']} {$_GET['xdir']}";
    if(isset($_POST['nlot']) && $_POST['nlot']>0){ 
        $html.=" lot: {$_POST['nlot']}";
        $sql="insert into add_new (login,pair,xdir,nmagic,nlot) values ({$_GET['login']},'{$_GET['pair']}','{$_GET['xdir']}',{$_GET['nmagic']},{$_POST['nlot']})";	
        $res = $mysqli->query($sql);
    }else{
        $html.="<form name=addnew method=post>";
        $html.="<table>";
        $html.="<tr><td align=right>Lot :</td><td><input name=nlot value='0.01' size=4></td></tr>";
        $html.="<tr><td colspan=2 align=center><input type=submit name=Submit></td></tr>";
        $html.="</table>";
        $html.="</form>";
        
    }
    $html.="</center>";
    echo $html;
}