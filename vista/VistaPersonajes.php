<!-- ------------------------------------------------------------------------------------------
VistaPersonajes
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
	<div class="tituloPagina"><h1>Personajes</h1></div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaPersonajes'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del registro a editar -->
		<input type="hidden" id="idPersonaje" name="idPersonaje" value="0"> 

		<!-- input para introducir un registro -->
		<div class="input-group">
			
			<input 
				id="nombrePersonaje" 
				type="text" 
				class="form-control" 
				placeholder="Nombre" 
				aria-label="Nombre de la personaje">

			<input 
				id="nombreLargoPersonaje" 
				type="text" 
				class="form-control" 
				placeholder="Nombre largo" 
				aria-label="Nombre largo del personaje">

			<input 
				id="sexoPersonaje" 
				type="text" 
				class="form-control" 
				placeholder="Sexo" 
				aria-label="Sexo del personaje">

			<input 
				id="edadPersonaje" 
				type="text" 
				class="form-control" 
				placeholder="Edad" 
				aria-label="Edad">

			<button 
				id="buscarPersonajes" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"
			    		data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Buscar personajes</em>"></i></span></button>

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
						title="<em>Crear personaje</em>"></i></span></button>

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
							title="<em>Actualizar personaje</em>"></i></span></button>			
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrPersonajes); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table 
			id="tablaPersonajes" 
			class="table table-dark table-striped">

			<thead>
		    	<tr>
		        	<th scope="col" style="width: 25%;">Nombre</th>
		        	<th scope="col" style="width: 30%;">Nombre largo</th>
		        	<th scope="col" style="width: 10%;" class="columnaCentrada">Sexo</th>
		        	<th scope="col" style="width: 15%;" class="columnaCentrada">Edad</th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>

		    <tbody id="cuerpoTabla">
		    	
		    	<?php foreach ($arrPersonajes as $personaje) { ?>
		    		
			    	<tr 
		        		class="datosTabla"
		        		data-id="<?php echo $personaje->getId(); ?>"
		        		data-nombre="<?php echo $personaje->getNombre(); ?>"
		        		data-nombre_largo="<?php echo $personaje->getNombreLargo(); ?>"
		        		data-sexo="<?php echo $personaje->getSexo(); ?>"
		        		data-edad="<?php echo $personaje->getEdad(); ?>">

			        	<td class="nombrePersonaje datosCelda">
			        		<?php echo $personaje->getNombre(); ?>
			        	</td>
			        	
			        	<td class="nombreLargoPersonaje datosCelda">
			        		<?php echo $personaje->getNombreLargo(); ?>
			        	</td>
			        	
			        	<td class="sexoPersonaje datosCelda columnaCentrada">
			        		<?php echo $personaje->getSexo(); ?>
			        	</td>			        				        	
			        	
			        	<td class="edadPersonaje datosCelda columnaCentrada">
			        		<?php echo $personaje->getEdad(); ?>
			        	</td>
			        	
			        	<td class="eliminarPersonaje">
			        		<span>
			        			<i 
			        				class="bi bi-trash-fill" 
			        				style="font-size:1rem; color:darkorange;"></i></span>
			        	</td>
			        	
			        	<td class="abrirPCaracteristicas">
			        		<span>
			        			<i 
			        				class="bi bi-sliders" 
			        				style="font-size:1rem; color:darkorange;"
			        				data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Abrir características del personaje</em>">
		        				</i></span>
			        	
			        	</td>
			        	
			        	<td class="abrirPRelaciones">
			        		<span>
			        			<i 
			        				class="bi bi-people-fill" 
			        				style="font-size:1rem; color:darkorange;"
			        				data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Abrir relaciones del personaje</em>"></i></span>
			        	</td>

			        	<td class="abrirPImagenes">
			        		<span>
			        			<i 
			        				class="bi bi-file-earmark-image" 
			        				style="font-size:1rem; color:darkorange;"
			        				data-placement="top"
									data-bs-toggle="tooltip"
									data-bs-html="true" 
									title="<em>Abrir imágenes del personaje</em>"></i></span>
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

	<!-- modal para gestionar las características del personaje -->
	<?php require 'modal/modalPersonajeCaracteristicas.php' ?>		



<!-- --------------------------------------------------------------------------------------
CODIGO JAVASCRIPT
--------------------------------------------------------------------------------------- -->
<script type="text/javascript">

// definición de todas las funciones ajax cuando el documento está preparado
$(document).ready(function() {


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
	function refrescarTabla(arrPersonajes) {

		// se limpia la lista de personajes
		$("#tablaPersonajes tbody tr").remove();

		// se recorre el array de personajes
		arrPersonajes.forEach(function(personaje) {

			// se añade el guión a la tabla
			$("#tablaPersonajes tbody").append(

				'<tr '+
	        		'class="datosTabla" '+
	        		'data-id="'+personaje.id+'" '+
	        		'data-nombre="'+personaje.nombre+'" '+
	        		'data-nombre_largo="'+personaje.nombreLargo+'" '+
	        		'data-sexo="'+personaje.sexo+'" '+
	        		'data-edad="'+personaje.edad+'"> '+

		        	'<td class="nombrePersonaje datosCelda"> '+
		        		personaje.nombre+
		        	'</td> '+

		        	'<td class="nombreLargoPersonaje datosCelda"> '+
		        		personaje.nombreLargo+
		        	'</td> '+

		        	'<td class="sexoPersonaje datosCelda columnaCentrada"> '+
		        		personaje.sexo+
		        	'</td> '+

		        	'<td class="edadPersonaje datosCelda columnaCentrada"> '+
		        		personaje.edad+
		        	'</td> '+

		        	'<td class="eliminarPersonaje"> '+
		        		'<span> '+
		        			'<i class="bi bi-trash-fill" style="font-size:1rem; color:darkorange;"></i></span> '+
		        	'</td> '+
		        	
		        	'<td class="abrirPCaracteristicas"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-sliders" '+
		        				'style="font-size:1rem; color:darkorange;" '+
		        				'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Abrir características del personaje</em>"> '+
	        				'</i></span> '+
		        	'</td> '+

		        	'<td class="abrirPRelaciones"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-people-fill" '+
		        				'style="font-size:1rem; color:darkorange;" '+
		        				'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Abrir relaciones del personaje</em>"></i></span> '+
		        	'</td> '+

		        	'<td class="abrirPImagenes"> '+
		        		'<span> '+
		        			'<i '+
		        				'class="bi bi-file-earmark-image" '+
		        				'style="font-size:1rem; color:darkorange;" '+
		        				'data-placement="top" '+
								'data-bs-toggle="tooltip" '+
								'data-bs-html="true" '+
								'title="<em>Abrir imágenes del personaje</em>"></i></span> '+
		        	'</td> '+

		      	'</tr>'
			);

		}); 

	   // se activa el botón cuando se pasa el ratón sobre él
	    $("div.iconoPurple i").hover(function(){
	    	$(this).css("color", "orange")
	    }).mouseleave(function(){
			$(this).css("color", "purple");
	    });

	}


	// función relativa al modal modalRegistroErroneo
	// función que asigna todos los tipos de errores en que se ha incurrido a la hora de
	// crear o actualizar un registro
	// ------------------------------------------------------------------------------
	function asignarErrores(
		nombrePersonaje, 
		nombreLargoPersonaje, 
		sexoPersonaje, 
		edadPersonaje) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay nombre de personaje se añade el mensaje de error
		if (nombrePersonaje.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nombre está vacío<b><br>');
		}

		// si no hay nombre largo de personaje se añade el mensaje de error
		if (nombreLargoPersonaje.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nombre largo está vacío<b><br>');
		}

		// si no hay sexo de personaje se añade el mensaje de error
		if (sexoPersonaje.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Sexo está vacío<b><br>');
		}

		// si el sexo de personaje no es 'f' ni 't' se añade el mensaje de error
		if (sexoPersonaje.localeCompare('t') != 0 && sexoPersonaje.localeCompare('f') != 0) {
			$('#cuerpoModalRegistroErroneo').
				append('<b>- Sexo es incorrecto<b>. Debe utilizarse "f" o "t" <br>');
		}

		// si no hay edad de personaje se añade el mensaje de error
		if (edadPersonaje.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Edad está vacía<b><br>');
		}

		// si el valor de edad introducido no es un entero se añade mensaje de error
		if (edadPersonaje != parseInt(edadPersonaje)) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Edad no es un entero<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(nombreLargoPersonaje) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar el personaje <b>'+nombreLargoPersonaje+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}

 

	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarPersonajes").click(function() {

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene todos los parámetros del personaje
		var nombrePersonaje = $("input#nombrePersonaje").val();
		var nombreLargoPersonaje = $("input#nombreLargoPersonaje").val();
		var sexoPersonaje = $("input#sexoPersonaje").val();
		var edadPersonaje = $("input#edadPersonaje").val();

		// se hace la llamada ajax para obtener la lista de personajes
		$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"buscarPersonajes", 
				nombre:nombrePersonaje,
				nombreLargo:nombreLargoPersonaje,
				sexo:sexoPersonaje,
				edad:edadPersonaje        				
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
	$("table#tablaPersonajes").on("click", "td.datosCelda", function() {

		// se obtiene el id y nombre del personaje pulsada
		idPersonaje = $(this).parent().data("id");
		nombrePersonaje = $(this).parent().data("nombre");
		nombreLargoPersonaje = $(this).parent().data("nombre_largo");
		sexoPersonaje = $(this).parent().data("sexo");
		edadPersonaje = $(this).parent().data("edad");

		// se guarda el id en el hidden del personaje editado
		$("input#idPersonaje").val(idPersonaje);

		// se muestra el nombre, nombre largo, sexo y año de la personaje en el input
		$("input#nombrePersonaje").val(nombrePersonaje);
		$("input#nombreLargoPersonaje").val(nombreLargoPersonaje);
		$("input#sexoPersonaje").val(sexoPersonaje);
		$("input#edadPersonaje").val(edadPersonaje);

		// se muestra el botón para permitir actualizar
		$("button#botonActualizar").show();

	});


	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se oculta el botón de actualizar
		$("button#botonActualizar").hide();

		// se obtiene los parámetros del personaje
		var nombrePersonaje = $("input#nombrePersonaje").val();
		var nombreLargoPersonaje = $("input#nombreLargoPersonaje").val();
		var sexoPersonaje = $("input#sexoPersonaje").val();
		var edadPersonaje = $("input#edadPersonaje").val();

		// si el usuario no ha incurrido en error a la hora de introducir valores se continúa
		if ((nombrePersonaje.trim().length > 0) &&
			(nombreLargoPersonaje.trim().length > 0) &&
			(sexoPersonaje.trim().length > 0) &&
			(sexoPersonaje.localeCompare('t') == 0 || sexoPersonaje.localeCompare('f') == 0) &&
			(edadPersonaje.trim().length > 0) &&
			(edadPersonaje == parseInt(edadPersonaje))) {

    		// se hace la llamada ajax para obtener la lista de personajes
    		$.post("/relatosapp/personajes.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearPersonaje",
    				nombre:nombrePersonaje,
    				nombreLargo:nombreLargoPersonaje,
    				sexo:sexoPersonaje,
    				edad:edadPersonaje	        				
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

    	// si el usuario ha incurrido en error al introducir valores de personajes se sigue
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(nombrePersonaje, nombreLargoPersonaje, sexoPersonaje, edadPersonaje);
		}

	});


	// función que actualiza un registro
	// ------------------------------------------------------------------------------
	$("button#botonActualizar").click(function() {

		// se obtienen los parámetros del personaje
		var idPersonaje = $("input#idPersonaje").val();
		var nombrePersonaje = $("input#nombrePersonaje").val();
		var nombreLargoPersonaje = $("input#nombreLargoPersonaje").val();
		var sexoPersonaje = $("input#sexoPersonaje").val();
		var edadPersonaje = $("input#edadPersonaje").val();

		// si el usuario no ha incurrido en error a la hora de introducir valores se continúa
		if ((nombrePersonaje.trim().length > 0) &&
			(nombreLargoPersonaje.trim().length > 0) &&
			(sexoPersonaje.trim().length > 0) &&
			(sexoPersonaje.localeCompare('t') == 0 || sexoPersonaje.localeCompare('f') == 0) &&
			(edadPersonaje.trim().length > 0) &&
			(edadPersonaje == parseInt(edadPersonaje))) {

    		// se hace la llamada ajax para obtener la lista de personajes
    		$.post("/relatosapp/personajes.php", 

    			// Se definen los parámetros
    			{
    				accion:"actualizarPersonaje",
    				id:idPersonaje,
    				nombre:nombrePersonaje,
    				nombreLargo:nombreLargoPersonaje,
    				sexo:sexoPersonaje,
    				edad:edadPersonaje	        				
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

    	// si el usuario ha incurrido en error al introducir valores de personajes se sigue
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se solicita asignar al modal todos los errores en que se ha incurrido
			asignarErrores(nombrePersonaje, nombreLargoPersonaje, sexoPersonaje, edadPersonaje);

		}			          		

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
	$("table#tablaPersonajes").on("click", "td.eliminarPersonaje", function() {

		// se obtiene el id y nombre largo del personaje
		var idPersonaje = $(this).parent().data("id");
		var nombreLargoPersonaje = $(this).parent().data("nombre_largo");

		// se actualiza el id del personaje editado
		$("input#idPersonaje").val(idPersonaje);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(nombreLargoPersonaje);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id del personaje
		var idPersonaje = $("input#idPersonaje").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/personajes.php", 

			// Se definen los parámetros
			{
				accion:"eliminarPersonaje",
				id:idPersonaje
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


	// función que abre las relaciones del personaje
	// ------------------------------------------------------------------------------
		$("table#tablaPersonajes").on("click", "td.abrirPRelaciones", function() {

		// se obtiene el id del personaje
		var idPersonaje = $(this).parent().data("id");					

		// se redirige a la página que gestiona las relaciones del personaje
		// url original: /relatosapp/personajerelaciones.php?idp="+idPersonaje
		window.location.href = "/relatosapp/personaje/"+idPersonaje+"/relaciones/";

	});	


		// función que abre las imágenes del personaje
	// ------------------------------------------------------------------------------
		$("table#tablaPersonajes").on("click", "td.abrirPImagenes", function() {

		// se obtiene el id del personaje
		var idPersonaje = $(this).parent().data("id");					

		// se redirige a la página que gestiona las imágenes del personaje
		// url original: /relatosapp/personajeimagenes.php?idp="+idPersonaje
		window.location.href = "/relatosapp/personaje/"+idPersonaje+"/imagenes/";

	});	

}); 

</script>

</body>
</html>
