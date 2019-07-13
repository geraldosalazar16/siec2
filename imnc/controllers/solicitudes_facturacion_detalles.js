// =======================================================================================
// ***** Creación del controlador con el nombre "solicitudes_facturacion_detalles_controller". *****
// =======================================================================================
app.controller('solicitudes_facturacion_detalles_controller', ['$scope', '$http', function($scope, $http) {
    $scope.solicitud = [];
    $scope.documentos = [];
    $scope.id_solicitud = getQueryVariable("id");

    // cargar datos de la solicitud
    function loadSolicitud(id_solicitud) {
        $http.get(`${global_apiserver}/facturacion_solicitudes/getByIdDetalles?id=${id_solicitud}`)
            .then(response => {
            if (response.data.resultado === 'error') {
            notify('Error', response.data.mensaje, 'error')
        } else {
            $scope.solicitud = response.data;
            console.log($scope.solicitud);
            $scope.solicitud.DOCUMENTO.RUTA = global_apiserver+'/'+$scope.solicitud.DOCUMENTO.RUTA;


        }
    })
    .catch(error => notify('Error', error.message, 'error'))
    }

    // cargar documentos de la solicitud
    $scope.loadDocumentos = function (id_solicitud) {
        $http.get(`${global_apiserver}/facturacion_solicitudes/getDocumentos?id=${id_solicitud}`)
            .then(response => {
            if (response.data.resultado === 'error') {
            notify('Error', response.data.mensaje, 'error')
        } else {

            $scope.documentos = response.data;
            draw_row_documentos();


        }
    })
    .catch(error => notify('Error', error.message, 'error'))
    }

    function draw_row_documentos()
    {
        $("#expander").empty();
        var html = '';
        var tipo = null;
        var ar = null;
        $.each($scope.documentos,function(index,item) {
            html += '';
            if(tipo != item.TIPO_DOCUMENTO)
            {
                if(tipo!=null)
                {
                    html += '<tbody>';
                    html += '</table>';
                    html += '</div>';
                }
                html += '<h3 class="expander-h3"><i class="fa fa-arrow-circle-right"></i> '+item.TIPO_DOCUMENTO+'</h3>';
                html += '<div class="expander-div">';
                html += '<table class="table">';
                html += ' <thead style="background: #966610;color: white;">';
                html += ' <tr class="headings">'
                html += ' <th class="column-title" width="90%">Nombre del documento</th>';
                html += ' <th class="column-title"  width="10%"></th>';
                html += ' </tr>';
                html += ' </thead>';
                html += '<tbody style="background: transparent">';
            }

                html += '<tr>';
                html += '<td>'+item.NOMBRE_DOCUMENTO+'</td>';
                html += '<td>';
                html += '<span class="btn-group">';
                html += '<button type="button" class="btn btn-primary btn-xs btn-imnc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones ';
                html += '<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>';
                html += '<ul class="dropdown-menu">';
                if(item.NOMBRE_ALMACENAMIENTO)
                {
                    html += '<li class="ver_documento" nombre_documento = "'+item.NOMBRE_ALMACENAMIENTO+'"><a> <span class="labelAcordeon"><i class="fa fa-eye"></i> Ver Documento</span></a> </li>';
                     html += '<li class="download_documento" nombre_documento = "'+item.NOMBRE_DOCUMENTO+'" nombre_almacen = "'+item.NOMBRE_ALMACENAMIENTO+'"><a> <span class="labelAcordeon"><i class="fa fa-download"></i> Descargar Documento</span></a> </li>';
                }


                html += '</ul></span></td></tr>';
                if($scope.documentos.length == index+1)
                {
                    html += '<tbody>';
                    html += '</table>';
                    html += '</div>';
                }

            tipo = item.TIPO_DOCUMENTO;
    });
        $("#expander").append(html);
        initExpander();
        listener_btn_ver_documento();
        listener_btn_download_documento();


    }
    function listener_btn_ver_documento(){
        $( ".ver_documento" ).click(function() {
            var direccion 		= global_apiserver+"/arch_facturacion/"+$(this).attr("nombre_documento");
            var abc =	window.open( direccion);
             abc.document.title=$(this).attr("nombre_documento");
             });
    }
    function listener_btn_download_documento(){
        $( ".download_documento" ).click(function() {
            var direccion = global_apiserver+"/facturacion_solicitudes/download_documento?root="+$(this).attr("nombre_almacen")+"&name="+$(this).attr("nombre_documento");

            var abc =	window.open( direccion);
            abc.document.title=$(this).attr("nombre_documento");
        });
    }

    function initExpander(){
        $("#expander h3").addClass('collapsed');
        $("#expander div").hide();

        $("#expander h3").click(function(){
            $(this).toggleClass('collapsed expanded');
             //$("#expander h3").not($(this)).removeClass('expanded').addClass('collapsed');
            $(this).next().slideToggle()
                //.siblings("div:visible").slideUp();
        });
    }

    $scope.openModalContacto = function () {
        $("#modalContacto").modal("show");
    }


    $(document).ready(function () {
        loadSolicitud($scope.id_solicitud);


    });
}])
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
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
