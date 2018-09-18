
app.controller('perfil_permisos_controller', ['$scope', function($scope) { 
	
	
	$scope.id_perfil = getQueryVariable("id");
	$scope.permisos_perfil = "";
	$scope.titulo = "Perfil Permisos";

	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			//dataType: "json",
			url:global_apiserver + "/perfiles_permisos/getByIdPerfil/?id_perfil="+$scope.id_perfil,
			success: function(data){
				console.log(data);
				$scope.$apply(function(){
					$scope.permisos_perfil = angular.fromJson(data);
				});
			}
		});
		$.ajax({
			type:'GET',
			dataType: "json",
			url:global_apiserver + "/perfiles/getById/?id="+$scope.id_perfil,
			success: function(data){
				$scope.$apply(function(){
					$scope.titulo = data.DESCRIPCION;
				});
			}
		});
	};
	$scope.cambio = function(id,w){
		var permiso_update = {
				ID:id,
				VALOR:$("#permiso"+w+"_"+id).is( ':checked' ),			
				ID_USUARIO_MODIFICACION : 0
			};
			$.post(global_apiserver + "/perfiles_permisos/update/", JSON.stringify(permiso_update), function(respuesta){
				respuesta = JSON.parse(respuesta);
				if (respuesta.resultado == "ok") {
					
					notify_success("Éxito", "Se ha insertado un nuevo registro");

				}
		
			});
		
	}
	
	
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

	function getTitulo(tabla){
		if(tabla == "prospecto_origen"){
			return "Origen de prospecto";
		}else if(tabla == "prospecto_propuesta_estado"){
			return "Estados de la propuesta";
		}else if(tabla == "prospecto_tipo_contrato"){
			return "Tipo de contrato";
		}else if(tabla == "prospecto_estatus_seguimiento"){
			return "Estatus del seguimiento";
		}else if(tabla == "prospecto_porcentaje"){
			return "Porcentaje del prospecto";
		}else if(tabla == "prospecto_competencia"){
			return "Competencias para el prospecto";
		}else{
			return "Catálogo";
		}
	}
	
	function getEtiqueta(tabla){
		if(tabla == "prospecto_origen"){
			return "Origen";
		}else if(tabla == "prospecto_propuesta_estado"){
			return "Estado";
		}else if(tabla == "prospecto_tipo_contrato"){
			return "Contrato";
		}else if(tabla == "prospecto_estatus_seguimiento"){
			return "Estatus";
		}else if(tabla == "prospecto_porcentaje"){
			return "Porcentaje";
		}else if(tabla == "prospecto_competencia"){
			return "Competencia";
		}else{
			return "Descripción";
		}
	}

