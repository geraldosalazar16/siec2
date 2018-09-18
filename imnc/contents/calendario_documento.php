
<span ng-controller="calendario_documento_controller">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script> 
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script> 
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<script type="text/javascript" src="js/datepicker/timepicker.js"></script> 	
	
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Planificador de actividades para Documento</h2></p>
		
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
		<h4><strong>Servicio:</strong> <i>{{tipo_servicio}} </i></h4>
		<h4><strong>Nombre Cliente:</strong> <i>{{nombre_cliente}}</i></h4>
		<h4><strong>Nombre Documento: </strong><i>{{nombre_documento}}</i></h4>
		<h4><strong>Ciclo: </strong><i></i>{{ciclo}}</h4>
		<h4><strong>Etapa: </strong><i>{{nombre_etapa}}</i></h4>
		<h4><strong>Seccion: </strong><i>{{nombre_seccion}}</i></h4>
			
		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
		<!--////////////////////////////////////////////////////////////////////////////////////////-->
			<div id="calendario" class="cal1"></div>
			
<!--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
			
<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalTitulo">Nueva tarea</h4>
			</div>
			<div class="modal-body">
				<form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
														
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Tarea
						<span class="required">*</span></label></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
	<!--					<select ng-model="form.cmbTarea" ng-disabled="bool_tarea === false" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="tarea.id_tarea as tarea.nombre_tarea for tarea in Tareas">
								<option value="">---Seleccione una Tarea---</option>
							</select>  
																	
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul> -->
							<input type="text" required="required" ng-model="form.cmbTarea" ng-disabled="bool_tarea === false" class="form-control col-md-5 col-xs-10" id="tarea" data-parsley-id="2324">
							<span id="tareaerror" class="text-danger"></span>
						</div>
					</div>
					<div class="form-group" id="chkAprobarTarea" >
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Cerrar Tarea</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="checkbox" ng-checked="form.AprobarTarea" id="chkTarea" ng-disabled="bool_estado_tarea === false">
						</div>
					</div>
					<div class="form-group" id="FI">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio
						<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" ng-model="form.fecha_inicio" ng-disabled="bool_estado_tarea === false" class="form-control col-md-5 col-xs-10" id="fecha_inicio" data-parsley-id="2324">
							<span id="fechainicioerror" class="text-danger"></span>
						</div>
					</div>

					<div class="form-group" id="HI">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicio
						<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="texttext" required="required" ng-model="form.hora_inicio" ng-disabled="bool_estado_tarea === false" class="form-control col-md-5 col-xs-10" id="hora_inicio" data-parsley-id="2324">
							<span id="horainicioerror" class="text-danger"></span>
						</div>
					</div>
															
					<div class="form-group" id="FF">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Fin
						<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" ng-model="form.fecha_fin" ng-disabled="bool_estado_tarea === false" class="form-control col-md-5 col-xs-10" id="fecha_fin" data-parsley-id="2324">
							<span id="fechafinerror" class="text-danger"></span>
						</div>
					</div>

					<div class="form-group" id="HF">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin
						<span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" required="required" ng-model="form.hora_fin" ng-disabled="bool_estado_tarea === false" class="form-control col-md-5 col-xs-10" id="hora_fin" data-parsley-id="2324">
							<span id="horafinerror" class="text-danger"></span>
						</div>
					</div>
												
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">{{desc_modificacion_tarea}}
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<textarea rows="4" cols="50" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-model="form.observaciones" ng-disabled="bool_estado_tarea === false" data-parsley-id="2324">
							</textarea>
							<ul class="parsley-errors-list" id="parsley-id-2324"></ul>
							<span id="observacioneserror" class="text-danger"></span>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
												
				<a type="button" class="btn btn-primary" id="hist-button"	href="./?pagina=prospecto_cita_historial&id_cita={{form.id_calendario}}">Historial</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" ng-click="guardar()" id="btnGuardar">Guardar</button>
			</div>
		</div>
	</div>
</div>

<!--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
			
			
			
		</div>
		
          </div>
      </div>
    </div>
  </div>
</div>

<!--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

										<!-- MODAL CREATE-->


