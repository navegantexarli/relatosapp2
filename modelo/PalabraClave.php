<?php

include_once ("Modelo.php");
include_once ("PalabraClaveValor.php");

/* *******************************************************************************************
 
 * CLASE Párrafo

 * ***************************************************************************************** */

class PalabraClave extends Modelo  {

	private $id;
	private $nombre;

	private $arrValores;


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */

	function __construct() {
		parent::__construct();

		switch (func_num_args()) { 

			// si hay un parámetro en el constructor puede ser el id o el nombre de la palabra clave
			case 1: { 

				// se obtiene el parámetros
				$parametro = func_get_arg(0);

				// se comprueba el tipo del parámetro
				$tipoParametro = gettype($parametro);

				switch ($tipoParametro) {
					
					// en caso de ser entero, será el id
					case 'integer':	$this->cargar($parametro); break;
					
					// en caso de ser cadena, será el nombre
					case 'string':	$this->buscar($parametro); break;
				}
				
			}
		}
	}


	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function cargar($id) {

		// cadena sql para realizar la carga
		$sql = "select * from palabra_clave where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->nombre = $arrCampos['nombre'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('PalabraClave::cargar: Palabra clave no existe');
	}


	private function crearFiltroBuscarValores($nivel, $valor) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si hay algún filtro añadimos el "where" y los filtros correspondientes
		if (!empty($nivel) or (!empty($valor))) {
			$sql = " and (";

			// se crea el filtro en base a los filtros pasados como parámetros
			if (!empty($nivel)) $sql .= " nivel = ".$nivel." or ";
			if (!empty($valor)) $sql .= " valor like '%".$valor."%' or ";

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);
			$sql .= ')';
		}

		// se devuelve el filtro
		return $sql;
	}	
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($nombre) {

		// cadena sql para realizar la carga
		$sql = "insert into palabra_clave (nombre) values ('".$nombre."') Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$id = $row[0];

		return $id;

	}	


	public function actualizar($nombre) {

		// si no está cargada la palabra clave, no se puede actualizar
		if (!$this->id) throw new Exception('PalabraClave::actualizar: Palabra clave no cargado');

		// cadena sql para realizar la carga
		$sql = "update palabra_clave set nombre = '".$nombre."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function eliminar() {

		// si no está cargado el palabra clave, no se puede eliminar
		if (!$this->id) throw new Exception('PalabraClave::eliminar: PalabraClave no cargado');

		// cadena sql para realizar la carga
		$sql = "delete from palabra_clave where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function buscar($nombre) {
		
		// cadena sql para realizar la búsqueda de la palabra clave por nombre
		$sql = " select * from palabra_clave where nombre = '".$nombre."' ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// si no se ha encontrado nada es que no es una palabra clave
		if (pg_num_rows($query) > 0) {

			// obteniendo el registro de la base de datos
			$arrRegistro = pg_fetch_array($query);

			// se crea la palabra clave
			$this->id = $arrRegistro['id'];
			$this->nombre = $arrRegistro['nombre'];

		// si no se ha encontrado nada es que no es una palabra clave
		} else $this->id = null;

	}




	/* Valores de palabra clave *********************************************************** */

	public function insertarValor($nivel, $valor) {

		// cadena sql para realizar la carga
		$sql = "insert into palabra_clave_valor (id_palabra_clave, nivel, valor) values ".
			" (".$this->id.", ".$nivel.", '".$valor."') ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function numValores() {

		// cadena sql para realizar la carga
		$sql = " select count(id_palabra_clave) from palabra_clave_valor ". 
			" where id_palabra_clave = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// se devuelve la cantidad de registros encontrados
		return $arrRegistro[0];

	}


	public function buscarValores($nivel = '', $valor = '') {

		// se inicializa el array de valores de palabra clave
		$this->arrValores = array();

		// cadena sql para realizar la carga
		$sql = "select * from palabra_clave_valor where id_palabra_clave = ".$this->id;

		// se incluye los filtros si los hubiere
		$sql .= $this->crearFiltroBuscarValores($nivel, $valor);

		// aplicamos la ordenación
		$sql .= " Order by nivel asc ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la valor de palabra clave
			$pr = new PalabraClaveValor();
			$pr->setNivel($arrRegistro['nivel']);
			$pr->setValor($arrRegistro['valor']);

			// introduciendo la valor de palabra clave en el array de características del personaje
			$this->arrValores[] = $pr;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrValores;
	}


	public function eliminarValor($valor) {

		// si no está cargado el palabra clave, no se puede eliminar
		if (!$this->id) throw new Exception('PalabraClave::eliminar: PalabraClave no cargado');

		// cadena sql para realizar la eliminación
		$sql = "delete from palabra_clave_valor where ".
			" id_palabra_clave = ".$this->id. " and ".
			" valor = '".$valor."' ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	



	/* Métodos getters y setters ***************************************** */

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