<?php
//setup php for working with Unicode data
header("Content-Type:text/html; charset=utf-8");
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');
/*
PHP MSSQL Example

Replace data_source_name with the name of your data source.
Replace database_username and database_password
with the SQL Server database username and password.
*/
$data_source='mt_log';
$user='dbWordAnalyzer';
$password='dbpassword';

// Connect to the data source and get a handle for that connection.
$conn=odbc_connect($data_source,$user,$password);
if (!$conn){
    if (phpversion() < '4.0'){
      exit("Connection Failed: . $php_errormsg" );
    }
    else{
      exit("Connection Failed:" . odbc_errormsg() );
    }
}
odbc_exec($conn, "SET NAMES 'UTF-8'");
odbc_exec($conn, "SET client_encoding='UTF-8'");

$ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

$input = isset($_POST['input'])?$_POST['input']:"";
$output = isset($_POST['output'])?$_POST['output']:"";
$lang_from = isset($_POST['lang_from'])?$_POST['lang_from']:"";
$lang_to = isset($_POST['lang_to'])?$_POST['lang_to']:"";

$input = iconv(mb_detect_encoding($input, mb_detect_order(), true), "UTF-8", $input);
$output = iconv(mb_detect_encoding($output, mb_detect_order(), true), "UTF-8", $output);

#$input = iconv("UTF-8", "UTF-8", $input);
#$output = iconv("UTF-8", "UTF-8", $output);

#$input = mb_convert_encoding($input, 'GBK', 'UTF-8');
#$output = mb_convert_encoding($output, 'GBK', 'UTF-8');

// This query generates a result set with one record in it.
$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values (?, ?, ?, ?, ?)";
$stmt = odbc_prepare($conn, $sql);
# Execute the statement.
$prepare_data = array($ipaddress, $input, $output, $lang_from, $lang_to);
$sucess=odbc_execute($stmt, $prepare_data);


#$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values ('0.0.0.0', N'hello', N'你好', 'en', 'zh')";
#$sql = iconv(mb_detect_encoding($sql, mb_detect_order(), true), "UTF-8", $sql);
#$sql = iconv("big-5", "UTF-8", $sql);
#odbc_exec($conn, $sql);
echo $success;
echo $ipaddress;
echo $input;
echo $output;
echo $lang_from;
echo $lang_to;

/*
$sql="select * from mtHistory;";
$rs = odbc_exec($conn, $sql);
// Fetch and display the result set value.
if (!$rs){
    exit("Error in SQL");
}
while (odbc_fetch_row($rs)){
    $col1=odbc_result($rs, "output");
    echo "$col1\n";
}

//echo $_POST['input'];
//echo $_POST['output'];
*/
?>
