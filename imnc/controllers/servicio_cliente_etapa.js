// =======================================================================================
// ***** Creación del controlador con el nombre "servicio_cliente_etapa_controller". *****
// =======================================================================================
app.controller('servicio_cliente_etapa_controller', ['$scope', '$http', function($scope, $http) {

    $scope.titulo = 'Servicios contratados'; //Titulo que aparece en el html
    $scope.modulo_permisos = global_permisos["SERVICIOS"];
    $scope.id_servicio = 0; //No hay documento seleccionado
    $scope.formData = {};
    $scope.datos = {};
    $scope.Etapas1 = { 0: { ID_ETAPA: "3", ETAPA: "Asignación" }, 1: { ID_ETAPA: "12", ETAPA: "Transferencia" } };
    $scope.Etapas2 = { 0: { ID_ETAPA: "17", ETAPA: "Asignación" } };
    $scope.cantidad_servicios = 0;

    /*AQUI VOY A INICIALIZAR LAS VARIABLES A USAR EN LOS FILTROS*/
    // =======================================================================================
    // ***** 		AQUI VOY A INICIALIZAR LAS VARIABLES A USAR EN LOS FILTROS			 *****
    // =======================================================================================
     $scope.total = 0;
     $scope.filtros = Array();
     $scope.campos = [
         {
             "nombre" : "Nombre del cliente",
             "value": "CLIENTES.NOMBRE",
             "sub": 0

         },
         {
             "nombre" : "RFC del cliente",
             "value": "CLIENTES.RFC",
             "sub": 0

         },
         {
             "nombre" : "Referencia",
             "value": "SERVICIO_CLIENTE_ETAPA.REFERENCIA",
             "sub": 0

         },
         {
             "nombre" : "Servicio",
             "value": "SERVICIOS.NOMBRE",
             "sub": 0

         },
         {
             "nombre" : "Tipo de Servicio",
             "value": "TIPOS_SERVICIO.NOMBRE",
             "sub": 0

         },
         {
             "nombre" : "Trámite",
             "value": "ETAPAS_PROCESO.ETAPA",
             "sub": 0

         },
         {
             "nombre" : "Norma",
             "value": "SCE_NORMAS.ID_NORMA",
             "sub": 1
         },
         {
             "nombre" : "Curso",
             "value": "C.NOMBRE",
             "sub": 3
         }

     ]


    // =======================================================================================
    // ***** 		Función para actualizar la tabla con los registros en la BD.		 *****
    // =======================================================================================
    $scope.tabla_servicios = function() {

        var tablaDatos1 = new Array();
        var indice1 = 0;
        $.post(global_apiserver + "/servicio_cliente_etapa/getAll/", function(response) {
            response = JSON.parse(response);
            $.each(response, function(indice, datos) {


                tablaDatos1[indice1] = angular.fromJson(datos);
                indice1 += 1;

            });
            $scope.tablaDatos = tablaDatos1;
            $scope.cantidad_servicios = indice1;
            $scope.$apply();
        });
    }

    // =======================================================================================
    // ***** 			FUNCION PARA EL BOTON AGREGAR SERVICIOS CONTRATADOS				 *****
    // =======================================================================================
    $scope.nuevoServicio = function() {

            $scope.modal_titulo = "Insertar documento";
            $scope.accion = "insertar";
            clear_modal_insertar_actualizar();
            $("#modalInsertarActualizar").modal("show");

        }
        // =======================================================================================
        // ***** 			FUNCION PARA EL BOTON EDITAR SERVICIOS CONTRATADOS				 *****
        // =======================================================================================
    $scope.editarServicio = function(id_servicio) {
        $scope.modal_titulo = "Editar servicio";
        $scope.accion = "editar";
        $scope.id_servicio = id_servicio;
        cargarCambios();
        llenar_modal_insertar_actualizar();
        $("#modalInsertarActualizar").modal("show");
    }


    // =======================================================================================
    // ***** 		FUNCION PARA LLENAR EL MODAL EDITAR SERVICIOS CONTRATADOS			 *****
    // =======================================================================================
    function llenar_modal_insertar_actualizar() {
        var servicio_obtenido =
            $scope.tablaDatos.find(function(element, index, array) {
                return element.ID == $scope.id_servicio
            });

        $scope.formData.txtReferencia = servicio_obtenido.REFERENCIA;
        $scope.formData.claveCliente = servicio_obtenido.ID_CLIENTE;
        $scope.formData.claveServicio = servicio_obtenido.ID_SERVICIO;
        $scope.formData.sel_tipoServicio = servicio_obtenido.ID_TIPO_SERVICIO;
        cargartipoServicio($scope.formData.claveServicio);


        if (servicio_obtenido.ID_SERVICIO == 3) {
            $('#txtsel_tipoServicio').text("Módulo");
            $('#divNorma').hide();
            $('#divCursos').show();
            cargarCursos(servicio_obtenido.ID_TIPO_SERVICIO);
            $scope.formData.sel_Cursos = servicio_obtenido.NORMAS[0].ID_CURSO;
            $scope.formData.cantidad_participantes = servicio_obtenido.NORMAS[0].CANTIDAD_PARTICIPANTES;
            cargarEtapas($scope.formData.claveServicio, servicio_obtenido.ID_ETAPA_PROCESO);

        } else {

            $('#txtsel_tipoServicio').text("Tipo de Servicio para generar Referencia");
            $('#divNorma').show();
            $('#divCursos').hide();
            cargarNormastipoServicio($scope.formData.sel_tipoServicio, servicio_obtenido.NORMAS);
            $scope.formData.cambio = servicio_obtenido.CAMBIO;

            $scope.formData.chk = {};
            $scope.formData.descripcion = {};
            if (servicio_obtenido.CAMBIO == "S") {

                TraerTodosCambios();
                var cambios_obtenido = "";
                var ciclo = ObtenerCicloDeReferencia(servicio_obtenido.REFERENCIA);

                for (var i = 0; i < $scope.Cambios.length; i++) {
                    //	for( var key in $scope.Cambios){
                    cambios_obtenido = "";
                    cambios_obtenido =
                        $scope.TodosCambios.find(function(element, index, array) {
                            return (element.ID_SERVICIO_CONTRATADO == $scope.id_servicio && element.ID_ETAPA == servicio_obtenido.ID_ETAPA_PROCESO && element.ID_TIPO_CAMBIO == $scope.Cambios[i].ID && element.CICLO == ciclo)
                        });
                    if (typeof cambios_obtenido != 'undefined') {
                        $scope.formData.chk[$scope.Cambios[i].ID] = true;
                        $scope.formData.descripcion[$scope.Cambios[i].ID] = cambios_obtenido.DESCRIPCION;
                    } else {
                        $scope.formData.chk[$scope.Cambios[i].ID] = false;
                        $scope.formData.descripcion[$scope.Cambios[i].ID] = "";
                    }
                }

            }
            cargarEtapas($scope.formData.claveServicio);
            $scope.formData.etapa = servicio_obtenido.ID_ETAPA_PROCESO;
        }
        $scope.formData.sel_tipoServicio = servicio_obtenido.ID_TIPO_SERVICIO;
        if ($scope.formData.sel_tipoServicio == 18) {
            if (servicio_obtenido.REFERENCIA.substr(7, 1) == 'D') {
                $scope.formData.DICTAMEN_CONSTANCIA = "Dictamen";
            }
            if (servicio_obtenido.REFERENCIA.substr(7, 1) == 'C') {
                $scope.formData.DICTAMEN_CONSTANCIA = "Constancia";
            }

        }
        //$scope.formData.Norma	=	servicio_obtenido.ID_NORMA;
        $scope.formData.etapa = servicio_obtenido.ID_ETAPA_PROCESO;
    }
    // ===========================================================================
    // ***** 		Funcion para eliminar Asignación de las posibles etapas  *****
    // ===========================================================================
    function eliminarAsignacion() {
        //Quitar Asignación de las posibles etapas
        $scope.Etapas = $scope.Etapas.filter(etapa => {
            return etapa.ID_ETAPA !== '3';
        });
        //$scope.Etapas = nuevasEtapas;
    }
    // ===========================================================================
    // ***** 		Funcion para limpiar las variables del modal			 *****
    // ===========================================================================
    function clear_modal_insertar_actualizar() {
        generar_referencia("C1", "XX", "XXX", "YYY", "ZZ");
        $scope.id_servicio = 0;
        //$scope.formData.txtReferencia = "";

        //$scope.formData.txtReferencia = $scope.Referencia;
        $scope.formData.claveCliente = "";
        $scope.formData.claveServicio = "";
        $scope.formData.sel_tipoServicio = "";
        $scope.formData.sel_Cursos = "";
        $scope.formData.cantidad_participantes = "";
        $scope.formData.Normas = [];
        $scope.Normas = [];
        $scope.formData.etapa = "";
        $scope.formData.DICTAMEN_CONSTANCIA = "";
    }

    // ===========================================================================
    // ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
    // ===========================================================================
    $scope.submitForm = function(formData) {
        //alert('Form submitted with' + JSON.stringify(formData));
        if ($scope.accion == 'insertar') {
            var datos = [];
            if ($scope.formData.claveServicio == 3) {
                datos = {
                    ID_CLIENTE: formData.claveCliente,
                    ID_SERVICIO: $scope.formData.claveServicio,
                    ID_TIPO_SERVICIO: $scope.formData.sel_tipoServicio,
                    ID_ETAPA_PROCESO: $scope.formData.etapa,
                    NORMAS: $scope.formData.sel_Cursos,
                    CANTIDAD: $scope.formData.cantidad_participantes,
                    REFERENCIA: formData.txtReferencia,
                    CAMBIO: "N",
                    ID_USUARIO: sessionStorage.getItem("id_usuario")
                };
            } else {
                datos = {
                    ID_CLIENTE: formData.claveCliente,
                    ID_SERVICIO: $scope.formData.claveServicio,
                    ID_TIPO_SERVICIO: $scope.formData.sel_tipoServicio,
                    ID_ETAPA_PROCESO: $scope.formData.etapa,
                    NORMAS: $scope.formData.Normas,
                    REFERENCIA: formData.txtReferencia,
                    CAMBIO: "N",
                    ID_USUARIO: sessionStorage.getItem("id_usuario")
                };
            }

            $http.post(global_apiserver + "/servicio_cliente_etapa/insert/", datos).
            then(function(response) {
                if (response.data.resultado == "ok") {
                    notify('Éxito', 'Se ha insertado un nuevo registro', 'success');
                    $scope.tabla_servicios();
                    TraerTodosCambios();

                } else {
                    notify('Error', response.data.mensaje, response.data.resultado);
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        } else if ($scope.accion == 'editar') {

            if ($scope.formData.claveServicio == 3) {

                var datos = {
                    ID: $scope.id_servicio,
                    ID_CLIENTE: $scope.formData.claveCliente,
                    ID_SERVICIO: $scope.formData.claveServicio,
                    ID_TIPO_SERVICIO: $scope.formData.sel_tipoServicio,
                    NORMAS: $scope.formData.sel_Cursos,
                    CANTIDAD: $scope.formData.cantidad_participantes,
                    ID_ETAPA_PROCESO: $scope.formData.etapa,
                    REFERENCIA: $scope.formData.txtReferencia,
                    CAMBIO: $scope.formData.cambio,
                    CHK: "",
                    DESCRIPCION: "",
                    ID_USUARIO: sessionStorage.getItem("id_usuario")
                };

            } else {

                var chk1 = $scope.formData.chk;

                var descripcion = $scope.formData.descripcion;
                var descripcion2 = "";
                var chk2 = "";
                var cambios_obtenido = "";
                var cambios_obtenido1 = "";
                var ciclo = ObtenerCicloDeReferencia($scope.formData.txtReferencia);
                //			for(var i=1; i<=$scope.Cambios.length; i++){
                for (var key in descripcion) {
                    ////////////////////////////////////////////////////////
                    cambios_obtenido = "";
                    cambios_obtenido =
                        $scope.TodosCambios.find(function(element, index, array) {
                            return (element.ID_SERVICIO_CONTRATADO == $scope.id_servicio && element.ID_ETAPA == $scope.formData.etapa && element.ID_TIPO_CAMBIO == key && element.CICLO == ciclo && element.DESCRIPCION != $scope.formData.descripcion[key])
                        });
                    cambios_obtenido1 = "";
                    cambios_obtenido1 =
                        $scope.TodosCambios.find(function(element, index, array) {
                            return (element.ID_SERVICIO_CONTRATADO == $scope.id_servicio && element.ID_ETAPA == $scope.formData.etapa && element.ID_TIPO_CAMBIO == key && element.CICLO == ciclo)
                        });
                    ////////////////////////////////////////////////////////
                    if (chk1[key] == true) {
                        if (typeof cambios_obtenido != 'undefined') {
                            chk2 += key + ";";
                            descripcion2 += descripcion[key] + ";";
                        } else if (typeof cambios_obtenido1 == 'undefined') {
                            chk2 += key + ";";
                            descripcion2 += descripcion[key] + ";";
                        }
                    }

                }
                var datos = {
                    ID: $scope.id_servicio,
                    ID_CLIENTE: $scope.formData.claveCliente,
                    ID_SERVICIO: $scope.formData.claveServicio,
                    ID_TIPO_SERVICIO: $scope.formData.sel_tipoServicio,
                    NORMAS: $scope.formData.Normas,
                    ID_ETAPA_PROCESO: $scope.formData.etapa,
                    REFERENCIA: $scope.formData.txtReferencia,
                    CAMBIO: $scope.formData.cambio,
                    CHK: chk2,
                    DESCRIPCION: descripcion2,
                    ID_USUARIO: sessionStorage.getItem("id_usuario")
                };

            }
            $http.post(global_apiserver + "/servicio_cliente_etapa/update/", datos).then(function(response) {
                if (response.data.resultado == "ok") {
                    notify('Éxito', 'Se ha insertado un nuevo registro', 'success');
                    $scope.tabla_servicios();
                    TraerTodosCambios();

                } else {
                    notify('Error', response.data.mensaje, response.data.resultado);
                }
                $("#modalInsertarActualizar").modal("hide");
            });
        }
    };

    // =======================================================================================
    // *****               Función para observar el campo del formulario         		 *****
    // =======================================================================================
    $scope.$watch('formData.cantidad_participantes', function(nuevo, anterior) {
        if (!nuevo) return;
        if (!$scope.validar_numeros())
            $scope.formData.cantidad_participantes = anterior;
    })

    // =======================================================================================
    // *****               Función para validar que entren solo numeros         		 *****
    // =======================================================================================
    $scope.validar_numeros = function() {
            var valor = $scope.formData.cantidad_participantes;
            valor = eliminaEspacios(valor);
            reg = /(^[0-9]{1,4}$)/;
            if (!reg.test(valor)) {
                $scope.formData.cantidad_participantes = "";
                // Si hay error muestro el div que contiene el error
                $("#minimo").focus();
                return false;
            } else
                return true;
        }
        // =======================================================================================
        // *****               Función para eliminar espacios a una cadena          		 *****
        // =======================================================================================
    function eliminaEspacios(cadena) {
        // Funcion equivalente a trim en PHP
        var x = 0,
            y = cadena.length - 1;
        while (cadena.charAt(x) == " ") x++;
        while (cadena.charAt(y) == " ") y--;
        return cadena.substr(x, y - x + 1);
    }

    // ===========================================================================
    // ***** 			Función para traer las sectores IAF.				 *****
    // ===========================================================================
    $scope.funcionsectoresIAF = function() {
            $http.get(global_apiserver + "/sectores/getAll/")
                .then(function(response) {
                    $scope.cmbSectoresIAF = response.data;
                });

        }
        // ===================================================================
        // ***** 			FUNCION PARA TRAER LOS CLIENTES				 *****
        // ===================================================================
    function cargarClientes() {
        $http.get(global_apiserver + "/clientes/getAll/")
            .then(function(response) {
                $scope.claveClientes = response.data;
            });
    }

    // ==================================================================
    // ***** 	Función para traer las claves de servicio.			*****
    // ==================================================================
    function cargarServicios() {
        $http.get(global_apiserver + "/servicios/getAll/")
            .then(function(response) {
                $scope.claveServicios = response.data;
            });
    }
    // ==================================================================
    // ***** 				Cambio clave Servicio					*****
    // ==================================================================
    $scope.cambioclaveServicio = function(id_servicio) {

            cargartipoServicio(id_servicio);
            cargarEtapas(id_servicio);
            if (id_servicio == 3) {
                $('#txtsel_tipoServicio').text("Módulo");
                $('#divNorma').hide();
                $('#divCursos').show();
                generar_referencia_cifa(id_servicio);
                $scope.formData.cantidad_participantes = "";

            } else {
                $('#txtsel_tipoServicio').text("Tipo de Servicio para generar Referencia");
                $('#divNorma').show();
                $('#divCursos').hide();
                $scope.formData.etapa = "";
                var ref = $scope.formData.txtReferencia;
                var etapa = "XX";
                var tipo_servicio = "XXX";
                var norma = "YYY";
                var dict_const = "ZZ";
                if ($scope.formData.sel_tipoServicio)
                    tipo_servicio = $scope.formData.sel_tipoServicio;
                if ($scope.formData.etapa)
                    etapa = $scope.formData.etapa;
                if ($scope.formData.DICTAMEN_CONSTANCIA)
                    dict_const = $scope.formData.DICTAMEN_CONSTANCIA;
                if ($scope.formData.Normas[0])
                    norma = $scope.formData.Normas[0].ID_NORMA;

                generar_referencia(ref, etapa, tipo_servicio, norma, dict_const);
            }




        }
        // ==============================================================================
        // ***** 	Funcion para traer los tipos de Servicios para este Servicio	*****
        // ==============================================================================
    function cargartipoServicio(id_servicio) {
        $http.get(global_apiserver + "/tipos_servicio/getByService/?id=" + id_servicio)
            .then(function(response) {
                $scope.sel_tipoServicios = response.data;
            });
    }
    // ==================================================================
    // ***** 				Cambio Tipo Servicio					*****
    // ==================================================================
    $scope.cambiosel_tipoServicio = function(id_tipo_servicio) {
            if ($scope.formData.claveServicio == 3) {
                cargarCursos(id_tipo_servicio);
            } else {
                cargarNormastipoServicio(id_tipo_servicio);
            }


            var ref = $scope.formData.txtReferencia;
            var etapa = "XX";
            var norma = "YYY";
            var dict_const = "ZZ";
            var tipo_servicio = $scope.formData.sel_tipoServicio;
            if ($scope.formData.etapa)
                etapa = $scope.formData.etapa;
            if ($scope.formData.DICTAMEN_CONSTANCIA) {
                dict_const = $scope.formData.DICTAMEN_CONSTANCIA;
            }
            if ($scope.formData.Normas[0]) {
                norma = $scope.formData.Normas[0].ID_NORMA;
            }
            if ($scope.formData.claveServicio != 3)
                generar_referencia(ref, etapa, tipo_servicio, norma, dict_const);
        }
        // ==============================================================================
        // ***** 	Funcion para traer los Cursos de este tipo de Servicio			*****
        // ==============================================================================
    function cargarCursos(id_tipo_servicio) {
        $http.get(global_apiserver + "/cursos/getByModulo/?id=" + id_tipo_servicio)
            .then(function(response) {
                $scope.sel_Cursos = response.data;
            })
    }
    // ==============================================================================
    // ***** 	Funcion para traer las Normas de este tipo de Servicio			*****
    // ==============================================================================
    function cargarNormastipoServicio(id_tipo_servicio, normas_a_mostrar) {
        if (!normas_a_mostrar) {
            normas_a_mostrar = [];
        }
        if (normas_a_mostrar)
        //Agregue normas a mostrar para cuando sea edición 
        //Se muestre en el multiselect las que tiene seleccionadas
        //Mientras que en las sugerencias no se muestren estas
            $http.get(global_apiserver + "/normas_tiposervicio/getNormabyIdTipoServicio/?id=" + id_tipo_servicio)
            .then(function(response) {
                $scope.Normas = [];
                response.data.forEach(norma_asociada => {
                    var found = normas_a_mostrar.find(function(norma_mostrar) {
                        return norma_mostrar.ID_NORMA == norma_asociada.ID_NORMA;
                    });
                    if (!found) {
                        $scope.Normas.push(norma_asociada);
                    }
                });
                if ($scope.Normas.length == 1) {
                    $scope.formData.Normas = $scope.Normas;
                } else {
                    if (normas_a_mostrar || normas_a_mostrar.length == 0) {
                        $scope.formData.Normas = normas_a_mostrar;
                    } else {
                        $scope.formData.Normas = [];
                    }
                }
            });
    }
    // ==============================================================================
    // ***** 	Funcion para traer las etapas	*****
    // ==============================================================================
    function cargarEtapas(id_servicio, seleccion) {
        var inicial = null;
        $http.get(global_apiserver + "/etapas_proceso/getByIdServicio/?id=" + id_servicio)
            .then(function(response) { //se ejecuta cuando la petición fue correcta
                    $scope.Etapas = response.data.map(function(item) {
                        if (item.ETAPA == "INSCRITO")
                            inicial = item.ID_ETAPA;
                        return {
                            ID_ETAPA: item.ID_ETAPA,
                            ETAPA: item.ETAPA
                        }
                    });
                    if (inicial) {
                        $scope.formData.etapa = seleccion ? seleccion : inicial;
                    }
                },
                function(response) {});
    }
    // ==================================================================
    // ***** 				Cambio Etapas					*****
    // ==================================================================
    $scope.cambioEtapa = function() {
            var ref = $scope.formData.txtReferencia;
            var etapa = $scope.formData.etapa;
            var tipo_servicio = "XXX";
            var norma = "YYY";
            var dict_const = "ZZ";
            if ($scope.formData.sel_tipoServicio)
                tipo_servicio = $scope.formData.sel_tipoServicio;
            if ($scope.formData.DICTAMEN_CONSTANCIA) {
                dict_const = $scope.formData.DICTAMEN_CONSTANCIA;
            }
            if ($scope.formData.Normas[0]) {
                norma = $scope.formData.Normas[0].ID_NORMA;
            }
            if ($scope.formData.claveServicio != 3)
                generar_referencia(ref, etapa, tipo_servicio, norma, dict_const);

        }
        // ==============================================================================
        // ***** 	Funcion para traer los tipos de cambios	*****
        // ==============================================================================
    function cargarCambios() {
        $http.get(global_apiserver + "/i_servicios_contratados_tipos_cambios/getAll/")
            .then(function(response) {
                $scope.Cambios = response.data;

            });
    }

    // ==============================================================================
    // ***** 	Funcion para traer todos los cambios	*****
    // ==============================================================================
    function TraerTodosCambios() {
        $http.get(global_apiserver + "/i_servicios_contratados_cambios/getAll/")
            .then(function(response) {

                $scope.TodosCambios = response.data;
                //$scope.$apply();
            });

    }
    // ==============================================================================
    // ***** 	Funcion para obtener ciclo a partir de referencia	*****
    // ==============================================================================
    function ObtenerCicloDeReferencia(referencia) {
        var c1 = referencia.split("-");
        var c2 = c1[0].split("C");
        return c2[1];

    }

    // ==============================================================================
    // ***** 		Funcion para generar referencia	de forma automatica			*****
    // ==============================================================================	
    function generar_referencia(ref, etapa, tipo_servicio, norma, dict_const) {
        if (!tipo_servicio) {
            tipo_servicio = "XXX";
        }
        if (!etapa) {
            etapa = "XX";
        }


        $http.get(global_apiserver + "/tipos_servicio/generarReferencia/?ref=" + ref + "&etapa=" + etapa + "&id=" + tipo_servicio + "&norma=" + norma + "&dict_const=" + dict_const)
            .then(function(response) {

                //$scope.formData.txtReferencia = response.data;
                $scope.formData.txtReferencia = response.data;

            });


    }

    // ======================================================================
    // ***** Cambio Dictamen COnstancia solo para informacion COmercial	*****
    // ======================================================================
    $scope.cambio_dictamen_constancia = function(dict_const) {



            var ref = $scope.formData.txtReferencia;
            var etapa = "XX";
            var tipo_servicio = "XXX";
            var norma = "YYY";
            if ($scope.formData.etapa)
                etapa = $scope.formData.etapa;
            if ($scope.formData.sel_tipoServicio) {
                tipo_servicio = $scope.formData.sel_tipoServicio;
            }
            if ($scope.formData.Normas[0].ID_NORMA) {
                norma = $scope.formData.Normas[0].ID_NORMA;
            }
            if ($scope.formData.claveServicio != 3) {
                generar_referencia(ref, etapa, tipo_servicio, norma, dict_const);
            }
        }
        // ==============================================================================
        // ***** 		Funcion para generar referencia	para CIFA		*****
        // ==============================================================================
    function generar_referencia_cifa(id_servicio) {
        $http.get(global_apiserver + "/cursos/getReferencia/?id=" + id_servicio + "&tipo=D")
            .then(function(response) {
                $scope.formData.txtReferencia = response.data;
            });
    }
// ==============================================================================
// ***** 		    Funcion btn filtrar accion                   		*****
// ==============================================================================
    $scope.cargaServiciosFiltrados = function() {


            var sql = "";
            var subsql1 = "";
            var subsql3 = "";

            $. each( $scope.filtros, function(i, n) {

                if(n.valor)
                {
                    var character = (n.container==1?"%":"");
                    if(n.sub == 0)
                    {
                        sql += " "+n.andor+" "+n.sql+" LIKE '"+character+n.valor+"%'";
                    }
                    if(n.sub == 1)
                    {
                        subsql1 += " "+n.andor+" "+n.sql+" LIKE '"+character+n.valor+"%'";
                    }
                    if(n.sub == 3)
                    {
                        subsql3 += " "+n.andor+" "+n.sql+" LIKE '"+character+n.valor+"%'";
                    }

                }


            })

            if(sql){if(sql.indexOf("AND")==1) sql = sql.substring(sql.indexOf("AND")+3,sql.length) ;}
            if(subsql1){if(subsql1.indexOf("AND")!=1) subsql1 = " AND"+ subsql1 ;}
            if(subsql3){if(subsql3.indexOf("AND")!=1) subsql3 = " AND"+ subsql3 ;}
            var filtros = { QUERY : sql?" WHERE"+sql:"",  QUERY1: subsql1,  QUERY3: subsql3};
            $.post(global_apiserver + "/servicio_cliente_etapa/getByFiltro/", JSON.stringify(filtros), function(respuesta) {
                response = JSON.parse(respuesta);
                $scope.cantidad_servicios = response.length;
                $scope.tablaDatos = response;

                $scope.$apply();
            });




    }

// ==============================================================================
// ***** 		    Funcion mostrar opciones para filtrar           		*****
// ==============================================================================
    $scope.showFiltrar = function()
    {
        $scope.mytoggle('divFitrar');
    }
// ================================================================================
// *****                  Funcion Mostrar/Ocultar elementos                   *****
// ================================================================================
    $scope.mytoggle = function (id)
    {
        $("#"+id).toggle(function(){

        },function(){

        });
    }
// ================================================================================
// *****                  Remove input al filtro                   *****
// ================================================================================
    $scope.removeFilter = function(pos)
    {
        $scope.filtros = $scope.filtros.filter(function (elem,index) {
            if(elem.index != pos)
            {
                return true;
            }
            else
            {
                $("#filtro-" + pos).remove();
                $scope.total--;
                return false;
            }

        })
           // $scope.reloadInputs();
            //$scope.events();

    }

// ================================================================================
// *****                  Add input al filtro                   *****
// ================================================================================
    $scope.addInput = function()
    {
        if(!$scope.selectCampo){
            return false;
        }
        if($scope.total>0)
        {
            $scope.filtros[$scope.total]= {index:$scope.total , nombre: $scope.selectCampo.nombre, sql: $scope.selectCampo.value, andor: 'AND' , container: 'false', valor: '', sub: $scope.selectCampo.sub};
        }
        else
        {
            $scope.filtros[$scope.total]= {index:$scope.total , nombre: $scope.selectCampo.nombre, sql: $scope.selectCampo.value, andor: "", container: 'false', valor: '', sub: $scope.selectCampo.sub};
        }
        $scope.total++;

        $scope.selectCampo = "";

    }

    $scope.onRemove = function(pos){
        $scope.removeFilter(pos);
    }

    $scope.changeAndOr = function(pos){
            $scope.filtros[pos].andor = $("#andor-"+pos).val();
     }
    $scope.changeValor = function(pos){
        if($scope.total>0)
        {
            $scope.filtros[pos].valor = $("#value-"+pos).val();
        }
    }
    $scope.changeContainer = function(pos){
        if($scope.total>0)
        {
            $scope.filtros[pos].container = $("#container-"+pos).val();
        }
    }
    $scope.cancelFilter = function()
    {
        $scope.tabla_servicios();

        $scope.mytoggle('divFitrar');
        $scope.filtros = Array();
        $scope.total = 0;


    }
    $scope.reloadInputs = function()
    {
        $("#divInputContainer").html("");
        $. each( $scope.filtros, function(i, n) {
            var newBoxDiv = $(document.createElement('div'))
                .attr("id", 'filtro-' + n.index);

            var andor = '';
            var html = '';
            if(i>0)
            {
                andor += '<select id="andor-'+n.index+'" ng-model="andor-'+n.index+'" pos="'+n.index+'" ng-init="andor-'+n.index+' = '+ n.andor +'" class="form-control andor" style="font-size: 10px;">' +
                    '  <option value="AND" selected>-- Y --</option>' +
                    '  <option value="OR">-- O --</option>' +
                    '  </select>';

            }
            else {
                $scope.filtros[i].andor = "";
            }

            html+=' <div class="form-group form-inline">\n' +
                andor+'\n'+
                '    <input type="text" class="form-control" id="nombre-'+n.index+'" ng-model="nombre-'+n.index+'" value="'+n.nombre+'"  readonly >\n' +
                '    <select id="container-'+n.index+'" ng-model="container-'+n.index+'" pos="'+n.index+'" class="form-control cont" style="font-size: 10px;" ng-init="container-'+n.index+' = '+ n.container +'">\n' +
                '       <option value="false" selected>Comienza con</option>\n' +
                '       <option value="true">Contiene </option>\n' +
                '    </select>\n' +
                '    <input type="text" class="form-control valor" pos="'+n.index+'" id="value-'+n.index+'" ng-model="value-'+n.index+'" value="'+n.valor+'">\n' +
                '<button type="button" id="remove-'+n.index+'"  name="remove-'+n.index+'" pos="'+n.index+'" class="btn btn-xs remove" ><i class="fa fa-remove"> </i></button>\n'+
                '    </div>';

            newBoxDiv.after().html(html);

            newBoxDiv.appendTo("#divInputContainer");



        }) ;
    }


    $(document).ready(function() {
        cargarClientes();
        cargarServicios();
        //cargarEtapas();
        cargarCambios();
        TraerTodosCambios();
        $scope.funcionsectoresIAF();
        $scope.tabla_servicios();



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

