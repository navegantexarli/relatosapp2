<?php



/* *******************************************************************************************
 
 * CLASE NodoPersonaje

 * ***************************************************************************************** */

class NodoPersonaje {

	private $idPersonaje;	 // id del personaje
	private $nombre; 		 // nombre del personaje
	private $nombreLargo; 	 
	private $sexo; 
	private $anyo; 
	private $numeroImagen;
	private $presente; 		 // si el personaje está o no presente en el nodo
	private $nombreImagen; 	 // nombre de la imagen que se ha elegido para mostrar en el nodo


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

	public function getNumeroImagen() {
		return $this->numeroImagen;
	}

	public function getPresente() {
		return $this->presente;
	}

	public function getNombreImagen() {
		return $this->nombreImagen;
	}



	public function setIdPersonaje($idPersonaje) {
		$this->idPersonaje = $idPersonaje;
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

	public function setNumeroImagen($numerImagen) {
		$this->numerImagen = $numerImagen;
	}	

	public function setPresente($presente) {
		$this->presente = $presente;
	}	

	public function setNombreImagen($nombreImagen) {
		$this->nombreImagen = $nombreImagen;
	}	

}

?>