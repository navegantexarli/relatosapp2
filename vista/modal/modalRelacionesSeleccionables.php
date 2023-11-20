<!-- ------------------------------------------------------------------------------------------
MODAL modalRelacionesSeleccionables                                                    
Modal para mostrar la lista de relaciones seleccionables para el personaje
------------------------------------------------------------------------------------------- -->
		
<div 
	class="modal fade" 
	id="modalRelacionesSeleccionables" 
	tabindex="-1" 
	aria-labelledby="modalRelacionesSeleccionablesLabel" 
	aria-hidden="true">

  	<div class="modal-dialog">
    <div class="modal-content">
    
    	<div class="modal-header">
        	
        	<h5 
        		class="modal-title" 
        		id="modalRelacionesSeleccionablesLabel">Selecciona una relación</h5>

        	<button 
        		type="button" 
        		class="btn-close" 
        		data-bs-dismiss="modal" 
        		aria-label="Close"></button>
      
      	</div>

      	<div class="modal-body">

			<!-- input para buscar una relación -->
			<div class="input-group areaInput">

				<input 
					id="nombreRelacionSeleccionable" 
					type="text" 
					class="form-control" 
					placeholder="Relación" 
					aria-label="Nombre de la relación">

				<button 
					id="buscarRelacionesSeleccionables" 
					class="btn btn-outline outlinePurple" 
					type="button">
					<span>
						<i 
							class="bi-search" 
							style="font-size:1rem; color:purple;"></i></span></button>

			</div>

			<!-- Se indica el número de registros cargados y los que hay en total -->
			<p class="numRegistros">Mostrando 
				<span id="numRelacionesSeleccionables"></span> de 
				<span id="totalRelacionesSeleccionables"></span></p>

			<!-- tabla de relaciones seleccionables -->
			<table 
				id="tablaRelacionesSeleccionables" 
				class="table table-dark table-striped">
				
				<thead>
			    	<tr><th scope="col">Relaciones</th></tr>
			    </thead>

			    <tbody id="cuerpoTabla">
			    	<tr 
			    		class="datosTablaRelacionesSeleccionables"
			    		data-id=""
			    		data-nombre="">
			        	<td class="nombreRelacion"></td>
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

	// función privada que actualiza la tabla de relaciones seleccionables dle modal
	// ------------------------------------------------------------------------------
	function refrescarTablaModalRelaciones(arrRelaciones) {
		
		// se limpia la lista de relaciones seleccionables
		$("#tablaRelacionesSeleccionables tbody tr").remove();

		// se recorre el array de relaciones
		arrRelaciones.forEach(function(relacion) {

			// se añade el guión a la tabla
			$("#tablaRelacionesSeleccionables tbody").append(

		    	'<tr '+
		    		'class="datosTablaRelacionesSeleccionables" '+
		    		'data-id="'+relacion.id+'" '+
		    		'data-nombre="'+relacion.nombre+'"> '+
		        	'<td class="nombreRelacion">'+relacion.nombre+'</td> '+
		      	'</tr>'

			);

		});			

	}



	// ------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// ------------------------------------------------------------------------------    	

	// función modal que busca relaciones seleccionables según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarRelacionesSeleccionables").click(function() {

		// se obtiene el nombre de la relación seleccionable a buscar
		var nombreRelacion = $("input#nombreRelacionSeleccionable").val();

		// se hace la llamada ajax para obtener la lista de relaciones
		$.post("/relatosapp/relaciones.php", 

			// Se definen los parámetros
			{
				accion:"buscarRelaciones", 
				nombre:nombreRelacion
			},
			
			// El resultado ha llegado en data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrRelaciones = JSON.parse(data);

				// se refresca la cantidad de relaciones enviadas
				$("span#numRelacionesSeleccionables").html(arrRelaciones[1].length);    

				// se refresca la cantidad total de relaciones enviadas
				$("span#totalRelacionesSeleccionables").html(arrRelaciones[0]);

    			// se refresca la tabla del modal
				refrescarTablaModalRelaciones(arrRelaciones[1]);

  			}

  		);

	}); 


	// función Modal:
	// función que abre el modal para asignar relación al personaje
	// ------------------------------------------------------------------------------
	$("button#botonBuscarRelacion").click(function() {

		// se abre el modal
		$('#modalRelacionesSeleccionables').modal('show');

		// se hace la llamada ajax para solicitar la lista de todas las relaciones
		$.post("/relatosapp/relaciones.php", 

			// Se definen los parámetros
			{
				accion:"buscarRelaciones",
				nombre:""
			},
			
			// El resultado se recoge en el data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrRelaciones = JSON.parse(data);

				// se refresca la cantidad de relaciones enviadas
				$("span#numRelacionesSeleccionables").html(arrRelaciones[1].length);    

				// se refresca la cantidad total de relaciones enviadas
				$("span#totalRelacionesSeleccionables").html(arrRelaciones[0]);

    			// se refresca la tabla del modal
    			refrescarTablaModalRelaciones(arrRelaciones[1]);

  			}

  		);	

	});	


	// función que selecciona definitivamente una relación para asignarla al personaje
	// ------------------------------------------------------------------------------
	$("table#tablaRelacionesSeleccionables").on("click", "td.nombreRelacion", function() {

		// se obtiene el id y nombre de la relación seleccionada
		var idRelacion = $(this).parent().data("id");
		var nombreRelacion = $(this).parent().data("nombre");

		// se actualizan el id y nombre de la relación 
		$("input#idRelacion").val(idRelacion);
		$("input#nombreRelacionC").val(nombreRelacion);

		// se cierra el modal 
		$('#modalRelacionesSeleccionables').modal('hide');

	});	


});				

</script>