// =======================================================================================
// ***** Creación del controlador con el nombre "solicitudes_facturacion_detalles_controller". *****
// =======================================================================================
app.controller('indicadores_comercial_plan_real_controller', ['$scope', '$http', function($scope, $http) {
    $scope.formData = {};
    $scope.prospectos = [];
    $scope.periodicidad = [];
    $scope.objetivos = [];
    $scope.currentYear = null;
    $scope.meses = [{"nombre" : "Enero"},{"nombre" : "Febrero"},{"nombre" : "Marzo"},{"nombre" : "Abril"},{"nombre" : "Mayo"},{"nombre" : "Junio"},{"nombre" : "Julio"},{"nombre" : "Agosto"},{"nombre" : "Septiembre"},{"nombre" : "Octubre"},{"nombre" : "Noviembre"},{"nombre" : "Diciembre"}];
    $scope.mes = '';
    $scope.mes_acumulado = '';
    $scope.flag = '';
	$scope.mesActual= moment().format('M'); 

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR		                         *****
// =======================================================================================
    $scope.openModalBuscar = function(year){
        $scope.currentYear = year;
         $scope.clear_form();
         $scope.loadPeriodicidad();
        $("#modalbuscar").modal("show");

    }
// =======================================================================================
// ***** 			          FUNCION LIMPIAR FORM     	                   *****
// =======================================================================================
    $scope.clear_form = function(){
        $scope.formData = {};
    }

// ===========================================================================
// ***** 	             FUNCION PARA CAMBIA PERIODICIDAD			 *****
// ===========================================================================
    $scope.loadPeriodicidad = function() {
        $.post(  global_apiserver + "/objetivos/getAllPeriodicidad/", function( response ) {
            $scope.periodicidad = JSON.parse(response);
            $scope.$apply();

        });
    }
// ===========================================================================
// ***** 	             FUNCION PARA OBTENER OBJETIVOS			 *****
// ===========================================================================
    $scope.loadObjetivos = function(periodicidad,valor) {
        $.post( global_apiserver + "/objetivos/getByVentaPlanReal/?periodicidad="+periodicidad+"&valor="+valor, function( response ) {
            $scope.objetivos = JSON.parse(response);
            $scope.$apply();

        });
    }
// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    $scope.cambio_periodicidad = function(valor) {
        $scope.formData.valor_periodicidad = "";
        if(valor == 1)
        {
            $scope.formData.valor_periodicidad = parseInt($scope.currentYear);

        }
    }
// =======================================================================================
// ***** 		                 	FUNCION BUSCAR FILTRADOS                        *****
// =======================================================================================
    $scope.submitBuscarFiltrados = function() {
        $scope.loadObjetivos($scope.formData.periodicidad,$scope.formData.valor_periodicidad);
        let url = `${global_apiserver}/indicadores/getPlanReal/?periodicidad=${$scope.formData.periodicidad}&valor=${$scope.formData.valor_periodicidad}`;
        $http.get(url)
            .then(function( response ){
                $scope.prospectos = response.data;
                $("#modalbuscar").modal("hide");
                loadResult($scope.formData.periodicidad);
            });
    }
// =======================================================================================
// ***** 		                 	FUNCION EXPORTAR EXCEL                        *****
// =======================================================================================
    $scope.exportExcel = function() {

        var url = "./generar/xls/indicadores/ventas_plan_real/index.php?prospectos="+JSON.stringify($scope.prospectos)+"&mes="+$scope.mes+"&flag="+$scope.flag+"&objetivos="+JSON.stringify($scope.objetivos);
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
    function loadResult(flag)
    {
        var mes = $scope.formData.valor_periodicidad;
        var acumulado = '';
        if(flag==1)
        {
            acumulado = 'DICIEMBRE';
        }
        if(flag==2){
            mes = mes.toUpperCase();
            acumulado = mes;
        }
        $scope.mes = mes;
        $scope.flag = flag;


        $("#expander").empty();
        var html = '';
        var tipo = null;
        html += '';
        var totalE = 0;
        var totalG = 0;
        var totalAE = 0;
        var totalAG = 0;
        var totalAC = 0;
        html += '<table class="table table-border text-dark">';
        if(flag==1) {
            html += '<thead style="font-weight: 700"><td bgcolor="silver" width="20%"></td><td bgcolor="#f0e68c" width="80%" style="text-align: center;" colspan="5"> ENERO - ' + acumulado + '</td></thead>';
            html += ' <thead style="font-weight: 700"><td bgcolor="silver" ></td><td bgcolor="#f0e68c">PROPUESTAS EMITIDAS</td><td bgcolor="#f0e68c">OBJETIVO EMITIDAS</td><td bgcolor="#f0e68c">PROPUESTAS GANADAS</td><td bgcolor="#f0e68c">OBJETIVO GANADAS</td><td bgcolor="#f0e68c">PROPUESTAS ACTIVAS</td></thead>';
        }
        if(flag==2) {
            html += '<thead style="font-weight: 700"><td bgcolor="silver" width="20%"></td><td bgcolor="#e6e6fa" width="40%" style="text-align: center;" colspan="4">'+mes+'</td></thead>';
            html += ' <thead style="font-weight: 700"><td bgcolor="silver" ></td><td bgcolor="#e6e6fa">PROPUESTAS EMITIDAS</td><td bgcolor="#e6e6fa">OBJETIVO EMITIDAS</td><td bgcolor="#e6e6fa">PROPUESTAS GANADAS</td><td bgcolor="#e6e6fa">OBJETIVO GANADAS</td></thead>';
        }
        $.each($scope.prospectos,function(index,item) {
               if(tipo!=item.TIPO)
               {

                   if(flag==1) {
                       html += '  <tr><td bgcolor="silver" ><strong>' + item.TIPO + '</strong></td>\n' +
                           '                    <td style="text-align: right;">' + number_format(parseFloat(item.TOTALE), 2, '.', ',') + '</td>\n' +
                           '                    <td style="text-align: right;"></td>\n' +
                           '                    <td style="text-align: right;">' + number_format(parseFloat(item.TOTALG), 2, '.', ',') + '</td>\n' +
                           '                    <td style="text-align: right;"></td>\n' +
                           '                    <td style="text-align: right;">' + number_format(parseFloat(item.ACTIVAS), 2, '.', ',') + '</td>\n' +
                           '                </tr>';
                   }
                   if(flag==2)
                   {
                       html += '  <tr><td bgcolor="silver" ><strong>' + item.TIPO + '</strong></td>\n' +
                           '                    <td  style="text-align: right;">' + number_format(parseFloat(item.TOTALE), 2, '.', ',') + '</td>\n' +
                           '                    <td  style="text-align: right;"></td>\n' +
                           '                    <td  style="text-align: right;">' + number_format(parseFloat(item.TOTALG), 2, '.', ',') + '</td>\n' +
                           '                    <td  style="text-align: right;"></td>\n' +
                           '                </tr>';
                   }

                   tipo = item.TIPO;
               }

            totalE += parseFloat(item.TOTALE);
            totalG += parseFloat(item.TOTALG);
            totalAC += parseFloat(item.ACTIVAS);
        });
        if (flag == 1) {
            html += '  <tr style="font-weight: 700"><td bgcolor="silver" >TOTAL</td>\n' +
                '                   <td bgcolor="#f0e68c" style="text-align: right;">' + number_format(totalE, 2, '.', ',') + '</td>\n' +
                '                   <td bgcolor="#f0e68c" style="text-align: right;">' + number_format(parseFloat($scope.objetivos.OBJETIVOS.E), 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#f0e68c" style="text-align: right;">' + number_format(totalG, 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#f0e68c" style="text-align: right;">' + number_format(parseFloat($scope.objetivos.OBJETIVOS.G), 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#f0e68c" style="text-align: right;">' + number_format(totalAC, 2, '.', ',') + '</td>\n' +
                '                </tr>';
        }
        if (flag == 2) {
            html += '  <tr style="font-weight: 700"><td bgcolor="silver" >TOTAL</td>\n' +
                '                    <td bgcolor="#e6e6fa" style="text-align: right;">' + number_format(totalE, 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#e6e6fa" style="text-align: right;">' + number_format(parseFloat($scope.objetivos.OBJETIVOS.E), 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#e6e6fa" style="text-align: right;">' + number_format(totalG, 2, '.', ',') + '</td>\n' +
                '                    <td bgcolor="#e6e6fa" style="text-align: right;">' + number_format(parseFloat($scope.objetivos.OBJETIVOS.G), 2, '.', ',') + '</td>\n' +
                '                </tr>';
        }
        html += '</table>';




        if($scope.prospectos.length == 0)
        {
            html += '<h3>No se encontraron registros</h3>';
        }


        $("#expander").append(html);


    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            toFixedFix = function (n, prec) {
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                var k = Math.pow(10, prec);
                return Math.round(n * k) / k;
            },
            s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    $(document).ready(function () {
		$scope.formData.periodicidad = 2;
		$scope.formData.valor_periodicidad = $scope.meses[$scope.mesActual-1]["nombre"];
		$scope.submitBuscarFiltrados();
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
