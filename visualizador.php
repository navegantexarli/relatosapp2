<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se inicializa la sesión de usuario
	session_start();
	
	// se pone en marcha el control de errores
	try {

	// si no hay usuario logeado se lanza el error
	if (!isset($_SESSION['usuario'])) throw new Exception('Usuario no conectado');

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
	$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirVisualizador';

	// dependiendo de la acción de usuario se solicita una acción u otra al controlador
	switch ($strAccion) {


		// se ha solicitado la acción abrirNodo
		case 'abrirNodo':
			$cr->abrirNodo();
			break;


		default:
			// se solicita abrir el visualizador
			$cr->abrirVisualizador();
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