<span ng-controller="empleado_perfil_controller">
<div class="right_col" role="main">

  <div class="">

   
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Perfil del empleado</h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

              <div class="profile_img">

                <!-- end of image cropping -->
                <div id="crop-avatar">
                  <!-- Current avatar -->
                  <div class="avatar-view" title="Change the avatar">
                    <img ng-if="empleado.IMAGEN_BASE64 === null" src="./pictures/user.png" alt="Avatar" >
                    <img ng-if="empleado.IMAGEN_BASE64 !== null" src="{{empleado.IMAGEN_BASE64}}" alt="Avatar" >
                  </div>
                  <!-- Loading state -->
                  <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                </div>
                <!-- end of image cropping -->

              </div>
              <h3  style="line-height: 30px;">{{empleado.NOMBRE+' '+empleado.APELLIDO_PATERNO+' '+empleado.APELLIDO_MATERNO}}</h3>

              <ul class="list-unstyled user_data">
                  <li><strong>No.: </strong> {{empleado.NO_EMPLEADO}}</li>
                  <li><strong>Fecha. Nac: </strong> {{empleado.FECHA_NACIMIENTO}}</li>
                  <li><strong>Edad: </strong> {{calcular_edad(empleado.FECHA_NACIMIENTO)}} años</li>
                  <li><strong>CURP: </strong> {{empleado.CURP}}</li>
                  <li><strong>Sexo: </strong> {{empleado.SEXO | uppercase}}</li>
                  <li><strong>Estado Civil: </strong> {{empleado.ESTADO_CIVIL | uppercase}}</li>
                  <li><strong>No. Seguro Social: </strong> {{empleado.NO_SEGURO_SOCIAL}}</li>
                  <li><strong>Teléfono: </strong> {{empleado.TELEFONO}}</li>
                  <li><strong>Email: </strong> {{empleado.EMAIL}}</li>
                  <li><strong>Dirección: </strong> {{ empleado.DIRECCION }}</li>
              </ul>

              <br/>

            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">

 

              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_ficha" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Ficha</a>
                  </li>
                  <li role="presentation" class=""><a href="#tab_activos_fijos" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Activos Fijos</a>
                  </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <!-- Tab fichas -->
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_ficha" aria-labelledby="profile-tab">
                    <div>
                        <?php
                        if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {
                            echo '<button type="button"  class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                            echo '  <i class="fa fa-plus"> </i> Agregar ficha ';
                            echo '</button>';
                        }
                        ?>
                    </div>
                    <div class="col-sm-10 invoice-col" >
                      Aqui van los datos de las fichas
                    </div>
                  </div>
                  <!-- Tab activos fijos -->
                  <div role="tabpanel" class="tab-pane fade" id="tab_activos_fijos" aria-labelledby="profile-tab">
                      <?php
                      if ($modulo_permisos["EMPLEADOS"]["registrar"] == 1) {
                          echo '<button type="button"  class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                          echo '  <i class="fa fa-plus"> </i> Agregar activo fijo ';
                          echo '</button>';
                      }
                      ?>
                    
                     <table class="table table-striped responsive-utilities jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
                          <th class="column-title">#</th>
                          <th class="column-title"></th>
                          <th class="column-title"></th>
						  <th class="column-title"></th>
                        </tr>
                      </thead>
                      <tbody  style="font-size: 12px;" class="ng-scope even pointer" >
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  <?php
    echo "var tab_seleccionada = '".$_REQUEST["tab"]."';";
  ?>

</script>
</span>