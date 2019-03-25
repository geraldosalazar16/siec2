<span ng-controller="indicadores_controller">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script> 
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script> 
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<script type="text/javascript" src="js/datepicker/timepicker.js"></script> 

<div class="right_col" role="main">

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Indicador Env&iacuteo Plan Auditor&iacutea {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="IndEnvPlanAudChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'> Indicador Env&iacuteo Plan Auditor&iacutea {{ano_actual}} </h5>
					<div style="width: 100%;">
						<canvas id="IndEnvPlanAudChart1" height="80"></canvas>
					</div>
				</div>
             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 2 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Indicador Programaci&oacuten Oportuna Vigilancias {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="IndProgOportVigChart" height="80"></canvas>
					</div>
				</div>
				
             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 3 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Indicador Programaci&oacuten Oportuna Renovaci&oacuten {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="IndProgOportRenChart" height="80"></canvas>
					</div>
				</div>
				
             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />

      </div>
	  
	</span> 