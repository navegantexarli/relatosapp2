<?php

include_once ("modelo/Usuario.php");

/* ************************************************************************************ *
 * CLASE Controlador
 * ************************************************************************************ */

class Controlador {

	// definición de la constante para el nivel máximo
	const nivelMax = 5; 	 				

	// ruta en la que están los directorios de las imágenes de los personajes
	const urlImagenes = 'res/imagenes/';

	// símbolo utilizado para detectar instruciones en el texto de un párrafo
	const simboloIniInst = '[';
	const simboloFinInst = ']';

	// definición del tiempo de espera para comprobar el estado del generador de relatos
	// en este caso se define a 1 segundo (1 millón de microsegundos)
	const tiempoEspera = 100000;			

	// nivel por defecto que se calcula en base al nivel máximo
	protected $nivelDefecto; 				



	/* ************************************************************************************ *
	 * CONSTRUCTOR
	 * ************************************************************************************ */
	
	function __construct() {
		$this->nivelDefecto = ceil(self::nivelMax / 2);
	}	



	/* *******************************************************************************************
	 * METODOS PUBLICOS
	 * ***************************************************************************************** */	


	// método que intenta autenticar un usuario
	// en caso de éxito se guarda su variable de sesión e instante de conexión
	public function autenticarUsuario($login, $password) {

		// se carga el usuario
		$usuario = new Usuario($login);

		// si no se ha podido cargar el usuario es que no existe
		if (!$usuario->getId()) throw new Exception('[2] Usuario o clave incorrectos');		

		// si la clave del usuario es válida se procederá a la autenticación
		if (!password_verify($password, $usuario->getPassword())) throw new Exception('[3] Error de autenticación');
		
		// eliminamos el posible de erro de autenticación
		unset($_SESSION['errorAutenticacion']);

		// se inicializa el tiempo de conexión
		$horas = 0;
		$minutos = 0;
		$segundos = 0;
		$strTiempoConexion = '[00:00:00]';	

		// si la clave de usuario es correcta se guarda en la variable de sesión
		$_SESSION['usuario'] = $usuario->getLogin();

		// se guarda también el rol del usuario
		$_SESSION['rol'] = 'escritor';

		// se crea el instante actual
		$instanteConexion = new DateTime();

		// se guarda el instante en que se ha logeado
		$_SESSION['instanteConexion'] = $instanteConexion->getTimeStamp();
		
	}


	// método que prepara la información para la vista de usuario 
	public function prepararUsuario() {

		// se obtiene el instante de conexión del usuario
		$instanteConexion = $_SESSION['instanteConexion'];

		// se crea la fecha para el instante de conexión
		$fechaInstanteConexion = new DateTime();

		// se obtiene la fecha relativa al instante de conexión
		$fechaInstanteConexion->setTimeStamp($instanteConexion);

		// se obtiene la fecha relativa al instante de conexión actual
		$fechaInstanteConexionActual = new DateTime();

		// se calcula la diferencia entre las dos fechas
		$tiempoConexion = $fechaInstanteConexion->diff($fechaInstanteConexionActual);

		// se formatea el instante de coenxión en horas:minutos:segundos
		$horas = ($tiempoConexion->h < 10) ? '0'.$tiempoConexion->h : $tiempoConexion->h;
		$minutos = ($tiempoConexion->i < 10) ? '0'.$tiempoConexion->i : $tiempoConexion->i;
		$segundos = ($tiempoConexion->s < 10) ? '0'.$tiempoConexion->s : $tiempoConexion->s;

		// creamos el instante de tiempo
		$arrTiempoConexion = [$horas, $minutos, $segundos];
	
		// devolvemos el instante de tiempo
		return $arrTiempoConexion;
	}	
		

	// método que comprueba si un rol de usuario está dentro de un array de permisos
	public function getAcceso($rolUsuario, $permisos) {
		
		// el acceso, por defecto, a false
		$acceso = false;

		// recorremos todos los roles a los que se le permite el acceso
		for ($i=0; $i<count($permisos); $i++) {
			if ($rolUsuario == $permisos[$i]) $acceso = true;
		}

		// devolvemos el resultado de comprobación de acceso
		return $acceso;
	}
		
}

?>