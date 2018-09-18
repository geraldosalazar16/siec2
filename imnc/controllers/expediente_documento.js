/*
	Creación del controlador con el nombre "tipo_documento_controller".
*/
app.controller('expediente_documento_controller', ['$scope',function($scope) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Expediente Documento';
	$scope.modulo_permisos =  global_permisos["EXPEDIENTES"];
	/*
		Los siguiente 4 campos se usan para el modelo de expediente_documento.
		$scope.selectDocumento : tipo de documento seleccionado en las opciones
					del tag select.
		$scope.cbobligatorio : true o false, para el campo obligatorio.
		$scope.cbhabilitado : true o false para el campo habilitado.
		$scope.id : id del tipo de documento.
		$scope.tipo_documento_texto : almacena el nombre de tipo de documento.
	*/
	$scope.selectDocumento = 0;
	$scope.cbobligatorio =true;
	$scope.cbhabilitado=true;
	$scope.id = 0;
	$scope.tipo_documento_texto= "";
	
	/*
		"$scope.id_expediente" guarda el id del expediente que entramos.
	*/	
	$scope.id_expediente = getQueryVariable("id_expediente");
	
	$scope.finalizado = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	  
	$scope.actualizaTabla = function(expediente_id){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/ex_expediente_documento/getAllByExpedienteId/?id="+$scope.id_expediente,
			success: function(data){
				$scope.$apply(function(){
					$scope.expedientedocumento = angular.fromJson(data);
				});
			}
		});
	};
	
	$scope.FillSelect = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/ex_expediente_documento/getDocumentosForSelect/?id="+$scope.id_expediente,
			success: function(data){
				$scope.$apply(function(){
					$scope.selectlistdocumento = angular.fromJson(data);					
				});				
				$('#tipo_documento option:selected').text('Selecciona un tipo de documento');
				$('#tipo_documento option:selected').attr('value','0');
			}
		});
	};
	
	$scope.limpiaCampos = function(){
		$scope.selectDocumento = 0;
		$scope.cbobligatorio =true;
		$scope.cbhabilitado=true;
		$scope.tipo_documento_texto= "";
		$scope.id=0;
	};
  
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar tipo de documento");
		$scope.selectStyle = {"display":"block"};
		$scope.tipoDocumentoStyle = {"display":"none"};
		$scope.FillSelect();
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("show");
		
		
	};
	
	$scope.editar =  function(expediente_documento_id) {		
	  $("#btnGuardar").attr("accion","editar");
	  $("#modalTitulo").html("Editar Tipo de Documento");
	  $scope.selectStyle = {"display":"none"};
	  $scope.selectTextStyle = {"display":"none"};
	  $scope.tipoDocumentoStyle = {"display":"block"};
	  
	 $.getJSON( global_apiserver + "/ex_expediente_documento/getById/?id="+expediente_documento_id, function( response ) {
		  $scope.id = response.ID;
		  $scope.tipo_documento_texto = response.NOMBRE;
		  if(response.HABILITADO == 1){
			  $scope.cbhabilitado = true;
		  }else{
			  $scope.cbhabilitado = false;
		  }
		  if(response.OBLIGATORIO == 1){
			  $scope.cbobligatorio = true;
		  }else{
			  $scope.cbobligatorio = false;
		  }
		  $scope.$apply();
	   });
	   
	  $("#modalInsertarActualizar").modal("show");
	
	};
	
	$scope.cerrar = function() {		
		$("#documentoerror").text("");	
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
	};
	
	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.selectDocumento == 0){			
			$scope.respuesta =  0;
			$("#documentoerror").text("Debes seleccionar un tipo de documento");
		}else{
			$("#documentoerror").text("");
		}
		
	}
	
	$scope.guardar = function() {
		if ($("#btnGuardar").attr("accion") == "insertar")
		{
			$scope.valida_agregar();
			if($scope.respuesta == 1){
			var expediente_documento = {
				ID:0,
				ID_EXPEDIENTE:$scope.id_expediente,
				ID_DOCUMENTO: $scope.selectDocumento,
				OBLIGATORIO:$scope.cbobligatorio,
				HABILITADO:$scope.cbhabilitado,
				ID_USUARIO_CREACION:0,
				ID_USUARIO_MODIFICACION:0				
			};
			$.post(global_apiserver + "/ex_expediente_documento/insert/", JSON.stringify(expediente_documento), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se ha insertado un nuevo registro");
					$scope.actualizaTabla();
				}
		
			});
		}
		}
		else if ($("#btnGuardar").attr("accion") == "editar")
		{	
			var expediente_documento = {
				ID:$scope.id,
				ID_EXPEDIENTE:$scope.id_expediente,
				OBLIGATORIO:$scope.cbobligatorio,
				HABILITADO:$scope.cbhabilitado,
				ID_USUARIO_MODIFICACION:0			
			};
			$.post( global_apiserver + "/ex_expediente_documento/update/", JSON.stringify(expediente_documento), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se han actualizado los datos");
					$scope.actualizaTabla();
					
				}
			});
		}
		$scope.limpiaCampos();
	};

	$scope.finalizar =function() {
		var $tipo_expediente = {
				ID:$scope.id_expediente,
				ID_USUARIO_MODIFICACION:0		
			};
			$.post( global_apiserver + "/ex_tipo_expediente/finalizarExpediente/", JSON.stringify($tipo_expediente), function($respuesta){
				respuesta = JSON.parse($respuesta);
				if (respuesta.resultado == "ok") {
					notify_success("Éxito", "Se han actualizado los datos");
					$scope.actualizaTabla();
					$scope.finalizado = 1;
					$scope.apply();
					
				}
			});
		
	}
	
	$scope.checa_finalizar = function() {
		$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/ex_tipo_expediente/checaFinalizadoById/?id="+$scope.id_expediente,
				success: function(data){
					$scope.finalizado = data.finalizado;
				}
			});
	}
	$scope.obtieneInfoExpediente = function(){
		$.getJSON( global_apiserver + "/ex_tipo_expediente/getNombreById/?id_expediente="+$scope.id_expediente, function( response ) {
				if(response[0].VIGENTE == "1"){
					$scope.$apply(function(){
						$scope.titulo = "Expediente "+response[0].NOMBRE;
						
					});
				}else{
					$scope.$apply(function(){
						$scope.titulo = "Expediente "+response[0].NOMBRE;
						
					});
					console.log("No esta Vigente" + response[0].VIGENTE);
				}
		});
		
	}
	$scope.obtieneInfoExpediente();
	$scope.checa_finalizar();
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
		
		
	function getQueryVariable(variable) {
	  var query = window.location.search.substring(1);
	  var vars = query.split("&");
	  for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
		  return pair[1];
		}
	  } 
	  alert('Query Variable ' + variable + ' not found');
	}

