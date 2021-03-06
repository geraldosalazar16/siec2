<!-- Modal insertar/actualizar Sitios-->
<div class="modal fade" id="modalInsertarActualizarSitios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloSitios">{{modal_titulo_sitio}}</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormSitio" >
			<div class='form-group'>
				<label 	for="cmbClaveClienteDomSitio">Nombre del sitio a auditar <span class="required">*</span></label>
				<select ng-model="formDataSitio.cmbClaveClienteDomSitio" ng-options="ClientesDomicilio.ID as ClientesDomicilio.NOMBRE for ClientesDomicilio in ClientesDomicilios"  class="form-control" id="cmbClaveClienteDomSitio" name="cmbClaveClienteDomSitio" ng-change='cambiocmbClaveClienteDomSitio()' required ng-disabled="accion_sitio=='editar'" ng-class="{ error: exampleFormSitio.cmbClaveClienteDomSitio.$error.required && !exampleFormSitio.$pristine}"></select>
            </div>
			<div class="form-group">
				<label class="control-label">Actividad<span class="required">*</span></label>
				<div style="float: right;"><input type="checkbox" id="formDataSitio.chkActv" ng-model="formDataSitio.chkActv" ng-change="chkActivid()"> <span style="font-size: 11px;">No encuentra actividad</span></div>
				<select ng-model="formDataSitio.txtActividad" ng-options="Actividad.ID as Actividad.ACTIVIDAD for Actividad in Actividades"  class="form-control" id="txtActividad" name="txtActividad" ng-change='cambiotxtActividad()' required ng-class="{ error: exampleFormSitio.txtActividad.$error.required && !exampleFormSitio.$pristine}"  ng-disabled="formDataSitio.chkActv" ></select>
			</div>
			<div class="form-group" ng-if="formDataSitio.chkActv">
				<label>Nueva Actividad<span class="required">*</span></label>
                <input type="text" class="form-control"  id="formDataSitio.nuevaActividad" ng-model="formDataSitio.nuevaActividad" required ng-class="{ error: exampleFormSitio.nuevaActividad.$error.required && !exampleFormSitio.$pristine}" >
			</div>
			<div class="form-group">
				<label>Cantidad de turnos<span class="required">*</span></label>
                <input type="text" class="form-control"  id="formDataSitio.txtCantTurn" ng-model="formDataSitio.txtCantTurn" required  ng-class="{ error: exampleFormSitio.txtCantTurn.$error.required && !exampleFormSitio.$pristine}" >
			</div>
			<div class="form-group">
			<label>Número total de empleados<span class="required">*</span></label>
                <input type="text" class="form-control"  id="formDataSitio.txtNoTotalEmplea" ng-model="formDataSitio.txtNoTotalEmplea" required  ng-class="{ error: exampleFormSitio.txtNoTotalEmplea.$error.required && !exampleFormSitio.$pristine}" >
			</div>
			<div class="form-group">
				<label>Número de empleados con certificación<span class="required">*</span></label>
                <input type="text" class="form-control"  id="formDataSitio.txtNoEmpleaCertif" ng-model="formDataSitio.txtNoEmpleaCertif" required  ng-class="{ error: exampleFormSitio.txtNoEmpleaCertif.$error.required && !exampleFormSitio.$pristine}" >
			</div>
			<div class="form-group">
				<label>Cantidad de procesos<span class="required">*</span></label>
                <input type="text" class="form-control"  id="formDataSitio.txtCantProce" ng-model="formDataSitio.txtCantProce" required  ng-class="{ error: exampleFormSitio.txtCantProce.$error.required && !exampleFormSitio.$pristine}" >
			</div>
			<div class="form-group">
				<label>Tipo de sitio<span class="required">*</span></label>
                <select ng-model="formDataSitio.cmbDuracion" class="form-control" id="cmbDuracion" name="cmbDuracion" ng-change='cambiocmbDuracion()' required ng-class="{ error: exampleFormSitio.cmbDuracion.$error.required && !exampleFormSitio.$pristine}"  >
					<option value="temporal">Temporal</option>
					<option value="fijo">Fijo</option>
				</select>
			</div>
			<div class="form-group">
				<label>¿Matriz o principal?<span class="required">*</span></label>
                <select ng-model="formDataSitio.cmbMatrizPrincipal" class="form-control" id="cmbMatrizPrincipal" name="cmbMatrizPrincipal" ng-change='cambiocmbMatrizPrincipal()' required ng-class="{ error: exampleFormSitio.cmbMatrizPrincipal.$error.required && !exampleFormSitio.$pristine}"  >
					<option value="si">Si</option>
					<option value="no">No</option>
				</select>
			</div>
			
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormSitio(formDataSitio)" ng-disabled="!exampleFormSitio.$valid" value="Guardar"/>
          </form>
   <!--       <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveSitio">ID de sitio de servicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveSitio" placeholder="asignado automaticamente" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClaveTServSitio">Clave de tipo de Servicio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClaveTServSitio" placeholder="" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbClaveClienteDomSitio">Nombre del sitio a auditar <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbClaveClienteDomSitio">
                  
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Actividad<span class="required">*</span>
              <div style="float: right;"><input type="checkbox" id="chkActv"> <span style="font-size: 11px;">No encuentra actividad</span></div>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                 <select class="form-control" id="txtActividad">
                  <option value=""  disabled>-elige una opción-</option>
                </select>
                <input  type="hidden" class="form-control col-md-7 col-xs-12" id="valueActividad"></input>
              </div>
            </div>

            <div id="newActivity" class="form-group" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12">Nueva Actividad<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input  type="text" class="form-control col-md-7 col-xs-12" id="nuevaActividad"></input>
                <ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>

            <div class="form-group" id="formCantidadPersonas">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtCantPerso">Cantidad de personas <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtCantPerso" placeholder="" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtCantTurn">Cantidad de turnos <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtCantTurn" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNoTotalEmplea">Número total de empleados <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNoTotalEmplea" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNoEmpleaCertif">Número de empleados con certificación <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNoEmpleaCertif" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group"  id="nombreProcesos" style="display: none;">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtNombreProcesos">Nombre de los Procesos<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtNombreProcesos" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtCantProce">Cantidad de procesos <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtCantProce" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbDuracion">Tipo de sitio <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbDuracion">
                  
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cmbDuracion">¿Matriz o principal? <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" id="cmbMatrizPrincipal">
                  <option value="elige" selected disabled>-elige una opción-</option>
                  <option value="si">Si</option>
                  <option value="no">No</option>
                </select>
              </div>
            </div>
          </form>	-->
      </div>
      <div class="modal-footer">
 <!--       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarSitio">Guardar</button>	-->
      </div>
    </div>
  </div>
</div>