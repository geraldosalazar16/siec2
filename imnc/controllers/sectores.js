
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

function fill_cmb_tipo_servicio(seleccionado){
  $.getJSON(  global_apiserver + "/tipos_servicio/getAll/", function( response ) {
    $("#cmbIdTs").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $.each(response, function( indice, objTserv ) {
      $("#cmbIdTs").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
    });
    $("#cmbIdTs").val(seleccionado);
  });
}

function clear_modal_insertar_actualizar(){
  $("#txtId").val("");
  $("#txtNombre").val("");
  $("#txtAnhio").val("");
  $("#txtIni").val("");
  $("#txtTer").val("");
  fill_cmb_tipo_servicio("elige");
}

function fill_modal_insertar_actualizar(id_sector){
  $.getJSON(  global_apiserver + "/sectores/getById/?id="+id_sector, function( response ) {
        var fec_ini = response.FECHA_INICIO;
        fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
        var fec_fin = response.FECHA_FIN;
        fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
        $("#txtIdSector").val(response.ID_SECTOR);
        $("#txtId").val(response.ID);
        $("#txtAnhio").val(response.ANHIO);
        $("#txtNombre").val(response.NOMBRE);
        $("#txtIni").val(fec_ini);
        $("#txtTer").val(fec_fin);
        fill_cmb_tipo_servicio(response.ID_TIPO_SERVICIO);
     });
}

function draw_row_sector(objSector){
  var fec_ini = objSector.FECHA_INICIO;
  fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
  var fec_fin = objSector.FECHA_FIN;
  fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
  var strHtml = "";
  strHtml += '<tr class="even pointer">';
  strHtml += '  <td>'+objSector.ID+'-'+objSector.ID_TIPO_SERVICIO+'-'+objSector.ANHIO+'</td>';
  strHtml += '  <td>'+objSector.NOMBRE+'</td>';
  strHtml += '  <td>'+fec_ini+'</td>';
  strHtml += '  <td>'+fec_fin+'</td>';
  strHtml += '  <td>';
  if (global_permisos["SERVICIOS"]["catalogos"] == 1 ) {
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_sector="'+objSector.ID_SECTOR+'" style="float: right;"> ';
    strHtml += '      <i class="fa fa-edit"> </i> Editar sector ';
    strHtml += '    </button>';
  }
 
  strHtml += '  </td>';
  strHtml += '</tr>';
  return strHtml;
}


function draw_tabla_sectores(){
 $.getJSON(  global_apiserver + "/sectores/getAll/", function( response ) {
      //console.log(response);
      $("#tbodySectores").html("");
      $.each(response, function( index, objSector ) {
        $("#tbodySectores").append(draw_row_sector(objSector));  
      });
      listener_btn_editar();
   });
}

function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar sector");
    clear_modal_insertar_actualizar();
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("id_ser",$(this).attr("id_sector"));
    $("#modalTitulo").html("Editar sector");
    fill_modal_insertar_actualizar($(this).attr("id_sector"));
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
  var sector = {
    ID:$("#txtId").val(),
    ID_TIPO_SERVICIO:$("#cmbIdTs").val(),
    ANHIO:$("#txtAnhio").val(),
    NOMBRE:$("#txtNombre").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    ID_USUARIO:sessionStorage.getItem("id_usuario")    
  };
  $.post( global_apiserver + "/sectores/insert/", JSON.stringify(sector), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", 'success');
        draw_tabla_sectores();
      }
      else{
        notify("Error", respuesta.mensaje, 'error');
      }
  });
}

function editar(){
  var fec_ini = $("#txtIni").val();
  fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
  var fec_fin = $("#txtTer").val();
  fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
  var sector = {
    ID:$("#txtId").val(),
    ID_SECTOR:$("#txtIdSector").val(),
    ID_TIPO_SERVICIO:$("#cmbIdTs").val(),
    ANHIO:$("#txtAnhio").val(),
    NOMBRE:$("#txtNombre").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    ID_USUARIO:sessionStorage.getItem("id_usuario")      
  };
    $.post( global_apiserver + "/sectores/update/", JSON.stringify(sector), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se han actualizado los datos", 'success');
          draw_tabla_sectores();
        }
        else{
          notify("Error", respuesta.mensaje, 'error');
        }
    });
}

 $( window ).load(function() {
    draw_tabla_sectores();
    listener_btn_nuevo();
    listener_btn_guardar();
});
