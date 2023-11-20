<?php

include_once ("Modelo.php");
include_once ("Parrafo.php");
include_once ("ParrafoHijo.php");

/* *******************************************************************************************
 
 * CLASE Guión

 * ***************************************************************************************** */

class Guion extends Modelo  {

	private $id;
	private $titulo;
	private $profundidad;
	private $refrescada;

	private $idParrafoIni;


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
		$sql = "select * from guion where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->titulo = $arrCampos['titulo'];
		$this->profundidad = $arrCampos['profundidad'];
		$this->refrescada = $arrCampos['refrescada'];
		$this->idParrafoIni = $arrCampos['id_parrafo_ini'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Guion::cargar: Guión no existe');
	}


	private function crearFiltroBuscarParrafos($idParrafo, $texto) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si el filtro de idPárrafo no está vacío
		if (!empty($idParrafo)) {

			// si el filtro del texto no está vacío...
			if (!empty($texto)) {
				$sql = " and (id >".$idParrafo." or texto like '%".$texto."%') ";
			
			// si el filtro del texto está vacío...
			} else {
				$sql = " and id >".$idParrafo." ";
			}

		// si el filtro de idParrafo está vacío pero el de texto no...
		} else if (!empty($texto)) {
			$sql = " and texto like '%".$texto."%' ";
		}

		// se devuelve el filtro
		return $sql;		
	}	
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($titulo) {

		// cadena sql para realizar la carga
		$sql = "insert into guion (titulo) values ('".$titulo."') Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$id = $row[0];

		// se actualiza el id del guion
		$this->id = $id;

		// se devuelve el id del guión
		return $id;

	}	


	public function actualizar($titulo) {

		// si no está cargada la guión, no se puede actualizar
		if (!$this->id) throw new Exception('Guion::actualizar: Guión no cargado');

		// cadena sql para realizar la carga
		$sql = "update guion set titulo = '".$titulo."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function eliminar() {

		// si no está cargado el guión, no se puede actualizar
		if (!$this->id) throw new Exception('Guion::actualizar: Guion no cargado');

		// cadena sql para realizar la carga
		$sql = "delete from guion where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function insertarParrafo($nivel) {

		// cadena sql para realizar la carga
		$sql = "insert into parrafo (nivel, id_guion) values (".$nivel.", ".$this->id.") Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id del párrafo
		$row = pg_fetch_row($query);
		$id = $row[0];

		return $id;

	}	


	public function buscarParrafosMarcados($idParrafo = '', $texto = '') {

		// se inicializa el array de parrafos
		$this->arrParrafos = array();

		// cadena sql para realizar la carga
		$sql = "select * from parrafo where id_guion = ".$this->id." and marcado = 't' ";

		// se incluye los filtros si los hubiere
		$sql .= $this->crearFiltroBuscarParrafos($idParrafo, $texto);

		// aplicamos la ordenación
		$sql .= " Order by id desc ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$p = new Parrafo();
			$p->setId($arrRegistro['id']);
			$p->setOperaciones($arrRegistro['operaciones']);
			$p->setTexto($arrRegistro['texto']);
			$p->setNivel($arrRegistro['nivel']);
			$p->setMarcado($arrRegistro['marcado']);
			$p->setProfundidad($arrRegistro['profundidad']);

			// introduciendo párrafo en el array de parrafos del guión
			$this->arrParrafos[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrParrafos;		
	}


	public function cargarParrafoIni() {

		// cadena sql para realizar la carga
		$sql = "select * from parrafo where id = ".$this->idParrafoIni;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// creando el Párrafo
		$p = new Parrafo();
		$p->setId($arrRegistro['id']);
		$p->setOperaciones($arrRegistro['operaciones']);
		$p->setTexto($arrRegistro['texto']);
		$p->setNivel($arrRegistro['nivel']);
		$p->setMarcado($arrRegistro['marcado']);
		$p->setProfundidad($arrRegistro['profundidad']);

		return $p;		
	}


	public function buscarParrafos() {

		// se inicializa el array de parrafos
		$this->arrParrafos = array();

		// cadena sql para realizar la carga
		$sql = "select * from parrafo where id_guion = ".$this->id;

		// aplicamos la ordenación
		$sql .= " Order by id asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$p = new Parrafo();
			$p->setId($arrRegistro['id']);
			$p->setProfundidad($arrRegistro['profundidad']);

			// introduciendo párrafo en el array de parrafos del guión
			$this->arrParrafos[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrParrafos;		
	}	


	public function existeParrafo($parrafo) {

		// cadena sql para realizar la búsqueda del párrafo
		$sql = "select * from parrafo where id_guion = ".$this->id." AND id = ".$parrafo->getId();
		
		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$existeParrafo = pg_num_rows($query);

		// se devuelve el resultado
		return $existeParrafo;			
	}


	public function cantidadParrafos() {

		// cadena sql para realizar la carga
		$sql = "select count(id_guion) from parrafo where id_guion = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// se devuelve la cantidad de registros encontrados
		return $arrRegistro[0];
	}


	public function buscarParrafosHijo() {

		// se inicializa el array de parrafos
		$arrParrafosHijo = array();

		// cadena sql para realizar la carga
		$sql = "select * from parrafo_hijos where id_guion = ".$this->id;

		// aplicamos la ordenación
		$sql .= " Order by id_parrafo_padre asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$ph = new ParrafoHijo();
			$ph->setIdParrafoPadre($arrRegistro['id_parrafo_padre']);
			$ph->setIdParrafoHijo($arrRegistro['id_parrafo_hijo']);

			// introduciendo párrafo en el array de parrafos del guión
			$this->arrParrafosHijo[] = $ph;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrParrafosHijo;		
	}


	public function asignarParrafoInicial($parrafo) {

		// si no está cargada la guión, no se puede actualizar
		if (!$this->id) throw new Exception('Guion::actualizar: Guión no cargado');

		// cadena sql para realizar la asignación del párrafo inicial al guión
		$sql = "update guion set id_parrafo_ini = ".$parrafo->getId()." where id = ".$this->id;
  
		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function actualizarProfundidad($profundidad) {

		// si no está cargada la guión, no se puede actualizar
		if (!$this->id) throw new Exception('Guion::actualizar: Guión no cargado');

		// cadena sql para realizar la carga
		$sql = "update guion set profundidad = ".$profundidad.", refrescada = 't' ".
			" where id = ".$this->id;
  
  		// se actualiza el guion con la nueva profundidad y refresco
		$this->profundidad = $profundidad;
		$this->refrescada = 't';

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function actualizarParrafos($arrActualizaciones) {

		// si no está cargada la guión, no se puede actualizar
		if (!$this->id) throw new Exception('Guion::actualizar: Guión no cargado');

		// si no el array de actualizaciones está vacío es porque la profundidad ya está actualizada
		if (count($arrActualizaciones) == 0) 
			throw new Exception('La profundidad del guión ya estaba actualizada');

		// cadena sql para realizar la actualización múltiple
		$sql = "update parrafo as p set profundidad = c.profundidad from (values ";

		// se recorre el array de actualizaciones
		foreach ($arrActualizaciones as $key => $value) {

			// se añade cada actualización
			$sql .= "(".$key.", ".$value."), ";
		}

		// se elimina la última coma (,) de la cadena
		$sql = substr($sql, 0, strlen($sql)-2);

		$sql .= ") as c(id, profundidad) where c.id = p.id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getTitulo() {
		return $this->titulo;
	}

	public function getProfundidad() {
		return $this->profundidad;
	}

	public function getRefrescada() {
		return $this->refrescada;
	}

	public function getIdParrafoIni() {
		return $this->idParrafoIni;
	}



	public function setId($id) {
		$this->id = $id;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}	

	public function setProfundidad($profundidad) {
		$this->profundidad = $profundidad;
	}

	public function setRefrescada($refrescada) {
		$this->refrescada = $refrescada;
	}

	public function setIdParrafoIni($idParrafoIni) {
		$this->idParrafoIni = $idParrafoIni;
	}
}

?>