var app = angular.module('certificandoApp', ['ngFileUpload']);
app.directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit(attr.onFinishRender);
                });
            }
        }
    }
});
app.controller('registro_expediente_controller', ['$scope','$http', 'Upload', function($scope,$http,Upload) { 
	$scope.titulo = 'Expedientes de Cliente'; 
	$scope.modulo_permisos =  global_permisos["EXPEDIENTES"];
	$scope.accion = "ninguna";
	$scope.registroExpediente = []; //lista de objetos a mostrar
	$scope.form = {}; // objeto para guardar datos del formulario y detalles
	$scope.expedientes = []; //lista auxiliar para los datos del select
	$scope.form.archivosDocumentos = []; //lista para los datos de formularios de Archivos
	$scope.form.archivosDocumentosDetalles = []; //lista para los datos de formularios de Archivos
	$scope.form.ID_REGISTRO = getQueryVariable("id");
	$scope.id_entidad = getQueryVariable("id_entidad");
	$scope.file_url= "ExpedienteArchivos.php";
	$scope.dateIndex = 0;
	var isClient = false;
	var upFiles = {};
	var inDetails = false;

	/*$scope.agregar = function() {	
		$scope.accion = "insertar";	
		$("#modalTitulo").html("Insertar Expediente Registro");
		clear_modal_insertar_actualizar();
		$("#modalInsertarActualizar").modal("show");
	};*/

	function setCalendar() {
                $('.dateInput').datepicker({
                    dateFormat: "mm/dd/yy",
                    onSelect: function (dateText, ins) {
                    	var ind = ins.input.attr("data-index");
                        $("#endDate-"+ind).datepicker("option","minDate", dateText);
                    	$scope.form.archivosDocumentos[ind].FECHA_VENCIMIENTO_INICIAL = dateText;
                    }
                }).css("display", "inline-block");
                $('.dateEndInput').datepicker({
                    dateFormat: "mm/dd/yy",
                    onSelect: function (dateText, ins) {
                    	var ind = ins.input.attr("data-index");
                    	$scope.form.archivosDocumentos[ind].FECHA_VENCIMIENTO_FINAL = dateText;
                    }
                }).css("display", "inline-block");
    }
	
	$scope.fechas = function(fecha){
       var fechaS = fecha.split("-");
       var fechaFin = new Date(fechaS[0], fechaS[1] - 1, fechaS[2]);
       var hoy = new Date();
       var sevenDay = new Date();
       sevenDay.setDate(sevenDay.getDate()+7);  
       if(fechaFin < hoy){
           return {
           color:"red"
           }
       }
       
       else if(fechaFin >= hoy && fechaFin <= sevenDay){

           return {
           color:"yellow"
           }
       }
       else if(fechaFin>sevenDay){
 
       }
       
   }


	$scope.detalles =  function(registro_expediente_id) {
		$("#tabla-exp").hide();
		$("#tabla-file").show();
		inDetails = true;
    	$http.get(  global_apiserver + "/ex_registro_expediente/getDetails/?id="+registro_expediente_id)
	  		.then(function( response ) {
	  			$scope.form.ID = registro_expediente_id;
	  			//$scope.form.ID_REGISTRO = response.data.ID_REGISTRO;
	  			$scope.form.ID_EXPEDIENTE_ENTIDAD = response.data.ID_EXPEDIENTE_ENTIDAD;
	  			$scope.form.NOMBRE_EXPEDIENTE_ENTIDAD = response.data.NOMBRE_EXPEDIENTE_ENTIDAD;
	  			console.log(response.data);
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
	  			$scope.titulo = 'Documentos de Expediente ' + $scope.form.NOMBRE_EXPEDIENTE_ENTIDAD + " - " + $scope.NOMBRE_REGISTRO;
	  			$scope.subTitulo = 'Documentos de Expediente ' + $scope.form.NOMBRE_EXPEDIENTE_ENTIDAD + " - " + $scope.NOMBRE_REGISTRO;
			},
			function (response){});
	}
	$scope.cerrarDetails = function(){
		$("#tabla-exp").show();
		$("#tabla-file").hide();
		inDetails = false;
		$scope.titulo = 'Expedientes de '+ $scope.ENTIDAD +" " + $scope.NOMBRE_REGISTRO; 
		$scope.actualizaTabla();
	}

	$scope.editar =  function(registro_expediente_id, isDetails) {		
		$scope.accion = "editar";
	 	$("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
	  	if(isDetails)
	  		fillForminDetails();
	  	else
	  		fillFormById(registro_expediente_id);
	  	$("#modalTitulo").html("Editar " + $scope.subTitulo);
		$("#modalInsertarActualizar").modal("show");    
	}

	$scope.validar =  function(registro_expediente_id, isDetails) {		
		$scope.accion = "validar";	
	 	$("#btnGuardar").attr("id_servicio",$(this).attr("id_servicio"));
	  	if(isDetails)
	  		fillForminDetails();
	  	else
			fillFormById(registro_expediente_id);
		$("#modalTitulo").html("Validar "+ $scope.subTitulo);
		$("#modalInsertarActualizar").modal("show");    
	}
	
	$scope.guardar = function() {
		$(".text-danger").empty();	
		/*if ($scope.accion == "insertar")
		{
			if( !(valida_tipo_expediente() && valida_archivos(true)) ){
				return false;
			}
			var registro_expediente = formToPHP(true);
			if(registro_expediente.archivosDocumentos.length == 0)
				return false;
			$.post(global_apiserver + "/ex_registro_expediente/insert/", JSON.stringify(registro_expediente)).then(function(response){
				respuesta = response.data;
				if (respuesta.resultado == "ok") {
					uploadExp();
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se ha insertado un nuevo registro");
					$scope.actualizaTabla();
				}
			},
			function (response){});
		}*/
		if ($scope.accion == "editar")
		{
			if( !(valida_archivos()) ){
				return false;
			}
			var registro_expediente = formToPHP(false);
			if(registro_expediente.archivosDocumentos.length == 0){
				$("#modalInsertarActualizar").modal("hide");
				notify_success("Sin Cambios", "No se ha realizado ningún cambio");
				return false;
			}
			$.post( global_apiserver + "/ex_registro_expediente/update/", JSON.stringify(registro_expediente),
				function(response){
				respuesta = JSON.parse(response);
				if (respuesta.resultado == "ok") {
					uploadExp();
					$("#modalInsertarActualizar").modal("hide");
					notify_success("Éxito", "Se han actualizado los datos");
					if(inDetails)
						$scope.detalles($scope.form.ID);
					else
						$scope.actualizaTabla();
				}
			});
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
	  					USUARIO : sessionStorage.getItem("id_usuario"),//$scope.form.USUARIO,
	  					CAMBIO : item.CAMBIO,
	  				}
	  				return doc;
	  			})
			};
			for(var i = registro_expediente.archivosDocumentos.length - 1; i>=0 ;i--){
			    if(!registro_expediente.archivosDocumentos[i].ID_ULT_ARCHIVO || !registro_expediente.archivosDocumentos[i].CAMBIO)
			        registro_expediente.archivosDocumentos.splice(i,1);
			}
			if(registro_expediente.archivosDocumentos.length == 0){
				$("#modalInsertarActualizar").modal("hide");
				notify_success("Sin Cambios", "No se ha realizado ningún cambio");
				return false;
			}
			$.post( global_apiserver + "/ex_registro_expediente/validate/", JSON.stringify(registro_expediente), 
				function(response){
					respuesta = JSON.parse(response);
					if (respuesta.resultado == "ok") {
						$("#modalInsertarActualizar").modal("hide");
						notify_success("Éxito", "Se han actualizado los datos");
						if(inDetails)
							$scope.detalles($scope.form.ID);
						else
							$scope.actualizaTabla();
					}
				});
		}
	};

	$scope.expedientePorEntidad = function(entidad){
		$scope.form.ID_EXPEDIENTE_ENTIDAD = 0;
		$http.get(  global_apiserver + "/ex_registro_expediente/getExpedienteByEntidad/?entidad="+entidad )
	  		.then(function( response ) {
	  			$scope.expedientes = response.data.map(function(item){
	  				return{
	  					id_exp_entidad : item.ID_EXPEDIENTE_ENTIDAD,
	  					id_expediente : item.ID_EXPEDIENTE,
	  					nombre : item.NOMBRE
	  				}
	  			});
	  			for(var i = $scope.registroExpediente.length - 1; i>=0 ;i--){
	  				for(var j = $scope.expedientes.length - 1; j>=0 ;j--){
		  				if($scope.registroExpediente[i].ID_EXPEDIENTE_ENTIDAD == $scope.expedientes[j].id_exp_entidad){
					        $scope.expedientes.splice(j,1);
					        break;
		  				}
					}
				}
			},
			function (response){});
	}

	$scope.archivosPorExpediente = function(expediente){
		$scope.form.ID_EXPEDIENTE_ENTIDAD = expediente.id_exp_entidad;
		$http.get(  global_apiserver + "/ex_expediente_documento/getAllByExpedienteId/?id="+expediente.id_expediente)
	  		.then(function( response ) {
	  			$scope.form.archivosDocumentos = response.data.map(function(item){
	  				return{
	  					ID_ARCHIVO_EXPEDIENTE : 0,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID,
	  					OBLIGATORIO : item.OBLIGATORIO,
	  					NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
	  					NOMBRE_ARCHIVO : "",
	  					FECHA_VENCIMIENTO_INICIAL : "",
	  					FECHA_VENCIMIENTO_FINAL : "",
	  					VALIDACION : false,
	  				}
	  			});
			},
			function (response){});
	}
	
	$scope.actualizaTabla = function(){
		$http.get(  global_apiserver + "/ex_registro_expediente/getAllByClient/?id_entidad="+$scope.id_entidad+"&cliente=" + $scope.form.ID_REGISTRO)
	  		.then(function( response ) {
	  			$scope.registroExpediente = response.data.map(function(item, index){
	  				var doc = {
	  					ID : item.ID,
	  					VALIDO : item.VALIDO,
			  			ID_EXPEDIENTE_ENTIDAD : item.ID_EXPEDIENTE_ENTIDAD,
			  			NOMBRE_EXPEDIENTE_ENTIDAD : item.NOMBRE_EXPEDIENTE_ENTIDAD,
	  				}
	  				return doc;
	  			});
				//$scope.expedientePorEntidad($scope.form.ID_REGISTRO);
			},
			function (response){});
	}

	function clear_modal_insertar_actualizar(){
		$("#form-cliente, #form-expediente").show();
		$scope.form.ID = "";
	  	$scope.form.ID_EXPEDIENTE_ENTIDAD = "";
	  	$scope.form.EXPEDIENTE_ENTIDAD = {};
	  	$scope.form.NOMBRE_EXPEDIENTE_ENTIDAD = "";
	  	$scope.form.archivosDocumentos = [];
	}
	
	function fillFormById(id){
	  	$("#form-cliente, #form-expediente").hide();
		$http.get(  global_apiserver + "/ex_registro_expediente/getById/?id="+id)
	  		.then(function( response ) {
	  			$scope.form.ID = response.data.ID;
	  			$scope.form.ID_EXPEDIENTE_ENTIDAD = response.data.ID_EXPEDIENTE_ENTIDAD;
	  			$scope.form.NOMBRE_EXPEDIENTE_ENTIDAD = response.data.NOMBRE_EXPEDIENTE_ENTIDAD;
	  			$scope.form.archivosDocumentos = response.data.archivosDocumentos.map(function(item, index){
	  				var doc = {
	  					ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  					OBLIGATORIO : item.OBLIGATORIO,
	  					NOMBRE_DOCUMENTO : item.NOMBRE_DOCUMENTO,
	  					ID_ULT_ARCHIVO : item.ID_ULT_ARCHIVO,
	  					ID_ULT_ENCRIPTADO : item.ID_ENCRIPTADO,
	  					ULT_NOMBRE_ARCHIVO : item.ULT_NOMBRE_ARCHIVO,
	  					ULT_FECHA_VENCIMIENTO_INICIAL : item.ULT_FECHA_VENCIMIENTO_INICIAL,
	  					ULT_FECHA_VENCIMIENTO_FINAL : item.ULT_FECHA_VENCIMIENTO_FINAL,
	  					ULT_VALIDACION : item.ULT_VALIDACION,
	  					NOMBRE_ARCHIVO : "",
	  					FECHA_VENCIMIENTO_INICIAL : "",
	  					FECHA_VENCIMIENTO_FINAL : "",
	  					VALIDACION : false,
	  				}
	  				return doc;
	  			});
	  			$scope.subTitulo = 'Documentos de Expediente ' + $scope.form.NOMBRE_EXPEDIENTE_ENTIDAD + " - " + $scope.NOMBRE_REGISTRO;
			},
			function (response){});
	}

	function fillForminDetails(){
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
	  			ID_ULT_ENCRIPTADO : item.ARCHIVOS[last].ID_ENCRIPTADO,
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

	function formToPHP(isInsert){
		var registro_expediente = {
				ID : $scope.form.ID,
	  			ID_REGISTRO : $scope.form.ID_REGISTRO,
	  			ID_EXPEDIENTE_ENTIDAD : $scope.form.ID_EXPEDIENTE_ENTIDAD,
	  			USUARIO : sessionStorage.getItem("id_usuario"),//form.USUARIO,
	  			archivosDocumentos : $scope.form.archivosDocumentos.map(function(item, index){
	  				var doc = {
	  					ID_ARCHIVO_EXPEDIENTE : item.ID_ARCHIVO_EXPEDIENTE,
	  					ID_REGISTRO_EXPEDIENTE : $scope.form.ID_REGISTRO,
	  					ID_EXPEDIENTE_DOCUMENTO : item.ID_EXPEDIENTE_DOCUMENTO,
	  					NOMBRE_ARCHIVO : item.NOMBRE_ARCHIVO,
	  					FECHA_VENCIMIENTO_INICIAL : Boolean(item.FECHA_VENCIMIENTO_INICIAL)? Date.parse(item.FECHA_VENCIMIENTO_INICIAL).toString("yyyy-MM-dd") : "",
                        FECHA_VENCIMIENTO_FINAL : Boolean(item.FECHA_VENCIMIENTO_FINAL)? Date.parse(item.FECHA_VENCIMIENTO_FINAL).toString("yyyy-MM-dd") : "",
	  					//FECHA_VENCIMIENTO_INICIAL : Boolean(item.FECHA_VENCIMIENTO_INICIAL)? new Date(item.FECHA_VENCIMIENTO_INICIAL.toString().split("/")[2],item.FECHA_VENCIMIENTO_INICIAL.toString().split("/")[1]-1,item.FECHA_VENCIMIENTO_INICIAL.toString().split("/")[0]) : "",
	  					//FECHA_VENCIMIENTO_FINAL : Boolean(item.FECHA_VENCIMIENTO_FINAL)? new Date(item.FECHA_VENCIMIENTO_FINAL.toString().split("/")[2],item.FECHA_VENCIMIENTO_FINAL.toString().split("/")[1]-1,item.FECHA_VENCIMIENTO_FINAL.toString().split("/")[0]) : "",
	  					VALIDACION : item.VALIDACION,
	  					USUARIO : sessionStorage.getItem("id_usuario"),//form.USUARIO,
	  				}
	  				return doc;
	  			})
			};
		if(isInsert)
			return registro_expediente;
		for(var i = registro_expediente.archivosDocumentos.length - 1; i>=0 ;i--){
		    if(!registro_expediente.archivosDocumentos[i].NOMBRE_ARCHIVO)
		        registro_expediente.archivosDocumentos.splice(i,1);
		}
		return registro_expediente;
	}

	function getCliente(){
		$http.get(  global_apiserver + "/ex_registro_expediente/getRegistroEntidad/?id_entidad="+$scope.id_entidad+"&id="+$scope.form.ID_REGISTRO)
	  		.then(function( response ) {
	  			if(!response.data.NOMBRE){
	  				$scope.titulo = 'No existe el cliente'; 
	  			}
	  			else{
		  			$scope.NOMBRE_REGISTRO = response.data.NOMBRE;
		  			$scope.ENTIDAD = response.data.ENTIDAD;
		  			$scope.titulo = 'Expedientes de '+ $scope.ENTIDAD +" " + $scope.NOMBRE_REGISTRO; 
		  			isClient = true;
	  			}
			},
			function (response){
				$scope.titulo = 'No existe el cliente'; 
			});
	}

	/*function valida_tipo_expediente(){
		if(!isClient)
			return isClient;
		var isValid = true;
		if(!$scope.form.ID_EXPEDIENTE_ENTIDAD){
			isValid = false;
			$("#expediente-error").text("Seleccione un Expediente.");
		}
		else{
			for(var i = $scope.registroExpediente.length - 1; i>=0 ;i--){
	  			if($scope.registroExpediente[i].ID_EXPEDIENTE_ENTIDAD == $scope.form.ID_EXPEDIENTE_ENTIDAD){
				   	isValid = false;
					$("#expediente-error").text("Ya exite un registro de éste expediente.");
				    break;
				}
			}
		}
		return isValid;
	}*/

	function valida_archivos(){
		if(!isClient)
			return isClient;
		var isValid = true;
		upFiles = {};
		$scope.form.archivosDocumentos.forEach(function(item, index){
			if(Boolean(item.FILE)){
				if(item.FILE.type != "application/pdf"){
					isValid = false;
					$("#archivo-error-"+index).text("Solo se permiten archivos PDF");
				}
				upFiles[item.ID_ARCHIVO_EXPEDIENTE] = item.FILE;

	       	}
			/*if(item.OBLIGATORIO == 1 && isInsert){
				if(!item.NOMBRE_ARCHIVO){
					isValid = false;
					$("#archivo-error-"+index).text("Seleccione un Archivo");
				}
				if(!item.FECHA_VENCIMIENTO_INICIAL){
					isValid = false;
					$("#vencimiento-inicial-error-"+index).text("Seleccione una Fecha");
				}
				if(!item.FECHA_VENCIMIENTO_FINAL){
					isValid = false;
					$("#vencimiento-final-error-"+index).text("Seleccione una Fecha");
				}
			}*/
			if(Boolean(item.NOMBRE_ARCHIVO) && ( !item.FECHA_VENCIMIENTO_INICIAL ||  !item.FECHA_VENCIMIENTO_FINAL)){
				isValid = false;
				$("#archivo-error-"+index).text("No se seleccionaron las fechas");
			}
			if(Boolean(item.NOMBRE_ARCHIVO) && ( Boolean(item.FECHA_VENCIMIENTO_INICIAL)  &&  Boolean(item.FECHA_VENCIMIENTO_FINAL) )){
				
				var dateStsp = item.FECHA_VENCIMIENTO_INICIAL.toString().split("/");
				var dateEndsp = item.FECHA_VENCIMIENTO_FINAL.toString().split("/");
				//var dateSt = new Date(dateStsp[2], dateStsp[1] - 1, dateStsp[0]);
				//var dateEnd = new Date(dateEndsp[2], dateEndsp[1] - 1, dateEndsp[0]);
				var dateSt = Date.parse(item.FECHA_VENCIMIENTO_INICIAL);
                var dateEnd = Date.parse(item.FECHA_VENCIMIENTO_FINAL);
                
				if(dateStsp.length != 3){
					isValid = false;
					$("#vencimiento-inicial-error-"+index).text("Fecha Inválida");
				}
				if(dateEndsp.length != 3){
					isValid = false;
					$("#vencimiento-final-error-"+index).text("Fecha Inválida");
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

	$scope.delFileName = function (index){
		$scope.form.archivosDocumentos[index].NOMBRE_ARCHIVO = "";
		$scope.form.archivosDocumentos[index].FILE = null;
	}
	$scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
	    setCalendar();
	});
	$(document).ready(function(){
		$("#tabla-file").hide();
	})
	getCliente();
	$scope.actualizaTabla();
	
}]);




