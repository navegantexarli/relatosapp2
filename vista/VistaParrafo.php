<!-- ------------------------------------------------------------------------------------------
VistaParrafo
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

	<!-- título de la página-->
	<div class="tituloPagina">

		<!-- se indica título de la página-->
		<h1><?php echo $guion->getTitulo(); ?></h1>
		
		<!-- se indica la cantidad de párrafos del guión que contiene este párrafo -->
		<button 
			id="cantidadParrafos" 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
			Cantidad de párrafos: 
			<span class="badge bg"><?php echo $numParrafos; ?></span>
		</button>

		<!-- se indica el id del párrafo actual -->
		<button 
			id="idParrafoActual" 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
		  	ID Párrafo: 
		  	<span class="badge bg"><?php echo $parrafo->getId(); ?></span>  
		</button>

	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaParrafo'; ?>	

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- hidden para el id del guión -->
		<input 
			type="hidden" 
			id="idGuion" 
			name="idGuion" 
			value="<?php echo $guion->getId(); ?>"> 

		<!-- hidden para el id de párrafo -->
		<input 
			type="hidden" 
			id="idParrafo" 
			name="idParrafo" 
			value="<?php echo $parrafo->getId(); ?>"> 

		<!-- hidden para la profundidad del párrafo -->
		<input 
			type="hidden" 
			id="profundidad" 
			name="profundidad" 
			value="<?php echo $parrafo->getProfundidad(); ?>">

		<!-- hidden para el nivel por defecto definido para la aplicación -->
		<input 
			type="hidden" 
			id="nivelDefecto" 
			name="nivelDefecto" 
			value="<?php echo $nivelDefecto; ?>"> 

		<!-- Area para mostrar padres e hijos de un párrafo -->		
		<div class="btn-toolbar panelParrafos" role="toolbar" aria-label="">
	  		
	  		<!-- Botones delanteros -->
	  		<div class="btn-group me-2 botoneraMarcas" role="group" aria-label="First group">
	    		
	    		<!-- botón para abrir marcas -->
	    		<button 
	    			id="abrirMarcas"
	    			type="button" 
	    			class="btn btn-outline outlinePurple">
	    			<span>
						<i 
							class="bi bi-ui-checks-grid" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Abrir marcas del guión</em>"></i></span></button>
	    		
	    		<!-- botón para abrir el primer párrafo del guión-->
	    		<button 
	    			type="button" 
	    			class="btn btn-outline outlinePurple abrirParrafo"
	    			data-id_parrafo="<?php echo $parrafo->getId(); ?>">
		    		<span>
						<i 
							class="bi bi-skip-start" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Abrir primer párrafo del guión</em>"></i></span></button>
	    	
	    	</div>

	    	<!-- Area para los párrafos padre del párrafo actual -->
			<div class="btn-group me-2 botoneraPadres" role="group" aria-label="First group">
	    	
	    		<!-- Párrafos padre del párrafo -->
	    		<?php foreach ($arrParrafosPadre as $parrafoPadre) { ?>
				
					<button 
						type="button" 
						class="btn btn-outline outlinePurple abrirParrafo parrafoPadre"
						data-id_parrafo="<?php echo $parrafoPadre->getId(); ?>">
						<?php echo $parrafoPadre->getId(); ?></button>
	    		
	    		<?php } ?>
	  		
	  		</div>	

			<!-- input para busca un párrafo -->
			<div class="input-group">
				
				<input 
					id="parrafoActual"
					type="text" 
					class="form-control" 
					placeholder="Guión" 
					aria-label="" 
					value="<?php echo $parrafo->getId(); ?>">
				
				<button 
					class="btn btn-outline outlinePurple abrirParrafo" 
					type="button">
  					<span>
  						<i 
  							class="bi-search" 
  							style="font-size:1rem; color:purple;"
			        		data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Abrir párrafo</em>"></i></span></button>
			</div>

			<!-- Botonera de hijos. Párrafos hijo del parrafo -->
	  		<div class="btn-group me-2 ml-2 botoneraHijos" role="group" aria-label="First group">
	    		
	    		<!-- Párrafos paadre del párrafo -->
	    		<?php foreach ($arrParrafosHijo as $parrafoHijo) { ?>
					
					<button 
						type="button" 
						class="btn btn-outline outlinePurple abrirParrafo parrafoHijo"
						data-id_parrafo="<?php echo $parrafoHijo->getId(); ?>">
						<?php echo $parrafoHijo->getId(); ?></button>
	    		
	    		<?php } ?>
	  		
	  		</div>	
		
		</div>

	</div>

	<!-- Area para el cuerpo del párrafo -->
	<div class="container areaParrafo">
	<div class="row">
    	
    	<!-- Area para la primera columna -->
    	<div class="col-8">
      		
      		<!-- textarea correspondiente a las operaciones del párrafo-->
      		<div class="form-group areaTextarea">
    			<textarea 
    				class="form-control form-control-lg" 
    				id="operaciones" 
    				placeholder="Operaciones" 
    				rows="3"><?php echo $parrafo->getOperaciones(); ?></textarea>
    		</div>

      		<!-- textarea correspondiente al texto del párrafo-->
    		<div class="form-group areaTextarea">
    			<textarea 
    				class="form-control form-control-lg" 
    				id="texto" 
    				placeholder="Texto" 
    				rows="10"><?php echo $parrafo->getTexto(); ?></textarea>
  			</div>

  			<!-- botonera de las acciones a realizar sobre el párrafo-->
			<div class="form-group d-grid gap-2 d-md-flex justify-content-md-end ">
				
				<!-- botón para eliminar el párrafo -->
				<button 
					id="eliminarParrafo" 
					type="button" 
					class="btn btn-outline outlinePurple me-md-2">
					<span>
						<i 
							class="bi bi-trash-fill" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Eliminar párrafo</em>"></i></span></button>
				
				<!-- botón para abrir las instrucciones -->
				<button 
					id="abrirInstrucciones" 
					type="button" 
					class="btn btn-outline outlinePurple me-md-2">
					<span>
						<i 
							class="bi bi-info" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Ver instrucciones</em>"></i></span></button>
				
				<!-- botón para guardar el párrafo -->
				<button 
					id="guardarParrafo" 
					type="button" 
					class="btn btn-outline outlinePurple me-md-2">
					<span>
						<i 
							class="bi bi-save" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Guardar párrafo</em>"></i></span></button>
				
				<!-- botón para crear un nuevo párrafo -->
				<button 
					id="crearNuevoHijo" 
					type="button" 
					class="btn btn-outline outlinePurple">
					<span>
						<i 
							class="bi bi-plus" 
							style="font-size:1.5rem; color:purple;"
							data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Crear nuevo párrafo hijo del párrafo actual</em>">
						</i></span></button>
			</div>  			

    	</div>


    	<!-- Area para la segunda columna -->
    	<div class="col">

    	<!-- Area para el bloque de asignación de hijos al párrafo -->
    	<div class="areaDelimitadoraParrafo">
	    	
	    	<!-- Area para describir del elemento que asigna hijos al párrafo -->
			<div class="row">
				<p>Asignación de hijos al párrafo</p>
			</div>

	    	<!-- Area para el input de asignación de párrafo como hijo de otro -->
			<div class="row input">
				
				<div class="col-9">
					<input 
						id="idParrafoHijo" 
						type="text" 
						class="form-control" 
						placeholder="" 
						aria-label="" 
						value="">
				</div>

				<div class="d-grid gap-2 col-3 mx-auto pl-0">
					<button 
						id="asignarParrafo" 
						type="button" 
						class="btn btn-outline outlinePurple">
						<span>
							<i 
								class="bi bi-box-arrow-in-up-right" 
								style="font-size:1rem; color:purple;"
								data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Asignar párrafo hijo al párrafo actual</em>">
								</i></span></button>
				</div>
		
			</div>

		</div>

		<!-- Area para separar los bloques -->
		<div class="areaSeparadora"></div>

		<!-- Area para el bloque de desasignación de hijos del párrafo -->
		<div class="areaDelimitadoraParrafo">

		    <!-- Area para describir el elemento que desasigna hijos del párrafo -->
			<div class="row">
				<p>Designación de hijos del párrafo</p>
			</div>

			<!-- Area para el select de desasignación de parrafos hijo del párrafo -->
			<div class="row input">
				<div class="col-9">
					<select 
						id="hijosDesasignables" 
						class="form-select" 
						aria-label="Default select example">
							
						<?php foreach ($arrParrafosHijo as $parrafoHijo) { ?>
							<option value="3"><?php echo $parrafoHijo->getId(); ?></option>
						<?php } ?>

					</select>
				</div>

				<div class="d-grid gap-2 col-3 mx-auto pl-0">
					<button 
						id="desasignarParrafo" 
						type="button" 
						class="btn btn-outline outlinePurple">
						<span>
							<i 
								class="bi bi-box-arrow-down-left" 
								style="font-size:1rem; color:purple;"
								data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Desasignar párrafo hijo del párrafo actual</em>">
							</i></span></button>
				</div>
		
			</div>

		</div>

		<!-- Area para separar los bloques -->
		<div class="areaSeparadora"></div>

		<!-- Area para gestionar el nivel del párrafo -->
		<div class="areaDelimitadoraParrafo">

		    <!-- Area para describir el nivel del párrafo -->
			<div class="row"><p>Nivel del párrafo</p></div>

			<!-- Area para el nivel del párrafo -->
			<div class="row rango">
			<div class="col">
				<div class="rangoNivelParrafo">
					<input 
						id="nivelParrafo" 
						type="range" 
						class="form-range nivelParrafo" 
						min="1" 
						max="<?php echo self::nivelMax; ?>" 
						value="<?php echo $parrafo->getNivel(); ?>">
				</div>
			</div>
			</div>

		</div>

		<!-- Area para separar los bloques -->
		<div class="areaSeparadora"></div>

		<!-- Area para el bloque que marca o desmarca el párrafo -->
		<div class="areaDelimitadoraParrafo">
				
			<!-- Area para marcar el párrafo -->
			<div class="row parrafoMarcado">
			<div class="col">
			  	<div class="form-check form-switch fs-6">
			    	<input 
			    		class="form-check-input" 
			    		type="checkbox" 
			    		id="parrafoMarcado" 
			    		<?php if ($parrafo->getMarcado() == 't') echo 'checked'; ?>
						data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Marcar el párrafo actual</em>">
					    <p class="descripcionMarcarParrafo">Marcar el párrafo</p>
				</div>
			</div>
			</div>

		</div>
	
	</div>			

    </div>
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

	<!-- modal para abrir la lista de instrucciones -->
	<?php require 'modal/modalInstrucciones.php' ?>		

	<!-- modal para abrir la lista de marcas -->
	<?php require 'modal/modalMarcas.php' ?>		

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


	// se oculta el botón de eliminar
	$("button#eliminarParrafo").hide();


	// --------------------------------------------------------------------------------
	// FUNCIONES DE LA VISTA
	// --------------------------------------------------------------------------------

	// función privada que actualiza el párrafo con el registro recibido por AJAX
	// --------------------------------------------------------------------------
	function refrescarParrafo(numParrafos, parrafosPadre, parrafosHijo, parrafo) {

		// se actualiza el número de párrafos del guión
		$("button#cantidadParrafos span").text(numParrafos);

		// se asigna el id del párrafo actual
		$("input#idParrafo").val(parrafo.id);
		$("button#idParrafoActual span").text(parrafo.id);

		// se asigna la profundidad del párrafo actual
		$("input#profundidad").val(parrafo.profundidad);

		// se limpia la botonera de padres e hijos
		$('button.parrafoPadre').remove();
		$('button.parrafoHijo').remove();

		// se recorre el array de párrafos padre
		parrafosPadre.forEach(function(parrafoPadre){

			// se añade el párrafo padre a la botonera de parrafos padre
   			$('div.botoneraPadres').append(

				'<button '+
					'type="button" '+
					'class="btn btn-outline outlinePurple abrirParrafo parrafoPadre"'+
					'data-id_parrafo="'+parrafoPadre.id+'">'+parrafoPadre.id+'</button>'

			);

		});

		// se actualiza el input con el parrafo actual
		$("input#parrafoActual").val(parrafo.id);

		// se recorre el array de párrafos hjo
		parrafosHijo.forEach(function(parrafoHijo){

			// se añade el párrafo hijo a la botonera de parrafos hijo
   			$('div.botoneraHijos').append(

				'<button '+
					'type="button" '+
					'class="btn btn-outline outlinePurple abrirParrafo parrafoHijo"'+
					'data-id_parrafo="'+parrafoHijo.id+'"> '+parrafoHijo.id+'</button>'

			);

		});

		// se actualiza las operaciones
		$("textarea#operaciones").val(parrafo.operaciones);

		// se actualiza el texto
		$("textarea#texto").val(parrafo.texto);

		// si el párrafo no tiene hijos se habilita el botón de eliminar
		if (parrafosHijo.length == 0) $("button#eliminarParrafo").show();
		
		// si el párrafo no tiene hijos se deshabilita el botón de eliminar
		else $("button#eliminarParrafo").hide();

		// se inicializa el html con los option del select de los hijos eliminables
		var html = "";

		// se recorre el array de parrafos hijo
		parrafosHijo.forEach(function(parrafoHijo){

			// se añade al select una opción con el nuevo arrafo hijo
			html += '<option value="'+parrafoHijo.id+'">'+parrafoHijo.id+'</option>';
		
		});

		// se actualiza el select de hijos desasignables
		$("select#hijosDesasignables").html(html);

		// se actualiza el nivel dle parrafo
		$("input#nivelParrafo").val(parrafo.nivel);

		// si el párrafo está marcado se marcará
		if (parrafo.marcado.localeCompare("t") == 0) { 

			// se actualiza la marca del parrafo a activo
			$("input#parrafoMarcado").prop('checked', true);
		
		// si el párrafo no está marcado se actualizará a párrafo no marcado
		} else $("input#parrafoMarcado").prop('checked', false);

		// se inicializan los tooltips
		var tooltipTriggerList = [].slice
			.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		    return new bootstrap.Tooltip(tooltipTriggerEl, {
		        container: 'body',
		        trigger : 'hover'
		    });			
		});

	}


	// función privada que actualiza sólo allá donde aparecen los párrafos hijo tras
	// respuesta AJAX
	// ------------------------------------------------------------------------------
	function refrescarParrafosHijo(parrafosHijo) {

		// se limpia la botonera de hijos
		$('button.parrafoHijo').remove();		    					

		// se recorre el array de párrafos hijo
		arrData[1].forEach(function(parrafoHijo){

			// se añade el párrafo hijo a la botonera de parrafos hijo
   			$('div.botoneraHijos').append(

				'<button '+
					'type="button" '+
					'class="btn btn-outline outlinePurple abrirParrafo parrafoHijo"'+
					'data-id_parrafo="'+parrafoHijo.id+'">'+parrafoHijo.id+'</button>'

			);

			// se inicializa el html con los option del select de los hijos eliminables
			var html = "";

			// se recorre el array de parrafos hijo
    		parrafosHijo.forEach(function(parrafoHijo){

    			// se añade al select una opción con el nuevo arrafo hijo
    			html += '<option value="'+parrafoHijo.id+'">'+parrafoHijo.id+'</option>';
			});

			// se actualiza el select de hijos desasignables
			$("select#hijosDesasignables").html(html);
				
		});	

	}


	// función relativa al modal modalRegistroErroneo
	// función que asigna todos los tipos de errores en que se ha incurrido a la hora de
	// crear o actualizar un registro
	// ------------------------------------------------------------------------------
	function asignarErrores(mensajeError) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido el siguiente error:<br>');

		// si no hay titulo de guión se añade el mensaje de error
		$('#cuerpoModalRegistroErroneo').append('<b>'+mensajeError+'</b>');

	}


	// función: abrirParrafo
	// Recoge el id del párrafo, realiza la llamada AJAX y actualiza el entorno para
	// mostrar toda la información relativa al párrafo abierto
	// ------------------------------------------------------------------------------
	function abrirParrafo(idGuion, idParrafo) {

		// se hace la llamada ajax para obtener el párrafo
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"abrirParrafo", 
				idGuion:idGuion,
				idParrafo:idParrafo
			},
			
			// El resultado ha llegado en data
			function(data, status) {

				// se transforma el valor json recibido en array
				arrData = JSON.parse(data);

				// si no ha habido error...
				if (arrData[0].localeCompare('ERROR') != 0) {

					// se refresca la tabla
					refrescarParrafo(arrData[0], arrData[1], arrData[2], arrData[3]);

				// si ha habido error...	
				} else {

					// se abre el modal para mostrar el mensaje de error
					$('#modalRegistroErroneo').modal('show');

					// se asignan los errores devueltos 
					asignarErrores(arrData[1]);
				}

			}

  		);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

 	// evento: abrirParrafo
 	// se ejecuta este evento cuando el modal de Marcas lanza un trigger con el idGuion
 	// y el idParrafo seleccionado
 	// ----------------------------------------------------------------------
	$("button#abrirMarcas").on("abrirParrafo", function(e, idGuion, idParrafo) {

		// se solicita abrir el párrafo con el idGuion e idParrafo
		abrirParrafo(idGuion, idParrafo);
	});


 	// evento: mouseover y mouseleave
	// se activa el botón cuando se pasa el ratón sobre botonera padres o hijos
	// ----------------------------------------------------------------------
	$("div.botoneraPadres, div.botoneraHijos").on("mouseover", "button", function() {
    	$(this)
    		.css("border-color", "orange")
    		.css("color", "orange")
    		.children().children()
    		.css("color", "orange");
    }).on("mouseleave", "button", function() {
		$(this)
			.css("border-color", "purple")
			.css("color", "purple")
			.children().children()
			.css("color", "purple");
    });


 	// evento: eventoAbrirParrafo
	// evento que abre un párrafo según id de párrafo solicitado
	// ----------------------------------------------------------------------
	$("div.panelParrafos").on("click", "button.abrirParrafo", function() {

		// se obtiene el id del guión y del párrafo que se desea abrir
		var idGuion = $("input#idGuion").val();
		var idParrafo = (typeof $(this).data("id_parrafo") !== 'undefined') ?
			$(this).data("id_parrafo") : $('input#parrafoActual').val();

		// se hace la llamada a la función que se encarga de abrir el párrafo
		abrirParrafo(idGuion, idParrafo);

	}); 


	// función que crea un nuevo párrafo
	// --------------------------------------------------------------------------
	$("button#crearNuevoHijo").click(function() {        		

		// se obtiene el id dle párrafo actual que será párrafo padre
		var idParrafo = $("input#idParrafo").val();

		// se cierra el tooltip aquí...

		// se hace la llamada ajax para proceder a crear nuevo párrafo hijo
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"crearNuevoParrafo",
				id:idParrafo
			},
			
			// El resultado ha llegado en data
			function(data, status) {

				// se transforma el valor json recibido en array
				arrData = JSON.parse(data);

				// si no ha habido error...
				if (arrData[0].localeCompare('ERROR') != 0) {

					// se refresca la tabla
					refrescarParrafo(arrData[0], arrData[1], arrData[2], arrData[3]);

				// si ha habido error...	
				} else {

					// se abre el modal para mostrar el mensaje de error
					$('#modalRegistroErroneo').modal('show');

					// se asignan los errores devueltos 
					asignarErrores(arrData[1]);
				}

  			}

  		);

	});


	// función que guarda el parrafo
	// ------------------------------------------------------------------------------
	$("button#guardarParrafo").click(function() {

		// se obtiene los atributos dle párrafo
		var idParrafo = $("input#idParrafo").val();
		var operaciones = $("textarea#operaciones").val();
		var texto = $("textarea#texto").val();
		var nivel = $("input#nivelParrafo").val();
		var marcado = ($("input#parrafoMarcado").prop('checked')) ? 't' : 'f';
		var profundidad = $("input#profundidad").val();

		// se hace la llamada ajax para proceder a la actualización del párrafo
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"guardarParrafo",
				id:idParrafo,
				operaciones:operaciones,
				texto:texto,
				nivel:nivel,
				marcado:marcado,
				profundidad:profundidad
			},
			
			// El resultado ha llegado en data
			function(data, status) {}
  		
  		);

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ---------------------------------------------------------------------------
	$("button#eliminarParrafo").click(function() {

		// se obtiene el id del párrafo
		idParrafo = $("input#idParrafo").val();

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(idParrafo);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id del párrafo
		var idParrafo = $("input#idParrafo").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/parrafo.php", 

			// Se definen los parámetros
			{
				accion:"eliminarParrafo",
				idParrafo:idParrafo
			},
			
			// El resultado se recoge en el data
			// data[0] -> total de registros
			// data[1] -> array de registros
			function(data, status) {

				// se obitene el valor del nivel por defecto
				nivelDefecto = $("input#nivelDefecto").val();
				
				// se limpia el formulario del párrafo
				$("textarea#operaciones").val('');
				$("textarea#texto").val('');
				$("input#idParrafo").val('');
    			$("input#nivelParrafo").val(nivelDefecto);
				$("input#parrafoMarcado").prop('checked', false);

  			}

  		);						

	});


	// función que desasignar al parrafo un párrafo hijo
	// ------------------------------------------------------------------------------
	$("button#asignarParrafo").click(function() {

		// se obtiene el id del párrafo que se quiere asignar
		idParrafoHijo = $("input#idParrafoHijo").val();

		// si el valor introducido es correcto se continúa
		if (idParrafoHijo == parseInt(idParrafoHijo)) {
		
			// se obtiene el id del parrafo actual
			idParrafo = $("input#idParrafo").val();

    		// se hace la llamada ajax para proceder a la asignación del párrafo hijo
    		$.post("/relatosapp/parrafo.php", 

    			// Se definen los parámetros
    			{
    				accion:"asignarParrafoHijo",
    				idParrafo:idParrafo,
    				idParrafoHijo:idParrafoHijo
    			},
    			
    			// El resultado ha llegado en data
    			function(data, status) {

    				// se transforma el valor json recibido en array
    				arrData = JSON.parse(data);

    				// si no ha habido error...
    				if (arrData[0].localeCompare('ERROR') != 0) {

    					// se refresca sólo allí donde intervienen los párrafos hijo
    					refrescarParrafosHijo(arrData[1]);		    					

    				// si ha habido error...	
    				} else {

    					// se abre el modal para mostrar el mensaje de error
						$('#modalRegistroErroneo').modal('show');

						// se asignan los errores devueltos 
						asignarErrores(arrData[1]);
   					}

      			}
      		);


		// si el valor está vacío o no es un entero se indica el error        		
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se limpia el registro de errores
			$('#cuerpoModalRegistroErroneo').html('');

			// se añade el mensaje de error
			$('#cuerpoModalRegistroErroneo').
				html('<b>- El id de párrafo hijo no es válido<b><br>');
		}

	});


	// función que desasigna del parrafo un párrafo hijo
	// ------------------------------------------------------------------------------
	$("button#desasignarParrafo").click(function() {

		// se obtiene el id del párrafo que se quiere desasignar
		idParrafoHijo = $("select#hijosDesasignables").val();

		// si el valor introducido es correcto se continúa
		if (idParrafoHijo == parseInt(idParrafoHijo)) {
		
			// se obtiene el id del parrafo actual
			idParrafo = $("input#idParrafo").val();

    		// se hace la llamada ajax para proceder a la desasignación del párrafo hijo
    		$.post("/relatosapp/parrafo.php", 

    			// Se definen los parámetros
    			{
    				accion:"desasignarParrafoHijo",
    				idParrafo:idParrafo,
    				idParrafoHijo:idParrafoHijo
    			},
    			
    			// El resultado ha llegado en data
    			function(data, status) {

    				// se transforma el valor json recibido en array
    				arrData = JSON.parse(data);

    				// si no ha habido error...
    				if (arrData[0].localeCompare('ERROR') != 0) {

    					// se refresca sólo allí donde intervienen los párrafos hijo
    					refrescarParrafosHijo(arrData[1]);

    				// si ha habido error...	
    				} else {

    					// se abre el modal para mostrar el mensaje de error
						$('#modalRegistroErroneo').modal('show');

						// se asignan los errores devueltos 
						asignarErrores(arrData[1]);
   					}

      			}

      		);


		// si el valor está vacío o no es un entero se indica el error        		
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se limpia el registro de errores
			$('#cuerpoModalRegistroErroneo').html('');

			// se añade el mensaje de error
			$('#cuerpoModalRegistroErroneo').
				html('<b>- El id de párrafo hijo no es válido<b><br>');

		}

	});

}); 

</script>

</body>
</html>