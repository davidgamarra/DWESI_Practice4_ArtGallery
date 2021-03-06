<?php
class AutoLoad {
    static public function load($class) {
        $file = "classes/" . str_replace('\\', '/', $class) . ".php";
        if (file_exists($file)) {
            require $file;
        } else {
			$file = "../classes/" . str_replace('\\', '/', $class) . ".php";
			require $file;
		}
    }
}
spl_autoload_register('AutoLoad::load');