/**
 * @ngdoc controller
 * @name controller:empleado_perfil
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('empleado_perfil_controller',['$scope','$http',function($scope,$http) {
//$scope.optionsList=[];
    $scope.tab_selected= getQueryVariable("tab");
    $scope.id= getQueryVariable("id");
    $scope.empleado = null;
    $scope.activos = null;
    $scope.flag = false;
    $scope.formDataFicha = {};
    $scope.formDataActivo = {};

// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS CURSOS				 *****
// ===================================================================
    $scope.cargarDatosEmpleado = function(){
        $(".loading").show();
        $http.get(  global_apiserver + "/personal_interno/getById/?id="+$scope.id)
            .then(function( response ){
                $scope.empleado = response.data;
                $scope.cargaFicha();
                $scope.cargarActivos();
                var fecha = $scope.empleado.FECHA_NACIMIENTO;
                $scope.empleado.FECHA_NACIMIENTO = $scope.formatFecha(fecha);
                $(".loading").hide();
            });
    }
// ================================================================================
// *****                       Calcular edad                          *****
// ================================================================================
    $scope.calcular_edad = function(fecha){

        if(typeof fecha !== "undefined") {
        var birthday_arr = fecha.split("/");
        var birthday_date = new Date(birthday_arr[2], birthday_arr[1] - 1, birthday_arr[0]);
        var ageDifMs = Date.now() - birthday_date.getTime();
        var ageDate = new Date(ageDifMs);
        return Math.abs(ageDate.getUTCFullYear() - 1970);
        }else {return false;}
    }
// ================================================================================
// *****                       format fecha                          *****
// ================================================================================
    $scope.formatFecha = function(fecha)
    {
        return fecha.substring(8, 10) + "/" + fecha.substring(5, 7) + "/" + fecha.substring(0, 4);
    }

/////////////////////////////////////////////////////////////////////////
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    }

// ==============================================================================
// ***** 		    Funcion mostrar fichas de empleados        		       *****
// ==============================================================================
    $scope.cargaFicha = function(){
        $scope.ficha = [];
        $.getJSON( global_apiserver + "/personal_interno/getFicha/?id="+$scope.empleado.NO_EMPLEADO, function( response ) {
            //console.log();
            $scope.ficha = response;
            onDatePicker();
            if(typeof $scope.ficha.ANTIGUEDAD !== "undefined" && $scope.ficha.ANTIGUEDAD!==null)
            {
                $scope.flag = false;
            }else {  $scope.flag = true; }
            $scope.$apply();
        });
    }
// ==============================================================================
// ***** 		    Funcion mostrar form editar ficha        		       *****
// ==============================================================================
    $scope.showFormConfiguracion = function () {
        $scope.flag = true;
        $scope.formDataFicha.antiguedad = $scope.ficha.ANTIGUEDAD;
        $scope.formDataFicha.seguro = $scope.ficha.SEGURO_GASTOS_MEDICOS;
        $scope.formDataFicha.vacaciones =$scope.ficha.DIAS_VACACIONES;
        $scope.formDataFicha.prestamo_caja =$scope.ficha.PRESTAMOS_CAJA;
        $scope.formDataFicha.prestamo_imnc = $scope.ficha.PRESTAMOS_IMNC;
    }
// =======================================================================================================
// *****                 Accion al presionar button Guardar  form Ficha                		 *****
// =======================================================================================================
    $scope.submitFormFicha = function (formDataFicha) {
        validar_formulario_ficha();
        if($scope.respuesta == 1){
            editar(formDataFicha);
        }
    }
// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UN EMPLEADO				 *****
// ===========================================================================
    function editar(formDataFicha) {

        var ficha = {
            NO: $scope.empleado.NO_EMPLEADO,
            ANTIGUEDAD: formDataFicha.antiguedad,
            SEGURO_GASTOS_MEDICOS: formDataFicha.seguro,
            DIAS_VACACIONES: formDataFicha.vacaciones,
            PRESTAMOS_CAJA: formDataFicha.prestamo_caja,
            PRESTAMOS_IMNC: formDataFicha.prestamo_imnc
           };
        $.post(global_apiserver + "/personal_interno/updateFicha/", JSON.stringify(ficha), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $scope.cargaFicha();
                notify("Éxito", "Se ha guardado la ficha del empleado", "success");
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });


    }
// =======================================================================================================
// *****                 Validar formulario ficha                		 *****
// =======================================================================================================
    function validar_formulario_ficha(){
        $scope.respuesta =  1;
        var setfocus = null;

////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataFicha.prestamo_imnc !== "undefined") {
            if ($scope.formDataFicha.prestamo_imnc.length == 0) {
                $scope.respuesta = 0;
                $scope.prestamo_imnc_error="No debe estar vacio";
                setfocus = "prestamo_imnc";
            } else {
                $scope.prestamo_imnc_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.prestamo_imnc_error="No debe estar vacio";
            setfocus = "prestamo_imnc";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataFicha.prestamo_caja !== "undefined") {
            if ($scope.formDataFicha.prestamo_caja.length == 0) {
                $scope.respuesta = 0;
                $scope.prestamo_caja_error="No debe estar vacio";
                setfocus = "prestamo_caja";
            } else {
                $scope.prestamo_caja_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.prestamo_caja_error="No debe estar vacio";
            setfocus = "prestamo_caja";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataFicha.vacaciones !== "undefined") {
            if ($scope.formDataFicha.vacaciones.length == 0) {
                $scope.respuesta = 0;
                $scope.vacaciones_error="No debe estar vacio";
                setfocus = "vacaciones";
            } else {
                $scope.vacaciones_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.vacaciones_error="No debe estar vacio";
            setfocus = "vacaciones";
        }

////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataFicha.seguro !== "undefined") {
            if ($scope.formDataFicha.seguro.length == 0) {
                $scope.respuesta = 0;
                $scope.seguro_error="No debe estar vacio";
                setfocus = "seguro";
            } else {
                $scope.seguro_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.seguro_error="No debe estar vacio";
            setfocus = "seguro";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataFicha.antiguedad !== "undefined") {
            if ($scope.formDataFicha.antiguedad.length == 0) {
                $scope.respuesta = 0;
                $scope.antiguedad_error="No debe estar vacio";
                setfocus = "antiguedad";
            } else {
                   if($scope.validar_fecha($scope.formDataFicha.antiguedad))
                   {
                       $scope.antiguedad_error="";
                   }
                   else
                   {
                       $scope.respuesta = 0;
                       $scope.antiguedad_error="Fecha inválida 00/00/0000";
                       setfocus = "antiguedad";
                   }

            }
        }else {
            $scope.respuesta = 0;
            $scope.antiguedad_error="No debe estar vacio";
            setfocus = "antiguedad";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }

    }

// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_numeros = function(campo)
    {

            var valor = eval('$scope.formDataFicha.' + campo);
            if(typeof valor !== "undefined")
            {
                valor = eliminaEspacios(valor);
                reg = /^[0-9]+([.])?([0-9]+)?$/;
                if (!reg.test(valor)) {
                    eval('$scope.formDataFicha.' + campo + '=""');
                    // Si hay error muestro el div que contiene el error
                    $("#" + campo).focus();
                    return false;
                } else
                    return true;
            }else { return false;}


    }
// =======================================================================================
// *****               Función para eliminar espacios a una cadena          		 *****
// =======================================================================================
    function eliminaEspacios(cadena)
    {
            // Funcion equivalente a trim en PHP
            var x = 0, y = cadena.length - 1;
            while (cadena.charAt(x) == " ") x++;
            while (cadena.charAt(y) == " ") y--;
            return cadena.substr(x, y - x + 1);
    }
// ===================================================================
// ***** 			FUNCION PARA CARGAR ACTIVOS				 *****
// ===================================================================
    $scope.cargarActivos= function(){
        $(".loading").show();
        $http.get(  global_apiserver + "/personal_interno/getActivosById/?id="+$scope.id)
            .then(function( response ){
                $scope.activos = response.data;
                $(".loading").hide();
            });
    }
// =======================================================================================
// ***** 		FUNCION PARA ABRIR MODAL INSERTAR ACTUALIZAR MOBILIARIO	             *****
// =======================================================================================
    $scope.openModalInsertUpdateM = function(){
        clear_mobiliario();
        $scope.modal_mobiliario = "Editando Mobiliario";
        $scope.formDataActivo.escritorio = $scope.activos.MOBILIARIO.ESCRITORIO;
        $scope.formDataActivo.silla = $scope.activos.MOBILIARIO.SILLA;
        $scope.formDataActivo.telefono = $scope.activos.MOBILIARIO.TELEFONO_FIJO;
        $scope.formDataActivo.movil = $scope.activos.MOBILIARIO.MOVIL;

        $("#modalInsertUpdateM").modal("show");

    }
// =======================================================================================
// ***** 		             FUNCION LIMPIAR MODAL MOBILIARIO                        *****
// =======================================================================================
    function clear_mobiliario(){
        $scope.formDataActivo.escritorio = "";
        $scope.formDataActivo.silla = "";
        $scope.formDataActivo.telefono = "";
        $scope.formDataActivo.movil = "";
    }
// ===========================================================================
// ***** 			FUNCION PARA EDITAR UN MOBILIARIO				 *****
// ===========================================================================
    $scope.editarM=function(formDataActivo) {

        var mobiliario = {
            NO: $scope.empleado.NO_EMPLEADO,
            ESCRITORIO: formDataActivo.escritorio,
            SILLA: formDataActivo.silla,
            TELEFONO_FIJO: formDataActivo.telefono,
            MOVIL: formDataActivo.movil,

        };
        $.post(global_apiserver + "/personal_interno/updateMobiliario/", JSON.stringify(mobiliario), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $scope.cargarActivos();
                notify("Éxito", "Se ha guardado el mobiliario del empleado", "success");
                $("#modalInsertUpdateM").modal("hide");
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });


    }

// =======================================================================================
// ***** 		FUNCION PARA ABRIR MODAL INSERTAR ACTUALIZAR EQUIPOS	             *****
// =======================================================================================
    $scope.openModalInsertUpdateE = function(key){
        clear_equipos();
        $scope.modal_equipos = "Agregar Equipos";
        $scope.accion = 'insertar';
        if(typeof key !== "undefined") {
            $scope.modal_equipos = "Editando Equipos";
            $scope.accion = 'editar';
            $scope.formDataActivo.computadora = $scope.activos.EQUIPOS[key].COMPUTADORA;
            $scope.formDataActivo.modelo = $scope.activos.EQUIPOS[key].MODELO;
            $scope.formDataActivo.software = $scope.activos.EQUIPOS[key].SOFTWARE;
            $scope.formDataActivo.licenciamiento = $scope.activos.EQUIPOS[key].LICENCIAMIENTO;
            $scope.ID_EQUIPO = $scope.activos.EQUIPOS[key].ID;
        }

        $("#modalInsertUpdateE").modal("show");

    }
// =======================================================================================
// ***** 		             FUNCION LIMPIAR MODAL MOBILIARIO                        *****
// =======================================================================================
    function clear_equipos(){
        $scope.formDataActivo.computadora = "";
        $scope.formDataActivo.modelo = "";
        $scope.formDataActivo.software = "";
        $scope.formDataActivo.licenciamiento = "";
        $scope.ID_EQUIPO = "";
    }
// ===========================================================================
// ***** 			FUNCION PARA EDITAR UN EQUIPO				 *****
// ===========================================================================
    $scope.editarE=function(formDataActivo,key) {

        $scope.valida_form();

        if($scope.respuesta ==  1)
        {
            var equipos = {
                NO: $scope.empleado.NO_EMPLEADO,
                COMPUTADORA: formDataActivo.computadora,
                MODELO: formDataActivo.modelo,
                SOFTWARE: formDataActivo.software,
                LICENCIAMIENTO: formDataActivo.licenciamiento,
                ACCION: $scope.accion,
                ID:$scope.ID_EQUIPO
            };
            $.post(global_apiserver + "/personal_interno/updateEquipos/", JSON.stringify(equipos), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $scope.cargarActivos();
                    notify("Éxito", "Se han guardado los equipos del empleado", "success");
                    $("#modalInsertUpdateE").modal("hide");
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }

            });
        }



    }
// ===========================================================================
// ***** 			FUNCION PARA ELIMINAR UN EQUIPO				 *****
// ===========================================================================
    $scope.eliminarE=function(key)
    {
        var equipo = {
            ID:$scope.activos.EQUIPOS[key].ID
        };
        $.confirm({
            title: 'Confirmación',
            content: 'Esta seguro que desea eliminar el siguiente equipo: '+$scope.activos.EQUIPOS[key].COMPUTADORA+'?',
            buttons: {
                Eliminar: function () {
                    $.post(global_apiserver + "/personal_interno/deleteEquipo/", JSON.stringify(equipo), function (respuesta) {
                        respuesta = JSON.parse(respuesta);
                        if (respuesta.resultado == "ok") {
                            $scope.cargarActivos();
                            notify("Éxito", "Se han eliminado el equipo", "success");
                        }
                        else {
                            notify("Error", respuesta.mensaje, "error");
                        }

                    });

                },
                Cancelar: function () {
                    console.log("cancel");

                }
            }
        });

    }
// ===========================================================================
// ***** 			FUNCION PARA ELIMINAR UN EQUIPO				 *****
// ===========================================================================
    $scope.valida_form = function(){
        $scope.respuesta =  1;
        var setfocus = null;

////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataActivo.licenciamiento !== "undefined") {
            $("#noerror").text("");
            if ($scope.formDataActivo.licenciamiento.length == 0) {
                $scope.respuesta = 0;
                $scope.licenciamiento_error="No debe estar vacio";
                setfocus = "no";
            } else {
                $scope.licenciamiento_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.licenciamiento_error="No debe estar vacio";
            setfocus = "no";
        }
////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataActivo.software !== "undefined") {
            $("#noerror").text("");
            if ($scope.formDataActivo.software.length == 0) {
                $scope.respuesta = 0;
                $scope.software_error="No debe estar vacio";
                setfocus = "no";
            } else {
                $scope.software_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.software_error="No debe estar vacio";
            setfocus = "no";
        }
////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataActivo.modelo !== "undefined") {
            $("#noerror").text("");
            if ($scope.formDataActivo.modelo.length == 0) {
                $scope.respuesta = 0;
                $scope.modelo_error="No debe estar vacio";
                setfocus = "no";
            } else {
                $scope.modelo_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.modelo_error="No debe estar vacio";
            setfocus = "no";
        }
////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formDataActivo.computadora !== "undefined") {
            $("#noerror").text("");
            if ($scope.formDataActivo.computadora.length == 0) {
                $scope.respuesta = 0;
                $scope.computadora_error="No debe estar vacio";
                setfocus = "no";
            } else {
                $scope.computadora_error="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.computadora_error="No debe estar vacio";
            setfocus = "no";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }
    }

// ===========================================================================
// ***** 	              FUNCION PARA UPLOAD IMAGEN           			 *****
// ===========================================================================
    $scope.uploadImageShow = function(id){
        $scope.id_upload = id;
        $("#modalSubirImagen").modal("show");

    }

    $scope.uploadFile = function(files) {
        var url = global_apiserver + "/personal_interno/uploadImagen/";
        var fd = new FormData();

        var validExtensions = ['jpg','png','jpeg']; //array of valid extensions
        var fileName = files[0].name;
        var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
        if ($.inArray(fileNameExt, validExtensions) == -1) {

            notify("Error", "Solo se permiten imagenes", "error");
        }
        else
        {
            //Take the first selected file
            fd.append("myfile", files[0]);
            fd.append("no", $scope.id_upload);


            $http.post(url, fd, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).success(function (response) {
                if(response.resultado == "ok")
                {
                    $scope.cargarDatosEmpleado();
                    $("#modalSubirImagen").modal("hide");
                    notify("Éxito", "Se a subido la imagen", "success");
                }
                else
                {
                    notify("Error", response.mensaje, "error");
                }
            }).error(function (response) {
                notify("Error", response.mensaje, "error");

            });
        }



    };

// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    function onDatePicker(date) {


        var antiguedad = $('#antiguedad').datepicker({
            dateFormat: "dd/mm/yy",
            language: "es",
            onSelect: function (dateText, ins) {
                $scope.formDataFicha.antiguedad = dateText;
            }
        }).css("display", "inline-block");

        if(typeof date !== "undefined")
        {
            $scope.formDataFicha.antiguedad = date;
        }

    }

// ================================================================================
// *****                  Funcion validar fecha                  *****
// ================================================================================
    $scope.validar_fecha = function(fecha) {
        if (typeof fecha !== "undefined") {
            if (validar_formato_fecha(fecha)) {
                    if (existe_fecha(fecha)) {
                        return true;
                    }
                    else {
                        return false;
                    }

            } else {

                return false;;
            }
        }else{
            return false;
        }



    }
    function validar_formato_fecha(fecha) {
        var RegExPattern = /^\d{2}\/\d{2}\/\d{4}$/;
        if ((fecha.match(RegExPattern)) && (fecha!='')) {
            return true;
        } else {
            return false;
        }

    }
    function existe_fecha(fecha){
        var fechaf = fecha.split("/");
        var d = fechaf[0];
        var m = fechaf[1];
        var y = fechaf[2];
        return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();

    }

// ================================================================================
// *****                        Al cargar la página                           *****
// ================================================================================

    $(document).ready(function () {

        if ($scope.tab_selected == "ficha") {
            $('#myTab li a[href="#tab_ficha"]').tab("show");
        }
        if ($scope.tab_selected == "activos") {
            $('#myTab li a[href="#tab_activos_fijos"]').tab("show");
        }

        $scope.cargarDatosEmpleado();


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



