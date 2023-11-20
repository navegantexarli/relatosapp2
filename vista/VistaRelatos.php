<!-- ------------------------------------------------------------------------------------------
VistaRelatos
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
	<div class="tituloPagina"><h1>Relatos</h1></div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaRelatos'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del registro a editar -->
		<input type="hidden" id="idRelato" name="idRelato" value="0">

		<!-- input para introducir un registro -->
		<div class="input-group">
			
			<input 
				id="tituloRelato" 
				type="text" 
				class="form-control" 
				placeholder="Relato" 
				aria-label="Titulo del relato">

			<button 
				id="buscarRelatos" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"
		    			data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Buscar relatos</em>"></i></span></button>

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
						title="<em>Crear relatos</em>"></i></span></button>

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
						title="<em>Actualizar relatos</em>"></i></span></button>				
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrRelatos); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table 
			id="tablaRelatos" 
			class="table table-dark table-striped">
			
			<thead>
		    	<tr>
		        	<th scope="col" style="width: 45%;">Relatos</th>
			        <th scope="col" style="width: 45%;">Guiones asignados</th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>
		    
		    <tbody id="cuerpoTabla">
		    
		    	<?php foreach ($arrRelatos as $relato) { ?>
		    	
			    	<!-- se coprueba si el relato tiene guión asignado -->
			    	<?php $guionAsignado = ($relato->getIdGuion() > 0) ? true : false; ?>
				    
				    	<tr 
				    		class="datosTabla"
				        	data-id="<?php echo $relato->getId(); ?>"
				        	data-titulo="<?php echo $relato->getTitulo(); ?>"
				        	data-id_guion="<?php echo $relato->getIdGuion(); ?>"
				        	data-titulo_guion="<?php echo $relato->getTituloGuion(); ?>">
				        	
				        	<td class="tituloRelato datosCelda">
				        		<?php echo $relato->getTitulo(); ?>
				        	</td>			        	
				        		
		        		<!-- si el guión ya está asignado se indica su nombre -->
		        		<?php if ($guionAsignado) { ?>

							<td class="tituloGuion guionAsignado">
							
							<?php echo $relato->getTituloGuion(); ?>
			        		
			        		<!-- si el guión no está asignado se muestra el icono -->
			        		<!-- para asignar un guión al relato -->
			        	<?php } else { ?>

			        		<td class="tituloGuion abrirGuiones">
						
								<span>
			        				<i 
			        					class="bi bi-journal-bookmark-fill" 
			        					style="font-size:1rem; color:#EF8B18;"
			        					data-placement="top"
										data-bs-toggle="tooltip"
										data-bs-html="true" 
										title="<em>Asignar guión</em>"></i></span>
			        	<?php } ?>

				        	</td>

				        	<td class="eliminarRelato">
				        		<span>
				        			<i 
				        				class="bi bi-trash-fill" 
				        				style="font-size:1rem; color:#EF8B18;"></i></span>
				        	</td>

						<!-- si el relato ya está generado se dará la opción de --> 
						<!-- visualizarlo -->
		        		<?php if ($relato->getGenerado() == 100) { ?>

							<td class="generarVisualizar abrirVisualizador">
								<span>
			        				<i 
			        					class="bi bi-caret-right-square-fill" 
			        					style="font-size:1rem; color:#EF8B18;"
			        					data-placement="top" 	        					
										data-bs-toggle="tooltip" 
										data-bs-html="true" 
										title="<em>Ver relato</em>"></i></span>											        		
			        	<!-- si el guión no está asignado se muestra el icono para -->
			        	<!-- asignar un guión al relato -->
			        	<?php } else { ?>

							<td class="generarVisualizar generarRelato">
					       		<span>
				        			<i 
				        				class="bi bi-gear-fill" 
				        				style="font-size:1rem; color:#EF8B18;"
				        				data-placement="top" 	        					
										data-bs-toggle="tooltip" 
										data-bs-html="true" 
										title="<em>Generar relato</em>"></i></span>
			        	<?php } ?>

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

	<!-- modal para seleccionar un guión para asignar al relato -->
	<?php require 'modal/modalGuionesSeleccionables.php' ?>	

	<!-- modal para generar el relato -->
	<?php require 'modal/modalGenerarRelato.php' ?>	

</div>



<!-- --------------------------------------------------------------------------------------
CODIGO JAVASCRIPT
--------------------------------------------------------------------------------------- -->

<script type="text/javascript">

// definición de todas las funciones ajax cuando el documento está preparado
$(document).ready(function() {


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
	function refrescarTabla(arrRelatos) { 

		// se limpia la lista de relatos
		$("#tablaRelatos tbody tr").remove();

		// se recorre el array de relatos
		arrRelatos.forEach(function(relato) {

			// se inicializa la celda relativa al guión del relato
			strCeldaGuion = '';

			//si el guión está asignado se indica su título
			if (relato.idGuion > 0) {

				// se crea la celda con el título dle guión
				strCeldaGuion = 
					'<td class="tituloGuion guionAsignado">'+
						relato.tituloGuion+
					'</td>';

			// si el guión no está asignado se muestra el icono para asignar guión
			} else {

				//  se crea el icono para asignar guión
				strCeldaGuion = 
	        		'<td class="tituloGuion abrirGuiones"> '+
						'<span > '+
	        				'<i '+
	        					'class="bi bi-journal-bookmark-fill" '+
	        					'style="font-size:1rem; color:#EF8B18;" '+
	        					'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Asignar guión</em>"></i></span> '+
		        	'</td>';
		    }


			// se inicializa la celda relativa a la visualización del relato
			strCeldaVisualizacion = '';

			// si el relato ya está generado se dará la opción de visualizarlo
			if (relato.generado == 100) {

				// se crea la celda con el icono para visualizar
	    		strCeldaVisualizacion = 
					'<td class="generarVisualizar abrirVisualizador"> '+
						'<span> '+
	        				'<i '+
	        					'class="bi bi-caret-right-square-fill" '+
	        					'style="font-size:1rem; color:#EF8B18;" '+
	        					'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Ver relato</em>"></i></span> '+							
	        		'</td>';

	    	// si el relato aún no está generado se da la opción de generarlo
	    	} else {

	    		// se crea la celda con el icono para generar relato
				strCeldaVisualizacion = 
					'<td class="generarVisualizar generarRelato"> '+
		        		'<span> '+
	        				'<i '+
	        					'class="bi bi-gear-fill" '+
	        					'style="font-size:1rem; color:#EF8B18;" '+
	        					'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Generar relato</em>"></i></span>'+
	    			'</td>';
	    	}
	    		
	    		
			// se añade el guión a la tabla
			$("#tablaRelatos tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-id="'+relato.id+'" '+
		        	'data-titulo="'+relato.titulo+'" '+
		        	'data-id_guion="'+relato.idGuion+'" '+
		        	'data-titulo_guion="'+relato.tituloGuion+'"> '+
		        	
		        	'<td class="tituloRelato datosCelda"> '+
		        		relato.titulo+
		        	'</td> '+
		        	
		        	strCeldaGuion+
		        	
		        	'<td class="eliminarRelato"> '+
		        		'<span> '+
		        			'<i class="bi bi-trash-fill" style="font-size:1rem; color:#EF8B18;"></i> '+
		        		'</span> '+
		        	'</td>'+
		        	
		        	strCeldaVisualizacion+
		        
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
	function asignarErrores(tituloRelato) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay titulo de relato se añade el mensaje de error
		if (tituloRelato.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Título está vacío<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(tituloRelato) {

			// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar el relato <b>'+tituloRelato+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarRelatos").click(function() {

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el titulo de la relato
		var tituloRelato = $("input#tituloRelato").val();

		// se hace la llamada ajax para obtener la lista de relatos
		$.post("/relatosapp/relatos.php", 

			// Se definen los parámetros
			{
				accion:"buscarRelatos", 
				titulo:tituloRelato
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
	$("table#tablaRelatos").on("click", "td.datosCelda", function() {

		// se obtiene el id y titulo de la relato pulsada
		idRelato = $(this).parent().data("id");
		tituloRelato = $(this).parent().data("titulo");

		// se guarda el id en el hidden de la relato editada
		$("input#idRelato").val(idRelato);

		// se muestra el titulo de la relato en el input
		$("input#tituloRelato").val(tituloRelato);

		// se muestra el botón para permitir actualizar
		$("button#botonActualizar").show();

	});


	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el titulo de la relato
		var tituloRelato = $("input#tituloRelato").val();

		// si el titulo de la relato no está vacío se continúa
		if (tituloRelato.trim().length > 0) {

			// se hace la llamada ajax para obtener la lista de relatos
			$.post("/relatosapp/relatos.php", 

				// Se definen los parámetros
				{
					accion:"crearRelato",
					titulo:tituloRelato
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

		// si el titulo de la relato está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(tituloRelato);
		}

	});


	// función que actualiza un registro
	// ------------------------------------------------------------------------------
	$("button#botonActualizar").click(function() {

		// se obtiene el id y titulo de la relato
		var idRelato = $("input#idRelato").val();
		var tituloRelato = $("input#tituloRelato").val();

		// si el titulo de la relato no está vacío se continúa
		if (tituloRelato.trim().length > 0) {

			// se hace la llamada ajax para obtener la lista de relatos
			$.post("/relatosapp/relatos.php", 

				// Se definen los parámetros
				{
					accion:"actualizarRelato",
					id:idRelato,
					titulo:tituloRelato
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

		// si el titulo de la relato está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se solicita asignar al modal todos los errores en que se ha incurrido
			asignarErrores(tituloRelato);

		}			          		

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
	$("table#tablaRelatos").on("click", "td.eliminarRelato", function() {

		// se obtiene el id y titulo de la relato
		var idRelato = $(this).parent().data("id");
		var tituloRelato = $(this).parent().data("titulo");

		// se actualiza el id de la relato editada
		$("input#idRelato").val(idRelato);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(tituloRelato);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {

		// se obtiene el id de la relato
		var idRelato = $("input#idRelato").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/relatos.php", 

			// Se definen los parámetros
			{
				accion:"eliminarRelato",
				id:idRelato
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


	// Evento: abrirVisualizador
	// Abre la visualización del relato
	// ------------------------------------------------------------------------------
	$("table#tablaRelatos").on("click", "td.abrirVisualizador", function() {

		// se obtiene el id del relato
		var idRelato = $(this).parent().data("id");					

		// se redirige a la página que gestiona la visualización del relato
		// url original: "/relatosapp/visualizador.php?idr="+idRelato;
		window.location.href = "/relatosapp/relato/"+idRelato+"/visualizador/";

	});

}); 

</script>

</body>
</html>