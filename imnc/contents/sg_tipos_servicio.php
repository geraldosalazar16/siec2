
<script type="text/javascript" src="js/jquery-ui.js"></script>
<div class="right_col" role="main" >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Certificación de Sistemas de Gestión</h2></p>
        <?php
          if ($modulo_permisos["SERVICIOS"]["registrar"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
              echo '     <i class="fa fa-plus"></i> Agregar servicio';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          <ul class="list-unstyled user_data">
                <li id="lbCliente">
                  Cliente: cargando...
                </li>

                <li id="lbServicio">
                  Servicio: cargando...
                </li>

                <li id="lbEtapa">
                  Trámite: cargando...
                </li>

                <li id="lbReferencia">
                  Referencia: cargando...
                </li>

                <li id="lbIntegral">
                  
                </li>
              </ul>


            </div>

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <th class="column-title" style="width:310px;">Servicio y norma</th>
                <th class="column-title" style="width:310px;">Detalles</th>
                <th class="column-title">Alcance</th>
                <th class="column-title" style="width:200px;"></th>
              </tr>
            </thead>

            <tbody id="tbodyServicios">

            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_sg_tipo_servicio.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_auditoria.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_auditoria_sitios.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_auditoria_grupo_auditores.php";
  require "contents/sg_tipos_servicio/modal_explorar_sitios_auditoria.php";
  require "contents/sg_tipos_servicio/modal_explorar_auditores_grupo_auditoria.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_sectores.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_sitios.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_certificado.php";
  require "contents/sg_tipos_servicio/modal_inserta_actualiza_proceso.php";
  require "contents/sg_tipos_servicio/modal_genera_notificacion.php";

?>



<script type="text/javascript">

<?php
  echo "var global_id_servicio_cliente_et = '" .$_REQUEST["id_serv_cli_et"] . "';";
  //echo "var global_sg_tipo_servicio = '" . $_REQUEST["sg_tipo_servicio"] . "';";
  echo "var global_diffname = '" . $global_diffname . "';";

  echo "var global_str_auditorias = '" . $str_auditorias . "';";
  echo "var global_str_auditoria = '" . $str_auditoria . "';";
  echo "var global_str_personal_tecnico = '" . $str_personal_tecnico . "';";
  echo "var global_str_personal_tecnico_singular = '" . $str_personal_tecnico_singular . "';";
?>
   
</script>