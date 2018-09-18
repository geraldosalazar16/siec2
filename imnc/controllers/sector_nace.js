app.controller('sector_nace_controller', ['$scope', '$http', function($scope,$http) { 
	$scope.modulo_permisos =  global_permisos["COTIZADOR"];
	$scope.nace = {};
	$scope.accion = "";
	$scope.catalogo = "sector_nace";
	$scope.titulo = "Sector NACE";
	$scope.lista_servicio = {};
	
	
	
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/"+$scope.catalogo+"/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.lista_sector_nace = angular.fromJson(data);
				});
			}
		});
	};
	
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$scope.accion = "insertar";
		$("#modalTitulo").html("Insertar NACE");
		$("#modalInsertarActualizar").modal("show");
		$scope.getSectores();
		$scope.nace = {};
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo. nombre
		
	*/
	$scope.editar =  function(registro_id) {
		$scope.getSectores();
		$scope.accion = "editar";
		$("#modalTitulo").html("Editar NACE");
		$.getJSON( global_apiserver + "/"+$scope.catalogo+"/getById/?id="+registro_id, function( response ) {
			$scope.nace = response;
		$scope.nace.Clave = response.ID_NACE;
		$scope.nace.Descripcion = response.DESCRIPCION;
		$scope.nace.Sector = response.ID_SECTOR;
		$scope.nace.id = registro_id;
		console.log($scope.nace.Sector);
			$scope.$apply() 
       });
		$("#modalInsertarActualizar").modal("show");
	};		
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardar = function() {		
		var nace = {
			ID : $scope.nace.id,
			ID_NACE : $scope.nace.Clave,
			DESCRIPCION : $scope.nace.Descripcion,
			ID_SECTOR : $scope.nace.Sector,
			ID_USUARIO : sessionStorage.getItem("id_usuario")
		};
		if ($scope.accion == 'insertar') {
		    var http_request = {
		        method: 'POST',
		        url: global_apiserver + "/" + $scope.catalogo + "/insert/",
		        data: angular.toJson(nace)
		    };
		}
		else if ($scope.accion == 'editar'){
		    var http_request = {
		        method: 'POST',
		        url: global_apiserver + "/" + $scope.catalogo + "/update/",
		        data: angular.toJson(nace)
		    };
		}
		   
		$http(http_request).success(function(data) {
		    if(data) { 
		        if (data.resultado == "ok") {
		           notify("Éxito", "Se han guardado los cambios", "success");
		           $('#modalInsertarActualizar').modal('hide');
		           $scope.actualizaTabla();
		        }
		        else{
		          notify("Error", data.mensaje, "error");
		        }
		    } 
		    else  {
		        console.log("No hay datos");
		    }
		    }).error(function(response) {
		    console.log("Error al generar petición: " + response);
		});
	}	
	
	$scope.eliminar = function (id, modulo){
		$.getJSON( global_apiserver + "/ex_common/delete.php?id="+id+"&catalogo="+modulo.toUpperCase(), function( response ) {
			if (response.resultado == "ok") {
				notify_success("Éxito", "Se ha eliminado el registro");
				$scope.actualizaTabla();
			}
       });
	}
	$scope.getSectores= function(){
				$http.get(  global_apiserver + "/sectores/getAllSectores/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Sectores = response.data.map(function(item){
	  				return{
	  					ID_SECTOR : item.ID_SECTOR,
	  					NOMBRE : item.ID+" - "+item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	
	$scope.actualizaTabla();
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


