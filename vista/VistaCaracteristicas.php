<!-- ------------------------------------------------------------------------------------------
VistaCaracteristicas
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
	<div class="tituloPagina"><h1>Características de los personajes</h1></div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaCaracteristicas'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del registro a editar -->
		<input type="hidden" id="idCaracteristica" name="idCaracteristica" value="0"> 

		<!-- input para introducir un registro -->
		<div class="input-group">
			
			<!-- se añade el input de búsqueda de características -->
			<input 
				id="nombreCaracteristica" 
				type="text" 
				class="form-control" 
				placeholder="Característica" 
				aria-label="Nombre de la característica">

			<!-- se añade el botón para buscar características -->
			<button 
				id="buscarCaracteristicas" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"
						data-placement="top"
                		data-bs-toggle="tooltip"
                		data-bs-html="true"  
                		title="<em>Buscar características</em>"></i></span></button>
			
			<!-- se añade el botón para crear nuevo registro -->
			<button 
				id="botonCrear" 
				class="btn btn-outline outlinePurple" 
				type="button">
					<span>
						<i 
							class="bi bi-plus-circle" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
                			data-bs-toggle="tooltip"
                			data-bs-html="true"  
                			title="<em>Crear característica</em>"></i></span></button>

			<!-- se añade el botón para actualizar registro -->
			<button 
				id="botonActualizar" 
				class="btn btn-outline outlinePurple" 
				type="button">
					<span>
						<i 
						class="bi bi-arrow-up-right-circle" 
						style="font-size:1.5rem; color:purple;"
	                	data-placement="top"
		                data-bs-toggle="tooltip"
	    	            data-bs-html="true"  
	        	        title="<em>Actualizar característica</em>"></i></span></button>
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrCaracteristicas); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table 
			id="tablaCaracteristicas" 
			class="table table-dark table-striped">

			<thead>
		    	<tr>
		        	<th scope="col" style="width: 95%;">Características</th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>

		    <tbody id="cuerpoTabla">
		    	
		    	<?php foreach ($arrCaracteristicas as $caracteristica) { ?>
		    		
			    	<tr 
			    		class="datosTabla"
			        	data-id="<?php echo $caracteristica->getId(); ?>"
			        	data-nombre="<?php echo $caracteristica->getNombre(); ?>">
			        	
			        	<td class="nombreCaracteristica datosCelda">
			        		<?php echo $caracteristica->getNombre(); ?>
			        	</td>
			        	
			        	<td class="eliminarCaracteristica">
			        		<span>
			        			<i 
			        				class="bi bi-trash-fill" 
			        				style="font-size:1rem; color:darkorange;"
                					data-placement="top"
                					data-bs-toggle="tooltip"
                					data-bs-html="true"  
                					title="<em>Eliminar característica</em>"></i></span>
			        	</td>

			      	</tr>

		    	<?php } ?>
				
		    </tbody>

		</table>

	</div>


	<!-- pie de la aplicación -->
	<?php require 'base/pie.php' ?>


	<!-- -----------------------------------------------------------------------------------
	INSTANCIACION DE LOS MODAL
	------------------------------------------------------------------------------------ -->

	<!-- modal para eliminar registros -->
	<?php require 'modal/modalEliminar.php' ?>		

	<!-- modal para mostrar errores al crear o actualizar registros -->
	<?php require 'modal/modalRegistroErroneo.php' ?>		

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

	// se oculta el botón actualizar
	$("button#botonActualizar").hide();



	// --------------------------------------------------------------------------------
	// FUNCIONES DE LA VISTA
	// --------------------------------------------------------------------------------

	// función privada que actualiza el número de registros de la tabla
	// ------------------------------------------------------------------------------
	function refrescarNumRegistros(numRegistros) {

		// se actualiza el número actual de registros de la tabla
		$("span#numRegistros").html(numRegistros);

	}


	// función privada que actualiza el total de registros de la tabla
	// ------------------------------------------------------------------------------
	function refrescarTotalRegistros(numRegistros) {

		// se actualiza el total de registros
		$("span#totalRegistros").html(numRegistros);

	}


	// función privada que actualiza la tabla con los registros recibidos por AJAX
	// ------------------------------------------------------------------------------
	function refrescarTabla(arrCaracteristicas) {

		// se limpia la lista de registros
		$("#tablaCaracteristicas tbody tr").remove();

		// se recorre el array de guiones
		arrCaracteristicas.forEach(function(caracteristica) {

			// se añade el guión a la tabla
			$("#tablaCaracteristicas tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-id="'+caracteristica.id+'" '+
		        	'data-nombre="'+caracteristica.nombre+'"> '+

		        	'<td class="nombreCaracteristica datosCelda"> '+
		        		caracteristica.nombre+
		        	'</td> '+

		        	'<td class="eliminarCaracteristica"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-trash-fill" '+
		        				'style="font-size:1rem; color:darkorange;" '+
            					'data-placement="top" '+
            					'data-bs-toggle="tooltip" '+
            					'data-bs-html="true" '+
            					'title="<em>Eliminar característica</em>"></i></span> '+
		        	'</td> '+
		      	'</tr>'

			);

		});

		// se inicializan los tooltips
		var tooltipTriggerList = [].slice
			.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});
		
	}


	// función relativa al modal modalRegistroErroneo
	// función que asigna todos los tipos de errores en que se ha incurrido a la hora de
	// crear o actualizar un registro
	// ------------------------------------------------------------------------------
	function asignarErrores(nombreCaracteristica) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay nombre de característica se añade el mensaje de error
		if (nombreCaracteristica.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nombre está vacío<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(nombreCaracteristica) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar la característica <b>'+nombreCaracteristica+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// ------------------------------------------------------------------------------
	// EVENTOS DEL MODAL
	// ------------------------------------------------------------------------------

	// evento que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarCaracteristicas").click(function() {

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el nombre de la característica
		var nombreCaracteristica = $("input#nombreCaracteristica").val();

		// se hace la llamada ajax para obtener la lista de características
		$.post("/relatosapp/caracteristicas.php", 

			// Se definen los parámetros
			{
				accion:"buscarCaracteristicas", 
				nombre:nombreCaracteristica
			},
			
			// El resultado ha llegado en data
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrData = JSON.parse(data);

				// se refresca la cantidad de registros enviados
				refrescarNumRegistros(arrData[1].length);

				// se refresca la cantidad total de registros
				refrescarTotalRegistros(arrData[0]);

				// se refresca la tabla
				refrescarTabla(arrData[1]);

  			}
  		);

	}); 


	// evento para editar un registro
	// ------------------------------------------------------------------------------
	$("table#tablaCaracteristicas").on("click", "td.datosCelda", function() {

		// se obtiene el id y nombre de la característica pulsada
		idCaracteristica = $(this).parent().data("id");
		nombreCaracteristica = $(this).parent().data("nombre");

		// se guarda el id en el hidden de la característica editada
		$("input#idCaracteristica").val(idCaracteristica);

		// se muestra el nombre de la característica en el input
		$("input#nombreCaracteristica").val(nombreCaracteristica);

		// se muestra el botón para permitir actualizar
		$("button#botonActualizar").show();

	});


	// evento que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el nombre de la característica
		var nombreCaracteristica = $("input#nombreCaracteristica").val();

		// si el nombre de la característica no está vacío se continúa
		if (nombreCaracteristica.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de características
    		$.post("/relatosapp/caracteristicas.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearCaracteristica",
    				nombre:nombreCaracteristica
    			},
    			
    			// El resultado ha llegado en data
    			function(data, status) {

      				// se transforma el valor json recibido en array
	    			arrData = JSON.parse(data);

    				// se refresca la cantidad de registros enviados
    				refrescarNumRegistros(arrData[1].length);

    				// se refresca la cantidad total de registros
    				refrescarTotalRegistros(arrData[0]);

    				// se refresca la tabla
    				refrescarTabla(arrData[1]);

      			}

      		);

    	// si el nombre de la característica está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(nombreCaracteristica);
		}

	});


	// evento que actualiza un registro
	// ------------------------------------------------------------------------------
	$("button#botonActualizar").click(function() {

		// se obtiene el id y nombre de la característica
		var idCaracteristica = $("input#idCaracteristica").val();
		var nombreCaracteristica = $("input#nombreCaracteristica").val();

		// si el nombre de la característica no está vacío se continúa
		if (nombreCaracteristica.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de características
    		$.post("/relatosapp/caracteristicas.php", 

    			// Se definen los parámetros
    			{
    				accion:"actualizarCaracteristica",
    				id:idCaracteristica,
    				nombre:nombreCaracteristica
    			},
    			
    			// El resultado ha llegado en data
    			function(data, status) {

      				// se transforma el valor json recibido en array
	    			arrData = JSON.parse(data);

    				// se refresca la cantidad de registros enviados
    				refrescarNumRegistros(arrData[1].length);

    				// se refresca la cantidad total de registros
    				refrescarTotalRegistros(arrData[0]);

    				// se refresca la tabla
    				refrescarTabla(arrData[1]);
      			
      			}
      		
      		);

    	// si el nombre de la característica está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se solicita asignar al modal todos los errores en que se ha incurrido
			asignarErrores(nombreCaracteristica);

		}			          		

	});


	// evento que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
	$("table#tablaCaracteristicas").on("click", "td.eliminarCaracteristica", function() {

		// se obtiene el id y nombre de la característica
		var idCaracteristica = $(this).parent().data("id");
		var nombreCaracteristica = $(this).parent().data("nombre");

		// se actualiza el id de la característica editada
		$("input#idCaracteristica").val(idCaracteristica);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(nombreCaracteristica);

	});		


	// evento que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id de la característica
		var idCaracteristica = $("input#idCaracteristica").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/caracteristicas.php", 

			// Se definen los parámetros
			{
				accion:"eliminarCaracteristica",
				id:idCaracteristica
			},
			
			// El resultado se recoge en el data
			// data[0] -> total de registros
			// data[1] -> array de registros
			function(data, status) {

  				// se transforma el valor json recibido en array
    			arrData = JSON.parse(data);

				// se refresca la cantidad de registros enviados
				refrescarNumRegistros(arrData[1].length);

				// se refresca la cantidad total de registros
				refrescarTotalRegistros(arrData[0]);

				// se refresca la tabla
				refrescarTabla(arrData[1]);
  			
  			}
  		
  		);						

	});

}); 

</script>

</body>
</html>