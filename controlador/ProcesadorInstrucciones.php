<?php

include_once ("Controlador.php");


/* *******************************************************************************************
 
 * CLASE ProcesadorInstrucciones

 * ***************************************************************************************** */

class ProcesadorInstrucciones extends Controlador {

	private $relato;
	private $arrPersonajes;
	private $arrEtiquetas;


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct($relato) {
		parent::__construct();

		$this->relato = $relato;
		$this->arrPersonajes = array();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearInstrucciones($strOperaciones) {

		// se inicializa el array de instrucciones
		$arrInstrucciones = array();

		// se busca todas las instrucciones en la cadena 
		while(strlen($strOperaciones)) {

			// se busca el inicio y fin de la i-ésima instrucción, si existe
			$intIni = strpos($strOperaciones, '[') + 1;
			$intFin = strpos($strOperaciones, ']');
			$intLong = $intFin - $intIni;

			// se obtiene la instrucción i-ésima
			$strInstruccion = substr($strOperaciones, $intIni, $intLong);

			// se obtienen los elementos que comprondrá la instrucción
			$arrElementos = explode(':', $strInstruccion);

			// se añaden los elementos al array de instrucciones
			$arrInstrucciones[] = $arrElementos;

			// se incrementa el pivote final
			$intFin += 2;

			// acortando la cadena de operaciones
			$strOperaciones = substr($strOperaciones, $intFin);

		}

		return $arrInstrucciones;
	}


	private function ejecutarInstrucciones(&$contexto, $arrInstrucciones) {

		// se procede a ejecutar todas las instrucciones en bucle
		foreach ($arrInstrucciones as $key => $value) {
		
			// se obtiene el nombre de la instrucción
			$funcion = $value[0];

    		if (!method_exists($this, $funcion)) 
    			throw new Exception('La instrucción "'.$funcion.'" no existe');

			// se obtiene el número de parámetros
			$numParametros = count($value) - 1;

    		// se ejecuta la instrucción según el nombre de la función
    		switch ($funcion) {

    			// si es la función que asigna sexo al personaje
    			case 'sexo': $this->sexo($value[1], $value[2]); break;

    			// si es la función que buscará un personaje según sus características
    			case 'getPersonaje': $this->getPersonaje($value[1], $value[2]); break;

    			// si es la función que buscará personajes
    			case 'getPersonajes': 
    				$this->getPersonajes($value[1], $value[2], $value[3]); break;

    			// si es la función que asigna contexto se ejecuta con los parámetros
    			case 'ac': $this->ac($contexto, $value[1], $value[2]); break;

			}		
		}
	}



	/* Instrucciones predefinidas ********************************************************** */ 

	// [sexo:x1:f]
	private function sexo($strEtiqueta, $booSexo) {

		// se crea una entrada para la etiqueta y el sexo del personaje
		$this->arrEtiquetas[$strEtiqueta] = $booSexo;
	}


	// [getPersonaje:x1:coraje>0#poder>1]   
	// [getPersonaje:x3:emparejada>3]
	private function getPersonaje($strEtiqueta, $strFiltros) {

		// se comprueba si la etiqueta del personaje ya está creada y asignada
		$personaje = $this->relato->buscarEtiquetaPersonaje($strEtiqueta);

		// si no existe personaje asignado a la etiqueta se continúa
		if (!$personaje) {

			// se inicializa el array de Valores
			$arrValores = array();

			// se asigna sexo para el personaje
			$booSexo = (isset($this->arrEtiquetas[$strEtiqueta])) ? $this->arrEtiquetas[$strEtiqueta] : 'f';

			// se separan las caracteristicas del filtro
			$arrFiltros = explode('#', $strFiltros);

			// se recorre el array de filtros para ir creando la sql
			foreach ($arrFiltros as $key => $value) {

				// se comprueba qué operación se ha solicitado
				if (strpos($value, '>')) $operacion = '>';
				elseif (strpos($value, '<')) $operacion = '<';
				else $operacion = '=';

				// se separa el nombre, del nivel
				$arrValores[$key] = explode($operacion, $value);

				// se añade la operación
				$arrValores[$key][] = $operacion;

			}

			// se inicializa la instrucción
			$instruccion = new Instruccion();

			// se solicita buscar un personaje que cumpla con el filtro
			$personaje = $instruccion->buscarPersonajeCaracteristicas(
				$booSexo, 
				$arrValores, 
				$this->arrPersonajes
			);

			// si no se ha encontrado un personaje se realiza una búsqueda simple
			if (!$personaje) $personaje = $instruccion->buscarPersonaje($booSexo, $this->arrPersonajes);

			// si hay personaje se guarda en el relato la asociación del personaje con la etiqueta y se asigna el personaje al array de personajes (de anetemano).
			if ($personaje) {
			
				// se guarda en el relato la asociación del personaje con la etiqueta
				$this->relato->insertarPersonaje($strEtiqueta, $personaje);

				// se asigna el personaje al array de personajes (de anetemano)				
				$this->arrPersonajes[] = $personaje;
			}
		}
	}
	

	// [getPersonajes:x2:pareja:x1]
	private function getPersonajes($strEtiqueta1, $strRelacion, $strEtiqueta2) {

		// se asigna sexo para los personajes 1 y 2
		$booSexo1 = (isset($this->arrEtiquetas[$strEtiqueta1])) ? $this->arrEtiquetas[$strEtiqueta1] : 'f';
		$booSexo2 = (isset($this->arrEtiquetas[$strEtiqueta2])) ? $this->arrEtiquetas[$strEtiqueta2] : 'f';

		// se crea el array de sexo
		$arrSexo = array($booSexo1, $booSexo2);

		// se comprueba si el personaje1 ya está creado y asignado
		$personaje1 = $this->relato->buscarEtiquetaPersonaje($strEtiqueta1);

		// se comprueba si el personaje2 ya está creado y asignado
		$personaje2 = $this->relato->buscarEtiquetaPersonaje($strEtiqueta2);

		// se inicializa la instrucción
		$instruccion = new Instruccion();



		// si todavía no se ha asignato ningún personaje al relato para las etiquetas pasadas
		// como parámetros se solicita buscar dos personajes que cumplan con el filtro
		if (!$personaje1 and !$personaje2) {

			// se pide buscar un par de personajes para asignar a personaje1 y personaje2
			$arrPersonajes = $instruccion->buscarPersonajesRelacion(
				$strRelacion, 
				$arrSexo,
				$this->arrPersonajes
			);

			// se comprueba la cantidad de personajes encontrados
			$numPersonajes = count($arrPersonajes);

			// si se han encontrado personajes se cargan
			if ($numPersonajes) {

				// se obtienen los dos personajes
				$personaje1 = $arrPersonajes[0];
				$personaje2 = $arrPersonajes[1];

			// si no se han encontrado personajes se realizará búsqueda simple de los mismos
			} else {
		
				// se realiza una búsqueda simple de personaje1
				$personaje1 = $instruccion->buscarPersonaje($booSexo1, $this->arrPersonajes);

				// se realiza una búsqueda simple de personaje2
				$personaje2 = $instruccion->buscarPersonaje($booSexo2, $this->arrPersonajes);

			}		

			// si se ha obtenido personaje 1 y 2 se guarda en el relato la asociación de
			// los personajes con la etiqueta correspondiente
			if ($personaje1) $this->relato->insertarPersonaje($strEtiqueta1, $personaje1);
			if ($personaje2) $this->relato->insertarPersonaje($strEtiqueta2, $personaje2);

		}
	


		// si sólo es personaje1 quien ya se ha asignado al relato, se buscará sólo personaje2
		if ($personaje1 and !$personaje2) {

			// se pide buscar un personaje para asignar a personaje2
			$personaje2 = $instruccion->buscarPersonaje2Relacion(
				$strRelacion, 
				$personaje1, 
				$booSexo2,
				$this->arrPersonajes);

			// si no se ha encontrado un personaje se realiza una búsqueda simple
			if (!$personaje2) $personaje2 = $instruccion->buscarPersonaje($booSexo2, $this->arrPersonajes);

			// se guarda en el relato la asociación del personaje con la etiqueta.
			if ($personaje2) $this->relato->insertarPersonaje($strEtiqueta2, $personaje2);

		}



		// si sólo es personaje2 quien ya se ha asignado al relato, se buscará sólo personaje1
		if (!$personaje1 and $personaje2) {

			// se pide buscar un personaje para asignar a personaje1
			$personaje1 = $instruccion->buscarPersonaje1Relacion(
				$strRelacion, 
				$personaje2, 
				$booSexo1,
				$this->arrPersonajes
			);

			// si no se ha encontrado un personaje se realiza una búsqueda simple
			if (!$personaje1) $personaje1 = $instruccion->buscarPersonaje($booSexo1, $this->arrPersonajes);

			// se guarda en el relato la asociación del personaje con la etiqueta.
			if ($personaje1) $this->relato->insertarPersonaje($strEtiqueta1, $personaje1);

		}

		// se asignan los personajes al array de personajes (de anetemano)				
		$this->arrPersonajes[] = $personaje1;
		$this->arrPersonajes[] = $personaje2;		
		
	}	


	// instrucción: asignarContexto()
	// descripción: devuelve una palabra clave que se está utilizando según el contexto.
	// ejemplo de uso: #ac:animal:tigre -> se le da a la palabra clave animal el contexto tigre.
	public function ac(&$contexto, $palabraClave, $valorContextual) {

		// se asigna la palabra según el contexto
		$contexto[$palabraClave] = $valorContextual;

		// se devuelve el valor de la palabra clave
		return $valorContextual;

	}

	

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function ejecturarInstrucciones(&$contexto, $parrafo) {

		// se carga el array de personajes ya asignados al relato
		$this->arrPersonajes = $this->relato->buscarPersonajes();

		// se crea el array de instrucciones
		$arrInstrucciones = $this->crearInstrucciones($parrafo->getOperaciones());

		// se ejecutan las instrucciones
		$this->ejecutarInstrucciones($contexto, $arrInstrucciones);

	}

}

?>