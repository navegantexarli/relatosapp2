<?php

include_once ("Controlador.php");
include_once ("ControlInstrucciones.php");
include_once ("GeneradorRelatos.php");



/* *******************************************************************************************
 
 * CLASE TraductorPalabras

 * ***************************************************************************************** */

class TraductorPalabras extends Controlador {

	private $relato;
	private $arrNiveles;



	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct($relato, $arrNiveles) {
		parent::__construct();

		$this->relato = $relato;
		$this->arrNiveles = $arrNiveles;
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	

	private function detectarInstrucciones($texto) {

		// se inicializa el array de instrucciones
		$arrStrInstrucciones = array();

		// se busca la primera instrucción, si existe
		$intIni = strpos($texto, self::simboloIniInst);

		// mientras haya instrucciones en el texto se ejecuta el bucle de búsqueda de instrucciones
		while($intIni !== false) {

			// se avanza en el texto
			$texto = substr($texto, $intIni+1);

			// se obtiene el final de la instrucción
			$intFin = strpos($texto, self::simboloFinInst);

			// se obtiene la instrucción y se guarda en el array de instrucciones
			$arrStrInstrucciones[] = substr($texto, 0, $intFin);

			// se avanza en el texto
			$texto = substr($texto, $intFin);

			// se busca la siguiente instrucción, si existe
			$intIni = strpos($texto, self::simboloIniInst);

		}

		return $arrStrInstrucciones;
	}


	private function crearInstrucciones($arrStrInstrucciones) {

		// se inicializa el array de instrucciones
		$arrInstrucciones = array();

		// se recorren todas las cadenas de instrucciones
		foreach ($arrStrInstrucciones as $strInstruccion) {
			
			// se obtienen los elementos que comprondrá la instrucción
			$arrElementos = explode(':', $strInstruccion);

			// se añaden los elementos al array de instrucciones
			$arrInstrucciones[] = $arrElementos;

		}

		return $arrInstrucciones;
	}


	private function ejecutarInstrucciones(&$contexto, $nodo, $arrInstrucciones) {

		// se inicializa el array de palabras clave
		$arrPalabrasClave = array();

		// se instancia el controlador de instrucciones
		$ci = new ControlInstrucciones($this->relato, $nodo);

		// se procede a ejecutar todas las instrucciones en bucle
		foreach ($arrInstrucciones as $value) {
		
			// se obtiene el nombre de la instrucción
			$funcion = $value[0];

    		if (!method_exists($ci, $funcion)) 
    			throw new Exception('La instrucción "'.$funcion.'" no existe');

    		// se ejecuta la instrucción según el nombre de la función
    		switch ($funcion) {
    			
    			// instrucción: palabraClave()
    			case 'pc': $arrPalabrasClave[] = $ci->pc($value[1]); break;

   				// instrucción: tratoFemeninoSegunEdad()
    			case 'tfse': $arrPalabrasClave[] = $ci->tfse($value); break;

    			// si es la función que obtiene una palabra clave del contexto se ejecuta
    			case 'gc': $arrPalabrasClave[] = $ci->gc($contexto, $value[1]); break;

				// instrucción: relacion()
				case 'rel': $arrPalabrasClave[] = $ci->rel($value[1], $value[2]); break;

				// instrucción: personaje()
				case 'p': $arrPalabrasClave[] = 
					(isset($value[2])) ? $ci->p($value[1], $value[2]) : $ci->p($value[1]); break;

    		}
    		
		}

		return $arrPalabrasClave;
	}


	private function obtenerValoresPalabrasClave($arrPalabrasClave) {

		$arrValoresPalabrasClave = array();

		// se recorre el array de palabras clave
		foreach ($arrPalabrasClave as $nombrePalabraClave) {

			// se carga la palabra clave
			$palabraClave = new palabraClave($nombrePalabraClave);

			// si existe la palabra clave se continúa
			if ($palabraClave->getId()) {

				// se quiere obtener todos los valores de la palabra clave
				$palabraClave->limitar(false);

				// se busca todos los valores de la palabra clave
				$arrValores = $palabraClave->buscarValores();

				// si hay valores para la palabra clave se continúa analizando
				if (count($arrValores) > 0) {

					// si hay más de un valor para la palabra clave se continúa analizando
					if (count($arrValores) > 1) {

						// si hay más de un valor, se elegirá el valor según el nivel
						$arrValoresCandidatos = array();

						// se inicializa el pivote para buscar el valor que mejor se ajuste al nivel
						$pivote = 0;

						// se obtiene de la cantidad de elementos del array de niveles, si existe
						$limiteNiveles = (isset($this->arrNiveles)) ? count($this->arrNiveles) : 0;

						// hasta que no se encuentre el valor adecuado se continuará el bucle
						while($pivote < $limiteNiveles) {

							// se inicializa el array de valores susceptibles de ser elegidos
							$arrValoresAux = array();

							// se obtiene el siguiente nivel
							$nivel = $this->arrNiveles[$pivote];

							// se recorre el array de valores
							foreach ($arrValores as $valor) {
								
								// si el valor es del nivel actual se recoge
								if ($valor->getNivel() == $nivel) $arrValoresAux[] = $valor;
							}

							// se obtiene la cantidad de valores susceptibles de ser elegidos
							$numValores = count($arrValoresAux);

							// si hay valores en el array de valores susceptibles de ser elegidos se
							// elige uno aleatoriamente y se cierra el bucle.
							if ($numValores > 0) {

								// se elige un valor aleatoriamente
								$nivel = rand(0, $numValores-1);

								// se recoge temporalmente el valor elegido aleatoriamente
								$arrValoresPalabrasClave[] = $arrValoresAux[$nivel]->getValor();

								// se obliga a cerrar el bucle de búsqueda pues ya tenemos el valor
								$pivote = $limiteNiveles;
							
							// En caso de no haber valores susceptibles se continúa con el bucle de búsqueda
							} else $pivote++;
						}

						$this->arrNiveles[0];

					// si sólo hay una palabra clave se toma ésta
					} else $arrValoresPalabrasClave[] = $arrValores[0]->getValor();

				// si no hay valores para la palabra clave, se obtiene la propia palabra clave
				} else $arrValoresPalabrasClave[] = $nombrePalabraClave;

			// si no existe la palabra clave, se toma el nombre por defecto
			} else $arrValoresPalabrasClave[] = $nombrePalabraClave;
		}

		// se devuelve el array de valores
		return $arrValoresPalabrasClave;
	}


	private function actualizarTexto($texto, $arrStrInstrucciones, $arrValoresPalabrasClave) {

		// se va a reemplazar cada uno de los valores
		foreach ($arrValoresPalabrasClave as $key => $valorPalabraClave) {

			// se realiza el siguiente reemplazo
			$texto = str_replace(
				self::simboloIniInst.$arrStrInstrucciones[$key].self::simboloFinInst, 
				$valorPalabraClave, 
				$texto
			);
		}

		// se devuelve el texto
		return $texto;
	}	



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function traducirPalabras(&$contexto, $nodo, $texto) {

		// se detectan las cadenas de instrucciones
		$arrStrInstrucciones = $this->detectarInstrucciones($texto);

		// se crean las instrucciones
		$arrInstrucciones = $this->crearInstrucciones($arrStrInstrucciones);

		// se obtienen todas las palabras clave de cada una de las instrucciones
		$arrPalabrasClave = $this->ejecutarInstrucciones($contexto, $nodo, $arrInstrucciones);

		// se obtienen los valores de cada una de las palabras clave
		$arrValoresPalabrasClave = $this->obtenerValoresPalabrasClave($arrPalabrasClave);

		// se actualiza el texto con los valores de la palabra clave
		$texto = $this->actualizarTexto($texto, $arrStrInstrucciones, $arrValoresPalabrasClave);

		// se devuelte el texto traducido
		return $texto;
	}

}

?>