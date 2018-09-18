<span ng-controller="expediente_documento_controller">
<style>
.selector.noshadow {
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
	}
  .break-word {
    width: 100%;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-8 col-sm-8 col-xs-8">
      <div class="x_panel">
        <div class="x_title">
        <p><h2 class="break-word">{{titulo}}</h2></p>
          
            <p >
          <button type="button" ng-click="agregar()" ng-if='finalizado == 0 && modulo_permisos["registrar"] == 1' id="btnNuevo" 
          class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar Tipo de Documento
          </button>
          
             <a href="./?pagina=tipo_expediente" class="btn btn-primary btn-xs btn-imnc" style="float: right;">
              <i class="fa fa-edit"> </i> Regresar
            </a>
          </p>
        
          <div class="clearfix"></div>
        </div>
		
        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">#</th>
                <th class="column-title">Tipo de Documento</th>
                <th class="column-title">Obligatorio</th>
				<th class="column-title" ng-if="finalizado == 1">Habilitado</th>
				<th class="column-title">&nbsp;</th>
				
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in expedientedocumento" class="ng-scope even pointer">
					<td>{{$index + 1}}</td>
					<td>{{x.NOMBRE_DOCUMENTO}}</td>
					<td ng-if="x.OBLIGATORIO == 0">No</td>
					<td ng-if="x.OBLIGATORIO == 1">S&iacute;</td>
					<td ng-if="x.HABILITADO == 0 && finalizado == 1" >No</td>
					<td ng-if="x.HABILITADO == 1 && finalizado == 1" >S&iacute;</td>
					<td ng-if="finalizado == 0 ">
						<button type="button" ng-click="editar(x.ID)" class="btn btn-primary btn-xs btn-imnc"
            ng-if='modulo_permisos["editar"] == 1' style="float: right;">
							<i class="fa fa-edit"> </i> Editar
						</button>
					</td>
					<td ng-if="finalizado == 1">
						&nbsp;
					</td>
					
				</tr>
            </tbody>
          </table>	
			<button ng-if='finalizado == 0 && modulo_permisos["editar"] == 1' type="button" ng-click="finalizar()" 
      class="btn btn-primary btn-xs btn-imnc" style="float: left;">
						<i class="fa fa-edit"> </i> Finalizar expediente
			</button>
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
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
			<div class="form-group">
				<label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo de documento<span class="required">*</span>
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select id="tipo_documento" ng-style="selectStyle"  class="form-control" ng-model="selectDocumento"/>
						<option ng-repeat="option in selectlistdocumento" value="{{option.ID}}">{{option.NOMBRE}}</option>

					</select>
					<span id="documentoerror" class="text-danger"></span>
					<input ng-style="tipoDocumentoStyle" type="text" ng-model="tipo_documento_texto" class="form-control col-md-7 col-xs-12 selector noshadow" readonly="true"></span>
					</div>
				
			</div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Obligatorio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
				<input type="hidden" ng-model="id"/>
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbobligatorio" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
            <div class="form-group" ng-if="finalizado == 1">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Habilitado<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" class="form-control col-md-7 col-xs-12 selector noshadow" ng-model="cbhabilitado" data-parsley-id="2324" ><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" ng-click="guardar()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
