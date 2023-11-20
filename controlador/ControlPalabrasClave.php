<?php

include_once ("Controlador.php");
include_once ("modelo/Buscador.php");


/* *******************************************************************************************
 
 * CLASE ControlPalabrasClave

 * ***************************************************************************************** */

class ControlPalabrasClave extends Controlador {

	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearJsonPalabrasClave($arrPalabrasClave, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de palabras clave
		foreach ($arrPalabrasClave as $palabraClave) {
			
			// se crea la siguiente palabra clave
			$arrJson[] = array(
				"id" => $palabraClave->getId(),
				"nombre" => $palabraClave->getNombre()
			);
		}

		// se incluye el número de registros y las palabras clave en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}


	private function crearJsonValoresPalabraClave($arrValoresPalabraClave, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de valores de palabra clave
		foreach ($arrValoresPalabraClave as $valorPalabraClave) {
			
			// se crea la siguiente valorPalabraClave
			$arrJson[] = array(
				"valor" => $valorPalabraClave->getValor(),
				"nivel" => $valorPalabraClave->getNivel()
			);
		}

		// se incluye el número de registros y las valores de palabra clave en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirPalabrasClave() {

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de palabras clave que hay en total
		$numRegistros = $buscador->numRegistros('palabra_clave');

		// se buscan palabras clave que cumplen con el filtro
		$arrPalabrasClave = $buscador->buscarPalabrasClave();

		// se abre la vista de las valores de palabra clave
		require 'vista/VistaPalabrasClave.php';		

	}


	public function buscarPalabrasClave() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se carga el buscador
		$buscador = new Buscador();
		
		// se obtiene la cantidad de palabras clave que hay en total
		$numRegistros = $buscador->numRegistros('palabra_clave');
		
		// se buscan palabras clave que cumplen con el filtro
		$arrPalabrasClave = $buscador->buscarPalabrasClave($nombre);

		// se codifica el array de valores de palabra clave en un JSON
		$jsonPalabrasClave = $this->crearJsonPalabrasClave($arrPalabrasClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonPalabrasClave;		

	}


	public function crearPalabraClave() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];

		// se instancia palabraClave
		$palabraClave = new PalabraClave();

		// se inserta palabra clave
		$palabraClave->insertar($nombre);

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de palabras clave que hay en total
		$numRegistros = $buscador->numRegistros('palabra_clave');
		
		// se buscan las valores de palabra clave con filtro
		$buscador->limitar(true);
		$arrPalabrasClave = $buscador->buscarPalabrasClave();

		// se codifica el array de valores de palabra clave en un JSON
		$jsonPalabrasClave = $this->crearJsonPalabrasClave($arrPalabrasClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonPalabrasClave;			

	}


	public function actualizarPalabraClave() {

		// se recogen los parámetros de entrada
		$id = intval($_POST['id']);
		$nombre = $_POST['nombre'];

		// se instancia palabra clave
		$palabraClave = new PalabraClave($id);

		// se inserta palabraClave
		$palabraClave->actualizar($nombre);
		
		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de palabras clave que hay en total
		$numRegistros = $buscador->numRegistros('palabra_clave');
		
		// se buscan las valores de palabra clave sin filtro
		$buscador->limitar(true);
		$arrPalabrasClave = $buscador->buscarPalabrasClave();

		// se codifica el array de valores de palabra clave en un JSON
		$jsonPalabrasClave = $this->crearJsonPalabrasClave($arrPalabrasClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonPalabrasClave;	
				
	}


	public function eliminarPalabraClave() {

		// se recogen los parámetros de entrada
		$id = intval($_POST['id']);

		// se instancia palabra clave
		$palabraClave = new PalabraClave($id);

		// se inserta palabra clave
		$palabraClave->eliminar();

		// se instancia el buscador
		$buscador = new Buscador();

		// se obtiene la cantidad de palabras clave que hay en total
		$numRegistros = $buscador->numRegistros('palabra_clave');
		
		// se buscan palabras clave sin filtro
		$buscador->limitar(true);
		$arrPalabrasClave = $buscador->buscarPalabrasClave();

		// se codifica el array de valores de palabra clave en un JSON
		$jsonPalabrasClave = $this->crearJsonPalabrasClave($arrPalabrasClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonPalabrasClave;			
	}


	/* Valores de palabra clave ************************************************* */

	public function abrirValoresPalabraClave() {

		// se recogen los parámetros de entrada
		$idPalabraClave = intval($_GET['idpc']);

		// se instancia palabra clave
		$palabraClave = new PalabraClave($idPalabraClave);

		// se obtiene la cantidad de valores que hay para la palabra clave
		$numRegistros = $palabraClave->numValores();

		// se buscan los valores de la palabra clave que cumplen con el filtro
		$arrValoresPalabraClave = $palabraClave->buscarValores();
		 
		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de valores de palabra clave que hay
		$arrIdPalabraClave = array('id_palabra_clave', $palabraClave->getId());
		$numRegistros = $buscador->numRegistros('palabra_clave_valor', $arrIdPalabraClave);

		// se abre la vista de las valores de palabra clave
		require 'vista/VistaValoresPalabraClave.php';		

	}


	public function buscarValoresPalabraClave() {

		// se recogen los parámetros de entrada
		$idPalabraClave = intval($_POST['idpc']);
		$nivel = $_POST['nivel'];
		$valor = $_POST['valor'];

		// se instancia palabra clave
		$palabraClave = new PalabraClave($idPalabraClave);

		// se obtiene la cantidad de valores que hay para la palabra clave
		$numRegistros = $palabraClave->numValores();

		// se buscan los valores de la palabra clave que cumplen con el filtro
		$arrValoresPalabraClave = $palabraClave->buscarValores($nivel, $valor);
 
 		// se codifica el array de valores de palabra clave en un JSON
		$jsonValoresPalabraClave = $this->crearJsonValoresPalabraClave($arrValoresPalabraClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonValoresPalabraClave;		

	}	


	public function crearValorPalabraClave() {

		// se recogen los parámetros de entrada
		$idPalabraClave = intval($_POST['idpc']);
		$nivel = $_POST['nivel'];
		$valor = $_POST['valor'];

		// se instancia palabra clave
		$palabraClave = new PalabraClave($idPalabraClave);

		// se inserta el valor de palabra clave
		$palabraClave->insertarValor($nivel, $valor);

		// se obtiene la cantidad de valores que hay para la palabra clave
		$numRegistros = $palabraClave->numValores();

		// se buscan las valores de palabra clave con filtro
		$palabraClave->limitar(true);
		$arrValoresPalabraClave = $palabraClave->buscarValores();

		// se codifica el array de valores de palabra clave en un JSON
		$jsonValoresPalabraClave = $this->crearJsonValoresPalabraClave($arrValoresPalabraClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonValoresPalabraClave;			
		
	}


	public function eliminarValorPalabraClave() {

		// se recogen los parámetros de entrada
		$idPalabraClave = intval($_POST['idpc']);
		$valor = $_POST['valor'];

		// se instancia palabra clave
		$palabraClave = new PalabraClave($idPalabraClave);

		// se procede a eliminar el valor de palabra clave
		$palabraClave->eliminarValor($valor);
		
		// se obtiene la cantidad de valores que hay para la palabra clave
		$numRegistros = $palabraClave->numValores();

		// se buscan las valores de palabra clave sin filtro
		$palabraClave->limitar(true);
		$arrValoresPalabraClave = $palabraClave->buscarValores();

		// se codifica el array de valores de palabra clave en un JSON
		$jsonValoresPalabraClave = $this->crearJsonValoresPalabraClave($arrValoresPalabraClave, $numRegistros);

		// se cdevuelve el json de valores de palabra clave
		echo $jsonValoresPalabraClave;			

	}	


}

?>