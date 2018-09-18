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

  function clear_modal_insertar_actualizar(){
    $("#txtRol").val("");
    $("#txtClave").val("");
    $("#txtClave").removeAttr("readonly");
    $("#txtJerarquia").val("");
  }

  function fill_modal_insertar_actualizar(id_personal_tecnico_rol){
    $.getJSON( global_apiserver + "/personal_tecnico_roles/getById/?id="+id_personal_tecnico_rol, function( response ) {
          $("#txtClave").val(response.ID);
          $("#txtClave").attr("readonly","true");
          $("#txtRol").val(response.ROL);
          $("#txtJerarquia").val(response.JERARQUIA);
       });
  }

  function notify_success(titulo, texto) {
      new PNotify({
          title: titulo,
          text: texto,
          type: 'success',
          nonblock: {
              nonblock: true,
              nonblock_opacity: .2
          },
          delay: 2500
      });
  }

  function draw_row_rol(objRol){
    var strHtml = "";
    strHtml += '<tr class="even pointer">';
    strHtml += '  <td>'+objRol.ID+'</td>';
    strHtml += '  <td>'+objRol.ROL+'</td>';
    strHtml += '  <td>';
    if (global_permisos["AUDITORES"]["editar"] == 1) {
       strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_rol="'+objRol.ID+'" style="float: right;"> ';
      strHtml += '      <i class="fa fa-edit"> </i> Editar rol';
      strHtml += '    </button>';
    }
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }


  function draw_tabla_roles(){
   $.getJSON( global_apiserver + "/personal_tecnico_roles/getAll/", function( response ) {
        //console.log(response);
        $("#tbodyRoles").html("");
        $.each(response, function( index, objRol ) {
          $("#tbodyRoles").append(draw_row_rol(objRol));  
        });
        listener_btn_editar();
     });
  }

  function listener_btn_nuevo(){
    $( "#btnNuevo" ).click(function() {
      $("#btnGuardar").attr("accion","insertar");
      $("#modalTitulo").html("Insertar rol");
      clear_modal_insertar_actualizar();
      $("#modalInsertarActualizar").modal("show");
    });
  }

  function listener_btn_editar(){
    $( ".btnEditar" ).click(function() {
      $("#btnGuardar").attr("accion","editar");
      $("#btnGuardar").attr("id_rol",$(this).attr("id_rol"));
      $("#modalTitulo").html("Editar rol");
      fill_modal_insertar_actualizar($(this).attr("id_rol"));
      $("#modalInsertarActualizar").modal("show");
    });
  }

  function listener_btn_guardar(){
    $( "#btnGuardar" ).click(function() {
      if ($("#btnGuardar").attr("accion") == "insertar")
      {
        insertar();
      }
      else if ($("#btnGuardar").attr("accion") == "editar")
      {
        editar();
      }
    });
  }

  function insertar(){
    var personal_tecnico_rol = {
      ID:$("#txtClave").val(),
      ROL:$("#txtRol").val(),
      JERARQUIA:$("#txtJerarquia").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico_roles/insert/", JSON.stringify(personal_tecnico_rol), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizar").modal("hide");
          notify_success("Éxito", "Se ha insertado un nuevo registro");
          draw_tabla_roles();
          //document.location = "./?pagina=auditores";
        }
        //console.log("insertar: " + respuesta);
    });
  }

  function editar(){
    var personal_tecnico_rol = {
      ID:$("#txtClave").val(),
      ROL:$("#txtRol").val(),
      JERARQUIA:$("#txtJerarquia").val(),
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post(global_apiserver + "/personal_tecnico_roles/update/", JSON.stringify(personal_tecnico_rol), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizar").modal("hide");
            notify_success("Éxito", "Se han actualizado los datos");
            draw_tabla_roles();
            //document.location = "./?pagina=auditores";
          }
          console.log("insertar: " + respuesta);
      });
  }

   $( window ).load(function() {
      draw_tabla_roles();
      listener_btn_nuevo();
      listener_btn_guardar();
  });
</script>