<!-- ------------------------------------------------------------------------------------------
VsitaPersonajeRelaciones
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
		
		<h1>Relaciones de <?php echo $personaje->getNombreLargo(); ?></h1>
		
		<button 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
			Personaje: 
			<span class="badge bg"><?php echo $personaje->getNombreLargo(); ?></span></button>
	
	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaPersonajeRelaciones'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define los hidden para los id de los elementos que configuran el registro -->
		<input 
			type="hidden" 
			id="idPersonaje" 
			name="idPersonaje" 
			value="<?php echo $personaje->getId(); ?>"> 

		<input 
			type="hidden" 
			id="idPersonaje2" 
			name="idPersonaje2" 
			value=""> 

		<input 
			type="hidden" 
			id="idRelacion" 
			name="idRelacion" 
			value=""> 

		<!-- inputs para introducir un registro -->
		<div class="input-group areaInput">
			
			<!-- input para mostrar el nombre de la relación -->
			<input 
				id="nombreRelacionC" 
				type="text" 
				class="form-control" 
				placeholder="" 
				aria-label="Nombre de la relación" 
				disabled>
			
			<button 
				id="botonBuscarRelacion" 
				class="btn btn-outline outlinePurple" 
				type="button">				
        		<span>
        			<i 
        				class="bi bi-people-fill" 
        				style="font-size:1.5rem; color:purple;"
						data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Abrir lista de relaciones para asignar relación</em>">
       				</i></span></button>

			<!-- input para mostrar el nombre del personaje2 -->
			<input 
				id="nombreLargoP2C" 
				type="text" 
				class="form-control" 
				placeholder="" 
				aria-label="Nombre largo de personaje relacionado" 
				disabled>

			<button 
				id="botonBuscarPersonaje2" 
				class="btn btn-outline outlinePurple" 
				type="button">
        		<span>
        			<i 
        				class="bi bi-person-fill" 
        				style="font-size:1.5rem; color:purple;"
						data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Abrir lista de personajes para asignar personaje relacionado</em>"></i></span></button>
			
			<!-- botón para crear un nuevo registro-->
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
						title="<em>Crear la relación entre el personaje y personaje relacionado</em>"></i></span></button>
		
		</div>

		<!-- inputs para buscar un registro -->
		<div class="input-group">
			
			<input 
				id="nombreRelacion" 
				type="text" 
				class="form-control" 
				placeholder="Relación" 
				aria-label="Nombre de la relación">

			<input 
				id="nombreLargoP2" 
				type="text" 
				class="form-control" 
				placeholder="Personaje relacionado" 
				aria-label="Nombre largo de personaje relacionado">

			<button 
				id="buscarPRelaciones" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"></i></span></button>
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrPersonajeRelaciones); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span>
		</p>

		<!-- tabla de registros -->
		<table id="tablaPRelaciones" class="table table-dark table-striped">
			
			<thead>
		    	<tr>
		        	<th scope="col" style="width: 30%;">Relaciones</th>
		        	<th scope="col" style="width: 65%;">Personajes relacionados</th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>
		    
		    <tbody id="cuerpoTabla">
		    	
		    	<?php foreach ($arrPersonajeRelaciones as $personajeRelacion) { ?>
		    		
			    	<tr 
			    		class="datosTabla"
			        	data-id_relacion="<?php echo $personajeRelacion->getIdRelacion(); ?>"
			        	data-id_personaje2="<?php echo $personajeRelacion->getIdPersonaje2(); ?>"
			        	data-nombre_largo="<?php echo $personajeRelacion->getNombreLargo(); ?>">
			        	
			        	<td class="nombreRelacion datosCelda">
			        		<?php echo $personajeRelacion->getNombreRelacion(); ?>
		        		</td>

			        	<td class="nombreLargoP2 datosCelda">
			        		<?php echo $personajeRelacion->getNombreLargo(); ?>
			        	</td>

			        	<td class="eliminarPRelacion">
			        		<span>
			        			<i 
			        				class="bi bi-trash-fill" 
			        				style="font-size:1rem; color:#EF8B18;"></i></span>
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

	<!-- modal para seleccionar una relación para asignar al personaje -->
	<?php require 'modal/modalRelacionesSeleccionables.php' ?>		

	<!-- modal para seleccionar un personaje2 para asignar al personaje -->
	<?php require 'modal/modalPersonajesSeleccionables.php' ?>		

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
	function refrescarTabla(arrRelaciones) {

		// se limpia la lista de relaciones del personaje
		$("#tablaPRelaciones tbody tr").remove();

		// se recorre el array de relaciones
		arrRelaciones.forEach(function(relacion) {

			// se añade el guión a la tabla
			$("#tablaPRelaciones tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-id_relacion="'+relacion.idRelacion+'" '+
		        	'data-id_personaje2="'+relacion.idPersonaje2+'" '+
		        	'data-nombre_largo="'+relacion.nombreLargoP2+'"> '+
		        	
		        	'<td class="nombreRelacion datosCelda"> '+
		        		relacion.nombreRelacion+
		        	'</td> '+
		        	
		        	'<td class="nombreLargoP2 datosCelda"> '+
		        		relacion.nombreLargoP2+
		        	'</td> '+
		        	
		        	'<td class="eliminarPRelacion"> '+
		        		'<span> '+
		        			'<i class="bi bi-trash-fill" style="font-size:1rem; color:#EF8B18;"></i> '+
		        		'</span> '+
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
	function asignarErrores(idRelacion, idPersonaje2) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay relación asignada se añade el mensaje de error
		if (idRelacion.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- No hay relación asignada<b><br>');
		}

		// si no hay personaje relacionado asignado se añade el mensaje de error
		if (idPersonaje2.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').
				append('<b>- No se ha asignado personaje relacionado<b><br>');
		}

	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(nombreLargoP2) {

			// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar la relación con el personaje <b>'+nombreLargoP2+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarPRelaciones").click(function() {

		// se obtiene el id del personaje
		var idPersonaje = $("input#idPersonaje").val();

		// se obtiene los nombres de la relación y el personaje relacionado
		var nombreRelacion = $("input#nombreRelacion").val();
		var nombreLargoP2 = $("input#nombreLargoP2").val();

		// se hace la llamada ajax para obtener la lista de relaciones del personaje
		$.post("/relatosapp/personajerelaciones.php", 

			// Se definen los parámetros
			{
				accion:"buscarRelacionesPersonaje", 
				idPersonaje:idPersonaje,
				nombreRelacion:nombreRelacion,
				nombreLargo:nombreLargoP2
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


	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se obtiene los id del personaje, la relación y el personaje relacionado
		var idPersonaje = $("input#idPersonaje").val();
		var idRelacion = $("input#idRelacion").val();
		var idPersonaje2 = $("input#idPersonaje2").val();

		// si se ha asignado tanto la relación como el personaje relacionado se continúa
		if ((idRelacion.trim().length > 0) &&
			(idPersonaje2.trim().length > 0)) {

    		// se hace la llamada ajax para crear una nueva relación para el personaje
    		$.post("/relatosapp/personajerelaciones.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearPersonajeRelacion",
    				idRelacion:idRelacion,
    				idPersonaje1:idPersonaje,
    				idPersonaje2:idPersonaje2
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

    	// si relación o personaje relacionado no están asignados se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(idRelacion, idPersonaje2);
		}

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
	$("table#tablaPRelaciones").on("click", "td.eliminarPRelacion", function() {

		// se obtiene los ids de relación y personaje relacionado
		var idRelacion = $(this).parent().data("id_relacion");
		var idPersonaje2 = $(this).parent().data("id_personaje2");
		
		// se obtiene el nombre largo del personaje relacionado
		var nombreLargoP2 = $(this).parent().data("nombre_largo");

		// se actualizan los ids
		$("input#idRelacion").val(idRelacion);
		$("input#idPersonaje2").val(idPersonaje2);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(nombreLargoP2);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtienen los ids de ambos personajes
		var idPersonaje = $("input#idPersonaje").val();
		var idPersonaje2 = $("input#idPersonaje2").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/personajerelaciones.php", 

			// Se definen los parámetros
			{
				accion:"eliminarPersonajeRelacion",
				idPersonaje1:idPersonaje,
				idPersonaje2:idPersonaje2
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