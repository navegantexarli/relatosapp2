<!-- ------------------------------------------------------------------------------------------
MODAL modalMarcas                                                    
Modal para mostrar la lista de párrafos marcados                                    
------------------------------------------------------------------------------------------- -->
		
<div 
	class="modal fade" 
	id="modalMarcas" 
	tabindex="-1" 
	aria-labelledby="modalMarcasLabel" 
	aria-hidden="true">

  	<div class="modal-dialog">
    <div class="modal-content">
    
    	<div class="modal-header">
        
        	<h5 class="modal-title" id="modalMarcasLabel">Selecciona una marca</h5>
        
        	<button 
        		type="button" 
        		class="btn-close" 
        		data-bs-dismiss="modal" 
        		aria-label="Close"></button>
     	</div>
      
      	<div class="modal-body">

			<!-- input para buscar una marca -->
			<div class="input-group areaInput">
				<input 
					id="idMarca" 
					type="text" 
					class="form-control" 
					placeholder="ID" 
					aria-label="ID de la marca">

				<input 
					id="textoMarca" 
					type="text" 
					class="form-control" 
					placeholder="Texto" 
					aria-label="Texto de la marca">

				<button 
					id="buscarMarcas" 
					class="btn btn-outline outlinePurple" 
					type="button">
					<span>
						<i 
							class="bi-search" 
							style="font-size:1rem; color:purple;"></i></span>
				</button>

			</div>

			<!-- Se indica el número de registros cargados y los que hay en total -->
			<p class="numRegistros">Mostrando 
				<span id="numMarcas"></span> de 
				<span id="totalMarcas"></span></p>

			<!-- tabla de marcas -->
			<table 
				id="tablaParrafosMarcados" 
				class="table table-dark table-striped">
				
				<thead>
			    	<tr>
			        	<th scope="col">ID</th>
			        	<th scope="col">Texto</th>
			        </tr>
			    </thead>
			    
			    <tbody id="cuerpoTabla">
			    	<tr class="datosTablaParrafosMarcados"
			    		data-id="">
			        	<td class="idMarca"></td>
			        	<td class="textoMarca"></td>
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

	// --------------------------------------------------------------------------------
	// FUNCIONES DEL MODAL
	// --------------------------------------------------------------------------------

	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	// ------------------------------------------------------------------------
	function crearCuerpoModalEliminacion(idParrafo) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar el párrafo <b>'+idParrafo+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}


	// función privada que actualiza la tabla de párrafos marcados del modal
	// ------------------------------------------------------------------------------
	function refrescarTablaModalMarcas(arrParrafosMarcados) {

		// se limpia la lista de marcas
		$("#tablaParrafosMarcados tbody tr").remove();

		// se recorre el array de párrafos marcados
		arrParrafosMarcados.forEach(function(parrafoMarcado) {

			// se añade el párrafo marcado a la tabla
			$("#tablaParrafosMarcados tbody").append(

		    	'<tr class="datosTablaParrafosMarcados" '+
		    		'data-id="'+parrafoMarcado.id+'"> '+
		        	'<td class="idMarca">'+parrafoMarcado.id+'</td> '+
		        	'<td class="textoMarca">'+parrafoMarcado.texto.slice(0, 40)+'</td> '+
		      	'</tr>'
			);
		});
	}



	// --------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// --------------------------------------------------------------------------------

	// evento que abre el modal para elegir una marca 
	// ------------------------------------------------------------------------------
	$("button#abrirMarcas").click(function() {

		// se obtiene el id del guión
		var idGuion = $("input#idGuion").val();

		// se abre el modal
		$('#modalMarcas').modal('show');

		// se hace la llamada ajax para solicitar la lista de todas las marcas
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"abrirParrafosMarcados",
				idGuion:idGuion
			},
			
			// El resultado se recoge en el data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrParrafosMarcados = JSON.parse(data);

				// se refresca la cantidad de parrafos marcados
				$("span#numMarcas").html(arrParrafosMarcados[1].length);    

				// se refresca la cantidad total de marcas
				$("span#totalMarcas").html(arrParrafosMarcados[0]);

    			// se refresca la tabla del modal
    			refrescarTablaModalMarcas(arrParrafosMarcados[1]);

  			}
  		
  		);	

	});	


	// evento que selecciona definitivamente una marca para abrir el párrafo correspondiente
	// ------------------------------------------------------------------------------
	$("table#tablaParrafosMarcados").on("click", "tr.datosTablaParrafosMarcados", function() {

		// se obtiene el id del guión y del párrafo que se desea abrir
		var idGuion = $("input#idGuion").val();

		// se obtiene el id del párrafo seleccionado
		var idParrafo = $(this).data("id");

		// se cierra el modal 
		$('#modalMarcas').modal('hide');
		
		// se lanza el evento abrirParrafo para que la vista del párrafo recupere el valor
		// de idGuion y de idParrafo para abrir el párrafo correspondiente
		$("button#abrirMarcas").trigger("abrirParrafo", [idGuion, idParrafo]);

	});	


	// evento modal que busca marcas según el filtro
	// ------------------------------------------------------------------------------
	$("button#buscarMarcas").click(function() {

		// se obtiene el id de guión y el id y texto de la marca a buscar
		var idGuion = $("input#idGuion").val();
		var idMarca = $("input#idMarca").val();
		var textoMarca = $("input#textoMarca").val();

		// se hace la llamada ajax para obtener la lista de marcas
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"buscarParrafosMarcados", 
				idGuion:idGuion,
				idParrafo:idMarca,
				textoParrafo:textoMarca
			},
			
			// El resultado ha llegado en data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrParrafosMarcados = JSON.parse(data);

				// se refresca la cantidad de personajes enviadas
				$("span#numMarcas").html(arrParrafosMarcados[1].length);    

				// se refresca la cantidad total de personajes enviados
				$("span#totalMarcas").html(arrParrafosMarcados[0]);

    			// se refresca la tabla del modal
				refrescarTablaModalMarcas(arrParrafosMarcados[1]);

  			}

  		);
  		
	}); 		

});				

</script>