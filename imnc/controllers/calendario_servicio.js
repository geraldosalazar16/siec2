var globla_domicilioprospecto;
var globla_contactoprospecto;
app.controller('calendario_servicios_controller', ['$scope', '$http', function($scope, $http) { 
	//Titulo que aparece en el html
	$scope.modulo_permisos =  global_permisos["CRM"]; //Esta variable global_permisos se imprime en el index
    var id_serv_cli_et =getQueryVariable("id_serv_cli_et");
	var serv_cli_et = {}; //Para almacenar el los datos del registro
	
	$.getJSON( global_apiserver + "/servicio_cliente_etapa/getById/?id="+id_serv_cli_et, function( response ) {
		   $scope.nombre_cliente =response.NOMBRE_CLIENTE;
		   $scope.tipo_servicio = response.NOMBRE_SERVICIO;
		   serv_cli_et = response;
		});
	/*
		Manejo de Contacto prospecto
	
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
	*/
    /*
		Manejo de Domicilios prospecto
	
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
	*/


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
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
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
	*/
	/*
	$scope.listaDomiciliosForContacto = function (){
	  $.getJSON( global_apiserver + "/prospecto_domicilio/getAll/?id="+$scope.id_prospecto , function( response ) {
	  	$scope.listaDomicilios = response;
	  });
	}
	*/
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
	/*
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
	*/
	/*
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
	*/
	/*
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
	/*
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
        //$scope.autocompleteListPais("");
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
            //$scope.autocompleteListPais(response.PAIS);
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
			if($scope.respuesta == 1){
				var contacto = {
					ID_PROSPECTO_DOMICILIO: $scope.domicilioContacto,				
	                NOMBRE:$scope.nombre_contacto,
	                CORREO:$scope.correo,
	                TELEFONO:$scope.telefono,
	                CELULAR:$scope.celular,
	                PUESTO:$scope.puesto,
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
						//$scope.actualizaTablaDomicilio();
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
						//$scope.actualizaTablaDomicilio();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		//$scope.limpiaCamposDomicilio();
		
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
	//$scope.OrigenLista();
	$scope.actualizaTablaContacto();
	//$scope.actualizaTablaDomicilio();
    //$scope.obtenerProspecto();
	//$scope.listaDomiciliosForContacto();
	//$scope.autocompleteListPais("");
	//$scope.autocompleteListCP("");
	//$scope.autocompleteListBarrio("","");
	//$scope.changeInAutoComplete();
	
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