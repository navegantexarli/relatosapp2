<!-- ------------------------------------------------------------------------------------------
VistaUsuario
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
    <?php $vistaActiva = 'VistaUsuario'; ?>

    <!-- menú principal de la aplicación -->
    <?php require 'base/menu.php' ?>     

    <!-- Area donde irá ubicaco el login y password -->
    <div class="areaLogin">

        <!-- se guarda las horas, minutos y segundos que llevan conectados -->
        <input type="hidden" id="horas" name="horas" value="<?php echo $arrTiempoConexion[0] ?>">
        <input type="hidden" id="minutos" name="minutos" value="<?php echo $arrTiempoConexion[1] ?>">
        <input type="hidden" id="segundos" name="segundos" value="<?php echo $arrTiempoConexion[2] ?>">

        <!-- tarjeta para mostrar el usuario conectado -->
        <div class="card border-warning mb-3" style="max-width: 18rem;">
            <div id="tiempoConexion" class="card-header">
                <?php echo '['.$arrTiempoConexion[0].':'.$arrTiempoConexion[1].':'.$arrTiempoConexion[2].']' ?> Usuario Conectado
            </div>
            <div class="card-body">
                <h5 class="card-title">Bienvenido <?php echo $_SESSION['usuario']; ?></h5>
                <p class="card-text">Ahora estás conectado a la aplicación de relatos. Puedes acceder a todas las secciones habilitadas para ti.</p>
                <p class="usuarioIncorrecto">
                    <?php if (isset($strPermisoDenegado)) echo $strPermisoDenegado; ?>
                </p>
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

    // si el usuario está conectado se arranca el temporizador
    intervalo();


    // --------------------------------------------------------------------------------
    // FUNCIONES DEL MODAL
    // --------------------------------------------------------------------------------



    // función que lanza cada segundo la solicitud de actualizar el temporizador
    // --------------------------------------------------------------------------------
    function intervalo() {

        // se pone en marcha el intervalo
        setInterval(function() {
            actualizarTiempoConexion();
        },1000,"JavaScript");
    }



    // función que actualiza el tiempo de conexión
    // --------------------------------------------------------------------------------
    function actualizarTiempoConexion() {
        
        // se obtienen las horas, minutos y segundos
        horas = parseInt($("input#horas").val());
        minutos = parseInt($("input#minutos").val());
        segundos = parseInt($("input#segundos").val());

        // se incrementan los segundos
        segundos++;

        // si los segundos han llegado a 60 pasan a 0
        if (segundos > 59) {
            segundos = 0;

            // se incrementan los minutos
            minutos++;

            // si los minutos han llegado a 60 pasan a 0
            if (minutos > 59) {
                minutos = 0;

                // se incrementan las horas
                horas++;
            }
        }

        // se vuelven a guardar las horas, minutos y segundos
        $("input#horas").val(horas);
        $("input#minutos").val(minutos);
        $("input#segundos").val(segundos);

        // se formatean las horas, minutos y segundos
        if (segundos < 10) segundos = '0'+segundos;
        if (minutos < 10) minutos = '0'+minutos;
        if (horas < 10) horas = '0'+horas;

        // se crea el tiempo de conexión
        tiempoConexion = '['+horas+':'+minutos+':'+segundos+']';

        // se actualiza el tiempo de conexión
        $("div#tiempoConexion").html(tiempoConexion+' Usuario Conectado');
    }
});

</script>

</body>
</html>
