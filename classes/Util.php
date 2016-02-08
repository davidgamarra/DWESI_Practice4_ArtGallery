<?php

class Util {
	static function getSelect($name, $params, $value=null, $empty=true, $attributes="", $id=null) {
		if($id !== null) {
			$id = "id='$id'";
		} else {
			$id = "";
		}
		$r = "<select name='$name' $id $attributes >\n";
		if($empty) {
			$r .= "<option value=''>&nbsp;</option>\n";
		}
		foreach($params as $key => $item){
			$selected = "";
			if($value !== null && $value === $key){
				$selected = "selected";
			}
			$r .= "<option $selected value='$key'>$item</option>\n";
		}
		$r .= "</select>\n";
		return $r;
	}
}