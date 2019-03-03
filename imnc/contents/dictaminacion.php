<span ng-controller="dictaminacion_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
       
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
				
				<ul class="list-unstyled user_data">
					<li ><b>
					Dictaminador:</b><i> {{DatosServicio.NOMBRE_DICTAMINADOR}}</i>
					</li>
					<li ><b>
					Tipos de Servicio: </b><i> {{DatosServicio.LISTA_TIPO_SERVICIO}}</i>
					</li>
				</ul>
				
				<div class="x_title">
					<table class="col-md-12">
						<tr>
							<td align="left" class="col-md-9">
								<p><h2>{{titulo_tabla}}</h2></p>
								<div class="clearfix"></div>
							</td>
							<td align="right" class="col-md-3">
								<div class="form-group">
									<select id="selectPendientesDictaminadas" ng-model="selectPendientesDictaminadas" class="form-control" ng-change ="cambio_solicitudes()">
										<option value="Pendientes" selected>Solicitudes Pendientes</option>
										<option value="Dictaminadas">Solicitudes Dictaminadas</option>
									</select>
									
								</div>
							</td>
						</tr>
					</table>
				</div>
				<table class="table table-striped responsive-utilities jambo_table bulk_action" >
					<thead>
						<tr class="headings">
							<th class="column-title">Datos Generales</th>
							<th class="column-title">Datos de Asignaci&oacuten </th>
							<th class="column-title"> </th>
							<th class="column-title">Status</th>
							
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="x in DatosDictaminaciones" class="ng-scope even pointer">
							<td>
								<ul class="list-unstyled user_data">
									<li>
										<b>Cliente:</b><i> {{x.NOMBRE_CLIENTE}}</i>
									</li>
									<li>
										<b>Servicio: </b><i> {{x.NOMBRE_SERVICIO}}</i>
									</li>
									<li>
										<b>Tipo Servicio: </b><i> {{x.NOMBRE_TIPO_SERVICIO}}</i>
									</li>
							<!--		<li>
										<b>Normas: </b><i> {{x.ID_NORMA}}</i>
									</li>	-->
									<li>
										<b>Tipo Auditor&iacutea: </b><i> {{x.NOMBRE_TIPO_AUDITORIA}}</i>
									</li>
									<li>
										<b>Ciclo: </b><i> {{x.CICLO}}</i>
									</li>
								</ul>
							</td>
							<td>
								<ul class="list-unstyled user_data">
									<li>
										<b>Asignado por:</b><i> {{x.NOMBRE_ASIGNADOR}}</i>
									</li>
									<li>
										<b>Asignado el: </b><i> {{mostrarFecha(x.FECHA_CREACION)}}</i>
									</li>
									<li ng-show="selectPendientesDictaminadas=='Dictaminadas'">
										<b>Dictaminado el: </b><i> {{mostrarFecha(x.FECHA_MODIFICACION)}}</i>
									</li>
									
								</ul>
							</td>
							<td >
								
								<a type="button" class="btn btn-primary btn-xs btn-imnc"  style="float: right;" href='./?pagina=ver_expediente&id={{x.ID_SERVICIO_CLIENTE_ETAPA}}&id_entidad=5' target="_blank"> 
									      Ir al Expediente
								</a>
													
								
							</td>	
							<td>
								<div class="btn-group" ng-show="selectPendientesDictaminadas=='Pendientes'">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Cambiar Estado
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a	ng-click="editarStatus(x.ID,'1')"> 
											<span class="labelAcordeon"	>Dictaminacion aprobada</span></a>
											
										</li>
										<li >
											<a	ng-click="editarStatus(x.ID,'2')"> 
											<span class="labelAcordeon"	>Dictaminacion negada</span></a>
											
										</li>
									</ul>
								</div>
								<br ng-show="selectPendientesDictaminadas=='Pendientes'">
								<p ng-show="x.STATUS =='0'">Solicitud enviada</p>
								<p ng-show="x.STATUS =='1'">Dictaminaci&oacuten aprobada</p>
								<p ng-show="x.STATUS =='2'">Dictaminaci&oacuten negada</p>
							</td>
					
						</tr>
					</tbody>

				</table>		
        </div>
      </div>
    </div>
  </div>
</div>


</span>