<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="titModal"><?= $titulo; ?></h3>
        </div>
        <div class="modal-body text-center">

        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cerrar</button>
            <button class="btn btn-default btn-success" 
                    data-loading-text="<i class='icon-spin icon-spinner'> </i> Enviando..."
                    onclick="guardarAlgo('<?= $id ?>', $(this));">
                Guardar</button>
        </div>  
    </div>
</div>