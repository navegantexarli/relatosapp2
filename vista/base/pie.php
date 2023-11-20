<!-- ------------------------------------------------------------------------------------------
PIE DE LA VISTA
------------------------------------------------------------------------------------------- -->

<!-- Javascript para Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

<!-- Versión de jquery -->
<script src="/relatosapp/vista/javascript/jquery-3.5.1.min.js"></script>

<!-- Código Javascript para inicializar comportamientos genéricos -->
<script type="text/javascript">

// definición de todas las autoejecuciones javascript cuando el documento está preparado
$(document).ready(function() {

	// --------------------------------------------------------------------------------
	// AUTOEJECUCIONES AL CARGAR EL DOCUMENTO
	// --------------------------------------------------------------------------------

	// se activa el icono del menú para que pueda cambiar de color al pasar el ratón 
    $("img.iconoMenu").hover(function(){
	    $(this).attr("src","/relatosapp/res/iconos/iconoDarkorangeRelatos.png");
    }).mouseleave(function(){
    	$(this).attr("src","/relatosapp/res/iconos/iconoVioletRelatos.png");
    });


    // se activa el botón cuando se pasa el ratón sobre él
    $("button.outlinePurple").hover(function(){
    	$(this)
    		.css("border-color", "orange")
    		.css("color", "orange")
    		.children().children()
    		.css("color", "orange");
    }).mouseleave(function(){
		$(this)
			.css("border-color", "purple")
			.css("color", "purple")
			.children().children()
			.css("color", "purple");
    });


    // se activa el botón cuando se pasa el ratón sobre él
    $("button.outlineViolet").hover(function(){
    	$(this)
    		.css("border-color", "darkorange")
    		.children().children()
    		.css("color", "darkorange");
    }).mouseleave(function(){
		$(this)
			.css("border-color", "violet")
			.children().children()
			.css("color", "violet");
    });


    // se activa el botón cuando se pasa el ratón sobre él
    $("div.iconoPurple i").hover(function(){
    	$(this).css("color", "orange")
    }).mouseleave(function(){
		$(this).css("color", "purple");
    });


   	// se oculta el botón actualizar si existe
   	if ($("button#botonActualizar").length) $("button#botonActualizar").hide();


	// se inicializan los tooltips
	var tooltipTriggerList = [].slice
		.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	});
	
});

</script>
