
  function clear_modal_insertar_actualizar(){
    $("#txtId").val("");
    $("#txtTipo").val("");
  }

  function fill_modal_insertar_actualizar(id_tipo){
    $.getJSON(  global_apiserver + "/tipos_entidad/getById/?id="+id_tipo, function( response ) {
          $("#txtId").val(response.ID);
          $("#txtTipo").val(response.TIPO);
       });
  }

  function notify(titulo, texto, tipo) {
      new PNotify({
          title: titulo,
          text: texto,
          type: tipo,
          nonblock: {
              nonblock: true,
              nonblock_opacity: .2
          },
          delay: 2500
      });
  }

  function draw_row_entidad(objEntidad){
    var strHtml = "";
    strHtml += '<tr class="even pointer">';
    strHtml += '  <td>'+objEntidad.ID+'</td>';
    strHtml += '  <td>'+objEntidad.TIPO+'</td>';
    strHtml += '  <td>';
    if (global_permisos["CLIENTES"]["catalogos"] == 1 ) {
       strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_tipo="'+objEntidad.ID+'" style="float: right;"> ';
      strHtml += '      <i class="fa fa-edit"> </i> Editar ' + global_str_tipo_entidad;
      strHtml += '    </button>';
    }
   
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }


  function draw_tabla_entidad(){
   $.getJSON(  global_apiserver + "/tipos_entidad/getAll/", function( response ) {
        //console.log(response);
        $("#tbodyEntidad").html("");
        $.each(response, function( index, objEntidad ) {
          $("#tbodyEntidad").append(draw_row_entidad(objEntidad));  
        });
        listener_btn_editar();
     });
  }

  function listener_btn_nuevo(){
    $( "#btnNuevo" ).click(function() {
      $("#btnGuardar").attr("accion","insertar");
      $("#modalTitulo").html("Insertar tipo de entidad");
      clear_modal_insertar_actualizar();
      $("#txtId").removeAttr("readonly");
      $("#modalInsertarActualizar").modal("show");
    });
  }

  function listener_btn_editar(){
    $( ".btnEditar" ).click(function() {
      $("#btnGuardar").attr("accion","editar");
      $("#btnGuardar").attr("id_tipo",$(this).attr("id_tipo"));
      $("#modalTitulo").html("Editar tipo de entidad");
      fill_modal_insertar_actualizar($(this).attr("id_tipo"));
      $("#txtId").attr("readonly","true");
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
    var entidad = {
      ID:$("#txtId").val(),
      TIPO:$("#txtTipo").val(),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/tipos_entidad/insert/", JSON.stringify(entidad), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo registro", "success");
          draw_tabla_entidad();
        }else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar(){
    var entidad = {
      ID:$("#txtId").val(),
      TIPO:$("#txtTipo").val(),
       ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
      $.post( global_apiserver + "/tipos_entidad/update/", JSON.stringify(entidad), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
             $("#modalInsertarActualizar").modal("hide");
            notify("Éxito", "Se han actualizado los datos", "success");
            draw_tabla_entidad();
          }else{
            notify("Error", respuesta.mensaje, "error");
          }
      });
  }

   $( window ).load(function() {
      draw_tabla_entidad();
      listener_btn_nuevo();
      listener_btn_guardar();
  });
