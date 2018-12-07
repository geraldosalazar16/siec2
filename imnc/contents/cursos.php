<span ng-controller="cursos_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Cursos</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="InsertarTipoServicio()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar Curso';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title" style="width:40%">Nombre Curso</th>
				<th class="column-title" style="width:30%">Norma</th>
                <th class="column-title" style="width:30%">Tipo de Servicio</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.NOMBRE}}</td>
					<td>{{x.ID_NORMA}}</td>
					<td>{{x.NOMBRE_TIPO_SEVICIO}}</td>					
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" curso="{{x.ID_CURSO}}" style="float: right;" ng-click="EditarCurso(x.ID_CURSO)"> ';
							echo '      <i class="fa fa-edit"> </i> Editar curso';
							echo '    </button>';
						}					
					?>
					</td>	
					
					
				</tr>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="exampleForm"  class="form-horizontal form-label-left" >
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombre">Nombre Curso <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombre" ng-model="formData.txtNombre" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="txtNombreerror" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="tipoServicio">Tipo de servicio  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              
              <select ng-model="formData.tipoServicio" ng-options="tipo.ID as tipo.NOMBRE for tipo in tipo_Servicio"
                                class="form-control" id="tipoServicio" name="tipoServicio" ng-change='cambio_tipoServicio(formData.tipoServicio)' required
                                ng-class="{ error: exampleForm.tipoServicio.$error.required && !exampleForm.$pristine}"></select>
				            <span id="tipoServicioerror" class="text-danger"></span>
              </div>
			  
            </div>
            <div class="form-group">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="selectNorma">Norma <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <select ng-model="formData.selectNorma" ng-options="norma.ID as norma.ID  for norma in optionsList"
                                      class="form-control" id="selectNorma" name="selectNorma" ng-change='cambio_Norma(formData.selectNorma)' required
                                      ng-class="{ error: exampleForm.selectNorma.$error.required && !exampleForm.$pristine}"
                                      ng-disabled="!formData.tipoServicio"></select>
                      <span id="selectedListerror" class="text-danger"></span>
              </div>
      
                  </div>
           
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="guardarCurso()" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
</span>