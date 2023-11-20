<?php

include_once ("Controlador.php");
include_once ("modelo/Buscador.php");


/* *******************************************************************************************
 
 * CLASE ControlRelaciones

 * ***************************************************************************************** */

class ControlRelaciones extends Controlador {

	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearJsonRelaciones($arrRelaciones, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de relaciones
		foreach ($arrRelaciones as $relacion) {
			
			// se crea la siguiente relacion
			$arrJson[] = array(
				"id" => $relacion->getId(),
				"nombre" => $relacion->getNombre()
			);
		}

		// se incluye el número de registros y las relaciones en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirRelaciones() {

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relaciones que hay en total
		$numRegistros = $buscador->numRegistros('relacion');

		// se buscan las relaciones que cumplen con el filtro
		$arrRelaciones = $buscador->buscarRelaciones();

		// se obtiene el total de relaciones que hay
		$numRegistros = $buscador->numRegistros('relacion');

		// se abre la vista de las relaciones
		require 'vista/VistaRelaciones.php';
	}


	public function buscarRelaciones() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se carga el buscador
		$buscador = new Buscador();
		
		// se buscan las relaciones que cumplen con el filtro
		$arrRelaciones = $buscador->buscarRelaciones($nombre);

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de relaciones que hay
		$numRegistros = $buscador->numRegistros('relacion');

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonRelaciones($arrRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;		

	}


	public function crearRelacion() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se instancia la relacion
		$relacion = new Relacion();

		// se inserta la relacion
		$relacion->insertar($nombre);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relaciones que hay en total
		$numRegistros = $buscador->numRegistros('relacion');
		
		// se buscan las relaciones con filtro
		$buscador->limitar(true);
		$arrRelaciones = $buscador->buscarRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonRelaciones($arrRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;		

	}


	public function actualizarRelacion() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];

		// se instancia la relacion
		$relacion = new Relacion($id);

		// se inserta la relacion
		$relacion->actualizar($nombre);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relaciones que hay en total
		$numRegistros = $buscador->numRegistros('relacion');
		
		// se buscan las relaciones sin filtro
		$buscador->limitar(true);
		$arrRelaciones = $buscador->buscarRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonRelaciones($arrRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;		
	}


	public function eliminarRelacion() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia la relacion
		$relacion = new Relacion($id);

		// se inserta la relacion
		$relacion->eliminar();

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relaciones que hay en total
		$numRegistros = $buscador->numRegistros('relacion');
		
		// se buscan las relaciones sin filtro
		$buscador->limitar(true);
		$arrRelaciones = $buscador->buscarRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonRelaciones($arrRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;		
	}


}

?>