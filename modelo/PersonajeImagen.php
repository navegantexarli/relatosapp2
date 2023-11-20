<?php



/* *******************************************************************************************
 
 * CLASE PersonajeImagen

 * ***************************************************************************************** */

class PersonajeImagen {

	private $idPersonaje;	 // id de la característica
	private $nombreImagen; // nombre de la característica


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

	public function getIdPersonaje() {
		return $this->idPersonaje;
	}

	public function getNombreImagen() {
		return $this->nombreImagen;
	}


	public function setIdPersonaje($idPersonaje) {
		$this->idPersonaje = $idPersonaje;
	}

	public function setNombreImagen($nombreImagen) {
		$this->nombreImagen = $nombreImagen;
	}	

}

?>