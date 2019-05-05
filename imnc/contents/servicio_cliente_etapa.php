<span ng-controller="servicio_cliente_etapa_controller">
<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      <?php
        if ($modulo_permisos["SERVICIOS"]["extraer"] == 1) {
            echo '<div class="dropdown" style="margin-bottom: 10px;">';
            echo '  <button class="btn btn-primaryí btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
            echo '  <i class="fa fa-cloud-download" aria-hidden="true"></i> Exportar todos';
            echo '  <span class="caret"></span></button>';
            echo '  <ul class="dropdown-menu">';
            echo '    <li><a href="./generar/csv/servicio_cliente_etapa/" target="_blank">CSV</a></li>';
            echo '  </ul>';
            echo '</div>';
        } 
      ?>
	  
	       
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>

        <?php
          if ($modulo_permisos["SERVICIOS"]["registrar"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="nuevoServicio()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar servicio contratado ';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="showFiltrar()">
          <i class="fa fa-filter"> </i> Filtrar</button>

          <div class="clearfix"></div>
        </div>

        <div class="x_content">
        <div id="divFitrar" class="col-md-12" hidden>
            <form>
                <div class="form-group w_25">
                    <label for="nombreFiltro">Nombre del filtro<span class="required"></span></label>
                    <input type="text" class="form-control input-filtro" id="nombreFiltro" ng-model="nombreFiltro">
                </div>

                <div id="divInputContainer">

                </div>
                <div class="form-group w_25">
                    <select class="form-control border-dark" style="margin-top: 10px;" id="selectCampo" ng-model="selectCampo" ng-options="campo as campo.nombre for campo in campos" ng-change="addInput()">
				      <option value="" selected>--	Seleccione un Campo  --</option>
                    </select>
                </div>
                <hr>
                <div class="col-md-3 col-sm-3 col-xs-12 mt-5" ng-show="total>0">
                  <button type="button" class="btn btn-success" id="btnclear" ng-click="cancelFilter()">Cancelar</button>
                  <button type="button" class="btn btn-primary" id="btnFiltrar" ng-click="cargaServiciosFiltrados()">Filtrar</button>
                 </div>
            </form>

        </div>
              
          
        </div>
      </div>
	  <div class="row">
		<p>
      Cantidad de servicios: {{cantidad_servicios}}		
		</p>
	  </div>
      <div class="row">
            <div class="col-md-12">
              <div class="x_panel">
                <div class="x_content">

                  <div class="row">
                    <div class="clearfix"></div>
                    <table class="table table-striped responsive-utilities jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
                          <th class="column-title">ID</th>
                          <th class="column-title">Información del servicio</th>
                          <th class="column-title">Normas del servicio</th>
                          <th class="column-title">Trámite</th>
                          <th class="column-title"></th>
						</tr>
                      </thead>

                      <tbody >
							<tr ng-repeat="x in tablaDatos" class="ng-scope  even pointer">
								<td>{{x.ID}}</td>
								<td>
									Referencia: <strong>{{x.REFERENCIA}}</strong><br>
									Cliente: <strong>{{x.NOMBRE_CLIENTE}}</strong><br>
									Servicio: <i>{{x.NOMBRE_SERVICIO}}</i><br>
                  Tipo de servicio: <strong>{{x.NOMBRE_TIPO_SERVICIO}}</strong><br>
								</td>
                <td>
									<i ng-repeat-start="object in x.NORMAS">{{object.ID_NORMA}}</i><br ng-repeat-end>
								</td>
								<td>{{x.NOMBRE_ETAPA}}</td>
								
								<td>
								<div class="btn-group">
						
										<button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > Opciones
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu pull-right">
											<li ng-if='modulo_permisos["editar"] == 1'>
												<a	ng-click="editarServicio(x.ID)"> 
												<span class="labelAcordeon"	>Editar servicio contratado</span></a>
												
											</li>
											<li >
												<a	href="./?pagina=ec_tipos_servicio&id_serv_cli_et={{x.ID}}"> 
												<span class="labelAcordeon"	>Ver detalles</span></a>
												
											</li>
								<!--			<li  ng-show="x.ID_SERVICIO == 1" >
												<a	ng-show="x.ID_ETAPA_PROCESO !=13" href="./?pagina=sg_tipos_servicio&id_serv_cli_et={{x.ID}}"> 
												<span class="labelAcordeon"	>Ver detalles</span></a>
												<a	ng-show="x.ID_ETAPA_PROCESO ==13" href="./?pagina=ec_tipos_servicio&id_serv_cli_et={{x.ID}}"	> 
												<span class="labelAcordeon"	>Ver detalles</span></a>
												
											</li>
											<li  ng-show="x.ID_SERVICIO == 2" >
												<a	href="./?pagina=ec_tipos_servicio&id_serv_cli_et={{x.ID}}"> 
												<span class="labelAcordeon"	>Ver detalles</span></a>
												
											</li>	-->
											<li  ng-show="x.ID_SERVICIO == 1" >
												<a	ng-show="x.ID_ETAPA_PROCESO !=13" href="./?pagina=ver_expediente&id={{x.ID}}&id_entidad=5"> 
												<span class="labelAcordeon"	>Ver expedientes</span></a>
												<a	ng-show="x.ID_ETAPA_PROCESO ==13" href="./?pagina=ver_expediente&id={{x.ID}}&id_entidad=5"> 
												<span class="labelAcordeon"	>Ver expedientes</span></a>
											</li>
											<li >
												<a	href="./?pagina=calendario_servicio&id_serv_cli_et={{x.ID}}"> 
												<span class="labelAcordeon"	>Ver Planificador</span></a>
												
											</li>
											<li >
												<a	href="./?pagina=i_servicio_contratado_historico&id_serv_cli_et={{x.ID}}"> 
												<span class="labelAcordeon"	>Ver Historico</span></a>
												
											</li>
										</ul>
								
									
								</div>
								</td>	
						
							</tr>
                      </tbody>

                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>
    </div>
  </div>
</div>

<?php 
  include "servicio_cliente_etapa/modal_insertar_actualizar_servicio_contratado.php";
?>

</span>
