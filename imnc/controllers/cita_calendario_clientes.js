var app = angular.module('certificandoApp', ['ngFileUpload']);
app.controller('cita_calendario_clientes_controller',['$scope','$http', 'Upload',function($scope,$http, Upload){
$scope.modulo_permisos =  global_permisos["CRM"];
$scope.form = {}; //objeto para guardar los datos del formulario	
$scope.accion = "";
$scope.file_url = "ExpedienteArchivos.php";
$scope.id_prospecto = getQueryVariable("id");
var last_id_cotizacion = "";
var id_user = sessionStorage.getItem("id_usuario");

		$scope.limpiar =function(){
            $scope.form.id_calendario = 0;
	        $scope.form.asunto = "";
	        $scope.form.tipo_asunto = 0;
	        $scope.form.recordatorio = 0;
	        $scope.form.observaciones = "";
	        $scope.form.fecha_inicio = "";
	        $scope.form.fecha_fin = "";
	        $scope.form.hora_inicio = null;
	        $scope.form.hora_fin = null;
	        $scope.form.usuario_asignado = $scope.Usuario.permisos == "admin"? 0 : id_user;
	        $scope.form.nombre_usuario_asignado = "";
	        $scope.form.porcentaje = {id_porcentaje : 0};
	        $scope.des_porcentaje = "";
	        $scope.form.propuesta_estado = 0;
	        $scope.form.id_cotizacion = last_id_cotizacion;
	        $scope.form.CITA_ARCHIVOS = {};
	        $scope.form.FILE = null;
	        $scope.form.archivos = [];
		}
		$scope.cerrar = function(){
			$scope.limpiar();
             $("#modalCreateEvento").modal("hide");
		}
        
		function setCalendar() {
            moment.locale('es');
            var right_menu = $scope.modulo_permisos["registrar"] == 1? 'month,agendaWeek,agendaDay,listWeek,newEvent' : 'month,agendaWeek,agendaDay,listWeek';
            var calendar = $('#calendario').fullCalendar({
                customButtons: {
                    newEvent: {
                        text: '+ Nuevo Evento',
                        click: function() { //desplegando el menu
                        	$(".text-danger").empty();
                        	$scope.accion="insertar";
                            $scope.limpiar();
                            $scope.$apply();
                        	$("#FI").show();
                    		$("#HI").show();
                    		$("#HF").show();
                    		$("#up_files").hide();
                    		$("#fechas").hide();
							$("#hist-button").hide();
                    		$("#modalTitulo").text("Nuevo Evento");
                            $("#modalCreateEvento").modal("show");

                        }
                    }
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: right_menu
                },
                locale: 'es',
                navLinks: true,
                editable: true,
                eventLimit: true,
                eventClick: function (calEvent, jsEvent, view) {
                    
                    /*
                    $http.get(global_apiserver + "/cita_calendario/getCitaById/?id="+calEvent.id)
                    .then(function(response){ //pasando los datos de la base a formato php
                    	$scope.form.id_calendario = response.data.id_calendario;
                    	$scope.form.id_prospecto = response.data.id_prospecto;
                    	$scope.form.asunto = response.data.asunto;
                    	$scope.form.fecha_inicio = response.data.fecha_inicio;
						$scope.form.fecha_fin = response.data.fecha_fin;
                    	$scope.form.tipo_asunto = response.data.tipo_asunto;
                    	$scope.form.recordatorio = Number(response.data.recordatorio); //transforma a cadena
                    	$scope.form.observaciones = response.data.observaciones;
                    	$scope.form.usuario_asignado = response.data.usuario_asignado;
                    	$scope.form.nombre_usuario_asignado = response.data.nombre_usuario_asignado;
				        $scope.form.propuesta_estado = response.data.estatus_cotizacion;
				        $scope.form.porcentaje = {}
				        $scope.form.porcentaje.id_porcentaje = response.data.factibilidad;
				        $scope.des_porcentaje = response.data.descripcion_porcentaje;
				        $scope.form.id_cotizacion = response.data.id_cotizacion;
	        			$scope.form.CITA_ARCHIVOS = {};
	        			$scope.form.archivos = response.data.archivos;
	                    $scope.accion="editar";
	                    $("#FI").hide();
	                    $("#HI").hide();
	                    $("#HF").hide();
	                   	$("#up_files").show();
	                   	$("#fechas").show();
	                   	$("#hist-button").show();
	                    $(".text-danger").empty();
	                    $("#modalTitulo").text("Editar Evento");
	                    $("#modalCreateEvento").modal("show");
	                })*/
                },
                events: function (start, end, timezone, callback) {

/*
                   $http.get(  global_apiserver + "/cita_calendario/getAllCitas/?id="+$scope.id_prospecto)
	  					.then(function( response ) {//se ejecuta cuando la petición fue correcta
			  			var eventos = response.data.map(function(item){
			  				return{
			  					id: item.id_calendario,
                                title: item.asunto,
                                start: item.fecha_inicio,
                                end : item.fecha_fin,
                                editable: false,
                                color: item.color,
                                id_cotizacion : item.id_cotizacion
                            }
			  			});
	  					last_id_cotizacion = eventos.slice(-1)[0].id_cotizacion;
	  					callback(eventos);
					},
					function (response){});*/
                }
            });
			$('#calendario').fullCalendar( 'today' );
        }

        function onCalendar() {
            $(document).ready(function () {

                $('#fecha_inicio').datepicker({
                    dateFormat: "mm/dd/yy",
                    minDate: "+0D",
                    onSelect: function (dateText, ins) {
                        $scope.form.fecha_inicio = dateText;
                    }
                }).css("display", "inline-block");

                $('#hora_inicio').timepicker({
                    controlType: 'select',
                    oneLine: true,
                    showSecond : false,
                    showMillisec : false,
                    showMicrosec : false,
                    showTimezone : false,
                    timeFormat: 'HH:mm',
                    onSelect : function (dateText, ins) {
                    	$scope.form.hora_inicio = dateText;
                        $("#hora_fin").timepicker("option","minTime", dateText);
                    }
                    
                }).css("display", "inline-block");

                $('#hora_fin').timepicker({
                    controlType: 'select',
                    oneLine: true,
                    showSecond : false,
                    showMillisec : false,
                    showMicrosec : false,
                    showTimezone : false,
                    timeFormat: 'HH:mm',
                    minTime:$('#hora_inicio').val(),
                    onSelect : function (dateText, ins) {
                        $scope.form.hora_fin = dateText;
                    }
                }).css("display", "inline-block");
            });
        }



        $scope.guardar = function() {
			$(".text-danger").empty();	//limpia mensajes de error
			if ($scope.accion == "insertar")
			{
				///VALIDACIONES
				if(!valida_fechas()){
					return false;
				}

				var citaObjeto = formToPHPCREATE();
				$.post(global_apiserver + "/cita_calendario/insert/", JSON.stringify(citaObjeto), function(response){
					respuesta = JSON.parse(response);
				if (respuesta.resultado == "ok") {
					uploadFile(respuesta.id);
					$("#modalCreateEvento").modal("hide");
					notify_success("Éxito", "Se ha insertado una nueva cita");
					$('#calendario').fullCalendar('refetchEvents');
					last_id_cotizacion = citaObjeto.id_cotizacion;
					$scope.limpiar();
				}
			});
		    }
			else if ($scope.accion == "editar")
			{
				///VALIDACIONES 

				var citaObjeto = formToPHPEDIT();
		
				$.post( global_apiserver + "/cita_calendario/update/", JSON.stringify(citaObjeto),function(response){
					respuesta = JSON.parse(response);
				if (respuesta.resultado == "ok") {
					uploadFile(respuesta.id);
					$("#modalCreateEvento").modal("hide");
					notify_success("Éxito", "Se han actualizado los datos");
					$('#calendario').fullCalendar('refetchEvents');
					last_id_cotizacion = citaObjeto.id_cotizacion;
					$scope.limpiar();
				}
			});
			}
		};


	function formToPHPCREATE(){ //obteniendo datos del formulario y se pasan a un json (mismo nombre que en el index.php)
		var aux_fechainicio = Date.parse($scope.form.fecha_inicio);
		aux_fechainicio.setHours(Date.parse($scope.form.hora_inicio).getHours());
		aux_fechainicio.setMinutes(Date.parse($scope.form.hora_inicio).getMinutes());
		
		var aux_fin = Date.parse($scope.form.fecha_inicio);
		aux_fin.setHours(Date.parse($scope.form.hora_fin).getHours());
		aux_fin.setMinutes(Date.parse($scope.form.hora_fin).getMinutes());
		
		var cita_calendario = {
				asunto : $scope.form.asunto,
				tipo_asunto : $scope.form.tipo_asunto,
				recordatorio : $scope.form.recordatorio,
				observaciones : $scope.form.observaciones,
				fecha_inicio : aux_fechainicio.toString("yyyy-MM-dd HH:mm"),
				fecha_fin : aux_fin.toString("yyyy-MM-dd HH:mm"),
	  			id_prospecto : $scope.id_prospecto,
	  			id_usuario_modificacion : id_user,
	  			usuario_asignado : $scope.form.usuario_asignado,
				estatus_cotizacion : $scope.form.propuesta_estado,
				factibilidad : $scope.form.porcentaje.id_porcentaje,
				id_cotizacion : $scope.form.id_cotizacion
			};
		return cita_calendario;
	}

		function formToPHPEDIT(){ //obteniendo datos del formulario y se pasan a un json (mismo nombre que en el index.php)

		var cita_calendario = {
				id_calendario : $scope.form.id_calendario,
				asunto : $scope.form.asunto,
				tipo_asunto : $scope.form.tipo_asunto,
				recordatorio : $scope.form.recordatorio,
				observaciones : $scope.form.observaciones,
	  			id_usuario_modificacion : id_user,
	  			usuario_asignado : $scope.form.usuario_asignado,
				factibilidad : $scope.form.porcentaje.id_porcentaje,
				estatus_cotizacion : $scope.form.propuesta_estado,
				id_cotizacion : $scope.form.id_cotizacion
			};
		return cita_calendario;
	}

//valida las fechas
function valida_fechas(){
		var isValid = true;
		if(Boolean($scope.form.fecha_inicio) && Boolean($scope.form.hora_fin) && Boolean($scope.form.hora_inicio)){
			var aux_fechainicio = Date.parse($scope.form.fecha_inicio);
			aux_fechainicio.setHours(Date.parse($scope.form.hora_inicio).getHours());
			aux_fechainicio.setMinutes(Date.parse($scope.form.hora_inicio).getMinutes());

			var aux_fin = Date.parse($scope.form.fecha_inicio);
			aux_fin.setHours(Date.parse($scope.form.hora_fin).getHours());
			aux_fin.setMinutes(Date.parse($scope.form.hora_fin).getMinutes());

			if(aux_fechainicio > aux_fin){
				isValid = false;
				$("#horafinerror").text("La hora de inicio no puede ser mayor a la hora final");
			}
		}
		if(!$scope.form.fecha_inicio){
			isValid = false;
			$("#fechainicioerror").text("Escriba una fecha");	
		}
		if(!$scope.form.hora_fin){
			isValid = false;
			$("#horafinerror").text("Escriba una hora");	
		}
		if(!$scope.form.hora_inicio){
			isValid = false;
			$("#horainicioerror").text("Escriba una hora");	
		}
		if(!$scope.form.asunto){
			isValid = false;
			$("#asuntoerror").text("Escriba un asunto");	
		}
		/*if(!$scope.form.recordatorio){
			isValid = false;
			$("#recordatorioerror").text("Agregue un recordatorio");
		}*/
		if(!$scope.form.tipo_asunto){
			isValid = false;
			$("#tipoasuntoerror").text("Elija un asunto");
		}
		if(!$scope.form.usuario_asignado){
			isValid = false;
			$("#usuarioasignadoerror").text("Elija un usuario");
		}
		if(!$scope.form.porcentaje){
			isValid = false;
			$("#porcentajeerror").text("Elija un porcentaje");
		}
		if(!$scope.form.propuesta_estado){
			isValid = false;
			$("#propuestaestadoerror").text("Elija un estado");
		}
		/*if(!$scope.form.observaciones){
			isValid = false;
			$("#observacioneserror").text("Agregue alguna observación");
		}*/
		return isValid;
	}

	function uploadFile(id_cita){
		var upFiles = $scope.form.CITA_ARCHIVOS;
		
		upFiles["prospecto"] = { prospecto : $scope.id_prospecto, cita : id_cita, usuario : 0};
		Upload.upload({
		   	url: global_apiserver + "/cita_calendario/upload/",
			data: upFiles
		}).then(function (resp) {
		   console.log('Success uploaded. Response: ' + resp.data);
		}, function (resp) {
		    console.log('Error status: ' + resp.status);
		}, function (evt) {
		    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
		    console.log('progress: ' + progressPercentage + '% ');
		});	
	}

	$scope.getFileName = function (file){
		if(!file)
			return false;
		$scope.form.CITA_ARCHIVOS[file.name] = file;
	}
	$scope.delFileName = function (index){
		delete $scope.form.CITA_ARCHIVOS[index];
	}

	/*function isAdminUser(){
		$http.get(  global_apiserver + "/cita_calendario/getUsuarioById/?id="+id_user)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Usuario = {
	  					id_usuarios : response.data.id_usuarios,
	  					nombre : response.data.nombre,
	  					usuario : response.data.usuario,
	  					permisos : response.data.permisos,
	  			};
	  			$scope.Lista_Usuarios = [];
	  			if($scope.Usuario.permisos == "admin"){
	  				$http.get(  global_apiserver + "/cita_calendario/getUsuarios/")
				  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
				  			$scope.Lista_Usuarios = response.data.map(function(item){
				  				return{
				  					id_usuarios : item.id_usuarios,
				  					nombre : item.nombre
				  				}
				  			});
						},
						function (response){});
				}
			},
			function (response){});
	}
*/
	//Desplegar Asuntos
	/*
	$scope.AsuntoLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/cita_calendario/getTipoAsunto/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Asuntos = response.data.map(function(item){
	  				return{
	  					id_tipo_asunto : item.id_tipo_asunto,
	  					descripcion : item.descripcion,
	  					color : item.color
	  				}
	  			});
	  			
			},
			function (response){});
	  	
		$http.get(  global_apiserver + "/cita_calendario/getPorcentaje/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Porcentajes = response.data.map(function(item){
	  				return{
	  					id_porcentaje : item.id_porcentaje,
	  					porcentaje : item.porcentaje + " %",
	  					descripcion : item.descripcion
	  				}
	  			});
	  			
			},
			function (response){});
		
		$http.get(  global_apiserver + "/cita_calendario/getPropuestaEstado/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.PropuestasEstados = response.data.map(function(item){
	  				return{
	  					id_estado : item.id_estado,
	  					estado : item.estado
	  				}
	  			});
	  			
			},
			function (response){});
			
			$http.get(  global_apiserver + "/cotizaciones/getFolioByEntidad/?id="+$scope.id_prospecto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Folios = response.data.map(function(item){
	  				return{
	  					id_cotizacion : item.ID,
	  					folio : item.FOLIO
	  				}
	  			});
	  			
			},
			function (response){});
		
	}*/

        $(document).ready(function () {
            setCalendar();
            //isAdminUser();
            //$scope.AsuntoLista();
        });
        onCalendar();
}]);