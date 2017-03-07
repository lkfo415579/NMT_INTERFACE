<?php
	$hostname='10.119.181.196';
	$username='dbWordAnalyzer';
	$password='dbpassword';
	$dbname='WordAnalyzer';

echo '1.1';

	$conn = mysql_connect($hostname, $username, $password) OR DIE('Unable to connect to database! Please try again later.');

echo '1.2';
	mysql_query("SET NAMES 'utf8'");

echo '1.3';
	mysql_select_db($dbname);

echo '1.4';
	date_default_timezone_set('Asia/Macau');

echo '1.5';
?>
