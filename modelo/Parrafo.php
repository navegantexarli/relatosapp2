<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Párrafo

 * ***************************************************************************************** */

class Parrafo extends Modelo  {

	private $id;
	private $nivel;
	private $operaciones;
	private $texto;
	private $marcado;
	private $profundidad;
	private $idGuion;

	private $arrParrafosHijo = array();


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct() {
		parent::__construct();

		switch (func_num_args()) { 
			case 1: { $this->cargar(func_get_arg(0)); } // si hay un parámetro en el constructor es el id
		}
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function cargar($id) {

		// cadena sql para realizar la carga
		$sql = "select * from parrafo where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->idGuion = $arrCampos['id_guion'];
		$this->nivel = $arrCampos['nivel'];
		$this->operaciones = $arrCampos['operaciones'];
		$this->texto = $arrCampos['texto'];
		$this->marcado = $arrCampos['marcado'];
		$this->profundidad = $arrCampos['profundidad'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Parrafo::cargar: Párrafo no existe');
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function actualizar($nivel, $operaciones, $texto, $marcado, $profundidad) {

		// si no está cargada la párrafo, no se puede actualizar
		if (!$this->id) throw new Exception('Parrafo::actualizar: Párrafo no cargado');

		// cadena sql para realizar la carga
		$sql = "update parrafo set ".
			"nivel = '".$nivel."', ".
			"operaciones = '".$operaciones."', ".
			"texto = '".$texto."', ".
			"marcado = '".$marcado."', ".
			"profundidad = '".$profundidad."' ".
			"where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function actualizarProfundidad($profundidad) {

				// si no está cargada la párrafo, no se puede actualizar
		if (!$this->id) throw new Exception('Parrafo::actualizar: Párrafo no cargado');

		// cadena sql para realizar la actualización
		$sql = "update parrafo set profundidad = '".$profundidad."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function eliminar() {

		// si no está cargado el párrafo, no se puede eliminar
		if (!$this->id) throw new Exception('Parrafo::eliminar: Parrafo no cargado');

		// cadena sql para realizar la carga
		$sql = "delete from parrafo where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function buscarParrafosPadre() {

		// se inicializa el array de parrafos
		$this->arrParrafos = array();

		// se buscan los párrafos padre del párrafo actual
		$sql = " select * from vista_parrafos_padre where id_parrafo_hijo = ".$this->id;

		// aplicamos la ordenación
		$sql .= " Order by id_parrafo_padre asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$p = new Parrafo();
			$p->setId($arrRegistro['id_parrafo_padre']);
			$p->setTexto($arrRegistro['texto']);

			// introduciendo párrafo en el array de parrafos del guión
			$this->arrParrafos[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrParrafos;		
	}


	public function buscarParrafosHijo($ordenacion = 'id_parrafo_hijo') {

		// se inicializa el array de parrafos
		$this->arrParrafos = array();

		// se buscan los párrafos padre del párrafo actual
		$sql = " select * from vista_parrafos_hijo where id_parrafo_padre = ".$this->id;

		// se configura y se aplica la ordenación de la consulta
		if ($ordenacion == 'nivel') $sql .= " Order by nivel desc ";
		else $sql .= " Order by id_parrafo_hijo asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$p = new Parrafo();
			$p->setId($arrRegistro['id_parrafo_hijo']);
			$p->setNivel($arrRegistro['nivel']);
			$p->setOperaciones($arrRegistro['operaciones']);
			$p->setTexto($arrRegistro['texto']);
			$p->setMarcado($arrRegistro['marcado']);
			$p->setProfundidad($arrRegistro['profundidad']);

			// introduciendo párrafo en el array de parrafos del guión
			$this->arrParrafos[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrParrafos;		
	}


	public function asignarParrafoHijo($parrafoHijo) {

		// cadena sql para realizar la asignación
		$sql = "insert into parrafo_hijos (id_parrafo_padre, id_parrafo_hijo, id_guion) values ".
			"(".$this->id.", ".$parrafoHijo->getId().", ".$this->idGuion.") ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function desasignarParrafoHijo($parrafoHijo) {

		// cadena sql para realizar la asignación
		$sql = "delete from parrafo_hijos where ".	
			" id_parrafo_padre = ".$this->id." and ".
			" id_parrafo_hijo = ".$parrafoHijo->getId();

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	



	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getNivel() {
		return $this->nivel;
	}

	public function getOperaciones() {
		return $this->operaciones;
	}

	public function getTexto() {
		return $this->texto;
	}

	public function getMarcado() {
		return $this->marcado;
	}

	public function getProfundidad() {
		return $this->profundidad;
	}

	public function getIdGuion() {
		return $this->idGuion;
	}

	public function getParrafosHijo() {
		return $this->arrParrafosHijo;
	}




	public function setId($id) {
		$this->id = $id;
	}

	public function setNivel($nivel) {
		$this->nivel = $nivel;
	}	

	public function setOperaciones($operaciones) {
		$this->operaciones = $operaciones;
	}

	public function setTexto($texto) {
		$this->texto = $texto;
	}

	public function setMarcado($marcado) {
		$this->marcado = $marcado;
	}

	public function setProfundidad($profundidad) {
		$this->profundidad = $profundidad;
	}

	public function setIdGuion($idGuion) {
		$this->idGuion = $idGuion;
	}

	public function setParrafosHijo($arrParrafosHijo) {
		$this->arrParrafosHijo = $arrParrafosHijo;
	}

}

?>