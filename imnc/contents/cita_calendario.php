<script type="text/javascript" src="controllers/cita_calendario.js"></script>
<span ng-controller="cita_calendario_controller">
<div class="right_col" rol="main">
	<div class="row">
		<div class="col-md-10 col-sm-10 col-xs-10">
			<div class="x_panel">
				<div class="x_title">CALENDARIO
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div id="calendario" class="cal1"></div>
				</div>
			</div>
			
		</div>
	</div>
</div>



<!-- MODAL CREATE-->
<div class="modal fade" id="modalCreateEvento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Nuevo Evento</h4>
      </div>
      <div class="modal-body">
          <form name="form2" id="form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Asunto<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="asunto" id="asunto" class="form-control col-md-7 col-xs-12" ng-model="form.asunto" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="asuntoerror" class="text-danger"></span>
			  </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Tipo Asunto<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
				<select id="txtAsunto" ng-model = "form.tipo_asunto" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324" ng-options="asu.id_tipo_asunto as asu.descripcion for asu in Asuntos" ><option value="">---Seleccione un Asunto---</option></select>
				<span id="tipoasuntoerror" class="text-danger"></span>
			  </div>
            </div>


            <div class="form-group" id="FI">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Fecha Inicio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="date" required="required" ng-model="form.fecha_inicio" class="form-control col-md-5 col-xs-10" 
                      id="fecha_inicio" data-parsley-id="2324">
                <span id="fechainicioerror" class="text-danger"></span>
			       </div>
            </div>

            <div class="form-group" id="HI">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="time" required="required" ng-model="form.hora_inicio" class="form-control col-md-5 col-xs-10" 
                      id="hora_inicio" data-parsley-id="2324">
                <span id="horainicioerror" class="text-danger"></span>
			       </div>
            </div>

            <div class="form-group" id="HF">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Fin<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="time" required="required" ng-model="form.hora_fin" class="form-control col-md-5 col-xs-10" 
                      id="hora_fin" data-parsley-id="2324">
                <span id="horafinerror" class="text-danger"></span>
			  </div>
            </div>


             <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Recordatorio<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" name="recordatorio" id="recordatorio" class="form-control col-md-7 col-xs-12" ng-model="form.recordatorio" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="recordatorioerror" class="text-danger"></span>
			  </div>
            </div>

             <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Observaciones<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="observaciones" id="observaciones" class="form-control col-md-7 col-xs-12" ng-model="form.observaciones" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
				<span id="observacioneserror" class="text-danger"></span>
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
<script type="text/javascript" src="js/notify.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('#calendario').fullCalendar('render');
            });
    $('#myTab a:first').tab('show');
  })
</script>



