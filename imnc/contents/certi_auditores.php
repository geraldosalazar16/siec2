<span ng-controller="certi_auditores_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="InsertarTipoServicio()"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar tipo de servicio';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
			<div id="tntGridServices" ui-grid="gridOptions" ui-grid-pinning></div>
  
        </div>
      </div>
    </div>
  </div>
</div>

</span>