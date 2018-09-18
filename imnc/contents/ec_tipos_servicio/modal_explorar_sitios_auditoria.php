

<!-- Modal Explorar Sitios Auditoria-->
<div class="modal fade" id="modalExplorarSitios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow-y: auto;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloExplorarSitios">Seleccionar sitio</h4>
      </div>
      <div class="modal-body" id="body-modalExplorarSitios">
        <span ng-show="cant_sitios == 0">Actualmente el servicio no cuenta con sitios cargados o los existentes ya están cargados en la auditoría.</span>
        <table class="table">
          <thead id="thead-modal-explora-sitios">
            <tr>
				<th>Domicilio del cliente</th>
				<th>Cantidad de Turnos</th>
				<th>Numero Total de Empleados</th>
				<th>Numero de Empleados con Certificacion</th>
				<th>Duracion</th>
				<th>Seleccionar</th>
			</tr>
          </thead>
          <tbody id="tbody-modal-explora-sitios">
            <tr ng-repeat="sitio in SitiosParaAuditoria">
                <td>
                    {{sitio.NOMBRE_DOMICILIO}}
                </td>
                <td>
                    {{sitio.CANTIDAD_TURNOS}}
                </td>
                <td>
                    {{sitio.NUMERO_TOTAL_EMPLEADOS}}
                </td>
                <td>
                    {{sitio.NUMERO_EMPLEADOS_CERTIFICACION}}
                </td>
                <td>
                    {{sitio.TEMPORAL_O_FIJO}}
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarSitio" 
                    ng-click="agregar_sitio_auditoria(sitio.ID_CLIENTE_DOMICILIO,sitio.ID_SERVICIO_CLIENTE_ETAPA,id_tipo_auditoria,ciclo)"
                     style="float: right;"> 
                        Seleccionar 
                    </button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>