<?php
//setup php for working with Unicode data
header("Content-Type:text/html; charset=utf-8");
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
$trans_msg = isset($_POST['trans_msg'])?$_POST['trans_msg']:"";

# send json data out
$url = 'http://10.119.181.181:8080/TestEnvironment/JsonServlet';
$data = array(
	'ip' => $ipaddress,
	'input' => $input, 
	'output' => $output,
	'lang_from' => $lang_from,
	'lang_to' => $lang_to,
	'trans_msg' => $trans_msg);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
#echo $result;

#var_dump($result);
#)

?>
