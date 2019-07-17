// =======================================================================================
// ***** Creación del controlador con el nombre "estado_actual_facturacion_controller". *****
// =======================================================================================
app.controller('estado_actual_facturacion_controller', ['$scope', '$http', function($scope, $http) {
    $scope.prospecto_seguimiento = [];
    $scope.formData = {};
    $scope.prospectos = [];
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.openModalBuscar = function(){
         $scope.clear_form();
         onCalendario();
         $scope.showDualListBox();
        //$('.modal-dialog').attr('class','modal-dialog modal-lg');
        $("#modalbuscar").modal("show");

    }
// =======================================================================================
// ***** 			          FUNCION LIMPIAR FORM     	                   *****
// =======================================================================================
    $scope.clear_form = function(){
        $scope.formData = {};
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
// ==================================================================
// ***** 	          FUNCION PARA CARGAR SERVICIOS 		*****
// ==================================================================
    function cargarServicios() {
        $http.get(global_apiserver + "/servicios/getAll/")
            .then(function(response) {
                $scope.Servicios = response.data;
            });
    }
// ==================================================================
// ***** 	          FUNCION PARA CARGAR TIPOS SERVICIOS 		*****
// ==================================================================
    function cargartipoServicio(id_servicio) {
        $http.get(global_apiserver + "/tipos_servicio/getByService/?id=" + id_servicio)
            .then(function(response) {
                $scope.tipoServicios = response.data;
            });
    }
// ==================================================================
// ***** 				CHANGE  SERVICIOS					*****
// ==================================================================
    $scope.changeServicio = function(id_servicio) {

        cargartipoServicio(id_servicio);
    }


// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS LISTBOX    	 *****
// ===================================================================
    $scope.cargarDualListbox= function(){

        var list = $('#column').bootstrapDualListbox({
            nonSelectedListLabel: 'Estados sin Seleccionar',
            selectedListLabel: 'Estados Seleccionados',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
        });
        return list;


    }
// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.showDualListBox = function(){
        var option = '';
        var list = $scope.cargarDualListbox();

        $.each($scope.prospecto_seguimiento,function (i,n) {
            option +='<option value="'+n.ID+'">'+n.DESCRIPCION+'</option>';
        })

        list
            .find('option')
            .remove()
            .end()
            .append(option)
        ;
        list.bootstrapDualListbox('refresh');
    }
// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    function onCalendario() {

        var dateInicial = $('#fecha_inicio').datepicker({
            dateFormat: "dd/mm/yy",
            // minDate: "+0D",
            language: "es",
            onSelect: function (dateText, ins) {
                $scope.formData.fecha_inicio = dateText;
                dateFinal.datepicker("option", "minDate", dateText)
                $scope.formData.fecha_fin = dateText;
            }
        }).css("display", "inline-block");

        var dateFinal =$('#fecha_fin').datepicker({
            dateFormat: "dd/mm/yy",
            language: "es",
            // minDate: "+0D",
            onSelect: function (dateText, ins) {
                $scope.formData.fecha_fin = dateText;
            }
        }).css("display", "inline-block");

    }

// =======================================================================================
// ***** 		                 	FUNCION BUSCAR TODOS                        *****
// =======================================================================================
    $scope.submitBuscarTodos = function() {
        let url = `${global_apiserver}/indicadores/getVentasAll/`;
        $http.get(url)
            .then(function( response ){
                $scope.prospectos = response.data;
                loadResult();
            });
    }
// =======================================================================================
// ***** 		                 	FUNCION BUSCAR FILTRADOS                        *****
// =======================================================================================
    $scope.submitBuscarFiltrados = function() {
        let url = `${global_apiserver}/indicadores/getVentasFiltradas/?status=${JSON.stringify($("#column").val())}&fechas=${JSON.stringify([$scope.formData.fecha_inicio,$scope.formData.fecha_fin])}&servicio=${$scope.formData.servicio?$scope.formData.servicio:''}&tipo=${$scope.formData.tipoServicio?$scope.formData.tipoServicio:''}`;
        $http.get(url)
            .then(function( response ){
                $scope.prospectos = response.data;
                criteriaSearch();
                loadResult();
                $("#modalbuscar").modal("hide");
            });
    }
// =======================================================================================
// ***** 		                 	FUNCION EXPORTAR EXCEL                        *****
// =======================================================================================
    $scope.exportExcel = function() {
        var url = "./generar/xls/indicadores/ventas/index.php?prospectos="+JSON.stringify($scope.prospectos);
        window.open(url,'_blank');
    }

// =======================================================================================
// ***** 			FUNCION MOSTRAR LOS CRIRERIOS DE LA BUSQUEDA                    *****
// =======================================================================================
    function criteriaSearch()
    {
        var html = '';
        $("#search").html('');
        if($("#column").val()) {

            html += '<small>Estados: </small>';
            $.each($("#column").val(), function (index, item) {
                var status = $scope.prospecto_seguimiento.find(s => {
                    return s.ID === item;
                });
                html += '<strong><small>'+status.DESCRIPCION+'</small></strong>,';
            });
            html = html.substring(html.length, -2);
            html += '<br>';
        }

        if($scope.formData.fecha_inicio && $scope.formData.fecha_fin)
        {
            html += '<small>Fechas:<strong>'+$scope.formData.fecha_inicio+'-'+$scope.formData.fecha_fin+'</strong></small><br>';
        }
        if($scope.formData.servicio)
        {
            html += '<small>Servicio:<strong>'+$("#servicio :selected").text()+'</strong></small><br>';
        }
        if($scope.formData.tipoServicio)
        {
            html += '<small>Tipo Servicio:<strong>'+$("#tipoServicio :selected").text()+'</strong></small><br>';

        }
        if(html)
        {
            html='<h4> Criterios de busqueda</h4>'+html;
        }
        $("#search").append(html);
        $('#search').attr('class','text-muted');
    }
// =======================================================================================
// ***** 			FUNCION MOSTRAR LOS RESULTADOS DE LA BUSQUEDA                    *****
// =======================================================================================
    function loadResult()
    {
        $("#expander").empty();
        var html = '';
        var status = null;
        $.each($scope.prospectos,function(index,item) {
            html += '';
            if(status != item.ID_STATUS)
            {
                if(status!=null)
                {
                    html += '<tbody>';
                    html += '</table>';
                    html += '</div>';
                }
                html += '<h3 class="expander-h3"><i class="fa fa-arrow-circle-right"></i> '+item.ESTATUS_SEGUIMIENTO+'<span class="pull-right" title="TOTAL">'+item.TOTAL+'</span></h3>';
                html += '<div class="expander-div">';
                html += '<table class="table">';
                html += ' <thead style="background: #966610;color: white;">';
                html += ' <tr class="headings">'
                html += ' <th class="column-title" width="80%">Prospecto</th>';
                html += ' <th class="column-title"  width="20%" style="text-align: center;">Monto</th>';
                html += ' </tr>';
                html += ' </thead>';
                html += '<tbody style="background: transparent">';
            }

                html += '<tr>';
                html += '<td>'+item.NOMBRE+'</td>';
                html += '<td style="text-align: right;">'+item.MONTO+'</td>';
                html += '</tr>';
                if($scope.prospectos.length == index+1)
                {
                    html += '</tbody>';
                    html += '</table>';
                    html += '</div>';
                }

            status = item.ID_STATUS;
    });
        if($scope.prospectos.length == 0)
        {
            html += '<h3>No se encontraron registros</h3>';
        }

        $("#expander").append(html);
        initExpander();


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

    $(document).ready(function () {
        $scope.cargarProspectoSeguimiento();
        cargarServicios();

    });
}])
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
