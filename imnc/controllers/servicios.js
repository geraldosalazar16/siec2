
function clear_modal_insertar_actualizar(){
  $("#txtId").val("");
  $("#txtId").removeAttr("readonly");
  $("#txtNombre").val("");
}

function fill_modal_insertar_actualizar(id_servicio){
  $.getJSON(  global_apiserver + "/servicios/getById/?id="+id_servicio, function( response ) {
        $("#txtId").val(response.ACRONIMO);
        $("#txtId").attr("readonly","true");
        $("#txtNombre").val(response.NOMBRE);
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

function draw_row_servicio(objServicio){
  var strHtml = "";
  strHtml += '<tr class="even pointer">';
  strHtml += '  <td>'+objServicio.ACRONIMO+'</td>';
  strHtml += '  <td>'+objServicio.NOMBRE+'</td>';
  strHtml += '  <td>';
  if (global_permisos["SERVICIOS"]["catalogos"] == 1) {
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" id_servicio="'+objServicio.ID+'" style="float: right;"> ';
    strHtml += '      <i class="fa fa-edit"> </i> Editar servicio ';
    strHtml += '    </button>';
  }
  strHtml += '  </td>';
  strHtml += '</tr>';
  return strHtml;
}


function draw_tabla_servicios(){
 $.getJSON(  global_apiserver + "/servicios/getAll/", function( response ) {
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
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
    $("#modalTitulo").html("Editar servicio");
    fill_modal_insertar_actualizar($(this).attr("id_servicio"));
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_guardar(){
  $( "#btnGuardar" ).click(function() {
	if($('#txtId').val()==""){
		$('#txtIderror').text("No debe estar vacio");
	}else{
		$("#txtIderror").text("");
		
	}
	if($('#txtNombre').val()==""){
		$('#txtNombreerror').text("No debe estar vacio");
	}else{
		$("#txtNombreerror").text("");
		
	}
	
	if(($('#txtId').val()!="")&&($('#txtNombre').val()!="")){
		if ($("#btnGuardar").attr("accion") == "insertar")
		{
		insertar();
		}
		else if ($("#btnGuardar").attr("accion") == "editar")
		{
		editar();
		}
	}
  });
}

function insertar(){
  var servicio = {
    ID:$("#txtId").val(),
    NOMBRE:$("#txtNombre").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
  $.post( global_apiserver + "/servicios/insert/", JSON.stringify(servicio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizar").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo registro", "success");
        draw_tabla_servicios();
        //document.location = "./?pagina=auditores";
      }
      else{
        notify("Error", respuesta.mensaje, "error");
      }
  });
}

function editar(){
  var servicio = {
    ID:$("#txtId").val(),
    NOMBRE:$("#txtNombre").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
    $.post( global_apiserver + "/servicios/update/", JSON.stringify(servicio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
           $("#modalInsertarActualizar").modal("hide");
          notify("Éxito", "Se han actualizado los datos", "success");
          draw_tabla_servicios();
          //document.location = "./?pagina=auditores";
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
