var app = angular.module('certificandoApp', ['ngFileUpload']);
app.controller('cita_calendario_servicio_controller',['$scope','$http', 'Upload',function($scope,$http, Upload){
$scope.modulo_permisos =  global_permisos["CRM"];
$scope.form = {}; //objeto para guardar los datos del formulario	
$scope.accion = "";
$scope.file_url = "ExpedienteArchivos.php";
$scope.editar_tipo_tarea = true;
$scope.editar_descripcion_tarea = true;
$scope.tarea = 0;
//Esta función está en el archivo notify.js
//La usan para obtener el valor de un parámetro pasado por la URL
//Ej http://a.com/?id=1 la funcion devuelve 1
var id_serv_cli_et = getQueryVariable("id_serv_cli_et");
$scope.entidad = getQueryVariable("entidad");
var last_id_cotizacion = "";
var id_user = sessionStorage.getItem("id_usuario");

		$scope.limpiar =function(){
            $scope.form.id_calendario = 0;
	        $scope.form.observaciones = "";
	        $scope.form.fecha_inicio = "";
	        $scope.form.fecha_fin = "";
	        $scope.form.hora_inicio = null;
	        $scope.form.hora_fin = null;
	        $scope.form.usuario_asignado = $scope.Usuario.permisos == "admin"? 0 : id_user;
	        $scope.form.nombre_usuario_asignado = "";

	        $scope.form.CITA_ARCHIVOS = {};
	        $scope.form.FILE = null;
	        $scope.form.archivos = [];
		}
		$scope.cerrar = function(){
			/*
			$scope.limpiar();
             $("#modalCreateEvento").modal("hide");
			 */
		}
        function setCalendar() {
            moment.locale('es');
            var right_menu = $scope.modulo_permisos["registrar"] == 1? 'month,agendaWeek,agendaDay,listWeek,newEvent' : 'month,agendaWeek,agendaDay,listWeek';
            var calendar = $('#calendario').fullCalendar({
                customButtons: {
                    newEvent: {
                        text: '+ Nuevo Evento',
                        click: function() { //desplegando el menu
							//Cuando es insercón se puede editar el tipo de tarea pero no la descripción de la modificación
                            $scope.editar_tipo_tarea = true;
							$scope.editar_descripcion_tarea = false;
                            
                        	$(".text-danger").empty();
                        	$scope.accion="insertar";
                            $scope.limpiar();
                            $scope.$apply();
                        	$("#FI").show();
                    		$("#HI").show();
                    		$("#HF").show();
                    		//$("#up_files").hide();
                    		//$("#fechas").hide();							
							$("#hist-button").hide();
                    		$("#modalTitulo").text("Nueva Tarea");
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
                    
                    
                    $http.get(global_apiserver + "/cita_calendario_servicios/getTareaById/?id="+calEvent.id)
                    .then(function(response){ //pasando los datos de la base a formato php
						$scope.form.cmbTarea = response.data.ID_TAREA;
                    	$scope.form.fecha_inicio = response.data.FECHA_INICIO;
						$scope.form.fecha_fin = response.data.FECHA_FIN;
						$scope.form.hora_inicio = response.data.HORA_INICIO;
						$scope.form.hora_fin = response.data.HORA_FIN;
						$scope.tarea = response.data.ID;
						
						//Cuando es edición no se puede editar el tipo de tarea pero si la descripción de la modificación
                        $scope.editar_tipo_tarea = false;
						$scope.editar_descripcion_tarea = true;
                    	$scope.desc_modificacion_tarea = "Descripción de la Modificación";
						
	                    $scope.accion="editar";
						/*
	                    $("#FI").hide();
	                    $("#HI").hide();
	                    $("#HF").hide();
	                   	$("#up_files").show();
	                   	$("#fechas").show();
						*/
	                   	//$("#hist-button").show();
						
	                    $(".text-danger").empty();
	                    $("#modalTitulo").text("Editar Tarea");
	                    $("#modalCreateEvento").modal("show");
	                })
                },
                events: function (start, end, timezone, callback) {


                   $http.get(  global_apiserver + "/cita_calendario_servicios/getByIdServicio/?id="+id_serv_cli_et)//+"&entidad="+$scope.entidad)
	  					.then(function( response ) {//se ejecuta cuando la petición fue correcta
			  			var eventos = response.data.map(function(item){
			  				return{
			  					id: item.ID,
                                title: item.NOMBRE_TAREA,
                                start: item.FECHA_INICIO,
                                end : item.FECHA_FIN,
                                editable: false
                                //color: item.color
                                //id_cotizacion : item.id_cotizacion
                            }
			  			});
	  					//last_id_cotizacion = eventos.slice(-1)[0].id_cotizacion;
	  					callback(eventos);
					},
					function (response){});
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
				
				$('#fecha_fin').datepicker({
                    dateFormat: "mm/dd/yy",
                    minDate: "+0D",
                    onSelect: function (dateText, ins) {
                        $scope.form.fecha_fin = dateText;
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

				var tareaObjeto = formToPHPCREATE();
				$.post(global_apiserver + "/cita_calendario_servicios/insert/", JSON.stringify(tareaObjeto), function(response){
					respuesta = JSON.parse(response);
					if (respuesta.resultado_tareas == "ok" && respuesta.resultado_hist == "ok") {
					//uploadFile(respuesta.id);
						$("#modalCreateEvento").modal("hide");
						notify_success("Éxito", "Se ha insertado una nueva tarea");
						$('#calendario').fullCalendar('refetchEvents');
						$scope.limpiar();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
		    }
			else if ($scope.accion == "editar")
			{
				///VALIDACIONES
				if(!valida_fechas()){
					return false;
				}

				var tareaObjeto = formToPHPCREATE();
				$.post(global_apiserver + "/cita_calendario_servicios/update/", JSON.stringify(tareaObjeto), function(response){
					respuesta = JSON.parse(response);
					if (respuesta.resultado_tareas == "ok" && respuesta.resultado_hist == "ok") {
						//uploadFile(respuesta.id);
						$("#modalCreateEvento").modal("hide");
						notify_success("Éxito", "Se ha modificado la tarea");
						$('#calendario').fullCalendar('refetchEvents');
						$scope.limpiar();
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
				});
			}
		};


	function formToPHPCREATE(){ //obteniendo datos del formulario y se pasan a un json (mismo nombre que en el index.php)
		var aux_fechainicio = Date.parse($scope.form.fecha_inicio);
		aux_fechainicio.setHours(Date.parse($scope.form.hora_inicio).getHours());
		aux_fechainicio.setMinutes(Date.parse($scope.form.hora_inicio).getMinutes());
		
		var aux_fin = Date.parse($scope.form.fecha_fin);
		aux_fin.setHours(Date.parse($scope.form.hora_fin).getHours());
		aux_fin.setMinutes(Date.parse($scope.form.hora_fin).getMinutes());
		
		var tarea = {
				id: $scope.tarea,
				id_servicio : id_serv_cli_et,
				id_tipo_tarea : $scope.form.cmbTarea,
				observaciones : $scope.form.observaciones,
				fecha_inicio : aux_fechainicio.toString("yyyy-MM-dd HH:mm"),
				fecha_fin : aux_fin.toString("yyyy-MM-dd HH:mm"),
				hora_inicio : aux_fechainicio.toString("HH:mm"),
				hora_fin : aux_fin.toString("HH:mm"),
	  			id_usuario_modificacion : id_user
			};
		return tarea;
	}

//valida las fechas
function valida_fechas(){
		var isValid = true;
		if(Boolean($scope.form.fecha_fin) &&Boolean($scope.form.fecha_inicio) && Boolean($scope.form.hora_fin) && Boolean($scope.form.hora_inicio)){
			var aux_fechainicio = Date.parse($scope.form.fecha_inicio);
			var aux_fechafin = Date.parse($scope.form.fecha_fin);
			
			aux_fechainicio.setHours(Date.parse($scope.form.hora_inicio).getHours());
			aux_fechainicio.setMinutes(Date.parse($scope.form.hora_inicio).getMinutes());
			
			aux_fechafin.setHours(Date.parse($scope.form.hora_fin).getHours());
			aux_fechafin.setMinutes(Date.parse($scope.form.hora_fin).getMinutes());

			if(aux_fechainicio > aux_fechafin){
				isValid = false;
				$("#horafinerror").text("La hora de inicio no puede ser mayor a la hora final");
			}
		}
		if(!$scope.form.fecha_inicio){
			isValid = false;
			$("#fechainicioerror").text("Escriba una fecha");	
		}
		if(!$scope.form.fecha_fin){
			isValid = false;
			$("#fechafinerror").text("Escriba una fecha");	
		}
		if(!$scope.form.hora_fin){
			isValid = false;
			$("#horafinerror").text("Escriba una hora");	
		}
		if(!$scope.form.hora_inicio){
			isValid = false;
			$("#horainicioerror").text("Escriba una hora");	
		}
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

	function isAdminUser(){
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
//Desplegar Asuntos
	
	$scope.TareasLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/cita_calendario_servicios/getCatalogoTareas/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Tareas = response.data.map(function(item){
	  				return{
	  					id_tarea : item.ID,
	  					nombre_tarea : item.NOMBRE_TAREA,
	  					id_etapa : item.ID_ETAPA
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
			
			$http.get(  global_apiserver + "/cotizaciones/getFolioByEntidad/?id="+id_serv_cli_et)//$scope.id_prospecto)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Folios = response.data.map(function(item){
	  				return{
	  					id_cotizacion : item.ID,
	  					folio : item.FOLIO
	  				}
	  			});
	  			
			},
			function (response){});
		
	}

        $(document).ready(function () {
            setCalendar();
            isAdminUser();
            //$scope.AsuntoLista();
			$scope.TareasLista();
        });
        onCalendar();
}]);