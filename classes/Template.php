<?php
class Template {
	
	private $template, $args, $content;
    
    function __construct($template, $args = null) {
        $this->template = $template;
		$this->args = $args;
		$this->content = file_get_contents($this->template);
		if($args !== null){
			foreach($this->args as $key =>$value){
				$this->content = str_replace("{".$key."}", $value, $this->content);
			}
		}
    }

	function show() {
		echo $this->content;
	}
	
	function get() {
		return $this->content;
	}
}