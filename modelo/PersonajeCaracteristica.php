<?php



/* *******************************************************************************************
 
 * CLASE PersonajeCaracteristica

 * ***************************************************************************************** */

class PersonajeCaracteristica {

	private $idCaracteristica;	 // id de la característica
	private $nombre; // nombre de la característica
	private $nivel;  // nivel de la característica para el personaje


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

	public function getIdCaracteristica() {
		return $this->idCaracteristica;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function getNivel() {
		return $this->nivel;
	}

	public function setIdCaracteristica($idCaracteristica) {
		$this->idCaracteristica = $idCaracteristica;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}	

	public function setNivel($nivel) {
		$this->nivel = $nivel;
	}	

}

?>