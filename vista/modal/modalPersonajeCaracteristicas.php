<!-- ------------------------------------------------------------------------------------------
MODAL modalCaracteristicasPersonaje                                                    
Modal para gestionar las características del personaje                                   
------------------------------------------------------------------------------------------- -->
		
<div 
	class="modal fade" 
	id="modalPersonajeCaracteristicas" 
	tabindex="-1" 
	aria-labelledby="modalPersonajeCaracteristicasLabel" 
	aria-hidden="true">

  	<div class="modal-dialog">
    <div class="modal-content">
    	
    	<div class="modal-header">
        
        	<h5 
        		class="modal-title" 
        		id="modalPersonajeCaracteristicasLabel">Características del personaje</h5>

        	<button 
        		type="button" 
        		class="btn-close" 
        		data-bs-dismiss="modal" 
        		aria-label="Close"></button>

      	</div>

	    <div class="modal-body">

	      	<!-- se guarda el nivel máximo permitido para las características-->
	      	<input 
	      		id="nivelMax" 
	      		type="hidden" 
	      		name="nivelMax" 
	      		value="<?php echo self::nivelMax; ?>">

			<!-- tabla de características del personaje -->
			<table 
				id="tablaPCaracteristicas" 
				class="table table-dark table-striped">
				
				<thead>
			    	<tr>
			        	<th scope="col">Características</th>
				        <th scope="col">Niveles</th>
			    	</tr>
			    </thead>
			    
			    <tbody id="cuerpoTabla">
			    	<tr id="" class="datosTablaPCaracteristicas">
			        	<td class="nombreCaracteristica"></td>
			        	<td class="nivelCaracteristica">
							<div class="rangoMedio">
	  							<input 
	  								type="range" 
	  								class="form-range nivelCaracteristica" 
	  								min="1" 
	  								max="<?php echo self::nivelMax; ?>" 
	  								value="">
							</div>
			        	</td>
			      	</tr>				
			    </tbody>
			
			</table>

	    </div>

	    <div class="modal-footer">
	    
	      	<button 
	    		type="button" 
	          	class="btn btn-outline outlinePurple" 
	          	data-bs-dismiss="modal">Cancelar</button>
	        
	        <button 
	          id="actualizarCaracteristicas"
	          type="button" 
	          class="btn btn-outline outlinePurple" 
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

	// ------------------------------------------------------------------------------
	// FUNCIONES DEL MODAL
	// ------------------------------------------------------------------------------ 

	// función privada que actualiza la tabla de características del modal
	// ------------------------------------------------------------------------------
	function refrescarTablaModalCaracteristicas(arrPCaracteristicas) {

		// se limpia la lista de características del personaje
		$("#tablaPCaracteristicas tbody tr").remove();

		// se obtiene el nivel máximo de las características
		nivelMax = $("input#nivelMax").val();

		// se recorre el array de guiones
		arrPCaracteristicas.forEach(function(personajeCaracteristica) {

			// se añade la característica a la tabla
			$("#tablaPCaracteristicas tbody").append(

				'<tr id="'+personajeCaracteristica.id+'" class="datosTablaPCaracteristicas"> '+
		    		'<td class="nombreCaracteristica">'+personajeCaracteristica.nombre+'</td> '+
		    		'<td class="nivelCaracteristica"> '+
						'<div class="rangoMedio"> '+
							'<input '+
								'type="range" '+
								'class="form-range nivelCaracteristica" '+
								'min="1" '+
								'max="'+nivelMax+'" '+
								'value="'+personajeCaracteristica.nivel+'"> '+
						'</div> '+
		    		'</td> '+
		  		'</tr>'
			);

		});

	}



	// ------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// ------------------------------------------------------------------------------ 

	// función que abre el modal para gestionar las características del personaje
	// ------------------------------------------------------------------------------
	$("table#tablaPersonajes").on("click", "td.abrirPCaracteristicas", function() {

		// se abre el modal
		$('#modalPersonajeCaracteristicas').modal('show');

		// se obtiene el id y nombre largo del personaje
		var idPersonaje = $(this).parent().data("id");
		var nombreLargoPersonaje = $(this).parent().data("nombre_largo");

		// se actualiza el id del personaje editado
		$("input#idPersonaje").val(idPersonaje);

		// se hace la llamada ajax para solicitar las características del personaje
		$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"abrirCaracteristicas",
				id:idPersonaje
			},
			
			// El resultado se recoge en el data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrPCaracteristicas = JSON.parse(data);

    			// se refresca la tabla con las características del personaje
    			refrescarTablaModalCaracteristicas(arrPCaracteristicas);

  			}

  		);	

	});	


	// función Modal:
	// función que abre el modal para gestionar las características del personaje
	// ------------------------------------------------------------------------------
	$("button#actualizarCaracteristicas").click(function() {

		// se obtiene el id del personaje
		var idPersonaje = $("input#idPersonaje").val();

		// se crea el array de características del personaje
		var arrPCaracteristicas = [];

		// se recorre cada fila de la tabla del DOM
		$("#tablaPCaracteristicas tbody tr").each(function () {

			// se obtiene tanto el id como el nivel de la característica del personaje
			idPCaracteristica =  $(this).attr("id");
			nivelPCaracteristica = $(this).
				children("td.nivelCaracteristica").
				children().children().
				val();

			// se crea el array para guardar la pareja id y nivel de característica
			var arrPCaracteristica = [idPCaracteristica, nivelPCaracteristica];

			// se añade al array de características  al array con la pareja id y nivel
			arrPCaracteristicas.push(arrPCaracteristica);

		});

		// se hace la llamada ajax para solicitar la actualización de las características
    	$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"actualizarCaracteristicas",
				id:idPersonaje,
				arrPersonajeCaracteristicas:arrPCaracteristicas
			},
			
			// El resultado de esta operación es un ok o un error 
			function(data, status) {

				// si ha llegado un mensaje se error se indica con el modal

  			}
  			
  		);

	});

});

</script>
				