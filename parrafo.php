<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se inicializa la sesión de usuario
	session_start();
	
	// se pone en marcha el control de errores
	try {

	// si no hay usuario logeado se lanza el error
	if (!isset($_SESSION['usuario'])) throw new Exception('Usuario no conectado');

	// se incluye el controlador de guiones
	include_once ("controlador/ControlGuiones.php");
	include_once ("controlador/ControlInstrucciones.php");

	// se incluye el control de excepciones de permisos
	include_once ("controlador/PermisosException.php");

	// definimos el array de roles que tiene permiso para acceder a esta sección de la aplicación
	$arrPermisos = ['admin', 'escritor'];

	// se inicializa el controlador de guiones
	$cg = new ControlGuiones();

	// si el usuario no tiene un rol con acceso se lanza el error
	if (!$cg->getAcceso($_SESSION['rol'], $arrPermisos)) throw new PermisosException(2, '[2] Acceso denegado');

	// se recoge la acción pasada por el usuario
	$strAccion = (isset($_POST['accion'])) ? $_POST['accion'] : 'abrirPrimerParrafo';

	// dependiendo de la acción de usuario se solicita una acción u otra al controlador
	switch ($strAccion) {


		// se ha solicitado la acción abrirParrafo
		case 'abrirParrafo':
			$cg->abrirParrafo();
			break;


		// se ha solicitado la acción crearNuevoParrafo
		case 'crearNuevoParrafo':
			$cg->crearNuevoParrafo();
			break;


		// se ha solicitado la acción guardarParrafo
		case 'guardarParrafo':
			$cg->guardarParrafo();
			break;			


		// se ha solicitado la acción eliminarParrafo
		case 'eliminarParrafo':
			$cg->eliminarParrafo();
			break;			


		// se ha solicitado la acción asignarParrafoHijo
		case 'asignarParrafoHijo':
			$cg->asignarParrafoHijo();
			break;		


		// se ha solicitado la acción desasignarParrafoHijo
		case 'desasignarParrafoHijo':
			$cg->desasignarParrafoHijo();
			break;		


		// se ha solicitado la acción abrirParrafosMarcados
		case 'abrirParrafosMarcados':
			$cg->abrirParrafosMarcados();
			break;
			
			
		// se ha solicitado la acción buscarParrafosMarcados
		case 'buscarParrafosMarcados':
			$cg->buscarParrafosMarcados();
			break;


		// se ha solicitado la acción abrirInstrucciones
		case 'abrirInstrucciones':
			$ci = new ControlInstrucciones();
			$ci->abrirInstrucciones();
			break;


		// se ha solicitado la acción buscarInstrucciones
		case 'buscarInstrucciones':
			$ci = new ControlInstrucciones();
			$ci->buscarInstrucciones();
			break;

			

		// si no hay acción se elige la acción por defecto que es abrir un párrafo
		default:
			// se solicita abrir la ventana que muestra un párrafo
			$cg->abrirPrimerParrafo();
			break;
	}

	// se captura primero las excepciones por permiso denegado
	} catch (PermisosException $e) {

		// en caso de permiso denegado se solicita al controlador preparar la vista de usuario
		$arrTiempoConexion = $cg->prepararUsuario();

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