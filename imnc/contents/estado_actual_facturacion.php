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
						<h3>Anexo 1 <small>Cantidad de facturas por estado</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte Certificados Vigentes {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="RepCantFactxEstad" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Hist&oacutericos Reporte Certificados Vigentes</h5>
					<div style="width: 100%;">
						<canvas id="RepCertVigHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel1">

				<div class="row x_title">
					<div class="col-md-6 ">
						<h3>Anexo 2 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte Comparativa de Contrataci&oacuten {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="repCompContrChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Hist&oacutericos Reporte Comparativa de Contrataci&oacuten </h5>
					<div style="width: 100%;">
						<canvas id="repCompContrHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />		
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel2">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 3 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte Mezcla de Portafolio {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="repMezclaPortChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Hist&oacutericos Reporte Mezcla de Portafolio</h5>
					<div style="width: 100%;">
						<canvas id="repMezclaPortHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel2">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 4 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte Certificados emitidos SG {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="repCertEmitSGChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'> Hist&oacutericos Reporte Certificados emitidos SG </h5>
					<div style="width: 100%;">
						<canvas id="repCertEmitSGHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel2">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 5 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte Servicios realizados SG {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="repServRealizSGChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'> Hist&oacutericos Reporte Servicios realizados SG </h5>
					<div style="width: 100%;">
						<canvas id="repServRealizSGHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="pruebaDaniel2">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 6 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'>Reporte D&iacuteas auditor SG {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="repDiasAudSGChart" height="80"></canvas>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<h5 align='center'> Hist&oacutericos Reporte D&iacuteas auditor SG </h5>
					<div style="width: 100%;">
						<canvas id="repDiasAudSGHistChart" height="80"></canvas>
					</div>
				</div>

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />				 


      </div> 

</span>