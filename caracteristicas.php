<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se inicializa la sesión de usuario
	session_start();

	// se pone en marcha el control de errores
	try {

	// si no hay usuario logeado se lanza el error
	if (!isset($_SESSION['usuario'])) throw new Exception('[1] Usuario no conectado');

	// se incluye el controlador de características
	include_once ("controlador/ControlCaracteristicas.php");

	// se incluye el control de excepciones de permisos
	include_once ("controlador/PermisosException.php");

	// definimos el array de roles que tiene permiso para acceder a esta sección de la aplicación
	$arrPermisos = ['admin'];

	// se inicializa el controlador de características
	$cc = new ControlCaracteristicas();

	// si el usuario no tiene un rol con acceso se lanza el error
	if (!$cc->getAcceso($_SESSION['rol'], $arrPermisos)) throw new PermisosException(2, '[2] Acceso denegado');

	// en caso de tener permiso se recoge la acción pasada por el usuario
	$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirCaracteristicas';

	// dependiendo de la acción de usuario se solicita una acción u otra al controlador
	switch ($strAccion) {


		// se ha solicitado la acción buscarCaracteristicas
		case 'buscarCaracteristicas':
			$cc->buscarCaracteristicas();
			break;


		// se ha solicitado la acción crearCaracteristica
		case 'crearCaracteristica':
			$cc->crearCaracteristica();
			break;


		// se ha solicitado la acción actualizarCaracteristica
		case 'actualizarCaracteristica':
			$cc->actualizarCaracteristica();
			break;			


		// se ha solicitado la acción eliminarCaracteristica
		case 'eliminarCaracteristica':
			$cc->eliminarCaracteristica();
			break;			


		// si no hay acción se elige la acción por defecto que es obtener la lista de características
		default:
			// se solicita abrir la ventana de características
			$cc->abrirCaracteristicas();
			break;
	}

	// se captura primero las excepciones por permiso denegado
	} catch (PermisosException $e) {

		// en caso de permiso denegado se solicita al controlador preparar la vista de usuario
		$arrTiempoConexion = $cc->prepararUsuario();

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