<!-- ------------------------------------------------------------------------------------------
MODAL modalGenerarRelato                                                    
Modal para generar los nodos del relato                                    
------------------------------------------------------------------------------------------- -->

<div 
    class="modal fade" 
    id="modalGenerarRelato" 
    tabindex="-1" 
    aria-labelledby="modalGenerarRelatoLabel" 
    aria-hidden="true">

    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            
            <h5 
                class="modal-title" 
                id="modalGenerarRelatoLabel">Generar relato <b><span></span></b></h5>
        
            <button 
                type="button" 
                class="btn-close" 
                data-bs-dismiss="modal" 
                aria-label="Close"></button>
        </div>

        <div class="modal-body">
      	
            <!-- Area para el botón que inicia el proceso -->
            <div class="areaIniciarProceso">
                <div class="d-grid gap-2">
                    <button 
                        id="generarRelato" 
                        class="btn btn-outline outlinePurple" 
                        type="button">Iniciar proceso</button>
                </div>
            </div>

           	<!-- Area para la barra de progreso -->
            <div class="areaProgreso">
                <div class="progress">
                    <div 
                        id="progressBarGenerarRelato" 
                        class="progress-bar progress-bar-striped progress-bar-animated" 
                        role="progressbar" 
                        aria-valuenow="100" 
                        aria-valuemin="0" 
                        aria-valuemax="100" 
                        style="width: 0%">0%</div>
                </div>
            </div>
	
            <!-- Area para los mensajes de la generación de relato. -->
            <!-- Aquí se muestra tanto los de error como el de generado con éxito -->
            <div class="areaMensajes">
                <p id="mensajeGeneradorRelatos"></p>
            </div>

        </div>

        <div class="modal-footer">
            <button 
                type="button" 
                class="btn btn-outline outlinePurple cancelar" 
                data-bs-dismiss="modal">Cancelar</button>
            
            <button 
                type="button" 
                class="btn btn-outline outlinePurple aceptar" 
                data-bs-dismiss="modal">Aceptar</button>        
        </div>
    </div>
    </div>
</div>	



<!-- ------------------------------------------------------------------------------------------
CODIGO JAVASCRIPT
------------------------------------------------------------------------------------------- -->

<script type="text/javascript">

// definición de todas las funciones ajax cuando el documento está preparado
$(document).ready(function() {

    // --------------------------------------------------------------------------------
    // FUNCIONES DEL MODAL
    // --------------------------------------------------------------------------------

    // función relativa al modal que genera un relato
    // se crea la función que ejecuta la llamada ajax para obtener el estado del proceso
    function getStatus(idRelato) {

        // se hace la llamada ajax para actualizar la barra de progreso
        $.post("/relatosapp/relatos.php", 

            // Se definen los parámetros
            {
                accion:"obtenerEstadoProceso", 
                id:idRelato
            },
            
            // El resultado final del generador de relatos ha llegado en data
            function(data, status) {

                // se transforma el valor json recibido en el valor entero
                intEstado = parseInt(data);

                // se crea el estdo del proceso con el símbolo %
                estadoProceso = intEstado+'%';

                // se actualiza la barra de estado del proceso
                $("div#progressBarGenerarRelato").html(estadoProceso);
                $("div#progressBarGenerarRelato").css('width', estadoProceso);

                // si aún no se ha llegado al 100% del proceso se vuelve a ejecutar
                // si se produce un error se queda a la espera pero no vuelve a cargar
                // otra vez la función recursivamente
                if (intEstado < 100) getStatus(idRelato);

            }
        );  

    }



    // --------------------------------------------------------------------------------
    // EVENTOS DEL MODAL
    // --------------------------------------------------------------------------------

    // evento que abre el modal para generar un relato
    // ------------------------------------------------------------------------------
    $("table#tablaRelatos").on("click", "td.generarRelato", function() {

        // se obtiene el id y nombre del relato
        var idRelato = $(this).parent().data("id");
        var tituloRelato = $(this).parent().data("titulo");

        // se guarda el id y nombre del relato en el hidden para después poder recuperarlo
        $("input#idRelato").val(idRelato);

        // se abre el modal
        $('#modalGenerarRelato').modal('show');

        // se actualiza el título del modal con el título del relato
        $("#modalGenerarRelatoLabel span").html(tituloRelato);

        // se oculta el botón aceptar y se muestra el cancelar
        $('#modalGenerarRelato button.aceptar').hide();
        $('#modalGenerarRelato button.cancelar').show();

        // se pone a cero la barra de progreso              
        $("div#progressBarGenerarRelato").html('0%');
        $("div#progressBarGenerarRelato").css('width', '0%');

        // se limpian los mensajes del modal
        $("#mensajeGeneradorRelatos").html('');             

    }); 


    // evento que pone en marcha el proceso para generar un relato
    // ------------------------------------------------------------------------------
    $("button#generarRelato").click(function() {

        // se obtiene el id del relato que se va a generar
        var idRelato = $("input#idRelato").val();

        // se van al realizar dos llamadas AJAX:
        // 1.- se hace la llamada ajax para generar el relato
        // 2.- se hace la llamada ajax para actualizar la barra de progreso

        // 1. se hace la llamada ajax para generar el relato
        $.post("/relatosapp/relatos.php", 

            // Se definen los parámetros
            {
                accion:"generarRelato", 
                id:idRelato
            },
            
            // El resultado final del generador de relatos ha llegado en data
            function(data, status) {

                // se transforma el valor json recibido en array
                arrMensaje = JSON.parse(data);

                // se actualiza el mensaje recibo en el modal
                $("#mensajeGeneradorRelatos").html(arrMensaje[0]+': '+arrMensaje[1]);

                // se oculta el botón cancelar y se muestra el botón aceptar
                $('#modalGenerarRelato button.cancelar').hide();
                $('#modalGenerarRelato button.aceptar').show();

                // se actualiza la clase del icono que permitirá visualizar el relato
                $("tr.datosTabla[data-id='"+idRelato+"']")
                    .children('td.generarVisualizar')
                    .attr('class', 'generarVisualizar abrirVisualizador');
                
                // se actualiza el icono de la tabla para poder visualizar
                $("tr.datosTabla[data-id='"+idRelato+"']")
                    .children('td.generarVisualizar')
                    .children().children()
                    .attr('class', 'bi bi-caret-right-square-fill');

            }
        );

        // 2. se hace la llamada ajax para actualizar la barra de progreso
        getStatus(idRelato);

    }); 

});

</script>				