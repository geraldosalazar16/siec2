
/*
	Creación del controlador con el nombre "auditor_perfil_controller".
*/

app.controller('auditor_perfil_controller',['$scope','$http',function($scope,$http){
//$scope.optionsList=[];
$scope.flag = "NORMAL";



/*
	Funcion para traer las normas que ya estan asociadas a ese servicio
*/
$scope.funcioncalificacionesnormas = function(id_calificacion){
	 $.ajax({
		type:'GET',
		url:global_apiserver+"/normas_calificaciones/getNormabyIdCalificacion/?id="+id_calificacion,
		success:function(data){
		data=JSON.parse(data);

			$.each(data, function( index, data1 ) {
				$scope.$apply(function(){
				//$scope.selectedList.push(angular.fromJson(data));
				//data1['ID']=data1['ID_NORMA'];
				$scope.selectedList.push(data1);
				 
				 })
			})

		}
	});
}
    /*
     Funcion para traer los tipos de servicios de este servicio
     */
    $scope.funcionparalistatiposervicio = function(id_servicio){
        $.ajax({
            type:'GET',
            url:global_apiserver+"/normas_tiposervicio/getNormabyIdTipoServicio/?id="+id_tipo_servicio,
            success:function(data){
                //	$scope.$apply(function(){
                //		$scope.optionsList=angular.fromJson(data);
                //
                //	})
                data=JSON.parse(data);
                $.each(data, function( index, data1 ) {
                    $scope.$apply(function(){
                        //data1['ID']=data1['ID_NORMA'];
                        $scope.optionsList.push(data1);
                    })
                })

            }
        });

    }
/*
	Funcion para traer las normas de este servicio
*/
$scope.funcionparalistanormas = function(id_tipo_servicio){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/normas_tiposervicio/getNormabyIdTipoServicio/?id="+id_tipo_servicio,
		success:function(data){
		//	$scope.$apply(function(){
		//		$scope.optionsList=angular.fromJson(data);
		//		
		//	})
		data=JSON.parse(data);
			$.each(data, function( index, data1 ) {
				$scope.$apply(function(){
				//data1['ID']=data1['ID_NORMA'];
				$scope.optionsList.push(data1);
				 })
			})
			
		}
	});
	
}
/*
	Funcion para crear los registros de forma automatica
*/
$scope.funcionGenerarRegistros = function(id_rol,id_tipo_servicio,id_pt){
	
	if(!id_tipo_servicio)
	{
		id_tipo_servicio = "XXX";
	}
	if(!id_rol)
	{
		id_rol = "XX";
	}
	$http.get(  global_apiserver + "/personal_tecnico_calificaciones/generarReferencia/?id_rol="+id_rol+"&id="+id_tipo_servicio+"&id_pt="+id_pt)
		.then(function( response ){
	//$.getJSON(  global_apiserver + "/personal_tecnico_calificaciones/generarReferencia/?id_rol="+id_rol+"&id="+id_tipo_servicio+"&id_pt="+id_pt, function( response ) {		
			//$scope.formData.txtReferencia = response.data;
			$("#txtRegistro").val(response.data);
			//$scope.formData.txtReferencia	= response.data;
			
		});
	
}
// ================================================================================
// *****                        Al cargar la página                           *****
// ================================================================================

  $( window ).load(function() {
  var mod = true;
  onCalendar();
  draw_calendario();

  draw_perfil();
  draw_domicilios_y_califs(); 
  listener_btn_nuevo_domicilio();
  listener_btn_guardar_domicilio();
  listener_btn_nuevo_calif();
  put_fechas(mod);
  listener_btn_guardar_calif();
  listener_btn_guardar_calif_actualizar();
  listener_btn_guardar_calif_sector();
  listener_btn_guardar_calif_curso();
  listener_tabs_change();
  listener_chk_sector_nace_na();
  listener_txt_nombre_domicilio();
  listener_txt_calle();
  listener_autocomplete_pais_change();
  listener_autocomplete_cp_change();
  listener_autocomplete_colonia_change();
  onDatepickerClasif();
  if (tab_seleccionada == "domicilios") {
    $('#myTab li a[href="#tab_domicilios"]').tab("show");
  }
  if (tab_seleccionada == "calificaciones") {
    $('#myTab li a[href="#tab_califs"]').tab("show");
  }
  if (tab_seleccionada == "agenda") {
    $('#myTab li a[href="#tab_agenda"]').tab("show");
  }
  
});


// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================

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

  function listener_tabs_change(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      if (target == "#tab_agenda") {
        $('#calendar').fullCalendar('render');
      }
    });
  }

  function draw_domicilios_y_califs(){
    $(".loading").show();
    jQuery('html, body').animate({scrollTop : 0},500);
    $.getJSON( global_apiserver + "/personal_tecnico/getById/?id="+global_id_personal_tecnico+"&completo=true", function( objPTecnico ) {
        var domicilios = objPTecnico.DOMICILIOS;
        $("#bodyDomicilios").html("");
        $.each(domicilios, function( index, objDomicilio ) {
          $("#bodyDomicilios").append(draw_row_domicilio(index + 1, objDomicilio));
        });

        var califs = objPTecnico.CALIFICACIONES;
        $("#tbodyCalifs").html("");
        $.each(califs, function( index, objCalif ) {
          $("#tbodyCalifs").append(draw_row_calif(index + 1, objCalif));
        });
        listener_btn_editar_domicilio();
        listener_btn_editar_calif();
        listener_btn_sectores();
        listener_btn_cursos();
        listener_btn_nuevo_calif_sector();
        listener_btn_nuevo_calif_curso();
		//listener_btn_actualiza_calif();
        $(".loading").hide();
     });
  }

  function draw_perfil(){
   $.getJSON( global_apiserver + "/personal_tecnico/getById/?id="+global_id_personal_tecnico, function( objPTecnico ) {
      var ruta_imagen = objPTecnico.RUTA_IMAGEN;
      var fec_nac = objPTecnico.FECHA_NACIMIENTO;
      fec_nac = fec_nac.substring(6,8)+"/"+fec_nac.substring(4,6)+"/"+fec_nac.substring(0,4);
      if (objPTecnico.IMAGEN_BASE64 === null){
        imagenSrc = './pictures/user.png';
      }
      else
      {
        imagenSrc = objPTecnico.IMAGEN_BASE64;
      }
      $("#imgAuditor").attr("src",imagenSrc);
      $("#lbNombre").html(objPTecnico.NOMBRE + " " + objPTecnico.APELLIDO_PATERNO + " " + objPTecnico.APELLIDO_MATERNO);
      $("#lbRol").html(objPTecnico.ID_ROL);
      $("#lbFecNac").html("Fecha de nacimiento: " + fec_nac);
      $("#lbCurp").html(str_curp + ": " + objPTecnico.CURP);
      $("#lbRfc").html(str_rfc + ": " + objPTecnico.RFC);
      $("#lbTelFijo").html("Teléfono Fijo: " + objPTecnico.TELEFONO_FIJO);
      $("#lbTelCelular").html("Teléfono Celular: " + objPTecnico.TELEFONO_CELULAR);
      $("#lbEmail").html("Email: " + objPTecnico.EMAIL);
      $("#lbStatus").html("STATUS: " + objPTecnico.STATUS);
     });
  }


// ================================================================================
// *****                       Domicilios                                     *****
// ================================================================================

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
        if (seleccionado == objColonia.COLONIA_BARRIO) {
          $("#autocompleteColonia").append('<option value="'+objColonia.COLONIA_BARRIO+'" selected>'+objColonia.COLONIA_BARRIO+'</option>'); 
        }else{
          $("#autocompleteColonia").append('<option value="'+objColonia.COLONIA_BARRIO+'">'+objColonia.COLONIA_BARRIO+'</option>'); 
        }
        
      });
      $("#autocompleteColonia").val(seleccionado);
      $('#autocompleteColonia').select2();
      $("#autocompleteColonia" ).change();
    });
  }

  function fill_modal_insertar_actualizar_domicilio(id_personal_tecnico_domicilio){
    $.getJSON( global_apiserver + "/personal_tecnico_domicilios/getById/?id="+id_personal_tecnico_domicilio, function( response ) {
          var fec_ini = response.FECHA_INICIO;
          fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
          var fec_fin = response.FECHA_FIN;
          fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
          $("#txtNomDom").val(response.NOMBRE_DOMICILIO);
          $("#txtCalle").val(response.CALLE);
          $("#txtNoExt").val(response.NUMERO_EXTERIOR);
          $("#txtNoInt").val(response.NUMERO_INTERIOR);
          $("#txtDelegacion").val(response.DELEGACION_MUNICIPIO);
          $("#txtEntidadFederativa").val(response.ENTIDAD_FEDERATIVA);
          $("#txtCP").val(response.CP);
          fill_autocomplete_pais(response.PAIS);
          $("#txtColonia").val(response.COLONIA_BARRIO);
		  $("#nuevaColonia").val(response.COLONIA_BARRIO);
          fill_autocomplete_colonia(response.COLONIA_BARRIO, response.CP);
          $("#txtFecIni").val(fec_ini);
          $("#txtFecFin").val(fec_fin);
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

  function clear_modal_insertar_actualizar_domicilio(){
    $("#txtNomDom").val("");
    $("#txtCalle").val("");
    $("#txtNoExt").val("");
    $("#txtNoInt").val("");
    $("#txtColonia").val("");
    $("#txtDelegacion").val("");
    $("#txtEntidadFederativa").val("");
    $("#txtCP").val("");
    fill_autocomplete_pais("elige");
    $("#txtFecIni").val("");
    $("#txtFecFin").val("");
	$("#nuevaColonia").val("");
  }

  function listener_autocomplete_pais_change(){
    $( "#autocompletePais" ).off('change').change(function() {
      
      if($(this).val() == "MEXICO (ESTADOS UNIDOS MEXICANOS)"){
        
        $("#txtCP").hide();
        $("#txtColonia").hide();
        $("#autocompleteCP").show();
        $("#autocompleteColonia").show();  
        fill_autocomplete_cp($("#txtCP").val());
        fill_autocomplete_colonia($("#txtColonia").val(), $("#txtCP").val());
        $("#txtEntidadFederativa").prop("readonly", true);
        $("#txtDelegacion").prop("readonly", true);
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
      }
      
    });
  }

  function listener_autocomplete_cp_change(){
    $( "#autocompleteCP").off('change').change(function() {
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
        editar_domicilio();
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

  function draw_row_domicilio(num, objDom) {
    var fec_ini = objDom.FECHA_INICIO;
    fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
    var fec_fin = objDom.FECHA_FIN;
    fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
    var num_int = objDom.NUMERO_INTERIOR;
    if (num_int == "") {
      num_int = "no hay";
    }
    var strHtml = "";
    strHtml += num +'.- <strong>'+objDom.NOMBRE_DOMICILIO+'</strong>';
    strHtml += '<address>'
    strHtml += ' Calle: <span class="domicilio">'+objDom.CALLE + '</span>, ';
    strHtml += ' Num. exterior: <span class="domicilio">'+objDom.NUMERO_EXTERIOR+'</span>, ';
    strHtml += 'Num. interior : <span class="domicilio">'+num_int+'</span>, ';
    strHtml += 'Colonia/Barrio: <span class="domicilio">'+objDom.COLONIA_BARRIO+'</span>, ';
    strHtml += 'Delegación/Municipio: <span class="domicilio">' + objDom.DELEGACION_MUNICIPIO + '</span>, ';
    strHtml += 'Entidad federativa: <span class="domicilio">' + objDom.ENTIDAD_FEDERATIVA+'</span>, ';
    strHtml += 'C.P. <span class="domicilio">'+objDom.CP+'</span>, ';
    strHtml += 'País: <span class="domicilio">' + objDom.PAIS+'</span>, ';
    strHtml += 'de <span class="domicilio">'+fec_ini+'</span> ';
    strHtml += 'a <span class="domicilio">'+fec_fin+'</span>';
    if ( global_permisos["AUDITORES"]["editar"] == 1) {
      strHtml += '  <br>  <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarDomicilio" id_domicilio="'+objDom.ID+'" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar domicilio </button><br>';
    }
    
    return strHtml;
  }

  function insertar_domicilio(){
    var fech_ini = $("#txtFecIni").val();
    fech_ini = fech_ini.substring(6,10)+fech_ini.substring(3,5)+fech_ini.substring(0,2);
    var fec_fin = $("#txtFecFin").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
$("#txtColonia").val($("#nuevaColonia").val());
    var personal_tecnico_domicilio = {
      ID_PERSONAL_TECNICO:parseInt(global_id_personal_tecnico),
      NOMBRE_DOMICILIO:$("#txtNomDom").val(),
      CALLE:$("#txtCalle").val(),
      NUMERO_EXTERIOR:$("#txtNoExt").val(),
      NUMERO_INTERIOR:$("#txtNoInt").val(),
      COLONIA_BARRIO:$("#txtColonia").val(),
      DELEGACION_MUNICIPIO:$("#txtDelegacion").val(),
      ENTIDAD_FEDERATIVA:$("#txtEntidadFederativa").val(),
      CP:$("#txtCP").val(),
      PAIS:$("#autocompletePais").val(),
      FECHA_INICIO:fech_ini,
      FECHA_FIN:fec_fin,
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico_domicilios/insert/", JSON.stringify(personal_tecnico_domicilio), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarDomicilio").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo domicilio", "success");
          draw_domicilios_y_califs(); 
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_domicilio(){
    var fech_ini = $("#txtFecIni").val();
    fech_ini = fech_ini.substring(6,10)+fech_ini.substring(3,5)+fech_ini.substring(0,2);
    var fec_fin = $("#txtFecFin").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
	$("#txtColonia").val($("#nuevaColonia").val());
      var personal_tecnico_domicilio = {
        ID:$("#btnGuardarDomicilio").attr("id_domicilio"),
        NOMBRE_DOMICILIO:$("#txtNomDom").val(),
        CALLE:$("#txtCalle").val(),
        NUMERO_EXTERIOR:$("#txtNoExt").val(),
        NUMERO_INTERIOR:$("#txtNoInt").val(),
        COLONIA_BARRIO:$("#txtColonia").val(),
        DELEGACION_MUNICIPIO:$("#txtDelegacion").val(),
        ENTIDAD_FEDERATIVA:$("#txtEntidadFederativa").val(),
        CP:$("#txtCP").val(),
        PAIS:$("#autocompletePais").val(),
        FECHA_INICIO:fech_ini,
        FECHA_FIN:fec_fin,
        ID_USUARIO:sessionStorage.getItem("id_usuario")
      };
      $.post(global_apiserver + "/personal_tecnico_domicilios/update/", JSON.stringify(personal_tecnico_domicilio), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
            $("#modalInsertarActualizarDomicilio").modal("hide");
            notify("Éxito", "Se ha actualizado un domicilio", "success");
            draw_domicilios_y_califs();
          }
          else
          {
             notify("Error", respuesta.mensaje, "error");
          }
      });
  }

// ================================================================================
// *****                       Calificaciones                                 *****
// ================================================================================

 function fill_cmb_norma(seleccionado,id_tipo_servicio){
    $.getJSON( global_apiserver + "/normas_tiposervicio/getNormabyIdTipoServicio/?id="+id_tipo_servicio, function( response ) {
      $("#cmbNorma").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        $("#cmbNorma").append('<option value="'+objTserv.ID_NORMA+'">'+objTserv.ID_NORMA+'</option>'); 
      });
      $("#cmbNorma").val(seleccionado);
    });
  }
 
 function fill_cmb_norma_actualizacion(seleccionado,id_tipo_servicio){
    $.getJSON( global_apiserver + "/normas_tiposervicio/getNormabyIdTipoServicio/?id="+id_tipo_servicio, function( response ) {
      $("#cmbNormaActualizacion").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        $("#cmbNormaActualizacion").append('<option value="'+objTserv.ID_NORMA+'">'+objTserv.ID_NORMA+'</option>'); 
      });
	  $("#cmbNormaActualizacion").val(seleccionado);
    });
  }

    function fill_cmb_servicio(seleccionado){
        $.getJSON( global_apiserver + "/servicios/getAll/", function( response ) {
            $("#cmbServicio").html('<option value="elige" selected disabled>-elige una opción-</option>');
            $.each(response, function( indice, objTserv ) {
                $("#cmbServicio").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>');
            });
            $("#cmbServicio").val(seleccionado);

        });
    }

  function fill_cmb_tipo_servicio(seleccionado,id_servicio){
    $.getJSON( global_apiserver + "/tipos_servicio/getByService/?id="+id_servicio, function( response ) {
      $("#cmbTipoServicio").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        $("#cmbTipoServicio").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
      });
      $("#cmbTipoServicio").val(seleccionado);
    });
  }
  
  function fill_cmb_tipo_servicio_actualizacion(){
    $.getJSON( global_apiserver + "/tipos_servicio/getAll/", function( response ) {
      $("#cmbTipoServicioActualizacion").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTserv ) {
        $("#cmbTipoServicioActualizacion").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
      });
    });
  }

  function fill_cmb_rol(seleccionado){
    $.getJSON( global_apiserver + "/personal_tecnico_roles/getAll/", function( response ) {
      $("#cmbRol").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objRol ) {
        $("#cmbRol").append('<option value="'+objRol.ID+'">'+objRol.ROL+'</option>'); 
      });
      $("#cmbRol").val(seleccionado);
    });
  }

    $("#cmbServicio").change(function(){
        $("#cmbTipoServicio").prop("disabled", false);
        //fill_cmb_norma("elige",$("#cmbTipoServicio").val());
        if(parseInt($("#cmbServicio").val())==3)
        {
            $("#txtTipoServicio").text("Módulo *");
            $("#divNormal").hide();
            $scope.flag = "CIFA";
        }
        else{
            $("#txtTipoServicio").text( "Tipo de servicio *");
            $("#divNormal").show();
            $scope.flag = "NORMAL"
            $scope.$apply(function(){
                $scope.optionsList = [];
                $scope.selectedList= [];

            })
        }


        fill_cmb_tipo_servicio("elige",$("#cmbServicio").val());
    });

    $("#cmbTipoServicio").change(function(){
        $("#cmbNorma").prop("disabled", false);
        $scope.$apply(function(){
            $scope.optionsList = [];
            $scope.selectedList= [];

        })
        //fill_cmb_norma("elige",$("#cmbTipoServicio").val());
        $scope.funcionparalistanormas($("#cmbTipoServicio").val());
    });

 // llamada click btn editar x funcion listener_btn_editar_calif()
  function fill_modal_insertar_actualizar_calif(id_personal_tecnico_calif){
    $.getJSON( global_apiserver + "/personal_tecnico_calificaciones/getById/?id="+id_personal_tecnico_calif, function( response ) {
        var fec_ini = response.FECHA_INICIO;
          fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
          var fec_fin = response.FECHA_FIN;
          fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
          $("#txtRegistro").val(response.REGISTRO);
          $("#txtFecIniCalif").val(fec_ini);
          $("#txtFecFinCalif").val(fec_fin);
          fill_cmb_servicio(response.ID_SERVICIO);

          fill_cmb_tipo_servicio(response.ID_TIPO_SERVICIO,response.ID_SERVICIO);
		  //fill_cmb_norma(response.ID_NORMA,response.ID_TIPO_SERVICIO);
		  $scope.funcionparalistanormas(response.ID_TIPO_SERVICIO);
		  $scope.funcioncalificacionesnormas(id_personal_tecnico_calif);
          fill_cmb_rol(response.ID_ROL);
		   $("#cmbTipoServicio").attr("disabled","true");
		   if(parseInt(response.ID_SERVICIO)==3)
           {
               $("#txtTipoServicio").text("Módulo *");
               $("#divNormal").hide();
               $scope.flag = "CIFA";
           }
           else{
               $("#txtTipoServicio").text( "Tipo de servicio *");
               $("#divNormal").show();
               $scope.flag = "NORMAL"
           }
       });
  }

  function put_fechas(mod){
    if(mod){
      //$("#txtFecIniCalif").attr('readonly', true);
      //$("#txtFecFinCalif").attr('readonly', true);
      put_select_for_serv_rol();
    }
  }

  function get_fecha_inicio(){
  	var fec_ini = $("#txtFecIniCalif").val().split('/');
  	if(fec_ini.length != 3)
  		return NaN;
  	fec_ini = fec_ini[1]+"/"+fec_ini[0]+"/"+fec_ini[2];
    var hoy = new Date(fec_ini);
    return hoy;
  }

  function put_select_for_serv_rol(){
  	function get_fecha_final(rol, servicio, fec_ini){
  		$.getJSON( global_apiserver + "/servicio_rol/getTime/?rol="+ rol + "&tipo_servicio=" + servicio, function( response ) {
  			if(Boolean(response)){
		    	var tiempo = parseInt(response);
    			var hoy = fec_ini;
		    	hoy.setFullYear(hoy.getFullYear() + tiempo);
		    	var dd = hoy.getDate();
			    var mm = hoy.getMonth()+1; //hoy es 0!
			    var yyyy = hoy.getFullYear();

			    if(dd<10) {
			        dd='0'+dd
			    } 

			    if(mm<10) {
			        mm='0'+mm
			    } 

			    hoy = dd+'/'+mm+'/'+yyyy;
    			$("#txtFecFinCalif").val(hoy);
    			$("#txtFecFinCalif").attr('readonly', true);
  			}
  			else{
  				$("#txtFecFinCalif").attr('readonly', false);
  			}
	  	});
  	}
  	$("#cmbTipoServicio").change(function(){
  		var fecha_inicio = get_fecha_inicio();
  		if( Boolean($("#cmbRol").val()) && !isNaN(fecha_inicio)){
  			var rol = $("#cmbRol").val();
  			var servicio = $(this).val();
  			get_fecha_final(rol, servicio, fecha_inicio);
  		}
  		else{
  			$("#txtFecFinCalif").attr('readonly', false);
  		}
		$scope.funcionGenerarRegistros($("#cmbRol").val(),$("#cmbTipoServicio").val(),global_id_personal_tecnico);
  	});
  	$("#cmbRol").change(function(){
  		var fecha_inicio = get_fecha_inicio();
  		if( Boolean($("#cmbTipoServicio").val()) && !isNaN(fecha_inicio) ){
  			var servicio = $("#cmbTipoServicio").val();
  			var rol = $(this).val();
  			get_fecha_final(rol, servicio, fecha_inicio);
  		}
  		else{
  			$("#txtFecFinCalif").attr('readonly', false);
  		}
		$scope.funcionGenerarRegistros($("#cmbRol").val(),$("#cmbTipoServicio").val(),global_id_personal_tecnico);
  	});
  	$("#txtFecIniCalif").change(function(){
  		var fecha_inicio = get_fecha_inicio();
  		if( Boolean($("#cmbRol").val()) && Boolean($("#cmbTipoServicio").val()) && !isNaN(fecha_inicio)){
  			var rol = $("#cmbRol").val();
  			var servicio = $("#cmbTipoServicio").val();
  			get_fecha_final(rol, servicio, fecha_inicio);
  		}
  		else{
  			$("#txtFecFinCalif").attr('readonly', false);
  		}
  	});
  }

function clear_modal_insertar_actualizar_calif(){
    $scope.$apply(function(){
        $scope.optionsList = [];
        $scope.selectedList= [];

    })

	$("#txtRegistro").val("");
	$("#txtFecIniCalif").val("");
	$("#txtFecFinCalif").val("");
    fill_cmb_servicio("elige");
    $("#cmbNorma").prop("disabled", true);
    $("#cmbNorma").show();
    $("#cmbTipoServicio").val("elige");
	fill_cmb_rol("elige");
}

  function listener_btn_nuevo_calif(){
    $( "#btnNuevoCalif" ).click(function() {
      $("#btnGuardarCalif").attr("accion","insertar");
      $("#modalTituloCalif").html("Insertar nueva calificación");
	  $("#cmbNorma").prop("disabled", true);
	  $("#cmbTipoServicio").prop("disabled",true);
      clear_modal_insertar_actualizar_calif();
      $("#modalInsertarActualizarCalif").modal("show");
    });
  }

  
  function listener_btn_guardar_calif(){
    $( "#btnGuardarCalif" ).click(function() {
      if ($("#btnGuardarCalif").attr("accion") == "insertar")
      {
        insertar_calif();
      }
      else if ($("#btnGuardarCalif").attr("accion") == "editar")
      {
        editar_calif();
      }
    });
  }
  
  function insertar_calif_anterior(){
    $.post(global_apiserver + "/personal_tecnico_calificaciones/updateCalificacionSectores/?id_calificacion="+$("#id_calificacion_input").val()+"&id_tipo_servicio="+$("#cmbTipoServicioActualizacion").val()+"&id_norma="+$("#cmbNormaActualizacion").val()+"&id_usuario="+sessionStorage.getItem("id_usuario"), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarCalifSectorAnterior").modal("hide");
          notify("Éxito", "Se ha insertado una nueva calificación", "success");
          draw_domicilios_y_califs(); 
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }
  
  function listener_btn_guardar_calif_actualizar(){
    $( "#btnActualizaCalif" ).click(function() {

        insertar_calif_anterior();

      
    });
  }

   function listener_btn_actualiza_calif(){

	$(".btnActualizarAnterior").click(function() {
       $("#id_calificacion_input").val($(this).attr("id_calificacion"));
	   $("#cmbNormaActualizacion").prop("disabled", true);
		//fill_cmb_norma_actualizacion();
		fill_cmb_tipo_servicio_actualizacion();
      $("#modalInsertarActualizarCalifSectorAnterior").modal("show");
    });
  }

	$("#cmbTipoServicioActualizacion").change(function(){
		 $("#cmbNormaActualizacion").prop("disabled", false);
		 fill_cmb_norma_actualizacion("elige",$("#cmbTipoServicioActualizacion").val());
	});
	
  function listener_btn_editar_calif(){
    $( ".btnEditarCalif" ).click(function() {
		clear_modal_insertar_actualizar_calif();
      $("#btnGuardarCalif").attr("accion","editar");
      $("#btnGuardarCalif").attr("id_calif",$(this).attr("id_calif"));
      $("#modalTituloCalif").html("Editar calificación");
      fill_modal_insertar_actualizar_calif($(this).attr("id_calif"));
      $("#modalInsertarActualizarCalif").modal("show");
    });
  }

  function draw_row_calif(num, objCalif) {
    var fec_ini = objCalif.FECHA_INICIO;
    fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
    var fec_fin = objCalif.FECHA_FIN;
    fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
    var strHtml = "";
    strHtml += '<tr>';
    strHtml += '  <td>'+num+'. </td>';
    strHtml += '  <td>'+objCalif.ACRONIMO_ROL+'</td>';
    strHtml += '  <td>'+objCalif.ACRONIMO+': <br>' + objCalif.NOMBRE_TIPO_SERVICIO + '</td>';
	strHtml += '  <td>'+objCalif.NORMA_ID+'</td>';
    strHtml += '  <td>'+objCalif.REGISTRO+'</td>';
    strHtml += '  <td> de: '+fec_ini+' <br> a: '+fec_fin+'</td>';
    strHtml += '  <td>';
	if (objCalif.ID_SERVICIO == 1) {
		strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnSectores" id_tipo_servicio="'+objCalif.ID_TIPO_SERVICIO+'" id="'+objCalif.ID+'" style="float: right;"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Ver sectores </button>';
	}
    if (objCalif.ID_SERVICIO == 3) {
          strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnCursos" id_tipo_servicio="'+objCalif.ID_TIPO_SERVICIO+'" id="'+objCalif.ID+'" style="float: right;"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Ver cursos </button>';
    }

    strHtml += '  </td>';
	strHtml += '  <td>';
    if (global_permisos["AUDITORES"]["editar"] == 1) {
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnActualizarAnterior" id_calificacion="'+objCalif.ID+'" style="float: right;"> <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Actualizar</button>';
    }
    strHtml += '  </td>';
    strHtml += '  <td>'
    if (global_permisos["AUDITORES"]["editar"] == 1) {
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarCalif" id_calif="'+objCalif.ID+'" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar calificación </button>';
    }
    strHtml += '  </td>';
    strHtml += '</tr>';
    strHtml += '<tr class="collapse out" id="collapse-'+objCalif.ID+'">';
    strHtml += '  <td colspan="10">';
    strHtml += '    <table class="table subtable">';
    if (objCalif.ID_SERVICIO == 1)
    strHtml += '      <caption>Sectores ';
    if (objCalif.ID_SERVICIO == 3)
    strHtml += '      <caption>Cursos ';
    if (global_permisos["AUDITORES"]["registrar"] == 1) {
      if (objCalif.ID_SERVICIO == 1) {
          strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaCalifSector" id_calif="' + objCalif.ID + '" id_tipo_servicio="' + objCalif.ID_TIPO_SERVICIO + '" style="float: right;"> <i class="fa fa-plus"> </i> Agregar sector </button>';
      }
      if (objCalif.ID_SERVICIO == 3) {
          strHtml += '        <button type="button" class="btn btn-primary btn-xs btn-imnc btnInsertaCurso" id_calif="' + objCalif.ID + '" id_tipo_servicio="' + objCalif.ID_TIPO_SERVICIO + '" style="float: right;"> <i class="fa fa-plus"> </i> Agregar curso </button>';
      }
      }
    strHtml += '      </caption>';
    strHtml += '      <thead id="thead-'+objCalif.ID+'">';
    strHtml += '      </thead>';
    strHtml += '      <tbody id="tbody-'+objCalif.ID+'">';
    strHtml += '      </tbody>';
    strHtml += '    </table>';
    strHtml += '  </td>';
    strHtml += '</tr>';
    return strHtml;
  }

  function insertar_calif(){

    var fec_ini = $("#txtFecIniCalif").val();
    fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
    var fec_fin = $("#txtFecFinCalif").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
    var personal_tecnico_calif = {
      ID_PERSONAL_TECNICO:parseInt(global_id_personal_tecnico),
      ID_ROL:$("#cmbRol").val(),
      ID_TIPO_SERVICIO:$("#cmbTipoServicio").val(),
	  ID_NORMA:	$scope.selectedList,				//$("#cmbNorma").val(),
      REGISTRO:$("#txtRegistro").val(),
      FECHA_INICIO:fec_ini,
      FECHA_FIN:fec_fin,
      ID_USUARIO:sessionStorage.getItem("id_usuario"),
      FLAG:$scope.flag
    };
    $.post(global_apiserver + "/personal_tecnico_calificaciones/insert/", JSON.stringify(personal_tecnico_calif), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarCalif").modal("hide");
          notify("Éxito", "Se ha insertado una nueva calificación", "success");
          draw_domicilios_y_califs(); 
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_calif(){
    var fec_ini = $("#txtFecIniCalif").val();
    fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
    var fec_fin = $("#txtFecFinCalif").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
    var personal_tecnico_calif = {
      ID:$("#btnGuardarCalif").attr("id_calif"),
      ID_ROL:$("#cmbRol").val(),
      ID_TIPO_SERVICIO:$("#cmbTipoServicio").val(),
	  ID_NORMA:$scope.selectedList,
      REGISTRO:$("#txtRegistro").val(),
      FECHA_INICIO:fec_ini,
      FECHA_FIN:fec_fin,
      ID_USUARIO:sessionStorage.getItem("id_usuario"),
      FLAG:$scope.flag
    };
    $.post(global_apiserver + "/personal_tecnico_calificaciones/update/", JSON.stringify(personal_tecnico_calif), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarCalif").modal("hide");
          notify("Éxito", "Se ha actualizado una calificación", "success");
          draw_domicilios_y_califs();
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }
// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL Calif       *****
// ===========================================================================
    function onDatepickerClasif() {

        var dateInicial = $('.fecha-inicio').datepicker({
            dateFormat: "dd/mm/yy",
            //minDate: "+0D",
            language: "es",
            onSelect: function (dateText, ins) {
                dateFinal.datepicker("option", "minDate", dateText)

            }
        }).css("display", "inline-block");

        var dateFinal =$('.fecha-fin').datepicker({
            dateFormat: "dd/mm/yy",
            language: "es",
            //minDate: "+0D",
            onSelect: function (dateText, ins) {

            }
        }).css("display", "inline-block");

    }


// ================================================================================
// *****                       Calif. Sector                                  *****
// ================================================================================

  function fill_cmb_sector(seleccionado, id_tipo_servicio){
    $.getJSON( global_apiserver + "/sectores/getByFiltros/?id_tipo_servicio="+$("#btnGuardarCalifSector").attr("id_tipo_servicio"), function( response ) {
      $("#cmbSector").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objSector ) {
        $("#cmbSector").append('<option value="'+objSector.ID_SECTOR+'">'+objSector.NOMBRE+'</option>'); 
      });
      $("#cmbSector").val(seleccionado);
    });
  }  
	$("#cmbSector").change(function(){
		var id_sector = $("#cmbSector").val();
		fill_cmb_sectores_NACE(id_sector,"N/A");
	});
	function fill_cmb_sectores_NACE(id_sector,seleccionado){
        console.log(id_sector);
		$.getJSON( global_apiserver + "/sectores/getSectoresNACE/?id="+id_sector, function( response ) {
			$("#cmbSectorNACE").html('<option value="N/A" selected>-NO APLICA-</option>');
			$.each(response, function( indice, objSector ) {
				$("#cmbSectorNACE").append('<option value="'+objSector.ID_NACE+'">'+objSector.ID_NACE+'-'+objSector.DESCRIPCION+'</option>'); 
			});
			$("#cmbSectorNACE").val(seleccionado);
		});
	}
  function fill_modal_insertar_actualizar_calif_sector(id_calif_sector){
    $.getJSON( global_apiserver + "/personal_tecnico_calif_sector/getById/?id="+id_calif_sector, function( response ) {
          var fec_ini = response.FECHA_INICIO;
          fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
          var fec_fin = response.FECHA_FIN;
          fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
		  fill_cmb_sector(response.ID_SECTOR);
		  
		  fill_cmb_sectores_NACE(response.ID_SECTOR,response.SECTOR_NACE);
          if (response.SECTOR_NACE == 'N/A') {
            $("#cmbSectorNACE").prop("disabled", true);
            $("#chkSectorNaceNA").prop("checked", true);
          }
          else{
             $("#cmbSectorNACE").prop("disabled", false);
            $("#chkSectorNaceNA").prop("checked", false); 
          }
          $("#txtEsquema").val(response.ESQUEMA_CERTIFICACION);
          $("#txtAlcance").val(response.ALCANCE);
          $("#txtAprobacion").val(response.APROBACION_UVIC);
          $("#txtFecIniCalifSector").val(fec_ini);
          $("#txtFecFinCalifSector").val(fec_fin);
       });
  }
	
  function listener_chk_sector_nace_na(){
    $('#chkSectorNaceNA').change(function() {
        if($(this).is(":checked")) {
            $("#cmbSectorNACE").val("N/A");
            $("#cmbSectorNACE").prop("disabled", true);
        }
        else{
            //$("#cmbSectorNACE").val("");
            $("#cmbSectorNACE").prop("disabled", false);
        }
    });
  }
function removeOptions(selectbox)
{
    var i;
    for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}
  function clear_modal_insertar_actualizar_calif_sector(){
    //$("#cmbSectorNACE").val("");
	removeOptions(document.getElementById("cmbSectorNACE"));
    $("#txtEsquema").val("");
    $("#txtAlcance").val("");
    $("#txtAprobacion").val("");
    $("#txtFecIniCalifSector").val("");
    $("#txtFecFinCalifSector").val("");
    fill_cmb_sector("elige");
  }

  function listener_btn_sectores(){
    $( ".btnSectores" ).click(function() {
      var id_calif = $(this).attr("id");
      var id_tipo_servicio = $(this).attr("id_tipo_servicio");
       $.getJSON( global_apiserver + "/personal_tecnico_calif_sector/getByPTCalif/?idCalif="+id_calif, function( response ) {
          if (response.length > 0) {
            $("#thead-"+id_calif).html(draw_head_row_calif_sectores());
          }
          $("#tbody-"+id_calif).html("");
          $.each(response, function( index, objCalifSector ) {
              $("#tbody-"+id_calif).append(draw_row_calif_sectores(objCalifSector, id_tipo_servicio));
          });
          $("#collapse-"+id_calif).collapse("toggle");
          listener_btn_editar_calif_sector();
       });
    });
  }
  
  function listener_btn_actualiza(){
    $( ".btnActualiza" ).click(function() {
      var id_calif = $(this).attr("id");
      var id_tipo_servicio = $(this).attr("id_tipo_servicio");
       
    });
  }

  function listener_btn_nuevo_calif_sector(){
    $( ".btnInsertaCalifSector" ).click(function() {
      $("#btnGuardarCalifSector").attr("accion","insertar");
      $("#btnGuardarCalifSector").attr("id_calif",$(this).attr("id_calif"));
      $("#btnGuardarCalifSector").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
      $("#modalTituloCalifSector").html("Insertar nuevo sector de calificación");
	  clear_modal_insertar_actualizar_calif_sector();
      $("#modalInsertarActualizarCalifSector").modal("show");
    });
  }

  function listener_btn_editar_calif_sector(){
    $( ".btnEditarCalifSector" ).click(function() {
      $("#btnGuardarCalifSector").attr("accion","editar");
      $("#btnGuardarCalifSector").attr("id_calif_sector",$(this).attr("id_calif_sector"));
      $("#btnGuardarCalifSector").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
      $("#modalTituloCalifSector").html("Editar sector de calificación");
      fill_modal_insertar_actualizar_calif_sector($(this).attr("id_calif_sector"));
      $("#modalInsertarActualizarCalifSector").modal("show");
    });
  }

  function listener_btn_guardar_calif_sector(){
    $( "#btnGuardarCalifSector" ).click(function() {
      if ($("#btnGuardarCalifSector").attr("accion") == "insertar")
      {
        insertar_calif_sector($(this).attr("id_calif"));
      }
      else if ($("#btnGuardarCalifSector").attr("accion") == "editar")
      {
        editar_calif_sector();
      }
    });
  }

  function draw_head_row_calif_sectores(){
    var strHtml = "";
    strHtml += '        <tr>';
    strHtml += '         <th style="width: 255px;">Clave de sector </th>';
    strHtml += '         <th>Sector NACE </th>';
    strHtml += '         <th>Esquema de certificación </th>';
    strHtml += '         <th>Alcance </th>';
    strHtml += '         <th>Aprobacion UVIC</th>';
    strHtml += '         <th>Fecha inicio</th>';
    strHtml += '         <th>Fecha fin</th>';
    strHtml += '         <th></th>';
    strHtml += '        </tr>';
    return strHtml;
  }

  function draw_row_calif_sectores(objCalifSector, id_tipo_servicio){
    var fec_ini = objCalifSector.FECHA_INICIO;
    fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
    var fec_fin = objCalifSector.FECHA_FIN;
    fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
    var strHtml = "";
    strHtml += '      <tr>';
    strHtml += '        <td>' + objCalifSector.CLAVE_COMPUESTA + '<br>' + objCalifSector.NOMBRE_SECTOR_TRUNCADO + '... </td>';
    strHtml += '        <td>' + objCalifSector.SECTOR_NACE + '</td>';
    strHtml += '        <td>' + objCalifSector.ESQUEMA_CERTIFICACION + '</td>';
    strHtml += '        <td>' + objCalifSector.ALCANCE + '</td>';
    strHtml += '        <td>' + objCalifSector.APROBACION_UVIC + '</td>';
    strHtml += '        <td>' + fec_ini+ '</td>';
    strHtml += '        <td>' + fec_fin + '</td>';
    strHtml += '  <td>'
    if (global_permisos["AUDITORES"]["editar"] == 1) {
      strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarCalifSector" id_calif_sector="'+objCalifSector.ID+'" id_tipo_servicio="'+id_tipo_servicio+'" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar sector </button>';
    }
    
    strHtml += '  </td>';
    strHtml += '      </tr>';
    return strHtml;
  }

  function insertar_calif_sector(id_calif){
    var fec_ini = $("#txtFecIniCalifSector").val();
    fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
    var fec_fin = $("#txtFecFinCalifSector").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
    var personal_tecnico_calif_sector = {
      ID_PERSONAL_TECNICO_CALIFICACION:parseInt(id_calif),
      ID_SECTOR:$("#cmbSector").val(),
      SECTOR_NACE:$("#cmbSectorNACE").val(),
      ESQUEMA_CERTIFICACION:$("#txtEsquema").val(),
      ALCANCE:$("#txtAlcance").val(),
      APROBACION_UVIC:$("#txtAprobacion").val(),
      FECHA_INICIO:fec_ini,
      FECHA_FIN:fec_fin,
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico_calif_sector/insert/", JSON.stringify(personal_tecnico_calif_sector), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarCalifSector").modal("hide");
          notify("Éxito", "Se ha insertado un nuevo sector-calificación", "success");
          draw_domicilios_y_califs(); 
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }

  function editar_calif_sector(){
    var fec_ini = $("#txtFecIniCalifSector").val();
    fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
    var fec_fin = $("#txtFecFinCalifSector").val();
    fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
    var personal_tecnico_calif_sector = {
      ID:$("#btnGuardarCalifSector").attr("id_calif_sector"),
      ID_SECTOR:$("#cmbSector").val(),
      SECTOR_NACE:$("#cmbSectorNACE").val(),
      ESQUEMA_CERTIFICACION:$("#txtEsquema").val(),
      ALCANCE:$("#txtAlcance").val(),
      APROBACION_UVIC:$("#txtAprobacion").val(),
      FECHA_INICIO:fec_ini,
      FECHA_FIN:fec_fin,
      ID_USUARIO:sessionStorage.getItem("id_usuario")
    };
    $.post(global_apiserver + "/personal_tecnico_calif_sector/update/", JSON.stringify(personal_tecnico_calif_sector), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
          $("#modalInsertarActualizarCalifSector").modal("hide");
          notify("Éxito", "Se ha actualizado una calificación", "success");
          draw_domicilios_y_califs();
        }
        else
        {
           notify("Error", respuesta.mensaje, "error");
        }
    });
  }


// ================================================================================
// *****                       Calendario                                     *****
// ================================================================================

  function draw_calendario(){
    var eventos = [];
    $.getJSON( global_apiserver + "/personal_tecnico/getFechasAuditorias/?id="+global_id_personal_tecnico, function( response ) {
      $.each(response, function( indice, objAuditoria ) {
        if (objAuditoria.FECHAS_ASIGNADAS.length > 0) {
          for (var i = 0; i < objAuditoria.FECHAS_ASIGNADAS.length; i++) {
            var f_ini= objAuditoria.FECHAS_ASIGNADAS[i];
            var anhio_ini = parseInt(f_ini.substring(0,4));
            var mes_ini = parseInt(f_ini.substring(4,6))-1; //En js los meses comienzan en 0
            var dia_ini = parseInt(f_ini.substring(6,8));

           const descripcion = 'Auditoría: ' + objAuditoria.REFERENCIA;
            eventos.push(
              {
                title: descripcion,
                start: new Date(anhio_ini, mes_ini, dia_ini, 07, 0),
                end: new Date(anhio_ini, mes_ini, dia_ini, 18, 30),
                allDay: false,
                url: './?pagina=ec_tipos_servicio&id_serv_cli_et='+objAuditoria.ID_SERVICIO_CLIENTE_ETAPA,
				        descripcion: descripcion,
                tipo: 'Auditoría'
              }
            )
          } 
        }
        
      });
  //Agrego los eventos
      $.getJSON( global_apiserver + "/personal_tecnico_eventos/getByIdPersonalTecnico/?id="+global_id_personal_tecnico, function( response ) {
        $.each(response, function( indice, evento ) {
          var f_ini= evento.FECHA_INICIO;
          var anhio_ini = parseInt(f_ini.substring(0,4));
          var mes_ini = parseInt(f_ini.substring(5,7))-1; //En js los meses comienzan en 0
          var dia_ini = parseInt(f_ini.substring(8,10));

          var f_fin= evento.FECHA_FIN;
          var anhio_fin = parseInt(f_fin.substring(0,4));
          var mes_fin = parseInt(f_fin.substring(5,7))-1; //En js los meses comienzan en 0
          var dia_fin = parseInt(f_fin.substring(8,10));
  
          const descripcion = 'Evento: ' + evento.EVENTO;
          const tipo = 'Evento';

          const start = new Date(anhio_ini, mes_ini, dia_ini, 07, 0);
          const end = new Date(anhio_fin, mes_fin, dia_fin, 18, 30);
          eventos.push(
            {
              title: evento.EVENTO,
              start: start,
              end: end,
              color: '#8AECFA',
              allDay: false,
              descripcion: descripcion,
              tipo: tipo,
              fecha_inicio: evento.FECHA_INICIO,
              fecha_fin: evento.FECHA_FIN,
              id_evento: evento.ID
            }
          )         
        });
        if ($('#calendar').fullCalendar() !== undefined) {
          $('#calendar').fullCalendar('destroy');
        }
        var calendar = $('#calendar').fullCalendar({
          customButtons: {
            newEvent: {
                text: '+ Nuevo Evento',
                click: function() { 
                  $("#btnGuardarEvento").attr("accion","insertar");
                  $("#modalCrearEventoTitulo").html("Insertar nuevo evento");
                  $("#btnEliminarEvento").hide();
                  clear_modal_insertar_evento();
                  $("#modalCrearEvento").modal("show");
                }
            }
          },
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,newEvent'
          },
          minTime:"07:00:00",
          allDaySlot:false,
          selectable: false,
          selectHelper: true,
          editable: false,
          eventBackgroundColor:"#3e5a23",
          events: eventos,
          eventClick: function( event, jsEvent, view ) { 
            if (event.tipo == 'Evento') {
              $("#btnGuardarEvento").attr("accion","editar");
              $("#btnGuardarEvento").attr("id_evento",event.id_evento);
              $("#btnEliminarEvento").show();
              $("#modalCrearEventoTitulo").html("Editar evento");
              fill_modal_insertar_evento(event);
              $("#modalCrearEvento").modal("show");
            }
            //notify(event.tipo,event.descripcion,'info')
          }
        });
      });      
    });
  }
  function clear_modal_insertar_evento() {
    $('#evento').val("");
    $('#fecha_inicio').val("");
    $('#fecha_fin').val("");
  }
  function fill_modal_insertar_evento(evento) {
    $('#evento').val(evento.title);
    $('#fecha_inicio').val(evento.fecha_inicio);
    $('#fecha_fin').val(evento.fecha_fin);
  }
  function onCalendar() {
    $(document).ready(function () {
        $('#fecha_inicio').datepicker({
            dateFormat: "yy/mm/dd",
            minDate: "+0D"
        }).css("display", "inline-block");
        $('#fecha_fin').datepicker({
          dateFormat: "yy/mm/dd",
          minDate: "+0D"
      }).css("display", "inline-block");
    });  
  }
  $("#btnGuardarEvento" ).click(function() {
    var accion = $("#btnGuardarEvento").attr("accion");
    if (accion == 'insertar') {
      var nuevo_evento = {
        ID_PERSONAL_TECNICO:parseInt(global_id_personal_tecnico),
        EVENTO: $("#evento").val(),
        FECHA_INICIO: $("#fecha_inicio").val(),
        FECHA_FIN: $("#fecha_fin").val(),
        ID_USUARIO:parseInt(sessionStorage.getItem("id_usuario"))
      };
      $.post(global_apiserver + "/personal_tecnico_eventos/insert/", JSON.stringify(nuevo_evento), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
            $("#modalCrearEvento").modal("hide");
            notify("Éxito", "Se ha insertado un nuevo evento", "success");
            draw_calendario(); 
          }
          else
          {
             notify("Error", respuesta.mensaje, "error");
          }
      });
    } else {
      var id_evento = $("#btnGuardarEvento").attr("id_evento");
      var evento = {
        ID: id_evento,
        EVENTO: $("#evento").val(),
        FECHA_INICIO: $("#fecha_inicio").val(),
        FECHA_FIN: $("#fecha_fin").val(),
        ID_USUARIO:parseInt(sessionStorage.getItem("id_usuario"))
      };
      $.post(global_apiserver + "/personal_tecnico_eventos/update/", JSON.stringify(evento), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
            $("#modalCrearEvento").modal("hide");
            notify("Éxito", "Se ha modificado el evento", "success");
            draw_calendario(); 
          }
          else
          {
             notify("Error", respuesta.mensaje, "error");
          }
      });
    }
  });
  $("#btnEliminarEvento" ).click(function() {
    var id_evento = $("#btnGuardarEvento").attr("id_evento");
      var evento = {
        ID: id_evento,
        ID_USUARIO:parseInt(sessionStorage.getItem("id_usuario"))
      };
      $.post(global_apiserver + "/personal_tecnico_eventos/delete/", JSON.stringify(evento), function(respuesta){
          respuesta = JSON.parse(respuesta);
          if (respuesta.resultado == "ok") {
            $("#modalCrearEvento").modal("hide");
            notify("Éxito", "Se ha eliminado el evento", "success");
            draw_calendario(); 
          }
          else
          {
             notify("Error", respuesta.mensaje, "error");
          }
      });
  });

    // ================================================================================
// ================================================================================
// *****                       CRUD AGREGAR CURSO                             *****
// =========================================================================bmyorth
// ================================================================================

// ================================================================================
// *****           ACCION MOSTRAR MODAL INSERTAR                              *****
// ================================================================================
function listener_btn_nuevo_calif_curso(){
    $( ".btnInsertaCurso" ).click(function() {
        $("#btnGuardarCurso").attr("accion","insertar");
        $("#btnGuardarCurso").attr("id_calif",$(this).attr("id_calif"));
        $("#btnGuardarCurso").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
        $("#modalTituloCurso").html("Agregar curso");
        clear_modal_insertar_actualizar()
        cargarCursos("elige",$(this).attr("id_tipo_servicio"));
        $("#modalInsertarActualizarCusro").modal("show");

    })



    }
// ================================================================================
// *****           Limpiar modal Insertar/Actualizar Curso                   *****
// ================================================================================
    function clear_modal_insertar_actualizar() {

        $("#selectCurso").val("");
        $("#fechaInicioCurso").val("");
        $("#fechaFinCurso").val("");

    }
// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    function cargarCursos(seleccionado,id){
        $.getJSON( global_apiserver + "/cursos/getByModulo/?id="+id, function( response ) {
            $("#selectCurso").html('<option value="elige" selected disabled>-elige una opción-</option>');
            $.each(response, function( indice, objCurso ) {

                $("#selectCurso").append('<option value="'+objCurso.ID_CURSO+'">'+objCurso.NOMBRE+'</option>');
            });
            $("#selectCurso").val(seleccionado);
        });
    }

// ===================================================================
// ***** 			FUNCION GUARDAR DEL MODAL  				 *****
// ===================================================================
    function listener_btn_guardar_calif_curso(){
        $( "#btnGuardarCurso" ).click(function() {
            if ($("#btnGuardarCurso").attr("accion") == "insertar")
            {
                insertar_calif_curso($(this).attr("id_calif"),$(this).attr("id_tipo_servicio"));

            }
            else if ($("#btnGuardarCurso").attr("accion") == "editar")
            {
                editar_calif_curso($(this).attr("id_calif_curso"),$(this).attr("id_tipo_servicio"),$(this).attr("id_calif"));
            }
        });
    }

// ===================================================================
// ***** 			FUNCION PARA INSERTAR CURSO  				 *****
// ===================================================================
    function insertar_calif_curso(id_calif,id_tipo_servicio){

        var fec_ini = $("#fechaInicioCurso").val();
        fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
        var fec_fin = $("#fechaFinCurso").val();
        fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
        var curso_calif = {
            ID_PERSONAL_TECNICO_CALIFICACION:parseInt(id_calif),
            ID_CURSO:$("#selectCurso").val(),
            FECHA_INICIO:fec_ini,
            FECHA_FIN:fec_fin
        };
        $.post(global_apiserver + "/personal_tecnico_calif_curso/insert/", JSON.stringify(curso_calif), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $("#modalInsertarActualizarCusro").modal("hide");
                notify("Éxito", "Se ha agregado un nuevo curso", "success");
                show_cursos(id_calif,id_tipo_servicio,false);
                //draw_domicilios_y_califs();
            }
            else
            {
                notify("Error", respuesta.mensaje, "error");
            }
        });
    }
// ===================================================================
// ***** 			FUNCION PARA EDITAR CURSO  				 *****
// ===================================================================
    function editar_calif_curso(id_calif_curso,id_tipo_servicio,id_calif){

        var fec_ini = $("#fechaInicioCurso").val();
        fec_ini = fec_ini.substring(6,10)+fec_ini.substring(3,5)+fec_ini.substring(0,2);
        var fec_fin = $("#fechaFinCurso").val();
        fec_fin = fec_fin.substring(6,10)+fec_fin.substring(3,5)+fec_fin.substring(0,2);
        var curso_calif = {
            ID:parseInt(id_calif_curso),
            ID_PERSONAL_TECNICO_CALIFICACION:parseInt(id_calif),
            ID_CURSO:$("#selectCurso").val(),
            FECHA_INICIO:fec_ini,
            FECHA_FIN:fec_fin
        };
        $.post(global_apiserver + "/personal_tecnico_calif_curso/update/", JSON.stringify(curso_calif), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $("#modalInsertarActualizarCusro").modal("hide");
                notify("Éxito", "Se ha editado el curso", "success");
                show_cursos(id_calif,id_tipo_servicio,false);
               // draw_domicilios_y_califs();
            }
            else
            {
                notify("Error", respuesta.mensaje, "error");
            }
        });
    }
// ===================================================================
// ***** 	ACCION VER CURSO - MUESTRA CURSOS AGREGADOR          *****
// ===================================================================
    function listener_btn_cursos(){
        $( ".btnCursos" ).click(function() {
            var id_calif = $(this).attr("id");
            var id_tipo_servicio = $(this).attr("id_tipo_servicio");
            show_cursos(id_calif,id_tipo_servicio,true);
        });
    }
    function show_cursos(id_calif,id_tipo_servicio,flag)
    {
        $.getJSON( global_apiserver + "/personal_tecnico_calif_curso/getByPTCalif/?idCalif="+id_calif, function( response ) {
            if (response.length > 0) {
                $("#thead-"+id_calif).html(draw_head_row_calif_cursos());
            }
            $("#tbody-"+id_calif).html("");
            $.each(response, function( index, objCalifCurso ) {
                $("#tbody-"+id_calif).append(draw_row_calif_cursos(objCalifCurso,id_calif,id_tipo_servicio));
            });
            if(flag)
            {$("#collapse-"+id_calif).collapse("toggle");}

            listener_btn_editar_calif_curso();
        });
    }
// ===================================================================
// ***** 	MUESTRA LA CABECERA DE LA TABLA DONDE LOS CURSOS     *****
// ===================================================================
    function draw_head_row_calif_cursos(){
        var strHtml = "";
        strHtml += '        <tr>';
        strHtml += '         <th style="width: 255px;">Curso</th>';
        strHtml += '         <th>Fecha inicio</th>';
        strHtml += '         <th>Fecha fin</th>';
        strHtml += '         <th></th>';
        strHtml += '        </tr>';
        return strHtml;
    }
// ===================================================================
// ***** 	MUESTRA LA CUERPO DE LA TABLA DONDE LOS CURSOS     *****
// ===================================================================
    function draw_row_calif_cursos(objCalifCurso,id_calif,id_tipo_servicio){
        var fec_ini = objCalifCurso.FECHA_INICIO;
        fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
        var fec_fin = objCalifCurso.FECHA_FIN;
        fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
        var strHtml = "";
        strHtml += '      <tr>';
        strHtml += '        <td>' + objCalifCurso.NOMBRE_CURSO + ' </td>';
        strHtml += '        <td>' + fec_ini+ '</td>';
        strHtml += '        <td>' + fec_fin + '</td>';
        strHtml += '  <td>'
        if (global_permisos["AUDITORES"]["editar"] == 1) {
            strHtml += '    <button type="button" class="btn btn-primary btn-xs btn-imnc btnEditarCalifCurso" id_calif_curso="'+objCalifCurso.ID+'" id_tipo_servicio="'+id_tipo_servicio+'" id_calif="'+id_calif+'" style="float: right;"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar curso </button>';
        }

        strHtml += '  </td>';
        strHtml += '      </tr>';
        return strHtml;
    }
// ================================================================================
// *****           ACCION MOSTRAR MODAL EDITAR                              *****
// ================================================================================
    function listener_btn_editar_calif_curso(){
        $( ".btnEditarCalifCurso" ).click(function() {
            $("#btnGuardarCurso").attr("accion","editar");
            $("#btnGuardarCurso").attr("id_calif_curso",$(this).attr("id_calif_curso"));
            $("#btnGuardarCurso").attr("id_tipo_servicio",$(this).attr("id_tipo_servicio"));
            $("#btnGuardarCurso").attr("id_calif",$(this).attr("id_calif"));
            $("#modalTituloCurso").html("Editar curso de calificación");
            clear_modal_insertar_actualizar();
            fill_modal_insertar_actualizar_calif_cursos($(this).attr("id_calif_curso"),$(this).attr("id_tipo_servicio"));
            $("#modalInsertarActualizarCusro").modal("show");
        });
    }

    function fill_modal_insertar_actualizar_calif_cursos(id_calif_curso,id_tipo_servicio){
        $.getJSON( global_apiserver + "/personal_tecnico_calif_curso/getById/?id="+id_calif_curso, function( response ) {
            var fec_ini = response.FECHA_INICIO;
            fec_ini = fec_ini.substring(6,8)+"/"+fec_ini.substring(4,6)+"/"+fec_ini.substring(0,4);
            var fec_fin = response.FECHA_FIN;
            fec_fin = fec_fin.substring(6,8)+"/"+fec_fin.substring(4,6)+"/"+fec_fin.substring(0,4);
            cargarCursos(response.ID_CURSO,id_tipo_servicio)
            $("#fechaInicioCurso").val(fec_ini);
            $("#fechaFinCurso").val(fec_fin);
        });
    }

  }]);

