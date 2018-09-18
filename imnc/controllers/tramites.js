
function fill_cmb_Tserv(seleccionado){
  $.getJSON(  global_apiserver + "/servicios/getAll/", function( response ) {
    $("#claveServicio").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $.each(response, function( indice, objTserv ) {
      $("#claveServicio").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
    });
    $("#claveServicio").val(seleccionado);
  });
}

function clear_modal_insertar_actualizar(){
  $("#txtClave").val("");
  $("#txtClaveEtapa").val("");
  $("#txtClaveEtapa").removeAttr("readonly");
  $("#etapa").val("");
  $("#txtDescripcion").val("");
  fill_cmb_Tserv("elige");
}

function fill_modal_insertar_actualizar(id_servicio){

  $.getJSON(  global_apiserver + "/etapas_proceso/getById/?id_etapa="+id_servicio, function( response ) {
        $("#txtClaveEtapa").val(response.ID_ETAPA);
        $("#txtClave").val(response.ID);
        fill_cmb_Tserv(response.ID_SERVICIO);
        $("#etapa").val(response.ETAPA);
        $("#txtDescripcion").val(response.DESCRIPCION);

     });
}

function notify(titulo, texto,tipo) {
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

function draw_row_servicio(objServicio){
  var strHtml = "";
  strHtml += '<tr class="even pointer">';
  // strHtml += '  <td>'+objServicio.ID_ETAPA+'</td>';
  strHtml += '  <td>'+objServicio.ID+'</td>';
  strHtml += '  <td>'+objServicio.ETAPA+'</td>';
  strHtml += '  <td>'+objServicio.NOMBRE_SERVICIO+'</td>';
  strHtml += '  <td>'+objServicio.DESCRIPCION+'</td>';
  strHtml += '  <td>';
  if(global_permisos["SERVICIOS"]["catalogos"] == 1 ){
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_servicio="'+objServicio.ID_ETAPA+'" style="float: right;"> ';
    strHtml += '      <i class="fa fa-edit"> </i> Editar trámite ';
    strHtml += '    </button>';
  }
  strHtml += '  </td>';
  strHtml += '</tr>';
  return strHtml;
}


function draw_tabla_servicios(){
 $.getJSON(  global_apiserver + "/etapas_proceso/getAll/", function( response ) {
      //console.log(response);
      $("#tbodyServicios").html("");
      $.each(response, function( index, objServicio ) {
        $("#tbodyServicios").append(draw_row_servicio(objServicio));  
      });
      listener_btn_editar();
   });
}

function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar servicio");
    clear_modal_insertar_actualizar();
    $("#txtClaveEtapa").attr("readonly","true");
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#txtClaveEtapa").attr("readonly","true");
    $("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
    $("#modalTitulo").html("Editar servicio");
    fill_modal_insertar_actualizar($(this).attr("id_servicio"));
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
  var servicio = {
    ID:$("#txtClave").val(),
    ID_SERVICIO:$("#claveServicio").val(),
    ETAPA:$("#etapa").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario"),
    DESCRIPCION:$("#txtDescripcion").val()
  };
  $.post( global_apiserver + "/etapas_proceso/insert/", JSON.stringify(servicio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", "success");
        draw_tabla_servicios();
      }
      else{
        notify("Error", respuesta.mensaje, "error");
      }
  });
}

function editar(){
  var servicio = {
    ID_ETAPA:$("#txtClaveEtapa").val(),
    ID:$("#txtClave").val(),
    ID_SERVICIO:$("#claveServicio").val(),
    ETAPA:$("#etapa").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario"),
    DESCRIPCION:$("#txtDescripcion").val()
  };
    $.post( global_apiserver + "/etapas_proceso/update/", JSON.stringify(servicio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se han actualizado los datos", "success");
          draw_tabla_servicios();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

 $( window ).load(function() {
    draw_tabla_servicios();
    listener_btn_nuevo();
    listener_btn_guardar();
});
