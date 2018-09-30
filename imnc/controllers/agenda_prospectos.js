app.controller('agenda_prospectos_controller',['$scope','$http' ,function($scope,$http){

$scope.form = {}; //objeto para guardar los datos del formulario	
$scope.filter = {};
$scope.file_url = "ExpedienteArchivos.php";
var allEvents = true;
var id_user = sessionStorage.getItem("id_usuario");
$scope.mostrar_guardar = true;
$scope.mostrar_cerrar = true;
		


        function setCalendar() {
            moment.locale('es');
            var calendar = $('#calendario').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek,newEvent'
                },
				defaultView: 'agendaDay',
                locale: 'es',
                navLinks: true,
                editable: true,
                eventLimit: true,
                eventClick: function (calEvent, jsEvent, view) {
                    //Cuando se da click en un evento se cargan sus datos en el editor
					var fecha_inicio_total = new Date(calEvent.start);
					var fecha_fin_total = new Date(calEvent.end);
					var fecha_inicio = (fecha_inicio_total.getMonth()+1)+"/"+fecha_inicio_total.getDate()+"/"+fecha_inicio_total.getFullYear();
					var fecha_fin = (fecha_fin_total.getMonth()+1)+"/"+fecha_fin_total.getDate()+"/"+fecha_fin_total.getFullYear();
					var hora_inicio = ("0"+fecha_inicio_total.getHours()).substr(-2)+":"+("0"+fecha_inicio_total.getMinutes()).substr(-2);
					var hora_fin = ("0"+fecha_fin_total.getHours()).substr(-2)+":"+("0"+fecha_fin_total.getMinutes()).substr(-2);
					
					$("#cmbProspecto").val("string:"+calEvent.id_prospecto).trigger('change.select2');
					$("#fecha_inicio").val(fecha_inicio);
					$("#hora_inicio").val(hora_inicio);
					$("#fecha_fin").val(fecha_fin);
					$("#hora_fin").val(hora_fin);
					$("#cmbTipoAsunto").val("string:"+calEvent.tipo_asunto);
					$("#descripcion").val(calEvent.desc_tarea);
					
					//Llena la tabla historial de tareas del prospecto
					cargarHistorial(calEvent.id_prospecto);
					cargarContactos(calEvent.id_prospecto);
					//Configurar el boton guardar y cerrar
					if(calEvent.estado_tarea == "PENDIENTE")
					{
						 $("#btnCerrarTarea").attr("estado_tarea","PENDIENTE");
						 $("#btnCerrarTarea").html("Guardar y Cerrar");
						 $scope.mostrar_guardar = true;
						$scope.mostrar_cerrar = true;
					}
					else
					{
						$scope.mostrar_guardar = false;
						$scope.mostrar_cerrar = false;
						/*
						$("#btnCerrarTarea").attr("estado_tarea","Cerrado");
						$("#btnCerrarTarea").html("Tarea Cerrada");
						$("#btnCerrarTarea").attr('disabled','disabled');
						*/
					
					}
					//Estado del prospecto
					$http.get(  global_apiserver + "/prospecto/getById/?id="+calEvent.id_prospecto)
					.then(function( response ) {//se ejecuta cuando la petición fue correcta
						$("#cmbEstatus").val("string:"+response.data.ID_ESTATUS_SEGUIMIENTO);
						$("#cmbPorcentaje").val("string:"+response.data.ID_PORCENTAJE);
						$scope.usuariosP = response.data.ID_USUARIO_PRINCIPAL;
						$scope.usuariosS = response.data.ID_USUARIO_SECUNDARIO;
					},
					function (response){});
					//Configuro el boton guardar en modo editar
					$("#btnGuardarTarea").attr("accion","editar");
                },
                events: function (start, end, timezone, callback) {
                   $http.get(  global_apiserver + "/prospecto_tareas/getAll/?id_usuario="+id_user)
	  					.then(function( response ) {//se ejecuta cuando la petición fue correcta
			  			var eventos = response.data.map(function(item){
							var fecha_inicio = Date.parse(item.fecha_inicio);
							fecha_inicio.setHours(Date.parse(item.hora_inicio).getHours());
							fecha_inicio.setMinutes(Date.parse(item.hora_inicio).getMinutes());
							
							var fecha_fin = Date.parse(item.fecha_fin);
							fecha_fin.setHours(Date.parse(item.hora_fin).getHours());
							fecha_fin.setMinutes(Date.parse(item.hora_fin).getMinutes());
							var color = "";
							if(item.estado_tarea == "PENDIENTE")
							{
								//Gris para los pendientes
								color = "#bebebe";
							}
							else
							{
								//Azul para las tareas completadas
								color = "#0000FF";
							}
							//Guardo el id de la tarea
							$("#btnGuardarTarea").attr("id_tarea",item.id);
			  				return{
			  					id: item.id,
                                title: item.nombre_prospecto,
                                start: fecha_inicio,
                                end : fecha_fin,
                                editable: false,
                                color: color,
                                tipo_asunto : item.tipo_asunto,
                                usuario : item.usuario,
								desc_tarea : item.desc_tarea,
								id_prospecto : item.id_prospecto,
								estado_tarea: item.estado_tarea
                            }
			  			});
	  					callback(eventos);
					},
					function (response){});
                },
				dayClick: function(date, jsEvent, view, resourceObj) {

				}
            });
			$('#calendario').fullCalendar( 'today' );
        }
		function cargarHistorial(id_prospecto){
			//Limpiar el historial
			$scope.Historial = "";
			$http.get(  global_apiserver + "/prospecto_tareas/getByProspecto/?id_prospecto="+id_prospecto)
					.then(function( response ) {//se ejecuta cuando la petición fue correcta
						$scope.Historial = response.data.map(function(item){
							return{
							    ////////////////////////////////////////
								hora_inicio	:	item.hora_inicio,
								fecha_fin	:	item.fecha_fin,
								hora_fin	:   item.hora_fin,
								tipo_asunto :   item.tipo_asunto,
							////////////////////////////////////////
								fecha : item.fecha_inicio,
								asunto : item.desc_asunto,
								descripcion: item.desc_tarea,
								estado: item.estado_tarea
							}
						});
					},
					function (response){});			
		}
		function cargarContactos(id_prospecto){
			var ruta = global_apiserver + "/prospecto/getContactos/?id="+id_prospecto;
			  $http.get(  ruta)
					.then(function( response ) {//se ejecuta cuando la petición fue correcta
						$scope.Contactos = response.data.map(function(item){
							return{
								nombre_contacto: item.NOMBRE_CONTACTO,
								ubicacion : item.ESTADO,
								correo : item.CORREO,
								telefono: item.TELEFONO,
								celular: item.CELULAR
							}
						});
					},
					function (response){});
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
	$scope.UsuariosLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/usuarios/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Usuarios = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					descripcion : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.ProspectosLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto/getByIdUsuario/?id="+id_user)
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Prospectos = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.NOMBRE
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.TipoAsuntoLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/tipo_asunto/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.TiposAsunto = response.data.map(function(item){
	  				return{
	  					id : item.id_tipo_asunto,
	  					nombre : item.descripcion
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.EstatusLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_estatus_seguimiento/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Estatus = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.DESCRIPCION
	  				}
	  			});
	  			
			},
			function (response){});
	}
	$scope.PorcentajeLista = function(){
		//recibe la url del php que se ejecutará
		$http.get(  global_apiserver + "/prospecto_porcentaje/getAll/")
	  		.then(function( response ) {//se ejecuta cuando la petición fue correcta
	  			$scope.Porcentajes = response.data.map(function(item){
	  				return{
	  					id : item.ID,
	  					nombre : item.PORCENTAJE
	  				}
	  			});
	  			
			},
			function (response){});
	}
//Desplegar Asuntos
	
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
		
	}
	////////////////////////////////////////////////////////////////////////////
	//	FUNCION PARA EDITAR TAREA
	$scope.editarTarea = function(fecha_inicio,hora_inicio,fecha_fin,hora_fin,asunto,descripcion)
	{
		$("#fecha_inicio").val(fecha_inicio);
		$("#hora_inicio").val(hora_inicio);
		$("#fecha_fin").val(fecha_fin);
		$("#hora_fin").val(hora_fin);
		$("#cmbTipoAsunto").val("string:"+asunto);
		$("#descripcion").val(descripcion);
		
	}
	////////////////////////////////////////////////////////////////////////////////////	
	$scope.cerrarTarea = function()
	{
		var tipo_accion = $("#btnGuardarTarea").attr("accion");
		//Validaciones de fecha
		var fecha_inicio = Date.parse($("#fecha_inicio").val());
		fecha_inicio.setHours(Date.parse($("#hora_inicio").val()).getHours());
		fecha_inicio.setMinutes(Date.parse($("#hora_inicio").val()).getMinutes());
		fecha_inicio = fecha_inicio.getTime();
							
		var fecha_fin = Date.parse($("#fecha_fin").val());
		fecha_fin.setHours(Date.parse($("#hora_fin").val()).getHours());
		fecha_fin.setMinutes(Date.parse($("#hora_fin").val()).getMinutes());
		fecha_fin = fecha_fin.getTime();
		
		if(fecha_fin < fecha_inicio)
		{
			notify("Error", "Las fechas capturadas no son correctas", "error");
		}
		else
		{
			if(tipo_accion == "guardar")
			{
				var tarea = {
					id_prospecto: $("#cmbProspecto").val().substring(7),
					fecha_inicio: $("#fecha_inicio").val(),
					hora_inicio: $("#hora_inicio").val(),
					fecha_fin: $("#fecha_fin").val(),
					hora_fin: $("#hora_fin").val(),
					tipo_asunto: $("#cmbTipoAsunto").val().substring(7),
					descripcion: $("#descripcion").val(),
					estado: "CERRADO"
				};
				$.post(global_apiserver + "/prospecto_tareas/insert/", JSON.stringify(tarea), function(response){
							respuesta = JSON.parse(response);
						if (respuesta.resultado == "ok") {
							//uploadFile(respuesta.id);
							notify_success("Éxito", "Se ha insertado la tarea");
						}
						else{
							notify("Error", respuesta.mensaje, "error");
						}
				});
			}
			if(tipo_accion == "editar")
			{
				var tarea = {
					id_tarea: $("#btnGuardarTarea").attr("id_tarea"),
					id_prospecto: $("#cmbProspecto").val().substring(7),
					fecha_inicio: $("#fecha_inicio").val(),
					hora_inicio: $("#hora_inicio").val(),
					fecha_fin: $("#fecha_fin").val(),
					hora_fin: $("#hora_fin").val(),
					tipo_asunto: $("#cmbTipoAsunto").val().substring(7),
					descripcion: $("#descripcion").val(),
					estado: "CERRADO"
				};
				$.post(global_apiserver + "/prospecto_tareas/update/", JSON.stringify(tarea), function(response){
							respuesta = JSON.parse(response);
						if (respuesta.resultado == "ok") {
							//uploadFile(respuesta.id);
							notify_success("Éxito", "Se ha actualizado la tarea");
						}
						else{
							notify("Error", respuesta.mensaje, "error");
						}
				});
			}
			$scope.limpiarEditor();
		}
	}
	$scope.guardarTarea = function()
	{
		var tipo_accion = $("#btnGuardarTarea").attr("accion");
		//Validaciones de fecha
		var fecha_inicio = Date.parse($("#fecha_inicio").val());
		fecha_inicio.setHours(Date.parse($("#hora_inicio").val()).getHours());
		fecha_inicio.setMinutes(Date.parse($("#hora_inicio").val()).getMinutes());
		fecha_inicio = fecha_inicio.getTime();
							
		var fecha_fin = Date.parse($("#fecha_fin").val());
		fecha_fin.setHours(Date.parse($("#hora_fin").val()).getHours());
		fecha_fin.setMinutes(Date.parse($("#hora_fin").val()).getMinutes());
		fecha_fin = fecha_fin.getTime();
		
		if(fecha_fin < fecha_inicio)
		{
			notify("Error", "Las fechas capturadas no son correctas", "error");
		}
		else
		{
			if(tipo_accion=="guardar")
			{
				var tarea = {
					id_prospecto: $("#cmbProspecto").val().substring(7),
					fecha_inicio: $("#fecha_inicio").val(),
					hora_inicio: $("#hora_inicio").val(),
					fecha_fin: $("#fecha_fin").val(),
					hora_fin: $("#hora_fin").val(),
					tipo_asunto: $("#cmbTipoAsunto").val().substring(7),
					descripcion: $("#descripcion").val(),
					estado: "PENDIENTE"
				};
				$.post(global_apiserver + "/prospecto_tareas/insert/", JSON.stringify(tarea), function(response){
							respuesta = JSON.parse(response);
						if (respuesta.resultado == "ok") {
							//uploadFile(respuesta.id);
							notify_success("Éxito", "Se ha insertado la tarea");
						}
						else{
							notify("Error", respuesta.mensaje, "error");
						}
				});
			}
			if(tipo_accion=="editar")
			{
				var tarea = {
					id_tarea: $("#btnGuardarTarea").attr("id_tarea"),
					id_prospecto: $("#cmbProspecto").val().substring(7),
					fecha_inicio: $("#fecha_inicio").val(),
					hora_inicio: $("#hora_inicio").val(),
					fecha_fin: $("#fecha_fin").val(),
					hora_fin: $("#hora_fin").val(),
					tipo_asunto: $("#cmbTipoAsunto").val().substring(7),
					descripcion: $("#descripcion").val(),
					estado: "PENDIENTE"
				};
				$.post(global_apiserver + "/prospecto_tareas/update/", JSON.stringify(tarea), function(response){
							respuesta = JSON.parse(response);
						if (respuesta.resultado == "ok") {
							//uploadFile(respuesta.id);
							notify_success("Éxito", "Se ha actualizado la tarea");
						}
						else{
							notify("Error", respuesta.mensaje, "error");
						}
				});
			}
			$scope.limpiarEditor();
		}
	}
	$scope.cambioPorcentaje = function(){
		var prospecto = {
		ID_PROSPECTO: $("#cmbProspecto").val().substring(7),
		PORCENTAJE: $("#cmbPorcentaje").val().substring(7),
		ESTATUS: $("#cmbEstatus").val().substring(7)
		};
		$.post(global_apiserver + "/prospecto/updatePorcentajeyEstado/", JSON.stringify(prospecto), function(response){
						respuesta = JSON.parse(response);
					if (respuesta.resultado == "ok") {
						notify_success("Éxito", "Se ha actualizado el prospecto");
					}
					else{
						notify("Error", respuesta.mensaje, "error");
					}
			});
	}
    $scope.limpiarEditor = function(){
        $("#cmbProspecto").val("").trigger('change.select2');
		$("#fecha_inicio").val("");
		$("#hora_inicio").val("");
		$("#fecha_fin").val("");
		$("#hora_fin").val("");
		$("#cmbTipoAsunto").val("");
		$("#descripcion").val("");
		$scope.Contactos = ""; //Limpiar lista de contactos
		$scope.Historial = ""; //Limpiar el historial de tareas
		$("#cmbEstatus").val("");
		$("#cmbPorcentaje").val("");
		/*
		$('#calendario').fullCalendar('removeEvents');
		$('#calendario').fullCalendar('refetchEvents');
		$('#calendario').fullCalendar('rerenderEvents');
		*/
		window.location.href = './?pagina=agenda_prospectos';
		
    }
	$scope.filtrarEventos = function(op){
		allEvents = op;
		if(allEvents){
			$scope.filter = {};
		}
		$( '#calendario' ).fullCalendar( 'rerenderEvents' );
	}
	function onCalendar()
	{
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
                    onSelect : function (dateText, ins) {
                    	$scope.hora_fin = dateText;
                    }
                    
                }).css("display", "inline-block");
				
				$(".select2_single").select2({});
	}
        $(document).ready(function () {
			setCalendar();
			onCalendar();
            $scope.AsuntoLista();
			$scope.ProspectosLista();
			$scope.TipoAsuntoLista();
			$scope.EstatusLista();
			$scope.PorcentajeLista();
			$scope.UsuariosLista();
        });
		$('.select2_single').on('select2:select', function (evt) {
			var id = $("#cmbProspecto").val().substring(7);
			cargarHistorial(id);
			cargarContactos(id);
			
			$scope.mostrar_guardar = true;
			$scope.mostrar_cerrar = true;
					
			//Configuro guardar y cerrar en modo pendiente
			$("#btnCerrarTarea").attr("estado_tarea","PENDIENTE");
			$("#btnCerrarTarea").html("Guardar y Cerrar");
			//Configuro el boton guardar en modo guardar
			$("#btnGuardarTarea").attr("accion","guardar");
			//Estado del prospecto		
				$http.get(  global_apiserver + "/prospecto/getById/?id="+id)
					.then(function( response ) {//se ejecuta cuando la petición fue correcta
						$("#cmbEstatus").val("string:"+response.data.ID_ESTATUS_SEGUIMIENTO);
						$("#cmbPorcentaje").val("string:"+response.data.ID_PORCENTAJE);
						$scope.usuariosP = response.data.ID_USUARIO_PRINCIPAL;
						$scope.usuariosS = response.data.ID_USUARIO_SECUNDARIO;
					},
					function (response){});
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