<?php

include_once ("Controlador.php");
include_once ("ProcesadorInstrucciones.php");
include_once ("TraductorPalabras.php");


/* *******************************************************************************************
 
 * CLASE GeneradorRelatos

 * ***************************************************************************************** */

class GeneradorRelatos extends Controlador {

	private $guion;
	private $nivelContextual;
	private $arrNiveles;
	private $contexto;
	private $relato;



	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();

		$this->nivelContextual = $this->nivelDefecto;
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	

	private function calcularPorcentaje($parrafo) {

		// se calcula el porcentaje que resta para concluir la generación del relato
		$porcentaje = ($parrafo->getProfundidad() / $this->guion->getProfundidad()) * 100;

		// se redondea al entero más próximo
		$porcentaje = round($porcentaje, 0);

		// se devuelve el porcentaje
		return $porcentaje;
	}


	private function obtenerArrayNiveles() {

		// se inicializa el array de niveles
		$arrNiveles = array();

		// se acota el nivel contextual
		$nivelMin = ($this->nivelContextual < 2) ? 1 : $this->nivelContextual - 1;
		$nivelMax = ($this->nivelContextual > (self::nivelMax - 1)) ? 
			self::nivelMax : $this->nivelContextual + 1;

		// se obtiene el nivel aleatoriamente dentro de la cota
		$nivel = rand($nivelMin, $nivelMax);

		// se introduce los niveles desde el nivel obtenido hasta el menor
		for ($i=$nivel; $i>0; $i--) $arrNiveles[] = $i;

		// se introduce los niveles superiores al nivel obtenido
		for ($i=$nivel+1; $i<self::nivelMax+1; $i++) $arrNiveles[] = $i;

		// se devuelve el array de niveles calculado
		return $arrNiveles;

	}


	private function crearArrayParrafosNivel($arrParrafos, $nivel) {

		// se inicializa el array de parrafos de nivel
		$arrParrafosNivel = array();

		// se recorre el array de párrafos
		foreach ($arrParrafos as $value) {

			// si el nivel del párrafo es el mismo se elige
			if ($value->getNivel() == $nivel) $arrParrafosNivel[] = $value;
		}

		return $arrParrafosNivel;

	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	// A. Obtener el párrafo por el que va a continuar el relato.
	public function elegirParrafoHijo($arrParrafos) {

		// se inicializa a nulo el párrafo que se va a devolver
		$parrafo = null;

		// si el array de párrafos está vacío es porque no hay hijos y se ha llegado al final del guión
		if (count($arrParrafos)) {

			// se recorre el array de niveles buscando un párrafo
			foreach ($this->arrNiveles as $value) {
	
				// se crea un array de párrafos en base al nivel
				$arrParrafosNivel = $this->crearArrayParrafosNivel($arrParrafos, $value);

				// si se han econtrado párrafos se elegirá uno aleatoriamente
				if (count($arrParrafosNivel) > 0) {

					// se obtiene aleatoriamente la posición que ocupa el párrafo en el array
					$i = rand(0, count($arrParrafosNivel)-1);

					// se obtiene el párrafo
					$parrafo = $arrParrafosNivel[$i];

					// se fuerza la salida del bucle
					break;
				}
			}
		}

		return $parrafo;

	}


	public function generarRelato() {

		// se desactiva el reporte de errores para poder visualizar sólo los errores diseñados 
		error_reporting(1);

		// se devolverá cualquier tipo de error capturado
		try {

			// se recogen los parámetros de entrada
			$id = $_POST['id'];

			// se instancia relato
			$this->relato = new Relato($id);

			// se inicializa el porcentaje generado del relato
			$this->relato->actualizarGenerado(0);

			// se instancia el guión asociado al relato
			$this->guion = new Guion($this->relato->getIdGuion());		

			// se instancia el procesador de instrucciones
			$pi = new ProcesadorInstrucciones($this->relato);

			// se obtiene el array de niveles
			$this->arrNiveles = $this->obtenerArrayNiveles();

			// se instancia el traductor de palabras
			$tp = new TraductorPalabras($this->relato, $this->arrNiveles);

			// se iniciliza el nodo padre a nulo
			$nodoPadre = null;

			// se inicializa el orden del nodo a 1
			$orden = 1;

			// se inicializa la cantidad de nodo a 0
			$cantidadNodos = 0;

			// se inicializa el array para la actualización múltiple de nodos hijo
			$arrNodosHijo = array();

			// se obtiene el primer párrafo del guión
			$parrafo = new Parrafo($this->guion->getIdParrafoIni());

			// se obtienen los hijos del parrafo inicial
			$arrParrafos = $parrafo->buscarParrafosHijo();

			// mientras hayan párrafos hijo se sigue generando el relato
			while (count($arrParrafos)) {

				// se solicita la ejecución de las instrucciones
				$pi->ejecturarInstrucciones($this->contexto, $parrafo);

				// se instancia un nodo nuevo
				$nodo = new Nodo();

				// se crea el nuevo nodo
				$nodo->insertar($this->relato, $nodoPadre, $orden);

				// se añade el nnuevo nodo al array de nodos hijo si el nodoPadre no es nulo
				if ($nodoPadre) $arrNodosHijo[$nodoPadre->getId()] = $nodo->getId();

				// si el nodo padre es nulo, es el primer nodo del relato, con lo cual se añade
				else $this->relato->asignarNodoIni($nodo);

				// se solicita la traducción de las palabras del texto del párrafo
				$texto = $tp->traducirPalabras($this->contexto, $nodo, $parrafo->getTexto());

				// se actualiza el texto del nodo
				$nodo->actualizarTexto($texto);

				// se obtienen los hijos del parrafo
				$arrParrafos = $parrafo->buscarParrafosHijo();

				// se obtiene el array de niveles por el que se va a seguir
				$this->arrNiveles = $this->obtenerArrayNiveles();			

				// se calcula el porcentaje recorrido
				$porcentaje = $this->calcularPorcentaje($parrafo);

				// se elige el párrafo hijo como nuevo párrafo
				$parrafo = $this->elegirParrafoHijo($arrParrafos);

				// el nodo pasa a ser el nodo padre
				$nodoPadre = $nodo;

				// se incrementa el orden
				$orden++;

				// se incrementa la cantidad de nodos
				$cantidadNodos++;

				// se actualiza el porcentaje generado del relato
				$this->relato->actualizarGenerado($porcentaje);		

//usleep(1000000);

			}

			// se actualiza la cantidad de nodos generados
			$this->relato->actualizarCantidadNodos($cantidadNodos);

			// se instancia un nodo nuevo
			$nodo = new Nodo();

			// se solicita la actualización múltiple de los nodos hijo
			$nodo->actualizarNodosHijo($arrNodosHijo);

			// se actualiza el porcentaje generado del relato
			$this->relato->actualizarGenerado(100);

			// se crea el array con el mensaje
			$arrMensaje = array('OK', 'Relato generado con éxito');


		} catch (Exception $e) {

			// se crea el array de respuesta con el error
			$arrMensaje = array('ERROR', $e->getMessage());

		}			

		// se codifica el array en formato JSON
		$json = json_encode($arrMensaje);

		// se envía el mensaje
		echo $json;

	}


	public function obtenerEstadoProceso() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia relato
		$this->relato = new Relato($id);

		// se obtiene el porcentaje del estado del proceso
		$intEstadoProceso = $this->relato->getGenerado();

		// antes de devolver el estado del proceso espera un tiempo
		usleep(self::tiempoEspera);

		// se devuelve el estado del proceso
		echo $intEstadoProceso;

	}

}

?>