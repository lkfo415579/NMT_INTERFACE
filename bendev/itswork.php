<?php
/*
PHP MSSQL Example

Replace data_source_name with the name of your data source.
Replace database_username and database_password
with the SQL Server database username and password.
*/
$data_source='db_log';
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

// This query generates a result set with one record in it.
$sql="SELECT * from enHistory";

# Execute the statement.
$rs=odbc_exec($conn,$sql);

// Fetch and display the result set value.
if (!$rs){
    exit("Error in SQL");
}
while (odbc_fetch_row($rs)){
    $col1=odbc_result($rs, "word");
    echo "$col1\n";
}
