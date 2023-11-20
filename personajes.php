<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se inicializa la sesión de usuario
	session_start();
	
	// se pone en marcha el control de errores
	try {

	// si no hay usuario logeado se lanza el error
	if (!isset($_SESSION['usuario'])) throw new Exception('Usuario no conectado');

	// se incluye el controlador de personajes
	include_once ("controlador/ControlPersonajes.php");

	// se incluye el control de excepciones de permisos
	include_once ("controlador/PermisosException.php");

	// definimos el array de roles que tiene permiso para acceder a esta sección de la aplicación
	$arrPermisos = ['admin', 'escritor'];

	// se inicializa el controlador de personajes
	$cp = new ControlPersonajes();

	// si el usuario no tiene un rol con acceso se lanza el error
	if (!$cp->getAcceso($_SESSION['rol'], $arrPermisos)) throw new PermisosException(2, '[2] Acceso denegado');

	// se recoge la acción pasada por el usuario
	$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirPersonajes';

	// dependiendo de la acción de usuario se solicita una acción u otra al controlador
	switch ($strAccion) {


		// se ha solicitado la acción buscarPersonajes
		case 'buscarPersonajes':
			$cp->buscarPersonajes();
			break;


		// se ha solicitado la acción crearPersonaje
		case 'crearPersonaje':
			$cp->crearPersonaje();
			break;


		// se ha solicitado la acción actualizarPersonaje
		case 'actualizarPersonaje':
			$cp->actualizarPersonaje();
			break;			


		// se ha solicitado la acción eliminarPersonaje
		case 'eliminarPersonaje':
			$cp->eliminarPersonaje();
			break;			


		// se ha solicitado la acción abrirCaracteristicas
		case 'abrirCaracteristicas':
			$cp->abrirCaracteristicasPersonaje();
			break;			


		// se ha solicitado la acción abrirCaracteristicas
		case 'actualizarCaracteristicas':
			$cp->actualizarCaracteristicas();
			break;			


		// si no hay acción se elige la acción por defecto que es obtener la lista de personajes
		default:
			// se solicita abrir la ventana de personajes
			$cp->abrirPersonajes();
			break;
	}

	// se captura primero las excepciones por permiso denegado
	} catch (PermisosException $e) {

		// en caso de permiso denegado se solicita al controlador preparar la vista de usuario
		$arrTiempoConexion = $cp->prepararUsuario();

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