

<!-- Modal Explorar Sitios Auditoria-->
<div class="modal fade" id="modalExplorarSitiosEC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="overflow-y: auto;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTituloExplorarSitios">Seleccionar sitio</h4>
      </div>
      <div class="modal-body" id="body-modalExplorarSitios">
        <span ng-show="cant_sitiosEC == 0">Actualmente el servicio no cuenta con sitios cargados o los existentes ya están cargados en la auditoría.</span>
        <table class="table">
          <thead id="thead-modal-explora-sitios">
            <tr>
				<th>Domicilio del cliente</th>
				<th>Datos del Sitio</th>
				
			</tr>
          </thead>
          <tbody id="tbody-modal-explora-sitios">
            <tr ng-repeat="sitio in SitiosParaAuditoriaEC">
                <td>
                    {{sitio.NOMBRE_DOMICILIO}}
                </td>
                <td>
					<ul class="list-unstyled user_data">
						<li  ng-repeat = "f in sitio.DATOS">
							{{f.NOMBRE_META_SITIOS}}:
							<i ng-show="f.TIPO_META_SITIOS!=2"> {{f.VALOR}}</i>
							<i ng-show="f.TIPO_META_SITIOS==2" ng-init="mostrarvalorselectsitios(f.VALOR)"> {{resp1[f.VALOR]}}</i>
						</li>
					</ul>
				</td>
				<td>
                    <button type="button" class="btn btn-primary btn-xs btn-imnc btnSeleccionarSitio" 
                    ng-click="agregar_sitio_auditoria(sitio.ID_CLIENTE_DOMICILIO,id_servicio_cliente_etapa,id_tipo_auditoria,ciclo)"
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