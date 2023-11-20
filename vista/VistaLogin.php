<!-- ------------------------------------------------------------------------------------------
VistaLogin
------------------------------------------------------------------------------------------- -->

<!doctype html>
<html lang="en">

<!-- Cabecera de la aplicación -->
<?php require 'base/cabecera.php' ?>



<!-- ------------------------------------------------------------------------------------------
CUERPO DE LA VISTA
------------------------------------------------------------------------------------------- -->

<body>

<div class="areaTrabajo"> 

    <!-- se indica el título de la página -->
    <div class="tituloPagina">
      <h1>Tus relatos</h1>
    </div>
      
    <!-- se define la vista activa -->
    <?php $vistaActiva = 'VistaLogin'; ?>

    <!-- menú principal de la aplicación -->
    <?php require 'base/menu.php' ?>     

    <!-- Area donde irá ubicaco el login y password -->
    <div class="areaLogin">


        <div class="wrapper fadeInDown">
        <div id="formContent">
        
            <!-- Icono de la aplicación -->
            <div class="fadeIn first iconoLogin">
                <img 
                    src="/relatosapp/res/iconos/iconoPurpleRelatos.png"  
                    width="40px" 
                    height="40px" 
                    id="icon" 
                    alt="User Icon" />
            </div>

            <!-- Formulario del login -->
            <form action="/relatosapp/" method="POST">
                
                <!-- hidden para guardar la acción solicitarAcceso -->
                <input type="hidden" name="accion" value="solicitarAcceso">

                <!-- input para indicar el login -->
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    class="fadeIn second botonLogin"  
                    placeholder="login">

                <!-- input para indicar el password-->
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="fadeIn third botonLogin" 
                    placeholder="password">

                <!-- botón submit para enviar el formulario-->
                <input type="submit" class="fadeIn fourth botonLogin" value="Acceder">

                <!-- se indican aquí los posibles mensajes de error -->
                <p class="usuarioIncorrecto">
                    <?php if (isset($_SESSION['errorAutenticacion'])) 
                        echo $_SESSION['errorAutenticacion']; ?>
                </p>
            
            </form>

            <!-- pie de formulario. Está deshabilitado -->
            <div id="formFooter">
                <!-- <a class="underlineHover disabled" href="#">Forgot Password?</a> -->
            </div>

        </div>
        </div>

    </div>

    <!-- pie de la aplicación -->
    <?php require 'base/pie.php' ?>

</div>



<!-- --------------------------------------------------------------------------------------
CODIGO JAVASCRIPT
--------------------------------------------------------------------------------------- -->

<script type="text/javascript">

// definición de todas las funciones ajax cuando el documento está preparado
$(document).ready(function() {

    // --------------------------------------------------------------------------------
    // AUTOEJECUCIONES AL CARGAR EL DOCUMENTO
    // --------------------------------------------------------------------------------

    // se obtiene el texto del mensaje de error
    strError = $("p.usuarioIncorrecto").html();

    // si no hay mensaje de error se oculta el area en el que se muestra el mensaje de error
    if (!strError) $("p.usuarioIncorrecto").hide();

    // si hay mensaje de error se muestra el area en el que se muestra el mensaje de error
    else $("p.usuarioIncorrecto").show();

});

</script>

</body>
</html>
