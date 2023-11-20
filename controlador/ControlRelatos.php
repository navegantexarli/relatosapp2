<?php

include_once ("Controlador.php");
include_once ("GeneradorRelatos.php");
include_once ("modelo/Buscador.php");
include_once ("modelo/Nodo.php");


/* *******************************************************************************************
 
 * CLASE ControlRelatos

 * ***************************************************************************************** */

class ControlRelatos extends Controlador {

	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	private function crearJsonRelatos($arrRelatos, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de relatos
		foreach ($arrRelatos as $relato) {
			
			// se crea la siguiente relato
			$arrJson[] = array(
				"id" => $relato->getId(),
				"titulo" => $relato->getTitulo(),
				"idGuion" => $relato->getIdGuion(),
				"tituloGuion" => $relato->getTituloGuion(),
				"generado" => $relato->getGenerado()
			);
		}

		// se incluye el número de registros y las relatos en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}


	private function crearJsonNodo($nodo, $arrPersonajesNodo) {

		// se inicializa el array JSON
		$arrJsonPersonajesNodo = array();

		// se crea el array para el relato
		$arrJsonNodo = array(
			"id" => $nodo->getId(),
			"orden" => $nodo->getOrden(),
			"texto" => $nodo->getTexto(),
			"idNodoPadre" => $nodo->getIdNodoPadre(),
			"idNodoHijo" => $nodo->getIdNodoHijo()
		);		

		// se recorre todo el array de personajes del nodo
		foreach ($arrPersonajesNodo as $personajeNodo) {
			
			// se crea el array de parrafosPadre
			$arrJsonPersonajesNodo[] = array(
				"idPersonaje" => $personajeNodo->getIdPersonaje(),
				"nombreImagen" => $personajeNodo->getNombreImagen()
			);
		}

		// se crea el array JSON		
		$arrJson = array('OK', $arrJsonNodo, $arrJsonPersonajesNodo);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;

	}
	


	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirRelatos() {

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relatos que hay en total
		$numRegistros = $buscador->numRegistros('relato');

		// se buscan relatos que cumplen con el filtro
		$arrRelatos = $buscador->buscarRelatos();

		// se abre la vista de las relatos
		require 'vista/VistaRelatos.php';		

	}


	public function buscarRelatos() {

		// se recogen los parámetros de entrada
		$titulo = $_POST['titulo'];

		// se carga el buscador
		$buscador = new Buscador();
		
		// se obtiene la cantidad de relatos que hay en total
		$numRegistros = $buscador->numRegistros('relato');
		
		// se buscan relatos que cumplen con el filtro
		$arrRelatos = $buscador->buscarRelatos($titulo);

		// se codifica el array de relatos en un JSON
		$jsonRelatos = $this->crearJsonRelatos($arrRelatos, $numRegistros);

		// se cdevuelve el json de relatos
		echo $jsonRelatos;		

	}


	public function crearRelato() {

		// se recogen los parámetros de entrada
		$titulo = $_POST['titulo'];

		// se instancia relato
		$relato = new Relato();

		// se inserta relato
		$relato->insertar($titulo);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relatos que hay en total
		$numRegistros = $buscador->numRegistros('relato');
		
		// se buscan relatos sin filtro
		$buscador->limitar(true);
		$arrRelatos = $buscador->buscarRelatos();


		$jsonRelatos = $this->crearJsonRelatos($arrRelatos, $numRegistros);

		// se cdevuelve el json de relatos
		echo $jsonRelatos;			

	}


	public function actualizarRelato() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$titulo = $_POST['titulo'];

		// se instancia relato
		$relato = new Relato($id);

		// se inserta relato
		$relato->actualizar($titulo);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relatos que hay en total
		$numRegistros = $buscador->numRegistros('relato');
		
		// se buscan relatos sin filtro
		$buscador->limitar(true);
		$arrRelatos = $buscador->buscarRelatos();

		// se codifica el array de relatos en un JSON
		$jsonRelatos = $this->crearJsonRelatos($arrRelatos, $numRegistros);

		// se cdevuelve el json de relatos
		echo $jsonRelatos;		
	}


	public function eliminarRelato() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia relato
		$relato = new Relato($id);

		// se inserta relato
		$relato->eliminar();

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de relatos que hay en total
		$numRegistros = $buscador->numRegistros('relato');
		
		// se buscan relatos sin filtro
		$buscador->limitar(true);
		$arrRelatos = $buscador->buscarRelatos();

		// se codifica el array de relatos en un JSON
		$jsonRelatos = $this->crearJsonRelatos($arrRelatos, $numRegistros);

		// se cdevuelve el json de relatos
		echo $jsonRelatos;			
	}


	// v51.2. Asignar guión al relato.  q1
	public function asignarGuionRelato() {

		// se recogen los parámetros de entrada
		$idRelato = $_POST['idRelato'];
		$idGuion = $_POST['idGuion'];

		// se instancia relato
		$relato = new Relato($idRelato);

		// se instancia guion
		$guion = new Guion($idGuion);

		// se asigna el guión al relato
		$relato->asignarGuion($guion);

		echo 'OK';
	}


	public function abrirVisualizador() {

		// se recogen los parámetros de entrada
		$idRelato = $_GET['idr'];
		
		// se instancia relato
		$relato = new Relato($idRelato);

		// se carga el nodo inicial
		$nodo = new Nodo($relato->getIdNodoIni());

		// se obtiene el array de personajes del nodo
		$arrPersonajesNodo = $nodo->buscarPersonajes();

		// se abre la vista del visualizador
		require 'vista/VistaVisualizador.php';	
	}


	public function abrirNodo() {


		// se recogen los parámetros de entrada
		$idRelato = $_POST['idRelato'];
		$idNodo = $_POST['idNodo'];

		try {
		
		// se instancia el relato
		$relato = new Relato($idRelato);

		// se instancia nodo
		$nodo = new Nodo($idNodo);

		// si el nodo no pertecene al relato se envía el error
		if ($relato->getId() != $nodo->getIdRelato()) 
			throw new Exception('El nodo no pertenece al relato');

		// se cargan los personajes del nodo
		$arrPersonajesNodo = $nodo->buscarPersonajes();

		// se codifica el array de nodo en un JSON
		$jsonNodo = $this->crearJsonNodo($nodo, $arrPersonajesNodo);

		// en caso de error se crea el mensaje de error
		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrJson = array('ERROR', $e->getMessage());

			// se codifica el array en formato JSON
			$jsonNodo = json_encode($arrJson);

		}	
		
		// se devuelve el json de guiones
		echo $jsonNodo;

	}	

}

?>