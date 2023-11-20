<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Caracteristica

 * ***************************************************************************************** */

class Caracteristica extends Modelo  {

	private $id;
	private $nombre;


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
		$sql = "select id, nombre from caracteristica where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);
		$this->id = $arrCampos['id'];
		$this->nombre = $arrCampos['nombre'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Caracteristica::cargar: Caracteristica no existe');		

	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($nombre) {

		// cadena sql para realizar la carga
		$sql = "insert into caracteristica (nombre) values ('".$nombre."') Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$this->id = $row[0];

		// cargando el resto de atributos
		$this->nombre = $nombre;

		return $this->id;

	}	


	public function actualizar($nombre) {

		// si no está cargada la característica, no se puede actualizar
		if (!$this->id) throw new Exception('Caracteristica::actualizar: Caracteristica no cargada');

		// cadena sql para realizar la carga
		$sql = "update caracteristica set nombre = '".$nombre."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function eliminar() {

		// si no está cargada la característica, no se puede actualizar
		if (!$this->id) throw new Exception('Caracteristica::actualizar: Caracteristica no cargada');

		// cadena sql para realizar la carga
		$sql = "delete from caracteristica where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function insertarPersonajes($arrPersonajes, $nivelDefecto) {

		// definición de la cadena de inserción
		$sql = " insert into personaje_caracteristica (id_personaje, id_caracteristica, nivel) values ";

		// se recorre el array de personajes para añadir a la inserción múltiple
		foreach ($arrPersonajes as $p) {
			$sql .= "(".$p->getId().", ".$this->id.", ".$nivelDefecto."), ";
		}

		// se elimina la última coma (,) de la cadena
		$sql = substr($sql, 0, strlen($sql)-2);

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);
	}



	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}	

}

?>