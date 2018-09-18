

function clear_modal_insertar_actualizar(){
  $("#txtId").val("");
  $("#txtId").removeAttr("readonly");
  $("#txtNombre").val("");
  $("#txtIni").val("");
  $("#txtTer").val("");
  $("#txtPrecio").val("");
  $("#txtIDorg").val("");
  $("#txtIDsta").val("");
}

function fill_modal_insertar_actualizar(id_norma){
  $.getJSON(  global_apiserver + "/normas/getById/?id="+id_norma, function( response ) {
        var fec_ini = response.FECHA_INICIO;
        fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
        var fec_fin = response.FECHA_FIN;
        fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
        $("#txtId").val(response.ID);
        $("#txtId").attr("readonly","true")
        $("#txtNombre").val(response.NOMBRE);
        $("#txtIni").val(fec_ini);
        $("#txtTer").val(fec_fin);
        $("#txtPrecio").val(response.PRECIO);
        $("#txtIDorg").val(response.ID_ORGANISMO);
        $("#txtIDsta").val(response.ID_ESTANDAR);
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

function draw_row_norma(objNorma){
  var fec_ini = objNorma.FECHA_INICIO;
  fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
  var fec_fin = objNorma.FECHA_FIN;
  fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
  var strHtml = "";
  strHtml += '<tr class="even pointer">';
  strHtml += '  <td style="text-align: justify;"><i>'+objNorma.ID+'</i><br>'+objNorma.NOMBRE+'</td>';
  strHtml += '  <td>de: '+fec_ini+' <br> a: '+fec_fin+'</td>';
  strHtml += '  <td>'+objNorma.PRECIO+'</td>';
  strHtml += '  <td>'+objNorma.ID_ORGANISMO+'</td>';
  strHtml += '  <td>'+objNorma.ID_ESTANDAR+'</td>';
  strHtml += '  <td>';
  if (global_permisos["SERVICIOS"]["catalogos"] == 1 ) {
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_norma="'+objNorma.ID+'" style="float: right;"> ';
    strHtml += '      <i class="fa fa-edit"> </i> Editar norma';
    strHtml += '    </button>';
  }
  
  strHtml += '  </td>';
  strHtml += '</tr>';
  return strHtml;
}


function draw_tabla_normas(){
 $.getJSON(  global_apiserver + "/normas/getAll/", function( response ) {
      //console.log(response);
      $("#tbodyNormas").html("");
      $.each(response, function( index, objNorma ) {
        $("#tbodyNormas").append(draw_row_norma(objNorma));  
      });
      listener_btn_editar();
   });
}

function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar norma");
    clear_modal_insertar_actualizar();
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("id_norma",$(this).attr("id_norma"));
    $("#modalTitulo").html("Editar norma");
    fill_modal_insertar_actualizar($(this).attr("id_norma"));
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
  var fec_ini = $("#txtIni").val();
  fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
  var fec_fin = $("#txtTer").val();
  fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
  var norma = {
    ID:$("#txtId").val(),
    NOMBRE:$("#txtNombre").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    PRECIO:$("#txtPrecio").val(),
    ID_ORGANISMO:$("#txtIDorg").val(),
    ID_ESTANDAR:$("#txtIDsta").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
  console.log(norma);
  $.post( global_apiserver + "/normas/insert/", JSON.stringify(norma), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro","success");
        draw_tabla_normas();
      }
      else{
        notify("Error", respuesta.mensaje,"error");
      }
  });
}

function editar(){
  var fec_ini = $("#txtIni").val();
  fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
  var fec_fin = $("#txtTer").val();
  fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
  var norma = {
    ID:$("#txtId").val(),
    NOMBRE:$("#txtNombre").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    PRECIO:$("#txtPrecio").val(),
    ID_ORGANISMO:$("#txtIDorg").val(),
    ID_ESTANDAR:$("#txtIDsta").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
    $.post( global_apiserver + "/normas/update/", JSON.stringify(norma), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se han actualizado los datos","success");
          draw_tabla_normas();
        }
        else{
          notify("Error", respuesta.mensaje,"error");
        }
    });
}

 $( window ).load(function() {
    draw_tabla_normas();
    listener_btn_nuevo();
    listener_btn_guardar();
});