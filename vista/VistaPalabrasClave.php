<!-- ------------------------------------------------------------------------------------------
VistaPalabrasClave
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

	<!-- título de la página -->
	<div class="tituloPagina">
		<h1>Palabras clave</h1>
	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaPalabrasClave'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del registro a editar -->
		<input type="hidden" id="idPalabraClave" name="idPalabraClave" value="0"> 

		<!-- input para introducir un registro -->
		<div class="input-group">
			
			<!-- input para buscar registros -->
			<input 
				id="nombrePalabraClave" 
				type="text" 
				class="form-control" 
				placeholder="Palabra clave" 
				aria-label="Nombre de la palabra clave">

			<button 
				id="buscarPalabrasClave" 
				class="btn btn-outline outlinePurple" 
				type="button">
  				<span>
  					<i 
  						class="bi-search" 
  						style="font-size:1.5rem; color:purple;"
			        	data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Buscar palabras clave</em>"></i></span></button>
					
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
						title="<em>Crear nueva palabra clave</em>"></i></span></button>

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
						title="<em>Actualizar palabra clave</em>"></i></span></button>				
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrPalabrasClave); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table id="tablaPalabrasClave" class="table table-dark table-striped">
		
			<thead>
		    	<tr>
		        	<th scope="col" style="width: 90%;">Palabras clave</th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>
		
		    <tbody id="cuerpoTabla">
		
			   	<?php foreach ($arrPalabrasClave as $palabraClave) { ?>
		    		
			    	<tr 
			    		class="datosTabla"
			        	data-id="<?php echo $palabraClave->getId(); ?>"
			        	data-nombre="<?php echo $palabraClave->getNombre(); ?>">
			        	<td class="nombrePalabraClave datosCelda">
			        		<?php echo $palabraClave->getNombre(); ?>
			        	</td>
			        	<td class="eliminarPalabraClave">
			        		<span>
			        			<i 
			        				class="bi bi-trash-fill" 
			        				style="font-size:1rem; color:#EF8B18;"
		        					data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Eliminar palabra clave</em>"></i></span>
			        	</td>
			        	
			        	<td class="abrirValorPC">
			        		<span>
			        			<i 
			        				class="bi bi-clipboard-check" 
			        				style="font-size:1rem; color:#EF8B18;"
			        				data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Abrir valores de la palabra clave</em>">
								</i></span>
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
	function refrescarTabla(arrPalabrasClave) {

		// se limpia la lista de palabras clave
		$("#tablaPalabrasClave tbody tr").remove();

		// se recorre el array de palabras clave
		arrPalabrasClave.forEach(function(palabraClave) {

			// se añade el guión a la tabla
			$("#tablaPalabrasClave tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-id="'+palabraClave.id+'" '+
		        	'data-nombre="'+palabraClave.nombre+'"> '+
		        	
		        	'<td class="nombrePalabraClave datosCelda"> '+
		        		palabraClave.nombre+
		        	'</td> '+
		        	
		        	'<td class="eliminarPalabraClave"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-trash-fill" '+
		        				'style="font-size:1rem; color:#EF8B18;" '+
	        					'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Eliminar palabra clave</em>"></i></span> '+
		        	'</td> '+
		        	
		        	'<td class="abrirValorPC"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-clipboard-check" '+
		        				'style="font-size:1rem; color:#EF8B18;" '+
		        				'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Abrir valores de la palabra clave</em>"> '+
	        				'</i></span> '+
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
	function asignarErrores(nombrePalabraClave) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay nombre de palabra clave se añade el mensaje de error
		if (nombrePalabraClave.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nombre está vacío<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(nombrePalabraClave) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar la palabra clave <b>'+nombrePalabraClave+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------
 
	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarPalabrasClave").click(function() {

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el nombre de la palabra clave
		var nombrePalabraClave = $("input#nombrePalabraClave").val();

		// se hace la llamada ajax para obtener la lista de palabras clave
		$.post("/relatosapp/palabrasclave.php", 

			// Se definen los parámetros
			{
				accion:"buscarPalabrasClave", 
				nombre:nombrePalabraClave
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


	// función para editar un registro
	// ------------------------------------------------------------------------------
	$("table#tablaPalabrasClave").on("click", "td.datosCelda", function() {

		// se obtiene el id y nombre de la palabra clave pulsada
		idPalabraClave = $(this).parent().data("id");
		nombrePalabraClave = $(this).parent().data("nombre");

		// se guarda el id en el hidden de la palabra clave editada
		$("input#idPalabraClave").val(idPalabraClave);

		// se muestra el nombre de la palabra clave en el input
		$("input#nombrePalabraClave").val(nombrePalabraClave);

		// se muestra el botón para permitir actualizar
		$("button#botonActualizar").show();

	});


 	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el nombre de la palabra clave
		var nombrePalabraClave = $("input#nombrePalabraClave").val();

		// si el nombre de la palabra clave no está vacío se continúa
		if (nombrePalabraClave.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de palabras clave
    		$.post("/relatosapp/palabrasclave.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearPalabraClave",
    				nombre:nombrePalabraClave
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

    	// si el nombre de la palabra clave está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(nombrePalabraClave);
		}

	});


	// función que actualiza un registro
	// ------------------------------------------------------------------------------
	$("button#botonActualizar").click(function() {

		// se obtiene el id y nombre de la palabra clave
		var idPalabraClave = $("input#idPalabraClave").val();
		var nombrePalabraClave = $("input#nombrePalabraClave").val();

		// si el nombre de la palabra clave no está vacío se continúa
		if (nombrePalabraClave.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de palabras clave
    		$.post("/relatosapp/palabrasclave.php", 

    			// Se definen los parámetros
    			{
    				accion:"actualizarPalabraClave",
    				id:idPalabraClave,
    				nombre:nombrePalabraClave
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

    	// si el nombre de la palabra clave está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se solicita asignar al modal todos los errores en que se ha incurrido
			asignarErrores(nombrePalabraClave);

		}			          		

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
		$("table#tablaPalabrasClave").on("click", "td.eliminarPalabraClave", function() {

		// se obtiene el id y nombre de la palabra clave
		var idPalabraClave = $(this).parent().data("id");
		var nombrePalabraClave = $(this).parent().data("nombre");

		// se actualiza el id de la palabra clave editada
		$("input#idPalabraClave").val(idPalabraClave);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(nombrePalabraClave);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id de la palabra clave
		var idPalabraClave = $("input#idPalabraClave").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/palabrasclave.php", 

			// Se definen los parámetros
			{
				accion:"eliminarPalabraClave",
				id:idPalabraClave
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


	// función que lanza la apertura de los valores de palabra clave
	// ------------------------------------------------------------------------------
	$("table#tablaPalabrasClave").on("click", "td.abrirValorPC", function() {

		// se obtiene el id de la palabra clave
		var idPalabraClave = $(this).parent().data("id");					

		// se redirige a la página que gestiona los valores de palabra clave
		// url original: /relatosapp/valorespalabraclave.php?idpc="+idPalabraClave;
		window.location.href = "/relatosapp/palabraclave/"+idPalabraClave+"/valores/";
	
	});			

}); 

</script>

</body>
</html>