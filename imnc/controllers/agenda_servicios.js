

  var global_calendar;

  function fill_select_tipos_servicio(arrTipoServicio, val){
    var strHtml = '';
    $("#selectTiposServicio").html('<option value="" selected>-ninguno-</option>');

    for (var i = 0; i < arrTipoServicio.length; i++) {
      objTipoServicio = arrTipoServicio[i];
      //console.log(objTipoServicio);
      $("#selectTiposServicio").append('<option value="'+objTipoServicio.ID_TIPO_SERVICIO+'">'+objTipoServicio.NOMBRE+'</option>');
    }
    $("#selectTiposServicio").val(val);
  }

  function fill_select_sectores(arrSectores, val){
    var strHtml = '';
    $("#selectSectores").html('<option value="" selected>-ninguno-</option>');

    for (var i = 0; i < arrSectores.length; i++) {
      objSector = arrSectores[i];
      //console.log(objSector);
      $("#selectSectores").append('<option value="'+objSector.ID_SECTOR+'">'+objSector.NOMBRE+'</option>');
    }
    $("#selectSectores").val(val);
  }

  function fill_select_referencias(arrReferencias, val){
    var strHtml = '';
    $("#selectReferencias").html('<option value="" selected>-ninguno-</option>');

    for (var i = 0; i < arrReferencias.length; i++) {
      objReferencia = arrReferencias[i];
      //console.log(objReferencia);
      $("#selectReferencias").append('<option value="'+objReferencia.REFERENCIA+'">'+objReferencia.REFERENCIA+'</option>');
    }
    $("#selectReferencias").val(val);
  }


function fill_select_clientes(arrClientes, val){
    var strHtml = '';
    $("#selectClientes").html('<option value="" selected>-ninguno-</option>');

    for (var i = 0; i < arrClientes.length; i++) {
      objCliente = arrClientes[i];
      //console.log(objCliente);
      $("#selectClientes").append('<option value="'+objCliente.ID_CLIENTE+'">'+objCliente.NOMBRE+'</option>');
    }
    $("#selectClientes").val(val);
}


var app = angular.module('certificandoApp');
app.controller('tareas_controller',['$scope','$http',function($scope,$http){
	$scope.id_serv_cli_et = 0;
	var id_user = sessionStorage.getItem("id_usuario");
	
	$scope.draw_calendario = function() {
		var eventos = [];
		var filtros = {
			TIPO_SERVICIO:$("#selectTiposServicio").val(),
			SECTOR:$("#selectSectores").val(),
			REFERENCIA:$("#selectReferencias").val(),
			CLIENTE:$("#selectClientes").val()
		};
		$(".loading").show();
		//Codigo que carga las auditorias programadas
		$.post(global_apiserver + "/sg_auditorias/getFechas/", JSON.stringify(filtros),function(response){
			response = JSON.parse(response);
			$.each(response.FECHAS, function( indice, objAuditoria ) {
				if (objAuditoria.FECHA_AUDITORIA !== null) {
					var f_ini= objAuditoria.FECHA_AUDITORIA;
					var anhio_ini = parseInt(f_ini.substring(0,4));
					var mes_ini = parseInt(f_ini.substring(4,6))-1; //En js los meses comienzan en 0
					var dia_ini = parseInt(f_ini.substring(6,8));
					
					eventos.push(
						{
							title: 'Ref: ' + objAuditoria.REFERENCIA + " (" + objAuditoria.ID_TIPO_SERVICIO + ")",
							start: new Date(anhio_ini, mes_ini, dia_ini, 07, 0),
							end: new Date(anhio_ini, mes_ini, dia_ini, 18, 30),
							allDay: false,
							color: "#3e5a23",
							url: './?pagina=sg_tipos_servicio&id_serv_cli_et='+objAuditoria.ID_SERVICIO_CLIENTE_ETAPA +'&sg_tipo_servicio='+objAuditoria.ID_SG_TIPO_SERVICIO,
						}
					)
				}
			});
			fill_select_tipos_servicio(response.TIPOS_SERVICIO, response.FILTROS.TIPO_SERVICIO);
			fill_select_sectores(response.SECTORES, response.FILTROS.SECTOR);
			fill_select_referencias(response.REFERENCIAS, response.FILTROS.REFERENCIA);
			fill_select_clientes(response.CLIENTES, response.FILTROS.CLIENTE);
			
			if(global_calendar !== undefined)
			{
				global_calendar.fullCalendar('destroy');
			}
			
			global_calendar = $('#calendar').fullCalendar({
			 header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
				 minTime:"07:00:00",
				 allDaySlot:false,
				 selectable: false,
				 selectHelper: true,
				 editable: false,
				 //eventBackgroundColor:"#3e5a23",
				 defaultDate:eventos[eventos.length-1].start,
				 events: eventos,
				 eventClick: function (calEvent, jsEvent, view) {
					if(calEvent.id)
					{
						$http.get(global_apiserver + "/cita_calendario_servicios/getTareaById/?id="+calEvent.id)
						.then(function(response){ //pasando los datos de la base a formato php
							$scope.cmbTarea = response.data.ID_TAREA;
							$scope.fecha_inicio = response.data.FECHA_INICIO;
							$scope.fecha_fin = response.data.FECHA_FIN;
							$scope.hora_inicio = response.data.HORA_INICIO;
							$scope.hora_fin = response.data.HORA_FIN;
							$scope.tarea = response.data.ID;
							
							//Cuando es edición no se puede editar el tipo de tarea pero si la descripción de la modificación
							$scope.editar_tipo_tarea = false;
							$scope.editar_descripcion_tarea = true;
							$scope.desc_modificacion_tarea = "Descripción de la Modificación";
							//Recibe como parametro el id de la tarea
							$scope.cargarAuditorias(calEvent.id);
							
							$scope.accion="editar";
							
							$(".text-danger").empty();
							$("#modalTitulo").text("Editar Tarea");
							$("#modalCreateEvento").modal("show");
						});
					}
				}
			});
			$(".select2_single").select2({});		
			$(".loading").hide();
			 //Fin de la carga de auditorias programadas
		});
		//Ahora es necesario cargar las tareas programadas
		 $(".loading").show();
		$.post(global_apiserver + "/cita_calendario_servicios/getAll/", JSON.stringify(filtros),function(response){
			response = JSON.parse(response);
			$.each(response, function( indice, objTarea ) {
			  //if (objAuditoria.FECHA_AUDITORIA != null) {
				var fecha_inicio = Date.parse(objTarea.FECHA_INICIO);
				fecha_inicio.setHours(Date.parse(objTarea.HORA_INICIO).getHours());
				fecha_inicio.setMinutes(Date.parse(objTarea.HORA_INICIO).getMinutes());
				
				var fecha_fin = Date.parse(objTarea.FECHA_FIN);
				fecha_fin.setHours(Date.parse(objTarea.HORA_FIN).getHours());
				fecha_fin.setMinutes(Date.parse(objTarea.HORA_FIN).getMinutes());
				
				eventos.push(
				  {
					id: objTarea.ID,
					title: 'Tarea: ' + objTarea.NOMBRE_TAREA + " " + objTarea.CLIENTE + " " + objTarea.ID_SERVICIO,
					start: fecha_inicio,
					end: fecha_fin,
					allDay: false,
					color: "##4682B4",
				  }
				);
			});
			if(global_calendar !== undefined)
			{
				global_calendar.fullCalendar('destroy');
			}
			global_calendar = $('#calendar').fullCalendar({
			 header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
				minTime:"07:00:00",
				allDaySlot:false,
				selectable: false,
				selectHelper: true,
				editable: false,
				 //eventBackgroundColor:"#3e5a23",
				defaultDate:eventos[eventos.length-1].start,
				events: eventos,
				eventClick: function (calEvent, jsEvent, view) {
					if(calEvent.id)
					{
						$http.get(global_apiserver + "/cita_calendario_servicios/getTareaById/?id="+calEvent.id)
						.then(function(response){ //pasando los datos de la base a formato php
							$scope.id_serv_cli_et = response.data.ID_SERVICIO;
							$scope.cmbTarea = response.data.ID_TAREA;
							$scope.fecha_inicio = response.data.FECHA_INICIO;
							$scope.fecha_fin = response.data.FECHA_FIN;
							$scope.hora_inicio = response.data.HORA_INICIO;
							$scope.hora_fin = response.data.HORA_FIN;
							$scope.tarea = response.data.ID;
							
							//Cuando es edición no se puede editar el tipo de tarea pero si la descripción de la modificación
							$scope.editar_tipo_tarea = false;
							$scope.editar_descripcion_tarea = true;
							$scope.desc_modificacion_tarea = "Descripción de la Modificación";
							//Funcion para buscar las posibles auditorias para relacionar con esta tarea
							//Recibe como parametro el id de la tarea
							$scope.cargarAuditorias(calEvent.id);
							
							$scope.accion="editar";
							
							$(".text-danger").empty();
							$("#modalTitulo").text("Editar Tarea");
							$("#modalCreateEvento").modal("show");
						});
					}
				}
			});
			$(".select2_single").select2({});		
			$(".loading").hide();
			 //Fin de la caarga de las atreas programadas
			/*
			fill_select_tipos_servicio(response.TIPOS_SERVICIO, response.FILTROS.TIPO_SERVICIO);
			fill_select_sectores(response.SECTORES, response.FILTROS.SECTOR);
			fill_select_referencias(response.REFERENCIAS, response.FILTROS.REFERENCIA);
			fill_select_clientes(response.CLIENTES, response.FILTROS.CLIENTE);
			*/        
		});
	}
	$scope.cargarAuditorias = function (id_tarea){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/cita_calendario_servicios/getAuditoriasByIdTarea/?id="+id_tarea)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Auditorias = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.TIPO
	  				}
	  			});
	  			
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
		var aux_fechainicio = Date.parse($scope.fecha_inicio);
		aux_fechainicio.setHours(Date.parse($scope.hora_inicio).getHours());
		aux_fechainicio.setMinutes(Date.parse($scope.hora_inicio).getMinutes());
		
		var aux_fin = Date.parse($scope.fecha_fin);
		aux_fin.setHours(Date.parse($scope.hora_fin).getHours());
		aux_fin.setMinutes(Date.parse($scope.hora_fin).getMinutes());
		
		var tarea = {
				id: $scope.tarea,
				id_servicio : $scope.id_serv_cli_et,
				id_tipo_tarea : $scope.cmbTarea,
				id__auditoria : $scope.cmbAuditorias,
				observaciones : $scope.observaciones,
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
		if(Boolean($scope.fecha_fin) &&Boolean($scope.fecha_inicio) && Boolean($scope.hora_fin) && Boolean($scope.hora_inicio)){
			var aux_fechainicio = Date.parse($scope.fecha_inicio);
			var aux_fechafin = Date.parse($scope.fecha_fin);
			
			aux_fechainicio.setHours(Date.parse($scope.hora_inicio).getHours());
			aux_fechainicio.setMinutes(Date.parse($scope.hora_inicio).getMinutes());
			
			aux_fechafin.setHours(Date.parse($scope.hora_fin).getHours());
			aux_fechafin.setMinutes(Date.parse($scope.hora_fin).getMinutes());

			if(aux_fechainicio > aux_fechafin){
				isValid = false;
				$("#horafinerror").text("La hora de inicio no puede ser mayor a la hora final");
			}
		}
		if(!$scope.fecha_inicio){
			isValid = false;
			$("#fechainicioerror").text("Escriba una fecha");	
		}
		if(!$scope.fecha_fin){
			isValid = false;
			$("#fechafinerror").text("Escriba una fecha");	
		}
		if(!$scope.hora_fin){
			isValid = false;
			$("#horafinerror").text("Escriba una hora");	
		}
		if(!$scope.hora_inicio){
			isValid = false;
			$("#horainicioerror").text("Escriba una hora");	
		}
		return isValid;
	}
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
	  			
			});
	}
	function onCalendar() {
            $(document).ready(function () {

                $('#fecha_inicio').datepicker({
                    dateFormat: "mm/dd/yy",
                    minDate: "+0D",
                    onSelect: function (dateText, ins) {
                        $scope.fecha_inicio = dateText;
                    }
                }).css("display", "inline-block");
				
				$('#fecha_fin').datepicker({
                    dateFormat: "mm/dd/yy",
                    minDate: "+0D",
                    onSelect: function (dateText, ins) {
                        $scope.fecha_fin = dateText;
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
                    	$scope.hora_inicio = dateText;
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
                        $scope.hora_fin = dateText;
                    }
                }).css("display", "inline-block");
            });
        }
	$scope.draw_calendario();
	$scope.TareasLista();
	onCalendar();
		$('.select2_single').on('select2:select', function (evt) {
		  global_calendar.fullCalendar('destroy');
		  $scope.draw_calendario();
		});
}]);
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
/*
   $( window ).load(function() {
      draw_calendario();
  });
*/
  

