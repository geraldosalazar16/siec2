<span ng-controller="dashboard_controller">
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
					<h5 align='center'>Reporte Certificados Vigentes {{ano_actual}}</h5>
					<div style="width: 100%;">
						<canvas id="RepCertVigChart" height="80"></canvas>
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
					<div class="col-md-6">
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
<!--	<div class="row">
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

				<div class="row x_title">
					<div class="col-md-12">
						<h3>Hit rate por valor <small>Certificaciones (Número de Contratos Nuevos/ Número de Ofertas Nuevas).</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<div style="width: 100%;">
						<div class = "row">
							<div class = "form-group col-md-6">
								<label class="control-label">Fecha Inicio<span class="required">*</span></label>
								<input type="text" id="fecha_inicio_cert" required="required" class="form-control" ng-model="fecha_inicio_cert" 
								data-parsley-id="2324" ></input>
							</div>
							<div class = "form-group col-md-6">
								<label class="control-label">Fecha Fin<span class="required">*</span></label>
								<input type="text" id="fecha_fin_cert" required="required" class="form-control" ng-model="fecha_fin_cert" 
								data-parsley-id="2324" ></input>
							</div>
						</div>
						
						<div class = "row" style="margin-top: 20px">
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Número de contratos: {{contratos_nuevos_certificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Número de ofertas: {{ofertas_nuevas_certificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Resultado: {{hit_rate_valor_certificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<button type="button" class="btn btn-primary" ng-click='calcular_hit_rate_valor_certificaciones()' id="btnHitRateValorCertificaciones" style="float: right">Calcular</button>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
	<br />
	
	<div class="row">
         <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

				<div class="row x_title">
					<div class="col-md-12">
						<h3>Hit rate por valor <small>Re-certificaciones (Número de Contratos/ Número de Ofertas).</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<div style="width: 100%;">
						<div class = "row">
							<div class = "form-group col-md-6">
								<label class="control-label">Fecha Inicio<span class="required">*</span></label>
								<input type="text" id="fecha_inicio_rec" required="required" class="form-control" ng-model="fecha_inicio_rec" 
								data-parsley-id="2324" ></input>
							</div>
							<div class = "form-group col-md-6">
								<label class="control-label">Fecha Fin<span class="required">*</span></label>
								<input type="text" id="fecha_fin_rec" required="required" class="form-control" ng-model="fecha_fin_rec" 
								data-parsley-id="2324" ></input>
							</div>
						</div>
						
						<div class = "row" style="margin-top: 20px">
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Número de contratos nuevos: {{contratos_nuevos_recertificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Número de ofertas nuevas: {{ofertas_nuevas_recertificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<label class="control-label">Resultado: {{hit_rate_valor_recertificacion}}</label>
							</div>
							<div class = "col-md-3 col-sm-3 col-xs-3">
								<button type="button" class="btn btn-primary" ng-click='calcular_hit_rate_valor_recertificaciones()' id="btnHitRateValorRecertificaciones" style="float: right">Calcular</button>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
	<br />
	-->
	<!--
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="competenciadivwidth">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<div >
					   <canvas id="competenciaChart" height="40"></canvas>
					</div>
				</div>
			</div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
	<br />
	-->
<!--			 
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
			<div class="dashboard_graph" id="entidaddivwidth">

				<div class="row x_title">
					<div class="col-md-6">
						<h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
					<div >
						<canvas id="entidadChart" height="40"></canvas>
					</div>
				</div>
            

             </div>

            <div class="clearfix" style="background-color:white;"></div>
        </div>
    </div>
		     <br />
<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                   <canvas id="origenChart"></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		  
		  
        <br />
		<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                   <canvas id="estatusChart" ></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		  
		         <br />



<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
		<div class="dashboard_graph" id="competenciadivwidthu">

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div >
                   <canvas id="competenciaChartU" height="40"></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		     <br />
			 
			 <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;overflow-y: hidden;">
		<div class="dashboard_graph" id="entidaddivwidthu">

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div >
                   <canvas id="entidadChartU" height="40"></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		     <br />
<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                   <canvas id="origenChartU"></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		  
		  
        <br />
		<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                   <canvas id="estatusChartU" ></canvas>
                </div>
              </div>
            

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		  
		         <br />
				 
				  <br />
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 1 <small>Indicadores Certificación de Sistemas de Gesti&oacute;n</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo1tabla1.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo1a.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  
			  <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo1b.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo1tabla2.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  <div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo1c.png" style="width:50%;height:50%;"/>
                </div>
              </div>

              </div>

              <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>


        <br />
		<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 2 <small>Indicadores Certificación Tur&iacute;stica</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo2tabla1.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo2a.png" style="width:50%;height:50%;"/>
                </div>
				</div>
              </div>
			  <div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
			

			
	<br />
				<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 3 <small>Indicadores de Producto Industrial</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo3tabla1.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo3a.png" style="width:50%;height:50%;"/>
                </div>
              </div>
			  </div>
<div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>

			  


              
<br />
				<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Anexo 5 <small>Indicadores de Venta de Normas</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo5tabla1.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo5a.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo5b.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  </div>
<div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
		<br />
		
		<div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph" >

              <div class="row x_title">0
                <div class="col-md-6">
                  <h3>Anexo 6 <small>Indicadores CIFA</small></h3>
                </div>
              </div>
				<div class="col-md-12 col-sm-12 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo6tabla1.png" style="width:100%;height:100%;"/>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo6a.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-12 bg-white">
                <div style="width: 100%;">
                  <img src="./images/imncgraficas/anexo6b.png" style="width:100%;height:100%;"/>
                </div>
              </div>
			  </div>
<div class="clearfix" style="background-color:white;"></div>
            </div>
          </div>
-->


      </div>
	  
	 