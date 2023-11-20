<?php

include_once ("modelo/Buscador.php");
include_once ("Controlador.php");


/* *******************************************************************************************
 
 * CLASE Controlador

 * ***************************************************************************************** */

class ControlPersonajes extends Controlador {


	/* *******************************************************************************************
	 * CONSTRUCTOR
	 * ***************************************************************************************** */
	
	function __construct() {
		parent::__construct();
	}



	/* *******************************************************************************************
	 * METODOS PRIVADOS
	 * ***************************************************************************************** */	
	
	private function eliminarDirectorio($urlDirectorio) {

		// se comprueba si existe el directorio
		if (file_exists($urlDirectorio)) {

			// antes de eliminar el directorio hay que eliminar sus ficheros (imágenes)
		    // se buscan los ficheros dentro del directorio
			$arrFicheros = scandir($urlDirectorio);

		    // por cada fichero se procede a su eliminación de forma recursiva
		    foreach( $arrFicheros as $file ){

				// ¡ALERTA! Los ficheros '.' y '..' no se eliminan porque se eliminaría recursivamente	
		    	if ($file != "." && $file != "..") {

		    		// se elimina el fichero
		    		unlink($urlDirectorio.$file);
		        }
		    }

		    // una vez eliminados las imágenes del directorio, se elimina el directorio
		    rmdir($urlDirectorio);
		}

	}


	private function crearJsonPersonajes($arrPersonajes, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de personajes
		foreach ($arrPersonajes as $personaje) {
			
			// se crea la siguiente personaje
			$arrJson[] = array(
				"id" => $personaje->getId(),
				"nombre" => $personaje->getNombre(),
				"nombreLargo" => $personaje->getNombreLargo(),
				"sexo" => $personaje->getSexo(),
				"edad" => $personaje->getEdad()
			);
		}

		// se incluye el número de registros y las personajes en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}	


	private function crearJsonPersonajeCaracteristicas($arrPersonajeCaracteristicas) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de características
		foreach ($arrPersonajeCaracteristicas as $caracteristica) {
			
			// se crea la siguiente caracteristica
			$arrJson[] = array(
				"id" => $caracteristica->getIdCaracteristica(),
				"nombre" => $caracteristica->getNombre(),
				"nivel" => $caracteristica->getNivel()
			);
		}

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;
	}	


	private function crearJsonPersonajeRelaciones($arrPersonajeRelaciones, $numRegistros) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de características
		foreach ($arrPersonajeRelaciones as $relacion) {
			
			// se crea la siguiente relacion
			$arrJson[] = array(
				"idRelacion" => $relacion->getIdRelacion(),
				"nombreRelacion" => $relacion->getnombreRelacion(),
				"idPersonaje2" => $relacion->getIdPersonaje2(),
				"nombreLargoP2" => $relacion->getNombreLargo()
			);
		}

		// se incluye el número de registros y relaciones del personaje en el json
		$arrJson = array($numRegistros, $arrJson);

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;

	}	


	private function crearJsonPersonajeImagenes($arrPersonajeImagenes) {

		// se inicializa el array JSON
		$arrJson = array();

		// se recorre todo el array de imágenes
		foreach ($arrPersonajeImagenes as $imagen) {
			
			// se crea la siguiente imagen
			$arrJson[] = array(
				"idPersonaje" => $imagen->getIdPersonaje(),
				"nombreImagen" => $imagen->getnombreImagen()
			);
		}

		// se codifica el array en formato JSON
		$json = json_encode($arrJson);

		// se devuelve el json
		return $json;

	}


	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	

	public function abrirPersonajes() {

		// se carga el buscador
		$buscador = new Buscador();

		// se obtiene el total de personajes que hay
		$numRegistros = $buscador->numRegistros('personaje');

		// se buscan los N primeros personajes
		$arrPersonajes = $buscador->buscarPersonajes();

		// se abre la vista de las personajes
		require 'vista/VistaPersonajes.php';

	}


	public function buscarPersonajes() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];
		$nombreLargo = $_POST['nombreLargo'];
		$sexo = $_POST['sexo'];
		$edad = $_POST['edad'];

		// se inicializa el año
		$anyo = '';

		// se calcula el año en base a la edad, si hay edad
		if ($edad) {
			$fecha = new DateTime();
			$anyoActual = $fecha->format('Y');
			$intAnyo = $anyoActual - $edad;
			$anyo = $intAnyo.'-01-01';
		}

		// se carga el buscador
		$b = new Buscador();
		
		// se obtiene la cantidad de personajes que hay en total
		$numRegistros = $b->numRegistros('personaje');
		
		// se buscan las personajes que cumplen con el filtro
		$arrPersonajes = $b->buscarPersonajes($nombre, $nombreLargo, $sexo, $anyo);

		// se codifica el array de personajes en un JSON
		$jsonPersonajes = $this->crearJsonPersonajes($arrPersonajes, $numRegistros);

		// se cdevuelve el json de personajes
		echo $jsonPersonajes;		

	}	


	public function crearPersonaje() {

		// se recogen los parámetros de entrada
		$nombre = $_POST['nombre'];
		$nombreLargo = $_POST['nombreLargo'];
		$sexo = $_POST['sexo'];
		$edad = $_POST['edad'];

		// se inicializa el año
		$anyo = '';

		// se calcula el año en base a la edad, si hay edad
		if ($edad) {

			$fecha = new DateTime();
			$anyoActual = $fecha->format('Y');
			$intAnyo = $anyoActual - $edad;
			$anyo = $intAnyo.'-01-01';
		}

		// se instancia la personaje
		$p = new Personaje();

		// se inserta el personaje
		$p->insertar($nombre, $nombreLargo, $sexo, $anyo);

		// se instancia el buscador
		$b = new Buscador();

		// se buscan todas las características
		$b->limitar(false);
		$arrCaracteristicas = $b->buscarCaracteristicas();

		// se comprueba si hay características
		if (count($arrCaracteristicas)) {

			// se asignar las características al personaje
			$p->insertarCaracteristicas($arrCaracteristicas, $this->nivelDefecto);
		}

		// se buscan la lista de N personajes
		$b->limitar(true);
		$arrPersonajes = $b->buscarPersonajes();

		// se obtiene la cantidad de personajes que hay en total
		$b->limitar(false);
		$numRegistros = $b->numRegistros('personaje');
		
		// se codifica el array de personajes en un JSON
		$jsonPersonajes = $this->crearJsonPersonajes($arrPersonajes, $numRegistros);

		// se cdevuelve el json de personajes
		echo $jsonPersonajes;			

	}	


	public function actualizarPersonaje() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$nombreLargo = $_POST['nombreLargo'];
		$sexo = $_POST['sexo'];
		$edad = $_POST['edad'];

		// se calcula el año en base a la edad
		$fecha = new DateTime();
		$anyoActual = $fecha->format('Y');
		$intAnyo = $anyoActual - $edad;
		$anyo = $intAnyo.'-01-01';


		// se instancia el personaje
		$c = new Personaje($id);

		// se inserta el personaje
		$c->actualizar($nombre, $nombreLargo, $sexo, $anyo);

		// se instancia el buscador
		$b = new Buscador();

		// se buscan las personajes sin filtro
		$b->limitar(true);
		$numRegistros = $b->numRegistros('personaje');
		
		// se buscan las personajes sin filtro
		$arrPersonajes = $b->buscarPersonajes();

		// se codifica el array de personajes en un JSON
		$jsonPersonajes = $this->crearJsonPersonajes($arrPersonajes, $numRegistros);

		// se cdevuelve el json de personajes
		echo $jsonPersonajes;
	}	


	public function eliminarPersonaje() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia la personaje
		$p = new Personaje($id);

		// se crea la ura al directorio de imágenes del personaje
		$urlDirectorio = self::urlImagenes.'p'.$p->getId().'/';

		// se elimina el directorio de imágenes del personaje
		$this->eliminarDirectorio($urlDirectorio);

		// se eliminan todos los relatos en los que aparezca el personaje
		$arrRelatos = $p->eliminarRelatosPersonaje();

		// se elimina personaje
		$p->eliminar();

		// se instancia el buscador
		$b = new Buscador();

		// se buscan las personajes sin filtro
		$b->limitar(true);		
		$numRegistros = $b->numRegistros('personaje');
		
		// se buscan las personajes sin filtro
		$arrPersonajes = $b->buscarPersonajes();

		// se codifica el array de personajes en un JSON
		$jsonPersonajes = $this->crearJsonPersonajes($arrPersonajes, $numRegistros);

		// se cdevuelve el json de personajes
		echo $jsonPersonajes;				

	}	


	public function abrirCaracteristicasPersonaje() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// se instancia la personaje
		$p = new Personaje($id);

		// se bucan las características del personaje
		$arrPersonajeCaracteristicas = $p->buscarCaracteristicas();

		// se codifica el array de características en un JSON
		$jsonCaracteristicas = $this->crearJsonPersonajeCaracteristicas($arrPersonajeCaracteristicas);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;
	
	}	


	public function actualizarCaracteristicas() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$arrPersonajeCaracteristicas = $_POST['arrPersonajeCaracteristicas'];

		// se instancia el personaje
		$personaje = new Personaje($id);

		// se actualizan las características
		$personaje->actualizarCaracteristicas($arrPersonajeCaracteristicas);

		// se cdevuelve el json de personajes
		echo 'ok';

	}


	public function abrirRelacionesPersonaje() {

		// se recogen los parámetros de entrada
		$id = intval($_GET['idp']);		

		// se instancia la personaje
		$personaje = new Personaje($id);

		// se bucan las relaciones del personaje limitadas
		$personaje->limitar(true);
		$arrPersonajeRelaciones = $personaje->buscarRelaciones();

		// se obtiene la cantidad total de relaciones que hay para el personaje
		$personaje->limitar(true);
		$numRegistros = $personaje->numRelaciones();

		// se abre la vista de las relaciones del personaje
		require 'vista/VistaPersonajeRelaciones.php';

	}		


	public function abrirPersonajeImagenes() {

		// se recogen los parámetros de entrada
		$id = intval($_GET['idp']);		

		// se instancia la personaje
		$personaje = new Personaje($id);

		// se bucan las imagenes del personaje
		$arrPersonajeImagenes = $personaje->buscarImagenes();

		// se abre la vista de las relaciones del personaje
		require 'vista/VistaPersonajeImagenes.php';

	}		


	public function buscarRelacionesPersonaje() {

		// se recogen los parámetros de entrada
		$id = intval($_POST['idPersonaje']);
		$nombreLargo = $_POST['nombreLargo'];
		$nombreRelacion = $_POST['nombreRelacion'];

		// se instancia la personaje
		$personaje = new Personaje($id);

		// se bucan las relaciones del personaje limitadas
		$personaje->limitar(true);
		$arrPersonajeRelaciones = $personaje->buscarRelaciones($nombreLargo, $nombreRelacion);

		// se obtiene la cantidad total de relaciones que hay para el personaje
		$personaje->limitar(true);
		$numRegistros = $personaje->numRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonCaracteristicas = $this->crearJsonPersonajeRelaciones($arrPersonajeRelaciones, $numRegistros);

		// se cdevuelve el json de características
		echo $jsonCaracteristicas;		

	}	


	public function crearPersonajeRelacion() {

		// se recogen los parámetros de entrada
		$idPersonaje1 = intval($_POST['idPersonaje1']);
		$idPersonaje2 = intval($_POST['idPersonaje2']);
		$idRelacion = intval($_POST['idRelacion']);

		// se instancia el personaje principal
		$personaje = new Personaje($idPersonaje1);

		// se instancia la personaje secundario
		$personaje2 = new Personaje($idPersonaje2);

		// se instancia la relación
		$relacion = new Relacion($idRelacion);

		// se crea la relación entre los dos personajes
		$personaje->insertarPersonajeRelacion($personaje2, $relacion);

		// se bucan las relaciones del personaje limitadas
		$personaje->limitar(true);
		$arrPersonajeRelaciones = $personaje->buscarRelaciones();

		// se obtiene la cantidad total de relaciones que hay para el personaje
		$personaje->limitar(false);
		$numRegistros = $personaje->numRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonPersonajeRelaciones($arrPersonajeRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;

	}


	public function eliminarPersonajeRelacion() {

		// se recogen los parámetros de entrada
		$idPersonaje1 = $_POST['idPersonaje1'];
		$idPersonaje2 = $_POST['idPersonaje2'];

		// se instancia el personaje principal
		$personaje = new Personaje($idPersonaje1);

		// se instancia la personaje secundario
		$personaje2 = new Personaje($idPersonaje2);

		// se crea la relación entre los dos personajes
		$personaje->eliminarPersonajeRelacion($personaje2);

		// se bucan las relaciones del personaje limitadas
		$personaje->limitar(true);
		$arrPersonajeRelaciones = $personaje->buscarRelaciones();

		// se obtiene la cantidad total de relaciones que hay para el personaje
		$personaje->limitar(false);
		$numRegistros = $personaje->numRelaciones();

		// se codifica el array de relaciones en un JSON
		$jsonRelaciones = $this->crearJsonPersonajeRelaciones($arrPersonajeRelaciones, $numRegistros);

		// se cdevuelve el json de relaciones
		echo $jsonRelaciones;	

	}	


	public function subirPersonajeImagen() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];

		// si no existe el fichero se lanza el error
		if ($_FILES["imagen"]['error'])			
			throw new Exception('ControladorPersonaje::subirPersonajeImagen: Imagen no cargada');

		// se carga el personaje
		$personaje = new Personaje($id);

		// si no existe el directorio de las imágenes del personaje se crea
		if (!is_dir('res/imagenes/p'.$personaje->getId())) mkdir('res/imagenes/p'.$personaje->getId());

		// se obtiene la ruta al directorio
		$dir = 'res/imagenes/p'.$personaje->getId().'/';

		// se crea le nombre de la imagen
		$nombreImagen = 'img'.$personaje->getNumeroImagen();

		// se obtiene el fichero cargado
		$target_file = basename($_FILES["imagen"]["name"]);

		// si el fichero cargado no es una imagen se lanza un error
		$check = getimagesize($_FILES["imagen"]["tmp_name"]);
		if ($check == false) 
			throw new Exception('ControladorPersonaje::subirPersonajeImagen: Fichero no es una imagen');

		// se guarda la imagen en el directorio y con el nombre correspondiente
		move_uploaded_file($_FILES["imagen"]["tmp_name"], $dir.$nombreImagen);

		// se guarda la imagen en la base de datos
		$personaje->insertarImagen($nombreImagen);
		
		// incrementando el numero de imagen del personaje
		$personaje->incrementarNumeroImagen();

		// se bucan las imagenes del personaje
		$arrPersonajeImagenes = $personaje->buscarImagenes();

		// se codifica el array en formato JSON
		$json = $this->crearJsonPersonajeImagenes($arrPersonajeImagenes);

		// se devuelve el json
		echo $json;

	}


	public function eliminarPersonajeImagen() {

		// se recogen los parámetros de entrada
		$id = $_POST['id'];
		$nombreImagen = $_POST['nombreImagen'];

		// se carga el personaje
		$personaje = new Personaje($id);

		// se obtiene la ruta al directorio
		$dir = 'res/imagenes/p'.$personaje->getId().'/';

		// se procece a la eliminación física de la imagen
		unlink($dir.$nombreImagen);

		// se procece a la eliminación de la imagen
		$personaje->eliminarImagen($nombreImagen);

		// se bucan las imagenes del personaje
		$arrPersonajeImagenes = $personaje->buscarImagenes();

		// se codifica el array en formato JSON
		$json = $this->crearJsonPersonajeImagenes($arrPersonajeImagenes);

		// se devuelve el json
		echo $json;
	}


}

?>