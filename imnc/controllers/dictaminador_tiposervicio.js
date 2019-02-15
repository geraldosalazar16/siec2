/*
	Creación del controlador con el nombre "normas_tiposervicio_controller".
*/

app.controller('dictaminador_tiposervicio_controller', ['$scope', function($scope) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Dictaminadores';
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
	/*
		Inicializacion de variables
	*/	
	$scope.accion ="";
	$scope.formDataInsActDict = {};
	$scope.confirmacion = {};
	$scope.nombreTipoServicio = "";
	$scope.Tipos_Servicio_Total ="";
	$scope.id = 0;
	$scope.bandera =1;
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/dictaminador_tiposervicio/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.dictaminadores = angular.fromJson(data);
				});
			}
		});
	};
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCampos = function(){
		$scope.formDataInsActDict = {};
		$scope.nombreTipoServicio = "";
		
	};
	
/*		
		Función para traer los nombres de los Servicios.
*/
$scope.funcionNombreServicio = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/servicios/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$scope.nombreServicios=angular.fromJson(data);
			})

		}
	});
}
/*		
		Función para traer los nombres de los tipos de Servicio.
*/
$scope.funcionNombreTipoServicio = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/tipos_servicio/getList",
		success:function(data){
			$scope.$apply(function(){
				$scope.Tipos_Servicio_Total=angular.fromJson(data);
				
			})

		}
	});
}
/*		
		Función para traer los nombres de los Usuarios.
*/
$scope.funcionNombreUsuarios = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/usuarios/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$scope.nombreUsuarios=angular.fromJson(data);
			})

		}
	});
}		
/*		
		Función para traer los tipos de servicio cdo cambia un servicio.
*/
 $scope.cambio_servicio = function () {
    const servicio = $scope.formDataInsActDict.nombreServicio;
   
    const tipos_servicio = $scope.Tipos_Servicio_Total;
    $scope.nombreTipoServicios = [];
    tipos_servicio.forEach(tipo_servicio => {
      if (tipo_servicio.ID_SERVICIO === servicio.ID) {
        $scope.nombreTipoServicios.push(tipo_servicio);
      }
    });
  }
/*
	Función para hacer que aparezca el formulario de agregar. Cambiamos el
	atributo de "accion" del boton guardar para tener una referencia a que tipo
	transacción se va a hacer (actualizar o insertar)
*/
	$scope.agregar = function() {
		$scope.limpiaCampos();		
		$scope.accion ="insertar";
		$("#modalTitulo").html("Insertar Dictaminador-Tipo de Servicio");
		$("#modalInsertarActualizar").modal("show");
		
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
	*/
	$scope.editar =  function(id_user) {
		$scope.limpiaCampos();	
		$scope.accion ="editar";
		$("#modalTitulo").html("Agregar Tipo de Servicio");
		$scope.formDataInsActDict.nombreUsuario = id_user;
		$("#modalInsertarActualizar").modal("show");
    
	};
	/*
		Función para hacer que aparezca el modal de confirmacion al eliminar 
		un tipo de servicio.
	*/
	$scope.eliminar = function(id_user,id_ts) {		
		
		$scope.confirmacion.ID_USUARIO = id_user;
		$scope.confirmacion.ID_TIPO_SERVICIO = id_ts;
		$("#modalConfirmacion").modal("show");
		
	};
	
	
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardar = function(formDataInsActDict) {		
	
			
				var dictaminador_tipo_servicio = {
					ID_USUARIO:$scope.formDataInsActDict.nombreUsuario,
					ID_TIPOSERVICIO:$scope.formDataInsActDict.nombreTipoServicio,				
					ID_USUARIO_CREACION : sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION : 0
				};
	
				$.post(global_apiserver + "/dictaminador_tiposervicio/insert/", JSON.stringify(dictaminador_tipo_servicio), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						
						notify("Éxito", "Se ha insertado un nuevo registro","success");
						$scope.actualizaTabla();
					}
					 else {
							notify('Error',respuesta.mensaje,'error');
					}
					$("#modalInsertarActualizar").modal("hide");
				});
			
	
		$scope.limpiaCampos();
		
	};	
	/*
		Funcion para eliminar un tipo de servicio de un dictaminador
	*/
	$scope.eliminar_tipoServicio = function(id_usuario,id_tipo_servicio) {		
	
			
				var dictaminador_tipo_servicio = {
					ID_USUARIO:id_usuario,
					ID_TIPOSERVICIO:id_tipo_servicio,				
					
				};
	
				$.post(global_apiserver + "/dictaminador_tiposervicio/delete/", JSON.stringify(dictaminador_tipo_servicio), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						
						notify("Éxito", "Se ha eliminado un registro","success");
						$scope.actualizaTabla();
					}
					 else {
							notify('Error',respuesta.mensaje,'error');
					}
					$("#modalConfirmacion").modal("hide");
				});
			
	
		
	};	
	
//$(document).ready(function () {	
	$scope.funcionNombreTipoServicio();
	$scope.funcionNombreUsuarios();
	$scope.funcionNombreServicio();
	$scope.actualizaTabla();
	
//});	
}]);
	/*
		Función que recibe el título y el texto de un cuadro de notificación.
	*/
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


