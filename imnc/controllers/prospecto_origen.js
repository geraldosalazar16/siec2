/*
	Creación del controlador con el nombre "prospecto_origen_controller".
*/

app.controller('prospecto_origen_controller', ['$scope', function($scope) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Prospecto Origen';
	/*
		Los siguiente 2 campos se usan para el modelo de tipo de documento.
		$scope.origen : origen del prospecto.
		$scope.id : id del documento. Solo se usa cuando se edita ya que 
					cuando queremos agregar	ponemos el id como 0.
	*/	
	$scope.origen = "";
	$scope.id = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/prospecto_origen/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.prospecto_origen = angular.fromJson(data);
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
        $scope.origen = "";
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar Prospecto Origen");
		$("#origen").attr("readonly",false);
		$("#modalInsertarActualizar").modal("show");
		
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo. nombre
		
	*/
	$scope.editar =  function(prospecto_origen_id) {		
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Tipo Prospecto Origen");
	  
		$.getJSON( global_apiserver + "/prospecto_origen/getById/?id="+prospecto_origen_id, function( response ) {
			$scope.id = response.ID;
			$scope.origen = response.ORIGEN;
			$scope.$apply() 
       });
		$("#origen").attr("readonly",false);
		$("#modalInsertarActualizar").modal("show");
    
	};
	/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
	*/
	$scope.cerrar = function() {		
		$("#origenerror").text("");		
		$scope.limpiaCampos();
		$("#modalInsertarActualizar").modal("hide");
		
	};
	/*
		Valida si la información que tiene el módelo es suficiente apra agregar
		el nuevo registro. Aquí se modifica el valor de "$scope.respuesta" para checar
		la validez del módelo.
		Primero se verifica que los campos no sean nulos y en el caso del origen
		se verifica que no se repita.
		Además se muestra el error conrrespondiente en las etiquetas con los
		id "origenerror" y "descripcionerror".
	*/
	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.origen.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/prospecto_origen/getByNombre/?origen="+$scope.origen,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						$("#origenerror").text("Nombre de Prospecto origen ya registrado");						
					}else{
						$("#origenerror").text("");
					}
				}
			});
		}else{
			$scope.respuesta =  0;
			$("#origenerror").text("No debe estar vacio");
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
				var prospecto_origen = {
					ID:0,
					ORIGEN:$scope.origen,				
					ID_USUARIO_CREACION : 0,
					ID_USUARIO_MODIFICACION : 0
				};
	
				$.post(global_apiserver + "/prospecto_origen/insert/", JSON.stringify(prospecto_origen), function(respuesta){
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
			$scope.valida_agregar();
			if($scope.respuesta == 1){
				var prospecto_origen = {
					ID:$scope.id,
					ORIGEN:$scope.origen,
					ID_USUARIO_MODIFICACION : 0
				};
				$.post( global_apiserver + "/prospecto_origen/update/", JSON.stringify(prospecto_origen), function(respuesta){
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


