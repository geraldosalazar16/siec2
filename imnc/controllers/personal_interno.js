/**
 * @ngdoc controller
 * @name controller:empleados
 *4
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('empleados_controller',['$scope','$http',function($scope,$http){
    $scope.total = 0;
    $scope.formData = {};
    $scope.objectSeleted = null;


// ==============================================================================
// ***** 		    Funcion mostrar fichas de empleados        		       *****
// ==============================================================================
    $scope.cargaFichas = function(){
        $(".loading").show();
        $scope.fichas = [];
        $.getJSON( global_apiserver + "/personal_interno/getAll/", function( response ) {
            //console.log();
            $scope.total = response.length;
            $scope.fichas = response;
            $scope.$apply();
            $(".loading").hide();
        });
    }

// ==============================================================================
// ***** 		    Funcion mostrar fichas de empleados filtrada     		       *****
// ==============================================================================
    $scope.cargaFichasFiltradas = function(){
        $(".loading").show();
        $scope.fichas = [];
        $scope.error_filtro = false;
        var filtros = {
            NO: $("#txtNo").val(),
            EDAD: $("#txtEdad").val(),
            EDAD_CONTAINS: $("#txtEdadContains").val(),
            CURP: $("#txtCurp").val(),
            CURP_CONTAINS: $("#txtCurpContains").val(),
            NOMBRE: $("#txtNombre").val(),
            NOMBRE_CONTAINS: $("#txtNombreContains").val(),
            SEXO: $("#cmbSexo").val(),
            SEGURO: $("#txtNoSS").val(),
            SEGURO_CONTAINS: $("#txtNoSSContains").val(),
            APELLIDO_PATERNO: $("#txtAPaterno").val(),
            APELLIDO_PATERNO_CONTAINS: $("#txtAPaternoContains").val(),
            ESTADO_CIVIL: $("#cmbEstadoCivil").val(),
            ALTA_BAJA: $("#cmbEstado").val()

        };
        $.post(global_apiserver + "/personal_interno/getByFiltro/", JSON.stringify(filtros), function(respuesta){
            response = JSON.parse(respuesta);
            $scope.total = response.length;
            if($scope.total==0)
            {
                $scope.error_filtro = true;
            }
            $scope.fichas = response;
            $scope.$apply();
            $(".loading").hide();
        });
    }

// ================================================================================
// *****                       Calcular edad                          *****
// ================================================================================
     $scope.calcular_edad = function(fecha){
             var birthday_arr = fecha.split("/");
             var birthday_date = new Date(birthday_arr[2], birthday_arr[1] - 1, birthday_arr[0]);
             var ageDifMs = Date.now() - birthday_date.getTime();
             var ageDate = new Date(ageDifMs);
             return Math.abs(ageDate.getUTCFullYear() - 1970);
    }
// ================================================================================
// *****                       format fecha                          *****
// ================================================================================
    $scope.formatFecha = function(fecha)
    {
        return fecha.substring(8,10)+"/"+fecha.substring(5,7)+"/"+fecha.substring(0,4);
    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL INSERTAR ACTUALIZAR	                *****
// =======================================================================================
    $scope.openModalInsertUpdate = function(object){
        $scope.accion = "insertar";
        $scope.modal_titulo = "Insertar Empleado";
        $scope.objectSeleted = null;
        onDatePicker();
        clear_form();
        if(typeof object !== "undefined")
        {
          $scope.accion = "editar";
          $scope.modal_titulo = "Editando Empleado";
          $scope.objectSeleted = object;
          $scope.formData.no  = object.NO_EMPLEADO;
          $scope.formData.nombre  = object.NOMBRE;
          $scope.formData.apellidoP  = object.APELLIDO_PATERNO;
          $scope.formData.apellidoM  = object.APELLIDO_MATERNO;
          $scope.formData.curp  = object.CURP;
          $scope.formData.fecha_nacimiento  = $scope.formatFecha(object.FECHA_NACIMIENTO);
          $scope.formData.cmbSexo  = object.SEXO;
          $scope.formData.estado_civil  = object.ESTADO_CIVIL;
          $scope.formData.no_seguridad  = object.NO_SEGURO_SOCIAL;
          $scope.formData.telefono  = object.TELEFONO;
          $scope.formData.email  = object.EMAIL;
          $scope.formData.direccion  = object.DIRECCION;
          $scope.formData.estado  = object.ISACTIVO;
          onDatePicker($scope.formatFecha(object.FECHA_NACIMIENTO));
        }


            $("#modalInsertUpdate").modal("show");

    }
// =======================================================================================
// ***** 			                FUNCION PARA VALIDAR FORM	                    *****
// =======================================================================================
    $scope.valida_form = function(){
        $scope.respuesta =  1;
        var setfocus = null;


////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.direccion !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.direccion.length == 0) {
                $scope.respuesta = 0;
                $scope.direccionerror="No debe estar vacio";
                setfocus = "direccion";
            } else {
                $scope.direccionerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.direccionerror="No debe estar vacio";
            setfocus = "direccion";
        }
/////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.email !== "undefined") {
            if ($scope.formData.email.length == 0) {
                /*$scope.respuesta = 0;
                $scope.emailerror = "No debe estar vacio";
                setfocus = "email";*/
            } else {

                if($scope.validar_email($scope.formData.email))
                {
                    $scope.emailerror = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.emailerror = "Correo electrónico inválido";
                    setfocus = "email";
                }
            }
        }/*else {
            $scope.respuesta = 0;
            $scope.emailerror = "No debe estar vacio";
            setfocus = "email";
        }*/
/////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.telefono !== "undefined") {
            if ($scope.formData.telefono.length == 0) {
                $scope.respuesta = 0;
                $scope.telefonoerror = "No debe estar vacio";
                setfocus = "telefono";
            } else {

                if($scope.validar_telefono($scope.formData.telefono))
                {
                    $scope.telefonoerror = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.telefonoerror = "Teléfono inválido";
                    setfocus = "telefono";
                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.telefonoerror = "No debe estar vacio";
            setfocus = "telefono";
        }
/////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.no_seguridad !== "undefined") {
            if ($scope.formData.no_seguridad.length == 0) {
                $scope.respuesta = 0;
                $scope.no_seguridaderror = "No debe estar vacio";
                setfocus = "no_seguridad";
            } else {

                if($scope.validar_no_seguridad($scope.formData.no_seguridad))
                {
                    $scope.no_seguridaderror = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.no_seguridaderror = "No. de Seguridad Social inválido";
                    setfocus = "no_seguridad";
                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.no_seguridaderror = "No debe estar vacio";
            setfocus = "no_seguridad";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.estado_civil !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.estado_civil.length == 0) {
                $scope.respuesta = 0;
                $scope.estado_civilerror="No debe estar vacio";
                setfocus = "estado_civil";
            } else {
                $scope.estado_civilerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.estado_civilerror="No debe estar vacio";
            setfocus = "estado_civil";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.cmbSexo !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.cmbSexo.length == 0) {
                $scope.respuesta = 0;
                $scope.cmbSexoerror="No debe estar vacio";
                setfocus = "cmbSexo";
            } else {
                $scope.cmbSexoerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.cmbSexoerror="No debe estar vacio";
            setfocus = "cmbSexo";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.fecha_nacimiento !== "undefined") {
            if ($scope.formData.fecha_nacimiento.length == 0) {
                $scope.respuesta = 0;
                $scope.fecha_nacimientoerror="No debe estar vacio";
                setfocus = "fecha_nacimiento";
            } else {
                $scope.fecha_nacimientoerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.fecha_nacimientoerror="No debe estar vacio";
            setfocus = "fecha_nacimiento";
        }
/////////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.curp !== "undefined") {
            if ($scope.formData.curp.length == 0) {
                $scope.respuesta = 0;
                $scope.curperror = "No debe estar vacio";
                setfocus = "curp";
            } else {

                if($scope.curpValida($scope.formData.curp))
                {
                    $scope.curperror = "";
                }
                else
                {
                    $scope.respuesta = 0;
                    $scope.curperror = "CURP inválido";
                    setfocus = "curp";
                }
            }
        }else {
            $scope.respuesta = 0;
            $scope.curperror = "No debe estar vacio";
            setfocus = "curp";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.apellidoM !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.apellidoM.length == 0) {
                $scope.respuesta = 0;
                $scope.apellidoMerror="No debe estar vacio";
                setfocus = "apellidoM";
            } else {
                $scope.apellidoMerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.apellidoMerror="No debe estar vacio";
            setfocus = "apellidoM";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.apellidoP !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.apellidoP.length == 0) {
                $scope.respuesta = 0;
                $scope.apellidoPerror="No debe estar vacio";
                setfocus = "apellidoP";
            } else {
                $scope.apellidoPerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.apellidoPerror="No debe estar vacio";
            setfocus = "apellidoP";
        }
////////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.nombre !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.nombre.length == 0) {
                $scope.respuesta = 0;
                $scope.nombreerror="No debe estar vacio";
                 setfocus = "nombre";
            } else {
                $scope.nombreerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.nombreerror="No debe estar vacio";
            setfocus = "nombre";
        }
////////////////////////////////////////////////////////////////////////////////
        if(typeof $scope.formData.no !== "undefined") {
            $("#noerror").text("");
            if ($scope.formData.no.length == 0) {
                $scope.respuesta = 0;
                $scope.noerror="No debe estar vacio";
                setfocus = "no";
            } else {
                $scope.noerror="";
            }
        }else {
            $scope.respuesta = 0;
            $scope.noerror="No debe estar vacio";
            setfocus = "no";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }

    }
// ===========================================================================
// ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
// ===========================================================================
    $scope.submitForm = function (formData) {

        $scope.valida_form();
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
// ***** 			FUNCION PARA INSERTAR UN EMPLEADO				 *****
// ===========================================================================
    function insertar(formData) {

        var empleado = {
            NO: formData.no,
            NOMBRE: formData.nombre,
            APELLIDO_PATERNO: formData.apellidoP,
            APELLIDO_MATERNO: formData.apellidoM,
            CURP: formData.curp,
            FECHA: formData.fecha_nacimiento,
            SEXO: formData.cmbSexo,
            ESTADO_CIVIL: formData.estado_civil,
            NO_SEGURIDAD:formData.no_seguridad,
            TELEFONO:formData.telefono,
            EMAIL:formData.email,
            DIRECCION:formData.direccion,
            ESTADO:(formData.estado?1:0),

        };
        $.post(global_apiserver + "/personal_interno/insert/", JSON.stringify(empleado), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $scope.cargaFichas();
                $("#modalInsertUpdate").modal("hide");
                notify("Éxito", "Se ha agregado un nueno empleado", "success");
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });


    }

// ===========================================================================
// ***** 			FUNCION PARA EDITAR UN EMPLEADO				 *****
// ===========================================================================
    function editar(formData) {

        var empleado = {
            NO: formData.no,
            NOMBRE: formData.nombre,
            APELLIDO_PATERNO: formData.apellidoP,
            APELLIDO_MATERNO: formData.apellidoM,
            CURP: formData.curp,
            FECHA: formData.fecha_nacimiento,
            SEXO: formData.cmbSexo,
            ESTADO_CIVIL: formData.estado_civil,
            NO_SEGURIDAD:formData.no_seguridad,
            TELEFONO:formData.telefono,
            EMAIL:formData.email,
            DIRECCION:formData.direccion,
            ESTADO:(formData.estado?1:0),

        };

        $.post(global_apiserver + "/personal_interno/update/", JSON.stringify(empleado), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $scope.cargaFichas();
                $("#modalInsertUpdate").modal("hide");
                notify("Éxito", "Se ha editado el empleado", "success");
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });


    }


// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    function onDatePicker(date) {

        var fecha_nacimiento = $('#fecha_nacimiento').datepicker({
            dateFormat: "dd/mm/yy",
            minDate: init,
            language: "es",
            onSelect: function (dateText, ins) {
                $scope.formData.fecha_nacimiento = dateText;
            }
        }).css("display", "inline-block");

        var init = '-20Y';
        if(typeof date !== "undefined")
        {
            init = date;
        }
        fecha_nacimiento.datepicker("option", "minDate", init);
    }

// =======================================================================================
// *****                       Función para validar CURP                    		 *****
// =======================================================================================
    $scope.curpValida = function(input) {
        if(typeof input !== "undefined") {
            var curp = input;
            var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0\d|1[0-2])(?:[0-2]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
                validado = curp.match(re);

            if (!validado)  //Coincide con el formato general?
                return false;

            //Validar que coincida el dígito verificador
            function digitoVerificador(curp17) {
                //Fuente https://consultas.curp.gob.mx/CurpSP/
                var diccionario = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                    lngSuma = 0.0,
                    lngDigito = 0.0;
                for (var i = 0; i < 17; i++)
                    lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
                lngDigito = 10 - lngSuma % 10;
                if (lngDigito == 10)
                    return 0;
                return lngDigito;
            }

            if (validado[2] != digitoVerificador(validado[1]))
                return false;

            return true; //Validado
        }else return false;
    }
// =======================================================================================================
// *****                           Accion al cambiar valor del campo curp		 *****
// =======================================================================================================
    $scope.onChangeCURP= function () {
        $scope.curperror=($scope.curpValida($scope.formData.curp)?'':'CURP Inválido');
    }

// =======================================================================================================
// *****    validar No. Seguridad Social                                	 *****
// =======================================================================================================
    function nssValido(nss) {
        const re       = /^(\d{2})(\d{2})(\d{2})\d{5}$/,
            validado = nss.match(re);

        if (!validado)  // 11 dígitos y subdelegación válida?
            return false;

        const subDeleg = parseInt(validado[1],10),
            anno     = new Date().getFullYear() % 100;
        var   annoAlta = parseInt(validado[2],10),
            annoNac  = parseInt(validado[3],10);

        //Comparar años (excepto que no tenga año de nacimiento)
        if (subDeleg != 97) {
            if (annoAlta <= anno) annoAlta += 100;
            if (annoNac  <= anno) annoNac  += 100;
            if (annoNac  >  annoAlta)
                return false; // Err: se dio de alta antes de nacer!
        }

        return luhn(nss);
    }

// Algoritmo de Luhn
//  https://es.wikipedia.org/wiki/Algoritmo_de_Luhn
    function luhn(nss) {
        var suma   = 0,
            par    = false,
            digito;

        for (var i = nss.length - 1; i >= 0; i--) {
            var digito = parseInt(nss.charAt(i),10);
            if (par)
                if ((digito *= 2) > 9)
                    digito -= 9;

            par = !par;
            suma += digito;
        }
        return (suma % 10) == 0;
    }


//Handler para el evento cuando cambia el input
//Elimina cualquier caracter no numérico y comprueba validez
     $scope.validar_no_seguridad = function(input) {
         if(typeof input !== "undefined") {
             var nss = input.replace(/\D+/g, "");
             if (nssValido(nss)) { // ⬅️ Acá se comprueba
                 return true;
             } else {
                 return false;
             }
         }else {return false;}

    }
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_telefono = function (telefono)
    {
        var caract = new RegExp(/(^[0-9]{1,10}$)/);

        if (caract.test(telefono) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_email = function (email)
    {
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

        if (caract.test(email) == false){
            return false;
        }else{
            return true;
        }
    }
// =======================================================================================================
// *****               Funcion para limpiar las variables del formulario INSERTAR EDITAR			 *****
// =======================================================================================================
    function clear_form(){
        $scope.formData.no = '';
        $scope.formData.nombre = '';
        $scope.formData.apellidoP = '';
        $scope.formData.apellidoM = '';
        $scope.formData.curp = '';
        $scope.formData.fecha_nacimiento = '';
        $scope.formData.cmbSexo = '';
        $scope.formData.estado_civil = '';
        $scope.formData.no_seguridad = '';
        $scope.formData.telefono = '';
        $scope.formData.email = '';
        $scope.formData.direccion = '';
        $scope.formData.estado = '';
        $scope.objectSeleted = null;
    }
// =======================================================================================================
// *****                               Funcion para eliminar empleado		                         *****
// =======================================================================================================
    $scope.eliminar = function(key)
    {
        $.confirm({
            title: 'Confirmación',
            content: "¿Estás seguro que desea eliminar la inscripción del cliente: "+$scope.objectSeleted.NOMBRE+"?",
            buttons: {
                Eliminar: function () {
                    var add = {
                        NO_EMPLEADO: $scope.objectSeleted.NO_EMPLEADO
                    }
                    $.post(global_apiserver + "/personal_interno/delete/", JSON.stringify(add), function (respuesta) {
                        respuesta = JSON.parse(respuesta);
                        if (respuesta.resultado == "ok") {
                            $scope.cargaFichas();
                            $("#modalInsertUpdate").modal("hide");
                            notify("Éxito", "Ha sido eliminado el empleado", "success");

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
                    $scope.cargaFichas();
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
$(document).ready(function () {



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
