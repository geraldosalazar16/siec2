<span ng-controller="indicadores_programacion_tasa_ocupacional_auditores_controller">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script> 
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script> 
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<script type="text/javascript" src="js/datepicker/timepicker.js"></script> 
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
					
				
        <p>
			<h2>{{titulo}}</h2> 
			<button ng-if="tablaDatos.length > 0" type="button" class="btn btn-primary btn-sm pull-right" ng-click="exportExcel()" ><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
		
		</p>
        <?php /*
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="InsertarTipoServicio()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar tipo de servicio';
              echo '  </button>';
              echo '</p>';
          } */
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			

				
				
					
				<p><h3>{{texto_tabla}}</h3></p>
				
				<div class="form-group">
					<label class="control-label col-sm-1" for="Ciclos"><h4><strong>Mes: </strong></h4><span class="required"></span>
					</label>
						<div class="col-md-3 col-sm-3 col-xs-3">
							<select class="form-control" id="mes_select" ng-model="mes_select" ng-options="m.ID as m.NOMBRE  for m in M" ng-change="CambioMes()">
                  
							</select>
							
						</div>
				</div>	
				
			
				<br><br>
					<table class="table table-striped responsive-utilities jambo_table bulk_action">
						<thead>
							<tr class="headings">
								<th class="column-title">Auditor</th>
								<th class="column-title">Tasa ocupacional</th>
								<th class="column-title"></th>
								
							</tr>
						</thead>

						<tbody>
			
							<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
								<td>{{x.NOMBRE}}</td>
								<td>{{x.TASA_OCUPACIONAL[mes_select]}}</td>
								<td>
									<button class="btn btn-primary btn-xs btn-imnc" ng-click="graficaTasaOcupXAudHist(x.ID)" >Ver Hist&oacuterico </button>
								</td>
								
					
							</tr>
						</tbody>

					</table>
					
					
				
				
				
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Mostrar Historico Auditor-->
<div class="modal fade" id="modalHistoricoAuditor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <h4 class="modal-title" id="modalTitulo">Mostrar Hist&oacuterico Auditor </h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group text-center">
				<div class="col-md-12 col-sm-12 col-xs-12 ">
					<h5 align='center'>{{texto_grafico}}</h5>
					<canvas id="RepCertVigHistChart" height="80"></canvas>
						
				</div>
            </div>
            
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			
			</div>
    </div>
  </div>
</div>
</div>
</div>
</span>