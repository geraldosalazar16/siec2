/**
 * @ngdoc controller
 * @name controller:reportes_controller
 *
 * @description
 *
 *
 * @requires $scope
 * */
app.controller('reportes_controller',['$scope','$http',function($scope,$http){
    $scope.id_usuario = sessionStorage.getItem("id_usuario");
    $scope.edit_reporte = {};
    $scope.formData = {};
    $scope.columns = [];
    $scope.comercial = [
                {
                    "nombre" : "Prospecto",
                    "value": "P.NOMBRE AS NOMBRE_PROSPECTO|string"
                },
                {
                    "nombre" : "Servicio",
                    "value": "S.NOMBRE AS SERVICIO|string"
                },
                {
                    "nombre" : "Tipo de servicio",
                    "value": "TS.NOMBRE AS TIPO_SERVICIO|string"
                },
                {
                    "nombre" : "Norma",
                    "value": "PPN.ID_NORMA AS NORMA|string"
                },
                {
                    "nombre" : "Estado Federativo",
                    "value": "PD.ESTADO AS ESTADO|string"
                },
                {
                    "nombre" : "Sector",
                    "value": "SEC.NOMBRE AS SECTOR|string"
                },
                {
                    "nombre" : "Fecha de cotización",
                    "value": "COT.FECHA_CREACION AS FECHA_COTIZACION|date"
                },
                {
                    "nombre" : "Fecha de creación del prospecto",
                    "value": "P.FECHA_CREACION AS FECHA_CREACION_PROSPECTO|date"
                },
                {
                    "nombre" : "Usuario que lo creó",
                    "value": "P.USUARIO_CREACION AS PROSPECTO_USUARIO_CREACION|string"
                },
                {
                    "nombre" : "Estado de la cotización",
                    "value": "PES.ESTATUS_SEGUIMIENTO AS ESTATUS_COTIZACION|string"
                },
                {
                    "nombre" : "Monto de la cotización",
                    "value": "COT.MONTO AS MONTO_COTIZACION|string"
                },
                {
                    "nombre" : "Fecha solicitud cotización",
                    "value": "CSF.FECHA_SOLICITUD_COTIZACION AS FECHA_SOLICITUD_COTIZACION|date"
                },
                {
                    "nombre" : "Fecha envío cotización",
                    "value": "CSF.FECHA_ENVIO_COTIZACION AS FECHA_ENVIO_COTIZACION|date"
                },
                {
                    "nombre" : "Fecha cotización firmada",
                    "value": "CSF.FECHA_FIRMADO AS FECHA_FIRMADO|date"
                },
                {
                    "nombre" : "Fecha cotización pedido",
                    "value": "CSF.FECHA_PEDIDO AS FECHA_PEDIDO|date"
                 },
                {
                    "nombre" : "Fecha cotización negociación",
                    "value": "CSF.FECHA_NEGOCIACION AS FECHA_NEGOCIACION|date"
                },
                {
                    "nombre" : "Fecha cotización cancelación",
                    "value": "CSF.FECHA_CANCELADO AS FECHA_CANCELADO|date"
                },
                {
                    "nombre" : "Fecha cotización ejecutado",
                    "value": "CSF.FECHA_EJECUTADO AS FECHA_EJECUTADO|date"
                },
                {
                    "nombre" : "Fecha cotización envio cuestionario",
                    "value": "CSF.FECHA_ENVIO_CUESTIONARIO AS FECHA_ENVIO_CUESTIONARIO|date"
                },
                {
                    "nombre" : "Fecha cotización recepción cuestionario",
                    "value": "CSF.FECHA_RECEPCION_CUESTIONARIO AS FECHA_RECEPCION_CUESTIONARIO|date"
                }

              ];
    $scope.programacion = [
                {
                    "nombre" : "Servicio",
                    "value": "S.NOMBRE AS SERVICIO|string"
                },
                {
                    "nombre" : "Tipo de servicio",
                    "value": "TS.NOMBRE AS TIPO_SERVICIO|string"
                },
                {
                    "nombre" : "Norma",
                    "value": "SCE_NORMAS.ID_NORMA AS NORMA|string"
                },
                {
                    "nombre" : "Estado Federativo",
                    "value": "CD.ENTIDAD_FEDERATIVA AS ESTADO|string"
                },
                {
                    "nombre" : "Cliente",
                    "value": "C.NOMBRE AS CLIENTE|string"
                },
                {
                    "nombre" : "Sector",
                    "value": "SECTORES.NOMBRE as SECTOR|string"
                },
                {
                    "nombre" : "Días auditor cotizados",
                    "value": "SGA.DURACION_DIAS AS DIAS_AUDITOR|int"
                },
                {
                    "nombre" : "Estado del servicio",
                    "value": "ETAPAS_PROCESO.ETAPA AS ESTADO_SERVICIO|string"
                },
                {
                    "nombre" : "Referencia",
                    "value": "SCE.REFERENCIA AS REFERENCIA|string"
                },
                {
                    "nombre" : "Fecha de creación del servicio",
                    "value": "SCE.FECHA_CREACION AS FECHA_CREACION_SERVICIO|date"
                }
    ];

    $scope.auditores = [
        {
            "nombre" : "Nombre y Apellidos",
            "value": "CONCAT(PT.NOMBRE*' '*PT.APELLIDO_PATERNO*' '*PT.APELLIDO_MATERNO) AS NOMBRE|string"
        },
        {
            "nombre" : "Rol",
            "value": "PTR.ROL AS ROL|string"
        },

        {
            "nombre" : "Servicio",
            "value": "S.NOMBRE AS SERVICIO|string"
        },
        {
            "nombre" : "Tipo de servicios",
            "value": "TS.NOMBRE AS TIPO_SERVICIO|string"
        },
        {
            "nombre" : "Sector IAF",
            "value": "(CASE WHEN CONCAT(SC.ID*'-'*SC.ID_TIPO_SERVICIO*'-'*SC.ANHIO) THEN CONCAT(SC.ID*'-'*SC.ID_TIPO_SERVICIO*'-'*SC.ANHIO) ELSE 'N/A'  END)  AS SECTOR|string"
        },
        {
            "nombre" : "Codigo nace",
            "value": "PTCS.SECTOR_NACE AS NACE|string"
        },
        {
            "nombre" : "Status",
            "value": "PT.STATUS AS STATUS|string"
        }
    ];


// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS REPORTES    	 *****
// ===================================================================
    $scope.cargarReportes= function(){
        $http.get(global_apiserver + "/reportes/getByIdUsuario/?id="+$scope.id_usuario)
            .then(function( response ){
                $scope.reportes = response.data;

            });
    }
// ===================================================================
// ***** 			FUNCION PARA CARGAR LAS AREAS    	 *****
// ===================================================================
    $scope.cargarAreas= function(){
        $http.get(global_apiserver + "/reportes/getAllAreas/")
            .then(function( response ){
                $scope.areas = response.data;
            });
    }
// ===================================================================
// ***** 		FUNCION PARA CARGAR PROSPECTO SEGUIMIENTO   	 *****
// ===================================================================
    $scope.cargarProspectoSeguimiento= function(){
        $http.get(global_apiserver + "/prospecto_estatus_seguimiento/getAll/")
            .then(function( response ){
                $scope.prospecto_seguimiento = response.data;
            });
    }

// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS LISTBOX    	 *****
// ===================================================================
    $scope.cargarDualListbox= function(){

        var list = $('#column').bootstrapDualListbox({
            nonSelectedListLabel: 'Sin Seleccionar',
            selectedListLabel: 'Seleccionados',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
        });
       return list;


    }


// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.openModalInsertUpdate = function(key){

        $scope.titulo = "Nuevo Reporte";
        $scope.accion = "insertar";
        $scope.clear_form();
        $scope.cargarAreas();
        $scope.cargarProspectoSeguimiento();

        if (typeof key !== "undefined") {
            $scope.edit_reporte = $scope.reportes[key];
            $scope.titulo = "Editando Reporte";
            $scope.accion = "editar";
            $scope.formData.nombre = $scope.reportes[key].NOMBRE;
            $scope.formData.select_area = { ID_AREA: $scope.reportes[key].ID_AREA,NOMBRE:$scope.reportes[key].AREA};
            $scope.formData.publico =  ($scope.reportes[key].COMPARTIDO == 1?true:false);
            $scope.columns = $scope.reportes[key].COLUMN;
            var array = [];
            $.each($scope.columns,function (i,n) {
                array.push(n.COLUMNA);
            });
            $scope.columns = array;
            $scope.showDualListBox();
        }
        $('.modal-dialog').attr('class','modal-dialog modal-lg');
        $("#modalInsertarActualizar").modal("show");

    }

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.showDualListBox = function(){

        var option = '';
        var list = $scope.cargarDualListbox();

        if($scope.accion == 'insertar')
        {
            if($scope.formData.select_area.ID_AREA == 1) {
                $.each($scope.comercial,function (i,n) {
                    option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                })


            }

            if($scope.formData.select_area.ID_AREA == 2)
            {
                $.each($scope.programacion,function (i,n) {
                    option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                })

            }

            if($scope.formData.select_area.ID_AREA == 3)
            {
                $.each($scope.auditores,function (i,n) {
                    option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                })

            }
            // if($scope.formData.select_area.ID_AREA == 4)
            // {
            //     $.each($scope.prospecto_seguimiento,function (i,n) {
            //         option +='<option value="'+n.ID+'">'+n.DESCRIPCION+'</option>';
            //     })
            //
            // }
        }
        else
        {
            if($scope.formData.select_area.ID_AREA == 1) {
                $.each($scope.comercial,function (i,n) {
                    if($scope.columns.indexOf(n.value)>-1 && $scope.edit_reporte.ID_AREA == $scope.formData.select_area.ID_AREA)
                    {
                        option +='<option value="'+n.value+'" selected="selected">'+n.nombre+'</option>';
                    }
                    else
                    {
                        option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                    }
                })
            }
            if($scope.formData.select_area.ID_AREA == 2) {
                $.each($scope.programacion,function (i,n) {
                    if($scope.columns.indexOf(n.value)>-1 && $scope.edit_reporte.ID_AREA == $scope.formData.select_area.ID_AREA)
                    {
                        option +='<option value="'+n.value+'" selected="selected">'+n.nombre+'</option>';
                    }
                    else
                    {
                     option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                    }
                })
            }
            if($scope.formData.select_area.ID_AREA == 3) {
                $.each($scope.auditores,function (i,n) {
                    if($scope.columns.indexOf(n.value)>-1 && $scope.edit_reporte.ID_AREA == $scope.formData.select_area.ID_AREA)
                    {
                        option +='<option value="'+n.value+'" selected="selected">'+n.nombre+'</option>';
                    }
                    else
                    {
                        option +='<option value="'+n.value+'">'+n.nombre+'</option>';
                    }
                })
            }
            // if($scope.formData.select_area.ID_AREA == 4) {
            //     $.each($scope.prospecto_seguimiento,function (i,n) {
            //         if($scope.columns.indexOf(n.value)>-1 && $scope.edit_reporte.ID_AREA == $scope.formData.select_area.ID_AREA)
            //         {
            //             option +='<option value="'+n.value+'" selected="selected">'+n.nombre+'</option>';
            //         }
            //         else
            //         {
            //             option +='<option value="'+n.value+'">'+n.nombre+'</option>';
            //         }
            //     })
            // }
        }
        list
            .find('option')
            .remove()
            .end()
            .append(option)
        ;
        list.bootstrapDualListbox('refresh');
    }

// =======================================================================================
// ***** 			FUNCION VALIDAR FORMULARIO		                         *****
// =======================================================================================
    $scope.valida_form = function(){
        $scope.respuesta = 1;
        var setfocus = null;

            if ($("#column").val() == null) {
                $scope.respuesta = 0;
                $scope.error_column = "Complete este campo";
                setfocus = "column";
            } else {
                $scope.error_column = "";
            }

        if (typeof $scope.formData.select_area !== "undefined") {
            if ($scope.formData.select_area.length == 0) {
                $scope.respuesta = 0;
                $scope.error_select_area = "Complete este campo";
                setfocus = "select_area";
            } else {
                $scope.error_select_area = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_select_area = "Complete este campo";
            setfocus = "select_area";
        }
        if (typeof $scope.formData.nombre !== "undefined") {
            if ($scope.formData.nombre.length == 0) {
                $scope.respuesta = 0;
                $scope.error_nombre = "Complete este campo";
                setfocus = "nombre";
            } else {
                $scope.error_nombre = "";
            }
        } else {
            $scope.respuesta = 0;
            $scope.error_nombre = "Complete este campo";
            setfocus = "nombre";
        }

        if(setfocus != null)
        {
            $('#'+setfocus).focus();
        }

    }

// =======================================================================================
// ***** 			FUNCION PARA BOTON GUARGAR		                         *****
// =======================================================================================
    $scope.submitGuardar = function(save){
       $scope.valida_form();
        if($scope.respuesta == 1){

            if($scope.accion=='insertar')
            {
                $scope.insertar(save);
            }

            if($scope.accion=='editar')
            {
                $scope.editar(save);
            }

        }

    }

// =======================================================================================
// ***** 			          FUNCION INSERTAR REPORTE      	                   *****
// =======================================================================================
    $scope.insertar = function(save){
        $scope.save = save;
         $scope.add = {
            NOMBRE: $scope.formData.nombre,
            ID_USUARIO: $scope.id_usuario,
            COMPARTIDO: ($scope.formData.publico==true?1:0),
            ID_AREA: $scope.formData.select_area.ID_AREA,
            COLUMN: $("#column").val(),
            };

        if($scope.save)
        {
            $.post(global_apiserver + "/reportes/insert/", JSON.stringify($scope.add), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $scope.cargarReportes();
                    notify("Éxito", "Se guardó el reporte", "success");
                    $("#modalInsertarActualizar").modal("hide");
                    $scope.generarEXCEL();
                    $scope.clear_form();
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }
            });
        }
        else {
            $scope.generarEXCEL();
        }


    }
// =======================================================================================
// ***** 			          FUNCION UPDATE REPORTE      	                   *****
// =======================================================================================
    $scope.editar = function(save){

        $scope.save = save;
        var diff = [];
        if($scope.edit_reporte.ID_AREA == $scope.formData.select_area.ID_AREA)
        {
            diff = $($("#column").val()).not($scope.columns).get();
            if(diff.length==0)
            {
                diff = $($scope.columns).not($("#column").val()).get();
            }
        }else
        {
            diff = $("#column").val();
        }
        $scope.add = {
            ID: $scope.edit_reporte.ID_REPORTE,
            NOMBRE: $scope.formData.nombre,
            ID_USUARIO: $scope.id_usuario,
            COMPARTIDO: ($scope.formData.publico==true?1:0),
            ID_AREA: $scope.formData.select_area.ID_AREA,
            COLUMN: diff,
            FLAG:($scope.edit_reporte.ID_AREA != $scope.formData.select_area.ID_AREA)
        };
        if($scope.save)
        {
            $.post(global_apiserver + "/reportes/update/", JSON.stringify($scope.add), function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta.resultado == "ok") {
                    $scope.cargarReportes();
                    notify("Éxito", "Se editó el reporte correctamente", "success");

                    $("#modalInsertarActualizar").modal("hide");
                    $scope.generarEXCEL();
                    $scope.clear_form();
                }
                else {
                    notify("Error", respuesta.mensaje, "error");
                }
            });
        }
        else {
            $scope.generarEXCEL();
        }

    }

// =======================================================================================
// ***** 			          FUNCION UPDATE REPORTE      	                   *****
// =======================================================================================
    $scope.eliminar = function(key){

        $.confirm({
            title: 'Confirmación',
            content: '¿Está seguro que desea eliminar este reporte: '+$scope.reportes[key].NOMBRE+'? ',
            buttons: {
                Aceptar: function () {
                    var add = {
                        ID:$scope.reportes[key].ID_REPORTE
                    };
                    $.post(global_apiserver + "/reportes/delete/", JSON.stringify(add), function (respuesta) {
                        respuesta = JSON.parse(respuesta);
                        if (respuesta.resultado == "ok") {
                            $scope.cargarReportes();
                            notify("Éxito", "Se eliminó el reporte correctamente", "success");
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
// =======================================================================================
// ***** 			          FUNCION GENERAR EXCEL REPORTE                          *****
// =======================================================================================
    $scope.generarEXCEL = function(key){

        if (typeof key !== "undefined") {

            $('#hiddenNombre').val($scope.reportes[key].NOMBRE);
            $('#hiddenArea').val($scope.reportes[key].AREA);
            var array = [];
              $.each($scope.reportes[key].COLUMN,function (i,n) {
                   array.push(n.COLUMNA);
               });
            $scope.columns = array;
            $('#hiddenColumnas').val(getArrayColumnas($scope.columns,key));
        }
        else {

            $('#hiddenNombre').val($scope.formData.nombre);
            $('#hiddenArea').val($scope.formData.select_area.NOMBRE);
            $('#hiddenColumnas').val(getArrayColumnas($("#column").val()));
        }
        console.log($('#hiddenColumnas').val());
        window.open('', 'VentanaReporteXLS');
        $("#formReporte").submit();

        //document.getElementById('formReporte').submit();

    }
   function getArrayColumnas(selected,key)
   {
       var array = [];
       if (typeof key !== "undefined") {
           if($scope.reportes[key].ID_AREA == 1)
           {
               $.each($scope.comercial,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }
           if( $scope.reportes[key].ID_AREA == 2)
           {
               $.each($scope.programacion,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }

           if( $scope.reportes[key].ID_AREA == 3)
           {
               $.each($scope.auditores,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }
       }
       else
       {
           if($scope.formData.select_area.ID_AREA == 1 )
           {
               $.each($scope.comercial,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }
           if($scope.formData.select_area.ID_AREA == 2 )
           {
               $.each($scope.programacion,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }

           if($scope.formData.select_area.ID_AREA == 3 )
           {
               $.each($scope.auditores,function (i,n) {
                   var value = n.value.split("|");
                   $.each(selected,function (j,m) {
                       if (m.indexOf(value[0]) > -1) {
                           array.push(n) ;
                       }
                   });

               });
           }
       }

       return JSON.stringify(array);


   }
// =======================================================================================
// ***** 			          FUNCION LIMPIAR FORM     	                   *****
// =======================================================================================
    $scope.clear_form = function(){
        $scope.add = [];
        $scope.formData.nombre = "";
        $scope.formData.publico = "";
        $scope.formData.select_area = "";
        $scope.edit_reporte = {};
    }



$(document).ready(function () {
        $scope.cargarReportes();



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





