<?php

include_once ("Modelo.php");
include_once ("NodoPersonaje.php");

/* *******************************************************************************************
 
 * CLASE Nodo

 * ***************************************************************************************** */

class Nodo extends Modelo  {

	private $id;
	private $texto;
	private $orden;
	private $idRelato;
	private $idNodoPadre;
	private $idNodoHijo;


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
		$sql = "select * from nodo where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->texto = $arrCampos['texto'];
		$this->orden = $arrCampos['orden'];
		$this->idRelato = $arrCampos['id_relato'];
		$this->idNodoPadre = $arrCampos['id_nodo_padre'];
		$this->idNodoHijo = $arrCampos['id_nodo_hijo'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Nodo::cargar: Nodo no existe');
	}
		

	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($relato, $nodoPadre, $orden) {

		// si nodoPadre no es nulo se inserta
		if ($nodoPadre) {

			// cadena sql para realizar la inserción con nodoPadre
			$sql = "insert into nodo (id_relato, id_nodo_padre, orden) values ("
				.$relato->getId().", ".$nodoPadre->getId().", ".$orden.") Returning id";
		
		} else {

			// cadena sql para realizar la inserción sin nodoPadre
			$sql = "insert into nodo (id_relato, orden) values ("
				.$relato->getId().", ".$orden.") Returning id";

		}

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id
		$row = pg_fetch_row($query);
		$id = $row[0];

		// se cargan los valores del nodo
		$this->id = $id;
		$this->idRelato = $relato->getId();		
		$this->idNodoPadre = ($nodoPadre) ? $nodoPadre->getId() : null;
		$this->orden = $orden;

		return $id;
	}	


	public function buscarPersonajes() {

		// se inicializa el array de personajes
		$arrPersonajes = array();

		// cadena sql para realizar la carga
		$sql = "select distinct on (id_personaje, nombre_largo)
			np.id_nodo, 
			np.id_personaje,
			p.nombre,
			p.nombre_largo,
			p.sexo,
			p.anyo,
			p.numero_imagen,
			np.presente,
			pi.nombre_imagen
   
   			from nodo_personaje np
     		left join personaje p ON np.id_personaje = p.id
     		left join personaje_imagen pi on pi.id_personaje = p.id";

     	// filtramos para que sólo aparezca el nodo buscado 
     	// y los personajes presentes en la escena
     	$sql .= " where np.id_nodo = ".$this->id." and np.presente = 't' ";

     	// ordenamos por nombre de personaje e imagen aleatoria
     	$sql .= " order by nombre_largo, id_personaje, random() ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando el personaje de nodo
			$np = new NodoPersonaje();
			$np->setIdPersonaje($arrRegistro['id_personaje']);
			$np->setNombre($arrRegistro['nombre']);
			$np->setNombreLargo($arrRegistro['nombre_largo']);
			$np->setSexo($arrRegistro['sexo']);
			$np->setAnyo($arrRegistro['anyo']);
			$np->setNumeroImagen($arrRegistro['numero_imagen']);
			$np->setPresente($arrRegistro['presente']);
			$np->setNombreImagen($arrRegistro['nombre_imagen']);

			// introduciendo la caracteristica en el array de características del personaje
			$arrPersonajes[] = $np;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $arrPersonajes;		
	}	


	public function asignarPersonaje($personaje, $strPresente) {

		// cadena sql para realizar la inserción
		$sql = "insert into nodo_personaje (id_nodo, id_personaje, presente) values (".
			$this->id.", ".$personaje->getId().", '".$strPresente."') ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function actualizarTexto($texto) {

		// cadena sql para realizar la actualización
		$sql = "update nodo set texto = '".$texto."' where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);
	}


	public function actualizarNodosHijo($arrActualizaciones) {

		// solo se actualizará si el array de actualizaciones contiene al menos un valor
		if (count($arrActualizaciones) > 0) {		

			// cadena sql para realizar la actualización múltiple
			$sql = "update nodo as n1 set id_nodo_hijo = n2.id_nodo_hijo from (values ";

			// se recorre el array de actualizaciones
			foreach ($arrActualizaciones as $key => $value) {

				// se añade cada actualización
				$sql .= "(".$key.", ".$value."), ";
			}

			// se elimina la última coma (,) de la cadena
			$sql = substr($sql, 0, strlen($sql)-2);

			$sql .= ") as n2(id, id_nodo_hijo) where n2.id = n1.id";

			// ejecución de la consulta
			$query = pg_exec($this->conn, $sql);
		}

	}	



	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getTexto() {
		return $this->texto;
	}

	public function getOrden() {
		return $this->orden;
	}

	public function getIdRelato() {
		return $this->idRelato;
	}

	public function getIdNodoPadre() {
		return $this->idNodoPadre;
	}

	public function getIdNodoHijo() {
		return $this->idNodoHijo;
	}



	public function setId($id) {
		$this->id = $id;
	}

	public function setTexto($texto) {
		$this->texto = $texto;
	}

	public function setOrden($orden) {
		$this->orden = $orden;
	}

	public function setIdRelato($idRelato) {
		$this->idRelato = $idRelato;
	}

}

?>