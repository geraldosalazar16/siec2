
app.controller('perfilesc_controller', ['$scope',function($scope) {
		//Titulo que aparece en el html
		$scope.titulo = 'Perfiles';
		/*
		Los siguiente 2 campos se usan para el modelo de prospecto porcentaje.
		$scope.porcentaje : numero de porcentaje.
		$scope.id : id del documento. Solo se usa cuando se edita ya que 
					cuando queremos agregar	ponemos el id como 0.
		*/
	$scope.perfil = "";
	$scope.id = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/perfiles/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.prospectoporcentaje = angular.fromJson(data);
				});
			}
		});
	};
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCampos = function(){
		$scope.id = 0;
        $scope.perfil = "";
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar Porcentaje");
		$("#nombre").attr("readonly",false);
		$("#modalInsertarActualizar").modal("show");
		
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
	*/
	$scope.editar =  function(perfil_id) {		
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Porcentaje");
	  
		$.getJSON( global_apiserver + "/perfiles/getById/?id="+perfil_id, function( response ) {
			$scope.id = response.ID;
			$scope.perfil = response.DESCRIPCION;
			$scope.$apply() 
       });
		$("#nombre").attr("readonly",true);
		$("#modalInsertarActualizar").modal("show");
    
	};
	/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
	*/
	$scope.cerrar = function() {		
		$("#nombreerror").text("");		
		$("#descripcionerror").text("");
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
	};
	
	
		
	/*
		Esta función nos sirve para hacer el insert o update. Se checa cual de las
		dos transacciones se debe de hacer. Al finalizar la transacción se actualiza
		la tabla.
	*/
	$scope.guardar = function() {		
		if ($("#btnGuardar").attr("accion") == "insertar")
		{
			if($scope.respuesta == 1){
				var perfil_nuevo = {
					ID:0,
					PERFIL:$scope.perfil,				
					ID_USUARIO_CREACION : 0,
					ID_USUARIO_MODIFICACION : 0
				};
	
				$.post(global_apiserver + "/perfiles/insert/", JSON.stringify(perfil_nuevo), function(respuesta){
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
			if($scope.respuesta == 1){
				var prospecto_porcentaje = {
					ID:$scope.id,
					PERFIL:$scope.perfil,	
					ID_USUARIO_MODIFICACION : 0
				};
				$.post( global_apiserver + "/perfiles/update/", JSON.stringify(prospecto_porcentaje), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						$scope.actualizaTabla();
					}
				});
			}
		}
		$scope.limpiaCampos();
		
	};	
	
	
	
	
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


