<!-- ------------------------------------------------------------------------------------------
VistaValoresPalabraClave
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
		
		<h1>Valores de palabra clave</h1>
		
		<!-- se indica información acerca de la palabra clave para la que se muestran valores -->
		<button 
			type="button" 
			class="btn btn-outline outlinePurpleInactivo" 
			disabled>
			Palabra clave: 
			<span class="badge bg"><?php echo $palabraClave->getNombre(); ?></span></button>
	
	</div>

	<!-- se define la vista activa -->
	<?php $vistaActiva = 'VistaValoresPalabraClave'; ?>

	<!-- menú principal de la aplicación -->
	<?php require 'base/menu.php' ?>			

	<!-- Area para crear y modficar registros -->
	<div class="areaFormulario">

		<!-- se define el hidden para el id de la palabra clave -->
		<input 
			type="hidden" 
			id="idPalabraClave" 
			name="idPalabraClave" 
			value="<?php echo $palabraClave->getId(); ?>"> 

		<!-- se define el hidden para el valor del valor de palabra clave a editar -->
		<!-- se utilizará junto a idPalabraClave para establecer la PK del valor   -->
		<input 
			type="hidden" 
			id="valorValorPCEditada" 
			name="valorValorPCEditada" 
			value="0"> 

		<!-- inputs para introducir un registro -->
		<div class="input-group">

			<input 
				id="valorValorPC" 
				type="text" 
				class="form-control" 
				placeholder="Valor de palabra clave" 
				aria-label="Valor de la palabra clave">

			<input 
				id="nivelValorPC" 
				type="text" 
				class="form-control" 
				placeholder="Nivel de valor de palabra clave" 
				aria-label="Nivel de valor de palabra clave">	

			<button 
				id="buscarValoresPC" 
				class="btn btn-outline outlinePurple" 
				type="button">
				<span>
					<i 
						class="bi-search" 
						style="font-size:1.5rem; color:purple;"
		    			data-placement="top"
						data-bs-toggle="tooltip"
						data-bs-html="true" 
						title="<em>Buscar valores de palabra clave</em>"></i></span></button>

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
						title="<em>Crear palabra clave</em>"></i></span></button>
		</div>

	</div>

	<!-- Area para ubicar la tabla de registros -->
	<div class="areaTabla">

		<!-- Se indica el número de registros cargados y los que hay en total -->
		<p class="numRegistros">Mostrando 
			<span id="numRegistros"><?php echo count($arrValoresPalabraClave); ?></span> de 
			<span id="totalRegistros"><?php echo $numRegistros; ?></span></p>

		<!-- tabla de registros -->
		<table 
			id="tablaValoresPC" 
			class="table table-dark table-striped">
			
			<thead>
		    	<tr>
		        	<th scope="col" style="width: 70%;">Valores de palabra clave</th>
			        <th scope="col" style="width: 25%;" class="columnaCentrada">Niveles</th>
			        <th scope="col" style="width: 5%;"></th>
		    	</tr>
		    </thead>

		    <tbody id="cuerpoTabla">
		    	
		    	<?php foreach ($arrValoresPalabraClave as $valorPC) { ?>
		    		
			    	<tr 
			    		class="datosTabla"
			        	data-valor="<?php echo $valorPC->getValor(); ?>"
			        	data-nivel="<?php echo $valorPC->getNivel(); ?>">
			        	
			        	<td class="valorValorPC datosCelda">
			        		<?php echo $valorPC->getValor(); ?>
			        	</td>
			        	
			        	<td class="nivelValorPC datosCelda columnaCentrada">
			        		<?php echo $valorPC->getNivel(); ?>
			        	</td>			        	
			        	
			        	<td class="eliminarValorPC">
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
	function refrescarTabla(arrValoresPC) {

		// se limpia la lista de valores de palabra clave seleccionables
		$("#tablaValoresPC tbody tr").remove();

		// se recorre el array de valores de palabra clave
		arrValoresPC.forEach(function(valorPC) {

			// se añade el guión a la tabla
			$("#tablaValoresPC tbody").append(

				'<tr '+
		    		'class="datosTabla" '+
		        	'data-valor="'+valorPC.valor+'" '+
		        	'data-nivel="'+valorPC.nivel+'"> '+
		        	
		        	'<td class="valorValorPC datosCelda"> '+
		        		valorPC.valor+
		        	'</td> '+
		        	
		        	'<td class="nivelValorPC datosCelda columnaCentrada"> '+
		        		valorPC.nivel+
		        	'</td> '+
		        	
		        	'<td class="eliminarValorPC"> '+
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
	function asignarErrores(valorValorPC, nivelValorPC) {

		// se limpia el registro de errores
		$('#cuerpoModalRegistroErroneo').html('');

		// se define el mensaje de cabecera de los errores
		$('#cuerpoModalRegistroErroneo').append('Se ha producido los siguiente errores:<br>');

		// si no hay valor de valor de palabra clave se añade el mensaje de error
		if (valorValorPC.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Valor está vacío<b><br>');
		}

		// si no hay nivel de valor de palabra clave se añade el mensaje de error
		if (nivelValorPC.trim().length == 0) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nivel está vacío<b><br>');
		}

		// si el nivel de palabra clave introducido no es un entero se añade mensaje de error
		if (nivelValorPC != parseInt(nivelValorPC)) {
			$('#cuerpoModalRegistroErroneo').append('<b>- Nivel no es un entero<b><br>');
		}
	}


	// función relativa al modal modalEliminar
	// función que crea el cuerpo del mensaje del modal de eliminación
	function crearCuerpoModalEliminacion(valorValorPC) {

		// se limpia el registro de errores
		$('#cuerpoModalEliminar').html('');

		// se construye el texto para el cuerpo del mensaje
		strHTML = '¿Quieres eliminar el valor <b>'+valorValorPC+'</b>?';

		// se define el mensaje del cuerpo
		$('#cuerpoModalEliminar').append(strHTML);

	}

 

	// --------------------------------------------------------------------------------
	// EVENTOS DE LA VISTA
	// --------------------------------------------------------------------------------

	// función que busca registros según un filtro
	// ------------------------------------------------------------------------------
	$("button#buscarValoresPC").click(function() {

		// se obtiene el id de la palabra clave
		var idPalabraClave = $("input#idPalabraClave").val();

		// se obtiene el valor y nivel de la palabra clave
		var valorValorPC = $("input#valorValorPC").val();
		var nivelValorPC = $("input#nivelValorPC").val();

		// se hace la llamada ajax para obtener la lista de valores de palabra clave
		$.post("/relatosapp/valorespalabraclave.php", 

			// Se definen los parámetros
			{
				accion:"buscarValoresPalabraClave", 
        		idpc:idPalabraClave,
        		valor:valorValorPC,
        		nivel:nivelValorPC        				
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
	$("table#tablaValoresPC").on("click", "td.datosCelda", function() {

		// se obtiene el valor y nivel de palabra clave pulsada
		valorValorPC = $(this).parent().data("valor");
		nivelValorPC = $(this).parent().data("nivel");

		// se guarda el valor en el hidden del valor de palabra clave editada
		$("input#valorValorPCEditada").val(valorValorPC);

		// se muestra el valor y nivel del valor de palabra clave en el input
		$("input#valorValorPC").val(valorValorPC);
		$("input#nivelValorPC").val(nivelValorPC);

	});


	// función que crea un nuevo registro
	// ------------------------------------------------------------------------------
	$("button#botonCrear").click(function() {        		

		// se obtiene el id de la palabra clave
		var idPalabraClave = $("input#idPalabraClave").val();

		// se obtiene el valor y nivel del valor de la palabra clave
		var valorValorPC = $("input#valorValorPC").val();
		var nivelValorPC = $("input#nivelValorPC").val();

		// si el usuario no ha incurrido en errores se continúa
		if ((valorValorPC.trim().length > 0) &&
			(nivelValorPC.trim().length > 0) &&
			(nivelValorPC == parseInt(nivelValorPC))) {

    		// se hace la llamada ajax para obtener la lista de valores de palabra clave
    		$.post("/relatosapp/valorespalabraclave.php", 

    			// Se definen los parámetros
    			{
    				accion:"crearValorPalabraClave",
	        		idpc:idPalabraClave,
	        		valor:valorValorPC,
    	    		nivel:nivelValorPC
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

    	// si el valor de valor de palabra clave está vacío se muestra el error
		} else {

			// se abre el modal para mostrar el error cometido
			$('#modalRegistroErroneo').modal('show');

			// se asigna al modal todos los errores en que se ha incurrido
			asignarErrores(valorValorPC, nivelValorPC);
		}

	});


	// función que abre el modal para confirmar que se quiere eliminar el registro
	// ------------------------------------------------------------------------------
	$("table#tablaValoresPC").on("click", "td.eliminarValorPC", function() {

		// se obtiene el valor y nivel del valor de la palabra clave
		var valorValorPC = $(this).parent().data("valor");
		var nivelValorPC = $(this).parent().data("nivel");

		// se actualiza el valor y nivel de la palabra clave editada
		$("input#valorValorPC").val(valorValorPC);
		$("input#nivelValorPC").val(nivelValorPC);

		// se actualiza el valor del valor de palabra clave editada
		$("input#valorValorPCEditada").val(valorValorPC);

		// se abre el modal para la confirmación de la eliminación
		$('#modalEliminar').modal('show');

		// se crea el cuerpo del modal de eliminación
		crearCuerpoModalEliminacion(valorValorPC);

	});		


	// función que finalmente lanza la petición de eliminación al controlador
	// ------------------------------------------------------------------------------
	$("button#confirmarEliminarRegistro").click(function() {
	
		// se obtiene el id de la palabra clave
		var idPalabraClave = $("input#idPalabraClave").val();

		// se obtiene el valor del valor de la palabra clave
		var valorValorPC = $("input#valorValorPC").val();

		// se hace la llamada ajax para solicitar la eliminación
		$.post("/relatosapp/valorespalabraclave.php", 

			// Se definen los parámetros
			{
				accion:"eliminarValorPalabraClave",
				idpc:idPalabraClave,
				valor:valorValorPC
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