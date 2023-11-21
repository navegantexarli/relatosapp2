<!-- ------------------------------------------------------------------------------------------
VistaGuiones
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

	<!-- se indica el título de la vista -->
	<div class="tituloPagina">
		<h1>Guiones</h1>
	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaGuiones'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del registro a editar -->
		<input type="hidden" id="idGuion" name="idGuion" value="0"> 

		<!-- input para introducir un registro -->
		<div class="input-group">
			
			<!-- input para introducir un registro -->			
			<input 
				id="tituloGuion" 
				type="text" 
				class="form-control" 
				placeholder="Guión" 
				aria-label="Título del guión">

			<!-- se añade el botón para buscar guiones -->
			<button 
				id="buscarGuiones" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"
		        		data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Buscar guiones</em>"></i></span></button>
			
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
						title="<em>Crear nuevo guión</em>"></i></span></button>

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
						title="<em>Actualizar guión</em>"></i></span></button>
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrGuiones); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table 
			id="tablaGuiones" 
			class="table table-dark table-striped">
			
			<thead>
		    	<tr>
		        	<th scope="col" style="width: 70%;">Guiones</th>
			        <th scope="col" style="width: 20%;" class="columnaCentrada">Profundidad</th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>
		    <tbody id="cuerpoTabla">
		    	
		    	<?php foreach ($arrGuiones as $guion) { ?>

		    		<!-- se obtiene el valor de refresco de modo que: -->
		    		<!-- 1: profundidad refrescada -->
		    		<!-- 0: profundidad no refrescada -->
		    		<?php $refrescada = strcmp($guion->getRefrescada(), 't'); ?>
		    		<?php $refrescada = ($refrescada == 0) ? 1 : 0; ?>
		    		
			    	<tr 
			    		class="datosTabla"
			        	data-id_guion="<?php echo $guion->getId(); ?>"
			        	data-id_parrafo_ini="<?php echo $guion->getIdParrafoIni(); ?>"
			        	data-titulo="<?php echo $guion->getTitulo(); ?>"
			        	data-refrescada="<?php echo $refrescada; ?>">
			        	
			        	<td class="tituloGuion datosCelda">
			        		<?php echo $guion->getTitulo(); ?>
			        	</td>
			        	
			        	<td class="asignarProfundidades columnaCentrada">
			        		
			        		<!-- si la profundidad del guión está refrescada se indica -->
			        		<?php if ($refrescada) { 

			        			echo $guion->getProfundidad(); ?>
			        		
			        			<!-- si la profundidad del guión no está referscada se -->
			        			<!-- muestra el icono para actualizar -->
			        		<?php } else { ?>
								
								<span >
			        				<i 
			        					class="bi bi-arrow-counterclockwise" 
			        					style="font-size:1rem; color:#EF8B18;"
			        					data-placement="top" 	        					
										data-bs-toggle="tooltip" 
										data-bs-html="true" 
										title="<em>Actualizar profundidad de guión</em>">
									</i></span>

			        		<?php } ?>

			        	</td>

			        	<td class="eliminarGuion">
			        		<span>
			        			<i 
			        				class="bi bi-trash-fill" 
			        				style="font-size:1rem; color:darkorange;"
			        				data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Eliminar guión</em>"></i></span>
			        	</td>

			        	<td class="abrirPrimerParrafo">
			        		<span>
			        			<i 
			        				class="bi bi-pencil-square" 
			        				style="font-size:1rem; color:#EF8B18;"
		        					data-placement="top" 	        					
									data-bs-toggle="tooltip" 
									data-bs-html="true" 
									title="<em>Abrir primer párrafo de guión</em>"></i></span>
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
	// FUNCIONES DEL MODAL
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
	function refrescarTabla(arrGuiones) {

		// se limpia la lista de guiones seleccionables
		$("#tablaGuiones tbody tr").remove();

		// se recorre el array de guiones
		arrGuiones.forEach(function(guion) {

			// se obtiene la profundidad o el icono para refrescar el guión:
			
			// se inicializa la profundidad
			strProfundidad = guion.profundidad;

			// si el guion no está refrescado se incluirá el icono para refrescar
			if (guion.refrescada.localeCompare('f') == 0) {

				// se incluirá el icono para refrescar
				strProfundidad = 

					'<span > '+
	    				'<i '+
	    					'class="bi bi-arrow-counterclockwise" '+
	    					'style="font-size:1rem; color:#EF8B18;" '+
	    					'data-placement="top" '+			
							'data-bs-toggle="tooltip" '+
							'data-bs-html="true" '+
							'title="<em>Actualizar profundidad de guión</em>"> '+
						'</i> '+
	    			'</span> '

			}

			// se añade el guión a la tabla
			$("#tablaGuiones tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-id_guion="'+guion.id+'" '+
		        	'data-id_parrafo_ini="'+guion.idParrafoIni+'" '+
		        	'data-titulo="'+guion.titulo+'" '+
		        	'data-refrescada="'+guion.refrescada+'"> '+
		        	
		        	'<td class="tituloGuion datosCelda"> '+
		        		guion.titulo+
		        	'</td> '+
		        	
		        	'<td class="asignarProfundidades columnaCentrada"> '+
		        		strProfundidad+
		        	'</td> '+	        	
		        	
		        	'<td class="eliminarGuion"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-trash-fill" '+
		        				'style="font-size:1rem; color:darkorange;" '+
		        				'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Eliminar guión</em>"></i></span> '+
		        	'</td> '+
		        	
		        	'<td class="abrirPrimerParrafo"> '+
		        		'<span> '+
		        			'<i  '+
		        				'class="bi bi-pencil-square" '+
		        				'style="font-size:1rem; color:#EF8B18;" '+
	        					'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Abrir primer párrafo de guión</em>"></i></span> '+
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
	function asignarErrores(tituloGuion) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay titulo de guión se añade el mensaje de error
		if (tituloGuion.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Título está vacío<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(tituloGuion) {

			// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar el guión <b>'+tituloGuion+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS
	// --------------------------------------------------------------------------------
 
	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarGuiones").click(function() {

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el titulo de la guión
		var tituloGuion = $("input#tituloGuion").val();

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
	$("table#tablaGuiones").on("click", "td.datosCelda", function() {

		// se obtiene el id y titulo de la guión pulsada
		idGuion = $(this).parent().data("id");
		tituloGuion = $(this).parent().data("titulo");

		// se guarda el id en el hidden del guión editado
		$("input#idGuion").val(idGuion);

		// se muestra el titulo del guión en el input
		$("input#tituloGuion").val(tituloGuion);

		// se muestra el botón para permitir actualizar
		$("button#botonActualizar").show();

	});


	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene el titulo del guión
		var tituloGuion = $("input#tituloGuion").val();

		// si el titulo del guión no está vacío se continúa
		if (tituloGuion.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de guiones
    		$.post("/relatosapp/guiones.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearGuion",
    				titulo:tituloGuion
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

    	// si el titulo de la guión está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(tituloGuion);
		}

	});


	// función que actualiza un registro
	// ------------------------------------------------------------------------------
	$("button#botonActualizar").click(function() {

		// se obtiene el id y titulo de la guión
		var idGuion = $("input#idGuion").val();
		var tituloGuion = $("input#tituloGuion").val();

		// si el titulo del guión no está vacío se continúa
		if (tituloGuion.trim().length > 0) {

    		// se hace la llamada ajax para obtener la lista de guiones
    		$.post("/relatosapp/guiones.php", 

    			// Se definen los parámetros
    			{
    				accion:"actualizarGuion",
    				id:idGuion,
    				titulo:tituloGuion
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

    	// si el titulo de la guión está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se solicita asignar al modal todos los errores en que se ha incurrido
			asignarErrores(tituloGuion);

		}			          		

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
		$("table#tablaGuiones").on("click", "td.eliminarGuion", function() {

		// se obtiene el id y titulo del guión
		var idGuion = $(this).parent().data("id_guion");
		var tituloGuion = $(this).parent().data("titulo");

		// se actualiza el id del guión editado
		$("input#idGuion").val(idGuion);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(tituloGuion);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id de la guión
		var idGuion = $("input#idGuion").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/guiones.php", 

			// Se definen los parámetros
			{
				accion:"eliminarGuion",
				id:idGuion
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


	// función que solicita calcular la profundidad de un guión
	// ------------------------------------------------------------------------------
	$("table#tablaGuiones").on("click", "td.asignarProfundidades", function() {			

		// se obtiene el valor de refresco de profundidad
		var refrescada = $(this).parent().data("refrescada");

		// si no está refrescada se permite el envío de solicitud de cálculo
		if (!refrescada) {

    		// se obtiene el id del guión pulsado
    		var idGuion = $(this).parent().data("id_guion");
			
    		// se hace la llamada ajax para solicitar el refresco
    		$.post("/relatosapp/guiones.php", 

    			// Se definen los parámetros
    			{
    				accion:"asignarProfundidades",
    				id:idGuion
    			},
    			
    			// El resultado se recoge en el data
    			// se utiliza el método proxy para poder pasar el contexto de la celda
    			// a esta función y que pueda actualizarse la celda desde la propia función
    			$.proxy(function(data, status) {

      				// se transforma el valor json recibido en array
	    			arrData = JSON.parse(data);

	    			// si se ha podido calcular la profundidad se continúa
	    			if (arrData[0].localeCompare('OK') == 0) {

        				// se refresca el valor de la profundidad del guión
        				$(this).text(arrData[1]);

    					// se indica que el guión está refrescado
    					$(this).parent().data("refrescada", 1);

	    				// se muestra el modal
						$('#modalRegistroErroneo').modal('show');

		    			// se define el mensaje de cabecera de los errores
		    			$('#cuerpoModalRegistroErroneo').
		    				append('Profundidad calculada con éxito: <b>('+arrData[1]+')</b> ');
	    			
					// si ha habido un error al calcular la profundidad se muestra
	    			} else {

						// se muestra el modal
						$('#modalRegistroErroneo').modal('show');

		    			// se limpia el registro de errores
		    			$('#cuerpoModalRegistroErroneo').html('');

		    			// se define el mensaje de cabecera de los errores
		    			$('#cuerpoModalRegistroErroneo').
		    				append('Se han producido los siguiente errores:<br>');

		    			// se asigna el error recibido
						$('#cuerpoModalRegistroErroneo').append('<b>'+arrData[1]+'</b>');

	    			}

      			}, this)
      		);		
      	}				

	});


	// función que lanza la apertura de un párrafo
	// ------------------------------------------------------------------------------
	$("table#tablaGuiones").on("click", "td.abrirPrimerParrafo", function() {

		// se obtiene el id de la palabra clave
		var idGuion = $(this).parent().data("id_guion");
		var idParrafo = $(this).parent().data("id_parrafo_ini");

		// se redirige a la página que gestiona los valores de palabra clave
		// url original: /relatosapp/parrafo.php?idg="+idGuion+"&idpf="+idParrafo
		window.location.href = "/relatosapp/guion/"+idGuion+"/parrafo/"+idParrafo;
	
	});	
			
}); 

</script>

</body>
</html>