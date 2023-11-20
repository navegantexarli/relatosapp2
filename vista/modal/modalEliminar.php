<!-- ------------------------------------------------------------------------------------------
MODAL modalEliminar                                                    
Modal para confirmar la eliminación de un registro                                     
------------------------------------------------------------------------------------------- -->

<div 
    class="modal fade" 
    id="modalEliminar" 
    tabindex="-1" 
    aria-labelledby="modalEliminarLabel" 
    aria-hidden="true">
  
    <div class="modal-dialog">
    <div class="modal-content">
        
        <div class="modal-header">
            <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
            <button 
                type="button" 
                class="btn-close" 
                data-bs-dismiss="modal" 
                aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
            <p id="cuerpoModalEliminar"></p>
        </div>
        
        <div class="modal-footer">
            
            <button 
              type="button" 
              class="btn btn-outline outlinePurple" 
              data-bs-dismiss="modal">Cancelar</button>
            
            <button 
              id="confirmarEliminarRegistro" 
              type="button" 
              class="btn btn-outline outlinePurple" 
              data-bs-dismiss="modal">Aceptar</button>
          
        </div>
    </div>
    </div>
</div>
