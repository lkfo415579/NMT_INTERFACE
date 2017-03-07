<?php
//use mysql database 1.1 2015-1-16
//一

class DBConnectionPDO{
	private $db_con;
	
	public function __construct($db_name,$db_user="dbWordAnalyzer",$db_password="dbpassword",$db_server="10.119.181.196:1433"){
echo 'enter construct<br/>';
		$dsn = "mssql:host={$db_server};dbname={$db_name}";
		try {
echo "{$dsn}<br/>";
echo "{$db_user}<br/>";
echo "{$db_password}<br/>";
			$this->db_con = new PDO($dsn, $db_user, $db_password);
//			$this->db_con = new PDO('mysql:Driver={SQL Server};Server=10.119.181.196;Database=WordAnalyzer; Uid=dbWordAnalyzer;Pwd=dbpassword');
			$this->db_con->exec('SET NAMES utf8,CHARACTER_SET_CLIENT=utf8,CHARACTER_SET_RESULTS=utf8;');
		} catch (PDOException $e) {
echo "error occur: {$e->getMessage()} <br/>";
			$this->db_con = null;
			//var_dump($e);
			throw new Exception('Cannot connect to database.');
		}
	}
	
	public function StartConnection(){
		return null;
	}
	
	public function CloseConnection(){
		$this->db_con = null;
	}
	
	// return boolean or result set
	public function ExecuteQuery($query){
		return $this->db_con->query($query);
	}
	
	public function GetNextRow(&$sth){
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function GetAllRows(&$sth){
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function GetNumRows(&$sth){
		return $sth->rowCount();
	}
	
	public function FreeResultSet(&$sth){
		$sth = null;
	}
	
	public function PrepareQuery($query){
		return $this->db_con->prepare($query);
	}
	
	public function ExecutePreparedQuery($sth,$params){
		return $sth->execute($params);
	}
	
	public function FormatQueryElement($elem){
		$elem = str_replace("\\","\\\\",$elem);
		$elem = str_replace("'","\\'",$elem);
		$elem = str_replace('"','\"',$elem);
		return $elem;
	}
}
?>
