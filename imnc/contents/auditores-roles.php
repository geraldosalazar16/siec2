<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="x_panel">
        <div class="x_title">
        <p><h2>Roles </h2></p>
        <?php
          if ($modulo_permisos["AUDITORES"]["registrar"] == 1) {
              echo '<p>';
              echo '  <button type="button" id="btnNuevo" class="btn btn-primary btn-xs btn-imnc" style="float: right;"> ';
              echo '    <i class="fa fa-plus"> </i> Agregar rol';
              echo '  </button>';
              echo '</p>';
          } 
        ?>
        
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <thead>
              <tr class="headings">
                <!--<th>
                  <input type="checkbox" id="check-all" class="flat">
                </th>-->
                <th class="column-title">Clave </th>
                <th class="column-title">Rol </th>
                <th class="column-title"></th>
              </tr>
            </thead>

            <tbody id="tbodyRoles">

            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal insertar/actualizar-->
<div class="modal fade" id="modalInsertarActualizar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalTitulo">Insertar/Actualizar</h4>
      </div>
      <div class="modal-body">
          <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
			<div class="form-group" hidden>
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtID">ID<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtID"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtJerarquia">Jerarquia<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtJerarquia"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtClave">Clave<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtClave"  required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="4103"><ul class="parsley-errors-list" id="parsley-id-4103"></ul>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtRol">Rol<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="txtRol"  name="txtRol" required="required" class="form-control col-md-7 col-xs-12" data-parsley-id="2324"><ul class="parsley-errors-list" id="parsley-id-2324"></ul>
              </div>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">

</script>