<span ng-controller="indicadores_programacion_tasa_ocupacional_instituto_controller">
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
				
				<br><br>
				<h5 align='center'>{{texto_grafico}}</h5>
				<canvas id="RepCertVigHistChart" height="80"></canvas>
					
					
				
				
				
        </div>
      </div>
    </div>
  </div>
</div>

</span>