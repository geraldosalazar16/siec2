<span ng-controller="dictaminador_tiposervicio_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <p>
          <button type="button" ng-click="agregar()" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc"
          ng-if='modulo_permisos["registrar"] == 1' style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar Dictaminador
          </button>
        </p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Nombre</th>
                <th class="column-title">Tipo de Servicio que puede dictaminar</th>
				<th class="column-title">&nbsp;</th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in dictaminadores" class="ng-scope even pointer">
					<td>{{x.NOMBRE_USUARIO}}</td>
					<td>
						<ul class="list-unstyled user_data">
							<li ng-repeat = "y in x.TIPOS_SERVICIO">
								<table>
									<tr>
										<td>
											{{y.NOMBRE_TIPO_SERVICIO}}
										</td>
										<td>
											<button class="btn btn-primary btn-xs btn-imnc" ng-if='modulo_permisos["editar"] == 1' ng-click="eliminar(x.ID_USUARIO,y.ID_TIPO_SERVICIO)" ><i class="fa fa-trash" aria-hidden="true"></i></button>
										</td>
									</tr>	
								 </table>
									
							</li>
						</ul>	
					</td>					
					<td>
						<button type="button" ng-click="editar(x.ID_USUARIO)" class="btn btn-primary btn-xs btn-imnc btnEditar"
							ng-if='modulo_permisos["editar"] == 1' style="float: right;">
							<i class="fa fa-edit"> </i> Agregar Tipo Servicio
						</button>
					</td>
				</tr>
            </tbody>
          </table>	

        </div>
      </div>
    </div>
  </div>
</div>


<!--**************************************************************************************-->
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormInsActDict" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
				<div  class='form-group'>
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Usuarios<span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select class="form-control" id="formDataInsActDict.nombreUsuario" 
							ng-model="formDataInsActDict.nombreUsuario"  
							ng-options="nameUser.ID as nameUser.NOMBRE for nameUser in nombreUsuarios" 
							ng-disabled = "accion=='editar'"	
							class="form-control" required 
							ng-class="{ error: exampleFormInsActDict.nombreUsuario.$error.required && !exampleForm.$pristine}" >

						</select>
					</div>
				</div>
				<div  class='form-group'>
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Servicios<span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select class="form-control" id="formDataInsActDict.nombreServicio" 
							ng-model="formDataInsActDict.nombreServicio"  
							ng-options="servicio as servicio.NOMBRE for servicio in nombreServicios" 
							ng-change ="cambio_servicio()"							
							class="form-control" required 
							ng-class="{ error: exampleFormInsActDict.nombreServicio.$error.required && !exampleForm.$pristine}" >

						</select>
					</div>
				</div>
				<div  class='form-group'>
					<label class="control-label col-md-4 col-sm-4 col-xs-12">Nombre Tipo de Servicio<span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select class="form-control" id="formDataInsActDict.nombreTipoServicio" 
							ng-model="formDataInsActDict.nombreTipoServicio"  
							ng-options="item_servicio.ID as item_servicio.NOMBRE for item_servicio in nombreTipoServicios"  
							class="form-control" required 
							ng-class="{ error: exampleFormInsActDict.nombreTipoServicio.$error.required && !exampleForm.$pristine}" >

						</select>
					</div>
				</div>
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="guardar(formDataInsActDict)" ng-disabled="!exampleFormInsActDict.$valid" />
          </form>
      </div>
      <div class="modal-footer">

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
			<h4 class="modal-title" id="modalTitulo">Confirmaci&oacuten </h4>
		</div>
		<div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
			<div class="form-group text-center" style="display: none;">
				<input type="text" ng-model="confirmacion.ID_USUARIO" id="txtIDUsuario"  required="required"  ng-hide="1">
				<input type="text" ng-model="confirmacion.ID_TIPO_SERVICIO" id="txtID_Tipo_Servicio"  required="required" ng-hide="1">
            </div>
            <div class="form-group text-center">
              <label class="control-label col-md-12 " >Esta seguro que desea eliminar el registro? </label>
            </div>
           </form> 
		</div>   
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="btnEliminar" ng-click="eliminar_tipoServicio(confirmacion.ID_USUARIO,confirmacion.ID_TIPO_SERVICIO)">Aceptar</button>
		</div>
    </div>
  </div>
</div>

</span>