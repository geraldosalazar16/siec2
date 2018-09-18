/*
	Creación del controlador con el nombre "prospecto_origen_controller".
*/

app.controller('catalogos_controller', ['$scope', function($scope) { 
	//Titulo que aparece en el html
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
	
	$scope.catalogo = getQueryVariable("catalogo");
	
	$scope.titulo = getTitulo($scope.catalogo);
	$scope.etiqueta = getEtiqueta ($scope.catalogo);

	function setModulo(){
		var list_modulos = {
			"CRM" : ["prospecto_origen","prospecto_propuesta_estado","prospecto_tipo_contrato","prospecto_estatus_seguimiento","prospecto_competencia"],
			"USUARIOS" : []
		}
		$.each(list_modulos, function(index, item){
			if($.inArray($scope.catalogo, item) >= 0){
				$scope.modulo_permisos =  global_permisos[index];
				return true;
			}
		});
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
        $scope.descripcion = "";
	};
	/*
		Función para hacer que aparezca el formulario de agregar. Cambiamos el
		atributo de "accion" del boton guardar para tener una referencia a que tipo
		transacción se va a hacer (actualizar o insertar)
	*/
	$scope.agregar = function() {		
		$("#btnGuardar").attr("accion","insertar");
		$("#modalTitulo").html("Insertar Prospecto "+$scope.etiqueta);
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
	$scope.editar =  function(registro_id) {		
		$("#btnGuardar").attr("accion","editar");
		$("#modalTitulo").html("Editar Prospecto "+$scope.etiqueta);
	  
		$.getJSON( global_apiserver + "/"+$scope.catalogo+"/getById/?id="+registro_id, function( response ) {
			console.log(response);
			$scope.id = response.ID;
			$scope.descripcion = response.DESCRIPCION;
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
		$("#descripcionerror").text("");		
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
		id "descripcionerror" y "descripcionerror".
	*/
	$scope.valida_agregar = function(){
		$scope.respuesta = 1;
		if($scope.descripcion.length > 0){	
			$.ajax({
				type:'GET',
				dataType: 'json',
				async: false,
				url:global_apiserver + "/"+$scope.catalogo+"/getByDescripcion/?descripcion="+$scope.descripcion,
				success: function(data){
					if(data.cantidad > 0){
						$scope.respuesta =  0;	
						$("#descripcionerror").text("Ya hay un registro así");						
					}else{
						$("#descripcionerror").text("");
					}
				}
			});
		}else{
			$scope.respuesta =  0;
			$("#descripcionerror").text("No debe estar vacio");
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
				var registro = {
					ID:0,
					DESCRIPCION:$scope.descripcion,				
					ID_USUARIO_CREACION : 0,
					ID_USUARIO_MODIFICACION : 0
				};
				$.post(global_apiserver + "/"+$scope.catalogo+"/insert/", JSON.stringify(registro), function(respuesta){
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
				var registro = {
					ID:$scope.id,
					DESCRIPCION:$scope.descripcion,
					ID_USUARIO_MODIFICACION : 0
				};
				$.post( global_apiserver + "/"+$scope.catalogo+"/update/", JSON.stringify(registro), function(respuesta){
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
	
	$scope.eliminar = function (id, modulo){
		$.getJSON( global_apiserver + "/ex_common/delete.php?id="+id+"&catalogo="+modulo.toUpperCase(), function( response ) {
			if (response.resultado == "ok") {
				notify_success("Éxito", "Se ha eliminado el registro");
				$scope.actualizaTabla();
			}
       });
	}
	
	$scope.actualizaTabla();
	setModulo();
	
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

