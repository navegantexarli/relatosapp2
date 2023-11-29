<?php

include_once ("Controlador.php");
include_once ("modelo/Buscador.php");
include_once ("ArbolProfundidades.php");


/* *******************************************************************************************
 
 * CLASE ControlGuiones q20

 * ***************************************************************************************** */

class ControlGuiones extends Controlador {

	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	private function crearJsonGuiones($arrGuiones, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de guiones
		foreach ($arrGuiones as $guion) {
			
			// se crea la siguiente guion
			$arrJson[] = array(
				"id" => $guion->getId(),
				"idParrafoIni" => $guion->getIdParrafoIni(),
				"titulo" => $guion->gettitulo(),
				"profundidad" => $guion->getProfundidad(),
				"refrescada" => $guion->getRefrescada()
			);
		}

		// se incluye el número de registros y las guiones en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}	


	private function crearJsonParrafo(
		$arrParrafosPadre, $arrParrafosHijo, $parrafo, $numParrafos) {

		// se inicializan los arrays que se van a utilizar
		$arrJsonParrafosPadre = array();
		$arrJsonParrafosHijo = array();

		// se recorre todo el array de parrafos padre
		foreach ($arrParrafosPadre as $parrafoPadre) {
			
			// se crea el array de parrafosPadre
			$arrJsonParrafosPadre[] = array("id" => $parrafoPadre->getId());
		}

		// se recorre todo el array de parrafos hijo
		foreach ($arrParrafosHijo as $parrafoHijo) {
			
			// se crea el array de parrafosPadre
			$arrJsonParrafosHijo[] = array("id" => $parrafoHijo->getId());
		}		

		// se crea el array para los atributos del párrafo solicitado
		$arrJsonParrafo = array(
			"id" => $parrafo->getId(),
			"operaciones" => $parrafo->getOperaciones(),
			"texto" => $parrafo->getTexto(),
			"nivel" => $parrafo->getNivel(),
			"marcado" => $parrafo->getMarcado(),
			"profundidad" => $parrafo->getProfundidad()
		);

		// se crea el array JSON con los arrays creados
		$arrJson = array(
			$numParrafos, $arrJsonParrafosPadre, $arrJsonParrafosHijo, $arrJsonParrafo);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}	


	private function crearJsonParrafosHijo($arrParrafosHijo) {

		// se inicializan los arrays que se van a utilizar
		$arrJsonParrafosHijo = array();

		// se recorre todo el array de parrafos hijo
		foreach ($arrParrafosHijo as $parrafoHijo) {
			
			// se crea el array de parrafosHijo
			$arrJsonParrafosHijo[] = array("id" => $parrafoHijo->getId());
		}		

		// se crea el array JSON con los arrays creados
		$arrJson = array('OK', $arrJsonParrafosHijo);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}	


	private function crearJsonMarcas($arrParrafosMarcados, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de marcas
		foreach ($arrParrafosMarcados as $parrafoMarcado) {
			
			// se crea el siguiente parrafo marcado
			$arrJson[] = array(
				"id" => $parrafoMarcado->getId(),
				"texto" => $parrafoMarcado->getTexto()
			);
		}

		// se incluye el número de registros y los párrafos marcados en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirGuiones() {

		// se carga el buscador
		$buscador = new Buscador();

		// se buscan guiones que cumplen con el filtro
		$arrGuiones = $buscador->buscarGuiones();

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de guiones que hay
		$numRegistros = $buscador->numRegistros('guion');

		// se abre la vista de las guiones
		require 'vista/VistaGuiones.php';		

	}


	public function buscarGuiones() {

		// se recogen los parámetros de entrada
		$titulo = $_POST['titulo'];

		// se carga el buscador
		$buscador = new Buscador();
		
		// se obtiene la cantidad de guiones que hay en total
		$numRegistros = $buscador->numRegistros('guion');
		
		// se buscan guiones que cumplen con el filtro
		$arrGuiones = $buscador->buscarGuiones($titulo);

		// se codifica el array de guiones en un JSON
		$jsonGuiones = $this->crearJsonGuiones($arrGuiones, $numRegistros);

		// se cdevuelve el json de guiones
		echo $jsonGuiones;		

	}


	public function crearGuion() {

		// se recogen los parámetros de entrada
		$titulo = $_POST['titulo'];

		// se instancia guion
		$guion = new Guion();

		// se inserta guion
		$guion->insertar($titulo);

		// se crea el primer párrafo del guión
		$idParrafoIni = $guion->insertarParrafo($this->nivelDefecto);

		// se obtiene el párrafo creado
		$parrafo = new Parrafo($idParrafoIni);

		// se asigna el párrafo inicial al guion
		$guion->asignarParrafoInicial($parrafo);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de guiones que hay en total
		$buscador->limitar(false);
		$numRegistros = $buscador->numRegistros('guion');
		
		// se buscan guiones con filtro
		$buscador->limitar(true);
		$arrGuiones = $buscador->buscarGuiones();

		// se codifica el array de guiones en un JSON
		$jsonGuiones = $this->crearJsonGuiones($arrGuiones, $numRegistros);

		// se cdevuelve el json de guiones
		echo $jsonGuiones;			

	}


	public function actualizarGuion() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$titulo = $_POST['titulo'];

		// se instancia guion
		$guion = new Guion($id);

		// se inserta guion
		$guion->actualizar($titulo);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de guiones que hay en total
		$buscador->limitar(false);
		$numRegistros = $buscador->numRegistros('guion');
		
		// se buscan guiones con filtro
		$buscador->limitar(true);
		$arrGuiones = $buscador->buscarGuiones();

		// se codifica el array de guiones en un JSON
		$jsonGuiones = $this->crearJsonGuiones($arrGuiones, $numRegistros);

		// se cdevuelve el json de guiones
		echo $jsonGuiones;			

	}


	public function eliminarGuion() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia guion
		$guion = new Guion($id);

		// se inserta guion
		$guion->eliminar();

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de guiones que hay en total
		$buscador->limitar(false);
		$numRegistros = $buscador->numRegistros('guion');
		
		// se buscan guiones sin filtro
		$buscador->limitar(true);
		$arrGuiones = $buscador->buscarGuiones();

		// se codifica el array de guiones en un JSON
		$jsonGuiones = $this->crearJsonGuiones($arrGuiones, $numRegistros);

		// se cdevuelve el json de guiones
		echo $jsonGuiones;			

	}


	// v.4.6. Abrir marcas de guión.
	public function abrirParrafosMarcados() {

		// se recogen los parámetros de entrada
		$idGuion = $_POST['idGuion'];

		// se instancia guion
		$guion = new Guion($idGuion);

		// se solicita la búsqueda de los párrafos del guión que estén marcados
		$guion->limitar(true);
		$arrParrafos = $guion->buscarParrafosMarcados();

		// se solicita la búsqueda de todos los párrafos del guión que estén marcados
		$guion->limitar(false);
		$arrTotalParrafos = $guion->buscarParrafosMarcados();

		// se obtiene el total de párrafos marcados que hay
		$numRegistros = count($arrTotalParrafos);

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonMarcas($arrParrafos, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;			

	}


	// v41.1. Buscar marcas de guión.
	public function buscarParrafosMarcados() {

		// se recogen los parámetros de entrada
		$idGuion = $_POST['idGuion'];
		$idParrafo = $_POST['idParrafo'];
		$texto = $_POST['textoParrafo'];

		// se instancia guion
		$guion = new Guion($idGuion);

		// se solicita la búsqueda de los párrafos del guión que estén marcados
		$arrParrafos = $guion->buscarParrafosMarcados($idParrafo, $texto);

		// se solicita la búsqueda de todos los párrafos del guión que estén marcados
		$guion->limitar(false);
		$arrTotalParrafos = $guion->buscarParrafosMarcados();

		// se obtiene el total de párrafos marcados que hay
		$numRegistros = count($arrTotalParrafos);

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonMarcas($arrParrafos, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;		

	}


	// v41.2. Abrir párrafo de guión.
	public function abrirPrimerParrafo() {

		// se recogen los parámetros de entrada
		$idGuion = $_GET['idg'];
		$idParrafo = $_GET['idpf'];

		// se instancia guion
		$guion = new Guion($idGuion);

		// se instancia párrafo
		$parrafo = new Parrafo($idParrafo);

		// se obtiene la cantidad de párrafos del guión
		$numParrafos = $guion->cantidadParrafos();

		// se carga el párrafo inicial del guión
		$parrafoIni = $guion->cargarParrafoIni();

		// se buscan los párrafos padre
		$arrParrafosPadre = $parrafo->buscarParrafosPadre();

		// se buscan los párrafos hijo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// se incluye el nivel por defecto
		$nivelDefecto = $this->nivelDefecto;

		// se abre la vista de las guiones
		require 'vista/VistaParrafo.php';		

	}


	// v41.2. Abrir párrafo de guión.
	public function abrirParrafo() {

		// se desactiva el reporte de errores para poder visualizar sólo los errores diseñados 
		error_reporting(0);

		// se recogen los parámetros de entrada
		$idGuion = $_POST['idGuion'];
		$idParrafo = $_POST['idParrafo'];

		try {

		// se instancia guion
		$guion = new Guion($idGuion);

		// se instancia párrafo
		$parrafo = new Parrafo($idParrafo);

		// se comprueba si el párrafo solicitado pertecene al guión
		$existeParrafo = $guion->existeParrafo($parrafo);

		// se lanza el error
		if (!$existeParrafo) throw new Exception('El párrafo no pertenece al guión');

		// se obtiene la cantidad de párrafos del guión
		$numParrafos = $guion->cantidadParrafos();

		// se carga el párrafo inicial del guión
		$parrafoIni = $guion->cargarParrafoIni();

		// se buscan los párrafos padre
		$arrParrafosPadre = $parrafo->buscarParrafosPadre();

		// se buscan los párrafos hijo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// se codifica el array de parrafos en un JSON
		$jsonParrafos = $this->crearJsonParrafo(
			$arrParrafosPadre, $arrParrafosHijo, $parrafo, $numParrafos);

		// en caso de error se crea el mensaje de error
		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrJson = array('ERROR', $e->getMessage());

			// se codifica el array en formato JSON
			$jsonParrafos = json_encode($arrJson);

		}	
		
		// se devuelve el json de guiones
		echo $jsonParrafos;

	}


	public function asignarParrafoHijo() {

		// se recogen los parámetros de entrada
		$idParrafo = $_POST['idParrafo'];
		$idParrafoHijo = $_POST['idParrafoHijo'];

		// se pone en marcha el control de errores
		try {

		// se instancian párrafos y el guión
		$parrafo = new Parrafo($idParrafo);
		$parrafoHijo = new Parrafo($idParrafoHijo);
		$guion = new Guion($parrafo->getIdGuion());

		// se instancia el arbol de profundidades
		$ap = new ArbolProfundidades();

		// se busca si parrafoHijo es descendiente de parrafo
		$esDescendiente = $ap->esDescendiente($parrafo, $parrafoHijo, $guion);

		// si parrafoHijo es descendiente de parrafo no se puede asignar
		if ($esDescendiente) throw new Exception('Peligro de ruta cíclica. No se puede asignar párrafo hijo a un parrafo descendiente de dicho párrafo hijo.');

		// si parrafoHijo no es descendiente de párrafo se puede continuar
		// se asigna el párrafo hijo al padre
		$parrafo->asignarParrafoHijo($parrafoHijo);		

		// se buscan los párrafos hijo del párrafo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// se codifica el array en formato JSON
		$json = $this->crearJsonParrafosHijo($arrParrafosHijo);

		// en caso de error se crea el mensaje de error
		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrJson = array('ERROR', $e->getMessage());

			// se codifica el array en formato JSON
			$json = json_encode($arrJson);

		}

		// se envía el json de vuelta
		echo $json;

	}


	public function desasignarParrafoHijo() {

		// se recogen los parámetros de entrada
		$idParrafo = $_POST['idParrafo'];
		$idParrafoHijo = $_POST['idParrafoHijo'];

		// se inicializa el control de errores
		try {

		// se instancian párrafos
		$parrafo = new Parrafo($idParrafo);
		$parrafoHijo = new Parrafo($idParrafoHijo);

		// se buscan los párrafos padre del párrafo hijo
		$arrParrafosPadre = $parrafoHijo->buscarParrafosPadre();

		// si hay menos de un padre no se permitirá desasignar
		if (count($arrParrafosPadre) < 2)
			throw new Exception('Peligro de huérfano. No se puede desasignar el párrafo hijo ID:'.$parrafoHijo->getId().' porque el párrafo ID:'.$parrafo->getId().' se quedaría huérfano');

		// se desasigna el párrafo hijo al padre
		$parrafo->desasignarParrafoHijo($parrafoHijo);		

		// se buscan los párrafos hijo del párrafo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// se codifica el array en formato JSON
		$json = $this->crearJsonParrafosHijo($arrParrafosHijo);

		// en caso de error se crea el mensaje de error
		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrJson = array('ERROR', $e->getMessage());

			// se codifica el array en formato JSON
			$json = json_encode($arrJson);

		}

		// se envía el json de vuelta
		echo $json;		
	}


	public function guardarParrafo() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$nivel = $_POST['nivel'];
		$operaciones = $_POST['operaciones'];
		$texto = $_POST['texto'];
		$marcado = $_POST['marcado'];
		$profundidad = $_POST['profundidad'];

		// se instancia parrafo
		$parrafo = new Parrafo($id);

		// se actualiza  párrafo
		$parrafo->actualizar($nivel, $operaciones, $texto, $marcado, $profundidad);
	
		echo 'OK';
	}


	public function eliminarParrafo() {

		// se inicialia el mensaje de vuelta
		$txtEliminacion = 'Eliminado con éxito';

		// se recogen los parámetros de entrada
		$idParrafo = $_POST['idParrafo'];

		// se instancia parrafo
		$parrafo = new Parrafo($idParrafo);

		// se instancia el guion
		$guion = new Guion($parrafo->getIdGuion());

		// si el primer párrafo del guion y el párrafo actual son el mismo no se puede eliminar
		if ($parrafo->getId() == $guion->getIdParrafoIni()) 
			throw new Exception('No se puede eliminar. El párrafo es el primer párrafo del guión');

		// se buscan los hijos del párrafo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// si tiene hijos no se puede eliminar
		if (count($arrParrafosHijo) > 0) 
			throw new Exception('No se puede eliminar. El párrafo tiene hijos');

		// se elimina el párrafo
		$parrafo->eliminar();		

		// se devuelve el resultado de la operación
		echo 'OK';
	}


	public function crearNuevoParrafo() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// controlaremos los errores
		try {

		// se instancia parrafo padre
		$parrafoPadre = new Parrafo($id);

		// se instancia el guion
		$guion = new Guion($parrafoPadre->getIdGuion());

		// se crea el nuevo párrafo
		$idParrafoHijo = $guion->insertarParrafo($this->nivelDefecto);

		// se instancia el nuevo párrafo creado
		$parrafo = new Parrafo($idParrafoHijo);

		// se asigna al párrafo padre el párrafo hijo
		$parrafoPadre->asignarParrafoHijo($parrafo);

		// se obtiene la cantidad de párrafos del guión
		$numParrafos = $guion->cantidadParrafos();

		// se carga el párrafo inicial del guión
		$parrafoIni = $guion->cargarParrafoIni();

		// se buscan los párrafos padre
		$arrParrafosPadre = $parrafo->buscarParrafosPadre();

		// se buscan los párrafos hijo
		$arrParrafosHijo = $parrafo->buscarParrafosHijo();

		// se codifica el array de parrafos en un JSON
		$jsonParrafos = $this->crearJsonParrafo(
			$arrParrafosPadre, $arrParrafosHijo, $parrafo, $numParrafos);

		// en caso de error se crea el mensaje de error
		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrJson = array('ERROR', $e->getMessage());

			// se codifica el array en formato JSON
			$jsonParrafos = json_encode($arrJson);

		}	
		
		// se devuelve el json de guiones
		echo $jsonParrafos;		

	}


	public function asignarProfundidades() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia guión
		$guion = new Guion($id);

		// se instancia el arbol de profundidades
		$ap = new ArbolProfundidades();

		// se devolverá cualquier tipo de error capturado
		try {
		
			// se solicita asignar profundidades al guión
			$ap->asignarProfundidades($guion);

			// se crea el array de respuesta
			$arrProfundidades = array('OK', $guion->getProfundidad());

		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrProfundidades = array('ERROR', $e->getMessage());

		}	


		// se codifica el array en formato JSON
		$json = json_encode($arrProfundidades);

		// se devuelve el JSON creado con la profundidad de guión
		echo $json;

	}	
}

?>