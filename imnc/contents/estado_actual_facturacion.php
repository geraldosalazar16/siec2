<span ng-controller="estado_actual_facturacion_controller">
  <link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-themes/timepicker.css">
<script type="text/javascript" src="js/jquery-ui.js"></script> 
<script src="js/ngFileUpload/ng-file-upload-shim.min.js"></script> 
<script src="js/ngFileUpload/ng-file-upload.min.js"></script>
<script type="text/javascript" src="js/datepicker/timepicker.js"></script> 

<div class="right_col" role="main">

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="grafica">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Estado actual facturaci&oacuten</h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h4 align='center'>Cantidad de facturas por estado</h4>
					<div style="width: 100%;">
						<canvas id="RepCantFactxEstad" height="80"></canvas>
					</div>
				</div>							

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
		</div>
	</div>
	<br />		
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="grafica2">				
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h4 align='center'>Monto de facturas por estado</h4>
					<div style="width: 100%;">
						<canvas id="RepMontoFactxEstad" height="80"></canvas>
					</div>
				</div>							

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>

	<br />
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="grafica2">				
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h4 align='center'>Cartera vencida</h4>
					<div style="width: 100%;">
						<canvas id="RepCarteraVencida" height="80"></canvas>
					</div>
				</div>							

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>		


</div> 

</span>