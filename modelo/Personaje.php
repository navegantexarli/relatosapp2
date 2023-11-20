<?php

include_once ("Modelo.php");
include_once ("PersonajeCaracteristica.php");
include_once ("PersonajeRelacion.php");
include_once ("PersonajeImagen.php");

/* *******************************************************************************************
 
 * CLASE Personaje

 * ***************************************************************************************** */

class Personaje extends Modelo  {

	private $id;
	private $nombre;
	private $nombre_largo;
	private $sexo;
	private $anyo;
	private $edad;
	private $numero_imagen;

	private $arrCaracteristicas;
	private $arrRelaciones;
	private $arrImagenes;



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
		$sql = "select * from personaje where id=".$id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo los valores
		$arrCampos = pg_fetch_Array($query);

		$this->id = $arrCampos['id'];
		$this->nombre = $arrCampos['nombre'];
		$this->nombre_largo = $arrCampos['nombre_largo'];
		$this->sexo = $arrCampos['sexo'];
		$this->anyo = $arrCampos['anyo'];
		$this->edad = $this->calcularEdad();
		$this->numero_imagen = $arrCampos['numero_imagen'];

		// si no se ha podido cargar es que no existe
		if (!$this->id) throw new Exception('Personaje::cargar: Personaje no existe');
	}


	private function calcularEdad() {

		// se obtiene el año de nacimiento
		$anyo = new DateTime($this->anyo);

		// se instancia la fecha actual
		//$hoy = date('Y-m-d');
		$hoy = new DateTime();

		// se obtiene la diferencia entre fechas
		$diferencia = $anyo->diff($hoy);

		// se obtiene la edad
		$edad = $diferencia->y;

		// si no tiene edad se indica a null
		$this->edad = ($edad) ? $edad : '';

	}


	private function crearFiltroBuscarRelaciones($nombreLargo, $nombreRelacion) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si hay algún filtro añadimos el "where" y los filtros correspondientes
		if (!empty($nombreLargo) or (!empty($nombreRelacion))) {
			$sql = " and (";

			// se crea el filtro en base a los filtros pasados como parámetros
			if (!empty($nombreLargo)) $sql .= " nombre_largo like '%".$nombreLargo."%' or ";
			if (!empty($nombreRelacion)) $sql .= " nombre_relacion like '%".$nombreRelacion."%' or ";

			// se elimina la última partícula disyuntiva (or) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);
			$sql .= ')';
		}

		// se devuelve el filtro
		return $sql;		
	}		


	private function crearFiltroBuscarPersonajesSeleccionables($nombreLargo) {

		// se inicializa la cadena que contendrá el filtro
		$sql = "";

		// si hay algún filtro añadimos el "where" y los filtros correspondientes
		if (!empty($nombreLargo)) {
			$sql = " where nombre_largo like '%".$nombreLargo."%' ";
		}

		// se devuelve el filtro
		return $sql;		
	}


	private function buscarRelatosPersonaje() {

		// se inicializa el array de relatos
		$this->arrRelatos = array();

		// cadena sql para realizar la carga de relatos del personaje
		$sql = "select * from relato_personaje where id_personaje = ".$this->id;

		// ejecución de la consulta de búsqueda
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la imagen
			$r = new Relato();
			$r->setId($arrRegistro['id_relato']);

			// introduciendo la imagen en el array de características del personaje
			$this->arrRelatos[] = $r;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrRelatos;	

	}	


		
	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function insertar($nombre, $nombre_largo, $sexo, $anyo) {

		// si la fecha no es válida no se puede insertar
		$arrFecha = explode('-', $anyo);

 		$fechaValida = checkdate( $arrFecha[1] , $arrFecha[2] , $arrFecha[0] ); // mes, día, año
		if (!$fechaValida) throw new Exception('Personaje::insertar: Fecha no válida: '.$anyo);

		// cadena sql para realizar la inserción
		$sql = "insert into personaje (nombre, nombre_largo, sexo, anyo, numero_imagen) ".
			"values ('".$nombre."', '".$nombre_largo."', '".$sexo."', '".$anyo."', 0) ".
			"Returning id";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el id del personaje
		$row = pg_fetch_row($query);
		$this->id = $row[0];

		// cargando el resto de atributos
		$this->nombre = $nombre;
		$this->nombre_largo = $nombre_largo;
		$this->sexo = $sexo;
		$this->anyo = $anyo;
		$this->edad = $this->calcularEdad();

		return $this->id;

	}	


	public function actualizar($nombre, $nombre_largo, $sexo, $anyo) {

		// si no está cargada la relación, no se puede actualizar
		if (!$this->id) throw new Exception('Personaje::actualizar: Personaje no cargado');

		// cadena sql para realizar la carga
		$sql = "update personaje set ".
			"nombre = '".$nombre."', ".
			"nombre_largo = '".$nombre_largo."', ".
			"sexo = '".$sexo."', ".
			"anyo = '".$anyo."' ".
			"where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function eliminar() {

		// si no está cargada la característica, no se puede actualizar
		if (!$this->id) throw new Exception('Personaje::eliminar: Personaje no cargado');

		// cadena sql para realizar la carga
		$sql = "delete from personaje where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function insertarImagen($nombre_imagen) {

		// si no está cargada la relación, no se puede actualizar
		if (!$this->id) throw new Exception('Personaje::insertarImagen: Personaje no cargado');

		// cadena sql para realizar la inserción múltiple de ambas relaciones
		$sql = "insert into personaje_imagen (id_personaje, nombre_imagen) values ".
			"(".$this->id.", '".$nombre_imagen."')";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}	


	public function eliminarRelacion($personaje2) {

		// si no está cargada la relación, no se puede actualizar
		if (!$this->id) throw new Exception('Personaje::eliminarRelacion: Personaje no cargado');

		// cadena sql para eliminar las relaciones entre ambos personajes
		$sql = "delete from personaje_relacion where ".
			"(id_personaje1 = ".$this->id." AND id_personaje2 = ".$personaje2->getId().") OR ".
			"(id_personaje1 = ".$personaje2->getId()." AND id_personaje2 = ".$this->id.")";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function eliminarImagen($nombreImagen) {

		// si no está cargada la relación, no se puede actualizar
		if (!$this->id) throw new Exception('Personaje::eliminarImagen: Personaje no cargado');

		// cadena sql para eliminar la imagen del personaje
		$sql = "delete from personaje_imagen where ".
			"(id_personaje = ".$this->id." AND nombre_imagen = '".$nombreImagen."')";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}		


	public function insertarCaracteristicas($arrCaracteristicas, $nivelDefecto) {

		// definición de la cadena de inserción
		$sql = " insert into personaje_caracteristica (id_personaje, id_caracteristica, nivel) values ";

		// se recorre el array de características para añadir a la inserción múltiple
		foreach ($arrCaracteristicas as $c) {
			$sql .= "(".$this->id.", ".$c->getId().", ".$nivelDefecto."), ";
		}

		// se elimina la última coma (,) de la cadena
		$sql = substr($sql, 0, strlen($sql)-2);

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);
	}	


	public function buscarCaracteristicas() {

		// se inicializa el array de relaciones
		$this->arrCaracteristicas = array();

		// cadena sql para realizar la carga
		$sql = "select * from vista_personaje_caracteristicas where id_personaje = ".$this->id.
			" Order by nombre_caracteristica asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la caracteristica
			$pc = new PersonajeCaracteristica();
			$pc->setIdCaracteristica($arrRegistro['id_caracteristica']);
			$pc->setNombre($arrRegistro['nombre_caracteristica']);
			$pc->setNivel($arrRegistro['nivel']);

			// introduciendo la caracteristica en el array de características del personaje
			$this->arrCaracteristicas[] = $pc;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrCaracteristicas;		
	}


	public function numRelaciones() {

		// cadena sql para realizar la carga
		$sql = "select count(id_personaje1) from personaje_relacion where id_personaje1 = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// se devuelve la cantidad de registros encontrados
		return $arrRegistro[0];

	}


	public function buscarRelaciones($nombreLargo = '', $nombreRelacion = '') {

		// se inicializa el array de relaciones
		$this->arrRelaciones = array();

		// cadena sql para realizar la carga
		$sql = "select * from vista_personaje_relaciones where id_personaje1 = ".$this->id;

		// se incluye los filtros si los hubiere
		$sql .= $this->crearFiltroBuscarRelaciones($nombreLargo, $nombreRelacion);

		// aplicamos la ordenación
		$sql .= " Order by nombre_relacion asc ";

		// si no está limitado se pone a false
		if ($this->limitar) $sql .= " limit ".$this->N;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la relacion
			$pr = new PersonajeRelacion();
			$pr->setIdPersonaje2($arrRegistro['id_personaje2']);
			$pr->setIdRelacion($arrRegistro['id_relacion']);
			$pr->setNombre($arrRegistro['nombre']);
			$pr->setNombreLargo($arrRegistro['nombre_largo']);
			$pr->setNombreRelacion($arrRegistro['nombre_relacion']);

			// introduciendo la relacion en el array de características del personaje
			$this->arrRelaciones[] = $pr;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrRelaciones;		
	}


	public function buscarImagenes() {

		// se inicializa el array de imagenes
		$this->arrImagenes = array();

		// cadena sql para realizar la carga
		$sql = "select * from personaje_imagen where id_personaje = ".$this->id.
			" Order by substr(nombre_imagen,4)::numeric asc ";
			//nombre_imagen asc ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el primer registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// recorriendo el apuntador para obtener todos los registros
		while ($arrRegistro) {

			// creando la imagen
			$pr = new PersonajeImagen();
			$pr->setIdPersonaje($arrRegistro['id_personaje']);
			$pr->setNombreImagen($arrRegistro['nombre_imagen']);

			// introduciendo la imagen en el array de características del personaje
			$this->arrImagenes[] = $pr;

			// obteniendo el siguiente registro de la base de datos
			$arrRegistro = pg_fetch_array($query);
		}

		return $this->arrImagenes;		
	}	


	public function actualizarCaracteristicas($arrCaracteristicas) {

		// se construye la sql para actualización múltiple
		$sql = " update personaje_caracteristica as t set nivel = c.nivel from (values ";

		// se incluye cada fila para actualizar
		foreach ($arrCaracteristicas as $caracteristica) {
			$sql .= " (".$this->getId().", ".$caracteristica[0].", ".$caracteristica[1]."), ";
		}

		// se elimina la última coma (,) de la cadena
		$sql = substr($sql, 0, strlen($sql)-2);

		// se termina de construir la consulta múltiple
		$sql .= ") as c(id_personaje, id_caracteristica, nivel) ".
			" where c.id_personaje = t.id_personaje and c.id_caracteristica = t.id_caracteristica ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function insertarPersonajeRelacion($personaje, $relacion) {

		// cadena sql para realizar la inserción
		$sql = "insert into personaje_relacion (id_personaje1, id_personaje2, id_relacion) ".
			"values ('".$this->id."', '".$personaje->getId()."', '".$relacion->getId()."') ";

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function eliminarPersonajeRelacion($personaje) {

		// si no está cargado el pesonaje, no se puede eliminar
		if (!$this->id) throw new Exception('Personaje::eliminar: Personaje no cargado');

		// cadena sql para realizar la eliminación
		$sql = "delete from personaje_relacion where ".
			" id_personaje1 = ".$this->id." and id_personaje2 = ".$personaje->getId();

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function incrementarNumeroImagen() {

		// se incremental el número de imagen
		$numeroImagen = $this->numero_imagen + 1;

		// cadena sql para realizar la actualización
		$sql = "update personaje set numero_imagen = ".$numeroImagen." where id = ".$this->id;

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

	}


	public function eliminarRelatosPersonaje() {

		// se buscan los relatos del personaje
		$arrRelatos = $this->buscarRelatosPersonaje();

		// si existen relatos se procede a su eliminación
		if (count($arrRelatos) > 0) {

			// se crea la consulta de eliminación
			$sql = " delete from relato where (";

			// se incluyen todos los relatos
			foreach ($arrRelatos as $value) {
				$sql .= " id = ".$value->getId().' or ';
			}

			// se elimina la última partícula conjuntiva (and) de la cadena
			$sql = substr($sql, 0, strlen($sql)-4);
			$sql .= ')';

			// ejecución de la consulta
			$query = pg_exec($this->conn, $sql);
		}

	}


	public function buscarRelacionConPersonaje($personaje2) {

		// cadena sql para realizar la carga
		$sql = "select * from vista_personaje_relaciones where ";

		// aplicamos la ordenación
		$sql .= " id_personaje1 = ".$this->id." and id_personaje2 = ".$personaje2->getId();

		// ejecución de la consulta
		$query = pg_exec($this->conn, $sql);

		// obteniendo el registro de la base de datos
		$arrRegistro = pg_fetch_array($query);

		// creando la relacion
		$pr = new PersonajeRelacion();
		$pr->setIdPersonaje2($arrRegistro['id_personaje2']);
		$pr->setIdRelacion($arrRegistro['id_relacion']);
		$pr->setNombre($arrRegistro['nombre']);
		$pr->setNombreLargo($arrRegistro['nombre_largo']);
		$pr->setNombreRelacion($arrRegistro['nombre_relacion']);

		// se devuelve el objeto PersonajeRelacion
		return $pr;		
	}



	/* Métodos getters y setters */

	public function getId() {
		return $this->id;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function getNombreLargo() {
		return $this->nombre_largo;
	}

	public function getSexo() {
		return $this->sexo;
	}

	public function getAnyo() {
		return $this->anyo;
	}

	public function getEdad() {
		return $this->edad;	
	}

	public function getNumeroImagen() {
		return $this->numero_imagen;
	}

	public function setId($id) {
		$this->id = $id;
	}	

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}	

	public function setNombreLargo($nombre_largo) {
		$this->nombre_largo = $nombre_largo;
	}	

	public function setSexo($sexo) {
		$this->sexo = $sexo;
	}	

	public function setAnyo($anyo) {
		$this->anyo = $anyo;
	}	

	public function setEdad() {
		$this->calcularEdad();
	}	

	public function setNumeroImagen($numero_imagen) {
		$this->numero_imagen = $numero_imagen;
	}	

}

?>