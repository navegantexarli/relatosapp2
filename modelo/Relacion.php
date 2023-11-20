<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Relación

 * ***************************************************************************************** */

class Relacion extends Modelo  {

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
		$sql = "select id, nombre from relacion where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->nombre = $arrCampos['nombre'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Relacion::cargar: Relación no existe');
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($nombre) {

		// cadena sql para realizar la carga
		$sql = "insert into relacion (nombre) values ('".$nombre."') Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$id = $row[0];

		return $id;

	}	


	public function actualizar($nombre) {

		// si no está cargada la relación, no se puede actualizar
		if (!$this->id) throw new Exception('Relacion::actualizar: Relación no cargada');

		// cadena sql para realizar la carga
		$sql = "update relacion set nombre = '".$nombre."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function eliminar() {

		// si no está cargada la característica, no se puede actualizar
		if (!$this->id) throw new Exception('Relacion::actualizar: Relacion no cargada');

		// cadena sql para realizar la carga
		$sql = "delete from relacion where id = ".$this->id;

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