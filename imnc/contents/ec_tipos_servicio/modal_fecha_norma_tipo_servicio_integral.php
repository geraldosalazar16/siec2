<!-- Modal insertar/actualizar Grupo Auditoria-->
<div class="modal fade" id="modalNormaFechaServIntegral" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloGrupoAuditoria">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
		<form name="exampleFormGrupoAuditorFechaNorma" >
			<div class="form-group">
				<label>Norma a auditar<span class="required">*</span></label>
				<select ng-model="formDataGrupoAuditorFechaNorma.norma" ng-options="norma.ID_NORMA as norma.ID_NORMA for norma in DatosServicio.NORMAS"  class="form-control" ng-change='' required ng-class="{ error: exampleFormGrupoAuditor.cmbRol.$error.required && !exampleFormGrupoAuditor.$pristine}"  ></select>	
			</div>
	
			<br><br><br>
			<input type="submit" class="btn btn-success pull-right mt-2" ng-click="submitFormGrupoAuditorFechaNorma(formDataGrupoAuditorFechaNorma)" ng-disabled="!exampleFormGrupoAuditorFechaNorma.$valid" value="Guardar"/>
		</form>

      </div>
      <div class="modal-footer">
 
      </div>
    </div>
  </div>
</div>