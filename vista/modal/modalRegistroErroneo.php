<!-- ------------------------------------------------------------------------------------------
MODAL modalRegistroErroneo                                                    
Modal para mostrar los errores a la hora de querer crear o actualizar un registro
------------------------------------------------------------------------------------------- -->

<div 
    class="modal fade" 
    id="modalRegistroErroneo" 
    tabindex="-1" 
    aria-labelledby="modalRegistroErroneoLabel" 
    aria-hidden="true">

    <div class="modal-dialog">
    <div class="modal-content">
    
        <div class="modal-header">
        
            <h5 class="modal-title" id="modalRegistroErroneoLabel">Datos erróneos</h5>
        
            <button 
                type="button" 
                class="btn-close" 
                data-bs-dismiss="modal" 
                aria-label="Close"></button>
      
        </div>
      
        <div class="modal-body">
            <p id="cuerpoModalRegistroErroneo"></p>
        </div>

        <div class="modal-footer">
            <button 
                type="button" 
                class="btn btn-outline outlinePurple" 
                data-bs-dismiss="modal">Aceptar</button>
      
        </div>
    
    </div>
    </div>

</div>
