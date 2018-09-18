var app = angular.module('certificandoApp', ['ngFileUpload']);
app.controller('calendario_documento_controller',['$scope','$http', 'Upload',function($scope,$http, Upload){
$scope.modulo_permisos =  global_permisos["EXPEDIENTES"];
$scope.form = {}; //objeto para guardar los datos del formulario	
$scope.accion = "";

//Esta función está en el archivo notify.js
//La usan para obtener el valor de un parámetro pasado por la URL
//Ej http://a.com/?id=1 la funcion devuelve 1
var id_serv_cli_et = getQueryVariable("id");
var id_documento = getQueryVariable("id_docum");
var ciclo = getQueryVariable("ciclo");
var id_user = sessionStorage.getItem("id_usuario");
var serv_cli_et = {};
$scope.ciclo	=	ciclo;


$scope.limpiar =	function (){
            $scope.form.id_calendario = 0;
			$scope.form.cmbTarea = "";
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

/*	$scope.cerrar1 = function(){
			
			$("#modalConfirmacion").modal("show");
			
			 
		}
		$scope.close = function(){
			$("#modalConfirmacion").modal("hide");
			$scope.limpiar();
			$("#modalCreateEvento").modal("hide");
		
		}*/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function DatosDocumentos(){
  
$.getJSON( global_apiserver + "/ver_expedientes/getIntegralById/?id="+id_serv_cli_et, function( response ) {
		   $scope.nombre_cliente =response[1].NOMBRE_CLIENTE;
		   $scope.tipo_servicio = response[1].NOMBRE_SERVICIO;
		   serv_cli_et = response;
		   $scope.$apply();
		});

$.getJSON(  global_apiserver + "/ver_expedientes/getCatDocumentoById/?id="+id_serv_cli_et+"&id_catag_docum="+id_documento+"&nombre_ciclo="+ciclo,
 function( response ) {
		   $scope.nombre_documento =response.NOMBRE;
		    
			$.getJSON(  global_apiserver + "/etapas_proceso/getById/?id_etapa="+response.ID_ETAPA,
			function( respuesta ) {
				$scope.nombre_etapa =respuesta.ETAPA;
				$scope.$apply();				
			});
			$.getJSON(  global_apiserver + "/ver_expedientes/getNombreSeccionById/?id_seccion="+response.ID_SECCION,
			function( respuesta1 ) {
				$scope.nombre_seccion =respuesta1.NOMBRE_SECCION;
				$scope.$apply();	
			});
				
			serv_cli_et = response;
			$scope.$apply();
		});
	
	
}

function setCalendar() {
            moment.locale('es');
            var right_menu = $scope.modulo_permisos["registrar"] == 1? 'month,agendaWeek,agendaDay,listWeek,newEvent' : 'month,agendaWeek,agendaDay,listWeek';
            var calendar = $('#calendario').fullCalendar({
                customButtons: {
                    newEvent: {
                        text: '+ Nuevo Evento',
                        click: function() { //desplegando el menu
                            $scope.bool_tarea = true;
                            $scope.bool_estado_tarea = true;
                        	$(".text-danger").empty();
                        	$scope.accion="insertar";
							$scope.desc_modificacion_tarea = "Descripcion";
                            $scope.limpiar();
                            $scope.$apply();
 //                       	$("#FI").show();
 //                   		$("#HI").show();
                    		$("#HF").show();
                    		//$("#up_files").hide();
                    		//$("#fechas").hide();
							$("#chkAprobarTarea").hide();
							
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
                    
                    
                    $http.get(global_apiserver + "/cita_calendario_documentos/getTareaById/?id="+calEvent.id)
                    .then(function(response){ //pasando los datos de la base a formato php
						//$scope.form.cmbTarea = response.data.ID;
						$scope.form.id_calendario=response.data.ID;
						$scope.form.cmbTarea = response.data.NOMBRE_TAREA;
                    	$scope.form.fecha_inicio = response.data.FECHA_INICIO;
						$scope.form.fecha_fin = response.data.FECHA_FIN;
						$scope.form.hora_inicio = response.data.HORA_INICIO;
						$scope.form.hora_fin = response.data.HORA_FIN;
						if((response.data.ESTADO==0)||(response.data.ESTADO==-1)){
								$scope.form.AprobarTarea	= false;
								$scope.bool_estado_tarea = true;
						}		
						else {
								$scope.form.AprobarTarea	= true;
								$scope.bool_estado_tarea = false;
						}		
	                    $scope.accion="editar";
	                    $scope.bool_tarea = false;
						
//	                    $("#FI").hide();
//	                    $("#HI").hide();
//	                    $("#HF").hide();
//	                   	$("#up_files").show();
//	                   	$("#fechas").show();
						$("#chkAprobarTarea").show();
						
						$scope.desc_modificacion_tarea = "Observacion";
	                   	$("#hist-button").hide();
						
	                    $(".text-danger").empty();
	                    $("#modalTitulo").text("Editar Tarea");
	                    $("#modalCreateEvento").modal("show");
	                })
                },
                events: function (start, end, timezone, callback) {


                   $http.get(  global_apiserver + "/cita_calendario_documentos/getByIdDocumento/?id="+id_serv_cli_et+"&id_documento="+id_documento+"&ciclo="+ciclo)//+"&entidad="+$scope.entidad)
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

function chkAprobTarea(){
$("#chkTarea").change(function() {
        if($(this).is(":checked")) {
			$scope.form.AprobarTarea	= true;
			$scope.form.observaciones	= "Se ha cerrado la tarea";
		//	$scope.bool_estado_tarea = false;
			
        }
        else{
			$scope.form.AprobarTarea	= false;
			$scope.form.observaciones	= "";
		//	$scope.bool_estado_tarea = true;
			
        }
		$scope.$apply();
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
				$.post(global_apiserver + "/cita_calendario_documentos/insert/", JSON.stringify(tareaObjeto), function(response){
					respuesta = JSON.parse(response);
				if (respuesta.resultado_tareas == "ok" && respuesta.resultado_hist == "ok") {
					//uploadFile(respuesta.id);
					$("#modalCreateEvento").modal("hide");
					notify("&Eacutexito", "Se ha insertado una nueva tarea", "success");
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
				$.post(global_apiserver + "/cita_calendario_documentos/update/", JSON.stringify(tareaObjeto), function(response){
					respuesta = JSON.parse(response);
				if (respuesta.resultado_tareas == "ok" && respuesta.resultado_hist == "ok") {
					//uploadFile(respuesta.id);
					$("#modalCreateEvento").modal("hide");
					notify("&Eacutexito", "Se ha modificado la tarea", "success");
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
				id	:	$scope.form.id_calendario,
				id_servicio : id_serv_cli_et,
				///////////////////////////////////////////////
				id_catag_docum :	id_documento,
				ciclo 	:	ciclo,
				nombre_tarea : $scope.form.cmbTarea,
				estado	:	$scope.form.AprobarTarea,
				///////////////////////////////////////////////
				//id_tarea : $scope.form.cmbTarea,
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
		if(!$scope.form.cmbTarea){
			isValid = false;
			$("#tareaerror").text("Escriba un nombre para la tarea");	
		}
		return isValid;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


       $(document).ready(function () {
//		DatosDocumentos();
           setCalendar();
            isAdminUser();
			chkAprobTarea();
		 
		
            //$scope.AsuntoLista();
//			$scope.TareasLista();
       });
	   DatosDocumentos();
        onCalendar();
			
		//	 $scope.limpiar();

}]);

	function getQueryVariable(variable) {
	  var query = window.location.search.substring(1);
	  var vars = query.split("&");
	  for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
		  return pair[1];
		}
	  } 
	  alert('Query Variable ' + variable + ' not found');
	}
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
	/////////////////////////////////////////////////////////////////////////
	
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	