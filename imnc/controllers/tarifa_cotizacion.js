/*
	Creación del controlador con el nombre "prospecto_origen_controller".
*/

app.controller('tarifa_cotizacion_controller', ['$scope', '$http', function($scope,$http) { 
	$scope.modulo_permisos =  global_permisos["COTIZADOR"];
	$scope.tarifa_cotizacion = {};
	$scope.accion = "";
	$scope.catalogo = "tarifa_cotizacion";
	$scope.titulo = "Tarifa Por Día Auditor";
	$scope.lista_servicio = {};
	
	$scope.getTipoServicio = function(){
		$http.get(  global_apiserver + "/tipos_servicio/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.lista_servicio = response.data.map(function(item){
	  				return{
	  					ID : item.ID,
	  					ID_SERVICIO : item.ID_SERVICIO,
						NOMBRE : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/"+$scope.catalogo+"/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.lista_tarifa_cotizacion = angular.fromJson(data);
				});
			}
		});
	};
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCampos = function(){
		$scope.tarifa_cotizacion = {};
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$scope.limpiaCampos();
		$scope.accion = "insertar";
		$scope.getTipoServicio();
		$("#modalTitulo").html("Insertar Tarifa");
		$("#modalInsertarActualizar").modal("show");	
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo. nombre
		
	*/
	$scope.editar =  function(registro_id) {
		$scope.accion = "editar";
		$("#modalTitulo").html("Editar Tarifa");
	  $scope.getTipoServicio();
		$.getJSON( global_apiserver + "/"+$scope.catalogo+"/getById/?id="+registro_id, function( response ) {
			$scope.tarifa_cotizacion = response;
			if(response.ACTIVO == 1){
			  $scope.tarifa_cotizacion.ACTIVO = true;
		      }else{
			  $scope.tarifa_cotizacion.ACTIVO = false;
		      }
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
		var tarifa = {
			ID : $scope.tarifa_cotizacion.ID,
			DESCRIPCION : $scope.tarifa_cotizacion.DESCRIPCION,
			TARIFA : $scope.tarifa_cotizacion.TARIFA,
			ACTIVO : $scope.tarifa_cotizacion.ACTIVO,
			ID_TIPO_SERVICIO : $scope.tarifa_cotizacion.ID_TIPO_SERVICIO,
			ID_USUARIO : sessionStorage.getItem("id_usuario")
		};
		if ($scope.accion == 'insertar') {
		    var http_request = {
		        method: 'POST',
		        url: global_apiserver + "/" + $scope.catalogo + "/insert/",
		        data: angular.toJson(tarifa)
		    };
		}
		else if ($scope.accion == 'editar'){
		    var http_request = {
		        method: 'POST',
		        url: global_apiserver + "/" + $scope.catalogo + "/update/",
		        data: angular.toJson(tarifa)
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


