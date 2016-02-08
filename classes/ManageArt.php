<?php

class ManageArt {
	
	private $db = null;
	private $table = "art";
	
	function __construct(DataBase $db) {
		$this->db = $db;
	}
	
	function get($id) {
		$params = array();
		$params["id"] = $id;
		$this->db->select($this->table, "*", "id=:id", $params);
		$row = $this->db->getRow();
		$art = new Art();
		$art->set($row);
		return $art;
	}
	
	function set(Art $art) {
		$conditions = array();
		$conditions["id"] = $art->getId();
		return $this->db->update($this->table, $art->getArray(), $conditions);
	}
	
	function insert(Art $art) {
		return $this->db->insert($this->table, $art->getArray(), false);
	}
	
	function delete($id) {
		$params = array();
		$params["id"] = $id;
		return $this->db->delete($this->table, $params);
	}
	
	function getList($email) {
		$this->db->query($this->table, ["*"], array("email" => $email));
		$r = array();
		while($row = $this->db->getRow()) {
			$art = new Art();
			$art->set($row);
			$r[] = $art;
		}
		return $r;
	}
	
	function count($condition = "1=1", $params = array()){
		return $this->db->count($this->table, $condition, $params);
	}
	
}