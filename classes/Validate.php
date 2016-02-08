<?php

class Validate {
    
    static function isEmail($value){
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
	
	static function isNumber($value){
		return filter_var($value, FILTER_VALIDATE_INT) || filter_var($value, FILTER_VALIDATE_FLOAT);
	}
    
    static function isInteger($value){
        return filter_var($value, FILTER_VALIDATE_INT);
    }
    
    static function isFloat($value){
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
    
    static function isIP($value){
        return filter_var($value, FILTER_VALIDATE_IP);
    }
    
    static function isURL($value){
        return filter_var($value, FILTER_VALIDATE_URL);
    }
    
    static function isMinLength($value, $length){
        return strlen($value) >= $length;
    }
	
	static function isMaxLength($value, $length){
        return strlen($value) <= $length;
    }
	
	static function isLogin($value){
		 return preg_match('/^[A-Za-z][A-Za-z0-9]{5,9}$/', $value);
	}
	
	static function isDate($param) {
		$test_arr  = explode('-', $param);
		if (count($test_arr) == 3) {
			if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	static function isValid($value, $required = true, $type = "string", $format = "none", $minLength = 0, $maxLength = 0){
		if($required && empty($value)) {
			return false;
		}
		if($type === "number" && !self::isNumber($value)){
			return false;
		}
		if(($type === "date" && !self::isDate($value)) || ($type === "time" && !self::isTime($value))){
			return false;
		}
		if($type === "string"){
			if($maxLength !== 0 && !self::isMaxLength($value, $maxLength) || !self::isMinLength($value, $minLength)){
				return false;
			}
			switch($format){
				case "none":
					return true;
				case "gender":
					return $value === "M" || $value ==="F";
				case "email":
					return self::isEmail($value);
				case "url":
					return self::isURL($value);
				case "ip":
					return self::isIP($value);
				case "login":
					return self::isLogin($value);
			}
		}
	}
    
}