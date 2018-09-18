<div class="right_col" role="main" ng-controller="prospecto_cita_historial_controller as $ctrl" ng-init='despliega_usuarios()' ng-cloak>

  <div class="row">
    <div class="col-md-9 col-sm-9 col-xs-9">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Historial de citas</h2></p>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Asunto</th>
                <th class="column-title">Tipo Asunto</th>
				<th class="column-title">Día Cita</th>
                <th class="column-title">Hora Inicio</th>
                <th class="column-title">Hora Fin</th>
                <th class="column-title">Fecha insert/update</th>
                <th class="column-title">Nombre usuario</th>
				<th class="column-title">Nombre usuario</th>
				<th class="column-title">Archivos</th>
              </tr>
            </thead>

            <tbody>
              <tr class="even pointer" ng-repeat="row in historial">
                <td>{{row.ASUNTO}}</td>
                <td>{{row.TIPO}}</td>
				<td>{{row.FECHA_CITA}}</td>
                <td>{{row.INICIO}}</td>
                <td>{{row.FIN}}</td>
                <td>{{row.FECHA}}</td>
                <td>{{row.NOMBRE}}</td>
				<td>{{row.NOMBRE}}</td>
				<td><p ng-repeat="archivos in row.archivos"><a href="ExpedienteArchivos.php?entidad=1&codigo={{archivos.codificado}}" target="_blank">{{archivos.NOMBRE_ARCHIVO}}</a></p></td>
      
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>