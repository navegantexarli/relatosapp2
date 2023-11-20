<?php

include_once ("Modelo.php");

/* *******************************************************************************************
 
 * CLASE Instrucción

 * ***************************************************************************************** */

class Instruccion extends Modelo  {

	private $id;
	private $operacion;
	private $descripcion;


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
		$sql = "select * from instruccion where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->operacion = $arrCampos['operacion'];
		$this->descripcion = $arrCampos['descripcion'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Instruccion::cargar: Instrucción no existe');
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	// [getPersonaje:x1:altura>4#avaricia<2]  
	// [getPersonaje:x3:emparejada>3]
	public function buscarPersonajeCaracteristicas($booSexo, $arrValores, $arrPersonajes) {

		// se inicializa el personaje a null
		$personaje = null;

		// se inicializa la sql para obtener el personaje
		$sql = ' select id_personaje from ( ';

		// se recorre el array de filtros para ir creando la sql
		foreach ($arrValores as $key => $value) {
			
			// se va añadiendo cada filtro
			$sql .= " select id_personaje from vista_personaje_caracteristicas where (".
				"sexo = '".$booSexo."' and nivel ".
				$value[2]." ".$value[1]." ".
				" and nombre_caracteristica like '%".$value[0]."%') intersect ";
		}

		// se elimina la última partícula de insertacción (intersect) de la cadena
		$sql = substr($sql, 0, strlen($sql)-10);

		// se añade el resto de la consulta
		$sql .= ") as q ";

		// si hay personajes ya asignados al relato se añaden al filtro
		if ($arrPersonajes) {

			$sql .= " where ";

			// se recorre el array de personajes que ya están asignados y por lo tanto no se pueden 
			// volver a asignar a otras etiquetas
			foreach ($arrPersonajes as $value) {
				
				// se va añadiendo cada personaje en el filtro
				$sql .= " id_personaje <> ".$value->getId()." and ";
			}

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);

		}

		// se añade la ordenación aleatoria y el límite
		$sql .= " order by random() limit 1";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// si se ha encontrado un personaje se crea
		if ($arrRegistro) $personaje = new personaje($arrRegistro['id_personaje']);

		// se devuelve el personaje
		return $personaje;

	}


	public function buscarPersonajesRelacion($strRelacion, $arrSexo, $arrPersonajes) {

		// se inicializa el array de personajes encontrados
		$arrPersonajesEncontrados = array();

		// se inicializa la sql para obtener el personaje
		$sql = " select * from vista_personaje_relaciones where ".
			" sexo1 = '".$arrSexo[0]."' and sexo2 = '".$arrSexo[1]."' and ".
			" nombre_relacion like '%".$strRelacion."%'";

		// si hay personajes ya asignados al relato se añaden al filtro
		if (count($arrPersonajes) > 0) {

			// se añade la partícula copulativa
			$sql .= " and ";

			// se recorre el array de personajes que ya están asignados y por lo tanto no se pueden 
			// volver a asignar a otras etiquetas
			foreach ($arrPersonajes as $value) {
				
				// se va añadiendo cada personaje en el filtro
				$sql .= " id_personaje1 <> ".$value->getId()." and ";
			}

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);

		}

		// se añade la ordenación aleatoria y el límite
		$sql .= " order by random() limit 1";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// si se ha encontrado una coincidencia se crean los 2 personajes
		if ($arrRegistro) {

			// se recoge el id del personaje1 y 2
			$idPersonaje1 = $arrRegistro['id_personaje1'];
			$idPersonaje2 = $arrRegistro['id_personaje2'];

			// creando personaje1 y 2
			$personaje1 = new Personaje($idPersonaje1);
			$personaje2 = new Personaje($idPersonaje2);

			// introduciendo el personaje en el array de personajes
			$arrPersonajesEncontrados[] = $personaje1;
			$arrPersonajesEncontrados[] = $personaje2;
		}

		// se devuelve el array de personajes
		return $arrPersonajesEncontrados;
	}


	public function buscarPersonaje1Relacion($strRelacion, $personaje2, $booSexo, $arrPersonajes){

		// se inicializa el personaje
		$personaje1 = null;

		// se inicializa la sql para obtener el personaje
		$sql = " select * from vista_personaje_relaciones where ".
			" nombre_relacion like '%".$strRelacion."%' and ".
			" sexo1 = '".$booSexo."' and ".			
			" id_personaje2 = ".$personaje2->getId();

		// si hay personajes ya asignados al relato se añaden al filtro
		if ($arrPersonajes) {

			// se añade la partícula copulativa
			$sql .= " and ";

			// se recorre el array de personajes que ya están asignados y por lo tanto no se pueden 
			// volver a asignar a otras etiquetas
			foreach ($arrPersonajes as $value) {
				
				// se va añadiendo cada personaje en el filtro
				$sql .= " id_personaje1 <> ".$value->getId()." and ";
			}

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);

		}

		// se añade la ordenación aleatoria y el límite
		$sql .= " order by random() limit 1";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// si se ha encontrado una coincidencia se crea el personaje
		if ($arrRegistro) {

			// se recoge el id del personaje2
			$idPersonaje1 = $arrRegistro['id_personaje1'];

			// creando personaje2
			$personaje1 = new Personaje($idPersonaje1);

		}

		// se devuelve el array de personajes
		return $personaje1;
	}		


	public function buscarPersonaje2Relacion($strRelacion, $personaje1, $booSexo, $arrPersonajes){

		// se inicializa el personaje
		$personaje2 = null;

		// se inicializa la sql para obtener el personaje
		$sql = " select * from vista_personaje_relaciones where ".
			" nombre_relacion like '%".$strRelacion."%' and ".
			" sexo2 = '".$booSexo."' and ".
			" id_personaje1 = ".$personaje1->getId();

		// si hay personajes ya asignados al relato se añaden al filtro
		if ($arrPersonajes) {

			// se añade la partícula copulativa
			$sql .= " and ";

			// se recorre el array de personajes que ya están asignados y por lo tanto no se pueden 
			// volver a asignar a otras etiquetas
			foreach ($arrPersonajes as $value) {
				
				// se va añadiendo cada personaje en el filtro
				$sql .= " id_personaje2 <> ".$value->getId()." and ";
			}

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);

		}			

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// si se ha encontrado una coincidencia se crea el personaje2
		if ($arrRegistro) {

			// se recoge el id del personaje2
			$idPersonaje2 = $arrRegistro['id_personaje2'];

			// creando personaje2
			$personaje2 = new Personaje($idPersonaje2);

		}

		// se devuelve el array de personajes
		return $personaje2;
	}			


	public function buscarPersonaje($booSexo, $arrPersonajes) {

		// se inicializa el personaje
		$personaje = null;

		// se inicializa la sql para buscar el personaje
		$sql = " select * from personaje where sexo = '".$booSexo."' ";

		// si hay personajes ya asignados al relato se añaden al filtro
		if ($arrPersonajes) {

			$sql .= " and ";

			// se recorre el array de personajes que ya están asignados y por lo tanto no se pueden 
			// volver a asignar a otras etiquetas
			foreach ($arrPersonajes as $value) {
				
				// se va añadiendo cada personaje en el filtro
				$sql .= " id <> ".$value->getId()." and ";
			}

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);

		}

		// se añade la ordenación aleatoria y el límite
		$sql .= " order by random() limit 1";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// si se ha encontrado una coincidencia se crea el personaje
		if ($arrRegistro) {

			// se recoge el id del personaje1
			$idPersonaje = $arrRegistro['id'];

			// creando personaje
			$personaje = new Personaje($idPersonaje);

		}

		// se devuelve el array de personajes
		return $personaje;

	}



	/* Métodos getters y setters *************************************************************** */

	public function getId() {
		return $this->id;
	}

	public function getOperacion() {
		return $this->operacion;
	}

	public function getDescripcion() {
		return $this->descripcion;
	}


	public function setId($id) {
		$this->id = $id;
	}

	public function setOperacion($operacion) {
		$this->operacion = $operacion;
	}	

	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

}

?>