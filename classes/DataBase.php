<?php
class DataBase {
	
	private $conection;
	private $query;
	
	//Automatically database creation using Constant class
	function __construct() {
		try{
			$this->conection = new PDO(
				'mysql:host=' . Constant::SERVER . ';dbname=' . Constant::DATABASE,
				Constant::DBUSER, Constant::DBPASSWORD,
				array( PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8' )
			);
		} catch(PDOException $e) {
			//TODO: errors
			exit();
		}
	}
	
	//Close database conection
	function close() {
		$this->conection = null;
	}
	
	//Send sql sentence to database
	function sendSQL($sql, $params=array()) {
		$this->query = $this->conection->prepare($sql);
		foreach($params as $key => $value){
			$this->query->bindValue($key, $value);
		}
		return $this->query->execute();
	}
	
	//Get each row from a query
	function getRow() {
		$r = $this->query->fetch();
		if($r === null){
			$this->query->closeCursor();
		}
		return $r;
	}
	
	//Insert into $table all $params and if $auto return id else return row count
	function insert($table, $params=array(), $auto = true) {
		$keys = "";
		$values = "";
		foreach($params as $key => $value){
			$keys .= $key . ',';
			$values .= ':' . $key . ',';
		}
		$keys = substr($keys, 0, -1);
		$values = substr($values, 0, -1);
		$sql = "insert into $table ($keys) values ($values)";
		
		if($this->sendSQL($sql, $params)){
			if($auto){
				return $this->getId();
			}
			return $this->rowCount();
		}
		return -1;
	}
	
	//Update table getting params array and condition array
	function update($table, $params=array(), $conditions=array()) {
		$set = "";
		$update = array();
		foreach($params as $key => $value){
			$set .= $key . '= :' . $key . ',';
			$update[$key] = $value;
		}
		$set = substr($set, 0, -1);
		
		$where = "";
		foreach($conditions as $key => $value){
			$where .= $key . '= :_' . $key . ' and ';
			$update['_' . $key] = $value;
		}
		//$where .= "1=1";
		$where = substr($where, 0, -4);
		
		$sql = "update $table set $set where $where";
		
		if($this->sendSQL($sql, $update)){
			return $this->rowCount();
		}
		return -1;
	}
	
	//Delete from table and return rows
	function delete($table, $params=array()){
		$where = "";
		foreach($params as $key => $value){
			$where .= $key . '= :' . $key . ' and ';
		}
		//$where .= "1=1";
		$where = substr($where, 0, -4);
		$sql = "delete from $table where $where";
		if($this->sendSQL($sql, $params)){
			return $this->rowCount();
		}
		return -1;
	}
	
	//Delete from table and return rows
	function erase($table, $condition, $params=array()){
		$sql = "delete from $table where $condition";
		if($this->sendSQL($sql, $params)){
			return $this->rowCount();
		}
		return -1;
	}
	
	//Select $params from $table where $conditions order by $order $limit
	function query($table, $params=["*"], $conditions=array(), $order="1", $limit="") {
		$select = "";
		foreach($params as $key => $value){
			$select .= $value . ',';
		}
		$select = substr($select, 0, -1);
		
		$where = "";
		foreach($conditions as $key => $value){
			$where .= $key . '= :' . $key . ' and ';
		}
		$where .= "1=1";
		//$where = substr($where, 0, -4);
		if($limit !== ""){
			$limit = "limit $limit";
		}
		
		$sql = "select $select from $table where $where order by $order $limit";
		return $this->sendSQL($sql, $conditions);
	}
	
	function select($table, $proyection = "*", $condition = "1 = 1", $params = array(), $order="1", $limit="") {
		if($limit !== ""){
			$limit = "limit $limit";
		}
		$sql = "select $proyection from $table where $condition order by $order $limit";
		return $this->sendSQL($sql, $params);
	}
	
	//Return row count from query
	function rowCount() {
		return $this->query->rowCount();
	}
	
	function count($table, $condition = "1=1", $params = array()){
		$sql = "select count(*) from $table where $condition";
		$this->sendSQL($sql, $params);
		$row = $this->getRow();
		return $row[0];
	}
	
	//Return last item id was inserted
	function getId() {
		return $this->conection->lastInsertId();
	}
	
	//Return conection errors
	function getConectionError() {
		return $this->conection->errorInfo();
	}
	
	//Return query errors
	function getQueryError() {
		return $this->query->errorInfo();
	}
}