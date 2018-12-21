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
    $scope.date_evento_select = '';
    $scope.selectedInst = '';
    $scope.instructoresCursos=[];
    $scope.id_instructor = "";
    $scope.objCursoProgramado = [];



// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS INSTRUCTORES    	 *****
// ===================================================================
    $scope.cargarInstructores= function(seletcCurso){

		$http.get(global_apiserver + "/cursos_programados/getInstructores/?id="+seletcCurso)
		.then(function( response ){
			$scope.instructoresCursos = response.data;
		});
	}



// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarCursos = function(){
		$http.get(  global_apiserver + "/cursos/getAllHabilitados/")
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
            var dias = parseInt(response.DIAS)+1;
            if (response.DIAS == 0)
                dias = dias+' día ';
            else
                dias = dias + ' días ';
            $scope.txtDuracion = dias;
            $scope.txtCurso = response.NOMBRE_CURSO;
            $scope.txtReferencia=response.REFERENCIA;
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
            $("#divInstructor").hide();
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
        $scope.modal_titulo = "Agregar Curso";
		$scope.accion = accion;
        clear_modal_insertar_actualizar()
        $scope.cargarCursos();
        //$scope.cargarInstructores();
        $scope.formData.fecha_inicio = $scope.date_evento_select;

        if(accion == 'editar')
        {

            $scope.modal_titulo = "Modificando Curso";
            $.getJSON( global_apiserver + "/cursos_programados/getById/?id="+$scope.id_evento_select, function( response ) {
                $scope.objCursoProgramado = response;
                $scope.formData.selectCurso = response.ID_CURSO;
                $scope.formData.instructor = response.ID_INSTRUCTOR;
                $scope.formData.fecha_inicio = response.FECHA_INICIO;
                $scope.formData.fecha_fin = response.FECHA_FIN;
                $scope.formData.minimo = response.PERSONAS_MINIMO;
                $scope.id_instructor = response.ID_INSTRUCTOR;
                $scope.formData.referencia = response.REFERENCIA;
                $("#btnInstructor").attr("value",response.NOMBRE_AUDITOR.NOMBRE+" "+response.NOMBRE_AUDITOR.APELLIDO_PATERNO+" "+response.NOMBRE_AUDITOR.APELLIDO_MATERNO)
                $("#btnInstructor").attr("class", "form-control ");
                $scope.$apply();
                ;

            });

            mytoggle("divMostrar");
            mytoggle("divInsertar");
            /*$("#divMostrar").hide();
            $("#divInsertar").show();*/


        }
        else
        {
            generar_referencia_cifa();
            $("#divInsertar").show();
            $("#divMostrar").hide();
            $("#divInstructor").hide();
            $("#modalMostrar").modal("show");
        }



        onCalendario();
	}

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR INSTRUCTOR             		 *****
// =======================================================================================
$scope.openModalMostarInst = function() {
    $scope.formData.searchText = "";
    if (typeof $scope.formData.selectCurso !== "undefined") {
        $("#txtinstructorerror").text("");
        if ($scope.formData.selectCurso.length != 0 && $scope.formData.fecha_inicio.length != 0 && $scope.formData.fecha_fin.length != 0) {
            $.getJSON(global_apiserver + "/cursos/getById/?id=" + $scope.formData.selectCurso, function (response) {
                $scope.id_curso = response.ID_CURSO;
                $scope.nombre_curso = response.NOMBRE;
<<<<<<< HEAD
                //$scope.apply();
=======
                $scope.$apply();
>>>>>>> a501e9c6e964fae4e1ed00683803c8eccf3b4b6a

            })
            $("#modal-size").attr("class","modal-dialog modal-lg");
            mytoggle("divInstructor")
            mytoggle("divInsertar");

            $scope.cargarInstructores($scope.formData.selectCurso);
            $("#txtinstructorerror").text("");
        }
        else {
            $("#txtinstructorerror").text("Debe seleccionar un curso y las fechas");
        }
    }
    else {
        $("#txtinstructorerror").text("Debe seleccionar un curso");
    }


}
// ===========================================================================
// ***** 		      Funcion button select instructor 	            	 *****
// ===========================================================================
$scope.onSelectInstructor = function(instructor)
{
    var validar = {
        ID:		          	        instructor,
        FECHAS:			            $scope.formData.fecha_inicio+","+$scope.formData.fecha_fin
    };
    $("#btn-"+instructor).attr("disabled",true);
    $("#btn-"+instructor).text("verificando...");
    $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.disponible == "si") {

            $scope.id_instructor = instructor;
            $("#btnInstructor").attr("value", $("#lb-"+instructor).val());
            $("#btnInstructor").attr("class", "form-control btn btn-primary");
            $("#btn-"+instructor).attr("disabled",false);
            $("#btn-"+instructor).text("seleccionar");
            $scope.cerrarInstructores();
        }
        else
        {
            if (respuesta.disponible == "no")
            {
                notify("Error", respuesta.razon, "error");
                $("#btn-"+instructor).attr("disabled",true);
                $("#btn-"+instructor).attr("title",respuesta.mensaje);
                $("#btn-"+instructor).text("inactivo");
            }else
            {
                notify("Error", respuesta.mensaje, "error");
                $("#btn-"+instructor).attr("disabled",false);
                $("#btn-"+instructor).text("seleccionar");
            }

        }
    })
}
$scope.cerrarInstructores = function()
{
    $("#modal-size").attr("class","modal-dialog");
    mytoggle("divInsertar");
    mytoggle("divInstructor");
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
    $scope.selectedInst = '';
    $scope.id_instructor = "";
    $scope.formData.referencia ="";
    $("#btnInstructor").attr("value","Selecciona un Instructor");
    $("#btnInstructor").attr("class", "form-control btn ");

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

    if ($scope.formData.referencia.length == 0) {
        $scope.respuesta = 0;
        $("#referenciaerror").text("No debe estar vacio");
    } else {
        $("#referenciaerror").text("");
    }


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


    if ($scope.id_instructor.length == 0) {
        $scope.respuesta = 0;
        $("#txtinstructorerror").text("No debe estar vacio");
    } else {
        $("#txtinstructorerror").text("");
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
                    $scope.validaEditar(formData);
                }


      		}
	}
// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UNA PROGRAMACION				 *****
// ===========================================================================
function insertar(formData) {

    var validar = {
        ID:		          	        $scope.id_instructor,
        FECHAS:			            formData.fecha_inicio+","+formData.fecha_fin
    };
    $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
        respuesta = JSON.parse(respuesta);
        if (respuesta.disponible == "si") {
            var curso = {
                ID_CURSO: formData.selectCurso,
                FECHAS: formData.fecha_inicio + "-" + formData.fecha_fin,
                ID_INSTRUCTOR: $scope.id_instructor,
                PERSONAS_MINIMO: formData.minimo,
                REFERENCIA:formData.referencia
            };
            $.post(global_apiserver + "/cursos_programados/insert/", JSON.stringify(curso), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $("#modalMostrar").modal("hide");
                    notify("Éxito", "Se ha insertado un nuevo evento", "success");
                    $scope.onAgenda(formData.fecha_inicio);
                    irFechaCalendario(formData.fecha_inicio);
                    //document.location = "./?pagina=auditores";
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }

            });

        }
        else
        {
            if (respuesta.disponible == "no")
            {

                notify("Error", respuesta.razon, "error");
                return false;

            }else
            {
                notify("Error", respuesta.mensaje, "error");
                return false;
            }

        }
    })
    $scope.date_evento_select = "";
}

// ===========================================================================
// ***** 		       Validar editar programación             			 *****
// ===========================================================================
    $scope.validaEditar = function(formData)
    {

        if($scope.objCursoProgramado.ID_INSTRUCTOR == $scope.id_instructor && $scope.objCursoProgramado.ID_CURSO == formData.selectCurso && $scope.objCursoProgramado.FECHA_INICIO == formData.fecha_inicio && $scope.objCursoProgramado.FECHA_FIN == formData.fecha_fin)
        {
            editar(formData);
        }else {
        var validar = {
            ID:		          	        $scope.id_instructor,
            FECHAS:			            formData.fecha_inicio+","+formData.fecha_fin
        };
        $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.disponible == "si") {

                editar(formData);
            }
            else
            {
                if (respuesta.disponible == "no")
                {

                    notify("Error", respuesta.razon, "error");
                    return false;

                }else
                {
                    notify("Error", respuesta.mensaje, "error");
                    return false;
                }

            }
        })
        }
    }


// ===========================================================================
// ***** 			FUNCION PARA EDITAR UNA PROGRAMACION				 *****
// ===========================================================================
    function editar(formData) {

            var curso = {
                ID: $scope.id_evento_select,
                ID_CURSO: formData.selectCurso,
                FECHAS: formData.fecha_inicio + "-" + formData.fecha_fin,
                ID_INSTRUCTOR: $scope.id_instructor,
                PERSONAS_MINIMO: formData.minimo,
            };
            $.post(global_apiserver + "/cursos_programados/update/", JSON.stringify(curso), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    //$("#modalMostrar").modal("hide");
                    $scope.actualizaDatos();
                    notify("Éxito", "Se ha modificado el evento", "success");
                    $scope.onAgenda(formData.fecha_inicio);
                    //document.location = "./?pagina=auditores";
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }

            });

        $scope.objCursoProgramado = [];
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
    if($scope.date_evento_select!="")
    {
        dateInicial.datepicker("option", "minDate", $scope.date_evento_select);
        dateFinal.datepicker("option", "minDate", $scope.date_evento_select);
    }
}

// ===========================================================================
// ***** 	              FUNCION PARA CARGAR LA AGENDA			         *****
// ===========================================================================
$scope.onAgenda = function(fecha) {
    var date = new Date();
    if(typeof fecha !== "undefined" && fecha.length != 0)
    {
        var partes = fecha.split("/");
        date = new Date(partes[2],parseInt(partes[1])-1,partes[0]);
    }
    var eventos = [];
    //Codigo que carga los cursos programador
    $.post(global_apiserver + "/cursos_programados/getAll/", function(response){
        response = JSON.parse(response);
        var i = 7;
        $.each(response, function( indice, objEvento ) {

            var array = objEvento.FECHA_INICIO.split('/');
            var anhio_ini = array[2];
            var mes_ini = array[1]-1; //En js los meses comienzan en 0
            var dia_ini =array[0];

            var array = objEvento.FECHA_FIN.split('/');
            var anhio_fin = array[2];
            var mes_fin = array[1]-1; //En js los meses comienzan en 0
            var dia_fin =array[0];
            var dias = objEvento.DIAS;
            if(objEvento.DIAS==1)
                dias = dias +' día';
            else
                dias = dias +' días';
                eventos.push(
                    {
                        title: ' '+dias+' - ' + objEvento.NOMBRE_CURSO + " - Por: " + objEvento.NOMBRE_AUDITOR ,
                        start: new Date(anhio_ini, mes_ini, dia_ini, 07, 0),
                        end: new Date(anhio_fin, mes_fin, dia_fin, 18, 30),
                        allDay: false,
                        id: objEvento.ID,
                        color: "#"+i+"0287E",

                    }
                )
            if(i==0)
                i=7;
                else
                i--;
        });

        if($scope.global_calendar !== undefined)
        {
            $scope.global_calendar.fullCalendar('destroy');
        }

        $scope.global_calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            minTime:"07:00:00",
            selectable: false,
            editable: false,
            //eventBackgroundColor:"#50287E",
            eventBackgroundColor:"#50287E",
            locale: 'es',
            navLinks: true,
            defaultDate:date,
            events: eventos,
            eventClick: function (calEvent, jsEvent, view) {
                if(calEvent.id)
                {
                    $scope.id_evento_select = calEvent.id;
                    $scope.openModalMostar();
                }

            }
            ,
            dayClick: function(date, jsEvent, view) {

                if(esDespuesHoy(date.format("DD/MM/YYYY")))
                {
                    $scope.date_evento_select = date.format("DD/MM/YYYY");
                    $(this).css('background-color', '#F1FFFF');
                    $scope.openModalInsertarModificar("insertar");
                }


            }

        });


    });
}

// ================================================================================
// *****                  Funcion comparar fecha                  *****
// ================================================================================
function esDespuesHoy(fecha) {

    var hoy = new Date();
    var partes = fecha.split("/");
    var select = new Date(partes[2],parseInt(partes[1])-1,partes[0]);
    if(hoy<=select){return true;}else {return false;}
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
// ================================================================================
// *****           Funcion llamada cuando se selecciona un curso              *****
// ================================================================================
$scope.onSelectedCurso = function(){
     //$scope.cargarInstructores(selectCursos);
    $scope.id_instructor = "";
    $("#btnInstructor").attr("value","Selecciona un Instructor");
    $("#btnInstructor").attr("class", "form-control btn ");

    }
// ==============================================================================
// ***** 		Funcion para generar referencia	para CIFA		*****
// ==============================================================================
    function generar_referencia_cifa(){
        $http.get(  global_apiserver + "/cursos/getReferencia/?id=3&tipo=P")
            .then(function( response ){
                $scope.formData.referencia	= response.data;
            });
    }

$(document).ready(function () {
$scope.onAgenda();
    $("button.btn-default:contains('Cerrar') ,#btnCerrar, .close").click(function(){
        $scope.date_evento_select = '';
        $scope.objCursoProgramado = [];
    })
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