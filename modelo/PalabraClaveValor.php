<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Valor de Palabra Clave

 * ***************************************************************************************** */

class PalabraClaveValor extends Modelo  {

	private $nivel;
	private $valor;


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct() {
		parent::__construct();

		switch (func_num_args()) { 
			case 1: { $this->cargar(func_get_arg(0)); } // si hay un parámetro en el constructor es el id
		}
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function cargar() { }
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	


	/* Métodos getters y setters */

	public function getValor() {
		return $this->valor;
	}

	public function getNivel() {
		return $this->nivel;
	}


	public function setValor($valor) {
		$this->valor = $valor;
	}	

	public function setNivel($nivel) {
		$this->nivel = $nivel;
	}

}

?>