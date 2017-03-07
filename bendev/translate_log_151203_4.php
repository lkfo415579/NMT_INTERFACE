<?php

//setup php for working with Unicode data
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');
header("Content-Type:text/html; charset=UTF-8");

$conn = mssql_connect('10.119.181.196:1433', 'dbWordAnalyzer', 'dbpassword')
	or die('Could not connect to the server!');
mssql_select_db('WordAnalyzer', $conn) 
	or die ('Could not select a database.');

#mssql_query("SET NAMES 'UTF8'");

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

echo mb_detect_encoding($output, mb_detect_order(), true);

$input = iconv(mb_detect_encoding($input, mb_detect_order(), true), "utf-8", $input);
$output = iconv(mb_detect_encoding($output, mb_detect_order(), true), "utf-8", $output);

echo mb_detect_encoding($output, mb_detect_order(), true);

/*
$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values ('{$ipaddress}', N'{$input}', N'{$output}', '{$lang_from}', '{$lang_to}')";

#$sql = iconv(mb_detect_encoding($sql, mb_detect_order(), true), "gb2312", $sql);

echo $sql;

mssql_query($sql);
*/
/*
$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values (?, ?, ?, ?, ?)";
$stmt = odbc_prepare($conn, $sql);
# Execute the statement.
$prepare_data = array($ipaddress, $input, $output, $lang_from, $lang_to);
$sucess=odbc_execute($stmt, $prepare_data);
*/

$all_charset = array("ANSI_X3.4-1968", "ANSI_X3.4-1986", "ASCII", "CP367", "IBM367", "ISO-IR-6", "ISO646-US", "ISO_646.IRV:1991", "US", "US-ASCII", "CSASCII", "UTF-8", "ISO-10646-UCS-2", "UCS-2", "CSUNICODE", "UCS-2BE", "UNICODE-1-1", "UNICODEBIG", "CSUNICODE11", "UCS-2LE", "UNICODELITTLE", "ISO-10646-UCS-4", "UCS-4", "CSUCS4", "UCS-4BE", "UCS-4LE", "UTF-16", "UTF-16BE", "UTF-16LE", "UTF-32", "UTF-32BE", "UTF-32LE", "UNICODE-1-1-UTF-7", "UTF-7", "CSUNICODE11UTF7", "UCS-2-INTERNAL", "UCS-2-SWAPPED", "UCS-4-INTERNAL", "UCS-4-SWAPPED", "C99", "JAVA", "CP819", "IBM819", "ISO-8859-1", "ISO-IR-100", "ISO8859-1", "ISO_8859-1", "ISO_8859-1:1987", "L1", "LATIN1", "CSISOLATIN1", "ISO-8859-2", "ISO-IR-101", "ISO8859-2", "ISO_8859-2", "ISO_8859-2:1987", "L2", "LATIN2", "CSISOLATIN2", "ISO-8859-3", "ISO-IR-109", "ISO8859-3", "ISO_8859-3", "ISO_8859-3:1988", "L3", "LATIN3", "CSISOLATIN3", "ISO-8859-4", "ISO-IR-110", "ISO8859-4", "ISO_8859-4", "ISO_8859-4:1988", "L4", "LATIN4", "CSISOLATIN4", "CYRILLIC", "ISO-8859-5", "ISO-IR-144", "ISO8859-5", "ISO_8859-5", "ISO_8859-5:1988", "CSISOLATINCYRILLIC", "ARABIC", "ASMO-708", "ECMA-114", "ISO-8859-6", "ISO-IR-127", "ISO8859-6", "ISO_8859-6", "ISO_8859-6:1987", "CSISOLATINARABIC", "ECMA-118", "ELOT_928", "GREEK", "GREEK8", "ISO-8859-7", "ISO-IR-126", "ISO8859-7", "ISO_8859-7", "ISO_8859-7:1987", "ISO_8859-7:2003", "CSISOLATINGREEK", "HEBREW", "ISO-8859-8", "ISO-IR-138", "ISO8859-8", "ISO_8859-8", "ISO_8859-8:1988", "CSISOLATINHEBREW", "ISO-8859-9", "ISO-IR-148", "ISO8859-9", "ISO_8859-9", "ISO_8859-9:1989", "L5", "LATIN5", "CSISOLATIN5", "ISO-8859-10", "ISO-IR-157", "ISO8859-10", "ISO_8859-10", "ISO_8859-10:1992", "L6", "LATIN6", "CSISOLATIN6", "ISO-8859-11", "ISO8859-11", "ISO_8859-11", "ISO-8859-13", "ISO-IR-179", "ISO8859-13", "ISO_8859-13", "L7", "LATIN7", "ISO-8859-14", "ISO-CELTIC", "ISO-IR-199", "ISO8859-14", "ISO_8859-14", "ISO_8859-14:1998", "L8", "LATIN8", "ISO-8859-15", "ISO-IR-203", "ISO8859-15", "ISO_8859-15", "ISO_8859-15:1998", "LATIN-9", "ISO-8859-16", "ISO-IR-226", "ISO8859-16", "ISO_8859-16", "ISO_8859-16:2001", "L10", "LATIN10", "KOI8-R", "CSKOI8R", "KOI8-U", "KOI8-RU", "CP1250", "MS-EE", "WINDOWS-1250", "CP1251", "MS-CYRL", "WINDOWS-1251", "CP1252", "MS-ANSI", "WINDOWS-1252", "CP1253", "MS-GREEK", "WINDOWS-1253", "CP1254", "MS-TURK", "WINDOWS-1254", "CP1255", "MS-HEBR", "WINDOWS-1255", "CP1256", "MS-ARAB", "WINDOWS-1256", "CP1257", "WINBALTRIM", "WINDOWS-1257", "CP1258", "WINDOWS-1258", "850", "CP850", "IBM850", "CSPC850MULTILINGUAL", "862", "CP862", "IBM862", "CSPC862LATINHEBREW", "866", "CP866", "IBM866", "CSIBM866", "MAC", "MACINTOSH", "MACROMAN", "CSMACINTOSH", "MACCENTRALEUROPE", "MACICELAND", "MACCROATIAN", "MACROMANIA", "MACCYRILLIC", "MACUKRAINE", "MACGREEK", "MACTURKISH", "MACHEBREW", "MACARABIC", "MACTHAI", "HP-ROMAN8", "R8", "ROMAN8", "CSHPROMAN8", "NEXTSTEP", "ARMSCII-8", "GEORGIAN-ACADEMY", "GEORGIAN-PS", "KOI8-T", "CP154", "CYRILLIC-ASIAN", "PT154", "PTCP154", "CSPTCP154", "KZ-1048", "RK1048", "STRK1048-2002", "CSKZ1048", "MULELAO-1", "CP1133", "IBM-CP1133", "ISO-IR-166", "TIS-620", "TIS620", "TIS620-0", "TIS620.2529-1", "TIS620.2533-0", "TIS620.2533-1", "CP874", "WINDOWS-874", "VISCII", "VISCII1.1-1", "CSVISCII", "TCVN", "TCVN-5712", "TCVN5712-1", "TCVN5712-1:1993", "ISO-IR-14", "ISO646-JP", "JIS_C6220-1969-RO", "JP", "CSISO14JISC6220RO", "JISX0201-1976", "JIS_X0201", "X0201", "CSHALFWIDTHKATAKANA", "ISO-IR-87", "JIS0208", "JIS_C6226-1983", "JIS_X0208", "JIS_X0208-1983", "JIS_X0208-1990", "X0208", "CSISO87JISX0208", "ISO-IR-159", "JIS_X0212", "JIS_X0212-1990", "JIS_X0212.1990-0", "X0212", "CSISO159JISX02121990", "CN", "GB_1988-80", "ISO-IR-57", "ISO646-CN", "CSISO57GB1988", "CHINESE", "GB_2312-80", "ISO-IR-58", "CSISO58GB231280", "CN-GB-ISOIR165", "ISO-IR-165", "ISO-IR-149", "KOREAN", "KSC_5601", "KS_C_5601-1987", "KS_C_5601-1989", "CSKSC56011987", "EUC-JP", "EUCJP", "EXTENDED_UNIX_CODE_PACKED_FORMAT_FOR_JAPANESE", "CSEUCPKDFMTJAPANESE", "MS_KANJI", "SHIFT-JIS", "SHIFT_JIS", "SJIS", "CSSHIFTJIS", "CP932", "ISO-2022-JP", "CSISO2022JP", "ISO-2022-JP-1", "ISO-2022-JP-2", "CSISO2022JP2", "CN-GB", "EUC-CN", "EUCCN", "GB2312", "CSGB2312", "GBK", "CP936", "MS936", "WINDOWS-936", "GB18030", "ISO-2022-CN", "CSISO2022CN", "ISO-2022-CN-EXT", "HZ", "HZ-GB-2312", "EUC-TW", "EUCTW", "CSEUCTW", "BIG-5", "BIG-FIVE", "BIG5", "BIGFIVE", "CN-BIG5", "CSBIG5", "CP950", "BIG5-HKSCS:1999", "BIG5-HKSCS:2001", "BIG5-HKSCS", "BIG5-HKSCS:2004", "BIG5HKSCS", "EUC-KR", "EUCKR", "CSEUCKR", "CP949", "UHC", "CP1361", "JOHAB", "ISO-2022-KR", "CSISO2022KR", "CP856", "CP922", "CP943", "CP1046", "CP1124", "CP1129", "CP1161", "IBM-1161", "IBM1161", "CSIBM1161", "CP1162", "IBM-1162", "IBM1162", "CSIBM1162", "CP1163", "IBM-1163", "IBM1163", "CSIBM1163", "DEC-KANJI", "DEC-HANYU", "437", "CP437", "IBM437", "CS", "PC8CODEPAGE437", "CP737", "CP775", "IBM775", "CSPC775BALTIC", "852", "CP852", "IBM852", "CSPCP852", "CP853", "855", "CP855", "IBM855", "CSIBM855", "857", "CP857", "IBM857", "CSIBM857", "CP858", "860", "CP860", "IBM860", "CSIBM860", "861", "CP-IS", "CP861", "IBM861", "CSIBM861", "863", "CP863", "IBM863", "CSIBM863", "CP864", "IBM864", "CSIBM864", "865", "CP865", "IBM865", "CSIBM865", "869", "CP-GR", "CP869", "IBM869", "CSIBM869", "CP1125", "EUC-JISX0213", "SHIFT_JISX0213", "ISO-2022-JP-3", "BIG5-2003", "ISO-IR-230", "TDS565", "ATARI", "ATARIST", "RISCOS-LATIN1");

foreach ($all_charset as $charset){
	#echo $charset;
	$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values ('0.0.0.0', N'{$charset}', N'你好', 'en', 'zh')";
	$sql = iconv("UTF-8", $charset, $sql);
	mssql_query($sql);
}


#$sql="insert into mtHistory(ip, input, output, source_lang, target_lang) values ('0.0.0.0', N'hello', N'你好', 'en', 'zh')";


$sql = iconv("UTF-8", "CP1251", $sql);
echo mb_detect_encoding($sql, mb_detect_order(), true);

echo $sql;
mssql_query($sql);

//mssql_close($conn);







/*
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

//PHP MSSQL Example

//Replace data_source_name with the name of your data source.
//Replace database_username and database_password
//with the SQL Server database username and password.

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
