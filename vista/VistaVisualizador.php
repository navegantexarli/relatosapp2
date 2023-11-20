<!-- ------------------------------------------------------------------------------------------
VistaVisualizador
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
	<div class="tituloPagina"><h1><?php echo $relato->getTitulo(); ?></h1></div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaVisualizador'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div>
		
		<!-- se define el hidden para el id del registro a editar -->
		<input 
			type="hidden" 
			id="idRelato" 
			name="idRelato" 
			value="<?php echo $relato->getId(); ?>">
		
		<input 
			type="hidden" 
			id="cantidadNodos" 
			name="cantidadNodos" 
			value="<?php echo $relato->getCantidadNodos(); ?>">
	</div>

	
	<?php

		// Cálculo de los ajustes de las imágenes con respecto al texto

		// se define el array de ajuste de columnas
		// (columna para el texto, columna para bloque imágenes, columna para celda de imagen)
		$arrAjusteColumnasBasico = array(array(12, 0, 0), array(9, 3, 12), array(6, 6, 6));

		// se indica por defecto el primer ajuste relativo al caso de no existir imágenes
		$arrAjusteColumnas = $arrAjusteColumnasBasico[0];

		// si hay una sola imagen...
		if (count($arrPersonajesNodo) == 1) $arrAjusteColumnas = $arrAjusteColumnasBasico[1];

		// si hay más de una imagen...
		else if (count($arrPersonajesNodo) > 1) $arrAjusteColumnas = $arrAjusteColumnasBasico[2];

		// Cálculo del porcentaje del progreso de la visualización del relato
		$porcentaje = round((100 * $nodo->getOrden()) / $relato->getCantidadNodos());

	?>

	<!-- Area para ubicar el cuerpo visual del relato -->
	<div class="areaVisualizacion">
  	
	<!-- Fila que contiene el texto y el bloque de imágenes -->
  	<div class="row">
    	
    	<!-- Columna para ubicar el texto del nodo -->    	
    	<div class="col-<?php echo $arrAjusteColumnas[0]; ?> areaColumnaTexto">
      		<p class="texto"><?php echo nl2br($nodo->getTexto()); ?></p>
    	</div>
    	
    	<!-- Columna para ubicar las imágenes -->
    	<div class="col-<?php echo $arrAjusteColumnas[1]; ?> areaColumnaImagenes">

			<div class="row">

	      		<!-- Por cada imagen del personaje se creará una estructura -->
				<?php foreach ($arrPersonajesNodo as $personajeNodo) { ?>

					<!-- se define la ruta de la imagen -->
					<?php 
						// se calcula la ruta a la imagen
						$idPersonaje = "p".$personajeNodo->getIdPersonaje();
						$nombreImagen = $personajeNodo->getNombreImagen();
						$rutaImagen = "/relatosapp/res/imagenes/".$idPersonaje."/".$nombreImagen;
					?>

					<!-- se crea el área para la imagen y se ubica la imagen -->
					<div class="col-<?php echo $arrAjusteColumnas[2]; ?> areaCeldaImagen">
				    	<img 
				    		src="<?php echo $rutaImagen; ?>" 
				    		width="100px" 
				    		height="300px" 
				    		alt="Lights" 
				    		style="width:100%">
					</div>

				<?php } ?>

			</div>

    	</div>
	
	</div>


	<!-- Fila para ubicar el area de la barra de desplazamiento y el panel -->
	<div class="row">
	
		<!-- Primera columna, vacía -->
		<div class="col-3"></div>

		<!-- Segunda columna, barra de desplazamiento para ver el progreso del relato -->
		<div class="col-6">
	        
	        <div class="areaProgreso">
	        <div class="progress">
	            <div 
	            	id="progressBarVisualizarRelato" 
	            	class="progress-bar progress-bar-striped progress-bar-animated" 
	            	role="progressbar" 
	            	aria-valuenow="100" 
	            	aria-valuemin="0" 
	            	aria-valuemax="100" 
	            	style="width: <?php echo $porcentaje; ?>%"><?php echo $porcentaje; ?>%
	            </div>
	        </div>    
	        </div>  		

			<!-- Panel de desplazamiento del visor -->		
			<div 
				class="
					btn-toolbar 
					d-flex 
					justify-content-center 
					align-items-center 
					panelDesplazamiento" 
				role="toolbar" 
				aria-label="">
		  		
		  		<!-- Botón inicio de relato -->
		  		<div class="btn-group me-2 areaNodoInicio" role="group" aria-label="First group">
		    		<button 
		    			type="button" 
		    			class="btn btn-outline outlinePurple abrirNodo"
		    			data-id_nodo="<?php echo $relato->getIdNodoIni(); ?>">
						<span>
							<i 
								class="bi bi-skip-backward" 
								style="font-size:1rem; color:purple;"
				    			data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Volver al principio del relato</em>">
							</i></span></button>
		  		</div>	

		  		<!-- Botón nodo anterior -->
		  		<div class="btn-group me-2 areaNodoPadre" role="group" aria-label="First group">
		    		<button 
		    			type="button" 
		    			class="btn btn-outline outlinePurple abrirNodo"
		    			data-id_nodo="<?php echo $nodo->getIdNodoPadre(); ?>">
		    			<span>
							<i 
								class="bi bi-skip-start" 
								style="font-size:1rem; color:purple;"
				    			data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Volver a la página anterior</em>">
							</i></span></button>
		  		</div>	

				<!-- input para busca un nodo -->
				<div class="input-group">
					
					<input 
						id="nodoActual"
						type="text" 
						class="form-control" 
						placeholder="Guión" 
						aria-label="" 
						value="<?php echo $nodo->getId(); ?>">
					
					<button 
						class="btn btn-outline outlinePurple abrirNodo" 
						type="button">
	  					<span>
	  						<i 
	  							class="bi bi-search" 
	  							style="font-size:1rem; color:purple;"
				    			data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Abrir página</em>"></i></span></button>
				</div>

		  		<!-- Botón nodo posterior -->
		  		<div 
		  			class="btn-group me-2 ml-2 areaNodoHijo" 
		  			role="group" 
		  			aria-label="First group">
		    		<button 
		    			type="button" 
		    			class="btn btn-outline outlinePurple abrirNodo"
		    			data-id_nodo="<?php echo $nodo->getIdNodoHijo(); ?>">
		    			<span>
							<i 
								class="bi bi-skip-end" 
								style="font-size:1rem; color:purple;"
				    			data-placement="top"
								data-bs-toggle="tooltip"
								data-bs-html="true" 
								title="<em>Pasar a la siguiente página</em>"></i></span></button>
		  		</div>	

		  	</div>  

	  	</div> 

		<!-- Tercera columna, vacía -->
		<div class="col-3"></div>

	</div>


	<!-- pie de la aplicación -->
	<?php require 'base/pie.php' ?>



	<!-- -----------------------------------------------------------------------------------
	INSTANCIACION DE LOS MODAL
	------------------------------------------------------------------------------------ -->

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

	// el nodo padre se oculta porque cuando se carga la página siempre se hace con 
	// el nodo inicial del relato
	$("div.areaNodoPadre").hide();



	// --------------------------------------------------------------------------------
	// FUNCIONES DE LA VISTA
	// --------------------------------------------------------------------------------

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


	// función: abrirNodo
	// Recoge el id del nodo, realiza la llamada AJAX y actualiza el entorno para
	// mostrar toda la información relativa al nodo abierto
	// ------------------------------------------------------------------------------
	function abrirNodo(idRelato, idNodo) {

		// se hace la llamada ajax para obtener el nodo
		$.post("/relatosapp/visualizador.php", 

			// Se definen los parámetros
			{
				accion:"abrirNodo", 
				idRelato:idRelato,
				idNodo:idNodo
			},
			
			// El resultado ha llegado en data
			function(data, status) {

				// se transforma el valor json recibido en array
				arrData = JSON.parse(data);

				// si no ha habido error...
				if (arrData[0].localeCompare('ERROR') != 0) {

					// se refresca la tabla
					refrescarNodo(arrData[1], arrData[2]);

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
	

	// función privada que actualiza el nodo con el registro recibido por AJAX
	// --------------------------------------------------------------------------
	function refrescarNodo(nodo, arrPersonajesNodo) {

		// Cálculo de los ajustes de las imágenes con respecto al texto

		// se define el array de ajuste de columnas
		// (columna para texto, columna para bloque imágenes, columna para celda de imagen)
		arrAjusteColumnasBasico = [[12, 0, 0], [9, 3, 12], [6, 6, 6]];

		// se indica por defecto el primer ajuste relativo al caso de no existir imágenes
		arrAjusteColumnas = arrAjusteColumnasBasico[0];

		// si hay una sola imagen...
		if (arrPersonajesNodo.length == 1) arrAjusteColumnas = arrAjusteColumnasBasico[1];

		// si hay más de una imagen...
		else if (arrPersonajesNodo.length > 1) arrAjusteColumnas = arrAjusteColumnasBasico[2];

		// se obtiene la cantidad de nodos del relato
		cantidadNodos = $("input#cantidadNodos").val();

		// Cálculo del porcentaje del progreso de la visualización del relato
		porcentaje = Math.round((100 * nodo.orden) / cantidadNodos);

		// se actualiza la estructura de las columnas
		$("div.areaColumnaTexto")
			.attr('class', 'col-'+arrAjusteColumnas[0]+' areaColumnaTexto');
		$("div.areaColumnaImagenes")
			.attr('class', 'col-'+arrAjusteColumnas[1]+' areaColumnaImagenes');

		// se formatea el texto para que detecte los saltos de linea
		var texto = nodo.texto.replace(/\n/g, "<br/>");

		// se actualiza el html con el texto del nodo
		$("p.texto").html(texto);

		// se limpia el area de las imágenes
		$("div.areaColumnaImagenes div.row").html('');

		// se recorre el array de personajes dle nodo
		arrPersonajesNodo.forEach(function(personajeNodo){

			// se define la ruta de la imagen
			idPersonaje = "p"+personajeNodo.idPersonaje;
			nombreImagen = personajeNodo.nombreImagen;
			rutaImagen = "/relatosapp/res/imagenes/"+idPersonaje+"/"+nombreImagen;

			// se crea el área para la imagen y se ubica la imagen
			$("div.areaColumnaImagenes div.row").append(

				'<div class="col-'+arrAjusteColumnas[2]+' areaCeldaImagen">'+
		    		'<img '+
		    			'src="'+rutaImagen+'" '+
		    			'width="100px" '+
		    			'height="300px" '+
		    			'alt="Lights" '+
		    			'style="width:100%">'+
				'</div>'

			);

		});

		// se actualiza el porcentaje de relato visualizado:
		
		// se crea el porcentaje con el símbolo %
		porcentaje = porcentaje+'%';

		// se actualiza la barra de porcentaje visualizado
		$("div#progressBarVisualizarRelato").html(porcentaje);
		$("div#progressBarVisualizarRelato").css('width', porcentaje);

		// si hay nodo padre...
		if (nodo.idNodoPadre) {
		
			// se muestra el nodo padre
			$("div.areaNodoPadre").show();

			// se actualiza el padre
			$("div.areaNodoPadre button").data("id_nodo", nodo.idNodoPadre);
			/*$("div.areaNodoPadre button").text(nodo.idNodoPadre);*/
		
		// si no hay nodo padre se oculta
		} else $("div.areaNodoPadre").hide();

		// se actualiza el valor dle input
		$("input#nodoActual").val(nodo.id);

		// si hay nodo hijo...
		if (nodo.idNodoHijo) {
		
			// se muestra el nodo hijo
			$("div.areaNodoHijo").show();

			// se actualiza el hijo
			$("div.areaNodoHijo button").data("id_nodo", nodo.idNodoHijo);
			/*$("div.areaNodoHijo button").text(nodo.idNodoHijo);*/
		
		// si no hay nodo hijo se oculta
		} else $("div.areaNodoHijo").hide();

	}



	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------
 
 	// evento: abrirNodo
	// evento que abre un párrafo según id de párrafo solicitado
	// ----------------------------------------------------------------------
	$("div.panelDesplazamiento").on("click", "button.abrirNodo", function() {

		// se obtiene el id del relato y del nodo que se desea abrir
		var idRelato = $("input#idRelato").val();
		var idNodo = (typeof $(this).data("id_nodo") !== 'undefined') ?
			$(this).data("id_nodo") : $('input#nodoActual').val();

		// se hace la llamada a la función que se encarga de abrir el nodo
		abrirNodo(idRelato, idNodo);

	}); 

}); 

</script>

</body>
</html>