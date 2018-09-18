/*
	Creación del controlador con el nombre "tipo_documento_controller".
*/

app.controller('tipo_documento_controller', ['$scope', function($scope) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Tipo de documento';
	$scope.modulo_permisos =  global_permisos["EXPEDIENTES"];
	/*
		Los siguiente 3 campos se usan para el modelo de tipo de documento.
		$scope.descripcion : descripcion del documento.
		$scope.nombre : nombre del documento.
		$scope.id : id del documento. Solo se usa cuando se edita ya que 
					cuando queremos agregar	ponemos el id como 0.
	*/	
	$scope.descripcion = "";
	$scope.nombre = "";
	$scope.id = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/ex_tipo_documento/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.tipodocumento = angular.fromJson(data);
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
        $scope.nombre = "";
		$scope.descripcion = "";
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar tipo de documento");
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
	$scope.editar =  function(tipo_documento_id) {		
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Tipo de Documento");
	  
		$.getJSON( global_apiserver + "/ex_tipo_documento/getById/?id="+tipo_documento_id, function( response ) {
			$scope.id = response.ID;
			$scope.nombre = response.NOMBRE;
			$scope.descripcion = response.DESCRIPCION;
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
		Valida si la información que tiene el módelo es suficiente apra agregar
		el nuevo registro. Aquí se modifica el valor de "$scope.respuesta" para checar
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
				url:global_apiserver + "/ex_tipo_documento/getByNombre/?nombre="+$scope.nombre,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						$("#nombreerror").text("Nombre de documento ya registrado");						
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		}else{
			$scope.respuesta =  0;
			$("#nombreerror").text("No debe estar vacio");
		}
		if($scope.descripcion.length == 0){
			$scope.respuesta =  0;
			$("#descripcionerror").text("No debe estar vacio");
		}else{
			$("#descripcionerror").text("");
		}
	}
	
	/*
		Se checa si es válida la modificación. Solo tomamos en cuenta la descripción
		ya que el nombre no se puede modificar. Con el id "descripcionerror" mostramos
		el error correspondiente.
	*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;		
		if($scope.descripcion.length == 0){
			$scope.respuesta =  0;
			$("#descripcionerror").text("No debe estar vacio");
		}else{
			$("#descripcionerror").text("");
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
				var tipo_documento = {
					ID:0,
					DESCRIPCION:$scope.descripcion,
					NOMBRE:$scope.nombre,				
					ID_USUARIO_CREACION : 0,
					ID_USUARIO_MODIFICACION : 0
				};
	
				$.post(global_apiserver + "/ex_tipo_documento/insert/", JSON.stringify(tipo_documento), function(respuesta){
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
			$scope.valida_editar();
			if($scope.respuesta == 1){
				var tipo_documento = {
					ID:$scope.id,
					DESCRIPCION:$scope.descripcion,
					NOMBRE:$scope.nombre,
					ID_USUARIO_MODIFICACION : 0
				};
				$.post( global_apiserver + "/ex_tipo_documento/update/", JSON.stringify(tipo_documento), function(respuesta){
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


