<?php



/* *******************************************************************************************
 
 * CLASE ParrafoHijo

 * ***************************************************************************************** */

class ParrafoHijo {

	private $idParrafoPadre;	 // id del personaje con el que se relaciona el personaje principal
	private $idParrafoHijo; 	 // nombre de la relación


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct() { }


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	

		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	



	/* Métodos getters y setters */

	public function getIdParrafoPadre() {
		return $this->idParrafoPadre;
	}

	public function getIdParrafoHijo() {
		return $this->idParrafoHijo;
	}



	public function setIdParrafoPadre($idParrafoPadre) {
		$this->idParrafoPadre = $idParrafoPadre;
	}	

	public function setIdParrafoHijo($idParrafoHijo) {
		$this->idParrafoHijo = $idParrafoHijo;
	}	

}

?>