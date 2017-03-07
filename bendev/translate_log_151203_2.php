<?php
	echo '1 log;';
	require_once('DBConnectionPDO.class.php');

	echo '1 PDO;';
	$db = new DBConnectionPDO('WordAnalyzer');
	echo '2 PDO done;';
/*
	$sql_query = "SELECT * FROM `register` where pic_name=? and facebook_id=? ";
	$sth = $db->PrepareQuery($sql_query);
	$db->ExecutePreparedQuery($sth, array($pic_name,$fb_id));
*/

	$ip = '0.0.0.0';
	$input = 'this is input';
	$output = 'this is output';
	$source_lang = 'zh';
	$target_lang = 'en';
	echo '2';

	$sql = "SELECT * FROM mtHistory where hid = ?";
	$sth = $db->PrepareQuery($sql);
	$db->ExecutePreparedQuery($sth, array(1));
//	$result = mssql_query($sql) or die('MySQL query error'.$sql);
	
	$report_str = '';
	if ($row = mssql_fetch_array($result)){
		$report_str = $row['input'];
	}

	echo $sql+$report_str;

//	$sql = "insert into mtHistory(ip, input, output, source_lang, target_lang) values ('$ip', N'$input', N'$output', '$source_lang', '$target_lang')";
//	echo $sql;
//	mssql_query($sql) or die('Error, insert query failed');

	
//	echo $_POST["output"];
?>
