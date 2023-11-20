<!-- ------------------------------------------------------------------------------------------
MODAL modalGuionesSeleccionables                                                    
Modal para mostrar la lista de guiones seleccionables para el personaje
------------------------------------------------------------------------------------------- -->
		
<div 
	class="modal fade" 
	id="modalGuionesSeleccionables" 
	tabindex="-1" 
	aria-labelledby="modalGuionesSeleccionablesLabel" 
	aria-hidden="true">

  	<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
        	<h5 
        		class="modal-title" 
        		id="modalGuionesSeleccionablesLabel">Selecciona una guión</h5>

        	<button 
        		type="button" 
        		class="btn-close" 
        		data-bs-dismiss="modal" 
        		aria-label="Close"></button>

	    </div>
    
    	<div class="modal-body">

			<!-- input para buscar una guión -->
			<div class="input-group areaInput">
				<input 
					id="tituloGuionSeleccionable" 
					type="text" 
					class="form-control" 
					placeholder="Guión" 
					aria-label="Título del guión">

				<button 
					id="buscarGuionesSeleccionables" 
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
				<span id="numGuionesSeleccionables"></span> de 
				<span id="totalGuionesSeleccionables"></span>
			</p>

			<!-- tabla de guiones seleccionables -->
			<table 
				id="tablaGuionesSeleccionables" 
				class="table table-dark table-striped">
				
				<thead>
			    	<tr><th scope="col">Guiones</th></tr>
			    </thead>
			    
			    <tbody id="cuerpoTabla">
			    	<tr class="datosTablaGuionesSeleccionables"
			    		data-id=""
			    		data-titulo="">
			        	<td class="tituloGuion"></td></tr>
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

	// función privada que actualiza la tabla de guiones seleccionables del modal
	// ------------------------------------------------------------------------------
	function refrescarTablaModalGuiones(arrGuiones) {

		// se limpia la lista de guiones seleccionables
		$("#tablaGuionesSeleccionables tbody tr").remove();

		// se recorre el array de guiones
		arrGuiones.forEach(function(guion) {

			// se añade el guión a la tabla
			$("#tablaGuionesSeleccionables tbody").append(

		    	'<tr class="datosTablaGuionesSeleccionables" '+
		    		'data-id="'+guion.id+'" '+
		    		'data-titulo="'+guion.titulo+'"> '+
		        	'<td class="tituloGuion">'+guion.titulo+'</td> '+
		        '</tr>'
			);

		});

	}



	// ------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// ------------------------------------------------------------------------------

	// función que abre el modal para asignar guion al personaje
	// ------------------------------------------------------------------------------
	$("table#tablaRelatos").on("click", "td.abrirGuiones", function() {

		// se obtiene el id del relato
		var idRelato = $(this).parent().data("id");

		// se guarda el id del relato en el hidden para después poder recuperarlo
		$("input#idRelato").val(idRelato);

		// se abre el modal
		$('#modalGuionesSeleccionables').modal('show');

		// se hace la llamada ajax para solicitar la lista de todos los guiones
		$.post("/relatosapp/guiones.php", 

			// Se definen los parámetros
			{
				accion:"buscarGuiones",
				titulo:""
			},
			
			// El resultado se recoge en el data
			function(data, status) {

					// se transforma el valor json recibido en array
				arrGuiones = JSON.parse(data);

				// se refresca la cantidad de guiones enviados
				$("span#numGuionesSeleccionables").html(arrGuiones[1].length);    

				// se refresca la cantidad total de guiones enviados
				$("span#totalGuionesSeleccionables").html(arrGuiones[0]);

				// se refresca la tabla del modal
				refrescarTablaModalGuiones(arrGuiones[1]);

			}

		);	

	});	


	// evento que busca guiones seleccionables según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarGuionesSeleccionables").click(function() {

		// se obtiene el titulo del guión seleccionable a buscar
		var tituloGuion = $("input#tituloGuionSeleccionable").val();

		// se hace la llamada ajax para obtener la lista de guiones
		$.post("/relatosapp/guiones.php", 

			// Se definen los parámetros
			{
				accion:"buscarGuiones", 
				titulo:tituloGuion
			},
			
			// El resultado ha llegado en data
			function(data, status) {

					// se transforma el valor json recibido en array
				arrGuiones = JSON.parse(data);

				// se refresca la cantidad de guiones enviadas
				$("span#numGuionesSeleccionables").html(arrGuiones[1].length);    

				// se refresca la cantidad total de guiones enviadas
				$("span#totalGuionesSeleccionables").html(arrGuiones[0]);

				// se refresca la tabla del modal
				refrescarTablaModalGuiones(arrGuiones[1]);

			}
		);
	}); 


	// evento que selecciona definitivamente un guion para asignarlo al personaje
	// ------------------------------------------------------------------------------
	$("table#tablaGuionesSeleccionables").on("click", "td.tituloGuion", function() {

		// se obtiene el id del guion seleccionado
		var idGuion = $(this).parent().data("id");
		var tituloGuion = $(this).parent().data("titulo");

		// se obtiene el id del relato
		idRelato = $("input#idRelato").val();

		// se cierra el modal 
		$('#modalGuionesSeleccionables').modal('hide');

		// se hace la llamada ajax para solicitar la asignación del guión al relato
		$.post("/relatosapp/relatos.php", 

			// Se definen los parámetros
			{
				accion:"asignarGuionRelato",
				idRelato:idRelato,
				idGuion:idGuion
			},
			
			// El resultado se recoge en el data
			function(data, status) {

					// si ha habido éxito se carga la fila
				if (data.localeCompare('OK') == 0) {

					// se actualiza el id y título de guión de la fila relativa al relato
					$("tr.datosTabla[data-id='"+idRelato+"']").data("id_guion", idGuion);
					$("tr.datosTabla[data-id='"+idRelato+"']").data("titulo_guion", tituloGuion);
					$("tr.datosTabla[data-id='"+idRelato+"'] td.abrirGuiones").text(tituloGuion);

					// se actualiza la clase para que ya no se pueda asignar de nuevo
			 		$("tr.datosTabla[data-id='"+idRelato+"'] td.abrirGuiones").removeClass('abrirGuiones').addClass('guionAsignado');
				
				} 
			}
		);	

	});	
	
});

</script>
				