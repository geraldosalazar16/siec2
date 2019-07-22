<span ng-controller="indicadores_programacion_dias_auditor_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
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
          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title"></th>
                <th class="column-title">Enero</th>
                <th class="column-title">Febrero</th>
                <th class="column-title">Marzo</th>
				<th class="column-title">Abril</th>
                <th class="column-title">Mayo</th>
				<th class="column-title">Junio</th>
				 <th class="column-title">Julio</th>
                <th class="column-title">Agosto</th>
                <th class="column-title">Septiembre</th>
				<th class="column-title">Octubre</th>
                <th class="column-title">Noviembre</th>
				<th class="column-title">Diciembre</th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.DATOS}}</td>
					<td>{{x.ENERO}}</td>
					<td>{{x.FEBRERO}}</td>
					<td>{{x.MARZO}}</td>					
					<td>{{x.ABRIL}}</td>
					<td>{{x.MAYO}}</td>
					<td>{{x.JUNIO}}</td>	
					<td>{{x.JULIO}}</td>
					<td>{{x.AGOSTO}}</td>
					<td>{{x.SEPTIEMBRE}}</td>					
					<td>{{x.OCTUBRE}}</td>
					<td>{{x.NOVIEMBRE}}</td>
					<td>{{x.DICIEMBRE}}</td>	
					
				</tr>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

</span>