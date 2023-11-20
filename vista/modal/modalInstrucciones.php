<!-- ------------------------------------------------------------------------------------------
MODAL modalInstrucciones                                                    
Modal para mostrar la lista de instrucciones                                    
------------------------------------------------------------------------------------------- -->
		
<div 
	id="modalInstrucciones"
	class="modal fade" 
	tabindex="-1" 
	aria-labelledby="modalInstruccionesLabel" 
	aria-hidden="true">
	
	<div class="modal-dialog">
	<div class="modal-content">
   
	   <!-- Cabecera del modal -->
	   <div class="modal-header">

		   	<h5 class="modal-title" id="modalInstruccionesLabel">Instrucciones</h5>

		    <button 
		    	type="button" 
		    	class="btn-close" 
		    	data-bs-dismiss="modal" 
		    	aria-label="Close"></button>

		</div>
   
		<!-- Cuerpo del modal -->
   		<div class="modal-body">

			<!-- input para buscar una instrucción -->
			<div class="input-group areaInput">

				<input 
					id="operacionInstruccion" 
					type="text" 
					class="form-control" 
					placeholder="Operación" 
					aria-label="Operación">

				<input 
					id="descripcionInstruccion" 
					type="text" 
					class="form-control" 
					placeholder="Descripción" 
					aria-label="Descripción">

				<button 
					id="buscarInstrucciones" 
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
				<span id="numInstrucciones"></span> de 
				<span id="totalInstrucciones"></span></p>

			<!-- Tarjetas de instrucciones -->
			<div id="areaTarjetasInstrucciones"></div>

	  	</div>

	  	<!-- Pie del modal -->
   		<div class="modal-footer">
   			<button 
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



	// --------------------------------------------------------------------------------
	// FUNCIONES DEL MODAL
	// --------------------------------------------------------------------------------

	// función privada que actualiza la tabla de instrucciones
 	// ------------------------------------------------------------------------------
	function refrescarTarjetasModalInstrucciones(
		numInstrucciones, 
		totalInstrucciones, 
		arrInstrucciones) {

		// se limpia la lista de instrucciones
		$("div.tarjetaInstruccion").remove();

		// se recorre el  array de instrucciones
		arrInstrucciones.forEach(function(instruccion) {

			// se añade una nueva tarjeta
			$("div#areaTarjetasInstrucciones").append(

				'<div class="card border-dark mb-3 tarjetaInstruccion">'+
					'<div class="card-header cabeceraInstruccion">Instrucción ['+instruccion.id+'/'+totalInstrucciones+']</div>'+
			   		'<div class="card-body text-dark">'+
			   			'<h5 class="card-title"><b>'+instruccion.operacion+'</b></h5>'+
			    		'<p class="card-text">'+instruccion.descripcion+'</p>'+
			  		'</div>'+
				'</div>'
			);
		});    	

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// --------------------------------------------------------------------------------

 	// Abre el modal para mostrar lista de instrucciones
 	// ------------------------------------------------------------------------------
	$("button#abrirInstrucciones").click(function() {

		// se abre el modal
		$('#modalInstrucciones').modal('show');

		// se hace la llamada ajax para solicitar la lista de todas las instrucciones
 		$.post("/relatosapp/parrafo.php", 

 			// Se definen los parámetros
 			{
 				accion:"abrirInstrucciones"
 			},
 			
 			// El resultado se recoge en el data
 			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrInstrucciones = JSON.parse(data);

				// se refresca la cantidad de instrucciones
				$("span#numInstrucciones").html(arrInstrucciones[1].length);    

				// se refresca la cantidad total de instrucciones
				$("span#totalInstrucciones").html(arrInstrucciones[0]);

				// se obtienen los parámetros
				numInstrucciones = arrInstrucciones[1].length;
				totalInstrucciones = arrInstrucciones[0];
				arrInstrucciones = arrInstrucciones[1];

    			// se refresca la tabla del modal
    			refrescarTarjetasModalInstrucciones(
    				numInstrucciones, 
    				totalInstrucciones, 
    				arrInstrucciones);

			}
		);	

 	});	


	// Evento buscarInstrucciones:
	// Busca instrucciones según el filtro
 	// ------------------------------------------------------------------------------
 	$("button#buscarInstrucciones").click(function() {

		// se obtiene la operación y descripción para el filtro de instrucciones
		var operacion = $("input#operacionInstruccion").val();
		var descripcion = $("input#descripcionInstruccion").val();

 		// se hace la llamada ajax para obtener la lista de marcas
 		$.post("/relatosapp/parrafo.php", 

 			// Se definen los parámetros
 			{
 				accion:"buscarInstrucciones", 
 				operacion:operacion,
 				descripcion:descripcion
 			},
 			
 			// El resultado ha llegado en data
 			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrInstrucciones = JSON.parse(data);

				// se refresca la cantidad de instrucciones recibidas
				$("span#numInstrucciones").html(arrInstrucciones[1].length);    

				// se refresca la cantidad total de instrucciones existentes
				$("span#totalInstrucciones").html(arrInstrucciones[0]);

				// se obtienen los parámetros
				numInstrucciones = arrInstrucciones[1].length;
				totalInstrucciones = arrInstrucciones[0];
				arrInstrucciones = arrInstrucciones[1];

    			// se refresca la tabla del modal
    			refrescarTarjetasModalInstrucciones(
    				numInstrucciones, 
    				totalInstrucciones, 
    				arrInstrucciones);

			}
		);

	}); 

}); 

</script>
