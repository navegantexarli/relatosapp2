<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se recoge la acción pasada por el usuario
	$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirRelatos';
	
	// se pone en marcha el control de errores
	try {

	// se inicializa la sesión de usuario sólo si la acción no es la acción que comprueba el estado
	// del proceso, ya que el proceso queda síncrono y no avanza hasta que termine todo el proceso.
	// Esto también implica que la gestión de errores por usuario no conectado no debe capturarse.
	if (strcmp($strAccion, 'obtenerEstadoProceso') != 0) {

		// se inicializa la sesión de usuario
		session_start();

		// si no hay usuario logeado se lanza el error
		if (!isset($_SESSION['usuario'])) throw new Exception('Usuario no conectado');
	}

	// se incluye el controlador de relatos
	include_once ("controlador/ControlRelatos.php");

	// se incluye el control de excepciones de permisos
	include_once ("controlador/PermisosException.php");

	// definimos el array de roles que tiene permiso para acceder a esta sección de la aplicación
	$arrPermisos = ['admin', 'escritor', 'lector'];

	// se inicializa el controlador de relatos
	$cr = new ControlRelatos();

	// si el usuario no tiene un rol con acceso se lanza el error
	if (!$cr->getAcceso($_SESSION['rol'], $arrPermisos)) throw new PermisosException(2, '[2] Acceso denegado');

	// se recoge la acción pasada por el usuario
	//$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirRelatos';


	// dependiendo de la acción de usuario se solicita una acción u otra al controlador
	switch ($strAccion) {


		// se ha solicitado la acción buscarRelatos
		case 'buscarRelatos':
			$cr->buscarRelatos();
			break;


		// se ha solicitado la acción crearRelato
		case 'crearRelato':
			$cr->crearRelato();
			break;


		// se ha solicitado la acción actualizarRelato
		case 'actualizarRelato':
			$cr->actualizarRelato();
			break;			


		// se ha solicitado la acción eliminarRelato
		case 'eliminarRelato':
			$cr->eliminarRelato();
			break;			


		// se ha solicitado la acción asignarGuionRelato
		case 'asignarGuionRelato':
			$cr->asignarGuionRelato();
			break;			


		// se ha solicitado la acción generarRelato
		case 'generarRelato':
			// se inicia el generador de relatos para que ejecute la acción
			$gr = new GeneradorRelatos();
			$gr->generarRelato();
			break;			

		// se ha solicitado la acción obtenerEstadoProceso
		case 'obtenerEstadoProceso':
			// se inicia el generador de relatos para que ejecute la acción
			$gr = new GeneradorRelatos();
			$gr->obtenerEstadoProceso();
			break;		


		// si no hay acción se elige la acción por defecto que es obtener la lista de relatos
		default:
			// se solicita abrir la ventana de relatos
			$cr->abrirRelatos();
			break;
	}

	// se captura primero las excepciones por permiso denegado
	} catch (PermisosException $e) {

		// en caso de permiso denegado se solicita al controlador preparar la vista de usuario
		$arrTiempoConexion = $cr->prepararUsuario();

		// guardamos el error de usuario
		$strPermisoDenegado = $e->getMessage();

		// se despliega la vista de usuario
		require 'vista/VistaUsuario.php';

	// se capturan el resto de excepciones
	} catch (Exception $e) {

		// se redirecciona a la página de inicio
		header("Location: /relatosapp/");
	}	
	
?>