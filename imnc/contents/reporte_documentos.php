<span ng-controller="reporte_documentos_controller">
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
        <?php
        // if ($modulo_permisos["CLIENTES"]["extraer"] == 1) {
                  echo '<div class="dropdown" >';
                  echo '  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
                  echo '  <i class="fa fa-cloud-download" aria-hidden="true"></i> Reporte Plan vs Programado';
                  echo '  <span class="caret"></span></button>';
                  echo '  <ul class="dropdown-menu">';
                  echo '    <li><a href="./generar/csv/reporte_documentos/" target="_blank">CSV</a></li>';
                  echo '  </ul>';
                  echo '</div>';
             // } 
             ?>
        </div>
    </div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Filtros</h2></p>
          <div class="clearfix"></div>
        </div>
		<div class="x_content text-center">
		
 	
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Documento:</span>
				<select class="select2_single form-control"  ng-model="search.Documentos" id="catDocumentos"><!-- ng-options="catD.ID as catD.NOMBRE for catD in catalogoDocumentos track by  catD.ID"-->
			
					<option value="" selected>Ver Todos</option>
					<option ng-repeat="option in catalogoDocumentos" value="{{option.ID}}">{{option.NOMBRE}}</option>
				</select>
			</div>
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Cliente:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<select class="select2_single form-control" ng-model="search.Cliente" placeholder="Por Nombre" id="catClientes">
					<option value="" selected>Ver Todos</option>
					<option ng-repeat="option in clientes" value="{{option.ID}}">{{option.NOMBRE}}</option>
				</select>
			</div>
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Servicio:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<select class="select2_single form-control" ng-model="search.Servicio" placeholder="Por Nombre" id="catServicio">
					<option value="" selected>Ver Todos</option>
					<option ng-repeat="option in servicios" value="{{option.ID}}">{{option.NOMBRE}}</option>
				</select>
			</div>
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Etapa:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<select class="select2_single form-control" ng-model="search.Etapa" placeholder="Por Nombre" id="catEtapa">
					<option value="" selected>Ver Todos</option>
					<option ng-repeat="option in etapas" value="{{option.ID_ETAPA}}">{{option.ETAPA}}</option>
				</select>
			</div>
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Seccion:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<select class="select2_single form-control" ng-model="search.Seccion" placeholder="Por Nombre" id="catSeccion">
					<option value="" selected>Ver Todos</option>
					<option ng-repeat="option in secciones" value="{{option.ID}}">{{option.NOMBRE_SECCION}}</option>
				</select>
			</div>			
			<div class="input-group" style="text-align: left;">
				<span class="input-group-addon">Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
           <select   class="select2_single form-control" ng-model="search.Estado" placeholder="Por Nombre" id="catEstado">
				<option value="">Ver Todos</option>
				<option value="No se ha cargado">No se ha cargado</option>
				<option value="No Aplica">No Aplica</option>
				<option value="En Revision">En Revision</option>
				<option value="No Aprobado">No Aprobado</option>
				<option value="Aprobado">Aprobado</option>
				
          </select>
          </div>

			<div class="form-group" >
				 <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-9">
                  <button type="button" class="btn btn-success" ng-click="filtrartodos()" id="btnLimpiarFiltros">Ver todos</button>
                  <button type="button" class="btn btn-primary" ng-click="filtrar()" id="btnFiltrar">Filtrar</button>
                </div>
				
			</div>
		</div>	
      </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
      
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
<?php//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
 
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Nombre Documento</th>
                <th class="column-title">Cliente</th>
				<th class="column-title">Servicio</th>
				<th class="column-title">Ciclo</th>
				<th class="column-title">Etapa</th>
				<th class="column-title">Seccion</th>
				<th class="column-title">Estado</th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.NOMBRE_DOCUMENTO}}</td>
					<td>{{x.NOMBRE_CLIENTE}}</td>
					<td>{{x.NOMBRE_SERVICIO}}</td>					
					<td>{{x.CICLO}}</td>
					<td>{{x.NOMBRE_ETAPA}}</td>
					<td>{{x.NOMBRE_SECCION}}</td>	
					<td>{{x.ESTADO_DOCUMENTO}}</td>		
					
					
				</tr>
            </tbody>

          </table>	

<?//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
        </div>
      </div>
    </div>
  </div>
</div>



</span>`