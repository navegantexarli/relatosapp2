<?php

include_once ("Modelo.php");
include_once ("Caracteristica.php");
include_once ("Personaje.php");
include_once ("Relacion.php");
include_once ("Guion.php");
include_once ("Relato.php");
include_once ("PalabraClave.php");
include_once ("Instruccion.php");



/* *******************************************************************************************
 * CLASE Buscador
 * ***************************************************************************************** */

class Buscador extends Modelo  {

	private $arrCaracteristicas; // array de características
	private $arrPersonajes; // array de personajes
	private $arrRelaciones; // array de relaciones
	private $arrGuiones; // array de guiones
	private $arrRelatos; // array de guiones
	private $arrPalabrasClave; // array de guiones
	private $arrInstrucciones; // array de guiones



	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct() {

		parent::__construct();

	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function crearFiltroBuscarPersonajes($nombre, $nombre_largo, $sexo, $anyo) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si hay algún filtro añadimos el "where" y los filtros correspondientes
		if (!empty($nombre) or (!empty($nombre_largo)) or (!empty($sexo)) or (!empty($anyo))) {
			$sql .= " where ";
		
			// se crea el filtro en base a los filtros pasados como parámetros
			if (!empty($nombre)) $sql .= " nombre like '%".$nombre."%' or ";
			if (!empty($nombre_largo)) $sql .= " nombre_largo like '%".$nombre_largo."%' or ";
			if (!empty($sexo)) $sql .= " sexo = '".$sexo."' or ";
			if (!empty($anyo)) $sql .= " anyo < '".$anyo."' or ";

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);
		}

		// se devuelve el filtro
		return $sql;
	}



	private function crearFiltroBuscarInstruciones($operacion, $descripcion) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si el filtro de operacion no está vacío
		if (!empty($operacion)) {

			// si el filtro descripción no está vacío...
			if (!empty($descripcion)) {
				$sql = " where operacion like '%".$operacion."%' or descripcion like '%".$descripcion."%' ";
			
			// si el filtro del descripción está vacío...
			} else {
				$sql = " where operacion like '%".$operacion."%' ";
			}

		// si el filtro de operacion está vacío pero descripción no...
		} else if (!empty($descripcion)) {
			$sql = " where descripcion like '%".$descripcion."%' ";
		}

		// se devuelve el filtro
		return $sql;		
	}	



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function numRegistros($tabla, $arrId = null) {

		// cadena sql para realizar la búsqueda de todos los registros que cumplan la condición
		$sql = ($arrId) ? 
			"select count(".$arrId[0].") from ".$tabla." where ".$arrId[0]." = ".$arrId[1] : 
			"select count(id) from ".$tabla;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// se devuelve la cantidad de registros encontrados
		return $arrRegistro[0];

	}	


	public function buscarCaracteristicas($nombre = '') {

		// se inicializa el array de características
		$this->arrCaracteristicas = array();

		// cadena sql para realizar la carga
		$sql = "select * from caracteristica where nombre like '%".$nombre."%' ".
			" Order by nombre ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la característica
			$c = new Caracteristica();
			$c->setId($arrRegistro['id']);
			$c->setNombre($arrRegistro['nombre']);

			// introduciendo la característica en el array de características
			$this->arrCaracteristicas[] = $c;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrCaracteristicas;

	}	


	public function buscarPersonajes($nombre = '', $nombre_largo = '', $sexo = '', $anyo = '') {

		// se inicializa el array de personajes
		$this->arrPersonajes = array();

		// se inicia la cadena sql para realizar la búsqueda
		$sql = " select * from personaje ";

		// se incluye los filtros si los hubiere
		$sql .= $this->crearFiltroBuscarPersonajes($nombre, $nombre_largo, $sexo, $anyo);

		// aplicamos la ordenación
		$sql .= " Order by nombre_largo asc ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando el personaje
			$p = new Personaje();
			$p->setId($arrRegistro['id']);
			$p->setNombre($arrRegistro['nombre']);
			$p->setNombreLargo($arrRegistro['nombre_largo']);
			$p->setSexo($arrRegistro['sexo']);
			$p->setAnyo($arrRegistro['anyo']);
			$p->setEdad();

			// introduciendo la característica en el array de características
			$this->arrPersonajes[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrPersonajes;

	}	


	public function buscarRelaciones($nombre = '') {

		// se inicializa el array de relaciones
		$this->arrRelaciones = array();

		// cadena sql para realizar la carga
		$sql = "select * from relacion where nombre like '%".$nombre."%' ".
			" Order by nombre limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relación
			$r = new Relacion();
			$r->setId($arrRegistro['id']);
			$r->setNombre($arrRegistro['nombre']);

			// introduciendo la relación en el array de relaciones
			$this->arrRelaciones[] = $r;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrRelaciones;

	}	


	public function buscarGuiones($titulo = '') {

		// se inicializa el array de guiones
		$this->arrGuiones = array();

		// cadena sql para realizar la carga
		$sql = "select * from guion where titulo like '%".$titulo."%' ".
			" Order by titulo asc limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la guion
			$g = new Guion();
			$g->setId($arrRegistro['id']);
			$g->setIdParrafoIni($arrRegistro['id_parrafo_ini']);
			$g->setTitulo($arrRegistro['titulo']);
			$g->setProfundidad($arrRegistro['profundidad']);
			$g->setRefrescada($arrRegistro['refrescada']);

			// introduciendo el guion en el array de guiones
			$this->arrGuiones[] = $g;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrGuiones;

	}		


	public function buscarRelatos($titulo = '') {

		// se inicializa el array de relatos
		$this->arrRelatos = array();

		// cadena sql para realizar la carga
		$sql = "select * from vista_relatos_guiones where titulo like '%".$titulo."%' ".
			" Order by titulo asc limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relación
			$r = new Relato();
			$r->setId($arrRegistro['id']);
			$r->setTitulo($arrRegistro['titulo']);
			$r->setGenerado($arrRegistro['generado']);
			$r->setIdGuion($arrRegistro['id_guion']);
			$r->setTituloGuion($arrRegistro['titulo_guion']);

			// introduciendo la relato en el array de relatos
			$this->arrRelatos[] = $r;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrRelatos;

	}		


	public function buscarPalabrasClave($nombre = '') {

		// se inicializa el array de palabras clave
		$this->arrPalabrasClave = array();

		// cadena sql para realizar la carga
		$sql = "select * from palabra_clave where nombre like '%".$nombre."%' ".
			" Order by nombre limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relación
			$r = new PalabraClave();
			$r->setId($arrRegistro['id']);
			$r->setNombre($arrRegistro['nombre']);

			// introduciendo la palabra clave en el array de palabras clave
			$this->arrPalabrasClave[] = $r;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrPalabrasClave;

	}	


	public function buscarInstrucciones($operacion = '', $descripcion = '') {

		// se inicializa el array de instruccionese
		$this->arrInstrucciones = array();

		// cadena sql para realizar la carga
		$sql = "select * from instruccion ";

		// se incluye los filtros si los hubiere
		$sql .= $this->crearFiltroBuscarInstruciones($operacion, $descripcion);

		// se aplica la ordenación
		$sql .= " Order by descripcion desc ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relación
			$i = new Instruccion();
			$i->setId($arrRegistro['id']);
			$i->setOperacion($arrRegistro['operacion']);
			$i->setDescripcion($arrRegistro['descripcion']);

			// introduciendo la palabra clave en el array de palabras clave
			$this->arrInstrucciones[] = $i;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrInstrucciones;

	}	



	/* Métodos getters y setters */

	public function getCaracteristicas() {
		return $this->arrCaracteristicas;
	}

	public function getPersonajes() {
		return $this->arrPersonajes;
	}

	public function getRelaciones() {
		return $this->arrRelaciones;
	}

	public function getGuiones() {
		return $this->arrGuiones;
	}

	public function getRelatos() {
		return $this->arrRelatos;
	}

	public function getPalabrasClave() {
		return $this->arrRelatos;
	}

	public function getInstrucciones() {
		return $this->arrInstrucciones;
	}



	public function setCaracteristicas($arrCaracteristicas) {
		$this->arrCaracteristicas = $arrCaracteristicas;
	}

	public function setPersonajes($arrPersonajes) {
		$this->arrPersonajes = $arrPersonajes;
	}

	public function setRelaciones($arrRelaciones) {
		$this->arrRelaciones = $arrRelaciones;
	}

	public function setGuiones($arrGuiones) {
		$this->arrGuiones = $arrGuiones;
	}

	public function setRelatos($arrRelatos) {
		$this->arrRelatos = $arrRelatos;
	}

	public function setPalabrasClave($arrPalabrasClave) {
		$this->arrPalabrasClave = $arrPalabrasClave;
	}

	public function setInstruciones($arrInstrucciones) {
		$this->arrInstrucciones = $arrInstrucciones;
	}

}

?>