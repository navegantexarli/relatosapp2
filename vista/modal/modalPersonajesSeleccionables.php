<!-- ------------------------------------------------------------------------------------------
MODAL modalPersonajesSeleccionables                                                    
Modal para mostrar la lista de personajes seleccionables para el personaje
------------------------------------------------------------------------------------------- -->
		
<div 
	class="modal fade" 
	id="modalPersonajesSeleccionables" 
	tabindex="-1" 
	aria-labelledby="modalPersonajesSeleccionablesLabel" 
	aria-hidden="true">

  	<div class="modal-dialog">
    <div class="modal-content">
    	
    	<div class="modal-header">
        	<h5 
        		class="modal-title" 
        		id="modalPersonajesSeleccionablesLabel">Selecciona un personaje</h5>

        	<button 
        		type="button" 
        		class="btn-close" 
        		data-bs-dismiss="modal" 
        		aria-label="Close"></button>
      
      	</div>

      	<div class="modal-body">

			<!-- input para buscar un personaje -->
			<div class="input-group areaInput">
				
				<input 
					id="nombreLargoPersonajeSeleccionable" 
					type="text" 
					class="form-control" 
					placeholder="Personaje" 
					aria-label="Nombre del personaje">

				<button 
					id="buscarPersonajesSeleccionables" 
					class="btn btn-outline outlinePurple" 
					type="button">
					<span>
						<i 
							class="bi-search" 
							style="font-size:1rem; color:purple;"></i></span></button>
			
			</div>

			<!-- Se indica el número de registros cargados y los que hay en total -->
			<p class="numRegistros">Mostrando 
				<span id="numPersonajesSeleccionables"></span> de 
				<span id="totalPersonajesSeleccionables"></span></p>

			<!-- tabla de personajes seleccionables -->
			<table id="tablaPersonajesSeleccionables" class="table table-dark table-striped">
				
				<thead>
			    	<tr><th scope="col">Personajes</th></tr>
			    </thead>
			    
			    <tbody id="cuerpoTabla">
			    	<tr 
			    		class="datosTablaPersonajesSeleccionables"
			    		data-id=""
			    		data-nombre_largo="">
			        	<td class="nombreLargoPersonaje"></td>
			      	</tr>				
			    </tbody>
			</table>

	    </div>

	    <div class="modal-footer">
	    	<button 
	    		type="button" 
	    		class="btn btn-outline outlinePurple" 
	    		data-bs-dismiss="modal">Cancelar</button>

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

	// función privada que actualiza la tabla de personajes seleccionables del modal
   	// ------------------------------------------------------------------------------
	function refrescarTablaModalPersonajes(arrPersonajes) {
		
		// se limpia la lista de personajes seleccionables
		$("#tablaPersonajesSeleccionables tbody tr").remove();

		// se recorre el array de personajes
		arrPersonajes.forEach(function(personaje) {

			// se añade el guión a la tabla
			$("#tablaPersonajesSeleccionables tbody").append(
	    	
		    	'<tr '+
		    		'class="datosTablaPersonajesSeleccionables" '+
		    		'data-id="'+personaje.id+'" '+
		    		'data-nombre_largo="'+personaje.nombreLargo+'"> '+
		        	'<td class="nombreLargoPersonaje">'+personaje.nombreLargo+'</td> '+
		      	'</tr>'
			
			);

		});	

	}



	// ------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// ------------------------------------------------------------------------------ 

	// evento modal que busca personajes seleccionables según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarPersonajesSeleccionables").click(function() {

		// se obtiene el nombre del personaje seleccionable a buscar
		var nombreLargoPersonaje = $("input#nombreLargoPersonajeSeleccionable").val();

		// se hace la llamada ajax para obtener la lista de personajes
		$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"buscarPersonajes", 
				nombre:"",
				nombreLargo:nombreLargoPersonaje,
				sexo:"",
				edad:""        				
			},
			
			// El resultado ha llegado en data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrPersonajes = JSON.parse(data);

				// se refresca la cantidad de personajes enviadas
				$("span#numPersonajesSeleccionables").html(arrPersonajes[1].length);    

				// se refresca la cantidad total de personajes enviados
				$("span#totalPersonajesSeleccionables").html(arrPersonajes[0]);

    			// se refresca la tabla del modal
				refrescarTablaModalPersonajes(arrPersonajes[1]);

  			}

  		);

	}); 


	// evento que abre el modal para asignar personaje2 al personaje
	// ------------------------------------------------------------------------------
	$("button#botonBuscarPersonaje2").click(function() {

		// se abre el modal
		$('#modalPersonajesSeleccionables').modal('show');

		// se hace la llamada ajax para solicitar la lista de todos los personajes
		$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"buscarPersonajes",
				nombre:"",
				nombreLargo:"",
				sexo:"",
				edad:""
			},
			
			// El resultado se recoge en el data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrPersonajes = JSON.parse(data);

				// se refresca la cantidad de personajes enviados
				$("span#numPersonajesSeleccionables").html(arrPersonajes[1].length);    

				// se refresca la cantidad total de personajes enviados
				$("span#totalPersonajesSeleccionables").html(arrPersonajes[0]);

    			// se refresca la tabla del modal
    			refrescarTablaModalPersonajes(arrPersonajes[1]);

  			}

  		);	

	});	


	// función que selecciona definitivamente un personaje para asignarlo al personaje
	// ------------------------------------------------------------------------------
	$("table#tablaPersonajesSeleccionables").on("click", "td.nombreLargoPersonaje", function() {

		// se obtiene el id y nombre largo del pesonaje seleccionado
		var idPersonaje2 = $(this).parent().data("id");
		var nombreLargoPersonaje = $(this).parent().data("nombre_largo");

		// se actualizan el id y nombre largo del personaje seleccionado
		$("input#idPersonaje2").val(idPersonaje2);
		$("input#nombreLargoP2C").val(nombreLargoPersonaje);

		// se cierra el modal 
		$('#modalPersonajesSeleccionables').modal('hide');

	});	

});

</script>				