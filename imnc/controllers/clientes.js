var respuesta = 1;
function draw_ficha_clientes(objeto)
{
  if (objeto.IMAGEN_BASE64 === null){
    imagenHtml = '  <img src="./pictures/user.png" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive btnSubirImagen" cliente="'+objeto.ID+'">';
  }
  else
  {
    imagenHtml = '  <img src="'+objeto.IMAGEN_BASE64+'" style="width: 95px; height: 95px; cursor: pointer;" alt="" class="img-circle img-responsive btnSubirImagen" cliente="'+objeto.ID+'">';
  }
  strHtml = '';
  strHtml += '<div class="col-md-4 col-sm-4 col-xs-12 animated fadeInDown">';
  strHtml += '  <div class="well profile_view">';
  strHtml += '    <div class="col-sm-12" style="height: 250px;">';
  strHtml += '      <h4 class="brief"><i>Clave: '+objeto.ID+'</i></h4>';
  strHtml += '      <div class="left col-xs-9">';
  strHtml += '        <h5>'+objeto.NOMBRE+'</h5>';  
  strHtml += '        <ul class="list-unstyled">';
  strHtml += '          <li><strong>RFC: </strong> '+ (Boolean(objeto.RFC)? objeto.RFC : "Sin RFC"  )+'</li>';
  strHtml += '          <li><strong>¿Se le facturan los servicios? </strong> '+ ((objeto.ES_FACTURARIO == "S") ? "Si" : "No") +'</li>';
  // strHtml += '          <li><strong>Tiene Facturatario: </strong> '+objeto.TIENE_FACTURARIO+'</li>';
  if (typeof  objeto.NOMBRE_FACTURATARIO !== "undefined") {
    strHtml += '          <li><strong>Cliente Facturatario: </strong> '+objeto.NOMBRE_FACTURATARIO+'</li>';
  }
  
  strHtml += '          <li><strong>Tipo de Persona: </strong> '+objeto.TIPO_PERSONA+'</li>';
  strHtml += '          <li><strong>Tipo de Entidad: </strong> '+objeto.TIPO_ENTIDAD+'</li>';
  //strHtml += '          <li><strong>¿Única razón social?: </strong> '+ ((objeto.UNICA_RAZON_SOCIAL == "S") ? "Si" : "No") +'</li>';
  strHtml += '        </ul>';
  strHtml += '      </div>';
  strHtml += '      <div class="right col-xs-3 text-center" style="padding: 0px;">';
  strHtml += imagenHtml;
  strHtml += '      </div>';
  strHtml += '    </div>';
  strHtml += '    <div class="col-xs-12 bottom text-center">';
  strHtml += '      <div class="col-xs-12 col-sm-12 emphasis">';
  //Agregar como prospecto
  strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnAgregarProspecto" cliente="'+objeto.ID+'" style="float: right;">';
  strHtml += '            <i class="fa fa-user-plus"> </i> Agregar prospecto </button>';
 if (global_permisos["CLIENTES"]["editar"] == 1) {
      strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditar" cliente="'+objeto.ID+'" style="float: right;">';
      strHtml += '            <i class="fa fa-edit"> </i> Editar </button>';
  }
  strHtml += '        <a href="./?pagina=cliente_perfil&id='+objeto.ID+'&entidad=2" class="btn btn-primary btn-xs btn-imnc" style="float: right;">';
  strHtml += '            <i class="fa fa-home"> </i> Domicilios </a>';
  if(global_permisos["EXPEDIENTES"]["ver"] == 1){
    strHtml += '        <a href="./?pagina=registro_expediente&id='+objeto.ID+'&id_entidad=1" class="btn btn-primary btn-xs btn-imnc" style="float: right;">';
    strHtml += '            <i class="fa fa-home"> </i> Expedientes </a>';
  }
  strHtml += '      </div>';
  strHtml += '    </div>';
  strHtml += '  </div>';
  strHtml += '</div>';


  return strHtml;
}

function draw_inyecta_otra_razon_social(otra_razon_social){
  if (otra_razon_social == "") {
    return;
  }
  var consecutivo = parseInt($("#btnInsertaRazonSocial").attr("countOtrasRazonesSociales"));

  strHtml = '';
  strHtml += '<div class="row" style="margin-bottom: 25px;" id="rowOtraRazonSocial'+consecutivo+'">';
  strHtml += '  <div class="col-md-10 col-sm-10 col-xs-10">';
  strHtml += '    <input type="text" value="'+otra_razon_social+'" id="txtOtraRazonSocial'+consecutivo+'" required="required" class="txtOtrasRazonesSociales form-control col-md-7 col-xs-12">';
  strHtml += '  </div>';
  strHtml += '  <div class="col-md-2 col-sm-2 col-xs-2">';
  strHtml += '    <button class="btn btn-default btn-sm btnEliminaRazonSocial" consecutivo="'+consecutivo+'" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
  strHtml += '  </div>';
  strHtml += '</div>';

  $("#spanOtrasRazonesSociales").prepend(strHtml);
  
  consecutivo = consecutivo + 1;
  $("#btnInsertaRazonSocial").attr("countOtrasRazonesSociales", ""+consecutivo);

  $(".btnEliminaRazonSocial").unbind("click");
  listener_btn_elimina_razon_social();
}

function draw_destruye_otra_razon_social(consecutivo){
  $( "#rowOtraRazonSocial"+consecutivo ).remove();
}

function draw_otras_razones_sociales()
{
  strHtml = '';
  strHtml += '</div>';
  strHtml += '<label class="control-label col-md-12 col-sm-12 col-xs-12"> ';
  strHtml += '  Otras razones sociales <span class="required">*</span>';
  strHtml += '</label>';
  strHtml += '<span id="spanOtrasRazonesSociales">';

  strHtml += '<div class="row" style="margin-bottom: 25px;">';
  strHtml += '  <div class="col-md-10 col-sm-10 col-xs-10">';
  strHtml += '    <input type="text" id="txtOtraRazonSocialInsertar" required="required" class="form-control col-md-7 col-xs-12">';
  strHtml += '  </div>';
  strHtml += '  <div class="col-md-2 col-sm-2 col-xs-2">';
  strHtml += '    <button class="btn btn-primary btn-sm" id="btnInsertaRazonSocial" countOtrasRazonesSociales="1">Insertar</button>';
  strHtml += '  </div>';

  strHtml += '</span>';


  strHtml += '</div>';


  $("#formGroupOtrasRazonesSociales").html(strHtml);

  $("#btnInsertaRazonSocial").unbind("click");
  listener_btn_inserta_razon_social();
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

function clear_modal_insertar_actualizar(){
  $("#txtNombre").val("");
  $("#txtRfc").val("");
  $("#txtFecReg").val("");
  $("#cmbEsFacturario").val("");
  $("#cmbUnicaRazonSocial").val("");
  $("#cmbEsFacturario").change();
  $("#cmbUnicaRazonSocial").change();
  
  fill_cmb_tipo_persona("elige");
  fill_cmb_tipo_entidad("elige");
  fill_cmb_cliente_factuario("elige");

  $("#txtRfc").prop("placeholder", "SIA090305XXX");
  $("#txtRfc").prop("disabled", false);
  $('#chkRfc').prop('checked', false);

  
  draw_otras_razones_sociales();
}

function fill_modal_insertar_actualizar(id_cliente){
  $.getJSON(  global_apiserver + "/clientes/getById/?id="+id_cliente, function( response ) {
        
        $("#txtNombre").val(response.NOMBRE);
        $("#cmbEsFacturario").val(response.ES_FACTURARIO);
        $("#cmbEsFacturario").change();
		$("#txtClienteFacturario").val(response.CLIENTE_FACTURARIO);
		$("#txtRFCFac").val(response.RFC_FACTURARIO);
        //$("#cmbUnicaRazonSocial").val(response.UNICA_RAZON_SOCIAL);
        //$("#cmbUnicaRazonSocial").change();
        
        $("#txtRfc").val(response.RFC);
        fill_cmb_tipo_persona(response.ID_TIPO_PERSONA);
        fill_cmb_tipo_entidad(response.ID_TIPO_ENTIDAD);
        //fill_cmb_cliente_factuario(response.ID_CLIENTE_FACTURARIO);

        if (!Boolean(response.RFC)) {
          $("#txtRfc").prop("placeholder", "");
          $("#txtRfc").prop("disabled", true);
          $('#chkRfc').prop('checked', true);
        }
        else{
          $("#txtRfc").prop("placeholder", "SIA090305XXX");
          $("#txtRfc").prop("disabled", false);
          $('#chkRfc').prop('checked', false);
        }

        /*draw_otras_razones_sociales();

        $.each(response.OTRAS_RAZONES_SOCIALES, function( indice, objOtraRazonSocial ) {
          draw_inyecta_otra_razon_social(objOtraRazonSocial.OTRA_RAZON_SOCIAL);
          console.log(objOtraRazonSocial);
        });*/
     });
  
}
function fill_cmb_origen(seleccionado){
    $.getJSON( global_apiserver + "/prospecto_origen/getAll/", function( response ) {
      $("#cmbOrigen").html('<option value="elige" selected disabled> -- Elige un origen -- </option>');
      $.each(response, function( indice, objOrigen ) {
        $("#cmbOrigen").append('<option value="'+objOrigen.ID+'">'+objOrigen.DESCRIPCION+'</option>'); 
      });
      $("#cmbOrigen").val(seleccionado);
    });
}
function fill_cmb_tipo_servicio(seleccionado,servicio){
    $("#cmbTipoServicio").html('<option value="elige" selected disabled>-- Elige un tipo de servicio --</option>');
	
	if(servicio == "elige" || !servicio)
	{
		$.getJSON(  global_apiserver + "/tipos_servicio/getAll/", function( response ) {
			$.each(response, function( indice, objTserv ) {
			$("#cmbTipoServicio").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
			});
		});		
	}
	else
	{
		$.getJSON(  global_apiserver + "/tipos_servicio/getByService/?id="+servicio, function( response ) {
			$.each(response, function( indice, objTserv ) {
			$("#cmbTipoServicio").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
			}); 
		});	
	}
	//Selecciona el valor que se pase por parámetro
	if(seleccionado == "" || !seleccionado) 
		seleccionado = "sin_asignar";
    $("#cmbTipoServicio").val(seleccionado);
}

function fill_cmb_competencias(seleccionado){
    $.getJSON( global_apiserver + "/prospecto_competencia/getAll/", function( response ) {
      $("#cmbCompetencias").html('<option value="elige" selected disabled> -- Elige una competencia -- </option>');
      $.each(response, function( indice, objCompetencia ) {
        $("#cmbCompetencias").append('<option value="'+objCompetencia.ID+'">'+objCompetencia.DESCRIPCION+'</option>'); 
      });
      $("#cmbCompetencias").val(seleccionado);
    });
}
function fill_cmb_estatus_seguimiento(seleccionado){
    $.getJSON( global_apiserver + "/prospecto_estatus_seguimiento/getAll/", function( response ) {
      $("#cmbEstatusSeguimiento").html('<option value="elige" selected disabled> -- Elige un status -- </option>');
      $.each(response, function( indice, objStatus ) {
        $("#cmbEstatusSeguimiento").append('<option value="'+objStatus.ID+'">'+objStatus.DESCRIPCION+'</option>'); 
      });
      $("#cmbEstatusSeguimiento").val(seleccionado);
    });
}
function fill_cmb_tipo_contrato(seleccionado){
    $.getJSON( global_apiserver + "/prospecto_tipo_contrato/getAll/", function( response ) {
      $("#cmbTipoContrato").html('<option value="elige" selected disabled> -- Elige un tipo de contrato -- </option>');
      $.each(response, function( indice, objTipoContrato ) {
        $("#cmbTipoContrato").append('<option value="'+objTipoContrato.ID+'">'+objTipoContrato.DESCRIPCION+'</option>'); 
      });
      $("#cmbTipoContrato").val(seleccionado);
    });
}
function fill_cmb_usuarios(seleccionado){
    $.getJSON( global_apiserver + "/usuarios/getAll/", function( response ) {
      $("#cmbUsuarios").html('<option value="" selected>-ninguno-</option>');
      $.each(response, function( indice, objUsuario ) {
        $("#cmbUsuarios").append('<option value="'+objUsuario.ID+'">'+objUsuario.NOMBRE+'</option>'); 
      });
      $("#cmbUsuarios").val(seleccionado);
    });
}
function fill_cmb_departamento(seleccionado){
    $.getJSON( global_apiserver + "/departamentos/getAll/", function( response ) {
      $("#cmbDepartamentos").html('<option value="elige" selected disabled> -- Elige un departamento -- </option>');
      $.each(response, function( indice, objDep ) {
        $("#cmbDepartamentos").append('<option value="'+objDep.ID+'">'+objDep.NOMBRE+'</option>'); 
      });
      $("#cmbDepartamentos").val(seleccionado);
    });
}

function fill_cmb_tipo_persona(seleccionado){
    $.getJSON( global_apiserver + "/tipos_persona/getAll/", function( response ) {
      $("#cmbTPersona").html('<option value="elige" selected disabled> -- elige una opción -- </option>');
      $.each(response, function( indice, objTPersona ) {
        $("#cmbTPersona").append('<option value="'+objTPersona.ID+'">'+objTPersona.TIPO+'</option>'); 
      });
      $("#cmbTPersona").val(seleccionado);
    });
}

function fill_cmb_tipo_entidad(seleccionado){
    $.getJSON( global_apiserver + "/tipos_entidad/getAll/", function( response ) {
      $("#cmbTEntidad").html('<option value="elige" selected disabled> -- elige una opción -- </option>');
      $.each(response, function( indice, objTEntidad ) {
        $("#cmbTEntidad").append('<option value="'+objTEntidad.ID+'">'+objTEntidad.TIPO+'</option>'); 
      });
      $("#cmbTEntidad").val(seleccionado);
    });
}

function fill_cmb_cliente_factuario(seleccionado){
    $.getJSON( global_apiserver + "/clientes/getAll/", function( response ) {
      $("#cmbClienteFac").html('<option value="elige" rfc="" selected disabled> -- elige una opción -- </option>');
      $("#cmbClienteFac").append('<option value="null"  rfc="">Ninguno</option>');
      $.each(response, function( indice, objClienteFact ) {
        $("#cmbClienteFac").append('<option value="'+objClienteFact.ID+'"  rfc="'+objClienteFact.RFC+'">'+objClienteFact.NOMBRE+'</option>'); 
      });
      if (seleccionado == null) {
        $("#cmbClienteFac").val("null");
        $("#cmbClienteFac").prop("disabled", true);
      }
      else if (seleccionado == "elige") {
        $("#cmbClienteFac").prop("disabled", true);
      }
      else
      {
        $("#cmbClienteFac").val(seleccionado);
        $("#txtRFCFac").val($("#cmbClienteFac option:selected").attr("rfc"));
      }
      $( "#cmbClienteFac").unbind("change");
      //listener_cmb_cliente_facturatario();
      
    });
}

function draw_all_fichas(){
  $(".loading").show();
   $.getJSON(  global_apiserver + "/clientes/getAll/", function( response ) {
        //console.log();
        $("#area_fichas").html("");
        $.each(response, function( index, objeto ) {
          $("#area_fichas").append(draw_ficha_clientes(objeto));  
        });
        listener_btn_editar();
		listener_btn_agregar_prospecto();
        listener_btn_subir_imagen();
        $(".loading").hide();
     });

}

function draw_fichas_con_filtro(){
    var filtros = {
      NOMBRE:$("#txtFiltroNombreCliente").val(),
      NOMBRE_CONTAINS:$("#txtFiltroNombreClienteContains").val(),
      RFC:$("#txtFiltroRFC").val(),
      RFC_CONTAINS:$("#txtFiltroRFCContains").val(),
      ENTIDAD_FEDERATIVA:$("#txtFiltroEntidadFederativa").val(),
      ENTIDAD_FEDERATIVA_CONTAINS:$("#txtFiltroEntidadFederativaContains").val(),
      MUNICIPIO:$("#txtFiltroMunicipio").val(),
      MUNICIPIO_CONTAINS:$("#txtFiltroMunicipioContains").val(),
      CP:$("#txtFiltroCodigoPostal").val(),
      CP_CONTAINS:$("#txtFiltroCodigoPostalContains").val(),
      NOMBRE_CONTACTO:$("#txtFiltroNombreContacto").val(),
      NOMBRE_CONTACTO_CONTAINS:$("#txtFiltroNombreContactoContains").val(),
    };
    $(".loading").show();
    jQuery('html, body').animate({scrollTop : 0},500);
    $.post(global_apiserver + "/clientes/getByFiltro/", JSON.stringify(filtros), function(respuesta){
        response = JSON.parse(respuesta);
        $("#area_fichas").html("");
        if (response.length == 0) {
           $("#area_fichas").html("No se encontraron resultados");
        }
        $.each(response, function( index, objeto ) {
          $("#area_fichas").append(draw_ficha_clientes(objeto));  
        });
        listener_btn_editar();
		listener_btn_agregar_prospecto();
        listener_btn_subir_imagen();
        $(".loading").hide();
    });
}

function listener_btn_limpiar_filtros(){
  $( "#btnLimpiarFiltros" ).click(function() {
    $(".input-filtro").val("");
    draw_all_fichas();
  });
}

function listener_btn_elimina_razon_social(){
  $( ".btnEliminaRazonSocial" ).click(function() {
    draw_destruye_otra_razon_social($(this).attr("consecutivo"));
  });
}

function listener_btn_inserta_razon_social(){
  $( "#btnInsertaRazonSocial" ).click(function() {
    var _otra_razon_social = $("#txtOtraRazonSocialInsertar").val();

    draw_inyecta_otra_razon_social(_otra_razon_social);

    $("#txtOtraRazonSocialInsertar").val("");
  });
}

function listener_btn_filtrar(){
  $( "#btnFiltrar" ).click(function() {
      draw_fichas_con_filtro();
  });
}

function listener_txt_nombre(){
  $('#txtNombre').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
}

  function listener_txt_rfc(){
    $('#txtRFCFac').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

function listener_txt_nombre_facturario(){
    $('#txtClienteFacturario').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }
  function listener_txt_rfc_cliente(){
    $('#txtRfc').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }
function listener_btn_nuevo(){
  $( "#btnNuevo" ).click(function() {
    $("#btnGuardar").attr("accion","insertar");
    $("#modalTitulo").html("Insertar nuevo registro");
    clear_modal_insertar_actualizar();
    $("#modalInsertarActualizar").modal("show");
  });
}

function listener_btn_editar(){
  $( ".btnEditar" ).click(function() {
    $("#btnGuardar").attr("accion","editar");
    $("#btnGuardar").attr("idCliente",$(this).attr("cliente"));
    $("#modalTitulo").html("Editar registro");
    fill_modal_insertar_actualizar($(this).attr("cliente"));
    $("#modalInsertarActualizar").modal("show");
  });
}
function listener_btn_agregar_prospecto(){
  $( ".btnAgregarProspecto" ).click(function() {
    $("#btnGuardarProspecto").attr("accion","insertar");
    $("#btnGuardarProspecto").attr("idCliente",$(this).attr("cliente"));
    $("#modalAgregarProspectoTitulo").html("Agregar Prospecto");
    fill_modal_agregar_propsecto($(this).attr("cliente"));
    $("#modalAgregarProspecto").modal("show");
  });
}
function fill_modal_agregar_propsecto(id_cliente){
	$.getJSON(  global_apiserver + "/clientes/getById/?id="+id_cliente, function( response ) {
        
        $("#nombre").val(response.NOMBRE);
		//$("#txtRFCFac").val(response.RFC_FACTURARIO);        
        $("#rfc").val(response.RFC);
		 $("#giro").val("");
		 
		fill_cmb_origen("elige");
        fill_cmb_tipo_servicio("elige","elige");
        fill_cmb_competencias("elige");
		fill_cmb_estatus_seguimiento("elige");
		fill_cmb_tipo_contrato("elige");
		fill_cmb_usuarios("elige");
		fill_cmb_departamento("elige");
				
		$("#cbhabilitado").prop('checked', true);
        
	});
	listener_txt_nombre();
	listener_txt_rfc();
	//$(".select2_single").select2();
}
function listener_txt_nombre(){
	  $('#nombre').keyup(function(){
	      $(this).val($(this).val().toUpperCase());
	  });
	}

  function listener_txt_rfc(){
    $('#rfc').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }
function listener_btn_subir_imagen(){
  $( ".btnSubirImagen" ).click(function() {
    var _id_cliente = $(this).attr("cliente");
    var uploadObj = $("#singleupload").uploadFile({
      url: global_apiserver + "/clientes/uploadImagen/",
      multiple:false,
      dragDrop:false,
      maxFileCount:1,
      acceptFiles:"image/*",
      fileName:"myfile",
      formData: {"id_cliente":_id_cliente}, 
      onSuccess:function(files,data,xhr,pd)
      {
        $("#modalSubirImagen").modal("hide");
        notify("Éxito", "La imagen ha cambiado", "success");
        draw_all_fichas();
        uploadObj.reset();
      }
    });
    $("#modalSubirImagen").modal("show");
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
function listener_btn_guardar_prospecto(){
  $( "#btnGuardarProspecto" ).click(function() {
    if ($("#btnGuardarProspecto").attr("accion") == "insertar")
    {
      insertar_prospecto($("#btnGuardarProspecto").attr("idCliente"));
    }
    else if ($("#btnGuardarProspecto").attr("accion") == "editar")
    {
      //editar();
    }
  });
}

function listener_chk_rfc(){
  $("#chkRfc").change(function() {
    console.log("checkpoint");
    if($(this).is(":checked")) {
      $("#txtRfc").val("");
      $("#txtRfc").prop("placeholder", "");
      $("#txtRfc").prop("disabled", true);
    }
    else{
      $("#txtRfc").prop("placeholder", "SIA090305XXX");
      $("#txtRfc").prop("disabled", false);
    }
  });
}

/*function listener_cmb_cliente_facturatario(){
  $("#cmbClienteFac").change(function() {
    $("#txtRFCFac").val($("#cmbClienteFac option:selected").attr("rfc"));
  });
}*/

function listener_cmb_unica_razon_social(){
  $("#cmbUnicaRazonSocial").change(function() {
    if ($("#cmbUnicaRazonSocial option:selected").val() == "N"){
      $("#formGroupOtrasRazonesSociales").show();
    }
    else{
      $("#formGroupOtrasRazonesSociales").hide();
    }
  });
}

function listener_cmb_es_facturario(){
    $("#cmbEsFacturario").change(function() {
      if ($(this).val() == "S") {
        $("#txtClienteFacturario").val("");
		$("#txtClienteFacturario").prop("disabled", true);
		$("#txtRFCFac").val("");
		$("#txtRFCFac").prop("disabled", true);
      }
      else if ($(this).val() == "N") {
        $("#txtClienteFacturario").val("");
		$("#txtClienteFacturario").prop("disabled", false);
		$("#txtRFCFac").val("");
		$("#txtRFCFac").prop("disabled", false);
      }
      else{
        $("#txtClienteFacturario").val("");
		$("#txtClienteFacturario").prop("disabled", true);
		$("#txtRFCFac").val("");
		$("#txtRFCFac").prop("disabled", true);
      }
    });
  }

function hide_modal_inserta_actualiza(){
  $("#modalInsertarActualizar").modal("hide");
}

function insertar(){
    /*if (clienteFac == "null") {
      clienteFac = null;
    }*/
    // Trucazo!!!! Obtiene todos los valores de los inputs en un arreglo
    //var arr_otras_razones_sociales = $(".txtOtrasRazonesSociales").map(function(){return $(this).val();}).get();
    var cliente = {
      NOMBRE:$("#txtNombre").val(),
      RFC:$("#txtRfc").val(),
      ES_FACTURARIO:$("#cmbEsFacturario").val(),
      RFC_FACTURARIO:$("#txtRFCFac").val(),
      //TIENE_FACTURARIO:$("#cmbTieneFacturario").val(),
      //UNICA_RAZON_SOCIAL:$("#cmbUnicaRazonSocial").val(),
      //OTRAS_RAZONES_SOCIALES:arr_otras_razones_sociales,
      ID_TIPO_ENTIDAD:$("#cmbTEntidad").val(),
      ID_TIPO_PERSONA:$("#cmbTPersona").val(),
	  CLIENTE_FACTURARIO:$("#txtClienteFacturario").val(),
      //ID_CLIENTE_FACTURARIO:clienteFac,
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/clientes/insert/", JSON.stringify(cliente), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          hide_modal_inserta_actualiza();
          notify("Éxito", "Se ha insertado un nuevo registro", "success");
          draw_all_fichas();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}
/*
function valida_agregar_prospecto(id_cliente){
	respuesta = 1;
		if($("nombre").val() !== "" && !$("nombre").val()){	
			$.getJSON(  global_apiserver + "/clientes/getById/?id="+id_cliente, function( response ) {
					if(response.length > 0){
						respuesta=0;	
						$("#nombreerror").text("Nombre ya registrado");						
					}else{
						$("#nombreerror").text("");
					}
				}
		}else{
			$scope.respuesta =  0;
			$("#nombreerror").text("No debe estar vacio");
		}
}
*/
function insertar_prospecto(id_cliente){
    //valida_agregar_prospecto();
	var habilitado = 0;
	if($("cbhabilitado").checked == true)
	{
		habilitado = 1;
	}
    var prospecto = {
	    NOMBRE:$("#nombre").val(),
		RFC:$("#rfc").val(),
		GIRO:$("#giro").val(),
		ID_USUARIO_CREACION: sessionStorage.getItem("id_usuario"),
		ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario"),
		ID_CLIENTE:id_cliente,
		ACTIVO:habilitado,
		ORIGEN : $("#cmbOrigen").val(),
		TIPO_SERVICIO: $("#cmbTipoServicio").val(),
		COMPETENCIA:$("#cmbCompetencias").val(),
		ESTATUS_SEGUIMIENTO :$("#cmbEstatusSeguimiento").val(),
		TIPO_CONTRATO : $("#cmbTipoContrato").val(),
		ID_USUARIO:sessionStorage.getItem("id_usuario"),
		ID_USUARIO_SECUNDARIO:$("#cmbUsuarios").val(),
		DEPARTAMENTO:$("#cmbDepartamentos").val()
    };
    $.post( global_apiserver + "/prospecto/insert/", JSON.stringify(prospecto), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalAgregarProspecto").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo prospecto", "success");
		  //Aca debe mandarme a la pagina del prospecto
		  window.location.href = "./?pagina=perfilprospecto&id="+respuesta.id+"&entidad=1";
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

function editar(){
    /*if (clienteFac == "null") {
      clienteFac = null;
    }*/
    // Trucazo!!!! Obtiene todos los valores de los inputs en un arreglo
    //var arr_otras_razones_sociales = $(".txtOtrasRazonesSociales").map(function(){return $(this).val();}).get();
    var cliente = {
      ID_CLIENTE:$("#btnGuardar").attr("idCliente"),
      NOMBRE:$("#txtNombre").val(),
      RFC:$("#txtRfc").val(),
      ES_FACTURARIO:$("#cmbEsFacturario").val(),
      //TIENE_FACTURARIO:$("#cmbTieneFacturario").val(),
      //UNICA_RAZON_SOCIAL:$("#cmbUnicaRazonSocial").val(),
	  CLIENTE_FACTURARIO:$("#txtClienteFacturario").val(),
	  RFC_FACTURARIO:$("#txtRFCFac").val(),
      //OTRAS_RAZONES_SOCIALES:arr_otras_razones_sociales,
      ID_TIPO_ENTIDAD:$("#cmbTEntidad").val(),
      ID_TIPO_PERSONA:$("#cmbTPersona").val(),
      //ID_CLIENTE_FACTURARIO:clienteFac,
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post( global_apiserver + "/clientes/update/", JSON.stringify(cliente), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          hide_modal_inserta_actualiza();
          notify("Éxito", "Se ha actualizado el registro", "success");
          draw_all_fichas();
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

  $( window ).load(function() {
      //draw_all_fichas();
      listener_btn_nuevo();
      listener_btn_guardar();
	  listener_btn_guardar_prospecto();
      listener_txt_nombre();
	  listener_txt_nombre_facturario();
      listener_txt_rfc();
	  listener_txt_rfc_cliente();
      listener_cmb_es_facturario();
      listener_chk_rfc();
      listener_btn_limpiar_filtros();
      listener_btn_filtrar();
      listener_cmb_unica_razon_social();
      //$("#cmbClienteFac").prop("disabled", true);
  });

