<?php 

	// establecer la caducidad de la caché a 0 minutos para poder trabajar con variables de sesión
	session_cache_expire(0);
	
	// se inicializa la sesión de usuario
	session_start();
	
	// se pone en marcha el control de errores
	try {

	// si no hay usuario logeado se lanza el error
	if (!isset($_SESSION['usuario']))
		throw new Exception('Usuario no conectado');

		// se incluye el controlador
		include_once ("controlador/Controlador.php");

		// se inicializa el controlador de acceso
		$c = new Controlador();

		// se solicita al controlador preparar la vista de usuario
		$arrTiempoConexion = $c->prepararUsuario();

		// se despliega la vista de usuario
		require 'vista/VistaUsuario.php';

	} catch (Exception $e) {

		// se redirecciona a la página de inicio
		header("Location: /relatosapp/");
	}	
	
?>