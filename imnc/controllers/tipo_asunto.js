app.controller('tipo_asunto_controller',['$scope',function($scope){

	$scope.titulo = 'Tipo de asunto';
	$scope.form = {}; //objeto para guardar los datos del formulario	
	$scope.accion = "";
	$scope.respuesta = 1;
	$scope.modulo_permisos =  global_permisos["CRM"];

	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/cita_calendario/getTipoAsunto/",
			success: function(data){
				$scope.$apply(function(){
					$scope.tipoasunto = angular.fromJson(data);
					$(".colorcit").hide();
				});
			}
		});

	};

	$scope.limpiaCampos = function(){
		$scope.form.id_tipo_asunto = 0;
     	$scope.form.descripcion = "";
     	$scope.form.color="";
	};


	$scope.agregar = function() {
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar tipo de asunto");
		$("#descripcion").attr("readonly",false);
		$("#modalInsertarActualizar").modal("show");
		$scope.limpiaCampos();
		
	};

	$scope.editar =  function(id_tipo_asunto) {		//función que tenrá el botón de editar
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Tipo de Asunto");
		$("#descripcion").attr("readonly",true);
		$("#descripcionerror").text("");
		$("#colorerror").text("");
	  
		$.getJSON( global_apiserver + "/tipo_asunto/getById/?id="+id_tipo_asunto, function( response ) {
			$scope.form.id_tipo_asunto = response.id_tipo_asunto;
			$scope.form.descripcion = response.descripcion;
			$scope.form.color = response.color;

			
			$scope.$apply(); 
       });
		$("#modalInsertarActualizar").modal("show");
    
	};

	$scope.cerrar = function() {		
		$("#nombreerror").text("");		
		$("#descripcionerror").text("");
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
	};


	$scope.valida_agregar = function(){
		$scope.respuesta = 1;

		if($scope.form.descripcion){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/tipo_asunto/getByDescripcion/?desc="+$scope.form.descripcion,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						$("#descripcionerror").text("Descripcion del asunto ya existe");						
					}else{
						$("#descripcionerror").text("");
					}
				}
			});
		}else{
			$scope.respuesta =  0;
			$("#descripcionerror").text("No debe estar vacio");
		}
		if(!$scope.form.color){
			$scope.respuesta =  0;
			$("#colorerror").text("No debe estar vacio");
		}else{
			$("#colorerror").text("");
		}
	}

	$scope.valida_editar = function(){
		$scope.respuesta = 1;		
		if($scope.form.color.length == 0){
			$scope.respuesta =  0;
			$("#colorerror").text("No debe estar vacio");
		}else{
			$("#colorerror").text("");
		}


	}


	$scope.guardar = function() {		
		if ($("#btnGuardar").attr("accion") == "insertar")
		{ 
			$scope.valida_agregar();
			if($scope.respuesta == 1){
			var tipo_asunto = formToPHPCREATE();
	         
				$.post(global_apiserver + "/tipo_asunto/insert/", JSON.stringify(tipo_asunto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se ha insertado un nuevo registro");
						$scope.actualizaTabla();
						$scope.limpiaCampos();
					}
			
				});
			}
		}
		else if ($("#btnGuardar").attr("accion") == "editar")
		{
			$scope.valida_editar();
			if($scope.respuesta == 1){
				var tipo_asunto = formToPHPEDIT(); //convirtiendo los datos a json
				$.post( global_apiserver + "/tipo_asunto/update/", JSON.stringify(tipo_asunto), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$scope.limpiaCampos();
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.actualizaTabla();
						$scope.limpiaCampos();
					}
				});
			}
		}
		
	};	

	function formToPHPCREATE(){ //obteniendo datos del formulario y se pasan a un json (mismo nombre que en el index.php)
		
		var tipo_asunto = {
				descripcion : $scope.form.descripcion,
				color : $scope.form.color,				
	  			id_usuario_registro : 0,
	  			id_usuario_modificacion : 0
	  			
			};
		return tipo_asunto;
	}

	function formToPHPEDIT(){ //obteniendo datos del formulario y se pasan a un json (mismo nombre que en el index.php)
		var tipo_asunto = {
				id_tipo_asunto : $scope.form.id_tipo_asunto,
				descripcion : $scope.form.descripcion,
				color : $scope.form.color,
				id_usuario_modificacion : 0	
			};
		return tipo_asunto;
	}


$scope.actualizaTabla();

}]);


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

