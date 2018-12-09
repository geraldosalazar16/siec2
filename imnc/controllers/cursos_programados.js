/**
 * @ngdoc controller
 * @name controller:cursos_programados
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('cursos_programados_controller',['$scope','$http',function($scope,$http){

    $scope.formData = {};
    $scope.global_calendar;
    $scope.id_evento_select = '';


// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarInstructores= function(){
		$http.get(  global_apiserver + "/personal_tecnico/getAll/")
		.then(function( response ){
			$scope.instructores = response.data;
		});
	}



// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarCursos = function(){
		$http.get(  global_apiserver + "/cursos/getAll/")
		.then(function( response ){
			$scope.cursos = response.data;
		});
	}

// ===================================================================
// ***** 			FUNCION PARA ELIMINAR EVENTO				 *****
// ===================================================================
    $scope.eliminaEvento = function(){
        if(confirm("¿Estás seguro que desea eliminar este evento?"))
        {

            var curso = { ID: $scope.id_evento_select}
            $.post( global_apiserver + "/cursos_programados/delete/", JSON.stringify(curso), function(respuesta){
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $("#modalMostrar").modal("hide");
                    notify("Éxito", "Se ha eliminado el evento", "success");
                    $scope.onAgenda();
                    //document.location = "./?pagina=auditores";
                }
                else{
                    notify("Error", respuesta.mensaje, "error");
                }

            });
        }else{

        }
    }

// =======================================================================================
// ***** 			FUNCION PARA ACTUALIZAR DATOS MODAL MOSTRAR		                 *****
// =======================================================================================
    $scope.actualizaDatos = function() {
        $.getJSON(global_apiserver + "/cursos_programados/getById/?id=" + $scope.id_evento_select, function (response) {
            var dias = "";
            if (response.DIAS == 0)
                dias = ' 1 día ';
            else
                dias = ' ' + response.DIAS + ' días ';
            $scope.txtDuracion = dias;
            $scope.txtCurso = response.NOMBRE_CURSO;
            $scope.txtInstructor = response.NOMBRE_AUDITOR.NOMBRE + " " + response.NOMBRE_AUDITOR.APELLIDO_PATERNO + " " + response.NOMBRE_AUDITOR.APELLIDO_MATERNO;
            var fechas = response.FECHA_INICIO + " - " + response.FECHA_FIN;
            if (response.FECHA_INICIO == response.FECHA_FIN)
                fechas = response.FECHA_INICIO;
            $scope.txtFechas = fechas;
            $scope.txtMinimo = response.PERSONAS_MINIMO;
            $scope.$apply();

        });
        if ($scope.accion == "editar")
        {
            mytoggle("divMostrar");
            mytoggle("divInsertar");
        }


        if ($scope.accion == "mostrar")
        {
            $("#divInsertar").hide();
            $("#divMostrar").show();
        }



        //$("#divInsertar").hide();
        //$("#divMostrar").show();
    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.openModalMostar = function(){
        $scope.accion = "mostrar";
        $scope.actualizaDatos();
        $("#modalMostrar").modal("show");

    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL INSERTAR/MODIFICAR		 *****
// =======================================================================================
$scope.openModalInsertarModificar = function(accion){


        $scope.modal_titulo = "Agregar Evento";
		$scope.accion = accion;
		clear_modal_insertar_actualizar()
        $scope.cargarCursos();
        $scope.cargarInstructores();
        if(accion == 'editar')
        {

            $scope.modal_titulo = "Modificando Evento";
            $.getJSON( global_apiserver + "/cursos_programados/getById/?id="+$scope.id_evento_select, function( response ) {
                $scope.formData.selectCurso = response.ID_CURSO;
                $scope.formData.instructor = response.ID_INSTRUCTOR;
                $scope.formData.fecha_inicio = response.FECHA_INICIO;
                $scope.formData.fecha_fin = response.FECHA_FIN;
                $scope.formData.minimo = response.PERSONAS_MINIMO;
                $scope.$apply();

            });

            mytoggle("divMostrar");
            mytoggle("divInsertar");
            /*$("#divMostrar").hide();
            $("#divInsertar").show();*/


        }
        else
        {
            $("#divInsertar").show();
            $("#divMostrar").hide();
            $("#modalMostrar").modal("show");
        }



        onCalendario();
	}


// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal			 *****
// ===========================================================================
function clear_modal_insertar_actualizar(){
    $scope.formData.selectCurso = '';
    $scope.formData.instructor = '';
    $scope.formData.minimo = '';
    $scope.formData.fecha_inicio = '';
    $scope.formData.fecha_fin = '';

    $("#txtcursoerror").text("");
    $("#txtinstructorerror").text("");
    $("#txtminimoerror").text("");

	}

// ===========================================================================
// ***** 		Funcion llamada cuando se selec un curso del modal			 *****
// ===========================================================================
    $scope.soloNumeros = function(e){
        var key = e.charCode;
        return key>= 48 && key <=57;
    }
// =======================================================================================
// *****               Función para eliminar espacios a una cadena          		 *****
// =======================================================================================
    function eliminaEspacios(cadena)
    {
        // Funcion equivalente a trim en PHP
        var x=0, y=cadena.length-1;
        while(cadena.charAt(x)==" ") x++;
        while(cadena.charAt(y)==" ") y--;
        return cadena.substr(x, y-x+1);
    }
// =======================================================================================
// *****               Función para observar el campo del formulario         		 *****
// =======================================================================================
    $scope.$watch('formData.minimo',function(nuevo, anterior) {
            if(!nuevo)return;
            if(!$scope.validar_numeros())
                $scope.formData.minimo = anterior;
                })

// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
 $scope.validar_numeros = function()
{
    var valor = $scope.formData.minimo;
    valor = eliminaEspacios(valor);
    reg=/(^[0-9,0-9]{1,10}$)/;
    if(!reg.test(valor))
    {
        $scope.formData.minimo = "";
        // Si hay error muestro el div que contiene el error
        $("#minimo").focus();
        return false;
    }
    else
        return true;
}

// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
function validar_formulario()
{
 $scope.respuesta =  1;

if(typeof $scope.formData.selectCurso !== "undefined") {
    $("#txtcursoerror").text("");
    if ($scope.formData.selectCurso.length == 0) {
        $scope.respuesta = 0;
        $("#txtcursoerror").text("No debe estar vacio");
    } else {
        $("#txtcursoerror").text("");
    }
}else {
    $scope.respuesta = 0;
    $("#txtcursoerror").text("No debe estar vacio");
}

if(typeof $scope.formData.instructor !== "undefined") {
    $("#txtinstructorerror").text("");
    if ($scope.formData.instructor.length == 0) {
        $scope.respuesta = 0;
        $("#txtinstructorerror").text("No debe estar vacio");
    } else {
        $("#txtinstructorerror").text("");
    }
}else {
    $scope.respuesta = 0;
    $("#txtinstructorerror").text("No debe estar vacio");
}

 if(typeof $scope.formData.fecha_inicio !== "undefined")
    {
      $("#fechainicioerror").text("");
 if($scope.formData.fecha_inicio.length == 0){
      $scope.respuesta =  0;
      $("#fechainicioerror").text("No debe estar vacio");
    }else{
      $("#fechainicioerror").text("");
    }
    }else{
      $scope.respuesta =  0;
      $("#fechainicioerror").text("No debe estar vacio");
    }

 if(typeof $scope.formData.fecha_fin !== "undefined")
      {
        $("#fechafinerror").text("");
 if($scope.formData.fecha_fin.length == 0){
      $scope.respuesta =  0;
      $("#fechafinerror").text("No debe estar vacío");
    }else{
      $("#fechafinerror").text("");
    }
    }else{
          $scope.respuesta =  0;
          $("#fechafinerror").text("No debe estar vacío");
        }

if(typeof $scope.formData.minimo !== "undefined") {
    $("#txtminimoerror").text("");
    if ($scope.formData.minimo.length == 0 || $scope.formData.minimo <= 0) {
        $scope.respuesta = 0;
        $("#txtminimoerror").text("No debe estar vacío");
    } else {
        $("#txtminimoerror").text("");
    }
}else {
    $scope.respuesta =  0;
    $("#txtminimoerror").text("Debe introducir un número");
}

 if(Boolean($scope.formData.fecha_inicio) && Boolean($scope.formData.fecha_fin)){
  var aux_fechainicio = Date.parse($scope.formData.fecha_inicio);
  var aux_fechafin = Date.parse($scope.formData.fecha_fin);
  if(aux_fechainicio>aux_fechafin)
  {
   $scope.respuesta =  0;
   $("#fechainicioerror").text("Esta fecha no puede ser mayor que la final");
  }else{
   $("#fechainicioerror").text("");
  }
  }

}

// ===========================================================================
// ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
// ===========================================================================
	$scope.submitForm = function (formData) {

            validar_formulario();
      		if($scope.respuesta == 1){
      		    if($scope.accion == "insertar")
                {
                    insertar(formData);
                }
                if($scope.accion == "editar")
                {
                    editar(formData);
                }


      		}
	}
// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UNA PROGRAMACION				 *****
// ===========================================================================
function insertar(formData) {
    var curso = {
        ID_CURSO:		          	formData.selectCurso,
        FECHAS:			            formData.fecha_inicio+"-"+formData.fecha_fin,
        ID_INSTRUCTOR:	            formData.instructor,
        PERSONAS_MINIMO:	        formData.minimo
    };
    $.post( global_apiserver + "/cursos_programados/insert/", JSON.stringify(curso), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.resultado == "ok") {
            $("#modalMostrar").modal("hide");
            notify("Éxito", "Se ha insertado un nuevo evento", "success");
            $scope.onAgenda();
            //document.location = "./?pagina=auditores";
        }
        else{
            notify("Error", respuesta.mensaje, "error");
        }

    });
}

// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UNA PROGRAMACION				 *****
// ===========================================================================
    function editar(formData) {

        var curso = {
            ID:                         $scope.id_evento_select,
            ID_CURSO:		          	formData.selectCurso,
            FECHAS:			            formData.fecha_inicio+"-"+formData.fecha_fin,
            ID_INSTRUCTOR:	            formData.instructor,
            PERSONAS_MINIMO:	        formData.minimo
        };
        $.post( global_apiserver + "/cursos_programados/update/", JSON.stringify(curso), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                //$("#modalMostrar").modal("hide");
                $scope.actualizaDatos(2);
                notify("Éxito", "Se ha modificado el evento", "success");



                $scope.onAgenda();
                //document.location = "./?pagina=auditores";
            }
            else{
                notify("Error", respuesta.mensaje, "error");
            }

        });
    }
// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
function onCalendario() {

 var dateInicial = $('#fecha_inicio').datepicker({
    dateFormat: "dd/mm/yy",
    minDate: "+0D",
    language: "es",
    onSelect: function (dateText, ins) {
        $scope.formData.fecha_inicio = dateText;
        dateFinal.datepicker("option", "minDate", dateText)
    }
}).css("display", "inline-block");

var dateFinal =$('#fecha_fin').datepicker({
    dateFormat: "dd/mm/yy",
    language: "es",
    minDate: "+0D",
    onSelect: function (dateText, ins) {
        $scope.formData.fecha_fin = dateText;
    }
}).css("display", "inline-block");
}

// ===========================================================================
// ***** 	              FUNCION PARA CARGAR LA AGENDA			         *****
// ===========================================================================
$scope.onAgenda = function() {
    var eventos = [];
    $(".loading").show();
    //Codigo que carga los cursos programador
    $.post(global_apiserver + "/cursos_programados/getAll/", function(response){
        response = JSON.parse(response);

        $.each(response, function( indice, objEvento ) {

            var array = objEvento.FECHA_INICIO.split('/');
            var anhio_ini = array[2];
            var mes_ini = array[1]-1; //En js los meses comienzan en 0
            var dia_ini =array[0];
            var dias = "";
            if(objEvento.DIAS==0)
                dias = ' 1 día - ';
            else
                dias = ' '+objEvento.DIAS+' días - ';

                eventos.push(
                    {
                        title: dias + objEvento.NOMBRE_CURSO + " Por: " + objEvento.NOMBRE_AUDITOR ,
                        start: new Date(anhio_ini, mes_ini, dia_ini, 07, 0),
                        //end: new Date(anhio_fin, mes_fin, dia_fin,),
                        allDay: false,
                        id: objEvento.ID,

                    }
                )
        });

        if($scope.global_calendar !== undefined)
        {
            $scope.global_calendar.fullCalendar('destroy');
        }

        $scope.global_calendar = $('#calendar').fullCalendar({
            customButtons: {
                newEvent: {
                    text: '+ Nuevo Evento',
                    click: function() {

                    }
                }
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            minTime:"07:00:00",
            selectable: false,
            editable: false,
            eventBackgroundColor:"#50287E",
            locale: 'es',
            //defaultDate:eventos[eventos.length-1].start,
            events: eventos,
            eventClick: function (calEvent, jsEvent, view) {
                if(calEvent.id)
                {
                    $scope.id_evento_select = calEvent.id;
                    $scope.openModalMostar();
                }

            }
        });
        $scope.global_calendar.fullCalendar( 'today' );

        //$(".select2_single").select2({});
        $(".loading").hide();

    });
}
// ================================================================================
// *****                  Funcion Mostrar/Ocultar elementos                   *****
// ================================================================================
function mytoggle(id)
{
    $("#"+id).toggle(function(){

    },function(){

    });
}

$(document).ready(function () {
$scope.onAgenda();

});
}]);

// ================================================================================
// *****                       Funciones de uso común                         *****
// ================================================================================

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