<span ng-controller="servicio_cliente_etapa_controller">
<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      <?php
        if ($modulo_permisos["SERVICIOS"]["extraer"] == 1) {
            echo '<div class="dropdown" style="margin-bottom: 10px;">';
            echo '  <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">';
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
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
        <div class="col-md-12">
            <form class="form-horizontal form-label-left ng-pristine ng-valid">
                  

				 <div class="form-group col-md-4">
                    <label>Por referencia: </label>
                    <div class="input-group" style="width: 100%;">
                         <input type="text" class="form-control input-filtro" id="txtFiltroReferencia" ng-model="txtFiltroReferencia">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroReferenciaContains" ng-model="txtFiltroReferenciaContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
				 
				 <div class="form-group col-md-4">
                    <label>Nombre cliente: </label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroNombreCliente" ng-model="txtFiltroNombreCliente">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroNombreClienteContains" ng-model="txtFiltroNombreClienteContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
                 <div class="form-group col-md-4">
                    <label>Nombre de servicio: </label>
                    <div class="input-group" style="width: 100%;">
                          <input type="text" class="form-control input-filtro" id="txtFiltroNombreServicio" ng-model="txtFiltroNombreServicio">
                          <!-- insert this line -->
                          <span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
                          <select id="txtFiltroNombreServicioContains" ng-model="txtFiltroNombreServicioContains" class="form-control" style="font-size: 10px;">
                              <option value="" selected>Comienza con</option>
                              <option value="1">Contenido en</option>
                          </select>
                    </div>
                  </div>
				  <div class="form-group col-md-4">
                    <label>Sector IAF: </label>
                    <div class="input-group" style="width: 100%;">
                          <select class="form-control" ng-model="cmbSectoresIAF_select">
						  <option value="" selected>--	Todos	--</option>
							<option ng-repeat="option in cmbSectoresIAF" value="{{option.ID}}">{{option.NOMBRE}}</option>
                          </select>
						  
                    </div>
                  </div>
                
				
			</form>
        </div>
              <div class="form-group">
                <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-9">
   <!--               <button type="button" class="btn btn-success" id="btnLimpiarFiltros" ng-click="btnLimpiarFiltros">Ver todos</button>
                  <button type="button" class="btn btn-primary" id="btnFiltrar" ng-click="btnFiltrar">Filtrar</button>
    -->            </div>
              </div>
          
        </div>
      </div>
	  <div class="row">
		<p >Cantidad de servicios: {{cantidad_servicios}}
		
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
                          <th class="column-title">Referencia, cliente y servicio</th>
                          <th class="column-title">Trámite</th>
                          <th class="column-title"></th>
						</tr>
                      </thead>

                      <tbody >
							<tr ng-repeat="x in tablaDatos" class="ng-scope  even pointer">
								<td>{{x.ID}}</td>
								<td>
									{{x.REFERENCIA}}<br>
									<strong>{{x.NOMBRE_CLIENTE}}</strong><br>
									<i>{{x.NOMBRE_SERVICIO}}</i>
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
												<a	ng-show="x.ID_ETAPA_PROCESO ==13" href="./?pagina=ver_expediente&id={{x.ID_REFERENCIA_SEG}}&id_entidad=5"> 
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