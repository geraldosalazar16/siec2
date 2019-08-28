/*
	Creación del controlador con el nombre "prospecto_controller".
*/
app.controller('prospecto_controller', ['$scope', '$http', function($scope,$http) { 
	var id_user = sessionStorage.getItem("id_usuario");
	$scope.filtros=getQueryVariable("ids");
	//Titulo que aparece en el html
	$scope.titulo = 'Prospectos';
	$scope.modulo_permisos =  global_permisos["CRM"];
	$scope.permiso_editar = $scope.modulo_permisos["editar"];
	//Variables para habilitar y deshabilitar controles
	$scope.habilitar_origen = true;
	$scope.habilitar_tipo_contrato = true;
	$scope.habilitar_departamento = true;
	$scope.habilitar_tipo_servicio = true;	
	
	if($scope.modulo_permisos["asignar_prospecto"] == 1){
		$scope.mostrarUsuarioPrincipal = true;
	}
	else{
		$scope.mostrarUsuarioPrincipal = false;
	}
	/*
		$scope.ID : id del prospecto. Solo se usa cuando se edita ya que 
					cuando queremos agregar	ponemos el id como 0.
	*/	
	$scope.nombre = "";
	$scope.rfc = "";
	$scope.giro="";
	$scope.id = 0;
	$scope.fecha_creacion;
	$scope.fecha_modicacion;
	$scope.id_usuario_creacion;
	$scope.id_usuario_modicacion;
	$scope.cbhabilitado=true;
	$scope.origen = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	//Control de los botones del prospecto seleccionado
	$scope.prospecto_seleccionado = 0;
	$scope.mostrar_editar_seleccionado = false;
	$scope.mostrar_detalles_seleccionado = false;
	$scope.mostrar_perfil_seleccionado = false;
	$scope.mostrar_expedientes_seleccionado = false;
	$scope.id_new_prospecto = null;
	$scope.accion = null;
	$scope.listaDomicilios = {};
	$scope.count_domicilios = 0;

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
		Función para actualizar la tabla con los creacions en la BD.
	*/

	$scope.actualizaTabla = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user)
		//$http.get(  global_apiserver + "/prospecto/getAll/")
			  .then(function( response ) {//se ejecuta cuando la petición fue correcta				
				$scope.cantidad_prospectos = response.data.length;
	  			$scope.Prospectos = response.data.map(function(item){
	  				return{
	  					ID : item.ID,
						NOMBRE : item.NOMBRE,
						ID_ESTATUS_SEGUIMIENTO: item.ID_ESTATUS_SEGUIMIENTO
	  				}
	  			});
			},
			function (response){});
		var url = global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user;
		if($scope.filtros)
		{
			url = global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user+"&ids="+$scope.filtros;
		}
		$.ajax({
			type:'GET',
			url:url,
			success: function(data){				
				$scope.$apply(function(){
					$scope.prospecto = angular.fromJson(data);
				});
			}
		});
	};
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCampos = function(){
		$scope.nombre = "";
	    $("#rfc").val("");
	    $scope.giro="";
	    $scope.id = 0;
	    $scope.origen = 0;
		$scope.estatus_seguimiento = 0;
		$scope.tipo_contrato = 0; 
		$scope.competencia = 0;
		$scope.tipo_servicio = 0; 
		$scope.departamentos  = 0;
		
		if($scope.modulo_permisos["asignar_prospecto"] == 1){
			$scope.mostrarUsuarioPrincipal = true;
		}
		else{
			$scope.mostrarUsuarioPrincipal = false;
			$scope.usuariosP = id_user;
		}
		$scope.id_new_prospecto = null;
		$scope.autocompleteListPais("");

	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {
	    $scope.limpiaCampos();	
		$scope.habilitar_origen = true;
		$scope.habilitar_tipo_contrato = true;
		$scope.habilitar_departamento = true;
		$scope.habilitar_tipo_servicio = true;	

		//$scope.usuarios_principal = sessionStorage.getItem("id_usuario");
		
		$("#btnGuardar").attr("accion","insertar");
		$scope.accion = "insertar";
		$scope.modal_title = "Agregar prospecto";
		//$("#nombre").attr("readonly",false);

		listener_txt_nombre();
      	listener_txt_rfc();
		console.log($("#ww"));
		$("#stepSegundo").hide();
		$("#stepTercero").hide();
		$("#stepPrimero").show();
		$("#modalInsertarActualizar").modal("show");

	};
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

  	$scope.º = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_origen/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Origenes = response.data.map(function(item){
	  				return{
	  					id_origen : item.ID,
	  					origen : item.DESCRIPCION
	  				}
	  			});
	  			
			},
			function (response){});
	}
		$scope.OrigenLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_origen/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Origenes = response.data.map(function(item){
	  				return{
	  					id_origen : item.ID,
	  					origen : item.DESCRIPCION
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.CompetenciaLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_competencia/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Competencia = response.data.map(function(item){
	  				return{
	  					id_competencia : item.ID,
	  					competencia : item.DESCRIPCION
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.cambioFiltroStatus = function(){
		var estatus = $scope.filtroEstatus;
		//$scope.prospectos_total = $scope.prospecto;
	$scope.prospectos_total = $scope.prospecto;$scope.prospectos_total = $scope.prospecto;	if($scope.prospectos_total){
			if($scope.prospectos_total.length == 0){
				$scope.prospectos_total = $scope.prospecto;
			}						
		} else {
			$scope.prospectos_total = $scope.prospecto;
		}		
		$scope.prospecto = [];
		$scope.prospectos_total.forEach(prospecto => {
			if(prospecto.ID_ESTATUS_SEGUIMIENTO == estatus){				
				$scope.prospecto.push(prospecto);
			}
		});
		$scope.cantidad_prospectos = $scope.prospecto.length;
	}
	$scope.EstatusSeguimientoLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_estatus_seguimiento/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Estatus_seguimiento = response.data.map(function(item){
	  				return{
	  					id_estatus_seguimiento : item.ID,
	  					estatus_seguimiento : item.DESCRIPCION
	  				}
	  			});
	  			
			},
			function (response){});
	}
	
	$scope.UsuariosLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/usuarios/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Usuarios = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					descripcion : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.UsuariosPrincipalLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/usuarios/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Usuarios_principal = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					descripcion : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	
	$scope.TipoServicioLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/tipos_servicio/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.TiposServicio = response.data.map(function(item){
	  				return{
	  					id_tipo_servicio : item.ID,
	  					tipo_servicio : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	
	
	
	$scope.TipoContratoLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_tipo_contrato/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Tipo_contrato = response.data.map(function(item){
	  				return{
	  					id_tipo_contrato : item.ID,
	  					tipo_contrato : item.DESCRIPCION
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
		$.getJSON( global_apiserver + "/prospecto_domicilio/getAll/?id="+$scope.id_new_prospecto , function( response ) {
			$scope.listaDomicilios = response;
			$scope.$apply();
		});
	}

	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del creacion que se va a obtener para cambiar los valores en el módelo.
		
	*/
	$scope.detalles=function(id_prospecto){

		$("#modalTitulo").html("Detalles de Prospecto")
	    $.getJSON(global_apiserver+"/prospecto/getByIdTipoServicioUsuario/?id="+id_prospecto,function(response){
			$scope.id = response.ID;
			$scope.nombre =response.NOMBRE;
			$scope.rfc =response.RFC;
	        $scope.giro=response.GIRO;
	        $scope.fecha_creacion=response.FECHA_CREACION;
	        $scope.fecha_modificacion=response.FECHA_MODIFICACION;
	        $scope.id_usuario_creacion=response.USUARIO_CREACION;
	        $scope.id_usuario_modificacion=response.USUARIO_MODIFICACION;
	        $scope.origen = response.ORIGEN;
	        $scope.nombre_origen = response.NOMBRE_ORIGEN;
			$scope.nombre_competencia = response.NOMBRE_COMPETENCIA;
			$scope.nombre_estatus_seguimiento = response.NOMBRE_ESTATUS_SEGUIMIENTO;
			$scope.nombre_tipo_contrato = response.NOMBRE_TIPO_CONTRATO;
			$scope.tipo_servicio = response.ID_TIPO_SERVICIO;
			$scope.usuarios = response.ID_USUARIO_SECUNDARIO;
	        if(response.ACTIVO == 1){
			  $scope.cbhabilitado = true;
		      }else{
			  $scope.cbhabilitado = false;
		      }
			$scope.$apply()
		});
	    $("#modalDetalles").modal("show");
	};
	$scope.editar =  function(id_prospecto) {
		$scope.limpiaCampos();
	    $("#nombreerror").text("");			
		$("#btnGuardar").attr("accion","editar");
		$scope.accion = "editar";
		$scope.modal_title = "Editar Prospecto";
		listener_txt_nombre();
      	listener_txt_rfc();
	  
		$.getJSON( global_apiserver + "/prospecto/getByIdTipoServicioUsuario/?id="+id_prospecto, function( response ) {
			
			$scope.habilitar_origen = false;
			$scope.habilitar_tipo_contrato = false;
			$scope.habilitar_departamento = false;
			$scope.habilitar_tipo_servicio = false;	
			
			$scope.id = response.ID;
			$scope.nombre =response.NOMBRE;
	       	$("#rfc").val(response.RFC);
	        $scope.giro=response.GIRO;
	        $scope.origen = response.ORIGEN;
			$scope.competencia = response.ID_COMPETENCIA;
			$scope.estatus_seguimiento = response.ID_ESTATUS_SEGUIMIENTO;
			$scope.tipo_contrato = response.ID_TIPO_CONTRATO;
			$scope.usuarios = response.ID_USUARIO_SECUNDARIO;
			$scope.usuariosP = response.ID_USUARIO_PRINCIPAL;
	         if(response.ACTIVO == 1){
			  $scope.cbhabilitado = true;
		      }else{
			  $scope.cbhabilitado = false;
		      }
			$scope.$apply()
       });
		$("#stepSegundo").hide();
		$("#stepTercero").hide();
		$("#stepPrimero").show();
		$("#modalInsertarActualizar").modal("show");
    
	};
	function validarTipoUsuario(){
		
	}
	/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
	*/
	$scope.cerrar = function() {		
		$("#nombreerror").text("");		
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
	};
	$scope.cerrarDetalle = function() {		
		$("#nombreerror").text("");		
		$scope.limpiaCampos();
		$("#modalDetalles").modal("hide");
		
	};
	/*
		Valida si la información que tiene el módelo es suficiente para agregar
		el nuevo creacion. Aquí se modifica el valor de "$scope.respuesta" para checar
		la validez del módelo.
		Primero se verifica que los campos no sean nulos y en el caso del nombre
		se verifica que no se repita.
		Además se muestra el error conrrespondiente en las etiquetas con los
		id "nombreerror" y "descripcionerror".
	*/
	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.nombre.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/prospecto/getByVentaPlanReal/?nombre="+$scope.nombre,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta=0;	
						$("#nombreerror").text("Nombre ya registrado");						
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		}else{
			$scope.respuesta =  0;
			$("#nombreerror").text("No debe estar vacio");
		}
	}
	
	/*
		Se checa si es válida la modificación. Solo tomamos en cuenta la descripción
		ya que el nombre no se puede modificar. Con el id "descripcionerror" mostramos
		el error correspondiente.
	*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;		
		if($scope.nombre.length == 0){
			$scope.respuesta =  0;
			$("#nombreerror").text("Ingresa un nombre");	
		}else{
			$("#nombreerror").text("");	
		}
	}
// ================================================================================
// *****                  Funcion Mostrar/Ocultar elementos                   *****
// ================================================================================
	$scope.mytoggle = function (id)
	{
		$("#"+id).toggle(function(){

		},function(){

		});
	}
// ================================================================================
// *****            Funcion limpiar campos formulario domicilio               *****
// ================================================================================
$scope.limpiaCamposDomicilio = function(){
		$scope.id_domicilio=0;
		$scope.nombre_domicilio="";
		$scope.pais="";
		$scope.estado="";
		$scope.municipio="";
		$scope.colonia="";
		$scope.codigo_postal="";
		$scope.calle="";
		$scope.numero_interior="";
		$scope.numero_exterior="";
		$scope.fiscal=0;
		$scope.chkColonia = false;
		$scope.fiscalhabilitado = false;
		$scope.domiciliohabilitado = false;
	    $scope.autocompleteListPais("");
		$scope.$apply();

		$("#campoNuevaColonia").hide();
		$("#campoSelectColonia").show();
		$("#auxColonia").hide();

	};
// ================================================================================
// *****            Funcion limpiar campos formulario contacto               *****
// ================================================================================
	$scope.limpiaCamposContacto = function(flag){
		$scope.id_contacto=0;
		$scope.domicilioContacto = "";
		$scope.nombre_contacto="";
		$scope.correo="";
		$scope.telefono="";
		$scope.puesto="";
		$scope.celular="";
		$scope.contactohabilitado = false;
		if(flag){$scope.$apply();}

	};
// ================================================================================
// *****              Funcion abrir modal agregar domicilio                   *****
// ================================================================================
	$scope.openDomicilio = function()
	{

		$scope.modal_title = "Agregar Domicilio";
		$(".text-danger").empty();
		$scope.limpiaCamposDomicilio();
		$("#btnGuardarDomicilio").attr("accion","insertarDomicilio");
		$scope.autocompleteListPais("");
		$scope.autocompleteListCP("");
		$scope.autocompleteListBarrio("","");
		$scope.changeInAutoComplete();
		$scope.$apply();
		$scope.count_domicilios = 0;

		this.mytoggle('stepPrimero');
		this.mytoggle('stepSegundo');
	}

// ================================================================================
// *****              Funcion abrir modal agregar domicilio                   *****
// ================================================================================
	$scope.openContactos = function(flag)
	{
		$scope.modal_title = "Agregar Contactos";
		$(".text-danger").empty();
		$scope.limpiaCamposContacto(flag);
		$(".text-danger").empty();
		$("#btnGuardarContacto").attr("accion","insertar");
		$scope.listaDomiciliosForContacto();

		this.mytoggle('stepSegundo');
		this.mytoggle('stepTercero');
	}

	 $scope.redireccionar = function()
	{
		location.href = "./?pagina=perfilprospecto&id="+$scope.id_new_prospecto+"&entidad=1";
	}
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardar = function() {
		if ($("#btnGuardar").attr("accion") == "insertar")
		{
			$scope.valida_agregar();
            var value_rfc = $("#rfc").val();
            value_rfc = value_rfc.replace("_","");
            value_rfc = value_rfc.trim();
            var tipo_persona = "";
            if(value_rfc.length == 12)
                tipo_persona = "Moral";
            if(value_rfc.length == 13)
                tipo_persona = "Física";

			if($scope.respuesta == 1){
				var prospecto = {
					RFC:$("#rfc").val(),
	                NOMBRE:$scope.nombre,
	                GIRO:$scope.giro,
					ID_USUARIO_CREACION: sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION: sessionStorage.getItem("id_usuario"),
					ID_CLIENTE:0,
					ACTIVO:$scope.cbhabilitado? 1 : 0,
					ORIGEN : $scope.origen,
					COMPETENCIA: $scope.competencia,
					ESTATUS_SEGUIMIENTO : $scope.estatus_seguimiento,
					TIPO_CONTRATO : $scope.tipo_contrato,
					TIPO_SERVICIO:$scope.tipo_servicio,
					ID_USUARIO:$scope.usuariosP,
					ID_USUARIO_SECUNDARIO:$scope.usuarios,
					DEPARTAMENTO:$scope.departamentos,
                    TIPO_PERSONA:tipo_persona
				};
				$.post(global_apiserver + "/prospecto/insert/", JSON.stringify(prospecto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$scope.id_new_prospecto = respuesta.id;
							notify_success("Éxito", "Se ha insertado un nuevo prospecto");
							$scope.openDomicilio();
						$scope.actualizaTabla();
					}
					else{
					  notify_success("Error", respuesta.mensaje, "error");
					}
			
				});
			}
		}
		else if ($("#btnGuardar").attr("accion") == "editar")
		{
			$scope.valida_editar();
            var value_rfc = $("#rfc").val();
            value_rfc = value_rfc.replace("_","");
            value_rfc = value_rfc.trim();
            var tipo_persona = "";
            if(value_rfc.length == 12)
                tipo_persona = "Moral";
            if(value_rfc.length == 13)
                tipo_persona = "Física";
			if($scope.respuesta == 1){
				var prospecto = {
					ID:$scope.id,
					RFC:$("#rfc").val(),
	                NOMBRE:$scope.nombre,
	                GIRO:$scope.giro,
					ID_USUARIO_MODIFICACION :  sessionStorage.getItem("id_usuario"),
					ACTIVO:$scope.cbhabilitado? 1 : 0,
					ORIGEN : $scope.origen,
					COMPETENCIA: $scope.competencia,
					ESTATUS_SEGUIMIENTO : $scope.estatus_seguimiento,
					TIPO_CONTRATO : $scope.tipo_contrato,
					ID_USUARIO:$scope.usuariosP,
					USUARIO_SECUNDARIO: $scope.usuarios,
                    TIPO_PERSONA:tipo_persona
				};
				$.post( global_apiserver + "/prospecto/update/", JSON.stringify(prospecto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.actualizaTabla();
					}
					 else{
					  notify_success("Error", respuesta.mensaje, "error");
					}
				});
			}
		}
		$("#modalInsertarActualizar").modal("hide");
		$scope.actualizaTabla();
	};
// ================================================================================
// *****                      Funcion guardar domicilios                   *****
// ================================================================================
	$scope.guardarDomicilio = function() {
		if ($("#btnGuardarDomicilio").attr("accion") == "insertarDomicilio")
		{
			//$scope.valida_agregarDomicilio();
			if($scope.respuesta == 1){
				var domicilio = {
					ID_PROSPECTO: $scope.id_new_prospecto,
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
						    $scope.count_domicilios++;
							$scope.limpiaCamposDomicilio();
							notify_success("Éxito", "Se ha insertado un nuevo domicilio");
					}
					else{
						notify_success("Error", respuesta.mensaje, "error");
					}
				});
			}
		}



	};

// ================================================================================
// *****                      Funcion guardar contactos                   *****
// ================================================================================
	$scope.guardarContacto = function(flag) {
		if ($("#btnGuardarContacto").attr("accion") == "insertar")
		{
			var domicilio = $scope.domicilioContacto;
			if(!domicilio)
				domicilio = 0;
			var correo = $scope.correo;
			if(!correo)
				correo = "";
			var telefono = $scope.telefono;
			if(!telefono)
				telefono = "";
			var puesto = $scope.puesto;
			if(!puesto)
				puesto = "";
			if($scope.respuesta == 1){
				var contacto = {
					ID_PROSPECTO: $scope.id_new_prospecto,
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

							$scope.limpiaCamposContacto(true);

						notify_success("Éxito", "Se ha insertado un nuevo Contacto");

					}
					else{
						notify_success("Error", respuesta.mensaje, "error");
					}
				});
			}
		}


	};

	$('.select2_single').select2({});
	$('#cmbProspectos').on('select2:select', function (evt) {
		var prospecto = $("#cmbProspectos").val().substring(7);
		if(prospecto !== "elige"){
			$scope.prospecto_seleccionado = prospecto;
			$scope.mostrar_editar_seleccionado = true;
			$scope.mostrar_detalles_seleccionado = true;
			$scope.mostrar_perfil_seleccionado = true;
			$scope.mostrar_expedientes_seleccionado = true;			
		}
		else{
			$scope.prospecto_seleccionado = 0;
			$scope.mostrar_editar_seleccionado = false;
			$scope.mostrar_detalles_seleccionado = false;
			$scope.mostrar_perfil_seleccionado = false;
			$scope.mostrar_expedientes_seleccionado = false;
		}
		$scope.$apply();
	});
	 // ==================================================================
    // ***** 	Función para traer las claves de servicio.			*****
    // ==================================================================
    function cargarServicios() {
        $http.get(global_apiserver + "/servicios/getAll/")
            .then(function(response) {
                $scope.Servicios = response.data;
            });
    }
	// ==========================================================
    // ***** 	Funcion para traer los tipos de Servicios 	*****
    // ==========================================================
    function cargartipoServicio() {
        $http.get(global_apiserver + "/tipos_servicio/getAll/")
            .then(function(response) {
                $scope.TiposServicios = response.data;
            });
    }
	$scope.cambioFiltro = function(){
		var servicio = $scope.filtroServicios;
		var tipo_servicio = $scope.filtroTiposServicio;
		var estatus = $scope.filtroEstatus;
		
		if($scope.prospectos_total){
			if($scope.prospectos_total.length == 0){
				$scope.prospectos_total = $scope.prospecto;
				
			}	
			$scope.prospecto = [];			
		} else {
			$scope.prospectos_total = $scope.prospecto;
			$scope.prospecto = [];
		}
		//$scope.prospectos_total = $scope.prospecto;
		
		if(estatus){
			
			$scope.prospectos_total.forEach(prospecto => {
				if(prospecto.ID_ESTATUS_SEGUIMIENTO == estatus){				
					$scope.prospecto.push(prospecto);
				}
			});
		}		
		//$scope.prospecto = [];
		if(estatus){
			$scope.prospectos_total1 = $scope.prospecto;
			$scope.prospecto = [];
		}
		else{
			if($scope.prospectos_total1){
				if($scope.prospectos_total1.length == 0){
					$scope.prospectos_total1 = $scope.prospectos_total;
					
				}	
				$scope.prospecto = [];				
			} else {
				$scope.prospectos_total1 = $scope.prospectos_total;
				$scope.prospecto = [];
			}	
		}
		
		if(servicio){
			$scope.prospectos_total1.forEach(prospecto => {
				if(prospecto.PRODUCTOS !="No tiene Productos"){
					prospecto.PRODUCTOS.every(function(producto,index){
						if(producto.ID_SERVICIO == servicio){
							$scope.prospecto.push(prospecto);
							return false;
						}
						else{
							return true;
						}
					});
				
				}
		
			
			});
		}
		if(estatus||servicio){
			$scope.prospectos_total2 = $scope.prospecto;
			$scope.prospecto = [];
		}
		else{
			if($scope.prospectos_total2){
				if($scope.prospectos_total2.length == 0){
					$scope.prospectos_total2 = $scope.prospectos_total1;
					
				}	
				$scope.prospecto = [];				
			} else {
				$scope.prospectos_total2 = $scope.prospectos_total1;
				$scope.prospecto = [];
			}	
		}
		if(tipo_servicio){
			$scope.prospectos_total2.forEach(prospecto => {
				if(prospecto.PRODUCTOS !="No tiene Productos"){
					prospecto.PRODUCTOS.every(function(producto,index){
						if(producto.ID_TIPO_SERVICIO == tipo_servicio){
							$scope.prospecto.push(prospecto);
							return false;
						}
						else{
							return true;
						}
					});
				
				}
		
			
			});
		}
		$scope.cantidad_prospectos = $scope.prospecto.length;
	}
	cargarServicios();
	cargartipoServicio();
	$scope.OrigenLista();
	$scope.CompetenciaLista();
	$scope.EstatusSeguimientoLista();
	$scope.TipoServicioLista();
	$scope.UsuariosLista();
	$scope.UsuariosPrincipalLista();
	$scope.TipoContratoLista();
	$scope.DepartamentosLista();
	$scope.actualizaTabla();
	
}]);

	/*
		Función que recibe el título y el texto de un cuadro de notificación.
	*/
	function notify_success(titulo, texto) {
		new PNotify({
			title: titulo,
			text: texto,
			type: 'success',
			nonblock: {
				nonblock: true,
				nonblock_opacity: .2
			},
			delay: 2500
		});
	}

function getQueryVariable(variable) {
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			return pair[1];
		}
	}
	console.log('Query Variable ' + variable + ' not found');
}
