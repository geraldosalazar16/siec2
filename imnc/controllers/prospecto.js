/*
	Creación del controlador con el nombre "prospecto_controller".
*/
app.controller('prospecto_controller', ['$scope', '$http', function($scope,$http) { 
	var id_user = sessionStorage.getItem("id_usuario");
	
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
	
	/*
		Función para actualizar la tabla con los creacions en la BD.
	*/
	$(".select2_single").select2({});
	$scope.actualizaTabla = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user)
		//$http.get(  global_apiserver + "/prospecto/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
				$scope.cantidad_prospectos = response.data.length;
	  			$scope.Prospectos = response.data.map(function(item){
	  				return{
	  					ID : item.ID,
	  					NOMBRE : item.NOMBRE
	  				}
	  			});
			},
			function (response){});
		//url:global_apiserver + "/prospecto/getAll/",	
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user, 
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
		$("#modalTitulo").html("Crear prospecto");
		//$("#nombre").attr("readonly",false);
		listener_txt_nombre();
      	listener_txt_rfc();
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
		$("#modalTitulo").html("Editar Prospecto");
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
		Valida si la información que tiene el módelo es suficiente apra agregar
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
				url:global_apiserver + "/prospecto/getByNombre/?nombre="+$scope.nombre,
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
		
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardar = function() {		
		if ($("#btnGuardar").attr("accion") == "insertar")
		{
			$scope.valida_agregar();
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
					//ID_USUARIO:sessionStorage.getItem("id_usuario"),
					ID_USUARIO_SECUNDARIO:$scope.usuarios,
					DEPARTAMENTO:$scope.departamentos
				};
				console.log(prospecto);
				$.post(global_apiserver + "/prospecto/insert/", JSON.stringify(prospecto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se ha insertado un nuevo registro");
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
					USUARIO_SECUNDARIO: $scope.usuarios
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
		$scope.actualizaTabla();
	};
	$('.select2_single').on('select2:select', function (evt) {
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

