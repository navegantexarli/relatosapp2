<?php

include_once ("Controlador.php");


/* *******************************************************************************************
 
 * CLASE ArbolProfundidades

 * ***************************************************************************************** */

class ArbolProfundidades extends Controlador {

	private $arrProfundidadesInicial;
	private $arrProfundidadesFinal;
	private $intProfundidadGuion;


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();

		$this->arrProfundidadesInicial = array();
		$this->arrProfundidadesFinal = array();
		$this->intProfundidadGuion = 0;
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function formatearIndicesArrayParrafos($arrParrafos) {

		// se inicializa el nuevo array de párrafos
			$arrParrafosNuevo = array();
	
		// se recorre el antiguo array de párrafos
		foreach ($arrParrafos as $key => $value) {

			// se actualiza el índice de cada párrafo
			$arrParrafosNuevo[$value->getId()] = $value;
		}

		return $arrParrafosNuevo;
	}


	private function inicializarProfundidades($arrParrafos) {

		// se recorre el array de párrafos
		foreach ($arrParrafos as $value) {

			// se crea un elemento nuevo con la profundidad de cada párrafo
			$value->setProfundidad(0);
		}

		return $arrParrafos;
	}


	private function crearArrayProfundidadesIniciales($arrParrafos) {

		// se inicializa el array de profundidades iniciales
			$this->profundidadesInicial = array();
	
		// se recorre el array de párrafos
		foreach ($arrParrafos as $value) {

			// se crea un elemento nuevo con la profundidad de cada párrafo
			$this->arrProfundidadesInicial[$value->getId()] = $value->getProfundidad();
		}

	}


	private function crearArrayHijos($arrParrafosHijo) {

		// se inicializa el array de hijos
		$arrHijos = array();

		// se recorre el array de la estructura (padres e hijos) para craer el array de hijos
		foreach ($arrParrafosHijo as $key => $value) {

			// crea un array de hijos por cada padre
			$arrHijos[$value->getIdParrafoPadre()][] = $value->getIdParrafoHijo();
		}		

		return $arrHijos;
	}


	private function asignarHijos($arrParrafos, $arrIdsHijo) {

		// se recorre el array de ids de párrafo hijo para asignar a cada párrafo sus hijos
		foreach ($arrIdsHijo as $key => $value) {
			
			// se inicializa el array de párrafos hijo que va a tener el párrafo padre
			$arrParrafosHijo = array();

			// se recorre el array de ids de párrafo hijos asociados al parrafo padre
			foreach ($value as $value2) {

				// se añade el párrafo hijo al array de párrafos hijo
				$arrParrafosHijo[] = $arrParrafos[$value2];
			}

			// se asigna el array de párrafos hijo al párrafo padre
			$arrParrafos[$key]->setParrafosHijo($arrParrafosHijo);
		}

		return $arrParrafos;		
	}


	private function asignarProfundidad($parrafo, $profundidad) {

		// si la profundidad es mayor que la del párrafo actual habrá que actualizar
		if ($profundidad > $parrafo->getProfundidad())	{

			// se actualiza la profundiad del párrafo
			$parrafo->setProfundidad($profundidad);
			
			// se incrementa la profundidad en una unidad
			$profundidad++;

			// por cada uno de los hijos del párrafo se les asignará también la profundidad
			foreach ($parrafo->getParrafosHijo() as $value) {

				// llamada recursiva para asignar profundidad al párrafo hijo
				$this->asignarProfundidad($value, $profundidad);

				// se actualiza la profundidad guión si procede
				if ($this->intProfundidadGuion < $profundidad) $this->intProfundidadGuion = $profundidad;
			}
		}

	}


	private function crearArrayProfundidadesFinales($parrafo) {

		// se crea un array simplemente con el id de párrafo y el valor de la profundidad
		$this->arrProfundidadesFinal[$parrafo->getId()] = $parrafo->getProfundidad();

		// se recorre todos los párrafos hijo 
		foreach ($parrafo->getParrafosHijo() as $key => $value) {

			// se realiza la creación del array de profuniddades en modo recursivo
			$this->crearArrayProfundidadesFinales($value, $this->arrProfundidadesFinal);
		}
	}


	private function optimizarProfundidades() {

		$arrOptimizado = array();

		foreach ($this->arrProfundidadesFinal as $key => $value) {
			if ($value != $this->arrProfundidadesInicial[$key])
				$arrOptimizado[$key] = $value;
		}

		$this->arrProfundidadesFinal = $arrOptimizado;
	}


	private function crearArrayPadres($arrParrafosHijo) {

		// se inicializa el array de hijos
		$arrPadres = array();

		// se recorre el array de la estructura (padres e hijos) para craer el array de padres
		foreach ($arrParrafosHijo as $key => $value) {

			// crea un array de hijos por cada padre
			$arrPadres[$value->getIdParrafoHijo()][] = $value->getIdParrafoPadre();
		}		

		return $arrPadres;
	}	


	// función recursiva para comprobar si parrafo es descendiente de parrafo hijo
	// lo que se hace realmente es comprobar si el parrafo hijo es ascendente del parrafo
	private function buscarDescendiente($arrIdsParrafo, $idParrafo, $idParrafoHijo) {

		// se inicializa el resultado
		$esDescendiente = false;

		// se obtiene el array de padres del parrafo actual
		$arrIdsParrafoActual = 
			(isset($arrIdsParrafo[$idParrafo])) ? $arrIdsParrafo[$idParrafo] : array();

		// se recorre el array de padres del parrafo actual
		foreach ($arrIdsParrafoActual as $idParrafoPadre) {

			// se comprueba si el id del parrafo padre del parrafo actual es el mismo que el id
			// del párrafo hijo. En ese caso será descendiente
			if ($esDescendiente || ($idParrafoPadre == $idParrafoHijo)) {

				// el parrafo padre es descendiente
				$esDescendiente = true;

				// se cierra ya el proceso
				break;
			
			// si no es descendiente se continúa...
			} else {

				// se busca recursivamente hacia arriba del árbol
				$esDescendiente = $this->buscarDescendiente(
					$arrIdsParrafo, $idParrafoPadre, $idParrafoHijo);

			}
		}

		return $esDescendiente;

	}



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	
	public function asignarProfundidades($guion) {

		// se buscan los párrafos del guión tal cual
		$arrParrafosBruto = $guion->buscarParrafos();

		// si el guión no tiene párrafos se devuelve el error
		if (!count($arrParrafosBruto)) throw new Exception('El guión no contiene párrafos');

		// se formatean los índices del array de párrafos
		$arrParrafos = $this->formatearIndicesArrayParrafos($arrParrafosBruto);

		// se crea el array de profundidades iniciales
		$this->crearArrayProfundidadesIniciales($arrParrafos);

		// se inicializan las profundidades a cero del array de párrafos
		$arrParrafos = $this->inicializarProfundidades($arrParrafos);

		// se buscan los párrafos hijo
		$arrParrafosHijo = $guion->buscarParrafosHijo();

		// se crea el array de ids de párrafos hijo por cada uno de los parrafos padre
		$arrIdsHijo = $this->crearArrayHijos($arrParrafosHijo);

		// se asigna a cada párrafo el array de párrafos hijo
		$arrParrafos = $this->asignarHijos($arrParrafos, $arrIdsHijo);

		// se crea el primer párrafo del guión
		$parrafoInicial = new Parrafo($guion->getIdParrafoIni());

		// se obtiene el array de párrafos hijo del primer párrafo de guión extraído del array
		$arrParrafosHijoParrafoIni = $arrParrafos[$guion->getIdParrafoIni()]->getParrafosHijo();

		// se le asigna al párrafo inicial sus párrafos hijo
		$parrafoInicial->setParrafosHijo($arrParrafosHijoParrafoIni);

		// se inicializa la profundidad del párrafo inicial
		$parrafoInicial->setProfundidad(0);

		// se libera el array de párrafos
		unset($arrParrafos);

		// se actualiza la profunidad del árbol de párrafos
		$this->asignarProfundidad($parrafoInicial, 1);

		// se asigna la profundidad al guión
		$guion->actualizarProfundidad($this->intProfundidadGuion);

		// se crea el array de profundidades
		$this->crearArrayProfundidadesFinales($parrafoInicial);

		// se optimiza el array de profundidaes de manera que sólo se actualizarán los párrafos
		// cuyas profundidades hayan cambiado.
		$this->optimizarProfundidades();

		// se solicita la actualización múltiple de las profundidades de párrafo que corresponda
		$guion->actualizarParrafos($this->arrProfundidadesFinal);

	}


	// método que comprueba si parrafoHijo es descendiente de parrafo
	public function esDescendiente($parrafo, $parrafoHijo, $guion) {

		// se buscan los párrafos del guión tal cual
		$arrParrafosBruto = $guion->buscarParrafos();

		// si el guión no tiene párrafos se devuelve el error
		if (!count($arrParrafosBruto)) throw new Exception('El guión no contiene párrafos');

		// se formatean los índices del array de párrafos
		$arrParrafos = $this->formatearIndicesArrayParrafos($arrParrafosBruto);

		// se buscan los párrafos hijo
		$arrParrafosHijo = $guion->buscarParrafosHijo();

		// se crea el array de ids de párrafos hijo por cada uno de los parrafos padre
		$arrIdsPadre = $this->crearArrayPadres($arrParrafosHijo);

		// se recorre el array de ids padre recursivamente para comprobar si parrafo es descendiente
		// de párrafo hijo
		$esDescendiente = $this->buscarDescendiente(
			$arrIdsPadre, $parrafo->getId(), $parrafoHijo->getId());

		// se devuelve el resultado de la comprobación
		return $esDescendiente;

	}

	

}

?>