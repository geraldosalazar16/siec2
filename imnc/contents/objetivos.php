<span ng-controller="objetivos_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Objetivos</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="openModalInsertar()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar Objetivo';
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
                <th class="column-title" style="width:40%">Objetivo</th>
                <th class="column-title" style="width:30%">Valor</th>
				<th class="column-title" style="width:30%">Periodicidad</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in objetivos" class="ng-scope even pointer">
					<td>{{x.NOMBRE}} {{x.ID_PERIODICIDAD==1 ? " del ": " de " }} {{x.ID_PERIODICIDAD==1 ? x.ANHIO : meses[x.MES -1]['nombre']}} {{x.ID_PERIODICIDAD==1 ?  : " del "meses[x.MES -1]['nombre']}}</td>
					<td>{{x.VALOR_OBJETIVO}}</td>
                    <td>{{x.NOMBRE_PERIODICIDAD}}</td>
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" style="float: right;" ng-click="openModalEditar(x)" ng-show="x.ID_PERIODICIDAD==2"> ';
							echo '      <i class="fa fa-edit"> </i> Editar';
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


<!-- Modal insertar-->
<div class="modal fade" id="modalInsertar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="objetivosForm" name="objetivosForm"  class="form-horizontal form-label-left" >
           
            <div class="form-group">
              <label class="control-label col-md-5 col-sm-5 col-xs-12" for="periodicidad">Propuestas  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              
              <select ng-model="formData.propuestas" ng-options="p.NOMBRE as p.NOMBRE for p in Propuestas"
                                class="form-control" id="propuestas" name="propuestas" ng-disabled="accion == 'editar'" required>
                  <option value="" disabled>---Seleccione la Propuesta---</option>
              </select>
				<span id="propuestas_error" class="text-danger"></span>
              </div>
			  
            </div>
            <div class="form-group" >
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="anhio">Ingrese el año <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" id="anhio" name="anhio" ng-model="formData.anhio" ng-disabled="accion == 'editar'" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103" ></ul>
		        <span id="anhio_error" class="text-danger"></span>
              </div>
      
            </div>
           
            <div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasEnero">Monto Enero del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasEnero" name="montoEmitidasEnero" ng-model="formData.montoEmitidasEnero" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasFebrero">Monto Febrero del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasFebrero" name="montoEmitidasFebrero" ng-model="formData.montoEmitidasFebrero" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasMarzo">Monto Marzo del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasMarzo" name="montoEmitidasMarzo" ng-model="formData.montoEmitidasMarzo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasAbril">Monto Abril del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasAbril" name="montoEmitidasAbril" ng-model="formData.montoEmitidasAbril" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasMayo">Monto Mayo del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasMayo" name="montoEmitidasMayo" ng-model="formData.montoEmitidasMayo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasJunio">Monto Junio del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasJunio" name="montoEmitidasJunio" ng-model="formData.montoEmitidasJunio" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasJulio">Monto Julio del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasJulio" name="montoEmitidasJulio" ng-model="formData.montoEmitidasJulio" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasAgosto">Monto Agosto del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasAgosto" name="montoEmitidasAgosto" ng-model="formData.montoEmitidasAgosto" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasSeptiembre">Monto Septiembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasSeptiembre" name="montoEmitidasSeptiembre" ng-model="formData.montoEmitidasSeptiembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasOctubre">Monto Octubre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasOctubre" name="montoEmitidasOctubre" ng-model="formData.montoEmitidasOctubre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasNoviembre">Monto Noviembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasNoviembre" name="montoEmitidasNoviembre" ng-model="formData.montoEmitidasNoviembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Emitidas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoEmitidasDiciembre">Monto Diciembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoEmitidasDiciembre" name="montoEmitidasDiciembre" ng-model="formData.montoEmitidasDiciembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			 <div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasEnero">Monto Enero del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasEnero" name="montoGanadasEnero" ng-model="formData.montoGanadasEnero" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasFebrero">Monto Febrero del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasFebrero" name="montoGanadasFebrero" ng-model="formData.montoGanadasFebrero" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasMarzo">Monto Marzo del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasMarzo" name="montoGanadasMarzo" ng-model="formData.montoGanadasMarzo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasAbril">Monto Abril del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasAbril" name="montoGanadasAbril" ng-model="formData.montoGanadasAbril" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasMayo">Monto Mayo del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasMayo" name="montoGanadasMayo" ng-model="formData.montoGanadasMayo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasJunio">Monto Junio del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasJunio" name="montoGanadasJunio" ng-model="formData.montoGanadasJunio" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasJulio">Monto Julio del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasJulio" name="montoGanadasJulio" ng-model="formData.montoGanadasJulio" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasAgosto">Monto Agosto del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasAgosto" name="montoGanadasAgosto" ng-model="formData.montoGanadasAgosto" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasSeptiembre">Monto Septiembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasSeptiembre" name="montoGanadasSeptiembre" ng-model="formData.montoGanadasSeptiembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasOctubre">Monto Octubre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasOctubre" name="montoGanadasOctubre" ng-model="formData.montoGanadasOctubre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasNoviembre">Monto Noviembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasNoviembre" name="montoGanadasNoviembre" ng-model="formData.montoGanadasNoviembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			<div class="form-group" ng-show="formData.propuestas=='Propuestas Ganadas'">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="montoGanadasDiciembre">Monto Diciembre del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="montoGanadasDiciembre" name="montoGanadasDiciembre" ng-model="formData.montoGanadasDiciembre" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="guardarObjetivo()"  id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal actualizar-->
<div class="modal fade" id="modalActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="objetivosForm" name="objetivosForm"  class="form-horizontal form-label-left" >
           
            <div class="form-group">
              <label class="control-label col-md-5 col-sm-5 col-xs-12" for="periodicidad">Propuestas  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              
              <select ng-model="formData.propuestas" ng-options="p.NOMBRE as p.NOMBRE for p in Propuestas"
                                class="form-control" id="propuestas" name="propuestas" ng-disabled="accion == 'editar'" required>
                  <option value="" disabled>---Seleccione la Propuesta---</option>
              </select>
				<span id="propuestas_error" class="text-danger"></span>
              </div>
			  
            </div>
            <div class="form-group" >
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="anhio">Ingrese el año <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" id="anhio" name="anhio" ng-model="formData.anhio" ng-disabled="accion == 'editar'" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103" ></ul>
		        <span id="anhio_error" class="text-danger"></span>
              </div>
      
            </div>
           
            <div class="form-group">
            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="monto">Monto {{meses[formData.mes -1]['nombre']}} del {{formData.anhio}}<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="monto" name="monto" ng-model="formData.monto" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103">
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
			
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="guardarObjetivo()" ng-disabled="!objetivosForm.$valid" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal insertar/actualizar-->
<!--
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="objetivosForm" name="objetivosForm"  class="form-horizontal form-label-left" >
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="nombre_objetivo">Nombre del Objetivo <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="nombre_objetivo" name="nombre_objetivo" ng-model="formData.nombre_objetivo" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
				<span id="nombre_objetivo_error" class="text-danger"></span>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="periodicidad">Periodicidad  <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12" >
              
              <select ng-model="formData.periodicidad" ng-options="p.ID as p.NOMBRE for p in periodicidad"
                                class="form-control" id="periodicidad" name="periodicidad" ng-change='cambio_periodicidad(formData.periodicidad)' required>
                  <option value="" disabled>---Seleccione la Periodicidad---</option>
              </select>
				<span id="periodicidad_error" class="text-danger"></span>
              </div>
			  
            </div>
            <div class="form-group" ng-if="formData.periodicidad==1">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="valor_periodicidad">Ingrese el año <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="number" id="valor_periodicidad" name="valor_periodicidad" ng-model="formData.valor_periodicidad" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" ><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
		        <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>
      
            </div>
            <div class="form-group" ng-if="formData.periodicidad==2">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="valor_periodicidad">Mes<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select ng-model="formData.valor_periodicidad" ng-options="m.id as m.nombre  for m in meses"
                          class="form-control" id="valor_periodicidad" name="valor_periodicidad" required>
                      <option value="">---Seleccione un mes---</option>
                  </select>
                   <span id="valor_periodicidad_error" class="text-danger"></span>
              </div>

            </div>

            <div class="form-group" ng-show="formData.periodicidad">
            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="monto">Monto<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" id="monto" name="monto" ng-model="formData.monto" required class="form-control col-md-7 col-xs-12" data-parsley-id="4103" >
                <ul class="parsley-errors-list" id="parsley-id-4103"></ul>
                <span id="monto_error" class="text-danger"></span>
              </div>
            </div>
           
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="guardarObjetivo()" ng-disabled="!objetivosForm.$valid" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>
-->
</span>
