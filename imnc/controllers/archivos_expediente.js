var app = angular.module('certificandoApp', ['ngFileUpload']);
app.controller('archivos_expediente_controller', ['$scope','$http', 'Upload', function($scope,$http,Upload) { 
	$scope.titulo = 'Archivos de Expediente';
	$scope.accion = "ninguna";
	$scope.form = {}; // objeto para guardar datos del formulario y detalles
	$scope.form.archivosDocumentosDetalles = []; //lista para los datos de formularios de Archivos
	$scope.form.archivosDocumentos = []; //lista para los datos de formularios de Archivos
	$scope.form.ID = getQueryVariable("id");
	$scope.NOMBRE = getQueryVariable("nombre");
	var upFiles = {};

	$scope.detalles =  function(registro_expediente_id) {
    	$http.get(  global_apiserver + "/ex_registro_expediente/getDetails/?id="+registro_expediente_id)
	  		.then(function( response ) {
	  			//$scope.form.ID = response.data.ID;
	  			$scope.form.ID_REGISTRO = response.data.ID_REGISTRO;
	  			$scope.form.ID_EXPEDIENTE_ENTIDAD = response.data.ID_EXPEDIENTE_ENTIDAD;
	  			$scope.form.NOMBRE_EXPEDIENTE_ENTIDAD = response.data.NOMBRE_EXPEDIENTE_ENTIDAD;
	  			$scope.form.archivosDocumentosDetalles = response.data.archivosDocumentos.map(function(item, index){
	  				var doc = {
	  					ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  					OBLIGATORIO : item.OBLIGATORIO,
	  					NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
	  					ARCHIVOS : item.archivos.map(function(item, index) {
	  						return{
			  					ID_ARCHIVO : item.ID_ARCHIVO,
	  							ID_ENCRIPTADO : item.ID_ENCRIPTADO,
			  					NOMBRE_ARCHIVO : item.NOMBRE_ARCHIVO,
			  					FECHA_VENCIMIENTO_INICIAL : item.FECHA_VENCIMIENTO_INICIAL,
			  					FECHA_VENCIMIENTO_FINAL : item.FECHA_VENCIMIENTO_FINAL,
			  					VALIDACION : item.VALIDACION
			  				};
	  					})
	  				}
	  				return doc;
	  			});
	  			$scope.titulo = 'Archivos de Expediente ' + $scope.form.NOMBRE_EXPEDIENTE_ENTIDAD + " - " + $scope.NOMBRE;
			},
			function (response){});
	}

	$scope.editar =  function() {		
		$scope.accion = "editar";
	 	$("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
	  	$("#modalTitulo").html("Editar Expediente Registro");
	  	fillForm();
		$("#modalInsertarActualizar").modal("show");    
	}

	$scope.validar =  function() {		
		$scope.accion = "validar";	
	 	$("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
	  	$("#modalTitulo").html("Validar Expediente Registro");
		fillForm();
		$("#modalInsertarActualizar").modal("show");    
	}
	
	$scope.guardar = function() {	
		$(".text-danger").empty();
		if ($scope.accion == "editar")
		{
			if( !(valida_archivos()) ){
				return false;
			}
			var registro_expediente = formToPHP();
			if(registro_expediente.archivosDocumentos.length == 0)
				return false;
			$http.post( global_apiserver + "/ex_registro_expediente/update/", JSON.stringify(registro_expediente)).then(function(response){
				respuesta = response.data;
				if (respuesta.resultado == "ok") {
					uploadExp();
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se han actualizado los datos");
					$scope.detalles($scope.form.ID);
				}
			},
			function (response){});
		}
		else if ($scope.accion == "validar")
		{
			var registro_expediente = {
	  			archivosDocumentos : $scope.form.archivosDocumentos.map(function(item, index){
	  				var doc = {
	  					ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  					OBLIGATORIO : item.OBLIGATORIO,
	  					NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
	  					ID_ULT_ARCHIVO : item.ID_ULT_ARCHIVO,
	  					ULT_NOMBRE_ARCHIVO : item.ULT_NOMBRE_ARCHIVO,
	  					ULT_FECHA_VENCIMIENTO_INICIAL : item.ULT_FECHA_VENCIMIENTO_INICIAL,
	  					ULT_FECHA_VENCIMIENTO_FINAL : item.ULT_FECHA_VENCIMIENTO_FINAL,
	  					ULT_VALIDACION : item.ULT_VALIDACION,
	  					USUARIO : 0,//$scope.form.USUARIO,
	  					CAMBIO : item.CAMBIO,
	  				}
	  				return doc;
	  			})
			};
			for(var i = registro_expediente.archivosDocumentos.length - 1; i>=0 ;i--){
			    if(!registro_expediente.archivosDocumentos[i].ID_ULT_ARCHIVO || !registro_expediente.archivosDocumentos[i].CAMBIO)
			        registro_expediente.archivosDocumentos.splice(i,1);
			}
			if(registro_expediente.archivosDocumentos.length == 0)
				return false;
			$http.post( global_apiserver + "/ex_registro_expediente/validate/", JSON.stringify(registro_expediente)).then(function(response){
				respuesta = response.data;
				if (respuesta.resultado == "ok") {
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se han actualizado los datos");
					$scope.detalles($scope.form.ID);
				}
			},
			function (response){});
		}
	};

	function formToPHP(){
		var registro_expediente = {
				ID : $scope.form.ID,
	  			ID_REGISTRO : $scope.form.ID_REGISTRO,
	  			ID_EXPEDIENTE_ENTIDAD : $scope.form.ID_EXPEDIENTE_ENTIDAD,
	  			USUARIO : 0,//form.USUARIO,
	  			archivosDocumentos : $scope.form.archivosDocumentos.map(function(item, index){
	  				var doc = {
	  					ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  					ID_REGISTRO_EXPEDIENTE : $scope.form.ID_REGISTRO,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  					NOMBRE_ARCHIVO : item.NOMBRE_ARCHIVO,
	  					FECHA_VENCIMIENTO_INICIAL : new Date(item.FECHA_VENCIMIENTO_INICIAL).toString("yyyy-MM-dd"),
	  					FECHA_VENCIMIENTO_FINAL : new Date(item.FECHA_VENCIMIENTO_FINAL).toString("yyyy-MM-dd"),
	  					VALIDACION : item.VALIDACION,
	  					USUARIO : 0,//form.USUARIO,
	  				}
	  				return doc;
	  			})
			};
		for(var i = registro_expediente.archivosDocumentos.length - 1; i>=0 ;i--){
		    if(!registro_expediente.archivosDocumentos[i].NOMBRE_ARCHIVO)
		        registro_expediente.archivosDocumentos.splice(i,1);
		}
		return registro_expediente;
	}

	function fillForm(){

	  	$scope.form.archivosDocumentos = $scope.form.archivosDocumentosDetalles.map(function(item, index){
	  		if(item.ARCHIVOS.length == 0)
	  			return {
	  				ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
		  			ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
		  			OBLIGATORIO : item.OBLIGATORIO,
		  			NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
		  			NOMBRE_ARCHIVO : "",
		  			FECHA_VENCIMIENTO_INICIAL : "",
		  			FECHA_VENCIMIENTO_FINAL : "",
		  			VALIDACION : false,
	  			};
	  		var last = item.ARCHIVOS.length - 1;
	  		var doc = {
	  			ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  			ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  			OBLIGATORIO : item.OBLIGATORIO,
	  			NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
	  			ID_ULT_ARCHIVO : item.ARCHIVOS[last].ID_ARCHIVO,
	  			ULT_NOMBRE_ARCHIVO : item.ARCHIVOS[last].NOMBRE_ARCHIVO,
	  			ULT_FECHA_VENCIMIENTO_INICIAL : item.ARCHIVOS[last].FECHA_VENCIMIENTO_INICIAL,
	  			ULT_FECHA_VENCIMIENTO_FINAL : item.ARCHIVOS[last].FECHA_VENCIMIENTO_FINAL,
	  			ULT_VALIDACION : item.ARCHIVOS[last].VALIDACION,
	  			NOMBRE_ARCHIVO : "",
	  			FECHA_VENCIMIENTO_INICIAL : "",
	  			FECHA_VENCIMIENTO_FINAL : "",
	  			VALIDACION : false,
	  		}
	  		return doc;
	  	});
	}

	function valida_archivos(){
		var isValid = true;
		upFiles = {};
		$scope.form.archivosDocumentos.forEach(function(item, index){
			if(Boolean(item.FILE)){
				upFiles[item.ID_ARCHIVO_EXPEDIENTE] = item.FILE;
	       	}
			if(Boolean(item.NOMBRE_ARCHIVO) && ( !item.FECHA_VENCIMIENTO_INICIAL ||  !item.FECHA_VENCIMIENTO_FINAL)){
				isValid = false;
				$("#archivo-error-"+index).text("Seleccione una Fecha");
			}
			if(Boolean(item.NOMBRE_ARCHIVO) && ( Boolean(item.FECHA_VENCIMIENTO_INICIAL) &&  Boolean(item.FECHA_VENCIMIENTO_FINAL) )){
				var dateSt = new Date(item.FECHA_VENCIMIENTO_INICIAL);
				var dateEnd = new Date(item.FECHA_VENCIMIENTO_FINAL);
				if(isNaN(dateSt)){
					isValid = false;
					$("#vencimiento-inicial-error-"+index).text("La Fecha Inválida");
				}
				if(isNaN(dateEnd)){
					isValid = false;
					$("#vencimiento-final-error-"+index).text("La Fecha Inválida");
				}
				if(dateEnd.getTime() <= dateSt.getTime()){
					isValid = false;
					$("#vencimiento-final-error-"+index).text("La Fecha Final no puede ser menor o igual a la Inicial");
				}
			}
				
		});
		return isValid;
	}
	function uploadExp(){
		Upload.upload({
		   url: global_apiserver + "/ex_registro_expediente/upload/",
			data: upFiles//{file: upFiles}
		}).then(function (resp) {
		   console.log('Success uploaded. Response: ' + resp.data);
		}, function (resp) {
		    console.log('Error status: ' + resp.status);
		}, function (evt) {
		    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
		    console.log('progress: ' + progressPercentage + '% ');
		});	
	}
	$scope.getFileName = function (file, i){
		$scope.form.archivosDocumentos[i].NOMBRE_ARCHIVO = file.name;
	}
	$scope.detalles($scope.form.ID);
}]);




