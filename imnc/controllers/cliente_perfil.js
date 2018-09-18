$( window ).load(function() {
  draw_perfil();
  draw_domicilios(); 
  listener_btn_nuevo_domicilio();
  listener_btn_guardar_domicilio();
  listener_btn_guardar_contacto();
  listener_txt_nombre_domicilio();
  listener_txt_nombre_domicilio();
  listener_txt_calle();
  listener_autocomplete_pais_change();
  listener_autocomplete_cp_change();
  listener_autocomplete_colonia_change();
  colonia_checkbox();
  
});

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

function fill_modal_insertar_actualizar_domicilio(id_domicilio){
  $.getJSON( global_apiserver + "/clientes_domicilios/getById/?id="+id_domicilio, function( response ) {

        $("#txtNomDom").val(response.NOMBRE_DOMICILIO);
        $("#txtCalle").val(response.CALLE);
        $("#txtNoExt").val(response.NUMERO_EXTERIOR);
        $("#txtNoInt").val(response.NUMERO_INTERIOR);
       
        $("#txtDelegacion").val(response.DELEGACION_MUNICIPIO);
        $("#txtEntidadFederativa").val(response.ENTIDAD_FEDERATIVA);
        $("#txtCP").val(response.CP);
        $("#txtColonia").val(response.COLONIA_BARRIO);
        $("#newColonia").hide();
        $("#nuevaColonia").val(""); 
        $( "#chkCol" ).prop("checked", false);
        $("#campoSelectColonia").show();
        $("#auxColonia").hide();
        fill_autocomplete_pais(response.PAIS);
        $("#cmbEsFiscal").val(response.ES_FISCAL);
     });
}

function fill_modal_insertar_actualizar_contacto(id_contacto){
  $.getJSON( global_apiserver + "/clientes_contactos/getById/?id="+id_contacto, function( response ) {
        var fec_ini = response.FECHA_INICIO;
        fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
        var fec_fin = response.FECHA_FIN;
        fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
        $("#txtTipoContacto").val(response.ID_TIPO_CONTACTO);
        $("#txtNombreContacto").val(response.NOMBRE_CONTACTO);
        $("#txtCargoContacto").val(response.CARGO);
        $("#txtTelMovil").val(response.TELEFONO_MOVIL);
        $("#txtTelFijo").val(response.TELEFONO_FIJO);
        $("#txtExtension").val(response.EXTENSION);
        $("#txtEmail").val(response.EMAIL);
        $("#cmbEsPrincipal").val(response.ES_PRINCIPAL);
        $("#datos_adicionales").val(response.DATOS_ADICIONALES);
     });
}

function fill_autocomplete_pais(seleccionado){
  $.getJSON( global_apiserver + "/paises/getAll/", function( response ) {
    $("#autocompletePais").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $.each(response, function( indice, objPais ) {
      if (seleccionado == objPais.NOMBRE) {
        $("#autocompletePais").append('<option value="'+objPais.NOMBRE+'" selected>'+objPais.NOMBRE+'</option>'); 
      }else{
        $("#autocompletePais").append('<option value="'+objPais.NOMBRE+'">'+objPais.NOMBRE+'</option>'); 
      }
      
    });
    $("#autocompletePais").val(seleccionado);
    $('#autocompletePais').select2();
    $("#autocompletePais" ).change();
  });
}

function fill_autocomplete_cp(seleccionado){  
  $("#autocompleteCP").html('<option value="elige" selected disabled>-elige una opción-</option>');
  $("#autocompleteCP").append('<option value="'+seleccionado+'" selected>'+seleccionado+'</option>'); 
  
  
  $("#autocompleteCP" ).select2({
    language: "es",
    ajax: {
      url: global_apiserver + "/codigos_postales/getCPs/",
      dataType: 'json',
      delay: 50,

      data: function (params) {
        return {
          term: params.term, // search term
        };
      },
      results: function (data) {
          return {
              results: $.map(data, function (item) {
                  return {
                      text: item.CP,
                      slug: item.CP,
                      id: item.CP
                  }
              })
          };
      },
      processResults: function (data, params) {
        return {
              results: $.map(data, function (item) {
                  return {
                      text: item.CP,
                      slug: item.CP,
                      id: item.CP
                  }
              })
          };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  });
  $("#autocompleteCP" ).change();
}

function fill_autocomplete_colonia(seleccionado, cp){
  $.getJSON( global_apiserver + "/codigos_postales/getColoniaByCP/?cp="+cp, function( response ) {
    $("#autocompleteColonia").html('<option value="elige" selected disabled>-elige una opción-</option>');
    $.each(response, function( indice, objColonia ) {
      //console.log(seleccionado);
      if (seleccionado == objColonia.COLONIA_BARRIO) {
        $("#autocompleteColonia").append('<option value="'+objColonia.COLONIA_BARRIO+'" selected>'+objColonia.COLONIA_BARRIO+'</option>'); 
      }else{
        $("#autocompleteColonia").append('<option value="'+objColonia.COLONIA_BARRIO+'">'+objColonia.COLONIA_BARRIO+'</option>'); 
      }
      
    });
    $("#autocompleteColonia").val(seleccionado);
    $('#autocompleteColonia').select2();
    $("#autocompleteColonia" ).change();
    if(seleccionado != "" && !$("#autocompleteColonia").val()){
      $("#newColonia").show();
      $("#nuevaColonia").val(seleccionado); 
      $( "#chkCol" ).prop("checked", true);
      $("#campoSelectColonia").hide();
      $("#auxColonia").show();
    }
  });
}

function clear_autocomplete_cp(){
  $("#autocompleteCP").html('<option value="elige" selected disabled>-elige una opción-</option>');
  try {
    $("#autocompleteCP").select2("destroy");
  }
  catch(err) {
    if (err.message != "Cannot read property 'destroy' of undefined") {
      throw err;
    }
      
  }
}

function clear_autocomplete_colonia(){
  $("#autocompleteColonia").html('<option value="elige" selected disabled>-elige una opción-</option>');
  try {
    $("#autocompleteColonia").select2("destroy");
  }
  catch(err) {
      if (err.message != "Cannot read property 'destroy' of undefined") {
        throw err;
      }
  }
}

function listener_autocomplete_pais_change(){
  $( "#autocompletePais" ).off('change').change(function() {
    
    if($(this).val() == "MEXICO (ESTADOS UNIDOS MEXICANOS)"){
      
      $("#txtCP").hide();
      $("#txtColonia").hide();
      $("#autocompleteCP").show();
      $("#autocompleteColonia").show();  
      fill_autocomplete_cp($("#txtCP").val());
      //fill_autocomplete_colonia($("#txtColonia").val(), $("#txtCP").val());
      $("#txtEntidadFederativa").prop("readonly", true);
      $("#txtDelegacion").prop("readonly", true);
      $("#campoChkCol").show();
    }
    else
    {
      $("#txtCP").show();
      $("#txtColonia").show();
      clear_autocomplete_cp();
      clear_autocomplete_colonia();
      $("#autocompleteCP").hide();
      $("#autocompleteColonia").hide();
      $("#txtEntidadFederativa").prop("readonly", false);
      $("#txtDelegacion").prop("readonly", false);
      $("#campoChkCol").hide();
    }
    
  });
}

function listener_autocomplete_cp_change(){
	
  $( "#autocompleteCP").off('change').change(function() {
	  //console.log(1);
    $("#txtCP").val($(this).val());

    if($(this).val() != ""){
      $("#txtColonia").hide();
      $("#autocompleteColonia").show();
      fill_autocomplete_colonia($("#txtColonia").val(), $(this).val()); //CP como parámetro
	  get_delegacion_y_entidad($(this).val());
    }
    else
    {
      $("#txtColonia").show();
      clear_autocomplete_colonia();
      $("#autocompleteColonia").hide();
    }
    if($(this).val() != "" && $("#txtColonia").val() != ""){
      get_delegacion_y_entidad($(this).val(), $("#txtColonia").val());
	   //console.log(1);
    }
    
  });
}

function listener_autocomplete_colonia_change(){
  $( "#autocompleteColonia" ).off('change').change(function() {
    $("#txtColonia").val($(this).val());
    if($(this).val() != "" && $("#txtCP").val() != ""){
      get_delegacion_y_entidad($("#txtCP").val(), $(this).val());
    }
    
  });
}

function get_delegacion_y_entidad(cp, colonia){
  $.getJSON( global_apiserver + "/codigos_postales/getMunicipio&Entidad/?cp="+cp+"&colonia="+colonia, function( response ) {
    //console.log(response);
    if (response != null) {
      $("#txtDelegacion").val(response.DELEGACION_MUNICIPIO);
      $("#txtEntidadFederativa").val(response.ENTIDAD_FEDERATIVA);
    }
    else{
      $("#txtDelegacion").val("");
      $("#txtEntidadFederativa").val(""); 
    }
  });
}

function get_delegacion_y_entidad(cp){
  $.getJSON( global_apiserver + "/codigos_postales/getMunicipio&Entidad/?cp="+cp+"&colonia=", function( response ) {
    //console.log(response);
    if (response != null) {
      $("#txtDelegacion").val(response.DELEGACION_MUNICIPIO);
      $("#txtEntidadFederativa").val(response.ENTIDAD_FEDERATIVA);
    }
    else{
      $("#txtDelegacion").val("");
      $("#txtEntidadFederativa").val(""); 
    }
  });
}

function clear_modal_insertar_actualizar_domicilio(){
  $("#txtNomDom").val("");
  $("#txtCalle").val("");
  $("#txtNoExt").val("");
  $("#txtNoInt").val("");
  $("#txtColonia").val("");
  $("#txtDelegacion").val("");
  $("#txtEntidadFederativa").val("");
  fill_autocomplete_pais("elige");
  $("#txtCP").val("");
  $("#cmbEsFiscal").val("no");
  $("#newColonia").hide();
  $("#nuevaColonia").val(""); 
  $( "#chkCol" ).prop("checked", false);
  $("#campoSelectColonia").show();
  $("#auxColonia").hide();
}

function clear_modal_insertar_actualizar_contacto(){
  $("#txtTipoContacto").val("");
  $("#txtNombreContacto").val("");
  $("#txtCargoContacto").val("");
  $("#txtTelMovil").val("");
  $("#txtTelFijo").val("");
  $("#txtExtension").val("");
  $("#txtEmail").val("");
  $("#cmbEsPrincipal").val("no");
  $("#datos_adicionales").val("");
}             

function listener_txt_nombre_domicilio(){
  $('#txtNomDom').keyup(function(){
    $(this).val($(this).val().toUpperCase());
  });
}

function colonia_checkbox(){
    $( "#chkCol" ).change(function() {
      console.log("unko")
      if($(this).prop("checked")){
        $("#autocompleteColonia").val(""); 
        $("#txtColonia").val(""); 
        $("#campoSelectColonia").hide();
        $("#auxColonia").show();
        $("#newColonia").show();
      }
      else{
        $("#campoSelectColonia").show();
        $("#auxColonia").hide();
        $("#nuevaActividad").val(""); 
        $("#newColonia").hide();
      }
    });
  }

function listener_btn_nuevo_domicilio(){
  $( "#btnNuevoDomicilio" ).click(function() {
    $("#btnGuardarDomicilio").attr("accion","insertar");
    $("#modalTituloDomicilio").html("Insertar nuevo domicilio");
    clear_modal_insertar_actualizar_domicilio();
    $("#modalInsertarActualizarDomicilio").modal("show");
  });
}

function listener_btn_guardar_domicilio(){
  $( "#btnGuardarDomicilio" ).click(function() {
    if ($("#btnGuardarDomicilio").attr("accion") == "insertar")
    {
      insertar_domicilio();
    }
    else if ($("#btnGuardarDomicilio").attr("accion") == "editar")
    {
      editar_domicilio($(this).attr("id_domicilio"));
    }
  });
}

function listener_btn_editar_domicilio(){
  $( ".btnEditarDomicilio" ).click(function() {
    $("#btnGuardarDomicilio").attr("accion","editar");
    $("#btnGuardarDomicilio").attr("id_domicilio",$(this).attr("id_domicilio"));
    $("#modalTituloDomicilio").html("Editar domicilio");
    fill_modal_insertar_actualizar_domicilio($(this).attr("id_domicilio"));
    $("#modalInsertarActualizarDomicilio").modal("show");
  });
}

function listener_txt_nombre_domicilio(){
  $('#txtNomDom').keyup(function(){
    $(this).val($(this).val().toUpperCase());
  });
}

function listener_txt_calle(){
  $('#txtCalle').keyup(function(){
    $(this).val($(this).val().toUpperCase());
  });
}

function draw_head_contactos(id_domicilio){
  var strHtml = "";
  //strHtml += '<strong>Contactos</strong>';
  if (global_permisos["CLIENTES"]["registrar"] == 1) {
    strHtml += '<button type="button" class="btn btn-primary btn-xs btn-imnc btnAñadirContacto" id_domicilio="'+id_domicilio+'" style="float: right;"> <i class="fa fa-plus"> </i> Agregar contacto </button>';
  }
  strHtml += '<br>';
  strHtml += '<div class="col-sm-12 invoice-col" id="Contactosde'+id_domicilio+'"';
  strHtml += '</div>';

  return strHtml;
}

function listener_btn_contactos(){

  $( ".btnContactos" ).click(function() {
    var id_cliente_domicilio = $(this).attr("id");
    var agrego = false;
    var num = 0;
     $.getJSON( global_apiserver + "/clientes_contactos/getAll/", function( response ) {
        $("#bodyContactos-"+id_cliente_domicilio).html("");
        $("#bodyContactos-"+id_cliente_domicilio).append(draw_head_contactos(id_cliente_domicilio));
        var objContacto = response;
        $("#Contactosde"+id_cliente_domicilio).html("");
        $.each(response, function( index, objContacto ) {
						  	console.log(id_cliente_domicilio);
							console.log(objContacto.ID_CLIENTE_DOMICILIO);
            if (objContacto.ID_CLIENTE_DOMICILIO == id_cliente_domicilio) {
							  	console.log(id_cliente_domicilio);
              agrego = true;
              num = num+1;
            }
          if (agrego) {

            $("#Contactosde"+id_cliente_domicilio).append(draw_row_contactos(num,objContacto));
            agrego = false;
          }
        });
        listener_btn_nuevo_contacto();
        listener_btn_editar_contacto();
        $("#bodyContactos-"+id_cliente_domicilio).collapse("toggle");
     });
  });
}

function listener_btn_nuevo_contacto(){
  $( ".btnAñadirContacto" ).click(function() {
    $("#btnGuardarContacto").attr("accion","insertar");
    $("#btnGuardarContacto").attr("id_domicilio",$(this).attr("id_domicilio"));
    $("#modalTituloDomiContacto").html("Insertar nuevo contacto");
    clear_modal_insertar_actualizar_contacto();
    $("#modalInsertarActualizarDomiContacto").modal("show");
  });
}

function listener_btn_editar_contacto(){
  $( ".btnEditarContacto" ).click(function() {
    $("#btnGuardarContacto").attr("accion","editar");
    $("#btnGuardarContacto").attr("id_domicilio",$(this).attr("id_domicilio"));
    $("#btnGuardarContacto").attr("id_contacto",$(this).attr("id_contacto"));
    $("#modalTituloDomiContacto").html("Editar contacto");
    fill_modal_insertar_actualizar_contacto($(this).attr("id_contacto"));
    $("#modalInsertarActualizarDomiContacto").modal("show");
  });
}

function listener_btn_guardar_contacto(){
  $( "#btnGuardarContacto" ).click(function() {
    if ($("#btnGuardarContacto").attr("accion") == "insertar")
    {
      insertar_contacto($(this).attr("id_domicilio"));
    }
    else if ($("#btnGuardarContacto").attr("accion") == "editar")
    {
      editar_contacto($(this).attr("id_contacto"),$(this).attr("id_domicilio"));
    }
  });
}

function draw_row_contactos(num, objContacto){
  var fec_ini = objContacto.FECHA_INICIO;
  fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
  var fec_fin = objContacto.FECHA_FIN;
  fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
  var ext = objContacto.EXTENSION;
  if (ext == "") {
    ext = "no hay";
  }
  var strHtml = "";
  strHtml += num + ".-";
  strHtml += ' Tipo: '+objContacto.ID_TIPO_CONTACTO+'<br><br>';
  strHtml += '<strong> '+objContacto.NOMBRE_CONTACTO+'</strong>';
  strHtml += '<br>'+objContacto.CARGO;
  strHtml += '<address>';
  strHtml += '<br>Teléfono fijo: '+objContacto.TELEFONO_FIJO;
  if (objContacto.EXTENSION != '') {
    strHtml += ' ext: '+objContacto.EXTENSION+'<br>';  
  }
  strHtml += 'Teléfono móvil: '+objContacto.TELEFONO_MOVIL;
  strHtml += '<br>Email: '+objContacto.EMAIL+'<br> ';
  if (objContacto.ES_PRINCIPAL == 'si') {
    strHtml += '<strong style="background-color: #96daa1; color: #966610;">' + objContacto.ES_PRINCIPAL + ' es contacto principal</strong>';
  }
  else
  {
    strHtml += objContacto.ES_PRINCIPAL + ' es contacto principal';  
  }
  strHtml += '</address>';
  if (global_permisos["CLIENTES"]["editar"] == 1) {
    strHtml += '  <br>  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarContacto" id_domicilio="'+objContacto.ID_CLIENTE_DOMICILIO+'" id_contacto="'+objContacto.ID+'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Editar contacto</button>';
  }
  strHtml += '</div>';
  strHtml += '<br><hr>';
  return strHtml;
}
  
function draw_row_domicilios(num, objDom) {
  var num_int = objDom.NUMERO_INTERIOR;
  if (num_int == "") {
    num_int = "no hay";
  }
  var strHtml = "";
  strHtml += "<div class='row' style='margin-bottom: 20px; border-bottom: 1px solid #EDEDED;'><br>";
  strHtml += num + ".- ";
  strHtml += '<strong> '+objDom.NOMBRE_DOMICILIO+'</strong>';
  strHtml += '<address>'
  strHtml += ' Calle: ' + objDom.CALLE;
  strHtml += ' - No. exterior: '+objDom.NUMERO_EXTERIOR;
  strHtml += ' - No. interior: '+num_int;
  strHtml += ' - Colonia: '+objDom.COLONIA_BARRIO+'<br> ';
  strHtml += ' Municipio o delegación: ' + objDom.DELEGACION_MUNICIPIO;
  strHtml += ' - Entidad federativa: ' + objDom.ENTIDAD_FEDERATIVA +'<br> ';
  strHtml += ' C.P. '+objDom.CP;
  strHtml += ' - País: ' + objDom.PAIS+'<br> ';
  if (objDom.ES_FISCAL == 'si') {
    strHtml += '<strong style="background-color: #96daa1; color: #966610;">' + objDom.ES_FISCAL + ' es domicilio fiscal</strong>';
  }
  else
  {
    strHtml += objDom.ES_FISCAL + ' es domicilio fiscal';  
  }
  strHtml += '</address>';
  strHtml += '  <br>  <button type="button" class="btn btn-primary btn-xs btn-imnc btnContactos" id="'+objDom.ID+'"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Ver contactos </button>';
  if (global_permisos["CLIENTES"]["editar"] == 1) {
    strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarDomicilio" id_domicilio="'+objDom.ID+'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar domicilio </button>';
  }
  //strHtml += '<div class="collapse" id="collapse-'+objDom.ID+'">';
  strHtml += '<div class="col-sm-12 invoice-col accordion-contactos collapse" id="bodyContactos-'+objDom.ID+'">';
  strHtml += '</div>';
  //strHtml += '</div>';
  strHtml += '<br>';
  strHtml += "</div>";
  return strHtml;
}

function draw_domicilios(){
  $.getJSON( global_apiserver + "/clientes/getById/?id="+global_id_cliente+"&completo=true", function( objClienteDomicilios ) {
    var domicilios = objClienteDomicilios.DOMICILIOS;
    $("#bodyDomicilios").html("");
    $.each(domicilios, function( index, objDomicilio ) {
      $("#bodyDomicilios").append(draw_row_domicilios(index + 1, objDomicilio));
    });

    listener_btn_editar_domicilio();
    listener_btn_contactos();
  });
}

function draw_perfil(){
 $.getJSON( global_apiserver + "/clientes/getById/?id="+global_id_cliente, function( objCliente ) {
    var ruta_imagen = objCliente.RUTA_IMAGEN;
    if (objCliente.IMAGEN_BASE64 === null){
      imagenSrc = './pictures/user.png';
    }
    else
    {
      imagenSrc = objCliente.IMAGEN_BASE64;
    }
    $("#imgCliente").attr("src",imagenSrc);
    $("#lbNombre").html(objCliente.NOMBRE);
	if(objCliente.RFC == null){
		$("#lbRfc").html("RFC: " + objCliente.RFC_FACTURARIO);
	}else{
		$("#lbRfc").html("RFC: " + objCliente.RFC);
	}
    
    $("#lbEsFac").html("Es facturario: " + objCliente.ES_FACTURARIO);
    $("#lbTieneFac").html("Tiene facturario: " + objCliente.TIENE_FACTURARIO);
    //En cuanto estén estas conexiones debe descomentarse este código
    //$("#lbIdClienteFac").html("Clave de cliente facturario: " + objCliente.ID_CLIENTE_FACTURARIO);
    //$("#lbIdTipoPersona").html("Clave de tipo de persona: " + objCliente.ID_TIPO_PERSONA);
    //$("#lbIdTipoEntidad").html("Clave de tipo de entidad: " + objCliente.ID_TIPO_ENTIDAD);
  });
}

function insertar_domicilio(){
	if($("#chkCol").prop("checked")){
		$("#txtColonia").val($("#nuevaColonia").val());
	}
  var cliente_domicilio = {
    ID_CLIENTE:parseInt(global_id_cliente),
    NOMBRE_DOMICILIO:$("#txtNomDom").val(),
    CALLE:$("#txtCalle").val(),
    NUMERO_EXTERIOR:$("#txtNoExt").val(),
    NUMERO_INTERIOR:$("#txtNoInt").val(),
    COLONIA_BARRIO:$("#txtColonia").val(),
    DELEGACION_MUNICIPIO:$("#txtDelegacion").val(),
    ENTIDAD_FEDERATIVA:$("#txtEntidadFederativa").val(),
    CP:$("#txtCP").val(),
    PAIS:$("#autocompletePais").val(),
    ES_FISCAL:$("#cmbEsFiscal").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
  $.post(global_apiserver + "/clientes_domicilios/insert/", JSON.stringify(cliente_domicilio), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizarDomicilio").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo domicilio", "success");
        draw_domicilios(); 
      }
      else
      {
         notify("Error", respuesta.mensaje, "error");
      }
  });
}

function editar_domicilio(id){
	if($("#chkCol").prop("checked")){
		$("#txtColonia").val($("#nuevaColonia").val());
	}
  var cliente_domicilio = {
    ID:id,
    ID_CLIENTE:parseInt(global_id_cliente),
    NOMBRE_DOMICILIO:$("#txtNomDom").val(),
    CALLE:$("#txtCalle").val(),
    NUMERO_EXTERIOR:$("#txtNoExt").val(),
    NUMERO_INTERIOR:$("#txtNoInt").val(),
    COLONIA_BARRIO:$("#txtColonia").val(),
    DELEGACION_MUNICIPIO:$("#txtDelegacion").val(),
    ENTIDAD_FEDERATIVA:$("#txtEntidadFederativa").val(),
    CP:$("#txtCP").val(),
    PAIS:$("#autocompletePais").val(),
    ES_FISCAL:$("#cmbEsFiscal").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
    $.post(global_apiserver + "/clientes_domicilios/update/", JSON.stringify(cliente_domicilio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarDomicilio").modal("hide");
          notify("Éxito", "Se ha actualizado un domicilio", "success");
          draw_domicilios();
        }
        else
      {
         notify("Error", respuesta.mensaje, "error");
      }
    });
}

function insertar_contacto(id_domicilio){
  var fec_ini = "01/01/2000";
  fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
  var fec_fin = "31/01/2030";
  fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
  var cliente_domicilio_contacto = {
    ID_CLIENTE_DOMICILIO:parseInt(id_domicilio),
    ID_TIPO_CONTACTO:$("#txtTipoContacto").val(),
    NOMBRE_CONTACTO:$("#txtNombreContacto").val(),
    CARGO:$("#txtCargoContacto").val(),
    TELEFONO_MOVIL:$("#txtTelMovil").val(),
    TELEFONO_FIJO:$("#txtTelFijo").val(),
    EXTENSION:$("#txtExtension").val(),
    EMAIL:$("#txtEmail").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    ES_PRINCIPAL:$("#cmbEsPrincipal").val(),
    DATOS_ADICIONALES : $("#datos_adicionales").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
  console.log(cliente_domicilio_contacto);
  $.post(global_apiserver + "/clientes_contactos/insert/", JSON.stringify(cliente_domicilio_contacto), function(respuesta){
      respuesta = JSON.parse(respuesta);
      if (respuesta.resultado == "ok") {
        $("#modalInsertarActualizarDomiContacto").modal("hide");
        notify("Éxito", "Se ha insertado un nuevo contacto de este domicilio", "success");
        draw_domicilios(); 
      }
      else
      {
         notify("Error", respuesta.mensaje, "error");
      }
  });
}

function editar_contacto(id,id_domicilio){
	var fec_ini = "01/01/2000";
  fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
  var fec_fin = "01/01/2025";
  fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
  var cliente_domicilio_contacto = {
    ID:parseInt(id),
    ID_CLIENTE_DOMICILIO:parseInt(id_domicilio),
    ID_TIPO_CONTACTO:$("#txtTipoContacto").val(),
    NOMBRE_CONTACTO:$("#txtNombreContacto").val(),
    CARGO:$("#txtCargoContacto").val(),
    TELEFONO_MOVIL:$("#txtTelMovil").val(),
    TELEFONO_FIJO:$("#txtTelFijo").val(),
    EXTENSION:$("#txtExtension").val(),
    EMAIL:$("#txtEmail").val(),
    FECHA_INICIO:fec_ini,
    FECHA_FIN:fec_fin,
    ES_PRINCIPAL:$("#cmbEsPrincipal").val(),
    DATOS_ADICIONALES : $("#datos_adicionales").val(),
    ID_USUARIO:sessionStorage.getItem("id_usuario")
  };
    $.post(global_apiserver + "/clientes_contactos/update/", JSON.stringify(cliente_domicilio_contacto), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarDomiContacto").modal("hide");
          notify("Éxito", "Se ha actualizado un contacto", "success");
          draw_domicilios();
        }
        else
        {
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

