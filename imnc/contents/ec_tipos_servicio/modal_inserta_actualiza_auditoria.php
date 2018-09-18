<!--Modal insertar/actualizar Sectores-->
<div class="modal fade" id="modalInsertarActualizarAuditoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloAuditoria">{{modal_titulo_auditoria}}</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormAuditoria" >
			<div class='form-group'>
				<label class="control-label">No Aplicar Regla de Muestreo</label>
				<div style="float: right;">
					<input  type="checkbox" id="formDataAuditoria.chkNoMetodo" ng-model="formDataAuditoria.chkNoMetodo" ng-change="chkNoMetodoChange()"><span style="font-size: 11px;">Se solicitan más sitios a auditar</span>
				</div>
            </div>
			<br>
			<div class="form-group" ng-if="formDataAuditoria.chkNoMetodo">
				<label class="control-label">Sitios a Auditar<span class="required">*</span></label>
                <input type="text" class="form-control"   ng-model="formDataAuditoria.txtSitiosAuditoria" required ng-class="{ error: exampleFormAuditoria.txtSitiosAuditoria.$error.required && !exampleFormAuditoria.$pristine}" >
			</div>
			<div class="form-group">
				<label class="control-label">Días auditor<span class="required">*</span></label>
                <input type="text" class="form-control"   ng-model="formDataAuditoria.txtDuracionAuditoria" required ng-class="{ error: exampleFormAuditoria.txtDuracionAuditoria.$error.required && !exampleFormAuditoria.$pristine}" >
			</div>
			<div class="form-group">
				<label class="control-label">Tipo de Auditor&iacutea <span class="required">*</span></label>
                <select ng-model="formDataAuditoria.cmbTipoAuditoria" ng-options="TiposAuditoria.ID as TiposAuditoria.TIPO for TiposAuditoria in TiposAuditorias"  class="form-control"  ng-change='cambiocmbTipoAuditoria()' required ng-class="{ error: exampleFormAuditoria.cmbTipoAuditoria.$error.required && !exampleFormAuditoria.$pristine}"  ng-disabled="accion_auditoria=='editar'" ></select>
			</div>
			<div class="form-group">
				<label class="control-label">Status de Auditor&iacutea <span class="required">*</span></label>
                <select ng-model="formDataAuditoria.cmbStatusAuditoria" ng-options="StatusAuditoria.ID as StatusAuditoria.STATUS for StatusAuditoria in StatusAuditorias"  class="form-control"  ng-change='cambiocmbTipoAuditoria()' required ng-class="{ error: exampleFormAuditoria.cmbStatusAuditoria.$error.required && !exampleFormAuditoria.$pristine}" ></select>
			</div>
			
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormAuditoria(formDataAuditoria)" ng-disabled="!exampleFormAuditoria.$valid" value="Guardar"/>
          </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>