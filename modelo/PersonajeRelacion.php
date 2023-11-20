<?php



/* *******************************************************************************************
 
 * CLASE PersonajeRelacion

 * ***************************************************************************************** */

class PersonajeRelacion {

	private $idPersonaje2;	 // id del personaje con el que se relaciona el personaje principal
	private $idRelacion; 	 // nombre de la relación
	private $nombre;  		 // nombre de personaje2
	private $nombreLargo;   // nombre largo de personaje2
	private $sexo;  		 // sexo del personaje2
	private $anyo;  		 // anyo de nacimiento de personaje2
	private $nombreRelacion;  // nombre de la relacion


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

	public function getIdPersonaje2() {
		return $this->idPersonaje2;
	}

	public function getIdRelacion() {
		return $this->idRelacion;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function getNombreLargo() {
		return $this->nombreLargo;
	}

	public function getSexo() {
		return $this->sexo;
	}

	public function getAnyo() {
		return $this->anyo;
	}

	public function getNombreRelacion() {
		return $this->nombreRelacion;
	}

	public function setIdPersonaje2($idPersonaje2) {
		$this->idPersonaje2 = $idPersonaje2;
	}

	public function setIdRelacion($idRelacion) {
		$this->idRelacion = $idRelacion;
	}	

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}	

	public function setNombreLargo($nombreLargo) {
		$this->nombreLargo = $nombreLargo;
	}	

	public function setSexo($sexo) {
		$this->sexo = $sexo;
	}	

	public function setAnyo($anyo) {
		$this->anyo = $anyo;
	}	

	public function setNombreRelacion($nombreRelacion) {
		$this->nombreRelacion = $nombreRelacion;
	}	

}

?>