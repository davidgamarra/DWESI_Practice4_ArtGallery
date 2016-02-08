<?php

class User {
	
	private $email, $pass, $alias, $image, $style, $description, $admin;
    
    function __construct($email = null, $pass = null, $alias = null, $image = null, 
						 $style = null, $description = null, $admin = null) {
        $this->email = $email;
		$this->pass = $pass === null ? $pass : sha1($pass);
		$this->alias = $alias;
		$this->image = $image;
		$this->style = $style;
		$this->description = $description;
		$this->admin = $admin;
    }
    
    function getEmail() {
        return $this->email;
    }
	
	function getPass() {
		return $this->pass;
	}
	
	function getAlias() {
		return $this->alias;
	}
	
	function getImage() {
		return $this->image;
	}
	
	function getStyle() {
		return $this->style;
	}
	
	function getDescription() {
		return $this->description;
	}

	function getAdmin() {
		return $this->admin;
	}
	
    function setEmail($email) {
        $this->email = $email;
    }
	
	function setPass($pass) {
		$this->pass = sha1($pass);
	}
	
	function setAlias($alias) {
		$this->alias = $alias;
	}
	
	function setImage($image) {
		$this->image = $image;
	}
	
	function setStyle($style) {
		$this->style = $style;
	}
	
	function setAdmin($admin) {
		$this->admin = $admin;
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	function getJson() {
		$r = '{';
		foreach($this as $key => $value) {
			$r .= '"' . $key . '":"' . $value . '",';
		}
		$r = substr($r, 0, -1);
		$r .= '}';
		return $r;
	}

	function set($values, $index=0) {
		$i = 0;
		foreach($this as $key => $value) {
			$this->$key = $values[$i+$index];
			$i++;
		}
	}
	
	function getArray($values=true) {
		$r = array();
		foreach($this as $key => $value) {
			if($values){
				$r[$key] = $value;
			} else {
				$r[$key] = null;
			}
		}
		return $r;
	}
	
	function read(){
		foreach($this as $key => $value) {
			$this->$key = Request::req($key);
		}
	}
	
}