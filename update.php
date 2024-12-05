<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
if(isset($_POST['result'])){
	$r=explode('|',$_POST['result']);
	
}else{
	//file_put_contents("error.log",var_export($_GET,1));
	$r=explode('|',"");
}
foreach($r as $rs){
	$result=explode("@",$rs);
	if(count($result)>1){		
		if($result[0]=="100")$login=$result[1];
		if($result[0]=="200")$trade_mode=$result[1];
		if($result[0]=="300")$leverage=$result[1];
		if($result[0]=="400")$balance=$result[1];
		if($result[0]=="500")$equity=$result[1];
		if($result[0]=="600")$margin_free=$result[1];
		if($result[0]=="700")$name=$result[1];
		if($result[0]=="800")$server=$result[1];
		if($result[0]=="900")$currency=$result[1];
		if($result[0]=="1000")$company=$result[1];
		if($result[0]=="1100")$total_open_count=$result[1];
		if($result[0]=="1200")$positive_float=$result[1];
		if($result[0]=="1300")$total_volume=$result[1];
		if($result[0]=="1400")$positive_volume=$result[1];
		if($result[0]=="1500")$positive_count=$result[1];
		if($result[0]=="1600")$platform=$result[1];
		if($result[0]=="1700")$terminal_path=$result[1];
		if($result[0]=="1800")$terminal_build=$result[1];
	}
}
try{
	if(isset($login) && $login){
		
		//$mysqli = new mysqli("localhost", "fbsonline", "30Fu45y7qjsH9dze", "fbsonline");
		//$mysqli = new mysqli("localhost", "my369forex", "30Fu45y7qjsH9dze", "my369forex");	
		$mysqli = new mysqli("mysql", "root", "CF26D23C453D3EB6", "my369forex");
		if ($mysqli->connect_errno) {
			file_put_contents('error.log', "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		}else{
			$sql="SELECT * FROM account where login=$login";
			//file_put_contents("debug1.log",$sql);
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			$now=date("Y-m-d H:i:s");
			
			$account_float=$equity-$balance;
			//file_put_contents("debug1.log",count($row));
			if(count($row)==0){
				
				$sql="INSERT INTO account (login,init_date,init_balance,min_balance,init_equity,min_equity, min_margin_free) VALUES ($login, '$now', $balance, $balance, $equity, $equity, $margin_free)";			
				file_put_contents("debug1.log",$sql);
				$mysqli->query($sql);	
				
				$res = $mysqli->query("SELECT * FROM account where login=$login");
				$row = $res->fetch_assoc();
			}
			
			$min_balance=min($row['min_balance'],$balance);
			$max_balance=max($row['max_balance'],$balance);
			$min_equity=min($row['min_equity'],$equity);
			$max_equity=max($row['max_equity'],$equity);
			$min_float=min($row['min_float'],$account_float);
			$max_float=max($row['max_float'],$account_float);
			$min_margin_free=min($row['min_margin_free'],$margin_free);
			$max_open_count=max($row['max_open_count'],$total_open_count);
			$max_positive_count=max($row['max_positive_count'],$positive_count);
			$max_positive_float=max($row['max_positive_float'],$positive_float);
			
			$max_total_volume=max($row['max_total_volume'],$total_volume);
			$max_positive_volume=max($row['max_positive_volume'],$positive_volume);
			
			$sql="update account set ";
			$sql.="trade_mode=$trade_mode,";
			$sql.="leverage=$leverage,";
			$sql.="balance=$balance,";
			$sql.="min_balance=$min_balance,";
			$sql.="max_balance=$max_balance,";
			$sql.="equity=$equity,";
			$sql.="min_equity=$min_equity,";
			$sql.="max_equity=$max_equity,";
			$sql.="account_float=$account_float,";
			$sql.="min_float=$min_float,";
			$sql.="max_float=$max_float,";
			$sql.="margin_free=$margin_free,";
			$sql.="min_margin_free=$min_margin_free,";
			$sql.="open_count=$total_open_count,";
			$sql.="max_open_count=$max_open_count,";
			$sql.="positive_count=$positive_count,";
			$sql.="max_positive_count=$max_positive_count,";
			$sql.="positive_float=$positive_float,";
			$sql.="max_positive_float=$max_positive_float,";
			
			$sql.="total_volume=$total_volume,";
			$sql.="max_total_volume=$max_total_volume,";
			$sql.="positive_volume=$positive_volume,";
			$sql.="max_positive_volume=$max_positive_volume,";
			
			$sql.="ip_address='{$_SERVER['REMOTE_ADDR']}',";
			$sql.="name='$name',";
			$sql.="platform='$platform',";
			$sql.="server='$server',";
			$sql.="currency='$currency',";
			$sql.="company='$company',";
			$sql.="terminal_path='$terminal_path',";
			$sql.="terminal_build='$terminal_build',";
			$sql.="last_update='$now'";
			$sql.=" where id={$row['id']}";
			//file_put_contents("error.log",$sql);
			$mysqli->query($sql);
			
			$sql="insert into account_log (login,last_update,balance,equity,margin_free,positive_float,open_count,total_volume,positive_volume,positive_count) values ($login,'$now',$balance,$equity,$margin_free,$positive_float,$total_open_count,$total_volume,$positive_volume,$positive_count)";
			//file_put_contents("error.log",$sql);
			$mysqli->query($sql);

			
			$sql="SELECT * FROM add_new where login=$login limit 1";
			
			$res = $mysqli->query($sql);
			$row = $res->fetch_assoc();
			if(isset($row['id'])){
				$sql="delete from add_new where id={$row['id']}";
				$mysqli->query($sql);
				print "@{$row['pair']}@{$row['xdir']}@{$row['nlot']}@{$row['nmagic']}@";
				
			}
			
		}
	}
} catch(Exception $e) {
	file_put_contents("debug.log", var_export($e,1));
}