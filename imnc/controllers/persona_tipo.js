
function clear_modal_insertar_actualizar(){
  $("#txtId").val("");
  $("#txtTipo").val("");
}

function fill_modal_insertar_actualizar(id_tipo){
  $.getJSON(  global_apiserver + "/tipos_persona/getById/?id="+id_tipo, function( response ) {
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

function draw_row_persona(objPersona){
  var strHtml = "";
  strHtml += '<tr class="even pointer">';
  strHtml += '  <td>'+objPersona.ID+'</td>';
  strHtml += '  <td>'+objPersona.TIPO+'</td>';
  strHtml += '  <td>';
  if (global_permisos["CLIENTES"]["catalogos"] == 1) {
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_tipo="'+objPersona.ID+'" style="float: right;"> ';
    strHtml += '      <i class="fa fa-edit"> </i> Editar ' + global_str_tipo_persona;
    strHtml += '    </button>';
  }
  strHtml += '  </td>';
  strHtml += '</tr>';
  return strHtml;
}


function draw_tabla_persona(){
 $.getJSON(  global_apiserver + "/tipos_persona/getAll/", function( response ) {
      //console.log(response);
      $("#tbodyPersona").html("");
      $.each(response, function( index, objPersona ) {
        $("#tbodyPersona").append(draw_row_persona(objPersona));  
      });
      listener_btn_editar();
   });
}

function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar tipo de persona");
    clear_modal_insertar_actualizar();
    $("#txtId").removeAttr("readonly");
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("id_tipo",$(this).attr("id_tipo"));
    $("#modalTitulo").html("Editar tipo de persona");
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
  var persona = {
    ID:$("#txtId").val(),
    TIPO:$("#txtTipo").val(),
     ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/tipos_persona/insert/", JSON.stringify(persona), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", "success");
        draw_tabla_persona();

      }else{
          notify("Error", respuesta.mensaje, "error");
      }
      //console.log("insertar: " + respuesta);
  });
}

function editar(){
  var persona = {
    ID:$("#txtId").val(),
    TIPO:$("#txtTipo").val(),
     ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
    $.post( global_apiserver + "/tipos_persona/update/", JSON.stringify(persona), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se han actualizado los datos", "success");
          draw_tabla_persona();
        }else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

 $( window ).load(function() {
    draw_tabla_persona();
    listener_btn_nuevo();
    listener_btn_guardar();
});
