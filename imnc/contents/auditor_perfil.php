<?php 
  $id_personal_tecnico = $_REQUEST["id"];
?>
<style type="text/css">
    span.domicilio{
      font-size: 18px;
    }
</style>

<div class="right_col" role="main">

  <div class="">

   
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Perfil del <?php echo strtolower($str_personal_tecnico_singular); ?></h2>
            
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

            <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

              <div class="profile_img">

                <!-- end of image cropping -->
                <div id="crop-avatar">
                  <!-- Current avatar -->
                  <div class="avatar-view" title="Change the avatar">
                    <img src="../pictures/user.png" alt="Avatar" id="imgAuditor">
                  </div>


                  <!-- Loading state -->
                  <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                </div>
                <!-- end of image cropping -->

              </div>
              <h3 id="lbNombre" style="line-height: 30px;">cargando...</h3>

              <ul class="list-unstyled user_data">
                <li id="lbFecNac">
                  cargando...
                </li>

                <li id="lbCurp">
                  cargando...
                </li>

                <li id="lbRfc">
                  cargando...
                </li>

                <li id="lbTelFijo">
                  cargando...
                </li>

                <li id="lbTelCelular">
                  cargando...
                </li>

                <li id="lbEmail">
                  cargando...
                </li>

                <li id="lbStatus">
                  cargando...
                </li>
              </ul>

              <br/>

            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">

 

              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_domicilios" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Domicilios</a>
                  </li>
                  <li role="presentation" class=""><a href="#tab_califs" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Calificaciones</a>
                  </li>
                  <li role="presentation" class=""><a href="#tab_agenda" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Agenda</a>
                  </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <!-- Tab domicilio -->
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_domicilios" aria-labelledby="profile-tab">
                    <div>
                    <?php
                        if ($modulo_permisos["AUDITORES"]["registrar"] == 1) {
                            echo '<button type="button" id="btnNuevoDomicilio" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                            echo '  <i class="fa fa-plus"> </i> Agregar domicilio ';
                            echo '</button>';
                        } 
                    ?>
                    </div>
                    <div class="col-sm-10 invoice-col" id="bodyDomicilios">
                    
                    </div>
                  </div>
                  <!-- Tab calificación -->
                  <div role="tabpanel" class="tab-pane fade" id="tab_califs" aria-labelledby="profile-tab">
                    <?php
                        if ($modulo_permisos["AUDITORES"]["registrar"] == 1) {
                            echo '<button type="button" id="btnNuevoCalif" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
                            echo '  <i class="fa fa-plus"> </i> Agregar calificación ';
                            echo '</button>';
                        } 
                    ?>
                    
                    <table class="data table table-striped jambo_table no-margin">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Rol</th>
                          <th>Tipo de servicio</th>
                          <th>Registro</th>
                          <th>Periodo</th>
                          <th></th>
                          <th></th>
						  <th></th>
                        </tr>
                      </thead>
                      <tbody id="tbodyCalifs" style="font-size: 12px;">
                        
                      </tbody>
                    </table>
                  </div>
                  <!-- Tab agenda -->
                  <div role="tabpanel" class="tab-pane fade" id="tab_agenda" aria-labelledby="profile-tab">
                    <div id='calendar'></div>
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

<?php 

  include "auditor_perfil/modal_insertar_actualizar_domicilio.php";
  include "auditor_perfil/modal_insertar_actualizar_calificacion.php";
  include "auditor_perfil/modal_insertar_actualizar_calif_sector.php";
  include "auditor_perfil/modal_insertar_actualizar_calificacion_anterior.php";

?>


<script type="text/javascript">

  <?php
    echo "var global_id_personal_tecnico = '" . $id_personal_tecnico . "';";

    echo "var str_rfc = '" . $str_rfc . "';";
    echo "var str_curp = '" . $str_curp . "';";
    echo "var str_tipo_entidad = '" . $ststr_tipo_entidadr_rfc . "';";
    echo "var str_tipo_persona = '" . $str_tipo_persona . "';";

    echo "var tab_seleccionada = '".$_REQUEST["tab"]."';";
  ?>

</script>
