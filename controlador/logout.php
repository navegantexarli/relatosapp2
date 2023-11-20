<?php 
// se inicia la sesión de usuario
// a partir de ahora podemos acceder a sus variables de sesión
session_start();

// eliminamos la variable de sesión de usuario para evitar la caché
unset($_SESSION["usuario"]);

// destruímos la sesión
session_destroy();

// eliminamos la cookie de la sesión
setcookie(session_name(), 123, time()-1000);

// mostramos todas las variables de sesión del usuario
// print_r($_SESSION);

// redirigimos al programa principal
header("Location: /relatosapp/");
?>
