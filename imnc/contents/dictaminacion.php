<span ng-controller="dictaminacion_controller">
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>{{titulo}}</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;" ng-click="EnviarCorreoPrueba()"> ';
              echo '    <i class="fa fa-plus"> </i> Enviar correo prueba';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

   <!--       <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title">Clave de tipo de servicio</th>
                <th class="column-title">Nombre de tipo de servicio</th>
                <th class="column-title">Nombre Servicio</th>
                <th class="column-title">Texto para Referencia</th>
				<th class="column-title">Normas</th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody>
				<tr ng-repeat="x in tablaDatos" class="ng-scope even pointer">
					<td>{{x.ACRONIMO}}</td>
					<td>{{x.NOMBRE}}</td>
					<td>{{x.NOMBRE_SERVICIO}}</td>					
					<td>{{x.ID_REFERENCIA}}</td>
					<td>{{x.NORMA_ID}}</td>
					<td >
					<?php
						if ($modulo_permisos["SERVICIOS"]["catalogos"] == 1 ) {
							echo '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_tipo_servicio="{{x.ID}}" style="float: right;" ng-click="EditarTipoServicio(x.ID)"> ';
							echo '      <i class="fa fa-edit"> </i> Editar tipo de servicio';
							echo '    </button>';
						}					
					?>
					</td>	
					
					
				</tr>
            </tbody>

          </table>		-->
        </div>
      </div>
    </div>
  </div>
</div>


</span>