<?php
$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
if ($mysqli->connect_errno) {
    file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}else{
    
    $sql="delete FROM magicTrade where remark='DELETE'";
		
    $res = $mysqli->query($sql);

    echo "deleted";
}