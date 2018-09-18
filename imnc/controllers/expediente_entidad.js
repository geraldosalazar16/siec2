app.controller('expediente_entidad_controller', ['$scope', function($scope,$http) { 
$scope.titulo = 'Expediente Entidad';
$scope.modulo_permisos =  global_permisos["EXPEDIENTES"];
$scope.selectelementos = 0;
$scope.comboelementos;
$scope.expedienteentidad;
$scope.tipo =0;
$scope.respuesta = 1;
$scope.id_expediente = getQueryVariable("id_expediente");
	
	$scope.activar =  function(expediente_entidad_id, estado_expediente_entidad ) {		
		$.getJSON(  global_apiserver + "/ex_expediente_entidad/update/?id="+expediente_entidad_id+"&estado="+estado_expediente_entidad+"&id_usuario=0", function( response ) {
			if(response.resultado == "ok"){
				$scope.actualizaTabla();
			}
		});
	}
	
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/ex_expediente_entidad/getByIdExpediente/?id_expediente="+$scope.id_expediente,
			success: function(data){
				$scope.$apply(function(){
					$scope.expedienteentidad = angular.fromJson(data);
				});
			}
		});
	}

	$scope.obtieneInfoExpediente = function(){
		$.getJSON( global_apiserver + "/ex_tipo_expediente/getNombreById/?id_expediente="+$scope.id_expediente, function( response ) {
				if(response[0].VIGENTE == "1"){
					$scope.$apply(function(){
						$scope.titulo = response[0].NOMBRE;
						
					});
				}else{
					console.log("No esta Vigente" + response[0].VIGENTE);
				}
		});
		
	}

	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.selectelementos == 0){			
			$scope.respuesta =  0;
			$("#errorentidad").text("Debes seleccionar una opción");
		}else{
			$("#errorentidad").text("");
		}
		
	}
	
	$scope.agregar = function(tipo) {	
		$("#modalTitulo").html("Insertar Expediente Entidad");
		$scope.tipo = tipo;
		if($scope.tipo == 1){
			$("#tipo").html("Entidad");
			$('#selectelementos option:selected').text('Selecciona un tipo de entidad');
			$('#selectelementos option:selected').attr('value','0');
			
		}else{
			$("#tipo").html("Tr&aacute;mite");
			$('#selectelementos option:selected').text('Selecciona un tipo de trámite');
			$('#selectelementos option:selected').attr('value','0');
		}
		$.getJSON( global_apiserver + "/ex_expediente_entidad/getElementsForSelect/?tipo="+$scope.tipo+"&id_expediente="+$scope.id_expediente, function( response ) {
			$scope.$apply(function(){
				$scope.comboelementos = angular.fromJson(response);
			});	
		});
		$("#modalInsertarActualizar").modal("show");
	};
	
	$scope.guardar = function() {
		$scope.valida_agregar();		
		   if($scope.respuesta == 1){
			
			   var expediente_entidad = {
				   TIPO: $scope.tipo,
                   ID_ENTIDAD:$scope.selectelementos,
                   ID_TIPO_EXPEDIENTE:$scope.id_expediente,
                   ID_USUARIO:"0"
			       };
				   console.log(expediente_entidad);
			$.post(global_apiserver + "/ex_expediente_entidad/insert/", JSON.stringify(expediente_entidad), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					notify_success("Éxito", "Se ha insertado un nuevo registro");
					$scope.selectelementos = 0;
					$scope.actualizaTabla();
					
				}
        
			});
		   $("#modalInsertarActualizar").modal("hide");
		   }
		
	};
	
	$scope.cerrar = function() {	
	    $scope.selectelementos = 0;
		$("#modalInsertarActualizar").modal("hide");
	};
	
	$scope.actualizaTabla();
	$scope.obtieneInfoExpediente();
	
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
   