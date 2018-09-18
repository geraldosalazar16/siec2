var globla_domicilioprospecto;
var globla_contactoprospecto;
app.controller('perfilprospecto_controller', ['$scope', '$http', function($scope, $http) { 
	var form = $scope;
	//Titulo que aparece en el html
	$scope.modulo_permisos =  global_permisos["CRM"];
	
	$scope.titulo = 'Contactos';
	$scope.titulo2 = 'Domicilios';
	$scope.cbhabilitado=true;
	$scope.fiscalhabilitado=true;
	$scope.contactohabilitado=true;
	$scope.domiciliohabilitado=true;
    $scope.id_prospecto=getQueryVariable("id");
	/*
		Manejo de Contacto prospecto
	*/
	$scope.id_contacto=0;

	$scope.nombre_contacto="";
	$scope.domicilioContacto ="";
	$scope.correo="";
	$scope.telefono="";
	$scope.celular="";
    $scope.puesto="";
	$scope.fecha_creacion_contacto;
	$scope.fecha_modificacion_contacto;
	$scope.id_usuario_creacion_contacto;
	$scope.id_usuario_modificacion_contacto;
	$scope.activo_contacto=0;
	$scope.datos_adicionales = "";
    /*
		Manejo de Domicilios prospecto
	*/
	$scope.id_domicilio=0;
    $scope.nombre_domicilio="";
    $scope.pais="";
	$scope.estado="";
	$scope.municipio="";
    $scope.colonia="";
    $scope.codigo_postal=""
    $scope.calle="";
    $scope.numero_interior="";
    $scope.numero_exterior="";
    $scope.fiscal=0;
    $scope.fecha_creacion_domicilio;
	$scope.fecha_modificacion_domicilio;
	$scope.id_usuario_creacion_domicilio;
	$scope.id_usuario_modificacion_domicilio;
	$scope.activo_domicilio=0;
	$scope.chkColonia = false;

	/*
	Manejo de cliente
	*/
	/*$scope.nombre_cliente;
	$scope.cliente_rfc;
	$scope.fecha_creacion;
	$scope.cmbEsFacturario;
	$scope.cmbTieneFacturario;
	$scope.cmbClienteFac;
	$scope.cmbTPersona;
	$scope.cmbTEntidad;
    */
    //Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	$scope.id_producto = 0;
	$scope.mostrar_viaticos_anual = false;
	$scope.mostrar_viaticos_semestral = false;
	$scope.mostrar_semestrales = true;

	$scope.changeInAutoComplete = function(){
		$( "#autocompletePais" ).change(function() {
			$scope.pais = $( "#autocompletePais" ).val();
			if($(this).val() == "MEXICO (ESTADOS UNIDOS MEXICANOS)"){
		      $("#txtCP").hide();
		      $("#txtColonia").hide();
		      $("#autocompleteCP").show();
		      $("#autocompleteColonia").show();  
		      $scope.autocompleteListCP($("#txtCP").val());
		      $("#txtEstado").prop("readonly", true);
		      $("#txtMunicipio").prop("readonly", true);
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
		      $("#txtEstado").prop("readonly", false);
		      $("#txtMunicipio").prop("readonly", false);
		      $("#campoChkCol").hide();
		    }
		});

		$( "#autocompleteCP").change(function() {
		    $("#txtCP").val($(this).val());
		    $scope.codigo_postal = $(this).val();

		    if($(this).val() != ""){
		      $("#txtColonia").hide();
		      $("#autocompleteColonia").show();
		      $scope.autocompleteListBarrio($("#txtColonia").val(), $(this).val()); //CP como parámetro
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
	  	$( "#autocompleteColonia").change(function() {
	  		$scope.colonia = $(this).val();
	  		$("#txtColonia").val($(this).val());
		    if($(this).val() != "" && $("#txtCP").val() != ""){
		      get_delegacion_y_entidad($("#txtCP").val(), $(this).val());
		    }
	  	});

	}

	$scope.autocompleteListPais = function (seleccionado) {
	     $.getJSON( global_apiserver + "/paises/getAll/", function( response ) {
		    $("#autocompletePais").html('<option value="" selected disabled>-elige una opción-</option>');
		   	$.each(response, function( indice, objPais ) {
		      if (seleccionado == objPais.NOMBRE) {
		        $("#autocompletePais").append('<option value="'+objPais.NOMBRE+'" selected>'+objPais.NOMBRE+'</option>'); 
		      }else{
		        $("#autocompletePais").append('<option value="'+objPais.NOMBRE+'">'+objPais.NOMBRE+'</option>'); 
		      }
		      
		    });
		    $('#autocompletePais').select2();
		    $("#autocompletePais").val(seleccionado);
		    $("#autocompletePais" ).change();
		});
	}


	$scope.colonia_checkbox =function(){
      if($scope.chkColonia){
        $("#autocompleteColonia").val("");
        $("#txtColonia").val(""); 
	    $("#campoSelectColonia").hide();
	    $("#auxColonia").show();
        $("#campoNuevaColonia").show();
        $scope.colonia = "";
      }
      else{
        $("#campoSelectColonia").show();
	    $("#auxColonia").hide();
        $("#campoNuevaColonia").hide();
        $scope.colonia = "";
      } 
  	}

	$scope.autocompleteListCP = function (seleccionado){  
	  $("#autocompleteCP").html('<option value="" disabled>-elige una opción-</option>');
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


	$scope.autocompleteListBarrio = function (seleccionado, cp){
	  $.getJSON( global_apiserver + "/codigos_postales/getColoniaByCP/?cp="+cp, function( response ) {
	    $("#autocompleteColonia").html('<option value="" disabled>-elige una opción-</option>');
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
	    if(seleccionado != "" && !$("#autocompleteColonia").val()){
	      	$("#campoNuevaColonia").show();
	      	$scope.colonia = seleccionado; 
	      	$scope.chkColonia = true; 
	      	$scope.$apply();
	       	$("#campoSelectColonia").hide();
	    	$("#auxColonia").show();
	    }
	  });
	}

	function clear_autocomplete_cp(){
	  $("#autocompleteCP").html('<option value="" selected disabled>-elige una opción-</option>');
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
	  $("#autocompleteColonia").html('<option value="" selected disabled>-elige una opción-</option>');
	  try {
	    $("#autocompleteColonia").select2("destroy");
	  }
	  catch(err) {
	      if (err.message != "Cannot read property 'destroy' of undefined") {
	        throw err;
	      }
	  }
	}

	function get_delegacion_y_entidad(cp, colonia){
	  $.getJSON( global_apiserver + "/codigos_postales/getMunicipio&Entidad/?cp="+cp+"&colonia="+colonia, function( response ) {
	    //console.log(response);
	    if (response != null) {
	      $("#txtMunicipio").val(response.DELEGACION_MUNICIPIO);
	      $("#txtEstado").val(response.ENTIDAD_FEDERATIVA);
	      $scope.municipio = response.DELEGACION_MUNICIPIO;
	      $scope.estado = response.ENTIDAD_FEDERATIVA;
	    }
	    else{
	      $("#txtMunicipio").val("");
	      $("#txtEstado").val(""); 
	      $scope.municipio = "";
	      $scope.estado = "";
	    }
	  });
	}

	function get_delegacion_y_entidad(cp){
	  $.getJSON( global_apiserver + "/codigos_postales/getMunicipio&Entidad/?cp="+cp+"&colonia=", function( response ) {
	    //console.log(response);
	    if (response != null) {
	      $("#txtMunicipio").val(response.DELEGACION_MUNICIPIO);
	      $("#txtEstado").val(response.ENTIDAD_FEDERATIVA);
	      $scope.municipio = response.DELEGACION_MUNICIPIO;
	      $scope.estado = response.ENTIDAD_FEDERATIVA;
	    }
	    else{
	      $("#txtMunicipio").val("");
	      $("#txtEstado").val("");
	      $scope.municipio = "";
	      $scope.estado = ""; 
	    }
	  });
	}

	$scope.listaDomiciliosForContacto = function (){
	  $.getJSON( global_apiserver + "/prospecto_domicilio/getAll/?id="+$scope.id_prospecto , function( response ) {
	  	$scope.listaDomicilios = response;
	  });
	}

	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTablaContacto = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto_contacto/getAll/?id="+$scope.id_prospecto,
			success: function(data){
				$scope.$apply(function(){
					$scope.contactoprospecto = angular.fromJson(data);
					globla_contactoprospecto = $scope.contactoprospecto;
				});
				
			}
		});
	};
	$scope.actualizaPerfil= function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto_contacto/getAll/?id="+$scope.id_prospecto,
			success: function(data){
				$scope.$apply(function(){
					$scope.contactoprospecto = angular.fromJson(data);
					globla_contactoprospecto = $scope.contactoprospecto;
				});
				
			}
		});
	};
	$scope.actualizaTablaDomicilio = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto_domicilio/getAll/?id="+$scope.id_prospecto,
			success: function(data){
				$scope.$apply(function(){
					$scope.domicilioprospecto = angular.fromJson(data);
					globla_domicilioprospecto = $scope.domicilioprospecto;
				});
			}
		});
	};
	$scope.actualizarCotizacion = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/cotizacion_rapida/getByIdProspecto/?id_prospecto="+$scope.id_prospecto+"&id_producto="+$scope.id_producto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
					if(response.data == 0)
					{
						$scope.Cotizaciones = "";
					}
					else
					{
						$scope.Cotizaciones = response.data;
					}
					$scope.mostrar_cotizacion = false;
				},
				function (response){});
	}
	$scope.agregarCotizacion = function(){
		
		$scope.accion_cotizacion = "insertar";
		$scope.limpiarDatosCotizacion();
		$scope.mostrar_cotizacion = true;
		$scope.GenerarReferenciaProspecto();
	}
	$scope.guardarCotizacion = function(){
		//Validaciones
		if(!$scope.nombreCotizacion)
		{
			notify("Error", "El nombre de la cotización no puede estar vacío", "error");
		}
		else if($scope.tiposCotizacion != 0 && !$scope.productosCotizacion)
		{
			notify("Error", "Debe seleccionar un producto", "error");
		}
		else
		{
			if($scope.tiposCotizacion == 0)
			{
				$scope.id_producto = 0;
			}
			var periodicidad;
			if($scope.periodicidad_cotizacion == 0){
				periodicidad = 'SEMESTRAL';
			}
			else{
				periodicidad = 'ANUAL';
			}
			var viaticos_e1 = 0;
			if($scope.viaticos_e1){
				viaticos_e1 = $scope.viaticos_e1;
			}
			var viaticos_e2 = 0;
			if($scope.viaticos_e1){
				viaticos_e2 = $scope.viaticos_e2;
			}
			var viaticos_v1 = 0;
			if($scope.viaticos_e1){
				viaticos_v1 = $scope.viaticos_v1;
			}
			var viaticos_v2 = 0;
			if($scope.viaticos_e1){
				viaticos_v2 = $scope.viaticos_v2;
			}
			var viaticos_v3 = 0;
			if($scope.viaticos_e1){
				viaticos_v3 = $scope.viaticos_v3;
			}
			var viaticos_v4 = 0;
			if($scope.viaticos_e1){
				viaticos_v4 = $scope.viaticos_v4;
			}
			var viaticos_v5 = 0;
			if($scope.viaticos_v5){
				viaticos_v5 = $scope.viaticos_v5;
			}
			var cotizacion = {
				id_prospecto: $scope.id_prospecto,
				id_producto: $scope.id_producto,
				nombre: $scope.nombreCotizacion,
				REFERENCIA: $scope.referencia,
				FECHA_E1: $scope.fecha_e1,
				FECHA_E2: $scope.fecha_e2,
				FECHA_V1: $scope.fecha_v1,
				FECHA_V2: $scope.fecha_v2,
				FECHA_V3: $scope.fecha_v3,
				FECHA_V4: $scope.fecha_v4,
				FECHA_V5: $scope.fecha_v5,
				MONTO_E1: $scope.monto_e1,
				MONTO_E2: $scope.monto_e2,
				MONTO_V1: $scope.monto_v1,
				MONTO_V2: $scope.monto_v2,
				MONTO_V3: $scope.monto_v3,
				MONTO_V4: $scope.monto_v4,
				MONTO_V5: $scope.monto_v5,
				DIAS_E1: $scope.dias_e1,
				DIAS_E2: $scope.dias_e2,
				DIAS_V1: $scope.dias_v1,
				DIAS_V2: $scope.dias_v2,
				DIAS_V3: $scope.dias_v3,
				DIAS_V4: $scope.dias_v4,
				DIAS_V5: $scope.dias_v5,
				CANTIDAD_EMPLEADOS: $scope.cantidad_empleados,
				CANTIDAD_SITIOS: $scope.cantidad_sitios,
				IMPORTE_CERTIFICADO: $scope.importe_certificado,
				CERTIFICADO: $scope.certificado,
				VIATICOS:  $scope.viaticos,
				IVA:  $scope.iva,
				VIATICOS_E1: viaticos_e1,
				VIATICOS_E2: viaticos_e2,
				VIATICOS_V1: viaticos_v1,
				VIATICOS_V2: viaticos_v2,
				VIATICOS_V3: viaticos_v3,
				VIATICOS_V4: viaticos_v4,
				VIATICOS_V5: viaticos_v5,
				PERIODICIDAD: periodicidad
			};

			if($scope.accion_cotizacion == "editar")
			{
				$.post(global_apiserver + "/cotizacion_rapida/update/", JSON.stringify(cotizacion), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						notify_success("Éxito", "Se ha modificado la cotización");
						$scope.mostrar_cotizacion = false;
						$scope.ocultar_nombre_cotizacion = true;
					}
					else
					{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
			else if($scope.accion_cotizacion == "insertar")
			{
				$.post(global_apiserver + "/cotizacion_rapida/insert/", JSON.stringify(cotizacion), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						notify_success("Éxito", "Se ha insertado la cotización");
						$scope.mostrar_cotizacion = false;
						$scope.ocultar_nombre_cotizacion = true;
					}
					else
					{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
	}
	$scope.cambioProducto = function(){
		$("#productoerror").text("");
		if($scope.productosCotizacion)
		{
			$scope.id_producto = $scope.productosCotizacion.id;
		}
		else
		{
			$scope.id_producto = 0;
		}
		$scope.actualizarCotizacion();
		$scope.AreaCotizacion();
		$scope.limpiarDatosCotizacion();
		$scope.mostrar_cotizacion = false;
	}
	$scope.cambioCotizacion = function(){
		var cotizacion = $scope.cotizaciones;
		//Es necesario que se seleccione el producto
		$scope.id_producto = cotizacion.ID_PRODUCTO;
		if(cotizacion)
		{
			$scope.mostrar_cotizacion = true;
			$scope.accion_cotizacion = "editar";
			
			$scope.tiposCotizacion = cotizacion['ID_PRODUCTO'];
			$scope.nombreCotizacion = cotizacion['NOMBRE'];
			//////////////////////////////////////////////////
			$scope.referencia = cotizacion['REFERENCIA'];
			//////////////////////////////////////////////////
			$scope.fecha_e1 = convertir_fecha(cotizacion['FECHA_E1']);
			$scope.fecha_e2 = convertir_fecha(cotizacion['FECHA_E2']);
			$scope.fecha_v1 = convertir_fecha(cotizacion['FECHA_V1']);
			$scope.fecha_v2 = convertir_fecha(cotizacion['FECHA_V2']);
			$scope.fecha_v3 = convertir_fecha(cotizacion['FECHA_V3']);
			$scope.fecha_v4 = convertir_fecha(cotizacion['FECHA_V4']);
			$scope.fecha_v5 = convertir_fecha(cotizacion['FECHA_V5']);

			$scope.monto_e1 = parseFloat(cotizacion["MONTO_E1"]);
			$scope.monto_e2 = parseFloat(cotizacion["MONTO_E2"]);
			$scope.monto_v1 = parseFloat(cotizacion["MONTO_V1"]);
			$scope.monto_v2 = parseFloat(cotizacion["MONTO_V2"]);
			$scope.monto_v3 = parseFloat(cotizacion["MONTO_V3"]);
			$scope.monto_v4 = parseFloat(cotizacion["MONTO_V4"]);
			$scope.monto_v5 = parseFloat(cotizacion["MONTO_V5"]);
						
			$scope.dias_e1 = parseFloat(cotizacion["DIAS_E1"]);
			$scope.dias_e2 = parseFloat(cotizacion["DIAS_E2"]);
			$scope.dias_v1 = parseFloat(cotizacion["DIAS_V1"]);
			$scope.dias_v2 = parseFloat(cotizacion["DIAS_V2"]);	
			$scope.dias_v3 = parseFloat(cotizacion["DIAS_V3"]);
			$scope.dias_v4 = parseFloat(cotizacion["DIAS_V4"]);
			$scope.dias_v5 = parseFloat(cotizacion["DIAS_V5"]);
						
			$scope.cantidad_empleados = parseFloat(cotizacion["NO_EMPLEADOS"]);
			$scope.cantidad_sitios = parseFloat(cotizacion["NO_SITIOS"]);
			$scope.importe_certificado = parseFloat(cotizacion["IMPORTE_CERTIFICADO"]);
			
			$scope.certificado = cotizacion["CERTIFICADO_ACREDITADO"];
			$scope.viaticos = cotizacion["VIATICOS_INCLUIDOS"];
			$scope.iva = cotizacion["IVA_INCLUIDO"];
			
			//Funcionalidad de viaticos y periodicidad
			$scope.viaticos_e1 = cotizacion["VIATICOS_E1"];
			$scope.viaticos_e2 = cotizacion["VIATICOS_E2"];
			$scope.viaticos_v1 = cotizacion["VIATICOS_V1"];
			$scope.viaticos_v2 = cotizacion["VIATICOS_V2"];
			$scope.viaticos_v3 = cotizacion["VIATICOS_V3"];
			$scope.viaticos_v4 = cotizacion["VIATICOS_V4"];
			$scope.viaticos_v5 = cotizacion["VIATICOS_V5"];
			
			if(cotizacion["PERIODICIDAD"] == 'SEMESTRAL'){
				$scope.periodicidad_cotizacion = 0;
			}
			else{
				$scope.periodicidad_cotizacion = 1;
			}
		}
	}
	$scope.limpiarDatosCotizacion = function(){
		$scope.fecha_e1 = "";
		$scope.fecha_e2 = "";
		$scope.fecha_v1 = "";
		$scope.fecha_v2 = "";
		$scope.fecha_v3 = "";
		$scope.fecha_v4 = "";
		$scope.fecha_v5 = "";

		$scope.monto_e1 = 0;
		$scope.monto_e2 = 0;
		$scope.monto_v1 = 0;
		$scope.monto_v2 = 0;
		$scope.monto_v3 = 0;
		$scope.monto_v4 = 0;
		$scope.monto_v5 = 0;
					
		$scope.dias_e1 = 0;
		$scope.dias_e2 = 0;
		$scope.dias_v1 = 0;
		$scope.dias_v2 = 0;	
		$scope.dias_v3 = 0;
		$scope.dias_v4 = 0;
		$scope.dias_v5 = 0;
					
		$scope.cantidad_empleados = 0;
		$scope.cantidad_sitios = 0;
		$scope.importe_certificado = 0;
		
		$scope.certificado = "";
		$scope.viaticos = "";
		$scope.iva = "";
		
		$scope.nombreCotizacion = "";
		$scope.tiposCotizacion = "";
		
		//De inicio la cotizacion es anual
		$scope.mostrar_semestrales = false;
		$scope.periodicidad_cotizacion = 1;
		$scope.viaticos = 0;
	}
	$scope.guardarProductoProspecto = function(){
		var area = $scope.areas;
		if(!area)
			area = 0;
		var departamento = $scope.departamentos;
		if(!departamento)
			departamento = 0;
		var producto = $scope.productos;
		if(!producto)
			producto = 0;
		
		var boton = $("#btnGuardarProductoProspecto");
		var accion = boton.attr("accion");

		if (accion == "insertar")
		{
			var info = {
				id_prospecto: $scope.id_prospecto,
				area: area,
				departamento: departamento,
				producto: producto
			};
			$.post(global_apiserver + "/prospecto_producto/insert/", JSON.stringify(info), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					notify_success("Éxito", "Se ha insertado la información");
					$scope.ActualizarAreas();
				}
				else{
					notify("Error", respuesta.mensaje, "error");
				}
				$("#modalInsertarActualizarProductoProspecto").modal('hide');
			});
		}
		else if (accion == "editar")
		{
			var id_producto = boton.attr('id_producto');
			var info = {
				id: id_producto,
				id_prospecto: $scope.id_prospecto,
				area: area,
				departamento: departamento,
				producto: producto
			};
			$.post(global_apiserver + "/prospecto_producto/update/", JSON.stringify(info), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					notify_success("Éxito", "Se ha actualizado la información");
					$scope.ActualizarAreas();
				}
				else{
					notify("Error", respuesta.mensaje, "error");
				}
				$("#modalInsertarActualizarProductoProspecto").modal('hide');
			});
		}
	}
	$scope.agregarProducto = function(){
		$scope.AreasLista();
		$scope.DepartamentosLista();
		$scope.ProductosLista();
		$("#modalTituloProductoProspecto").html('INSERTAR PRODUCTO');
		$("#btnGuardarProductoProspecto").attr("accion","insertar");
		$("#modalInsertarActualizarProductoProspecto").modal('show');
	}
////////////////////////////////////////////////////////////////////////////
//Funciones para Eliminar PRODUCTOS
////////////////////////////////////////////////////////////////////////////////

	$scope.eliminarProducto = function(id){
		$("#btnEliminar").attr("id_tabla_producto",id);
		$("#modalConfirmacion").modal("show");	
		
	}
	
$( "#btnEliminar" ).click(function() {

    $scope.eliminar($("#btnEliminar").attr("id_tabla_producto"));
 });
$scope.eliminar = function(id){	
//function eliminar(id){
   $.post( global_apiserver + "/prospecto_producto/delete/?id="+id, function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
		
			$("#modalConfirmacion").modal("hide");
			notify("&Eacutexito", "Se han eliminado los datos", "success");
			$scope.ActualizarAreas();       
        }
        else{
			notify("Error", respuesta.mensaje, "error");
		}
    });
}	
////////////////////////////////////////////////////////////////////////////////
	$scope.editarProducto = function(id){
		//recibe la url del php que se ejecutará
		
		$http.get(  global_apiserver + "/prospecto_producto/getById/?id="+id)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta				
				var id_area = response.data["ID_AREA"];	
				var id_departamento = response.data["ID_DEPARTAMENTO"];	
				var id_producto = response.data["ID_PRODUCTO"];	
				
				//Primero cargo todas las areas
				$scope.AreasLista();
				//Luego verificio que tenga area asignada
				if(id_area == 0){//Sin asignar
					//Valor de área sin asignar
					id_area = '';
					$scope.areas = id_area;
					//Si no está asignada el area muestro todos los departamentos
					$scope.DepartamentosLista();
					//Ahora verifico si tiene departamento asignado
					if(id_departamento == 0){//Sin asignar
						id_departamento = 'elige';
						$scope.departamentos = id_departamento;
						//Cargo todos los productos ya que no hay departamentos asignados
						$scope.ProductosLista();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
					else{
						//Si el departamento esta asignado lo selecciono 
						//y limito los productos
						$scope.departamentos = id_departamento;
						$scope.departamentos_cambio();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
				}
				else{
					//Si esta actualizado muestro el área seleccionada
					$scope.areas = id_area;
					//Llamo a la funcion de cambios para que asigne los departamentos
					$scope.areas_cambio();
					//Ahora verifico si tiene departamento asignado
					if(id_departamento == 0){//Sin asignar
						id_departamento = 'elige';
						$scope.departamentos = id_departamento;
						//Cargo todos los productos ya que no hay departamentos asignados
						$scope.ProductosLista();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
					else{
						//Si el departamento esta asignado lo selecciono 
						//y limito los productos
						$scope.departamentos = id_departamento;
						$scope.departamentos_cambio();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
				}
			},
			function (response){});
		$("#modalTituloProductoProspecto").html('MODIFICAR PRODUCTO');
		$("#btnGuardarProductoProspecto").attr("accion","editar");
		$("#btnGuardarProductoProspecto").attr("id_producto",id);
		$("#modalInsertarActualizarProductoProspecto").modal('show');
	}
	$scope.ActualizarAreas = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_producto/getByIdProspecto/?id="+$scope.id_prospecto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.ProductosProspecto = response.data.map(function(item){
	  				return{
	  					id : item.ID_PROSPECTO_PRODUCTO,
	  					nombre : item.NOMBRE_PRODUCTO,
						departamento: item.NOMBRE_DEPARTAMENTO,
						area: item.NOMBRE_AREA
	  				}
	  			});
	  			
			},
			function (response){});
		//recibe la url del php que se ejecutará
		/*
		$http.get(  global_apiserver + "/prospecto_producto/getByIdProspecto/?id="+$scope.id_prospecto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta				
				var id_area = response.data["ID_AREA"];	
				var id_departamento = response.data["ID_DEPARTAMENTO"];	
				var id_producto = response.data["ID_PRODUCTO"];	
				
				//Primero cargo todas las areas
				$scope.AreasLista();
				//Luego verificio que tenga area asignada
				if(id_area == 0){//Sin asignar
					//Valor de área sin asignar
					id_area = '';
					$scope.areas = id_area;
					//Si no está asignada el area muestro todos los departamentos
					$scope.DepartamentosLista();
					//Ahora verifico si tiene departamento asignado
					if(id_departamento == 0){//Sin asignar
						id_departamento = 'elige';
						$scope.departamentos = id_departamento;
						//Cargo todos los productos ya que no hay departamentos asignados
						$scope.ProductosLista();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
					else{
						//Si el departamento esta asignado lo selecciono 
						//y limito los productos
						$scope.departamentos = id_departamento;
						$scope.departamentos_cambio();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
				}
				else{
					//Si esta actualizado muestro el área seleccionada
					$scope.areas = id_area;
					//Llamo a la funcion de cambios para que asigne los departamentos
					$scope.areas_cambio();
					//Ahora verifico si tiene departamento asignado
					if(id_departamento == 0){//Sin asignar
						id_departamento = 'elige';
						$scope.departamentos = id_departamento;
						//Cargo todos los productos ya que no hay departamentos asignados
						$scope.ProductosLista();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
					else{
						//Si el departamento esta asignado lo selecciono 
						//y limito los productos
						$scope.departamentos = id_departamento;
						$scope.departamentos_cambio();
						//Ahora verifico si tiene producto asignado
						if(id_producto == 0){//Sin asignar
							id_producto = 'elige';
							$scope.productos = id_producto;	
						}
						else{
							//Si tiene asignado muestro el valor y la descripcion
							$scope.productos = id_producto;			
							$scope.productos_cambio();
						}
					}
				}
			},
			function (response){});
			*/
	}
	$scope.OrigenLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto/getOrigen/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Origenes = response.data.map(function(item){
	  				return{
	  					id_origen : item.ID,
	  					origen : item.ORIGEN
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.AreasLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/areas/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Areas = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.NOMBRE
	  				}
	  			});
				/*
	  		$scope.Areas.push({
					id: 'elige',
					nombre: 'Seleccione un área'
				});
			*/				
			},
			function (response){});
	}
	$scope.areas_cambio = function(){
		//Si se cambia el área que solo aparezcan los departamentos de esa área
		var id_area = $scope.areas;
		if(id_area){
			//Selecciona los productos de esta area
			productos_por_area(id_area);
			//recibe la url del php que se ejecutará
			$http.get(  global_apiserver + "/departamentos/getByIdArea/?id_area="+id_area)
				.then(function( response ) {//se ejecuta cuando la petición fue correcta
					$scope.Departamentos = response.data.map(function(item){
						return{
							id : item.ID,
							nombre : item.NOMBRE
						}
					});
					
				},
				function (response){});
		}
	}
	function productos_por_area(id_area){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/productos/getByIdArea/?id_area="+id_area)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Productos = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.DepartamentosLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/departamentos/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Departamentos = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.departamentos_cambio = function(){
		//Si se cambia el área que solo aparezcan los departamentos de esa área
		var id_departamento = $scope.departamentos;
		if(id_departamento){
			//recibe la url del php que se ejecutará
			$http.get(  global_apiserver + "/productos/getByIdDepartamento/?id_departamento="+id_departamento)
				.then(function( response ) {//se ejecuta cuando la petición fue correcta
					$scope.Productos = response.data.map(function(item){
						return{
							id : item.ID,
							nombre : item.NOMBRE
						}
					});
					
				},
				function (response){});
		}
	}
	$scope.ProductosLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/productos/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Productos = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.productos_cambio = function(){
		//Si se cambia el producto se muestra su descripcion
		var id_producto = $scope.productos;
		if(id_producto){
			//recibe la url del php que se ejecutará
			$http.get(  global_apiserver + "/productos/getById/?id="+id_producto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
					$scope.desc_producto = response.data["DESCRIPCION"];  			
			},
			function (response){});
		}
	}
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCamposContacto = function(){
		$scope.id_contacto=0;
		$scope.domicilioContacto = "";
	    $scope.nombre_contacto="";
	    $scope.correo="";
	    $scope.telefono="";
	    $scope.puesto="";
	    $scope.celular="";
        
	};
	$scope.limpiaCamposDomicilio = function(){
		$scope.id_domicilio=0;
        $scope.nombre_domicilio="";
        $scope.pais="";
	    $scope.estado="";
	    $scope.municipio="";
        $scope.colonia="";
        $scope.codigo_postal=""
        $scope.calle="";
        $scope.numero_interior="";
        $scope.numero_exterior="";
        $scope.fiscal=0;
        $scope.chkColonia = false;
		$("#campoNuevaColonia").hide();
		$("#campoSelectColonia").show();
	    $("#auxColonia").hide();
        $scope.autocompleteListPais("");
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregarContacto = function() {
		$(".text-danger").empty();	
	    $scope.limpiaCamposContacto();
	    $(".text-danger").empty();		
		$("#btnGuardarContacto").attr("accion","insertarContacto");
		$("#modalTituloContactoProspecto").html("Crear Contacto");
		$("#modalInsertarActualizarContacto").modal("show");
	};
	$scope.agregarDomicilio= function() {
		$(".text-danger").empty();
	    $scope.limpiaCamposDomicilio();		
		$("#btnGuardarDomicilio").attr("accion","insertarDomicilio");
		$("#modalTitulo2").html("Crear Domicilio");
		$("#modalInsertarActualizarDomicilio").modal("show");
	};
	
	$scope.detallesContacto=function(id_contacto){

		$("#modalTitulo").html("Detalles de Prospecto")
	    $.getJSON(global_apiserver+"/prospecto_contacto/getById/?id="+id_contacto,function(response){
		
			$scope.id_contacto=response.ID;
	        $scope.nombre_contacto=response.NOMBRE;
	        $scope.nombre_domicilioContacto = response.NOMBRE_DOMICILIO;
	        $scope.domicilioContacto = response.ID_PROSPECTO_DOMICILIO;
	        $scope.correo=response.CORREO;
	        $scope.telefono=response.TELEFONO;
	        $scope.celular=response.CELULAR;
            $scope.puesto=response.PUESTO;
	        $scope.fecha_creacion_contacto=response.FECHA_CREACION;
	        $scope.fecha_modificacion_contacto=response.FECHA_MODIFICACION;
	        $scope.id_usuario_creacion_contacto=response.NOMBRE_USUARIO_CREAR;
	        $scope.id_usuario_modificacion_contacto=response.NOMBRE_USUARIO_MOD;
	        $scope.activo_contacto=response.ACTIVO;
	        $scope.datos_adicionales = response.DATOS_ADICIONALES;
	        if($scope.activo_contacto==1){
               $scope.contactohabilitado=true;
	        }else{
               $scope.contactohabilitado=false;
	        }

			$scope.$apply();
	});
	    $("#modalDetallesContacto").modal("show");
	};
	$scope.detallesDomicilio=function(id_domicilio){

		$("#modalTitulo2").html("Detalles de Domicilio");
	    $.getJSON(global_apiserver+"/prospecto_domicilio/getById/?id="+id_domicilio,function(response){
		
			$scope.id_domicilio=response.ID;
	        $scope.id_prospecto=response.ID_PROSPECTO;
            $scope.nombre_domicilio=response.NOMBRE;
            $scope.pais=response.PAIS;
	        $scope.estado=response.ESTADO;
	        $scope.municipio=response.MUNICIPIO;
            $scope.colonia=response.COLONIA;
            $scope.codigo_postal=response.CODIGO_POSTAL;
            $scope.calle=response.CALLE;
            $scope.numero_interior=response.NUMERO_INTERIOR;
            $scope.numero_exterior=response.NUMERO_EXTERIOR;
            $scope.fiscal=response.FISCAL;
            $scope.activo_domicilio=response.CENTRAL;
	        $scope.fecha_creacion_domicilio=response.FECHA_CREACION;
	        $scope.fecha_modificacion_domicilio=response.FECHA_MODIFICACION;
	        $scope.id_usuario_creacion_domicilio=response.NOMBRE_USUARIO_CREAR;
	        $scope.id_usuario_modificacion_domicilio=response.NOMBRE_USUARIO_MOD;
	        if($scope.fiscal==1){
               $scope.fiscalhabilitado=true;
	        }else{
               $scope.fiscalhabilitado=false;
	        }
	        if($scope.activo_domicilio==1){
               $scope.domiciliohabilitado=true;
	        }else{
               $scope.domiciliohabilitado=false;
	        }
			$scope.$apply()
	});
	    $("#modalDetallesDomicilio").modal("show");
	};
	$scope.editarContacto =  function(id_contacto) {
	    $(".text-danger").empty();				
		$("#btnGuardarContacto").attr("accion","editarContacto");
		$("#modalTituloContactoProspecto").html("Editar Contacto");
	  
		$.getJSON( global_apiserver + "/prospecto_contacto/getById/?id="+id_contacto, function( response ) {
			$scope.id_contacto=response.ID;
	        $scope.nombre_contacto=response.NOMBRE;
	        $scope.domicilioContacto = response.ID_PROSPECTO_DOMICILIO;
	        $scope.correo=response.CORREO;
			$scope.correo2=response.CORREO2;
	        $scope.telefono=response.TELEFONO;
	        $scope.celular=response.CELULAR;
            $scope.puesto=response.PUESTO;
	        $scope.activo_contacto=response.ACTIVO;
	        $scope.datos_adicionales = response.DATOS_ADICIONALES;
			$scope.$apply();
       });
		//$("#nombre").attr("readonly",true);
		$("#modalInsertarActualizarContacto").modal("show");
    
	};
	$scope.editarDomicilio =  function(id_domicilio) {
		$(".text-danger").empty();		
		$("#btnGuardarDomicilio").attr("accion","editarDomicilio");
		$("#modalTitulo2").html("Editar Domicilio ");
	  
		$.getJSON( global_apiserver + "/prospecto_domicilio/getById/?id="+id_domicilio, function( response ) {
			$scope.id_domicilio=response.ID;
            $scope.nombre_domicilio=response.NOMBRE;
            $scope.pais=response.PAIS;
            
	        $scope.estado=response.ESTADO;
	        $scope.municipio=response.MUNICIPIO;
            $scope.colonia=response.COLONIA;
            
            $scope.codigo_postal=response.CODIGO_POSTAL;
            $scope.calle=response.CALLE;
            $scope.numero_interior=response.NUMERO_INTERIOR;
            $scope.numero_exterior=response.NUMERO_EXTERIOR;
            $scope.fiscal=response.FISCAL;
            $scope.activo_domicilio=response.CENTRAL;
            if($scope.fiscal==1){
                $scope.fiscalhabilitado=true;
            }else{
                $scope.fiscalhabilitado=false;
            }
            if($scope.activo_domicilio==1){
                $scope.domiciliohabilitado=true;
            }else{
                $scope.domiciliohabilitado=false;
            }
            $scope.chkColonia = false;
            $scope.$apply();
            $("#txtCP").val(response.CODIGO_POSTAL);
	        $("#txtColonia").val(response.COLONIA);
	        $("#campoNuevaColonia").hide();
	        $("#campoSelectColonia").show();
	    	$("#auxColonia").hide();
            $scope.autocompleteListPais(response.PAIS);
			$("#modalInsertarActualizarDomicilio").modal("show");
       });
	};
	/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
	*/
	$scope.cerrarContacto= function() {	
		comsole.log("cerrarcontacto");	
		$("#nombreerror").text("");	
	};

	$scope.valida_agregarContacto = function(){
		$scope.respuesta = 1;
		$(".text-danger").empty();
			 if(!$scope.nombre_contacto){
				$scope.respuesta =  0;
				$("#nombreerror").text("Falta un Nombre.");
				console.log("Error nombre");
			} 
			if(!$scope.correo){
				$scope.respuesta =  0;
				$("#correoerror").text("Falta un Correo.");
				console.log("Error Correo");
			}
			 if(!$scope.telefono){
				$scope.respuesta =0;
				$("#telefonoerror").text("Falta Telefono.");
				console.log("Error telefono");
			}
			if(!$scope.puesto){
				$scope.respuesta = 0;
				$("#puestoerror").text("Falta Puesto.");
				console.log("Error puesto");
			}
	};

	$scope.valida_agregarDomicilio = function(){
            
		$scope.respuesta = 1;
		$(".text-danger").empty();
			 if(!$scope.pais){
				$scope.respuesta =  0;
				$("#paiserror").text("Falta Pais.");
				console.log("Error nombre");
			} 
			if(!$scope.codigo_postal){
				$scope.respuesta =  0;
				$("#codigo_postalerror").text("Falta Codigo Postal.");
				console.log("Error Correo");
			}
			 if(!$scope.calle){
				$scope.respuesta =0;
				$("#calleerror").text("Falta Calle.");
				console.log("Error telefono");
			}
			if(!$scope.numero_exterior){
				$scope.respuesta = 0;
				$("#numero_exteriorerror").text("Falta Número exterior.");
				console.log("Error puesto");
			}
			if(!$scope.colonia){
				$scope.respuesta = 0;
				$("#coloniaerror").text("Falta Colonia");
				console.log("Error puesto"+$scope.colonia);
			}
			if(!$scope.municipio){
				$scope.respuesta = 0;
				$("#municipioerror").text("Falta Municipio");
				console.log("Error puesto");
			}
			if(!$scope.estado){
				$scope.respuesta = 0;
				$("#estadoerror").text("Falta Estado");
				console.log("Error puesto");
			}
	};
	
	/*
		Se checa si es válida la modificación. Solo tomamos en cuenta la descripción
		ya que el nombre no se puede modificar. Con el id "descripcionerror" mostramos
		el error correspondiente.
	*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;	
		$("#nombreProspectoerror").text("");		
		if(!$scope.nombre_prospecto){
			$scope.respuesta =  0;
			$("#nombreProspectoerror").text("El nombre esta vacio");	
		}
	};
		
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardarContacto = function() {
		if ($("#btnGuardarContacto").attr("accion") == "insertarContacto")
		{
			console.log("insertar");
			//$scope.valida_agregarContacto();
			console.log($scope.correo);
			var domicilio = $scope.domicilioContacto;
			if(!domicilio)
				domicilio = 0;
			var correo = $scope.correo;
			if(!correo)
				correo = " ";
			var telefono = $scope.telefono;
			if(!telefono)
				telefono = " ";
			var puesto = $scope.puesto;
			if(!puesto)
				puesto = " ";
			if($scope.respuesta == 1){
				var contacto = {
					ID_PROSPECTO: $scope.id_prospecto,
					ID_PROSPECTO_DOMICILIO: domicilio,				
	                NOMBRE:$scope.nombre_contacto,
	                CORREO:correo,
					CORREO2:$scope.correo2,
	                TELEFONO:telefono,
	                CELULAR:$scope.celular,
	                PUESTO:puesto,
	                DATOS_ADICIONALES:$scope.datos_adicionales,
					ID_USUARIO_CREACION: sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario"),
					ACTIVO:$scope.contactohabilitado
				};
	
				$.post(global_apiserver + "/prospecto_contacto/insert/", JSON.stringify(contacto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizarContacto").modal("hide");
						notify_success("Éxito", "Se ha insertado un nuevo registro");
						$scope.actualizaTablaContacto();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		else if ($("#btnGuardarContacto").attr("accion") == "editarContacto")
		{
			//$scope.valida_agregarContacto();
			if($scope.respuesta == 1){
				var contacto = {
					ID_CONTACTO:$scope.id_contacto,	
					ID_PROSPECTO_DOMICILIO: $scope.domicilioContacto,			
	                NOMBRE:$scope.nombre_contacto,
	                CORREO:$scope.correo,
					CORREO2:$scope.correo2,
	                TELEFONO:$scope.telefono,
	                CELULAR:$scope.celular,
	                PUESTO:$scope.puesto,
	                DATOS_ADICIONALES:$scope.datos_adicionales,
					ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario"),
					ACTIVO:$scope.contactohabilitado
				};
				$.post( global_apiserver + "/prospecto_contacto/update/", JSON.stringify(contacto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizarContacto").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.actualizaTablaContacto();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		
	};
	$scope.guardarDomicilio = function() {
		if ($("#btnGuardarDomicilio").attr("accion") == "insertarDomicilio")
		{
			//$scope.valida_agregarDomicilio();
			if($scope.respuesta == 1){
				var domicilio = {
					ID_PROSPECTO: $scope.id_prospecto,
                    NOMBRE:$scope.nombre_domicilio,
                    PAIS:$scope.pais,
                    ESTADO:$scope.estado,
                    MUNICIPIO:$scope.municipio,
                    COLONIA: $scope.colonia,
                    CODIGO_POSTAL:$scope.codigo_postal,
                    CALLE:$scope.calle,
                    NUMERO_INTERIOR:$scope.numero_interior,
                    NUMERO_EXTERIOR:$scope.numero_exterior,
                    FISCAL:$scope.fiscalhabilitado,
                    ID_USUARIO_CREACION: sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario"),
					ACTIVO:$scope.domiciliohabilitado

				};
	
				$.post(global_apiserver + "/prospecto_domicilio/insert/", JSON.stringify(domicilio), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizarDomicilio").modal("hide");
						notify_success("Éxito", "Se ha insertado un nuevo registro");
						$scope.actualizaTablaDomicilio();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		else if ($("#btnGuardarDomicilio").attr("accion") == "editarDomicilio")
		{   
			//$scope.valida_agregarDomicilio();
			if($scope.respuesta == 1){
				var domicilio = {
					ID_DOMICILIO:$scope.id_domicilio,
					NOMBRE:$scope.nombre_domicilio,
                    PAIS:$scope.pais,
                    ESTADO:$scope.estado,
                    MUNICIPIO:$scope.municipio,
                    COLONIA: $scope.colonia,
                    CODIGO_POSTAL:$scope.codigo_postal,
                    CALLE:$scope.calle,
                    NUMERO_INTERIOR:$scope.numero_interior,
                    NUMERO_EXTERIOR:$scope.numero_exterior,
                    FISCAL:$scope.fiscalhabilitado,
                    ACTIVO:$scope.domiciliohabilitado,
					ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario")
				};
				$.post( global_apiserver + "/prospecto_domicilio/update/", JSON.stringify(domicilio), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizarDomicilio").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.actualizaTablaDomicilio();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		//$scope.limpiaCamposDomicilio();
		
	};

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//		FUNCION PARA GENERAR REFERENCIA
///////////////////////////////////////////////////////////////////////////////////////////////////////////
	$scope.GenerarReferenciaProspecto =  function() {
	  
		$.getJSON( global_apiserver + "/prospecto/generarReferencia/?id="+$scope.id_prospecto, function( response ) {
			$scope.referencia=response.REFERENCIA;
			$scope.$apply()
       });
		
   };
///////////////////////////////////////////////////////////////////////////////////////////////////////////	
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//		FUNCION PARA OBTENER AREA DE COTIZACION
///////////////////////////////////////////////////////////////////////////////////////////////////////////
	$scope.AreaCotizacion =  function() {
		$.getJSON(  global_apiserver + "/prospecto_producto/getByIdProspecto/?id="+$scope.id_prospecto, function( response ) {
			$scope.area_cotizacion=response[0].ID_AREA;
			$scope.$apply()
       });
			
	 };
///////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$scope.obtenerProspecto =  function() {
	  
		$.getJSON( global_apiserver + "/prospecto/getById/?id="+$scope.id_prospecto, function( response ) {
			$scope.nombre_prospecto=response.NOMBRE;
	        $scope.rfc = response.RFC;
	        $scope.giro=response.GIRO;
	        $scope.comentario=response.COMENTARIO;
	        $scope.origen = response.ORIGEN;
	        $scope.id_cliente=response.ID_CLIENTE;
	        if(response.ACTIVO == 1){
			  $scope.cbhabilitado = true;
		      }else{
			  $scope.cbhabilitado = false;
		      }
			$scope.$apply()
       });
		
   };
   $scope.editarProspecto =  function() {
	    //$(".text-danger").empty();		
		$("#btnGuardarProspecto").attr("accion","editar");
		$("#modalTituloProspecto2").html("Editar Prospecto");
	  
		$.getJSON( global_apiserver + "/prospecto/getById/?id="+$scope.id_prospecto, function( response ) {
			$scope.nombre_prospecto =response.NOMBRE;
	        $scope.rfc = response.RFC;
	        $scope.giro=response.GIRO;
	        $scope.origen = response.ORIGEN;
	        $scope.comentario=response.COMENTARIO;
			
	         if(response.ACTIVO == 1){
			  $scope.cbhabilitado = true;
		      }else{
			  $scope.cbhabilitado = false;
		      }
			$scope.$apply()
       	});
		$("#modalInsertarActualizarProspecto").modal("show");
	};
	$scope.guardarProspecto = function() {		
	     if ($("#btnGuardarProspecto").attr("accion") == "editar")
		{
			$scope.valida_editar();
			if($scope.respuesta == 1){
				var prospecto = {
					ID:$scope.id_prospecto,
					RFC:$scope.rfc,
	                NOMBRE:$scope.nombre_prospecto,
	                GIRO:$scope.giro,
	                COMENTARIO:$scope.comentario,
					ID_USUARIO_MODIFICACION :  sessionStorage.getItem("id_usuario"),
					ACTIVO:$scope.cbhabilitado,
					ORIGEN : $scope.origen
				};
				$.post( global_apiserver + "/prospecto/update/", JSON.stringify(prospecto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizarProspecto").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.obtenerProspecto();
					}
				});
			}
		}
	};

   $scope.detallesProspecto=function(){

		$("#modalTituloProspecto").html("Detalles de Prospecto")
	    $.getJSON(global_apiserver+"/prospecto/getById/?id="+$scope.id_prospecto,function(response){
		$scope.id = response.ID;
			$scope.nombre =response.NOMBRE;
	        $scope.rfc = response.RFC;
	        $scope.giro=response.GIRO;
	        $scope.comentario=response.COMENTARIO;
	        $scope.fecha_creacion=response.FECHA_CREACION;
	        $scope.fecha_modificacion=response.FECHA_MODIFICACION;
	        $scope.id_usuario_creacion=response.USUARIO_CREACION;
	        $scope.id_usuario_modificacion=response.USUARIO_MODIFICACION;
	        $scope.origen = response.ORIGEN;
	        $scope.nombre_origen = response.NOMBRE_ORIGEN;
	        if(response.ACTIVO == 1){
			  $scope.cbhabilitado = true;
		      }else{
			  $scope.cbhabilitado = false;
		      }
			$scope.$apply()
	});
	    $("#modalDetallesProspecto").modal("show");
	};
    $scope.asignarValor=function(){
    	$("#txtNombre").val($scope.nombre_prospecto);
    	$("#txtRfc").val($scope.rfc);
		$("#id_prospecto").val($scope.id_prospecto);
    	listener_btn_nuevo ();
    };

	$scope.cerrarDomicilio= function() {			
		//$("#modalInsertarActualizarDomicilio").modal("hide");
		$(".text-danger").empty();
	};
	$scope.cambioViaticos = function(){
		if($scope.viaticos != 1){
			$scope.mostrar_viaticos_anual = false;
			$scope.mostrar_viaticos_semestral = false;
		}
		else{
			//Aca validar si es semestral o anual la cotizacion
			if($scope.periodicidad_cotizacion == 0){
				$scope.mostrar_viaticos_semestral = true;
			}
			else{
				$scope.mostrar_viaticos_semestral = false;
			}
			$scope.mostrar_viaticos_anual = true;
		}
	}
	$scope.cambioPeriodicidad = function(){
		if($scope.periodicidad_cotizacion == 0){
			$scope.mostrar_semestrales = true;
			if($scope.viaticos == 1){
				$scope.mostrar_viaticos_semestral = true;
			}
		}
		else{
			$scope.mostrar_semestrales = false;
			$scope.mostrar_viaticos_semestral = false;
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Funcion para GENERAR PDF
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$scope.GenerarPDF = function(){
		
		  fill_cmb_contacto();
		  fill_cmb_domicilio();
		$("#modalGenerarPDF").modal("show");	
		
	}
function fill_cmb_contacto(seleccionado){
	$scope.cont_select = 0;
  $("#contactoprospecto1").html('<option value="" selected disabled>-- Selecciona un Contacto--</option>');
  $.getJSON( global_apiserver + "/prospecto_contacto/getAll/?id="+$scope.id_prospecto, function( response ) {
    $.each(response, function( indice, objTserv ) {
      $("#contactoprospecto1").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
    });
    $("#contactoprospecto1").val(seleccionado);
  });
}
function fill_cmb_domicilio(seleccionado){
	$scope.cont_select = 0;
  $("#domicilioprospecto1").html('<option value="" selected disabled>-- Selecciona un Contacto--</option>');
  $.getJSON( global_apiserver + "/prospecto_domicilio/getAll/?id="+$scope.id_prospecto, function( response ) {
    $.each(response, function( indice, objTserv ) {
      $("#domicilioprospecto1").append('<option value="'+objTserv.ID+'">'+objTserv.NOMBRE+'</option>'); 
    });
    $("#domicilioprospecto1").val(seleccionado);
  });
}

	$( "#btnGenerar" ).click(function() {
	if(($("#contactoprospecto1").val()!=null)&&($("#domicilioprospecto1").val()!=null))
	{
		var url = "./generar/pdf/cotizacion/index.php?id_prospecto="+$scope.id_prospecto+"&id_producto="+$scope.id_producto+"&id_contacto="+$("#contactoprospecto1").val()+"&id_domicilio="+$("#domicilioprospecto1").val();
		window.open(url,'_blank');
		$("#modalGenerarPDF").modal("hide");
	}	
	else
		notify("Érror", "Debe elegir un contacto y un domicilio", "error");
 });
//////////////////////////////////////////////////////////////////////////////////////////////////////////////		

	
	function onCalendar()
	{
		$('#fecha_e1').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_e1 = dateText;
    	}
		}).css("display", "inline-block");
		$('#fecha_e2').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_e2 = dateText;
    	}
		}).css("display", "inline-block");
		$('#fecha_v1').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_v1 = dateText;
    	}
		}).css("display", "inline-block");
		$('#fecha_v2').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_v2 = dateText;
    	}
		}).css("display", "inline-block");
		$('#fecha_v3').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_v3 = dateText;
    	}
		}).css("display", "inline-block");
		$('#fecha_v4').datepicker({
      dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_v4 = dateText;
    	}
		}).css("display", "inline-block");
		
		$('#fecha_v5').datepicker({
			dateFormat: "mm/dd/yy",
      minDate: "+0D",
      onSelect: function (dateText, ins) {
        $scope.fecha_v5 = dateText;
    	}
		}).css("display", "inline-block");;
	}

	$scope.OrigenLista();
	$scope.ActualizarAreas();
	
	$scope.actualizaTablaContacto();
	$scope.actualizaTablaDomicilio();
	$scope.actualizarCotizacion();
	$scope.obtenerProspecto();
	$scope.listaDomiciliosForContacto();
	$scope.autocompleteListPais("");
	$scope.autocompleteListCP("");
	$scope.autocompleteListBarrio("","");
	$scope.changeInAutoComplete();
	onCalendar();
	
}]);
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
function listener_btn_nuevo (){
  $("#btnGuardarCliente").attr("accion","insertar");
    $("#modalTituloCliente").html("Insertar nuevo cliente");
    clear_modal_insertar_actualizar();
    $("#modalInsertarActualizarCliente").modal("show");
}
function clear_modal_insertar_actualizar(){
	  $("#txtFecReg").val("");
	  $("#cmbEsFacturario").val("");
	  $("#cmbEsFacturario").change();
	  fill_cmb_tipo_persona("elige");
	  fill_cmb_tipo_entidad("elige");
	  fill_cmb_cliente_factuario("elige");
	  
	  $('#chkRfc').prop('checked', false);
}
function fill_cmb_tipo_persona(seleccionado){
    $.getJSON( global_apiserver + "/tipos_persona/getAll/", function( response ) {
      $("#cmbTPersona").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTPersona ) {
        $("#cmbTPersona").append('<option value="'+objTPersona.ID+'">'+objTPersona.TIPO+'</option>'); 
      });
      $("#cmbTPersona").val(seleccionado);
    });
}
function fill_cmb_tipo_entidad(seleccionado){
    $.getJSON( global_apiserver + "/tipos_entidad/getAll/", function( response ) {
      $("#cmbTEntidad").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $.each(response, function( indice, objTEntidad ) {
        $("#cmbTEntidad").append('<option value="'+objTEntidad.ID+'">'+objTEntidad.TIPO+'</option>'); 
      });
      $("#cmbTEntidad").val(seleccionado);
    });
}




function fill_cmb_cliente_factuario(seleccionado){
    $.getJSON( global_apiserver + "/clientes/getAll/", function( response ) {
      $("#cmbClienteFac").html('<option value="elige" selected disabled>-elige una opción-</option>');
      $("#cmbClienteFac").append('<option value="null">Ninguno</option>');
      $.each(response, function( indice, objClienteFact ) {
        $("#cmbClienteFac").append('<option value="'+objClienteFact.ID+'">'+objClienteFact.RFC+'</option>'); 
      });
      if (seleccionado == null) {
        $("#cmbClienteFac").val("null");
      }
      else
      {
        $("#cmbClienteFac").val(seleccionado);
      }
    });
}
function listener_btn_guardar(){
  $( "#btnGuardarCliente" ).click(function() {
    if ($("#btnGuardarCliente").attr("accion") == "insertar")
    {
      console.log("insertar");
      insertar();
    }
  });
}
function insertar(){
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
        console.log(respuesta);
		respuesta = JSON.parse(respuesta);
		console.log(respuesta.resultado);
        if (respuesta.resultado == "ok") {
        	insertar_domicilio_cliente(respuesta.id);
			$.post( global_apiserver + "/prospecto/updateIdCliente/?ID="+$("#id_prospecto").val()+"&ID_CLIENTE="+respuesta.id, function(respuesta2){
				hide_modal_inserta_actualiza();
				notify("Éxito", "Se ha insertado uno nuevo ", "success");
				$("#btnNuevoCliente").hide();
			});
        }
        else{
          notify("Error", respuesta.mensaje, "error");
        }
    });
}

function insertar_domicilio_cliente(id_cliente){
	var dom_cliente ={
		DOMICILIOS : globla_domicilioprospecto,
		CONTACTOS : globla_contactoprospecto,
		ID_CLIENTE : id_cliente
	}
	$.post(global_apiserver + "/clientes_domicilios/insertFromProspecto/", JSON.stringify(dom_cliente), function(respuesta){
		if (respuesta.resultado == "ok") {
			notify("Éxito", "Se han insertado domicilios con éxio", "success");
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
  function listener_txt_nombre(){
  $('#txtNombre').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
}

  function listener_txt_rfc(){
    $('#txtClienteFacturario').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }
  
  function listener_txt_rfc_cliente(){
    $('#txtRfc').keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }
  
  function listener_txt_rfc_cliente_otro(){
    $("#txtRFCFac").keyup(function(){
      $(this).val($(this).val().toUpperCase());
    });
  }

 function listener_chk_rfc(){
  $("#chkRfc").change(function() {
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

function hide_modal_inserta_actualiza(){
  $("#modalInsertarActualizarCliente").modal("hide");
}
function convertir_fecha(fecha){
	var fecha_aux = new Date(fecha);
	var transformacion = (fecha_aux.getMonth()+1)+"/"+fecha_aux.getDate()+"/"+fecha_aux.getFullYear();
	return transformacion;
}

$( window ).load(function() {     
      //listener_btn_nuevo();
      listener_chk_rfc();
      listener_btn_guardar();
      listener_txt_nombre();
      listener_txt_rfc();
	  listener_txt_rfc_cliente();
	  listener_txt_rfc_cliente_otro();
      listener_cmb_es_facturario();
  });