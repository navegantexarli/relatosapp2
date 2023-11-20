<?php

include_once ("Controlador.php");


/* *******************************************************************************************
 
 * CLASE ControlInstrucciones

 * ***************************************************************************************** */

class ControlInstrucciones extends Controlador {

	private $relato;
	private $nodo;



	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct($relato = null, $nodo = null) {
		parent::__construct();

		$this->relato = $relato;
		$this->nodo = $nodo;
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearJsonInstrucciones($arrInstrucciones, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de instrucciones
		foreach ($arrInstrucciones as $instruccion) {
			
			// se crea la siguiente instrucción
			$arrJson[] = array(
				"id" => $instruccion->getId(),
				"operacion" => $instruccion->getOperacion(),
				"descripcion" => $instruccion->getDescripcion()
			);
		}

		// se incluye el número de registros y las instrucciones en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	


	// instrucción: palabraClave()
	// descripción: obtiene el valor de la palabra clave que es si misma
	// ejemplo de uso: #pc:llorar
	public function pc($palabraClave) {

		// devuelve directamente el parámetro pues es exactamente la palabra clave buscada
		return $palabraClave;
	}


	// instrucción: tratoFemeninoSegunEdad()
	// descripción: devuelve el trato que se va a dar a todos los personajes según la edad que tienen
	// ejemplo de uso: #tfse:x1:x2
	public function tfse() {

		// se obtiene el único parámetro que llega a la función que es el array de parámetros
		$parametros = func_get_arg(0);

		// se obtiene el número de argumentos
		$numArgs = count($parametros);

		// si el numero de argumentos es mayor de 1 entonces el trato es en plural
		$strTrato = ($numArgs > 1) ? 'chicas' : 'chica';

		// se recorren todos los argumentos para obtener cada uno de los personajes
		// el primer argumento se desecha porque es el nombre de la función
		for ($i=1; $i<$numArgs; $i++) {

			// se obtiene el siguiente parámetro que es la etiqueta del personaje en el relato
			$etiquetaPersonaje = $parametros[$i];
			
			// se busca el siguiente personaje
			$personaje = $this->relato->buscarEtiquetaPersonaje($etiquetaPersonaje);

			// se obtiene la edad
			$edad = $personaje->getEdad();
 		
			// se modifica el trato según edad y cantidad de personajes
			if ($edad > 60) {
				$strTrato = ($numArgs > 1) ? 'señoras' : 'señora';
			} elseif ($edad > 40) {
				$strTrato = ($numArgs > 1) ? 'mujeres' : 'mujer';
			}
		}

		return $strTrato;
	}


	// instrucción: getContexto()
	// descripción: devuelve una palabra clave que se está utilizando según el contexto.
	// ejemplo de uso: #gc:animal -> obtiene el contexto de animal que podría ser tigre o serpiente
	public function gc(&$contexto, $palabraClave) {

		// se obtiene la palabra según el contexto, si existe
		$palabraClave = (isset($contexto[$palabraClave])) ? 
			$contexto[$palabraClave] : $palabraClave;

		// se devuelve la palabra clave
		return $palabraClave;

	}


	// instrucción: relacion()
	// descripción: devuelve la relación que hay entre dos personajes.
	// ejemplo de uso: #rel:x2:x1 -> obtiene la relación que tiene x2 con respecto a x1
	public function rel($etiqueta1, $etiqueta2) {

		// se obtiene el personaje1
		$personaje1 = $this->relato->buscarEtiquetaPersonaje($etiqueta1);

		// se obtiene el personaje2
		$personaje2 = $this->relato->buscarEtiquetaPersonaje($etiqueta2);

		// se obtiene un objeto de tipo PersonajRelación entre ambos personajes
		$pr = $personaje1->buscarRelacionConPersonaje($personaje2);

		// se obtiene la relación y se devuelve
		$strRelacion = $pr->getNombreRelacion();

		// se devuelve la relación
		return $strRelacion;

	}


	// instrucción: personaje()
	// descripción: busca un personaje en el relato cuya etiqueta coincida con el parámetro
	// ejemplo de uso: #p:x1:t -> para indicar que está presente en la escena 
	// ejemplo de uso: #p:x1:f -> para indicar que no está presente en la escena
	public function p($etiqueta, $presente = 't') {

		// se obtiene el personaje según etiqueta
		$personaje = $this->relato->buscarEtiquetaPersonaje($etiqueta);

		// si el personaje no existe se emite un error

		if (!$personaje) throw new Exception('ControlInstrucciones::p: El personaje cuya etiqueta es "'.$etiqueta.'" no está cargado'
		);

		// formateamos la presencia del personaje en el nodo
		$strPresente = (strcmp($presente, 't') == 0) ? 't' : 'f';

		// se asigna el personaje al nodo
		$this->nodo->asignarPersonaje($personaje, $strPresente);

		// se devuelve el nombre del personaje como valor de palabra clave
		return $personaje->getNombre();

	}



	/* *******************************************************************************************
	Funciones para operar con el modal que muestra las instrucciones
	******************************************************************************************* */

	public function abrirInstrucciones() {

		// se instancia el buscador
		$buscador = new Buscador();

		// se solicita la búsqueda de las N primeras instrucciones
		$buscador->limitar(true);
		$arrInstrucciones = $buscador->buscarInstrucciones();

		// se solicita la búsqueda de todas las instrucciones
		$buscador->limitar(false);
		$arrTotalInstrucciones = $buscador->buscarInstrucciones();

		// se obtiene el total de instrucciones 
		$numRegistros = count($arrTotalInstrucciones);

		// se codifica el array de relaciones en un JSON
		$jsonInstrucciones = $this->crearJsonInstrucciones($arrInstrucciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonInstrucciones;			

	}


	public function buscarInstrucciones() {

		// se recogen los parámetros de entrada
		$operacion = $_POST['operacion'];
		$descripcion = $_POST['descripcion'];

		// se instancia el buscador
		$buscador = new Buscador();

		// se solicita la búsqueda de las N primeras instrucciones
		$buscador->limitar(true);
		$arrInstrucciones = $buscador->buscarInstrucciones($operacion, $descripcion);

		// se solicita la búsqueda de todas las instrucciones
		$buscador->limitar(false);
		$arrTotalInstrucciones = $buscador->buscarInstrucciones();

		// se obtiene el total de instrucciones 
		$numRegistros = count($arrTotalInstrucciones);

		// se codifica el array de relaciones en un JSON
		$jsonInstrucciones = $this->crearJsonInstrucciones($arrInstrucciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonInstrucciones;			

	}



}

?>