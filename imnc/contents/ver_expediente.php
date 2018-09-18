
<script type="text/javascript">

  <?php
   $id_servicio_cliente_etapa = $_REQUEST["id"];
    echo "var global_id_servicio_cliente_etapa = '" . $id_servicio_cliente_etapa. "';";
		
     ?>

</script>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Expediente</h2></p>
		
        <?php
        /*  if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar etapa';
              echo '  </button>';
              echo '</p>';
          } */
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
		<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
		<h4><strong>Servicio:</strong> <i id="Servicio"> </i></h4>
		<h4><strong>Nombre Cliente:</strong> <i id="Cliente"></i></h4>
		<h4><strong>Referencia: </strong><i id="Referencia"></i></h4>
		
		
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Ciclos"><h4><strong>Ciclo: </strong></h4><span class="required"></span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select class="form-control" id="NombreCiclo">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
			</div>

	<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Etapas"><h4><strong>Etapa: </strong></h4><span class="required"></span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <select class="form-control" id="NombreEtapas">
                  
                </select>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
    </div>

	
	
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
		<!--////////////////////////////////////////////////////////////////////////////////////////-->
			<div class="" role="tabpanel" data-example-id="togglable-tabs" id="ValorSeccion">
				<ul class="nav nav-tabs bar_tabs" role="tablist" id="NombreSeccion">
                </ul>
				<div id="myTabContent" class="tab-content">               
				</div>
			</div>
		</div>
		
          </div>
      </div>
    </div>
  </div>
</div>



<!-- Modal Subir Archivos-->
<div class="modal fade" id="modalSubirArchivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Cargar Documento</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtIdServicio">Id Servicio
              </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" id="txtIdServicio" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
          </div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtIdDocumento">Id Documento
              </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" id="txtIdDocumento" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
			</div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombreCiclo">Ciclo
              </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" id="txtNombreCiclo" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
			</div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombreEtapa">Etapa
              </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" id="txtNombreEtapa" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
			</div>
			<div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombreSeccion">Seccion
              </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" id="txtNombreSeccion" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
			</div>
		 <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Subir un archivo</label>
              <div class="col-md-8 col-sm-6 col-xs-12">
				<input id="fileToUpload" type="file" name="archivo" class="col-md-12 col-sm-12 col-xs-12"/>
              </div>
            </div>
		   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Confirmación-->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="modalTitulo">Confirmación</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group text-center">
              <label class="control-label col-md-12 " >Esta seguro que desea eliminar el registro? </label>
            </div>
            
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="btnEliminar">Aceptar</button>
			</div>
    </div>
  </div>
</div>
</div>
</div>
<!-- Modal Confirmación chk No Aplica-->
<div class="modal fade" id="modalConfirmacionNoAplica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title" id="modalTitulo">Confirmación</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group text-center">
              <label class="control-label col-md-12 " >Esta seguro que este documento no aplica? </label>
            </div>
            
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="btnNoAplica">Aceptar</button>
			</div>
    </div>
  </div>
</div>
</div>
</div>
