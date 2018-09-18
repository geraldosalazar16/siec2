<span ng-controller="reporte_tareas_documento_controller">
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
                  echo '    <li><a href="./generar/csv/reporte_tareas_documentos/" target="_blank">CSV</a></li>';
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
				<span class="input-group-addon">Buscar:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				<input type="text" class="form-control" ng-model="search.NombreTarea" placeholder="Por Nombre Tarea" value="">
			</div>	
			
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
				<span class="input-group-addon">Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
           <select   class="select2_single form-control" ng-model="search.Estado" placeholder="Por Nombre" id="catEstado">
				<option value="">Ver Todos</option>
				<option value="1">Incumplida</option>
				<option value="2">En Tiempo</option>
				<option value="3">Cumplida</option>
				
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
        <!-- 
        <p>
          <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> 
            <i class="fa fa-plus"> </i> Agregar 
          </button>
        </p>-->
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
<?php//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
 
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Nombre Tarea</th>
                <th class="column-title">Nombre Documento</th>
                <th class="column-title">Cliente</th>
				<th class="column-title">Servicio</th>
				<th class="column-title">Ciclo</th>
				<th class="column-title">Etapa</th>
				<th class="column-title">Seccion</th>
				<th class="column-title">Fecha Final</th>
				<th class="column-title">Estado</th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.NOMBRE_TAREA}}</td>					
					<td>{{x.NOMBRE_DOCUMENTO}}</td>
					<td>{{x.NOMBRE_CLIENTE}}</td>
					<td>{{x.NOMBRE_SERVICIO}}</td>					
					<td>{{x.CICLO}}</td>
					<td>{{x.NOMBRE_ETAPA}}</td>
					<td>{{x.NOMBRE_SECCION}}</td>					
					<td>{{x.FECHA_FIN}} {{x.HORA_FIN}}</td>
					<td ng-if="x.ESTADO == 1">
						Cumplida
						<a type="button" class="btn btn-primary btn-xs btn-imnc"
								 style="float: right;" href="./?pagina=calendario_documento&id={{x.ID_SERVICIO}}&id_docum={{x.ID_CATALOGO_DOCUMENTOS}}&ciclo={{x.CICLO}}">
							<i class="fa fa-calendar"> </i> Ver Calendario
						</a>
					</td>
					<td ng-if="x.ESTADO == 0 ">
						En Tiempo
						<a type="button" class="btn btn-primary btn-xs btn-imnc"
								 style="float: right;" href="./?pagina=calendario_documento&id={{x.ID_SERVICIO}}&id_docum={{x.ID_CATALOGO_DOCUMENTOS}}&ciclo={{x.CICLO}}">
							<i class="fa fa-calendar"> </i> Ver Calendario
						</a>
					</td>
					<td ng-if="x.ESTADO == -1 " style="color: red">
						Incumplida
						<a type="button" class="btn btn-primary btn-xs btn-imnc"
								 style="float: right;" href="./?pagina=calendario_documento&id={{x.ID_SERVICIO}}&id_docum={{x.ID_CATALOGO_DOCUMENTOS}}&ciclo={{x.CICLO}}">
							<i class="fa fa-calendar"> </i> Ver Calendario
						</a>
					</td>
					
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