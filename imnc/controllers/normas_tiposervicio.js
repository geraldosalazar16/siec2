/*
	Creación del controlador con el nombre "normas_tiposervicio_controller".
*/

app.controller('normas_tiposervicio_controller', ['$scope', function($scope) { 
	//Titulo que aparece en el html
	$scope.titulo = 'Normas - Tipos de servicios';
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
	/*
		Los siguiente 3 campos se usan para el modelo de tipo de documento.
		$scope.descripcion : descripcion del documento.
		$scope.nombre : nombre del documento.
		$scope.id : id del documento. Solo se usa cuando se edita ya que 
					cuando queremos agregar	ponemos el id como 0.
	*/	
	$scope.nombreNorma = "";
	$scope.nombreTipoServicio = "";
	$scope.id = 0;
	//Se usa para checar si el módelo esta válido o no. Válido = 1 , no válido = 0.
	$scope.respuesta = 1;
	
	/*
		Función para actualizar la tabla con los registros en la BD.
	*/
	$scope.actualizaTabla = function(){
		$.ajax({
			type:'GET',
			url:global_apiserver + "/normas_tiposervicio/getAll/",
			success: function(data){
				$scope.$apply(function(){
					$scope.normas_tiposervicio = angular.fromJson(data);
				});
			}
		});
	};
	/*
		Función para limpiar la información del módelo y que no se quede guardada
		después de realizar alguna transacción.
	*/
	$scope.limpiaCampos = function(){
		$scope.nombreNorma = "";
		$scope.nombreTipoServicio = "";
		$scope.id = 0;
	};
	
/*		
		Función para traer los nombres de las normas.
*/
$scope.funcionNombreNorma = function(){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/normas/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$scope.nombreNorma=angular.fromJson(data);
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
		url:global_apiserver+"/tipos_servicio/getAll/",
		success:function(data){
			$scope.$apply(function(){
				$scope.nombreTipoServicio=angular.fromJson(data);
			})

		}
	});
}
		
/*
	Función para hacer que aparezca el formulario de agregar. Cambiamos el
	atributo de "accion" del boton guardar para tener una referencia a que tipo
	transacción se va a hacer (actualizar o insertar)
*/
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar Normas-Tipo de Servicio");
		$("#nombreNorma").attr("readonly",false);
		$("#modalInsertarActualizar").modal("show");
		
	};
	/*
		Función para hacer que aparezca el formulario de editar. Recibe de parámetro
		el id del tipo de documento que se va a editar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar) y obtenemos la información
		del registro que se va a obtener para cambiar los valores en el módelo.
		
	*/
	$scope.editar =  function(normatiposervicio_id) {		
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Normas-Tipo de Servicio");
	  
		$.getJSON( global_apiserver + "/normas_tiposervicio/getById/?id="+normatiposervicio_id, function( response ) {
		/*	$scope.id = response.ID;
			$scope.nombre = response.NOMBRE;
			$scope.descripcion = response.DESCRIPCION;
			$scope.$apply();*/ 
       });
		$("#nombreNorma").attr("readonly",true);
		$("#modalInsertarActualizar").modal("show");
    
	};
	/*
		Función para hacer que desaparezca el formulario de agregar o editar y
		limpiamos los campos del módelo.
	*/
	$scope.cerrar = function() {		
		$("#nombreNormaerror").text("");		
		$("#nombreTipoServicioerror").text("");
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
		id "nombreNormaerror" y "nombreTipoServicioerror".
	*/
	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if(($scope.nombreNorma.length > 0)&&($scope.nombreTipoServicio.length > 0)){	
			var local ={
				ID_NORMA:	$scope.nombreNorma,
				ID_TIPO_SERVICIO:	$scope.nombreTipoServicio,
			}
				$.ajax({
				type:'POST',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/normas_tiposervicio/getIfExist/"+local,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						notify_success("Éxito", "Ya existe esta relacion norma tipo servicio");							
					}else{
						$("#nombreerror").text("");
					}
				}
			});
		/*	$.post(global_apiserver + "/normas_tiposervicio/getIfExist/", JSON.stringify(local), function(respuesta){
				respuesta = JSON.parse(respuesta);
					if(respuesta.cantidad > 0){
						$scope.respuesta =  0;	
						notify_success("Éxito", "Ya existe esta relacion norma tipo servicio");						
					}else{
						$("#nombreerror").text("");
					}
				
			});*/
			$("#nombreTipoServicioerror").text("");
			$("#nombreNormaerror").text("");
		}else{
			if($scope.nombreNorma.length == 0){
				$scope.respuesta =  0;
				$("#nombreNormaerror").text("No debe estar vacio");
			}
			if($scope.nombreTipoServicio.length == 0){
				$scope.respuesta =  0;
				$("#nombreTipoServicioerror").text("No debe estar vacio");
			}
		}
		
	}
	
	/*
		Se checa si es válida la modificación. Solo tomamos en cuenta la norma 
		ya que el tipo servicio no se puede modificar. Con el id "nombreTipoServicioerror" mostramos
		el error correspondiente.
	*/
	
	$scope.valida_editar = function(){
		$scope.respuesta = 1;		
		if($scope.nombreTipoServicio.length == 0){
			$scope.respuesta =  0;
			$("#nombreTipoServicioerror").text("No debe estar vacio");
		}else{
			$("#nombreTipoServicioerror").text("");
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
				var norma_tipo_servicio = {
					ID_NORMA:$scope.nombreNorma,
					ID_TIPOSERVICIO:$scope.nombreTipoServicio,				
					ID_USUARIO_CREACION : sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION : 0
				};
	
				$.post(global_apiserver + "/normas_tiposervicio/insert/", JSON.stringify(norma_tipo_servicio), function(respuesta){
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
	
//$(document).ready(function () {	
	$scope.funcionNombreNorma();
	$scope.funcionNombreTipoServicio();
	$scope.actualizaTabla();
	
//});	
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


