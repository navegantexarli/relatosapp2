<!-- ------------------------------------------------------------------------------------------
VistaPersonajeImagenes
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
	<div class="tituloPagina">
		
		<h1>Imágenes de <?php echo $personaje->getNombreLargo(); ?></h1>
		
		<button 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
			Personaje: 
			<span class="badge bg"><?php echo $personaje->getNombreLargo(); ?></span></button>
		
		<button 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
			Imágenes:
			<span class="badge bg"><?php echo count($arrPersonajeImagenes); ?></span></button>
	
	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaPersonajeImagenes'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id del personaje -->
		<input 
			type="hidden" 
			id="idPersonaje" 
			name="idPersonaje" 
			value="<?php echo $personaje->getId(); ?>"> 

		<!-- se define el hidden para el nombre de la imagen -->
		<input 
			type="hidden" 
			id="nombreImagen" 
			name="nombreImagen" 
			value=""> 

		<!-- Area para buscar una imagen del sistema de carpetas y subirla a la aplicación -->
		<div class="row">
		
			<!-- se define el área para buscar una imagen en el sistema de directorios local -->
			<div class="col-11">
				<div class="mb-3">
		  			<input class="form-control" type="file" id="ficheroImagen" name="imagen">
				</div>
			</div>

			<!-- botón para enviar la imagen al servidor -->
			<div class="col-1">
	        <div class="d-grid gap-2 areaInput">
		        <button 
		        	id="subirImagen" 
		        	class="btn btn-outline outlinePurple" 
		        	type="submit">
					<span>
						<i 
							class="bi bi-arrow-up" 
							style="font-size:1rem; color:purple;"
			        		data-placement="top"
							data-bs-toggle="tooltip"
							data-bs-html="true" 
							title="<em>Subir imagen al servidor</em>"></i></span></button>
		    </div>
			</div>

		</div>

	</div>


	<!-- se define el área en el que se mostrarán las imágenes -->
	<div class="areaImagenes">

		<!-- se define la fila de imágenes. Todas las celdas se adaptarán al número de columnas
			que hayan quedado definidas. En este caso, 4 columnas. Después no será necesario calcular cuándo saltar de fila porque lo hará automáticamente --> 
		<div class="row areaFilaImagenes">
		
			<!-- Por cada imagen del personaje se creará una estructura -->
			<?php foreach ($arrPersonajeImagenes as $personajeImagen) { ?>

				<?php 
					// se calcula la ruta a la imagen
					$idPersonaje = "p".$personajeImagen->getIdPersonaje();
					$nombreImagen = $personajeImagen->getNombreImagen();
					$rutaImagen = "/relatosapp/res/imagenes/".$idPersonaje."/".$nombreImagen;
				?>

				<!-- se crea el área para la imagen y se ubica la imagen -->
				<div class="col-md-3 areaCeldaImagen">
			    	
			    	<img 
			    		src="<?php echo $rutaImagen; ?>" 
			    		width="100px" 
			    		height="300px" 
			    		alt="Lights" 
			    		style="width:100%">
			    	
			    	<div 
			    		class="d-flex justify-content-center iconoPurple areaEliminarImagen"
			    		data-nombre_imagen="<?php echo $personajeImagen->getNombreImagen(); ?>">
		    			<span>
		    				<i 
		    					class="bi bi-trash-fill" 
		    					style="font-size:1rem; color:purple;"></i></span>
		        	</div>

				</div>

			<?php } ?>

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

	// variable global que almacenará el formulario que se enviará con la imagen
	var formdata;



	// --------------------------------------------------------------------------------
	// FUNCIONES DE LA VISTA
	// --------------------------------------------------------------------------------

	// función privada que actualiza las imágenes con los registros recibidos por AJAX
	// ------------------------------------------------------------------------------
	function refrescarAreaImagenes(arrImagenes) {    		

		// se limpia la lista de imágenes
		$("div.areaFilaImagenes div.areaCeldaImagen").remove();

		// se recorre el array de imágenes
		arrImagenes.forEach(function(imagen) {

			// se calcula la ruta a la imagen
			idPersonaje = "p"+imagen.idPersonaje;
			nombreImagen = imagen.nombreImagen;
			rutaImagen = "/relatosapp/res/imagenes/"+idPersonaje+"/"+nombreImagen;

			// se añade la imagen a la lista de imágenes
			$("div.areaFilaImagenes").append(

				'<div class="col-md-3 areaCeldaImagen"> '+
			    	'<img '+
			    		'src="'+rutaImagen+'" '+
			    		'width="100px" '+
			    		'height="300px" '+
			    		'alt="Lights" '+
			    		'style="width:100%"> '+

			    	'<div '+
			    		'class="d-flex justify-content-center iconoPurple areaEliminarImagen" '+
			    		'data-nombre_imagen="'+imagen.nombreImagen+'"> '+
		    			'<span> '+
		    				'<i '+
		    					'class="bi bi-trash-fill" '+
		    					'style="font-size:1rem; color:purple;"></i></span> '+
		        	'</div> '+
				'</div>'
			);

		});

		// se inicializan los tooltips
		var tooltipTriggerList = [].slice
			.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});
		
	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(nombreImagen) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar la imagen <b>'+nombreImagen+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

	// función que actualiza el formulario para añadir la imagen
	// se lanza la función cada vez que cambia el fichero de la imagen a subir
	// se utiliza $.ajax porque hay que enviar un fichero y $.post no implementa formData
	// ------------------------------------------------------------------------------
	$('input#ficheroImagen').change(function() {
	    
	    // se inicializa el formulario
	    formdata = new FormData();

	    // si hay fichero cargado en el input se continúa
	    if($(this).prop('files').length > 0) {

	    	// se obtiene el fichero cargado en el input.
	        file = $(this).prop('files')[0];

	        // se añade el fichero al formulario
	        formdata.append("imagen", file);
	    }

	});


	// función que crea un nuevo registro
	// se utiliza $.ajax porque hay que enviar un fichero y $.post no implementa formData
	// ------------------------------------------------------------------------------
	$("button#subirImagen").click(function() { 

		// se obtiene el id del personaje
		var idPersonaje = $("input#idPersonaje").val();

	    // se añade al fomulario el parámetros acción e id dle personaje
	    formdata.append("accion", "subirPersonajeImagen");
	    formdata.append("id", idPersonaje);

		$.ajax({

			// se indica la url destino
		    url: "/relatosapp/personajeimagenes.php",
		    
		    // método POST
		    method: "POST",

		    // se incluye el formulario creado automáticamente
		    data: formdata,

		    // se indica que no se va a procesar los datos y el contenido a falso
		    processData: false,
		    contentType: false,

		    // si la operación se ha realizado con éxito se devuelve el resultado
		    success: function (data) {

				// se transforma el valor json recibido en array
	    		arrData = JSON.parse(data);

				// se refresca la tabla
				refrescarAreaImagenes(arrData);
		    }

		});

	});


	// función que abre el modal para confirmar que se quiere eliminar la imagen
	// ------------------------------------------------------------------------------
	$("div.areaImagenes").on("click", "div.areaEliminarImagen", function() {

		// se obtiene el id y nombre de la imagen
		var idPersonaje = $("input#idPersonaje").val();
		var nombreImagen = $(this).data("nombre_imagen");

		// se actualiza el nombre  de la imagen a borrar
		$("input#nombreImagen").val(nombreImagen);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(nombreImagen);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id del personaje y el nombre de la imagen
		var idPersonaje = $("input#idPersonaje").val();
		var nombreImagen = $("input#nombreImagen").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/personajeimagenes.php", 

			// Se definen los parámetros
			{
				accion:"eliminarPersonajeImagen",
				id:idPersonaje,
				nombreImagen:nombreImagen
			},
			
			// El resultado se recoge en el data
			// data[0] -> total de registros
			// data[1] -> array de registros
			function(data, status) {
				
				// se transforma el valor json recibido en array
	    		arrData = JSON.parse(data);

				// se refresca la tabla
				refrescarAreaImagenes(arrData);
  			}

  		);						

	});

}); 

</script>

</body>
</html>