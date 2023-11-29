<?php

include_once ("Controlador.php");
include_once ("modelo/Buscador.php");


/* *******************************************************************************************
 
 * CLASE ControlCaracteristicas q1 q10 q11 q12 q16

 * ***************************************************************************************** */

class ControlCaracteristicas extends Controlador {

	/* *******************************************************************************************
	 * CONSTRUCTOR q1 q2 q3 q4
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearJsonCaracteristicas($arrCaracteristicas, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de características
		foreach ($arrCaracteristicas as $caracteristica) {
			
			// se crea la siguiente caracteristica
			$arrJson[] = array(
				"id" => $caracteristica->getId(),
				"nombre" => $caracteristica->getNombre()
			);
		}

		// se incluye el número de registros y las características en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirCaracteristicas() {

		// se carga el buscador
		$buscador = new Buscador();

		// se buscan las características que cumplen con el filtro		
		$arrCaracteristicas = $buscador->buscarCaracteristicas();

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de características que hay
		$numRegistros = $buscador->numRegistros('caracteristica');

		// se abre la vista de las características
		require 'vista/VistaCaracteristicas.php';

	}


	public function buscarCaracteristicas() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se carga el buscador
		$buscador = new Buscador();
		
		// se buscan las características que cumplen con el filtro
		$arrCaracteristicas = $buscador->buscarCaracteristicas($nombre);

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de características que hay
		$numRegistros = $buscador->numRegistros('caracteristica');

		// se codifica el array de características en un JSON
		$jsonCaracteristicas = $this->crearJsonCaracteristicas($arrCaracteristicas, $numRegistros);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;

	}


	public function crearCaracteristica() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se instancia la característica
		$caracteristica = new Caracteristica();

		// se inserta la característica
		$caracteristica->insertar($nombre);

		// se instancia el buscador
		$buscador = new Buscador();

		// se buscan todos los personajes
		$buscador->limitar(false);
		$arrPersonajes = $buscador->buscarPersonajes();

		// se crea la nueva característica por cada personaje
		$caracteristica->insertarPersonajes($arrPersonajes, $this->nivelDefecto);

		// se obtiene la cantidad de características que hay en total
		$numRegistros = $buscador->numRegistros('caracteristica');
		
		// se buscan las características con filtro
		$buscador->limitar(true);
		$arrCaracteristicas = $buscador->buscarCaracteristicas();

		// se codifica el array de características en un JSON
		$jsonCaracteristicas = $this->crearJsonCaracteristicas($arrCaracteristicas, $numRegistros);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;		

	}


	public function actualizarCaracteristica() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];

		// se instancia la característica
		$caracteristica = new Caracteristica($id);

		// se inserta la característica
		$caracteristica->actualizar($nombre);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de características que hay en total
		$numRegistros = $buscador->numRegistros('caracteristica');
		
		// se buscan las características sin filtro
		$buscador->limitar(true);
		$arrCaracteristicas = $buscador->buscarCaracteristicas();

		// se codifica el array de características en un JSON
		$jsonCaracteristicas = $this->crearJsonCaracteristicas($arrCaracteristicas, $numRegistros);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;		
		
	}


	public function eliminarCaracteristica() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia la característica
		$caracteristica = new Caracteristica($id);

		// se inserta la característica
		$caracteristica->eliminar();

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de características que hay en total
		$numRegistros = $buscador->numRegistros('caracteristica');
		
		// se buscan las características sin filtro
		$buscador->limitar(true);
		$arrCaracteristicas = $buscador->buscarCaracteristicas();

		// se codifica el array de características en un JSON
		$jsonCaracteristicas = $this->crearJsonCaracteristicas($arrCaracteristicas, $numRegistros);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;				
	}


}

?>
