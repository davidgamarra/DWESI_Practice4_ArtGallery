<?php

class Art {
	
	private $id, $email, $title, $image, $cdate;
    
    function __construct($id = null, $email = null, $title = null, $image = null, $cdate = null) {
        $this->id = $id;
        $this->email = $email;
		$this->title = $title;
		$this->image = $image;
		$this->cdate = $cdate;
    }
    
    function getId() {
		return $this->id;
	}
    
    function getEmail() {
        return $this->email;
    }
	
	function getTitle() {
		return $this->title;
	}
	
	function getImage() {
		return $this->image;
	}
	
	function getCdate() {
		return $this->cdate;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
    function setEmail($email) {
        $this->email = $email;
    }
	
	function setTitle($title) {
		$this->title = $title;
	}
	
	function setImage($image) {
		$this->image = $image;
	}
	
	function setCdate($cdate) {
		$this->cdate = $cdate;
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