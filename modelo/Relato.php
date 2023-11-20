<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Relato

 * ***************************************************************************************** */

class Relato extends Modelo  {

	private $id;
	private $titulo;
	private $generado;
	private $idNodoIni;
	private $cantidad_nodos;

	private $idGuion;
	private $tituloGuion;
	private $nodoIni;


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
		$sql = "select * from vista_relatos_guiones where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->titulo = $arrCampos['titulo'];
		$this->generado = $arrCampos['generado'];
		$this->idNodoIni = $arrCampos['id_nodo_ini'];
		$this->idGuion = $arrCampos['id_guion'];
		$this->tituloGuion = $arrCampos['titulo_guion'];
		$this->cantidadNodos = $arrCampos['cantidad_nodos'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Relato::cargar: Relato no existe');
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($titulo) {

		// cadena sql para realizar la carga
		$sql = "insert into relato (titulo) values ('".$titulo."') Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$id = $row[0];

		return $id;

	}	


	public function actualizar($titulo) {

		// si no está cargada la relato, no se puede actualizar
		if (!$this->id) throw new Exception('Relato::actualizar: Relato no cargado');

		// cadena sql para realizar la carga
		$sql = "update relato set titulo = '".$titulo."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function eliminar() {

		// si no está cargado el relato, no se puede eliminar
		if (!$this->id) throw new Exception('Relato::eliminar: Relato no cargado');

		// cadena sql para realizar la carga
		$sql = "delete from relato where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		

	public function asignarGuion($guion) {

		// si no está cargada la relato, no se puede actualizar
		if (!$this->id) throw new Exception('Relato::actualizar: Relato no cargado');

		// cadena sql para realizar la carga
		$sql = "update relato set id_guion = '".$guion->getId()."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function asignarNodoIni($nodo) {

		// si no setá cargado el relato no se puede asginar
		if (!$this->id) throw new Exception('Relato::actualizar: Relato no cargado');

		// cadena sql para realizar la asignación del nodo inicial
		$sql = "update relato set id_nodo_ini = '".$nodo->getId()."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function insertarPersonaje($strEtiqueta, $personaje) {

		// cadena sql para realizar la carga
		$sql = "insert into relato_personaje (id_relato, id_personaje, etiqueta_personaje) values (".
			$this->id.", ".$personaje->getId().", '".$strEtiqueta."')";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function buscarPersonajes() {

		// se inicializa el array de personajes
		$arrPersonajes = array();

		// cadena sql para realizar la búsqueda
		$sql = " select * from vista_relato_personajes where id_relato = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la característica
			$p = new Personaje();
			$p->setId($arrRegistro['id_personaje']);
			$p->setNombre($arrRegistro['nombre']);
			$p->setNombreLargo($arrRegistro['nombre_largo']);
			$p->setSexo($arrRegistro['sexo']);

			// introduciendo la característica en el array de características
			$arrPersonajes[] = $p;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $arrPersonajes;

	}


	public function buscarEtiquetaPersonaje($strEtiqueta) {

		// se inicializa el personaje a null porque puede que no exista
		$personaje = null;

		// cadena sql para realizar la búsqueda
		$sql = " select * from relato_personaje where id_relato = ".$this->id." and ".
			" etiqueta_personaje = '".$strEtiqueta."'";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);
 
		// si se ha encontrado un personaje se carga
		if (($arrCampos)) {

			// se obtiene el id del personaje
			$idPersonaje = $arrCampos['id_personaje'];
			
			// se carga el personaje
			$personaje = new Personaje($idPersonaje);
		}

		// se devuelve el personaje
		return $personaje;
	}


	public function actualizarGenerado($generado) {

		// si no está cargada la relato, no se puede generar
		if (!$this->id) throw new Exception('Relato::actualizarGenerado: Relato no cargado');

		// cadena sql para realizar la actualización
		$sql = "update relato set generado = '".$generado."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function actualizarCantidadNodos($cantidadNodos) {

		// si no está cargada la relato, no se puede generar
		if (!$this->id) throw new Exception('Relato::actualizarGenerado: Relato no cargado');

		// cadena sql para realizar la actualización
		$sql = "update relato set cantidad_nodos = '".$cantidadNodos."' where id = ".$this->id;

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

	public function getGenerado() {
		return $this->generado;
	}

	public function getIdGuion() {
		return $this->idGuion;
	}

	public function getTituloGuion() {
		return $this->tituloGuion;
	}

	public function getIdNodoIni() {
		return $this->idNodoIni;
	}

	public function getCantidadNodos() {
		return $this->cantidadNodos;
	}



	public function setId($id) {
		$this->id = $id;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}	

	public function setGenerado($generado) {
		$this->generado = $generado;
	}

	public function setIdGuion($idGuion) {
		$this->idGuion = $idGuion;
	}

	public function setTituloGuion($tituloGuion) {
		$this->tituloGuion = $tituloGuion;
	}

	public function setIdNodoIni($idNodoIni) {
		$this->idNodoIni = $idNodoIni;
	}

	public function setCantidadNodos($cantidadNodos) {
		$this->cantidadNodos = $cantidadNodos;
	}

}

?>