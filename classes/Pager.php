<?php
class Pager {

    private $registers, $nrpp, $currentPage;

    function __construct($registers, $nrpp = Constant::NRPP, $currentPage = 1) {
		if($nrpp === null){
			$nrpp = Constant::NRPP;
		}
		if($currentPage === null){
			$currentPage = 1;
		}
		$this->registers = $registers;
		$this->nrpp = $nrpp;
		$this->currentPage = $currentPage;
    }

    function getRegisters() {
		return $this->registers;
    }

    function getNrpp() {
		return $this->nrpp;
    }

    function getCurrentPage() {
		return $this->currentPage;
    }

    function getFirst(){
		return 1;
    }

    function getPrevious(){
		return max(1, $this->currentPage-1);
    }

    function getNext(){
		return min($this->currentPage+1, $this->getPages());
    }
	
	function getLast(){
		return $this->getPages();
	}
	
	function getSelect($id, $name = null){
		if($name === null) {
			$name = $id;
		}
		$array = ["10"=>"10", "50"=>"50", "100"=>"100"];
		return Util::getSelect($name, $array, $this->nrpp, false, "", $id);
	}		
	
    function getSelectFull($id, $name = null, $params = array()){
		if($name === null) {
			$name = $id;
		}
		$array = ["10"=>"10", "50"=>"50", "100"=>"100"];
		
		$r = "<form style='display: inline;' method='get' id='$id' name='$name'>";
			$r .= Util::getSelect("nrpp", $array, $this->nrpp, false);
		foreach($params as $key => $value) {
			if($key !== "nrpp"){
				$r.= "<input type='hidden' name='$key' value='$name'/>";
			}
		}
			$r .= "<input type='submit' value='ver' />";
		$r .= "</form>";
    }

    function getPages(){
		return ceil($this->registers / $this->nrpp);
    }

    function setRegisters($registers) {
		$this->registers = $registers;
    }

    function setNrpp($nrpp) {
		$this->nrpp = $nrpp;
    }

    function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
    }   

    function getEnlacesPaginas($rango, $enlace, $nombreParametroPagina, $pagina = 0){

    }

}