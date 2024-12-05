<?php
    if(isset($_POST['username'])){
        $ip_address=$_SERVER['REMOTE_ADDR'];
        //$mysqli = new mysqli("localhost", "fbsonline", "30Fu45y7qjsH9dze", "fbsonline");
        //$mysqli = new mysqli("localhost", "my369forex", "30Fu45y7qjsH9dze", "my369forex");	
        $mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
        $enable_access=0;
        if(trim($_POST['username'])=='89961810')$enable_access=1;
        $sql="insert into account_user (username,ip_address,enable_access) values ('{$_POST['username']}','{$ip_address}',{$enable_access})";	
        $res = $mysqli->query($sql);
        echo "Welcome {$_POST['username']} ! ";
        echo '<a href=/>Home</a>';
        exit();
    }
?>
<h2>Resgister</h2>
<form method=post>
Email: <input name=username type=text size=10> <input type=submit>
</form>
    