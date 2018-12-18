<!-- Modal Explorar Grupo Auditoria-->
<div class="modal fade" id="modalExplorarGrupo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow-y: auto;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloExplorarGrupo">Seleccionar auditor</h4>
      </div>
      <div class="modal-body" id="body-modalExplorarGrupo">
		<span ng-show="cant_auditores == 0">Actualmente el servicio no cuenta con auditores o los existentes ya est�n cargados en la auditor�a.</span>
          <table class="table">
          <thead id="thead-modal-explora-sitios">
            <tr>
				<th>Datos del Auditor</th>
				<th>Sectores que cubre</th>
				<th></th>
			</tr>
          </thead>
          <tbody id="tbody-modal-explora-sitios">
            <tr ng-repeat="auditor in AuditoresParaAuditoria">
                <td td style="font-size: 12px;">
                    {{auditor.NOMBRE_COMPLETO}}<br>
					{{auditor.REGISTRO}}<br>
					{{auditor.STATUS}}<br>
					
                </td>
                <td style="font-size: 11px;">
					<ul class="list-unstyled user_data">
						<li> {{auditor.TOTAL}}</li>
						<li ng-repeat="r in auditor.CALIFICACIONES">
								{{r.ID_SECTOR}}-{{r.NOMBRE_SECTOR}}({{r.ROL}}) Sector NACE: {{r.SECTOR_NACE}}
														
						</li>
					</ul>	
                </td>
               
                <td>
					<button type="button" class="btn btn-default btn-xs" style="float: right;" disabled ng-if="auditor.EN_GRUPO"> en auditoria </button>
					<button type="button" class="btn btn-default btn-xs" style="float: right;" disabled ng-if="!auditor.EN_GRUPO && auditor.STATUS != 'activo'"> {{auditor.STATUS}} </button>
					<button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarGrupo" style="float: right;" ng-if="!auditor.EN_GRUPO && auditor.STATUS == 'activo'" ng-click="cargarModalInsertarActualizarGrupoAuditor(auditor.PT_CALIF_ID,auditor.NOMBRE_COMPLETO)"> seleccionar </button>
              <!--      <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarSitio" 
                    ng-click="agregar_sitio_auditoria()"
                     style="float: right;"> 
                        Seleccionar 
                    </button> -->
                </td>
            </tr>
			 <tr ng-repeat="auditor in AuditoresParaAuditoria1">
                <td td style="font-size: 12px;">
                    {{auditor.NOMBRE_COMPLETO}}<br>
					{{auditor.REGISTRO}}<br>
					{{auditor.STATUS}}<br>
					
                </td>
                <td style="font-size: 11px;">
					<ul class="list-unstyled user_data">
						<li> {{auditor.TOTAL}}</li>
						<li>{{auditor.ROL}}</li>
					</ul>	
                </td>
               
                <td>
					<button type="button" class="btn btn-default btn-xs" style="float: right;" disabled ng-if="auditor.EN_GRUPO"> en auditoria </button>
					<button type="button" class="btn btn-default btn-xs" style="float: right;" disabled ng-if="!auditor.EN_GRUPO && auditor.STATUS != 'activo'"> {{auditor.STATUS}} </button>
					<button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarGrupo" style="float: right;" ng-if="!auditor.EN_GRUPO && auditor.STATUS == 'activo'" ng-click="cargarModalInsertarActualizarGrupoAuditor(auditor.PT_CALIF_ID,auditor.NOMBRE_COMPLETO)"> seleccionar </button>
              <!--      <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarSitio" 
                    ng-click="agregar_sitio_auditoria()"
                     style="float: right;"> 
                        Seleccionar 
                    </button> -->
                </td>
            </tr>
			
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>