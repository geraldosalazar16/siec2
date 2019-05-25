app.controller('ec_tipos_servicio_controller',['$scope','$http' ,function($scope,$http){
	
	$scope.titulo	= "Cargando....";
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
	$scope.formData = {};
	$scope.formDataSector = {};
	$scope.formDataSitiosEC = {};
	$scope.formDataGastosAud={};
	$scope.formDataViaticosAud={};
	$scope.formDataSitio = {};
	$scope.formDataAuditoria = {};
	$scope.formDataAuditoriaEC = {};
	$scope.formDataGrupoAuditor	=	{};
	$scope.formDataGrupoAuditorFechaNorma	=	{};
	$scope.formDataGeneraNotificacionPDF = {};
    $scope.formDataParticipante = {};
    $scope.formDataConfiguracion = {};
	$scope.resp={};
	$scope.resp1={};
	$scope.DatosSitiosEC={};
	$scope.CICLO1=[];// $scope.NombreCiclo="";
	$scope.formDataDictaminacion={};
	$scope.prueba	= "PAGINA EN DESARROLLO";
	$scope.id_servicio_cliente_etapa = getQueryVariable("id_serv_cli_et");
	$scope.PrincipalSectores	=	{0:{ID:"S",NOMBRE:"Si"},1:{ID:"N",NOMBRE:"No"}};
	$scope.ocultar	=	false;
	$scope.FechaPrueba = ["2018-10-5","2018-9-3"];
	$scope.txtInsertarFechas = new Array();
	$scope.txtFechasAuditoria={};
	$scope.txtInsertarFechasGrupo = {};
    $scope.id_instructor = "";
    $scope.flag = true;
	$scope.notas = [];
	$scope.countnotas = 0;

	$scope.colapseActual; // Para guardar si hay un colapse activo
	$scope.estatusAuditoria; // Para guardar el estatus de las auditorías
	$scope.estatusGastosAuditoria; // Para guardar el estatus de las auditorías
	
// =======================================================================================
// ***** 			FUNCION PARA EL BOTON AGREGAR INFORMACION AUDITORIA				 *****
// =======================================================================================	
$scope.agregar_info_auditoria	=	function()	{
	clear_modal_agregar_informacion();
	cargarOpcionesSelectMetaDatos();
	if($scope.accion == 'insertar'){
		$scope.modal_titulo = "Agregar informacion";
	}
	if($scope.accion == 'editar'){
		$scope.modal_titulo = "Editar informacion";
		llenar_modal_editar_informacion();
	}
	$("#modalAgregarInformacion").modal("show");
}
// =======================================================================================
// ***** 		FUNCION PARA LLENAR EL MODAL EDITAR INFORMACION AUDITORIA			 *****
// =======================================================================================
function llenar_modal_editar_informacion(){
		cargarValoresMetaDatosServicio($scope.id_servicio_cliente_etapa);
		for(var key in $scope.MetaDatos){
			var datos_servicio	=	$scope.ValoresMetaDatos.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == $scope.id_servicio_cliente_etapa && element.ID_META_SCE == $scope.MetaDatos[key].ID)
			});
			if(typeof datos_servicio != 'undefined'){
				if(datos_servicio.ID_META_SCE == 11){
					var temp = JSON.parse(datos_servicio.VALOR);
					var tamano=0;
					$.each(temp, function( i, datos ) {
			
						$scope.formData.Turno[i]=datos.T;
						$scope.formData.Pers_Turno[i]=datos.PT;
						tamano+=1;
		 
					});
					/*
					for(var i=0;i<temp.length-1;i++){
						$scope.formData.Turno[i]=temp[i].T;
						$scope.formData.Pers_Turno[i]=temp[i].PT;
					}
					//$scope.formData.Turno = temp['T'];
					//$scope.formData.Pers_Turno = temp['PT'];*/
					datos_servicio.VALOR	=	tamano;
					$scope.MD11(datos_servicio.VALOR);
				}
				if(datos_servicio.ID_META_SCE == 12){
					var temp1 = JSON.parse(datos_servicio.VALOR);
					var tamano1 = 0;
					if(temp1.TS){
						$scope.formData.TipoSolucion = temp1.ETS;
						datos_servicio.VALOR	=	temp1.TS;
					}
					
				}
				if(datos_servicio.ID_META_SCE == 35){
					var temp3 = JSON.parse(datos_servicio.VALOR);
					if(temp3.ND>0){
						$scope.formData.TipoDiscapacidad = temp3.TD;
						
					}
					datos_servicio.VALOR = temp3.ND;	
				}
				if(datos_servicio.ID_META_SCE == 38){
					var temp2 = JSON.parse(datos_servicio.VALOR);
					$scope.formData.Ano = 	temp2.A;
					$scope.formData.Mes	=	temp2.M;
				}
				$scope.formData.input[$scope.MetaDatos[key].ID]	= datos_servicio.VALOR;
			}
			else{
				$scope.formData.input[$scope.MetaDatos[key].ID]	= "";
			}
		}
		
}
// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal			 *****
// ===========================================================================
function clear_modal_agregar_informacion(){
	
	$scope.formData.input	=	{};
	$scope.formData.Turno	=	{};
	$scope.formData.Pers_Turno	=	{};
	$scope.formData.TipoSolucion = "";
	$scope.formData.TipoDiscapacidad = "";
	$scope.formData.Ano = "";
	$scope.formData.Mes = "";
		for(var key in $scope.MetaDatos){
				$scope.formData.input[$scope.MetaDatos[key].ID]	= "";
		}
}
	
// ===========================================================================
// ***** 			FUNCION PARA EL BOTON GUARDAR DEL MODAL				 *****
// ===========================================================================
	$scope.submitForm = function (formData) {
			var input = ""; 
			var indice	=	"";
/*			for(var key in $scope.formData.input){
				indice	+=	key+";";
				if(key == 11){
				var aaa = {};
						for(var i=0;i<$scope.formData.input[key];i++){
							aaa[i]	=	{TURNO : $scope.formData.Turno[i],PERS_TURNO: $scope.formData.Pers_Turno[i]};
						}
						aaa[$scope.formData.input[key]]=$scope.formData.input[key];
						$scope.formData.input[key]= JSON.stringify(aaa);
				}
				input	+=	$scope.formData.input[key]+";";
			}
			*/
			/* ************************************************************** */
			if($scope.formData.input[11]){
				var aaa = {};
						for(var i=0;i<$scope.formData.input[11];i++){
							aaa[i]	=	{T:$scope.formData.Turno[i],PT:$scope.formData.Pers_Turno[i]};
						}
						$scope.formData.input[11]= JSON.stringify(aaa);
				}
			if($scope.formData.input[12] == 8){
				var bbb = {}; 
				bbb	=	{TS: $scope.formData.input[12], ETS:$scope.formData.TipoSolucion}
				$scope.formData.input[12] = JSON.stringify(bbb);
			
			}	
			if($scope.formData.input[35]){
				var ddd = {};
				ddd	=	{ND:$scope.formData.input[35] , TD:$scope.formData.TipoDiscapacidad }
				$scope.formData.input[35] = JSON.stringify(ddd);
			}
			if($scope.formData.Ano){
				var ccc = {};
				ccc = {A:$scope.formData.Ano , M:$scope.formData.Mes};
				$scope.formData.input[38] = JSON.stringify(ccc);
			}
			/* ************************************************************** */
			input = JSON.stringify($scope.formData.input);
			var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				//KEY	:	indice,
				INPUT	:	input,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
		if($scope.accion == 'insertar'){
			
			$http.post(global_apiserver + "/i_tipos_servicios/insert/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
					
					cargarValoresMetaDatosServicio($scope.id_servicio_cliente_etapa);
					
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		 }
		if($scope.accion == 'editar'){	
			$http.post(global_apiserver + "/i_tipos_servicios/update/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
					
					cargarValoresMetaDatosServicio($scope.id_servicio_cliente_etapa);
					
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		}
		$("#modalAgregarInformacion").modal("hide");
		
	};
// =======================================================================================
// ***** 			FUNCION PARA EL BOTON AGREGAR INFORMACION AUDITORIA				 *****
// =======================================================================================	
	function cargarMetaDatos(id_tipos_servicio,norma){
		$http.get(  global_apiserver + "/i_meta_sce/getByIdTipoServicio/?id="+id_tipos_servicio+"&norma="+norma+"&id_sce="+$scope.id_servicio_cliente_etapa)
		.then(function( response ){
			$scope.MetaDatos = response.data;
			
		});
		
	}
// ==============================================================================
// ***** 	Funcion para obtener datos servicio contratado a partir del id	*****
// ==============================================================================
	function DatosServicioContratado(id_servicio){
		$http.get(  global_apiserver + "/servicio_cliente_etapa/getById/?id="+id_servicio)
		.then(function( response ){
			
			$scope.DatosServicio = response.data;
			$scope.DatosServicio.CICLO = ObtenerCicloDeReferencia($scope.DatosServicio.REFERENCIA);
			$scope.titulo = $scope.DatosServicio.NOMBRE_SERVICIO;

            if($scope.DatosServicio.ID_SERVICIO == 3)
            $scope.cargarDatosConfiguracion($scope.DatosServicio.ID,$scope.DatosServicio.ID_CURSO);

			if($scope.DatosServicio.ID_TIPO_SERVICIO == 17){
				cargarMetaDatos($scope.DatosServicio.ID_TIPO_SERVICIO,$scope.DatosServicio.ID_NORMA);
				
			}
			else{
				cargarMetaDatos($scope.DatosServicio.ID_TIPO_SERVICIO,0);
			}
			cargarMetaDatosSitios($scope.DatosServicio.ID_TIPO_SERVICIO);

			// Datos para select para filtrar ciclos
			$scope.CICLO1.push({VAL:0,NOMBRE:"Todos"});
			for(var i=0;i<$scope.DatosServicio.CICLO;i++){
				$scope.CICLO1.push({VAL:i+1,NOMBRE:"C"+(i+1)});
				
			}
			$scope.DatosServicio.NombreCiclo	= 0;
			$scope.funcionDictaminadores($scope.DatosServicio.ID_TIPO_SERVICIO);
		});
	
	}	

//	funcion para cambio ciclo
$scope.cambioCiclo	=	function(){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
	var cc = $scope.DatosServicio.NombreCiclo;
	if($scope.DatosServicio.ID_SERVICIO == 1){
		if(cc == 0){
			cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
		}
		else{
			cargarDatosAuditoriasSG_Ciclo($scope.id_servicio_cliente_etapa,cc);
		
		}
	}
	if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
		if(cc == 0){
			cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
		}
		else{
			cargarDatosAuditoriasEC_Ciclo($scope.id_servicio_cliente_etapa,cc);
		
		}
	}
}	
// ==============================================================================
// ***** 	Funcion para obtener metadatos de este servicio 				*****
// ==============================================================================
	function cargarValoresMetaDatosServicio(id_servicio){
		$http.get(  global_apiserver + "/i_tipos_servicios/getByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.ValoresMetaDatos = response.data;
			if($scope.ValoresMetaDatos.length == 0){
				$scope.titulo_boton_info_auditoria	=	"Agregar informacion auditoria";
				$scope.accion = 'insertar';
			}	
			else{		
				$scope.titulo_boton_info_auditoria	=	"Editar informacion auditoria";
				$scope.accion = 'editar';
			}	
		});
	}
// =============================================================================
// ***** Funcion para obtener las opciones de los metadatos que son select *****
// =============================================================================
function cargarOpcionesSelectMetaDatos(){
		$scope.Ys	= {};
		for(var key in $scope.MetaDatos){
			if( $scope.MetaDatos[key].TIPO == 2 || $scope.MetaDatos[key].ID == 11){
					$http.get(  global_apiserver + "/i_opciones_select_metadatos/getByIdMeta/?id="+$scope.MetaDatos[key].ID)
						.then(function( response ){
							$scope.Ys[response.data[0].ID_META] = response.data;
			
					});
					//$scope.Ys[$scope.MetaDatos[key].ID] = datos_select;	
			}
			else{
				
			}
		}

/*		*/
		
	}
	$scope.MD11 = function(dat){
		$scope.DatosTurnos	=	{};
		for(var i=0;i<dat;i++){
			$scope.DatosTurnos[i]	=	{ID: i};
		}
	}
// ======================================================================
// *****				 	Mostrar valor de un select 				*****
// ======================================================================
$scope.mostrarvalorselect	= function(dat,ind){
		$http.get(  global_apiserver + "/i_opciones_select_metadatos/getById/?id="+dat)
						.then(function( response ){
							$scope.resp[ind]	= response.data.OPCION;
			
					});
			
}	
// ======================================================================
// *****		Funcion acomodar los datos de Turnos				*****
// ======================================================================
$scope.FuncionTurnos	= function(dat){
		$scope.respTurn =JSON.parse(dat);
		$scope.num_turnos=0;
		$.each($scope.respTurn, function( i, datos ) {
			
						$scope.num_turnos+=1;
		 
					});
		
}	
// ======================================================================
// *****		Funcion acomodar los datos de Tipo Solucion			*****
// ======================================================================
$scope.FuncionTipoSolucion	=	function(dat,ind){
		var ccc	=	JSON.parse(dat);
		if(ccc.TS){
				$scope.respTS	=	ccc.TS;
				$scope.mostrarvalorselect(ccc.TS,ind);
				$scope.respETS	=	ccc.ETS;
		}
		else{
			$scope.respTS	=	ccc;
			$scope.mostrarvalorselect(ccc,ind);
		}
		
}
// ======================================================================
// *****		Funcion acomodar los datos de Discapacidad			*****
// ======================================================================
$scope.FuncionDiscapacidad	=	function(dat){
	var temp = JSON.parse(dat);
	$scope.NDisc	=	temp.ND;
	$scope.TDisc	=	temp.TD;

}
// ======================================================================
// *****		Funcion acomodar los datos de Ano y Mes			*****
// ======================================================================
$scope.FuncionAnoMes	=	function(dat){
	var temp = JSON.parse(dat);
	$scope.Ano	=	temp.A;
	$scope.Mes	=	temp.M;

}	
// ==============================================================================
// ***** 	Funciones para trabajar con los sectores de SGC 				*****
// ==============================================================================	
// =======================================================================
// ***** 			FUNCION PARA EL BOTON AGREGAR SECTOR			 *****
// =======================================================================
$scope.agregar_editar_sector	=	function(accion_sector,id_servicio_cliente_etapa,id_sector)	{
	
	clear_modal_sector();
	cargarSectoresTipoServicio($scope.DatosServicio.ID_TIPO_SERVICIO);
	$scope.accion_sector	=	accion_sector;
	if($scope.accion_sector == 'insertar'){
		$scope.modal_titulo_sector = "INSERTAR SECTOR DE SERVICIO";
	}
	if($scope.accion_sector == 'editar'){
		$scope.modal_titulo_sector = "EDITAR SECTOR DE SERVICIO";
		llenar_modal_sector(id_servicio_cliente_etapa,id_sector);
	}
	$("#modalInsertarActualizarTServSector").modal("show");
}
$scope.eliminar_sector = function(id_servicio_cliente_etapa,id_sector){
	$.confirm({
        title: 'Eliminar registro',
        content: 'Estas a punto de eliminar un sector, la operaci?n es irreversible, estas seguro?',
        buttons: {
            cancel: {
                text: 'Cancelar'
            },
            irAuditoria: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
					var datos = {
						id_servicio_cliente_etapa: id_servicio_cliente_etapa,
						id_sector: id_sector
					}
                    $http.post(global_apiserver + "/i_sg_sectores/delete/",datos).
					then(function(response){
						if(response.data.resultado == 'ok'){
							notify('&Eacutexito','El sector ha sido eliminado','success');	
							cargarSectoresServicio($scope.id_servicio_cliente_etapa);					
						}
						else{
							notify('Error','No se pudo eliminar el registro','error');
						}
						
					});
                }
            }
        }
    });
}
// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal sector		 *****
// ===========================================================================
function clear_modal_sector(){
	
	$scope.formDataSector.Id_Sector	=	"";
	$scope.formDataSector.Principal	=	"S";
	
}
// ===========================================================================
// ***** 		Funcion para llenar las variables del modal sector		 *****
// ===========================================================================
function llenar_modal_sector(id_servicio_cliente_etapa,id_sector){
	
	var datos_servicio	=	$scope.SectoresServicio.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.ID_SECTOR  == id_sector )
			});
	$scope.formDataSector.Id_Sector	=	datos_servicio.ID_SECTOR;
	$scope.formDataSector.Principal	=	datos_servicio.PRINCIPAL;
	
}
// ===========================================================================
// ***** 		FUNCION PARA EL BOTON GUARDAR DEL MODAL	SECTOR			 *****
// ===========================================================================
	$scope.submitFormSector = function (formDataSector) {
						
			
		if($scope.accion_sector == 'insertar'){
			var datos	=	{
				ID_SECTOR	:	$scope.formDataSector.Id_Sector,
				ID_SERVICIO_CLIENTE_ETAPA	:	$scope.id_servicio_cliente_etapa,
				PRINCIPAL	:	$scope.formDataSector.Principal,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
			$http.post(global_apiserver + "/i_sg_sectores/insert/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   cargarSectoresServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		 }
		if($scope.accion_sector == 'editar'){	
			var datos	=	{
				
				ID_SECTOR	:	$scope.formDataSector.Id_Sector,
				ID_SERVICIO_CLIENTE_ETAPA	:	$scope.id_servicio_cliente_etapa,
				PRINCIPAL	:	$scope.formDataSector.Principal,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
			$http.post(global_apiserver + "/i_sg_sectores/update/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   cargarSectoresServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		}
		$("#modalInsertarActualizarTServSector").modal("hide");
		
	};
// =======================================================================================
// ***** 			FUNCION PARA CARGAR DATOS DE SECTORES DEL SERVICIO				 *****
// =======================================================================================	
	function cargarSectoresServicio(id_servicio){
		$http.get(  global_apiserver + "/i_sg_sectores/getByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.SectoresServicio = response.data;
			
		});
		
	}
// =======================================================================================
// ***** 			FUNCION PARA CARGAR DATOS DE SECTORES DEL TIPO SERVICIO			 *****
// =======================================================================================	
	function cargarSectoresTipoServicio(id_tipo_servicio){
				
			$http.get(  global_apiserver + "/sectores/getByIdTipoServicio/?id_tipo_servicio="+id_tipo_servicio)
			.then(function( response ){
				$scope.SectoresTipoServicio = response.data;
			
			});
			
	}	

// ==============================================================================
// ***** 	Funciones para trabajar con los sitios de SGC 				*****
// ==============================================================================	
// =======================================================================
// ***** 			FUNCION PARA EL BOTON AGREGAR SITIO			 *****
// =======================================================================	
$scope.agregar_editar_sitio	=	function(accion_sitio,id)	{
	//Id es el id del domicilio (estos nombres de variable...)
	clear_modal_sitio();
	clear_modal_sitios_ec();
	//cargarSectoresTipoServicio($scope.DatosServicio.ID_TIPO_SERVICIO);
	cargarOpcionesSelectMetaDatosSitios();
	cargarActividad();
	cargarClientesDomicilio($scope.DatosServicio.ID_CLIENTE);
	$scope.accion_sitio	=	accion_sitio;
	if($scope.accion_sitio == 'insertar'){
		$scope.modal_titulo_sitio = "INSERTAR SITIO";
	}
	if($scope.accion_sitio == 'editar'){
		$scope.modal_titulo_sitio = "EDITAR SITIO";
		if($scope.DatosServicio.ID_SERVICIO == 1){
			llenar_modal_sitiosSG($scope.id_servicio_cliente_etapa,id);
		}
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
			llenar_modal_sitiosEC($scope.id_servicio_cliente_etapa,id);
		}
		//llenar_modal_sitio(id);
	}
	if($scope.DatosServicio.ID_SERVICIO == 1){
		
		$("#modalInsertarActualizarSitios").modal("show");
	}
	if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
		
		cargarMetaDatosSitios($scope.DatosServicio.ID_TIPO_SERVICIO);
		$("#modalInsertarActualizarSitiosEC").modal("show");
	}	
}
$scope.eliminar_sitio = function(id_cliente_domicilio){
	$.confirm({
        title: 'Eliminar registro',
        content: 'Estas a punto de eliminar un sitio del servicio, estas seguro?',
        buttons: {
            cancel: {
                text: 'Cancelar'
            },
            irAuditoria: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
					var datos = {
						id_servicio_cliente_etapa: $scope.id_servicio_cliente_etapa,
						id_cliente_domicilio: id_cliente_domicilio
					}
					var url="";
					if($scope.DatosServicio.ID_SERVICIO == 1){
						url = global_apiserver + "/i_sg_sitios/delete/";
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
						url = global_apiserver + "/i_ec_sitios/delete/";
					}
					
					
                    $http.post(url,datos).
					then(function(response){
						if(response.data.resultado == 'ok'){
							notify('&Eacutexito','El sitio ha sido eliminado','success');	
							cargarSitiosECServicio($scope.id_servicio_cliente_etapa);
							cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
							cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);					
						}
						else{
							notify('Error','No se pudo eliminar el registro','error');
						}
						
					});
                }
            }
        }
    });
}
// ===========================================================================
// *****	Funcion para limpiar las variables del modal sitios ec		 *****
// ===========================================================================
function clear_modal_sitios_ec(){
	
	$scope.formDataSitiosEC.input	=	{};
	$scope.formDataSitiosEC.cmbClaveClienteDomSitio	="";
	
}
// ===========================================================================
// ***** 	Funcion para llenar las variables del modal sitiosEC		 *****
// ===========================================================================
function llenar_modal_sitiosEC(id_servicio_cliente_etapa,id_cliente_domicilio){

	cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
	for(var key in $scope.MetaDatosSitios){
		var datos_sitiosEC	=	$scope.TodosSitiosServicio.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.ID_CLIENTE_DOMICILIO  == id_cliente_domicilio && element.ID_META_SITIOS == $scope.MetaDatosSitios[key].ID )
			});
			if(typeof datos_sitiosEC != 'undefined'){
				$scope.formDataSitiosEC.input[$scope.MetaDatosSitios[key].ID]	= datos_sitiosEC.VALOR;
			}
			else{
				$scope.formDataSitiosEC.input[$scope.MetaDatosSitios[key].ID]	= "";
			}
	}		
	$scope.formDataSitiosEC.cmbClaveClienteDomSitio = id_cliente_domicilio;

	
}
// ===========================================================================
// ***** 		FUNCION PARA EL BOTON GUARDAR DEL MODAL	SITIOS EC		 *****
// ===========================================================================
	$scope.submitFormSitiosEC = function (formDataSitiosEC) {
						
		var input = "";	
		input = JSON.stringify($scope.formDataSitiosEC.input);
			var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				DOMICILIO	:	$scope.formDataSitiosEC.cmbClaveClienteDomSitio,
				INPUT	:	input,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
		if($scope.accion_sitio == 'insertar'){
			
			$http.post(global_apiserver + "/i_ec_sitios/insert/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarSitiosECServicio($scope.id_servicio_cliente_etapa);
					cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		 }
		if($scope.accion_sitio == 'editar'){	
			
			$http.post(global_apiserver + "/i_ec_sitios/update/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   
					cargarSitiosECServicio($scope.id_servicio_cliente_etapa);
					cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		}
		$("#modalInsertarActualizarSitiosEC").modal("hide");
		
	};


// =======================================================================================
// ***** 			FUNCION PARA EL BOTON INSERTAR ACTUALIZAR SITIOS				 *****
// =======================================================================================	
	function cargarMetaDatosSitios(id_tipos_servicio){
		$http.get(  global_apiserver + "/i_meta_sitios/getByIdTipoServicio/?id="+id_tipos_servicio)
		.then(function( response ){
			$scope.MetaDatosSitios = response.data;
			$scope.cant_MetaDatosSitios = $scope.MetaDatosSitios.length;
		});
		
	}
// ===================================================================================
// ***** Funcion para obtener las opciones de los metadatosSitios que son select *****
// ===================================================================================
function cargarOpcionesSelectMetaDatosSitios(){
		$scope.YsSitios	= {};
		for(var key in $scope.MetaDatosSitios){
			if( $scope.MetaDatosSitios[key].TIPO == 2){
					$http.get(  global_apiserver + "/i_opciones_select_metadatos_sitios/getByIdMetaSitios/?id="+$scope.MetaDatosSitios[key].ID)
						.then(function( response ){
							$scope.YsSitios[response.data[0].ID_META_SITIOS] = response.data;
			
					});
					//$scope.Ys[$scope.MetaDatos[key].ID] = datos_select;	
			}
			else{
				
			}
		}
}		
// =======================================================================================
// ***** 			FUNCION PARA CARGAR SITIOS DEL SERVICIO				 *****
// =======================================================================================	
	function cargarSitiosECServicio(id_servicio){
		$http.get(  global_apiserver + "/i_ec_sitios/getByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.SitiosServicio = response.data;
			
		});
		
	}
// =======================================================================================
// ***** 			FUNCION PARA CARGAR DATOS DE UN SITIO DEL SERVICIO 				 *****
// =======================================================================================	
	$scope.CargarDatosSitiosEC = function(id_cliente_domicilio){
		$http.get(  global_apiserver + "/i_ec_sitios/getByIdServicioAndIdDomicilio/?id="+$scope.id_servicio_cliente_etapa+"&id_cliente_domicilio="+id_cliente_domicilio)
		.then(function( response ){
			$scope.DatosSitiosEC[id_cliente_domicilio] = response.data;
			
		});
		
	}
// =======================================================================================
// ***** 			FUNCION PARA CARGAR DATOS DE TODOS LOS SITIOS DEL SERVICIO		 *****
// =======================================================================================	
	function cargarTodosSitiosECServicio(id_servicio){
		$http.get(  global_apiserver + "/i_ec_sitios/getAllByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.TodosSitiosServicio = response.data;
			
		});
		
	}	
// ======================================================================
// *****				 	Mostrar valor de un select 				*****
// ======================================================================
$scope.mostrarvalorselectsitios	= function(dat){
		$http.get(  global_apiserver + "/i_opciones_select_metadatos_sitios/getById/?id="+dat)
						.then(function( response ){
							$scope.resp1[dat]	= response.data.OPCION;
			
					});
			
}
//=======================================================================
//			A PARTIR DE AQUI LAS FUNCIONES PARA SITIOS SG
//=======================================================================
// ===========================================================================
// *****	Funcion para limpiar las variables del modal sitios para SG	 *****
// ===========================================================================
function clear_modal_sitio(){
	
	$scope.formDataSitio	=	{};
	
	
}
// ===========================================================================
// ***** 	Funcion para llenar las variables del modal sitiosSG		 *****
// ===========================================================================
function llenar_modal_sitiosSG(id_servicio_cliente_etapa,id_cliente_domicilio){

cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
	//for(var key in $scope.MetaDatosSitios){
		var datos_sitiosSG	=	$scope.SitiosServicioSG.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.ID_CLIENTE_DOMICILIO  == id_cliente_domicilio )
			});
			if(typeof datos_sitiosSG != 'undefined'){
				$scope.formDataSitio.txtCantTurn	= datos_sitiosSG.CANTIDAD_TURNOS;
				$scope.formDataSitio.txtNoTotalEmplea	=	datos_sitiosSG.NUMERO_TOTAL_EMPLEADOS;
				$scope.formDataSitio.txtNoEmpleaCertif	=	datos_sitiosSG.NUMERO_EMPLEADOS_CERTIFICACION;
				$scope.formDataSitio.txtCantProce	=	datos_sitiosSG.CANTIDAD_DE_PROCESOS;
				$scope.formDataSitio.cmbDuracion	=	datos_sitiosSG.TEMPORAL_O_FIJO;
				$scope.formDataSitio.cmbMatrizPrincipal	=	datos_sitiosSG.MATRIZ_PRINCIPAL;
				$scope.formDataSitio.txtActividad	=	datos_sitiosSG.ID_ACTIVIDAD;
				$scope.formDataSitio.cmbClaveClienteDomSitio	=	id_cliente_domicilio;
			}
			else{
				//$scope.formDataSitiosEC.input[$scope.MetaDatosSitios[key].ID]	= "";
			}
	
}
// =======================================================================
// ***** 		FUNCION PARA EL BOTON GUARDAR DEL MODAL	SITIOS 		 *****
// =======================================================================
	$scope.submitFormSitio = function (formDataSitio) {
		
		if($scope.formDataSitio.chkActv == true){
			InsertarActividad($scope.formDataSitio.nuevaActividad);
		}
		
			var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				DOMICILIO	:	$scope.formDataSitio.cmbClaveClienteDomSitio,
				//CANTIDAD_PERSONAS:$("#txtCantPerso").val(),
				CANTIDAD_TURNOS:	$scope.formDataSitio.txtCantTurn,
				NUMERO_TOTAL_EMPLEADOS:	$scope.formDataSitio.txtNoTotalEmplea,
				NUMERO_EMPLEADOS_CERTIFICACION:	$scope.formDataSitio.txtNoEmpleaCertif,
				CANTIDAD_DE_PROCESOS:	$scope.formDataSitio.txtCantProce,
				//NOMBRE_PROCESOS:$("#txtNombreProcesos").val(),
				TEMPORAL_O_FIJO:	$scope.formDataSitio.cmbDuracion,
				MATRIZ_PRINCIPAL:	$scope.formDataSitio.cmbMatrizPrincipal,
				ID_ACTIVIDAD : $scope.formDataSitio.txtActividad,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
		if($scope.accion_sitio == 'insertar'){
			
			$http.post(global_apiserver + "/i_sg_sitios/insert/",datos).
            then(function(response){
				
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
					//cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                
            });
		 }
		if($scope.accion_sitio == 'editar'){	
			
			$http.post(global_apiserver + "/i_sg_sitios/update/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   
					cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
					//cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                
            });
		}
		$("#modalInsertarActualizarSitios").modal("hide");
	
	};
//====================================================
//		FUNCION PARA EL CHEKBOX
//====================================================
$scope.chkActivid	=	function(){
	
	if($scope.formDataSitio.chkActv == true){
		$scope.formDataSitio.txtActividad	=	" ";
	}
	else{
		$scope.formDataSitio.txtActividad	=	"";
		$scope.formDataSitio.nuevaActividad = "123";
	}
	
}
//====================================================
//		FUNCION PARA INSERTAR NUEVA ACTIVIDAD
//====================================================
function InsertarActividad(newActiv){
	var actividad = {
          ACTIVIDAD : newActiv,
          ID_USUARIO:sessionStorage.getItem("id_usuario")
      };
	  $http.post(global_apiserver + "/sg_actividad/insert/",actividad).
            then(function(response){
			
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se ha insertado una nueva actividad','success');
					$scope.formDataSitio.txtActividad = response.data.ID;
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                
            });
}
//========================================================================
// ***** 	FUNCION PARA CARGAR SITIOS DEL SERVICIO	PARA SG			 *****
// =======================================================================
	function cargarSitiosSGServicio(id_servicio){
		$http.get(  global_apiserver + "/i_sg_sitios/getAllByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.SitiosServicioSG = response.data;
			
		});
		
    }
//========================================================================================
// ***** 	FUNCION PARA CARGAR SITIOS que se pueden asignar a una auditoria SG		 *****
// =======================================================================================
     function cargarSitiosParaAuditoria(id_servicio,id_tipo_auditoria,ciclo){
		$http.get(  global_apiserver + "/i_sg_sitios/getAllBySCETipoAuditoria/?idsce="+id_servicio+"&idtipoauditoria="+id_tipo_auditoria+"&ciclo="+ciclo)
		.then(function( response ){
			$scope.SitiosParaAuditoria = response.data;
            $scope.cant_sitios = $scope.SitiosParaAuditoria.length;
            $scope.id_tipo_auditoria = id_tipo_auditoria;
			$scope.ciclo=ciclo;
            $("#modalExplorarSitios").modal("show");
		});
		
    }
    $scope.agregar_sitio_auditoria = function(id_cliente_domicilio,id_sce,id_tipo_auditoria,ciclo){
		
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
		 var sitio = {
            ID_SERVICIO_CLIENTE_ETAPA:id_sce,
            TIPO_AUDITORIA:id_tipo_auditoria,
			CICLO:ciclo,
            ID_CLIENTE_DOMICILIO:id_cliente_domicilio,
            ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
          $http.post(global_apiserver + "/i_sg_auditoria_sitios/insert/",sitio).
            then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha insertado un nuevo sitio','success');
                    $("#modalExplorarSitios").modal("hide");
                    cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalExplorarSitios").modal("hide");
				$("#modalExplorarSitiosEC").modal("hide");
            });
          
    }
    $scope.eliminar_sitio_auditoria = function(id_sce,id_tipo_auditoria,id_cliente_domicilio,ciclo){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
		
        var sitio = {
            ID_SERVICIO_CLIENTE_ETAPA:id_sce,
            TIPO_AUDITORIA:id_tipo_auditoria,
			CICLO:ciclo,
            ID_CLIENTE_DOMICILIO:id_cliente_domicilio,
            ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
          $http.post(global_apiserver + "/i_sg_auditoria_sitios/delete/",sitio).
            then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha eliminado el sitio','success');
                    cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
                    $("#collapse-"+id_sce+"-"+id_tipo_auditoria+"-"+ciclo+"sitios-auditoria").collapse("show");	
					$("#collapse-"+id_sce+"-"+id_tipo_auditoria+"-"+ciclo+"sitios-auditoria_ec").collapse("show");	
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
            });
    }

// ======================================================================
// *****			FUNCION CARGAR CLIENTES DOMICILIO				*****
// ======================================================================
function cargarClientesDomicilio(id){
	$http.get(  global_apiserver + "/clientes_domicilios/getByClient/?id="+id)
		.then(function( response ){
			$scope.ClientesDomicilios = response.data;
			
		});
}
// ======================================================================
// *****				FUNCION CARGAR ACTIVIDAD					*****
// ======================================================================
function cargarActividad(){
	$http.get(  global_apiserver + "/sg_actividad/getAll/")
		.then(function( response ){
			$scope.Actividades = response.data;
			
		});
}
// ======================================================================
// *****	A PARTIR DE AQUI TRABAJO CON EL TAB AUDITORIAS PARA SG	*****
// ======================================================================
// 	======================================================================
//	*****				FUNCION AGREGAR ACTUALIZAR AUDITORIAS        *****
//	======================================================================
$scope.agregar_editar_auditorias = function(accion_auditoria,id_sce,id_tipo_auditoria,ciclo){
	
	cargarTiposAuditoria($scope.DatosServicio.ID_SERVICIO);
	cargarStatusAuditoria();
	clear_modal_auditorias();
	
	$scope.accion_auditoria	=	accion_auditoria;
	if(($scope.accion_auditoria == 'insertar')&&($scope.DatosServicio.ID_SERVICIO == 1)){
		$scope.modal_titulo_auditoria = "INSERTAR AUDITORIAS";
		$scope.formDataAuditoria.CICLO = $scope.DatosServicio.CICLO;
		$("#modalInsertarActualizarAuditoria").modal("show");
	}
	if(($scope.accion_auditoria == 'editar')&&($scope.DatosServicio.ID_SERVICIO == 1)){
		$scope.modal_titulo_auditoria = "EDITAR AUDITORIAS";
		$scope.formDataAuditoria.CICLO = ciclo;
		llenar_modal_AuditoriasSG($scope.id_servicio_cliente_etapa,id_tipo_auditoria);
		$("#modalInsertarActualizarAuditoria").modal("show");	
	}
	if(($scope.accion_auditoria == 'insertar')&&($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4)){
		$scope.modal_titulo_auditoria = "INSERTAR AUDITORIAS";
		$scope.formDataAuditoriaEC.CICLO = $scope.DatosServicio.CICLO;
		$("#modalInsertarActualizarAuditoriaEC").modal("show");
	}
	if(($scope.accion_auditoria == 'editar')&&($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4)){
		$scope.modal_titulo_auditoria = "EDITAR AUDITORIAS";
		$scope.formDataAuditoriaEC.CICLO = ciclo;
		llenar_modal_AuditoriasEC($scope.id_servicio_cliente_etapa,id_tipo_auditoria);
		$("#modalInsertarActualizarAuditoriaEC").modal("show");	
	}
}
// ===========================================================================
// ***** Funcion para limpiar las variables del modal auditorias para SG *****
// ===========================================================================
function clear_modal_auditorias(){
	if($scope.DatosServicio.ID_SERVICIO == 1){
		$scope.formDataAuditoria	=	{};
		if($scope.DatosServicio.ID_TIPO_SERVICIO == 20){
			$scope.formDataAuditoria.txtDuracionAuditoria1 = {};
			for(var key in $scope.DatosServicio.NORMAS){
					$scope.formDataAuditoria.txtDuracionAuditoria1[$scope.DatosServicio.NORMAS[key].ID_NORMA] = "";
				}
		}		
	}
	if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
		$scope.formDataAuditoriaEC	=	{};
	}
	
}
// ===========================================================================
// ***** 	Funcion para llenar las variables del modal AuditoriasSG		 *****
// ===========================================================================
function llenar_modal_AuditoriasSG(id_servicio_cliente_etapa,id_tipo_auditoria){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
		var datos_auditoriasSG	=	$scope.DatosAuditoriasSG.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria )
			});
			if(typeof datos_auditoriasSG != 'undefined'){
				$scope.formDataAuditoria.txtDuracionAuditoria	= datos_auditoriasSG.DURACION_DIAS;
				$scope.formDataAuditoria.cmbTipoAuditoria	=	datos_auditoriasSG.TIPO_AUDITORIA;
				$scope.formDataAuditoria.cmbStatusAuditoria	=	datos_auditoriasSG.STATUS_AUDITORIA;
				if(datos_auditoriasSG.NO_USA_METODO == 0)
					$scope.formDataAuditoria.chkNoMetodo	=	false;
				else
					$scope.formDataAuditoria.chkNoMetodo	=	true;
				$scope.formDataAuditoria.txtSitiosAuditoria	=	datos_auditoriasSG.SITIOS_AUDITAR;
				
				
			}
			else{
				
			}
			if($scope.DatosServicio.ID_TIPO_SERVICIO == 20){
				for(var key in $scope.DatosServicio.NORMAS){
	//$scope.formDataAuditoria.txtDuracionAuditoria += parseInt($scope.formDataAuditoria.txtDuracionAuditoria1[$scope.DatosServicio.NORMAS[key].ID_NORMA]);
					var datos_auditoriasSGIntegral=$scope.DatosAuditoriasSGIntegral.find(function(element,index,array){
						return (element.ID_SCE == id_servicio_cliente_etapa && element.ID_TIPO_AUDITORIA  == id_tipo_auditoria && element.ID_NORMA == $scope.DatosServicio.NORMAS[key].ID_NORMA )
					});
					if(typeof datos_auditoriasSGIntegral != 'undefined'){
						$scope.formDataAuditoria.txtDuracionAuditoria1[$scope.DatosServicio.NORMAS[key].ID_NORMA] = datos_auditoriasSGIntegral.DIAS_AUDITOR;
					}
				}	
			}else{
			}
	
}
// =======================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	AUDITORIAS 		 *****
// =======================================================================
	$scope.submitFormAuditoria = function (formDataAuditoria) {
	
	//VERIFICAMOS SI ES UNA AUDITORIA INTEGRAL
	if($scope.DatosServicio.ID_TIPO_SERVICIO==20){
		$scope.formDataAuditoria.txtDuracionAuditoria=0;
		for(var key in $scope.DatosServicio.NORMAS){
			$scope.formDataAuditoria.txtDuracionAuditoria += parseInt($scope.formDataAuditoria.txtDuracionAuditoria1[$scope.DatosServicio.NORMAS[key].ID_NORMA]);
		}
		$scope.formDataAuditoria.txtDuracionAuditoria1=JSON.stringify($scope.formDataAuditoria.txtDuracionAuditoria1);
	}
	else{
		$scope.formDataAuditoria.txtDuracionAuditoria1="NO INTEGRAL";
	}
		
		if($scope.formDataAuditoria.chkNoMetodo!=true){
			$scope.formDataAuditoria.txtSitiosAuditoria = "";
			$scope.formDataAuditoria.chkNoMetodo = false;
		}
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}		
		var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				DURACION_DIAS:	$scope.formDataAuditoria.txtDuracionAuditoria,
				DURACION_DIAS_INTEGRAL: $scope.formDataAuditoria.txtDuracionAuditoria1,
				TIPO_AUDITORIA:	$scope.formDataAuditoria.cmbTipoAuditoria,
				CICLO:	$scope.formDataAuditoria.CICLO,
				STATUS_AUDITORIA:	$scope.formDataAuditoria.cmbStatusAuditoria,
				NO_USA_METODO:	$scope.formDataAuditoria.chkNoMetodo,
				SITIOS_AUDITAR:	$scope.formDataAuditoria.txtSitiosAuditoria,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};		
			
		if($scope.accion_auditoria == 'insertar'){
			
						
			$http.post(global_apiserver + "/i_sg_auditorias/insert/",datos).
            then(function(response){
				
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					

                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });
			
		 }
		if($scope.accion_auditoria == 'editar'){	
			
				
			$http.post(global_apiserver + "/i_sg_auditorias/update/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					
					
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });
			 $("#modalInsertarActualizarAuditoria").modal("hide");
		}
		
	 $("#modalInsertarActualizarAuditoria").modal("hide");
	}
	
// ======================================================================
// *****			FUNCION CARGAR DATOS AUDITORIAS					*****
// ======================================================================
function cargarDatosAuditoriasSG(id_servicio){
	$http.get(  global_apiserver + "/i_sg_auditorias/getAllByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.DatosAuditoriasSG = response.data;
			const temp = [];
			let auditoriaSalvada;
			$scope.DatosAuditoriasSG.forEach(auditoria => {
				if ($scope.estatusAuditoria) {
					auditoriaSalvada = $scope.estatusAuditoria.find(aud => {
						return aud.SERVICIO_CLIENTE_ETAPA == auditoria.SERVICIO_CLIENTE_ETAPA && aud.TIPO_AUDITORIA == auditoria.TIPO_AUDITORIA && aud.CICLO == auditoria.CICLO;
					});
				}
				
				let mostrar = false;
				let mostrar1 = false;
				if (auditoriaSalvada) {
					mostrar = auditoriaSalvada.mostrandoSectoresAuditor;
					mostrar1 = auditoriaSalvada.mostrandoSectoresSitios;
				}
				temp.push({
					mostrandoSectoresAuditor: mostrar,
					mostrandoSectoresSitios: mostrar1,
					...auditoria
				});
			});
			$scope.DatosAuditoriasSG = temp;
			
			$.each($scope.DatosAuditoriasSG, function( i, datos1 ) {
				$.each(datos1.AUDITORIA_FECHAS, function( j, datos ) {
						$scope.txtFechasAuditoria[datos.ID]=datos.FECHA.substring(0,4)+"-"+datos.FECHA.substring(4,6)+"-"+datos.FECHA.substring(6,8);
		 
					});
				});	
				cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
		});
		//if($scope.DatosServicio.ID_TIPO_SERVICIO == 20){
				$http.get(  global_apiserver + "/sce_normas/getAll/")
					.then(function( response ){
						$scope.DatosAuditoriasSGIntegral = response.data;
				});				
		//}
		
    }
//
function cargarDatosAuditoriasSG_Ciclo(id_servicio,cc){
	$http.get(  global_apiserver + "/i_sg_auditorias/getAllByIdServicioAndCiclo/?id="+id_servicio+"&ciclo="+cc)
		.then(function( response ){
            $scope.DatosAuditoriasSG = response.data;
			const temp = [];
			let auditoriaSalvada;
			$scope.DatosAuditoriasSG.forEach(auditoria => {
				if ($scope.estatusAuditoria) {
					auditoriaSalvada = $scope.estatusAuditoria.find(aud => {
						return aud.SERVICIO_CLIENTE_ETAPA == auditoria.SERVICIO_CLIENTE_ETAPA && aud.TIPO_AUDITORIA == auditoria.TIPO_AUDITORIA && aud.CICLO == auditoria.CICLO;
					});
				}
				
				let mostrar = false;
				let mostrar1 = false;
				if (auditoriaSalvada) {
					mostrar = auditoriaSalvada.mostrandoSectoresAuditor;
					mostrar1 = auditoriaSalvada.mostrandoSectoresSitios;
				}
				temp.push({
					mostrandoSectoresAuditor: mostrar,
					mostrandoSectoresSitios: mostrar1,
					...auditoria
				});
			});
			$scope.DatosAuditoriasSG = temp;
			$.each($scope.DatosAuditoriasSG, function( i, datos1 ) {
				$.each(datos1.AUDITORIA_FECHAS, function( j, datos ) {
						$scope.txtFechasAuditoria[datos.ID]=datos.FECHA.substring(0,4)+"-"+datos.FECHA.substring(4,6)+"-"+datos.FECHA.substring(6,8);
		 
					});
				});	
			cargarGastosAuditorias($scope.id_servicio_cliente_etapa);	
		});			
		//}
    }	
$scope.btnInsertaSitiosAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,id_cliente_domicilio,ciclo){
    cargarSitiosParaAuditoria(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo);
}
// ========================================================================
// ***	FUNCION CUANDO CAMBIA DIAS AUDITOR EN TIPO SERVICIO INTEGRAL	***
// ========================================================================
$scope.CambioDiaAuditor = function(){
	$scope.formDataAuditoria.txtDuracionAuditoria=0; 
	for(var key in $scope.DatosServicio.NORMAS){
	//for($i=0;$i<count($scope.DatosServicio.NORMAS);$i++){
		$scope.formDataAuditoria.txtDuracionAuditoria += parseInt($scope.formDataAuditoria.txtDuracionAuditoria1[$scope.DatosServicio.NORMAS[key].ID_NORMA]);
	}
	 
}
// ======================================================================
// *****				FUNCION TIPOS AUDITORIAS					*****
// ======================================================================
function cargarTiposAuditoria(id_servicio){
	//$http.get(  global_apiserver + "/i_tipos_auditorias/getAll/")
	$http.get(  global_apiserver + "/i_tipos_auditorias//getByIdServicio/?id="+id_servicio)
		.then(function( response ){
			$scope.TiposAuditorias = response.data;
			
		});
}
// ======================================================================
// *****				FUNCION STATUS AUDITORIAS					*****
// ======================================================================
function cargarStatusAuditoria(){
	$http.get(  global_apiserver + "/i_sg_status_auditorias/getAll/")
		.then(function( response ){
			$scope.StatusAuditorias = response.data;
			
		});
}
// ======================================================================
// *****		FUNCION PARA EL BOTON SITIOS AUDITORIAS				*****
// ======================================================================
$scope.btnSitiosAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){

var datos_auditoriasSG	=	$scope.DatosAuditoriasSG.find(function(element,index,array){
		return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria && element.CICLO == ciclo)
	});
	
	if (datos_auditoriasSG) {
		const index = $scope.DatosAuditoriasSG.indexOf(datos_auditoriasSG);
		$scope.DatosAuditoriasSG[index].mostrandoSectoresSitios = !$scope.DatosAuditoriasSG[index].mostrandoSectoresSitios;
	}
	
}

//	=====================================================================
//	*****	FUNCION PARA AGREGAR O EDITAR FECHAS A UNA AUDITORIA	*****
//	=====================================================================
$scope.agregar_editar_fechasAuditoria = function(id_sce,id_tipo_auditoria,accion,ciclo,id){
	
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if(accion == 'insertar'){
			$scope.txtInsertarFechas[id_tipo_auditoria] = $('#txtInsertarFechas-'+id_tipo_auditoria+'-'+ciclo).val();
			if( $scope.txtInsertarFechas[id_tipo_auditoria] != "" && typeof $scope.txtInsertarFechas[id_tipo_auditoria] != 'undefined' ){
				
				var datos	=	{
					ID_SERVICIO_CLIENTE_ETAPA:	id_sce,
					TIPO_AUDITORIA:	id_tipo_auditoria,
					CICLO: ciclo,
					//FECHA:	$scope.txtInsertarFechas[id_tipo_auditoria].substring(0,4)+$scope.txtInsertarFechas[id_tipo_auditoria].substring(5,7)+$scope.txtInsertarFechas[id_tipo_auditoria].substring(8,10),
					FECHA:	$scope.txtInsertarFechas[id_tipo_auditoria],
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
				$http.post(global_apiserver + "/i_sg_auditoria_fechas/insert/",datos).
					then(function(response){
					$scope.txtInsertarFechas[id_tipo_auditoria]="";
					if(response.data.resultado=="ok"){
						notify('&Eacutexito','Se han actualizado los datos','success');
						if($scope.DatosServicio.ID_SERVICIO == 1){		
							cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
						}
						if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
							cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
						}
					}
					else{
						notify('Error',response.data.mensaje,'error');
						if($scope.DatosServicio.ID_SERVICIO == 1){		
							cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
						}
						if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){
							cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
						}
					}
               
				});
			}
			else{
				notify('Error',"Debe seleccionar una fecha para agregar",'error');
			}
		 }
		if(accion == 'editar'){	
			$scope.txtFechasAuditoria[id] = $('#txtFechasAuditoria'+id).val();
			var datos	=	{
					ID:	id,
					ID_SERVICIO_CLIENTE_ETAPA:	id_sce,
					TIPO_AUDITORIA:	id_tipo_auditoria,
					CICLO: ciclo,
					FECHA:	$scope.txtFechasAuditoria[id].substring(0,4)+$scope.txtFechasAuditoria[id].substring(5,7)+$scope.txtFechasAuditoria[id].substring(8,10),
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
			$http.post(global_apiserver + "/i_sg_auditoria_fechas/update/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);	
					}
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);	
					}
				}
               
            });
			 
		}
}
//	=====================================================================
//	*****	FUNCION PARA ELIMINAR FECHAS A UNA AUDITORIA			*****
//	=====================================================================
$scope.eliminar_fechasAuditoria = function(id){
	$scope.IdFechaEliminar=id;
	 $("#modalConfirmacion").modal("show");
	 
}
$scope.EliminarFecha = function(){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
	var datos	=	{
					ID:	$scope.IdFechaEliminar,
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
			$http.post(global_apiserver + "/i_sg_auditoria_fechas/delete/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han eliminado los datos','success');
					
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
					$scope.IdFechaEliminar="";
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
					$scope.IdFechaEliminar="";
                }
               
            });
	 $("#modalConfirmacion").modal("hide");
	 
}

// ======================================================================
// *****		FUNCION PARA EL BOTON GRUPOS AUDITORIAS				*****
// ======================================================================
$scope.btnGrupoAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){	
	// $("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"-grupo-auditoria").collapse("toggle");
	
	var datos_auditoriasSG	=	$scope.DatosAuditoriasSG.find(function(element,index,array){
		return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria && element.CICLO == ciclo)
	});
	
	if (datos_auditoriasSG) {
		const index = $scope.DatosAuditoriasSG.indexOf(datos_auditoriasSG);
		$scope.DatosAuditoriasSG[index].mostrandoSectoresAuditor = !$scope.DatosAuditoriasSG[index].mostrandoSectoresAuditor;
	}
	
}
// ======================================================================
// *****	FUNCION PARA EL BOTON INSERTAR GRUPOS AUDITORIAS		*****
// ======================================================================
$scope.btnInsertaGrupoAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){
    cargarAuditorParaGrupoAuditoria(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo);
}
//===============================================================================
// ***** FUNCION PARA CARGAR AUDITOR que se puede asignar a una auditoria	*****
// ==============================================================================
     function cargarAuditorParaGrupoAuditoria(id_servicio,id_tipo_auditoria,ciclo){
		$scope.grupo_id_tipo_auditoria = id_tipo_auditoria;
		$scope.grupo_ciclo=ciclo;
		$http.get(  global_apiserver + "/i_sg_auditorias/getAllAudWithSectorCalif/?idsce="+id_servicio+"&idtipoauditoria="+id_tipo_auditoria+"&ciclo="+ciclo)
		.then(function( response ){
			if(response.data.resultado == 'ok'){
				console.log(response.data);
				$scope.AuditoresParaAuditoria = response.data.CON_CALIFICACION;
				console.log($scope.AuditoresParaAuditoria);
				$scope.AuditoresParaAuditoria1 = response.data.SIN_CALIFICACION;
				$scope.cant_auditores = $scope.AuditoresParaAuditoria.length;
				//$scope.grupo_id_tipo_auditoria = id_tipo_auditoria;
				//$scope.grupo_ciclo=ciclo;
				$("#modalExplorarGrupo").modal("show");
				//$("#modalInsertarActualizarGrupoAuditoria").modal("show");
				
			}
			else if(response.data.resultado == 'error'){
				notify('Error',response.data.mensaje,'error');
			}
		});
		
    }
// ======================================================================
// *****  FUNCION PARA CARGAR MODAL QUE GUARDA EL AUDITOR GRUPO		*****
// ======================================================================
$scope.cargarModalInsertarActualizarGrupoAuditor = function(id_pt_calif,nombre_completo){
	$scope.formDataGrupoAuditor.txtClavePTCalifGrupo = nombre_completo;
	$scope.formDataGrupoAuditor.idPTCalifGrupo = id_pt_calif;
	cargarRolesAuditorTipoServicio($scope.DatosServicio.ID_TIPO_SERVICIO);
	if($scope.DatosServicio.ID_SERVICIO == 1){
		$("#modalExplorarGrupo").modal("hide");
	}
	if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
		$("#modalExplorarGrupoEC").modal("hide");
	}
	
    $("#modalInsertarActualizarGrupoAuditoria").modal("show");
}	
// ======================================================================
// *****	FUNCION CARGAR AUDITORES ROLES	SEGUN TIPO SERVICIO	 	*****
// ======================================================================
function cargarRolesAuditorTipoServicio(idts){
	$http.get(  global_apiserver + "/personal_tecnico_roles/getByIdTipoServicio/?id="+idts)
		.then(function( response ){
			$scope.cmbRoles = response.data;
			
		});
}	
// ======================================================================
// *****			FUNCION CARGAR AUDITORES ROLES					*****
// ======================================================================
function cargarRolesAuditor(){
	$http.get(  global_apiserver + "/personal_tecnico_roles/getAll/")
		.then(function( response ){
			$scope.cmbRoles = response.data;
			
		});
}	
// ===========================================================================
// ***** Funcion para limpiar las variables del modal auditorias para SG *****
// ===========================================================================
function clear_modal_auditorias_grupos(){
	
	$scope.formDataGrupoAuditor	=	{};
	
	
}
// ==============================================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	INSERTAR/ACTUALIZAR GRUPO AUDITORIAS 	*****
// ==============================================================================================
$scope.submitFormGrupoAuditor = function (formDataGrupoAuditor) {
		
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
		var grupo = {
            ID_SERVICIO_CLIENTE_ETAPA:$scope.id_servicio_cliente_etapa ,
            TIPO_AUDITORIA:	$scope.grupo_id_tipo_auditoria ,
			CICLO:	$scope.grupo_ciclo,
            ID_PERSONAL_TECNICO_CALIF: $scope.formDataGrupoAuditor.idPTCalifGrupo,
			ID_ROL:	formDataGrupoAuditor.cmbRol,
			//FECHAS_ASIGNADAS:$("#txtFechasGrupoAuditor").multiDatesPicker('value'),
            ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
		
		$http.post(global_apiserver + "/i_sg_auditoria_grupos/insert/",grupo).
            then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha insertado un nuevo auditor','success');
                    $("#modalInsertarActualizarGrupoAuditoria").modal("hide");
					if($scope.DatosServicio.ID_SERVICIO == 1){
		                cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
				}
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalInsertarActualizarGrupoAuditoria").modal("hide");
            });
}
// ==============================================================================
// ***** 			Funcion para agregar las fechas de un auditor			*****
// ==============================================================================
$scope.agregar_editar_fechasAuditoriaGrupo = function(id_sce,id_ta,ciclo,id_pt){
	
	//VERIFICAMOS SI ES UNA AUDITORIA INTEGRAL
	var norma_serv_integral = 0;
	if($scope.DatosServicio.ID_TIPO_SERVICIO==20){
	$scope.formDataGrupoAuditorFechaNorma.id_sce	=	id_sce;
	$scope.formDataGrupoAuditorFechaNorma.id_ta	=	id_ta;
	$scope.formDataGrupoAuditorFechaNorma.ciclo	=	ciclo;
	$scope.formDataGrupoAuditorFechaNorma.id_pt	=	id_pt;
		$("#modalNormaFechaServIntegral").modal("show");
	}
	else{
		$scope.funcion_guardar_datos(id_sce,id_ta,ciclo,id_pt,norma_serv_integral);
	}
		
}
$scope.submitFormGrupoAuditorFechaNorma = function (formDataGrupoAuditorFechaNorma) {
	
	$("#modalNormaFechaServIntegral").modal("hide");
	$scope.funcion_guardar_datos($scope.formDataGrupoAuditorFechaNorma.id_sce,$scope.formDataGrupoAuditorFechaNorma.id_ta,$scope.formDataGrupoAuditorFechaNorma.ciclo,$scope.formDataGrupoAuditorFechaNorma.id_pt,$scope.formDataGrupoAuditorFechaNorma.norma);
}
$scope.funcion_guardar_datos = function(id_sce,id_ta,ciclo,id_pt,norma_serv_integral){
	if($scope.DatosServicio.ID_SERVICIO == 1){
		$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
	}	
	if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
		$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
	}
	$scope.txtInsertarFechasGrupo[id_pt] = $('#txtInsertarFechasGrupo-'+id_ta+'-'+ciclo+'-'+id_pt).val();
	var fecha = {
            ID_SERVICIO_CLIENTE_ETAPA:id_sce ,
            TIPO_AUDITORIA:	id_ta ,
			CICLO:	ciclo,
            ID_PERSONAL_TECNICO_CALIF: id_pt,
			//FECHA:	$scope.txtInsertarFechasGrupo[id_pt].substring(0,4)+$scope.txtInsertarFechasGrupo[id_pt].substring(5,7)+$scope.txtInsertarFechasGrupo[id_pt].substring(8,10),
			FECHA:	$scope.txtInsertarFechasGrupo[id_pt],
			ID_NORMA: norma_serv_integral,
			ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
	$http.post(global_apiserver + "/i_sg_auditoria_grupo_fechas/insert/",fecha).
            then(function(response){
			
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se ha insertado una nueva fecha','success');
                    if($scope.DatosServicio.ID_SERVICIO == 1){
		                cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					 if($scope.DatosServicio.ID_SERVICIO == 1){
		                cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
                }
               
            });
		$scope.txtInsertarFechasGrupo={};		

}
// ==============================================================================
// ***** 			Funcion para eliminar las fechas de un auditor			*****
// ==============================================================================
$scope.eliminar_fechasAuditoriaGrupo = function(id){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
	var datos	=	{
					ID:	id,
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
			$http.post(global_apiserver + "/i_sg_auditoria_grupo_fechas/delete/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han eliminado los datos','success');
					
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa,0,0);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
					$scope.IdFechaEliminar="";
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					if($scope.DatosServicio.ID_SERVICIO == 1){
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa,0,0);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
					$scope.IdFechaEliminar="";
                }
               
            });
	 $("#modalConfirmacion").modal("hide");
	 
}	
// ==============================================================
// *****	FUNCION PARA MOSTRAR NORMA PARA TS INTEGRAL		*****
// ==============================================================
$scope.mostrarNorma = function(norma){
	var resp="";
	if(norma!= 0){
		resp ="para "+norma;
	}
	return resp;
}

// ==============================================================================
// ***** 				Funcion para eliminar un auditor					*****
// ==============================================================================
$scope.eliminar_grupo_auditoria = function(id_sce,id_ta,ciclo,id_pt){
	var auditor = {
            ID_SERVICIO_CLIENTE_ETAPA:id_sce ,
            TIPO_AUDITORIA:	id_ta ,
			CICLO:	ciclo,
            ID_PERSONAL_TECNICO_CALIF: id_pt,
			ID_USUARIO:sessionStorage.getItem("id_usuario")
		  };
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
	$http.post(global_apiserver + "/i_sg_auditoria_grupos/delete/",auditor).
            then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha eliminado un auditor','success');
                    if($scope.DatosServicio.ID_SERVICIO == 1){
		                cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					}
					if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });	  
	
}
// ==============================================================================
// ***** 	Funcion para obtener ciclo a partir de referencia	*****
// ==============================================================================
	function ObtenerCicloDeReferencia(referencia){
		var c1 = referencia.split("-");
		var c2 = c1[0].split("C");
		return c2[1];
	
	}	
// ======================================================================
// *****	FUNCION PARA MOSTRAR FECHA CON FORMATO DD/MM/AAAA		*****
// ======================================================================
$scope.mostrarFecha = function(fecha,norma){
	
	return fecha.substring(0,4)+"-"+fecha.substring(4,6)+"-"+fecha.substring(6,8);
}

// ==========================================================================================
// ***** FUNCION PARA GENERAR UN ARREGLO QUE HABILITE SOLO LAS FECHAS DE ESTA AUDITORIA	*****
// ==========================================================================================
$scope.GenerarArregloFecha = function(fechas){
    var fecha1 = new Array();
	var dia;
	var mes;
	var ano;
	for(var key in fechas){
		ano = fechas[key].FECHA.substring(0,4);
	//	if(fechas[key].FECHA.substring(4,5)==0){
	//		mes = fechas[key].FECHA.substring(5,6);
	//	}
	//	else{
			mes = fechas[key].FECHA.substring(4,6);
			
	//	}
	//	if(fechas[key].FECHA.substring(6,7)==0)	{
	//		dia = fechas[key].FECHA.substring(7,8);
			
	//	}
	//	else{
			dia = fechas[key].FECHA.substring(6,8);
			
	//	}	
		fecha1[key] = ano+"/"+mes+"/"+dia;
		//fecha1[key] = fecha1[key].toString();
	}
	//var fecha1 = ["2018-9-3"];
	//var fecha2 = ['2018/10/19','2018-9-21'];//,"2018-10-11","2018-10-12"
	//fecha3= new Array(fecha1);
	return fecha1;
}
$scope.GenerarFechaHoy = function(){
	var f = new Date();
	var d = f.getDate();
	var m =f.getMonth()+1;
	var y = f.getFullYear();
	if(m>0 && m<10){
		 m = '0'+m;
	}
					
	if(d>0 && d<10){
		d = '0'+d;
	}					 
	f1 = y +'/'+m+'/'+d;
	return f1;
}
$scope.GenerarFechaInicio = function(f){
	//var f = new Date();
	var y = f.substring(0,4);
	var m =f.substring(5,7);
	var d = f.substring(8,10);
				 
	f1 = y +'/'+m+'/'+d;
	return f1;
}
/*======================================================================*/
//		A PARTIR DE AQUI CODIGO PARA AUDITORIAS EC
/*======================================================================*/
// =======================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	AUDITORIAS 		 *****
// =======================================================================
	$scope.submitFormAuditoriaEC = function (formDataAuditoriaEC) {
		
/*		if($scope.formDataAuditoria.chkNoMetodo!=true){
			$scope.formDataAuditoria.txtSitiosAuditoria = "";
			$scope.formDataAuditoria.chkNoMetodo = false;
		}
*/		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}		
		var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				DURACION_DIAS:	$scope.formDataAuditoriaEC.txtDuracionAuditoria,
				TIPO_AUDITORIA:	$scope.formDataAuditoriaEC.cmbTipoAuditoria,
				CICLO:	$scope.formDataAuditoriaEC.CICLO,
				STATUS_AUDITORIA:	$scope.formDataAuditoriaEC.cmbStatusAuditoria,
			//	NO_USA_METODO:	$scope.formDataAuditoriaEC.chkNoMetodo,
			//	SITIOS_AUDITAR:	$scope.formDataAuditoriaEC.txtSitiosAuditoria,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};		
			
		if($scope.accion_auditoria == 'insertar'){
			
						
			$http.post(global_apiserver + "/i_ec_auditorias/insert/",datos).
            then(function(response){
				
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					

                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });
			
		 }
		if($scope.accion_auditoria == 'editar'){	
			
				
			$http.post(global_apiserver + "/i_ec_auditorias/update/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
				   
					
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });
			// $("#modalInsertarActualizarAuditoriaEC").modal("hide");
		}
		
	 $("#modalInsertarActualizarAuditoriaEC").modal("hide");
	}
// ======================================================================
// *****			FUNCION CARGAR DATOS AUDITORIAS	EC				*****
// ======================================================================
function cargarDatosAuditoriasEC(id_servicio){
	$http.get(  global_apiserver + "/i_ec_auditorias/getAllByIdServicio/?id="+id_servicio)
		.then(function( response ){
            $scope.DatosAuditoriasEC = response.data;
			const temp = [];
			let auditoriaSalvada;
			$scope.DatosAuditoriasEC.forEach(auditoria => {
				if ($scope.estatusAuditoria) {
					auditoriaSalvada = $scope.estatusAuditoria.find(aud => {
						return aud.SERVICIO_CLIENTE_ETAPA == auditoria.SERVICIO_CLIENTE_ETAPA && aud.TIPO_AUDITORIA == auditoria.TIPO_AUDITORIA && aud.CICLO == auditoria.CICLO;
					});
				}
				
				let mostrar = false;
				let mostrar1 = false;
				if (auditoriaSalvada) {
					mostrar = auditoriaSalvada.mostrandoSectoresAuditor;
					mostrar1 = auditoriaSalvada.mostrandoSectoresSitios;
				}
				temp.push({
					mostrandoSectoresAuditor: mostrar,
					mostrandoSectoresSitios: mostrar1,
					...auditoria
				});
			});
			$scope.DatosAuditoriasEC = temp;
			$.each($scope.DatosAuditoriasEC, function( i, datos1 ) {
				$.each(datos1.AUDITORIA_FECHAS, function( j, datos ) {
						$scope.txtFechasAuditoria[datos.ID]=datos.FECHA.substring(0,4)+"-"+datos.FECHA.substring(4,6)+"-"+datos.FECHA.substring(6,8);
		 
					});
				});	
				cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
		});
		
    }
//
function cargarDatosAuditoriasEC_Ciclo(id_servicio,cc){
	$http.get(  global_apiserver + "/i_ec_auditorias/getAllByIdServicioAndCiclo/?id="+id_servicio+"&ciclo="+cc)
		.then(function( response ){
            $scope.DatosAuditoriasEC = response.data;
			const temp = [];
			let auditoriaSalvada;
			$scope.DatosAuditoriasEC.forEach(auditoria => {
				if ($scope.estatusAuditoria) {
					auditoriaSalvada = $scope.estatusAuditoria.find(aud => {
						return aud.SERVICIO_CLIENTE_ETAPA == auditoria.SERVICIO_CLIENTE_ETAPA && aud.TIPO_AUDITORIA == auditoria.TIPO_AUDITORIA && aud.CICLO == auditoria.CICLO;
					});
				}
				
				let mostrar = false;
				let mostrar1 = false;
				if (auditoriaSalvada) {
					mostrar = auditoriaSalvada.mostrandoSectoresAuditor;
					mostrar1 = auditoriaSalvada.mostrandoSectoresSitios;
				}
				temp.push({
					mostrandoSectoresAuditor: mostrar,
					mostrandoSectoresSitios: mostrar1,
					...auditoria
				});
			});
			$scope.DatosAuditoriasEC = temp;
			$.each($scope.DatosAuditoriasEC, function( i, datos1 ) {
				$.each(datos1.AUDITORIA_FECHAS, function( j, datos ) {
						$scope.txtFechasAuditoria[datos.ID]=datos.FECHA.substring(0,4)+"-"+datos.FECHA.substring(4,6)+"-"+datos.FECHA.substring(6,8);
		 
					});
				});	
				cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
		});			
		//}
    }	
$scope.btnInsertaSitiosAuditoriaEC = function(id_servicio_cliente_etapa,id_tipo_auditoria,id_cliente_domicilio,ciclo){
    cargarSitiosParaAuditoriaEC(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo);
}	
// ======================================================================
// *****		FUNCION PARA EL BOTON SITIOS AUDITORIAS				*****
// ======================================================================
$scope.btnSitiosAuditoriaEC = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){
//			$("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"-sitios-auditoria_ec").collapse("show");

var datos_auditoriasEC	=	$scope.DatosAuditoriasEC.find(function(element,index,array){
		return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria && element.CICLO == ciclo)
	});
	
	if (datos_auditoriasEC) {
		const index = $scope.DatosAuditoriasEC.indexOf(datos_auditoriasEC);
		$scope.DatosAuditoriasEC[index].mostrandoSectoresSitios = !$scope.DatosAuditoriasEC[index].mostrandoSectoresSitios;
	}	
}	
//========================================================================================
// ***** 	FUNCION PARA CARGAR SITIOS que se pueden asignar a una auditoria EC		 *****
// =======================================================================================
     function cargarSitiosParaAuditoriaEC(id_servicio,id_tipo_auditoria,ciclo){
		$http.get(  global_apiserver + "/i_ec_sitios/getAllBySCETipoAuditoria/?idsce="+id_servicio+"&idtipoauditoria="+id_tipo_auditoria+"&ciclo="+ciclo)
		.then(function( response ){
			$scope.SitiosParaAuditoriaEC = response.data;
            $scope.cant_sitiosEC = $scope.SitiosParaAuditoriaEC.length;
            $scope.id_tipo_auditoria = id_tipo_auditoria;
			$scope.ciclo=ciclo;
            $("#modalExplorarSitiosEC").modal("show");
		});
		
    }
// ======================================================================
// *****		FUNCION PARA EL BOTON GRUPOS AUDITORIAS	EC			*****
// ======================================================================
$scope.btnGrupoAuditoriaEC = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){
	//$("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"-grupo-auditoria_ec").collapse("toggle");
var datos_auditoriasEC	=	$scope.DatosAuditoriasEC.find(function(element,index,array){
		return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria && element.CICLO == ciclo)
	});
	
	if (datos_auditoriasEC) {
		const index = $scope.DatosAuditoriasEC.indexOf(datos_auditoriasEC);
		$scope.DatosAuditoriasEC[index].mostrandoSectoresAuditor = !$scope.DatosAuditoriasEC[index].mostrandoSectoresAuditor;
	}	
}

// ======================================================================
// *****	FUNCION PARA EL BOTON INSERTAR GRUPOS AUDITORIAS EC		*****
// ======================================================================
$scope.btnInsertaGrupoAuditoriaEC = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){
    cargarAuditorParaGrupoAuditoriaEC(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo);
}
//===============================================================================
// ***** FUNCION PARA CARGAR AUDITOR que se puede asignar a una auditoria	*****
// ==============================================================================
     function cargarAuditorParaGrupoAuditoriaEC(id_servicio,id_tipo_auditoria,ciclo){
		$scope.grupo_id_tipo_auditoria = id_tipo_auditoria;
		$scope.grupo_ciclo=ciclo;
		$http.get(  global_apiserver + "/i_ec_auditorias/getAllAudWithSectorCalif/?idsce="+id_servicio+"&idtipoauditoria="+id_tipo_auditoria+"&ciclo="+ciclo)
		.then(function( response ){
			$scope.AuditoresParaAuditoriaEC = response.data.CON_CALIFICACION;
			$scope.AuditoresParaAuditoriaEC1 = response.data.SIN_CALIFICACION;
			$scope.cant_auditoresEC = $scope.AuditoresParaAuditoriaEC.length+$scope.AuditoresParaAuditoriaEC1.length;
			//$scope.grupo_id_tipo_auditoria = id_tipo_auditoria;
			//$scope.grupo_ciclo=ciclo;
            $("#modalExplorarGrupoEC").modal("show");
			//$("#modalInsertarActualizarGrupoAuditoria").modal("show");
		});
		
    }
// ===========================================================================
// ***** 	Funcion para llenar las variables del modal AuditoriasEC		 *****
// ===========================================================================
function llenar_modal_AuditoriasEC(id_servicio_cliente_etapa,id_tipo_auditoria){
		if($scope.DatosServicio.ID_SERVICIO == 1){
			$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}	
		if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
			$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
		}
cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
		var datos_auditoriasEC	=	$scope.DatosAuditoriasEC.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria )
			});
			if(typeof datos_auditoriasEC != 'undefined'){
				$scope.formDataAuditoriaEC.txtDuracionAuditoria	= datos_auditoriasEC.DURACION_DIAS;
				$scope.formDataAuditoriaEC.cmbTipoAuditoria	=	datos_auditoriasEC.TIPO_AUDITORIA;
				$scope.formDataAuditoriaEC.cmbStatusAuditoria	=	datos_auditoriasEC.STATUS_AUDITORIA;/*
				if(datos_auditoriasEC.NO_USA_METODO == 0)
					$scope.formDataAuditoria.chkNoMetodo	=	false;
				else
					$scope.formDataAuditoria.chkNoMetodo	=	true;
				$scope.formDataAuditoria.txtSitiosAuditoria	=	datos_auditoriasSG.SITIOS_AUDITAR;
				*/
			}
			else{
				
			}
	
}
/*================================================================*/

/*============================================================================================*/
//GENERAR NOTIFICACION
  $scope.modal_generar_notificacion = function(id_servicio,id_sce,id_ta,ciclo){
	    $scope.formDataGeneraNotificacionPDF.cmbDomicilioNotificacionPDF = "";
	    $scope.get_notificacion(id_servicio,id_sce,id_ta,ciclo);
		$("#inputIdSCE").val(id_sce);
		$("#inputIdTA").val(id_ta);
		$("#inputCiclo").val(ciclo);
		$("#inputServicio").val(id_servicio);
		$("#inputSave").val('');
		$("#inputNombreUsuario").val(sessionStorage.getItem("nombre_usuario"));
    $('#modalGeneraNotificacion').modal('show');
  }

// Agredar notas adicionales
$scope.addNote = function(value)
{
	if(typeof value !== "undefined" && value.length > 0)
	{
		$scope.notas[$scope.countnotas++] =value ;
		$scope.formDataGeneraNotificacionPDF.txtNotaPDF = "";
		$('#txtNotaPDF').focus();
	}

}
// Agredar notas adicionales
$scope.deleteNote = function(pos)
{
	var aux = [];
	var cont = 0;
	$("#inputNotas").val("");
	$.each($scope.notas,function(indice,elemento) {
		if(indice!=pos)
		{
			aux[cont++]= elemento;

		}
	}) ;
	$scope.notas = aux ;
	$scope.countnotas = cont;

}
  
 //	FUNCIONES PARA OBTENER LOS DOMICILIOS DEL CLIENTE 
$scope.get_notificacion	= function(id_servicio,id_sce,id_ta,ciclo){
	$scope.notas = [];
	$scope.countnotas = 0;
  	var noti = {
		ID_SCE:id_sce,
		ID_TA:id_ta,
		CICLO:ciclo,
		SERVICIO:id_servicio,
	};
  $http.post(global_apiserver + "/servicio_cliente_etapa/getNotas/",JSON.stringify(noti))
	.then(function( response ){
		$scope.saveNotas = response.data.NOTAS.map(function(item){
			$scope.notas[$scope.countnotas++] = item.NOTA;
			return item.NOTA;

		});
		$scope.get_domicilio_cliente($scope.id_servicio_cliente_etapa,response.data.DOMICILIO);
	});
	
  }
//	FUNCIONES PARA OBTENER LOS DOMICILIOS DEL CLIENTE
	$scope.get_domicilio_cliente	= function(id_cliente,id_domicilio){
		$http.get(  global_apiserver + "/servicio_cliente_etapa/getDomicilioByIDSCE/?id="+id_cliente)
			.then(function( response ){
				$scope.Domicilios	= response.data;
				$scope.formDataGeneraNotificacionPDF.cmbDomicilioNotificacionPDF = id_domicilio;
			});
	}
 $scope.validar_chck = function()
 {
	 if(typeof $scope.formDataGeneraNotificacionPDF.chckIMNC !== "undefined" && $scope.formDataGeneraNotificacionPDF.chckIMNC)
	 {
		 $scope.chck_error = false;
	 	return true;
	 }
	 if(typeof $scope.formDataGeneraNotificacionPDF.chckSitio !== "undefined" && $scope.formDataGeneraNotificacionPDF.chckSitio)
	 {
		 $scope.chck_error = false;
	 	return true;
	 }
		$scope.chck_error = true;
 	return false;
 }
  
 $scope.submitFormGeneraNotificacionPDF = function (save) {
	 var diff1 = [];
	 var diff2 = [];
  	if(typeof $scope.formDataGeneraNotificacionPDF.txtNotaPDF !== "undefined" && $scope.formDataGeneraNotificacionPDF.txtNotaPDF.length > 0)
		{
			$scope.addNote($scope.formDataGeneraNotificacionPDF.txtNotaPDF);
		}
	     $("#inputSave").val("");
		 if(typeof save !== "undefined")
		 {
			 $("#inputSave").val(save);
		 }

		 diff1 = $($scope.saveNotas).not($scope.notas).get();
		 diff2 = $($scope.notas).not($scope.saveNotas).get();
		 $("#inputNotas").val($scope.notas.join("<|>"));
		 $("#inputNotasEdit").val(diff2.concat(diff1).join("<|>"));
		 $("#inputDomicilio").val($scope.formDataGeneraNotificacionPDF.cmbDomicilioNotificacionPDF);


	    if($scope.validar_chck())
		{
			window.open('', 'VentanaNotificacionPDF');
			document.getElementById('exampleFormGeneraNotificacionPDF').submit();
			$scope.saveNotas = [];
			$.each($scope.notas,function (index,element) {
				$scope.saveNotas[index]=element;
			})
			//$scope.get_notificacion($("#inputServicio").val(),$("#inputIdSCE").val(),$("#inputIdTA").val(),$("#inputCiclo").val())
		}

  }

// ===========================================================================
// ===========================================================================
// ***** 	            INICIA SERVICIO CONTRATADO CIFA	            	 *****
// ===========================================================================

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL INSERTAR/MODIFICAR PARTICIPANTES		 *****
// =======================================================================================
    $scope.openModalInsertarModificarParticipante = function(accion,id) {
        $scope.modal_titulo_participante = "Agregar Participante";
        $scope.accion = accion;
        clear_modal_insertar_actualizar();

        if(accion == 'editar') {
            $scope.modal_titulo_participante = "Modificando Participante";

            $.getJSON(global_apiserver + "/sce_cifa_participantes/getById/?id=" + id, function (response) {
                $scope.formDataParticipante.razonSocial = response.RAZON_ENTIDAD;
                $scope.formDataParticipante.emailParticipante = response.EMAIL;
                $scope.formDataParticipante.telefonoParticipante = response.TELEFONO;
                $scope.formDataParticipante.curpParticipante = response.CURP;
                $scope.formDataParticipante.rfcParticipante = response.RFC;
                $scope.formDataParticipante.estadoParticipante = response.ID_ESTADO;
                $scope.formDataParticipante.comercialParticipante = response.EJECUTIVO;
                $scope.$apply();
                $("#btnSaveParticipante").attr("id_participante",response.ID);
            });

        }
        $scope.cargarEstados();
        $("#modalInsertarActualizarParticipante").modal("show");
    }

// =======================================================================================================
// *****   Funcion para limpiar las variables del MODAL INSERTAR/MODIFICAR PARTICIPANTES			 *****
// =======================================================================================================
    function clear_modal_insertar_actualizar(){
        $scope.formDataParticipante.razonSocial = '';
        $scope.formDataParticipante.emailParticipante = '';
        $scope.formDataParticipante.telefonoParticipante = '';
        $scope.formDataParticipante.curpParticipante = '';
        $scope.formDataParticipante.rfcParticipante = '';
        $scope.formDataParticipante.estadoParticipante = '';
        $scope.formDataParticipante.comercialParticipante = "";

       /* $("#btnInstructor").attr("value","Selecciona un Instructor");
        $("#btnInstructor").attr("class", "form-control btn ");*/

        $("#razonSocialerror").text("");
        $("#emailParticipanteerror").text("");
        $("#telefonoParticipanteerror").text("");
        $("#curpParticipanteerror").text("");
        $("#rfcParticipanteerror").text("");
        $("#estadoParticipanteerror").text("");
        $("#comercialParticipanteerror").text("");

    }
// =======================================================================================================
// *****    Accion al presionar button Guardar  MODAL INSERTAR/MODIFICAR PARTICIPANTES			 *****
// =======================================================================================================
    $scope.submitFormParticipante = function (formDataParticipante) {
        validar_formulario();
        if($scope.respuesta == 1){
            if($scope.accion == "insertar")
            {
                insertarParticipante(formDataParticipante);
            }
            if($scope.accion == "editar")
            {
                editarParticipante(formDataParticipante);
            }


        }
    }

// =======================================================================================
// *****     Función para validar los campos del formulario antes de Guardar		 *****
// =======================================================================================
    function validar_formulario()
    {
        $scope.respuesta =  1;

        if(typeof $scope.formDataParticipante.razonSocial !== "undefined") {
        if ($scope.formDataParticipante.razonSocial.length == 0) {
            $scope.respuesta = 0;
            $("#razonSocialerror").text("No debe estar vacio");
        } else {
            $("#razonSocialerror").text("");
        }
        }else {
            $scope.respuesta = 0;
            $("#razonSocialerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.emailParticipante !== "undefined") {
        if ($scope.formDataParticipante.emailParticipante.length == 0) {
            $scope.respuesta = 0;
            $("#emailParticipanteerror").text("No debe estar vacio");
        } else {
        	if(validar_email($scope.formDataParticipante.emailParticipante))
			{
                $("#emailParticipanteerror").text("");
			}
        	else
			{
                $scope.respuesta = 0;
                $("#emailParticipanteerror").text("Correo electrónico inválido");
			}

        }
        }else {
            $scope.respuesta = 0;
            $("#emailParticipanteerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.telefonoParticipante !== "undefined") {
        if ($scope.formDataParticipante.telefonoParticipante.length == 0) {
            $scope.respuesta = 0;
            $("#telefonoParticipanteerror").text("No debe estar vacio");
        } else {
            if(validar_telefono($scope.formDataParticipante.telefonoParticipante))
            {
                $("#telefonoParticipanteerror").text("");
            }
            else
            {
                $scope.respuesta = 0;
                $("#telefonoParticipanteerror").text("Número de telefono inválido");
            }

        }
        }else {
            $scope.respuesta = 0;
            $("#telefonoParticipanteerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.curpParticipante !== "undefined") {
            if ($scope.formDataParticipante.curpParticipante.length == 0) {
                $scope.respuesta = 0;
                $("#curpParticipanteerror").text("No debe estar vacio");
            } else {

                if($scope.curpValida())
                {
                    $("#curpParticipanteerror").text("");
                }
                else
                {
                    $scope.respuesta = 0;
                    $("#curpParticipanteerror").text("CURP inválido");
                }
            }
        }else {
            $scope.respuesta = 0;
            $("#curpParticipanteerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.rfcParticipante !== "undefined") {
            if ($scope.formDataParticipante.rfcParticipante.length == 0) {
                $scope.respuesta = 0;
                $("#rfcParticipanteerror").text("No debe estar vacio");
            } else {
                if($scope.validar_rfc())
                {
                    $("#rfcParticipanteerror").text("");
                }
                else
                {
                    $scope.respuesta = 0;
                    $("#rfcParticipanteerror").text("RFC inválido");
                }

            }
        }else {
            $scope.respuesta = 0;
            $("#rfcParticipanteerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.estadoParticipante !== "undefined") {
            if ($scope.formDataParticipante.estadoParticipante.length == 0) {
                $scope.respuesta = 0;
                $("#estadoParticipanteerror").text("No debe estar vacio");
            } else {
                $("#estadoParticipanteerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#estadoParticipanteerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataParticipante.comercialParticipante !== "undefined") {
            if ($scope.formDataParticipante.comercialParticipante.length == 0) {
                $scope.respuesta = 0;
                $("#comercialParticipanteerror").text("No debe estar vacio");
            } else {
                $("#comercialParticipanteerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#comercialParticipanteerror").text("No debe estar vacio");
        }




    }

// =======================================================================================
// *****                       Función para validar CURP                    		 *****
// =======================================================================================
     $scope.curpValida = function() {
    	var curp = $scope.formDataParticipante.curpParticipante;
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0\d|1[0-2])(?:[0-2]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
            validado = curp.match(re);

        if (!validado)  //Coincide con el formato general?
            return false;

        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma= lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if(lngDigito == 10)
                return 0;
            return lngDigito;
        }
        if (validado[2] != digitoVerificador(validado[1]))
            return false;

        return true; //Validado
    }
// =======================================================================================
// *****               Función para validar RFC        		 *****
// =======================================================================================
    $scope.validar_rfc = function()
    {
        var valor = $scope.formDataParticipante.rfcParticipante;
        valor = eliminaEspacios(valor);
            reg=/^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
        if(valor.length == 13)
        	reg=/^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))/;
        if(!reg.test(valor))
        {
            $scope.formDataParticipante.rfcParticipante = "";
            $("#rfcParticipante").focus();
            return false;
        }
        else
            return true;
    }
// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    $scope.validar_numeros = function()
    {
        var valor = $scope.formDataParticipante.telefonoParticipante;
        valor = eliminaEspacios(valor);
        reg=/(^[0-9]{1,12}$)/;
        if(!reg.test(valor))
        {
            $scope.formDataParticipante.telefonoParticipante = "";
        	$("#telefonoParticipante").focus();
            return false;
        }
        else
            return true;
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
    $scope.$watch('formDataParticipante.rfcParticipante',function(nuevo, anterior) {
        if(!nuevo)return;
        if($scope.formDataParticipante.rfcParticipante.length > 13)
            $scope.formDataParticipante.rfcParticipante = anterior;
    })

// =======================================================================================
// *****               Función para observar el campo del formulario         		 *****
// =======================================================================================
    $scope.$watch('formDataParticipante.telefonoParticipante',function(nuevo, anterior) {
        if(!nuevo)return;
        if(!$scope.validar_numeros())
            $scope.formDataParticipante.telefonoParticipante = anterior;
    })

// =======================================================================================
// *****               Función para validar que entren solo numeros         		 *****
// =======================================================================================
    function validar_telefono(telefono)
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
    function validar_email(email)
    {
        var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

        if (caract.test(email) == false){
          return false;
        }else{
		  return true;
        }
    }

// ===========================================================================
// ***** 			FUNCION PARA INSERTAR UN PARTICIPANTE				 *****
// ===========================================================================
    function insertarParticipante(formData) {


                var participante = {
                    ID_SERVICIO_CLIENTE_ETAPA: $scope.DatosServicio.ID,
                    RAZON_ENTIDAD: formData.razonSocial,
                    EMAIL: formData.emailParticipante,
                    TELEFONO: formData.telefonoParticipante,
                    CURP: formData.curpParticipante,
                    RFC:formData.rfcParticipante,
                    ESTADO:formData.estadoParticipante,
                    EJECUTIVO:formData.comercialParticipante,
                    ID_USUARIO:	sessionStorage.getItem("id_usuario"),
					ID_CURSO:$scope.DatosServicio.ID_CURSO,
                    CANTIDAD_PARTICIPANTES:$scope.DatosServicio.CANTIDAD_PARTICIPANTES


                };
                $.post(global_apiserver + "/sce_cifa_participantes/insert/", JSON.stringify(participante), function (respuesta) {
                    respuesta = JSON.parse(respuesta);
                    if (respuesta.resultado == "ok") {
                        $("#modalInsertarActualizarParticipante").modal("hide");
                        notify("Éxito", "Se ha insertado un nuevo Participante", "success");
                        $scope.cargarParticipantes($scope.DatosServicio.ID);
                    }
                    else {
                        notify("Error", respuesta.mensaje, "error");
                    }

                });


    }

// ===========================================================================
// ***** 			FUNCION PARA EDITAR UN PARTICIPANTE				 *****
// ===========================================================================
    function editarParticipante(formData) {
        var participante = {
            ID: $("#btnSaveParticipante").attr("id_participante"),
            RAZON_ENTIDAD: formData.razonSocial,
            EMAIL: formData.emailParticipante,
            TELEFONO: formData.telefonoParticipante,
            CURP: formData.curpParticipante,
            RFC:formData.rfcParticipante,
            ESTADO:formData.estadoParticipante,
            EJECUTIVO:formData.comercialParticipante,
            ID_USUARIO:	sessionStorage.getItem("id_usuario"),
            ID_SERVICIO_CLIENTE_ETAPA: $scope.DatosServicio.ID
        };
        $.post(global_apiserver + "/sce_cifa_participantes/update/", JSON.stringify(participante), function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta.resultado == "ok") {
                $("#modalInsertarActualizarParticipante").modal("hide");
                notify("Éxito", "Se ha editado el Participante", "success");
                $scope.cargarParticipantes($scope.DatosServicio.ID);
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });


    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS ESTADOSS		 *****
// ===================================================================
    $scope.cargarEstados = function(){
        $http.get(  global_apiserver + "/sce_cifa_participantes/getAllEstados")
            .then(function( response ){
                $scope.estados= response.data;
            });
    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS DATOS PARTICIPANTES		 *****
// ===================================================================
    $scope.cargarParticipantes = function(id){
        $http.get(  global_apiserver + "/sce_cifa_participantes/getAll/?id="+id)
            .then(function( response ){
                $scope.participantes = response.data;
            });
    }
// ===================================================================
// ***** 	  FUNCION PARA CARGAR LOS SITIOS DEL CLIENTE		 *****
// ===================================================================
    $scope.cargarSitios = function(){
        $http.get(  global_apiserver + "/sce_cifa_participantes/getSitiosByIdCliente/?id="+$scope.DatosServicio.ID_CLIENTE)
            .then(function( response ){
                $scope.sitios = response.data;
            });
    }
// ===================================================================
// ***** 	  FUNCION PARA TRAER DATOS FORM CONFIG		 *****
// ===================================================================
    $scope.traerConfiguracion = function(){
        $.getJSON( global_apiserver + "/sce_cifa_participantes/getSCECurso/?ID_SCE="+$scope.DatosServicio.ID+"&ID_CURSO="+$scope.DatosServicio.ID_CURSO, function( response ) {

            $scope.configuracion = response;
            $scope.formDataConfiguracion.selectSitio = $scope.configuracion.ID_SITIO;
            $scope.formDataConfiguracion.fecha_inicio_participante = $scope.configuracion.FECHA_INICIO;
            $scope.formDataConfiguracion.fecha_fin_participante = $scope.configuracion.FECHA_FIN;
            $scope.id_instructor =  $scope.configuracion.ID_INSTRUCTOR;

			if($scope.configuracion.ID_SITIO !== null)
			{
                $scope.flag = false;

			}
			else
                $scope.flag = true;

            $("#btnInstructor").attr("value",response.NOMBRE_INSTRUCTOR);
            $("#btnInstructor").attr("class", "form-control btn btn-primary");
            $scope.$apply();



        });
    }

// ===================================================================
// ***** 	  FUNCION PARA CARGAR DATOS CONFIGURACION		 *****
// ===================================================================
    $scope.cargarDatosConfiguracion = function(){
        onCalendario();
        clear_form_configuracion();
        $scope.cargarSitios();
        $scope.traerConfiguracion();

    }
// =======================================================================================================
// *****            Funcion para limpiar las variables del form Configuracion                 		 *****
// =======================================================================================================
    function clear_form_configuracion(){
        $scope.formDataConfiguracion.selectSitio = '';
        $scope.formDataConfiguracion.fecha_inicio_participante = '';
        $scope.formDataConfiguracion.fecha_fin_participante = '';
        $scope.id_instructor = "";
        $scope.formDataConfiguracion.chckVerTodos = "";
        $("#btnInstructor").attr("value","Selecciona un Instructor");
        $("#btnInstructor").attr("class", "form-control btn ");

		/* $("#btnInstructor").attr("value","Selecciona un Instructor");
		 $("#btnInstructor").attr("class", "form-control btn ");*/

        $("#selectSitioerror").text("");
        $("#fechainicioerror").text("");
        $("#fechafinerror").text("");
        $("#instructorerror").text("");

    }
// =======================================================================================================
// *****                 Accion al presionar button Guardar  form Configuracion                		 *****
// =======================================================================================================
    $scope.submitFormConfiguracion = function (formDataConfiguracion) {
        validar_formulario_configuracion();
        if($scope.respuesta == 1){
                validaEditarConfiguracion(formDataConfiguracion);
        }
    }
// =======================================================================================================
// *****                 Validar formulario configuracion                     		 *****
// =======================================================================================================
    function validar_formulario_configuracion() {
        $scope.respuesta =  1;

        if(typeof $scope.formDataConfiguracion.selectSitio !== "undefined") {
            if ($scope.formDataConfiguracion.selectSitio.length == 0) {
                $scope.respuesta = 0;
                $("#selectSitioerror").text("No debe estar vacio");
            } else {
                $("#selectSitioerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#selectSitioerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataConfiguracion.fecha_inicio_participante !== "undefined") {
            if ($scope.formDataConfiguracion.fecha_inicio_participante.length == 0) {
                $scope.respuesta = 0;
                $("#fechainicioerror").text("No debe estar vacio");
            } else {
                $("#fechainicioerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#fechainicioerror").text("No debe estar vacio");
        }

        if(typeof $scope.formDataConfiguracion.fecha_fin_participante !== "undefined") {
            if ($scope.formDataConfiguracion.fecha_fin_participante.length == 0) {
                $scope.respuesta = 0;
                $("#fechafinerror").text("No debe estar vacio");
            } else {
                $("#fechafinerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#fechafinerror").text("No debe estar vacio");
        }

        if(typeof $scope.id_instructor !== "undefined") {
            if ($scope.id_instructor.length == 0) {
                $scope.respuesta = 0;
                $("#instructorerror").text("No debe estar vacio");
            } else {
                $("#instructorerror").text("");
            }
        }else {
            $scope.respuesta = 0;
            $("#instructorerror").text("No debe estar vacio");
        }
    }

// ===========================================================================
// ***** 			FUNCION PARA VALIDAR EDITAR CONFIGURACION				 *****
// ===========================================================================
    function validaEditarConfiguracion(formData) {

        var validar = {
            ID:		          	        $scope.id_instructor,
            FECHAS:			            formData.fecha_inicio_participante+","+formData.fecha_fin_participante,
            ID_CURSO_INSITUS:           $scope.DatosServicio.ID
        };

        $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.disponible == "si") {
                editarConfiguracion(formData);
            }
            else
            {
                if (respuesta.disponible == "no") {
                    notify("Error", respuesta.razon, "error");
                    return false;

                }

            }
        })




    }
// ===========================================================================
// ***** 			FUNCION PARA VALIDAR EDITAR CONFIGURACION				 *****
// ===========================================================================
    function editarConfiguracion(formData) {
        var configuracion = {
            ID_SCE:$scope.DatosServicio.ID,
            ID_CURSO:$scope.DatosServicio.ID_CURSO,
            ID_SITIO: formData.selectSitio,
            FECHA_INICIO: formData.fecha_inicio_participante,
            FECHA_FIN: formData.fecha_fin_participante,
            ID_INSTRUCTOR: $scope.id_instructor,
            ID_USUARIO:	sessionStorage.getItem("id_usuario"),
        };
        $.post(global_apiserver + "/sce_cifa_participantes/updateConfiguracion/", JSON.stringify(configuracion), function (respuesta) {
            respuesta = JSON.parse(respuesta);

            if (respuesta.resultado == "ok") {
                notify("Éxito", "Se ha guardado la Configuracion", "success");
                $scope.traerConfiguracion();
                $scope.flag = false;
            }
            else {
                notify("Error", respuesta.mensaje, "error");
            }

        });
	}

// ===========================================================================
// ***** 	    FUNCION PARA CARGAR LOS DATEPICKER DEL MODAL			 *****
// ===========================================================================
    function onCalendario() {

        var dateInicial = $('#fecha_inicio_participante').datepicker({
            dateFormat: "dd/mm/yy",
            minDate: "+0D",
            language: "es",
            onSelect: function (dateText, ins) {
                $scope.formDataConfiguracion.fecha_inicio_participante = dateText;
                dateFinal.datepicker("option", "minDate", dateText)
            }
        }).css("display", "inline-block");

        var dateFinal =$('#fecha_fin_participante').datepicker({
            dateFormat: "dd/mm/yy",
            language: "es",
            minDate: "+0D",
            onSelect: function (dateText, ins) {
                $scope.formDataConfiguracion.fecha_fin_participante = dateText;
            }
        }).css("display", "inline-block");
    }

// =======================================================================================
// ***** 			FUNCION PARA ABRIR MODAL MOSTRAR INSTRUCTOR             		 *****
// =======================================================================================
    $scope.openModalMostarInst = function() {
        $scope.formDataConfiguracion.searchText = "";
            $("#instructorerror").text("");
            if ( $scope.formDataConfiguracion.fecha_inicio_participante.length != 0 && $scope.formDataConfiguracion.fecha_fin_participante.length != 0) {
                $("#modal-size").attr("class","modal-dialog modal-lg");


                $scope.cargarInstructores($scope.DatosServicio.ID_CURSO);
                $("#instructorerror").text("");


                $("#modalSelectInstructor").modal("show");

            }
            else {
                $("#instructorerror").text("Debe seleccionar las fechas");
            }



    }

// ===========================================================================
// ***** 		      Funcion button select instructor 	            	 *****
// ===========================================================================
    $scope.onSelectInstructor = function(instructor)
    {
        var validar = {
            ID:		          	        instructor,
            FECHAS:			            $scope.formDataConfiguracion.fecha_inicio_participante+","+$scope.formDataConfiguracion.fecha_fin_participante
        };
        $("#btn-"+instructor).attr("disabled",true);
        $("#btn-"+instructor).text("verificando...");
        $.post( global_apiserver + "/personal_tecnico/isDisponible/", JSON.stringify(validar), function(respuesta){
            respuesta = JSON.parse(respuesta);
            if (respuesta.disponible == "si") {
            	var validar_2 = {
            		ID_CLIENTE:$scope.DatosServicio.ID_CLIENTE,
            		ID_AUDITOR:instructor,
				    FECHA:$scope.formDataConfiguracion.fecha_fin_participante
				}
                $.post( global_apiserver + "/personal_tecnico/getAuditotiasClienteEn2Annos/", JSON.stringify(validar_2), function(response){
                	response = JSON.parse(response);
                    if (response.disponible == "si") {
                        $scope.id_instructor = instructor;
                        $("#btnInstructor").attr("value", $("#lb-"+instructor).val());
                        $("#btnInstructor").attr("class", "form-control btn btn-primary");
                        $("#btn-"+instructor).attr("disabled",false);
                        $("#btn-"+instructor).text("seleccionar");
                        $("#modalSelectInstructor").modal("hide");
					}else
                    {
                        if (response.disponible == "no")
                        {
                            notify("Error", respuesta.razon, "error");
                            $("#btn-"+instructor).attr("disabled",true);
                            $("#error-"+instructor).text(respuesta.razon);
                            $("#error-"+instructor).show();
                            $("#btn-"+instructor).text("seleccionar");
                        }else
                        {
                            notify("Error", respuesta.mensaje, "error");
                            $("#btn-"+instructor).attr("disabled",false);
                            $("#btn-"+instructor).text("seleccionar");
                        }

                    }


                })


            }
            else
            {
                if (respuesta.disponible == "no")
                {
                    notify("Error", respuesta.razon, "error");
                    $("#btn-"+instructor).attr("disabled",true);
                    $("#error-"+instructor).text(respuesta.razon);
                    $("#error-"+instructor).show();
                    $("#btn-"+instructor).text("seleccionar");
                }else
                {
                    notify("Error", respuesta.mensaje, "error");
                    $("#btn-"+instructor).attr("disabled",false);
                    $("#btn-"+instructor).text("seleccionar");
                }

            }
        })
    }

// ===================================================================
// ***** 			FUNCION PARA CARGAR LOS INSTRUCTORES    	 *****
// ===================================================================
    $scope.cargarInstructores= function(seletcCurso){
        var option = "no";
        if($scope.formDataConfiguracion.chckVerTodos)
            option = "si";
        $http.get(global_apiserver + "/cursos_programados/getInstructores/?id="+seletcCurso+"&option="+option)
            .then(function( response ){
                $scope.instructoresCursos = response.data;
            });
    }

$scope.showFormConfiguracion = function () {
	$scope.flag = true;
}
// ===========================================================================
// ***** 	            TERMINA  SERVICIO CONTRATADO CIFA	           	 *****
// ===========================================================================
// ===========================================================================
// ===========================================================================
// ***** 	          		 INICIA DICTAMINACION			           	 *****
// ===========================================================================
// ===========================================================================
//	FUNCION PARA EL MODAL
  $scope.modal_dictaminacion = function(id_sce,id_ta,ciclo){
		
		$scope.formDataDictaminacion = {};
		$scope.formDataDictaminacion.inputDictIdSCE = id_sce;
		$scope.formDataDictaminacion.inputDictIdTA = id_ta;
		$scope.formDataDictaminacion.inputDictCiclo = ciclo;
		$("#inputIdSCE").val(id_sce);
		$("#inputIdTA").val(id_ta);
		$("#inputCiclo").val(ciclo);
		$scope.get_domicilio_cliente($scope.id_servicio_cliente_etapa);

  
    $('#modalDictaminacion').modal('show');
  }
  
	
/*		
		FUNCION PARA CARGAR LOS DICTAMINADORES
*/
$scope.funcionDictaminadores = function(id_ts){
	$.ajax({
		type:'GET',
		url:global_apiserver+"/dictaminador_tiposervicio/getTipoServicio/?id_ts="+id_ts,
		success:function(data){
			$scope.$apply(function(){
				$scope.Dictaminadores=angular.fromJson(data);
			})

		}
	});
}
/*
		Esta función nos sirve para insertar los datos en la tabla dictaminaciones.
	*/
	$scope.submitFormDictaminacion = function(formDataDictaminacion) {		
	
			
				var dictaminaciones = {
					ID_SCE:	$scope.formDataDictaminacion.inputDictIdSCE,
					ID_TA:	$scope.formDataDictaminacion.inputDictIdTA,
					CICLO:	$scope.formDataDictaminacion.inputDictCiclo, 
					ID_DICTAMINADOR:	$scope.formDataDictaminacion.Dictaminador,
					ID_USUARIO_CREACION : sessionStorage.getItem("id_usuario"),
					ID_USUARIO_MODIFICACION : 0
				};
				if($scope.DatosServicio.ID_SERVICIO == 1){
					$scope.estatusAuditoria = $scope.DatosAuditoriasSG; // Para guardar cuáles estaban abiertas y cuáles cerradas
				}	
				if($scope.DatosServicio.ID_SERVICIO == 2 || $scope.DatosServicio.ID_SERVICIO == 4){	
					$scope.estatusAuditoria = $scope.DatosAuditoriasEC; // Para guardar cuáles estaban abiertas y cuáles cerradas
				}
				$.post(global_apiserver + "/dictaminaciones/insert/", JSON.stringify(dictaminaciones), function(respuesta){
					respuesta = JSON.parse(respuesta);
					if (respuesta.resultado == "ok") {
						
						notify("Éxito", "Se han enviado la auditoria a dictaminar","success");
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
						cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
					}
					 else {
							notify('Error',respuesta.mensaje,'error');
					}
					$('#modalDictaminacion').modal("hide");
				});
			
	
		
		
	};
// ===========================================================================
// ***** 	          		 TERMINA DICTAMINACION			           	 *****
// ===========================================================================
// ===========================================================================
// ===========================================================================
// ***** 	          		 INICIA GASTOS AUDITORIA		           	 *****
// ===========================================================================
// ===========================================================================
// ======================================================================
// *****			FUNCION CARGAR CATALOGO GASTOS					*****
// ======================================================================
function cargarCatalogoGastosAuditorias(){
	$http.get(  global_apiserver + "/i_cat_auditorias_costos/getAll/")
		.then(function( response ){
            $scope.CATALOGO_GASTOS = response.data;
			
		});
		
    }
// ======================================================================
// *****			FUNCION CARGAR GASTOS AUDITORIAS				*****
// ======================================================================
function cargarGastosAuditorias(id_servicio){
	if ($scope.GastosAuditorias) {
		$scope.estatusGastosAuditoria = $scope.GastosAuditorias;
	}
	$http.get(  global_apiserver + "/i_auditorias_costos/getAllByIdServicio/?id="+id_servicio)
		.then(function( response ){
            $scope.GastosAuditorias = response.data;
			
			const temp = [];
			let auditoriaSalvada;
			$scope.GastosAuditorias.AUDITORIAS.forEach(auditoria => {
				if ($scope.estatusGastosAuditoria) {
					auditoriaSalvada = $scope.estatusGastosAuditoria.AUDITORIAS.find(aud => {
						return aud.ID_SERVICIO_CLIENTE_ETAPA == auditoria.ID_SERVICIO_CLIENTE_ETAPA && aud.TIPO_AUDITORIA == auditoria.TIPO_AUDITORIA && aud.CICLO == auditoria.CICLO;
					});
				}
				
				let mostrar = false;
				if (auditoriaSalvada) {
					mostrar = auditoriaSalvada.mostrandoSectoresGastosAuditor;
					
				}
				temp.push({
					mostrandoSectoresGastosAuditor: mostrar,
					...auditoria
				});
			});
			$scope.GastosAuditorias.AUDITORIAS = temp;
			
		});
		
    }
// =======================================================================
// ***** 		FUNCION PARA EL BOTON AGREGAR EDITAR GASTOS			 *****
// =======================================================================
$scope.agregar_editar_gastos	=	function(aud_o_exptec,id_servicio_cliente_etapa,id_ta,ciclo,id_pt)	{
	
	clear_modal_gastos();
	$scope.accion_gastos	=	'editar';
	$scope.aud_o_exptec	=	aud_o_exptec;
	if($scope.accion_gastos == 'insertar'){
		$scope.modal_titulo_gastos = "INSERTAR GASTOS DE SERVICIO";
	}
	if($scope.accion_gastos == 'editar'){
		$scope.modal_titulo_gastos = "EDITAR GASTOS DE SERVICIO";
		llenar_modal_gastos(id_servicio_cliente_etapa,id_ta,ciclo,id_pt);
		$scope.formDataGastosAud.TA = id_ta;
		$scope.formDataGastosAud.CICLO = ciclo;
		$scope.formDataGastosAud.ID_PT = id_pt;
	}
	$("#modalInsertarActualizarGastosAuditoria").modal("show");
}
// ===========================================================================
// ***** 		FUNCION PARA EL BOTON GUARDAR DEL MODAL	GASTOS AUDITORIAS *****
// ===========================================================================
	$scope.submitFormGastosAud = function (formDataGastosAud) {
						
		var input = "";	
		input = JSON.stringify(formDataGastosAud.input);
			var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				TA	:	$scope.formDataGastosAud.TA,
				CICLO: $scope.formDataGastosAud.CICLO,
				ID_PT	:	$scope.formDataGastosAud.ID_PT,
				INPUT	:	input,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
		if($scope.accion_gastos == 'insertar'){
			
			$http.post(global_apiserver + "/i_auditorias_costos/insert/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		 }
		if($scope.accion_gastos == 'editar'){	
			
			$http.post(global_apiserver + "/i_auditorias_costos/update/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   
					cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
				   
		
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		}
		$("#modalInsertarActualizarGastosAuditoria").modal("hide");
		
	};
// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal gastos		 *****
// ===========================================================================
function clear_modal_gastos(){
$scope.formDataGastosAud.input = {};
$scope.formDataGastosAud.TA ="";	
$scope.formDataGastosAud.CICLO ="";
$scope.formDataGastosAud.ID_PT = "";
	
}
// ===========================================================================
// ***** 		Funcion para llenar las variables del modal gastos		 *****
// ===========================================================================
function llenar_modal_gastos(id_servicio_cliente_etapa,id_ta,ciclo,id_pt){
		datos_Auditorias = "";
		datos_Auditores = "";
		var datos_Auditorias	=	$scope.GastosAuditorias.AUDITORIAS.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_ta && element.CICLO == ciclo )
			});
			if(typeof datos_Auditorias != 'undefined'){
				if($scope.aud_o_exptec == 'auditor'){
					var datos_Auditores	=	datos_Auditorias.AUDITORES.find(function(element,index,array){
						return (element.ID_PERSONAL_TECNICO_CALIF == id_pt )
					});
				}	
				if($scope.aud_o_exptec == 'exptec'){
					var datos_Auditores	=	datos_Auditorias.EXP_TECNICOS.find(function(element,index,array){
						return (element.ID_PERSONAL_TECNICO_CALIF == id_pt )
					});
				}	
				if(typeof datos_Auditores != 'undefined'){
					
						$scope.formDataGastosAud.input	= datos_Auditores.MONTO;
					
				}
				
					
				
			}
	
	
	
}
// =======================================================================
// ***** 		FUNCION PARA EL BOTON AGREGAR EDITAR VIATICOS		 *****
// =======================================================================
$scope.agregar_editar_viaticos	=	function(id_servicio_cliente_etapa,id_ta,ciclo)	{
	
	clear_modal_viaticos();
	$scope.accion_viaticos	=	'editar';
	if($scope.accion_viaticos == 'insertar'){
		$scope.modal_titulo_viaticos = "INSERTAR VIATICOS DE SERVICIO";
	}
	if($scope.accion_viaticos == 'editar'){
		$scope.modal_titulo_viaticos = "EDITAR VIATICOS DE SERVICIO";
		llenar_modal_viaticos(id_servicio_cliente_etapa,id_ta,ciclo);
		$scope.formDataViaticosAud.TA = id_ta;
		$scope.formDataViaticosAud.CICLO = ciclo;
		
	}
	$("#modalInsertarActualizarViaticosAuditoria").modal("show");
}
// ===========================================================================
// ***** 		Funcion para limpiar las variables del modal viaticos	 *****
// ===========================================================================
function clear_modal_viaticos(){
	$scope.formDataViaticosAud.MONTO = "";
	$scope.formDataViaticosAud.TA ="";	
	$scope.formDataViaticosAud.CICLO ="";
	

}
// ===========================================================================
// ***** 		Funcion para llenar las variables del modal gastos		 *****
// ===========================================================================
function llenar_modal_viaticos(id_servicio_cliente_etapa,id_ta,ciclo,id_pt){
		datos_Auditorias = "";
		datos_Auditores = "";
		var datos_Auditorias	=	$scope.GastosAuditorias.AUDITORIAS.find(function(element,index,array){
				return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_ta && element.CICLO == ciclo )
			});
			if(typeof datos_Auditorias != 'undefined'){
				
					
						$scope.formDataViaticosAud.MONTO	= datos_Auditorias.TOTAL_VIATICOS;
					
				
			}
	
	
	
}
// ===========================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	VIATICOS AUDITORIAS *****
// ===========================================================================
	$scope.submitFormViaticosAud = function (formDataViaticosAud) {
						
			var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				TA	:	$scope.formDataViaticosAud.TA,
				CICLO: $scope.formDataViaticosAud.CICLO,
				MONTO	:	$scope.formDataViaticosAud.MONTO,
				ID_USUARIO:	sessionStorage.getItem("id_usuario")
			};
		if($scope.accion_viaticos == 'insertar'){
			
			$http.post(global_apiserver + "/i_auditorias_viaticos/insert/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
					cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		 }
		if($scope.accion_viaticos == 'editar'){	
			
			$http.post(global_apiserver + "/i_auditorias_viaticos/update/",datos).
            then(function(response){
                if(response){
					notify('&Eacutexito','Se han actualizado los datos','success');
                   
					cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
				   
                }
                else{
                    notify('Error','No se pudo guardar los cambios','error');
                }
                
            });
		}
		$("#modalInsertarActualizarViaticosAuditoria").modal("hide");
		
	};
	// FUNCION PARA MOSTRAR U OCULTAR LOS DATOS DE GASTOS AUDITORES
	$scope.changePrueba = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){

	var datos_auditoriasSG	=	$scope.GastosAuditorias.AUDITORIAS.find(function(element,index,array){
		return (element.ID_SERVICIO_CLIENTE_ETAPA == id_servicio_cliente_etapa && element.TIPO_AUDITORIA  == id_tipo_auditoria && element.CICLO == ciclo)
	});
	
	if (datos_auditoriasSG) {
		const index = $scope.GastosAuditorias.AUDITORIAS.indexOf(datos_auditoriasSG);
		$scope.GastosAuditorias.AUDITORIAS[index].mostrandoSectoresGastosAuditor = !$scope.GastosAuditorias.AUDITORIAS[index].mostrandoSectoresGastosAuditor;
	}
}	
// ===========================================================================
// ***** 	          		 TERMINA GASTOS AUDITORIA		           	 *****
// ===========================================================================
// ===========================================================================
DatosServicioContratado($scope.id_servicio_cliente_etapa);
cargarValoresMetaDatosServicio($scope.id_servicio_cliente_etapa);
cargarSectoresServicio($scope.id_servicio_cliente_etapa);
cargarSitiosECServicio($scope.id_servicio_cliente_etapa);
cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
cargarDatosAuditoriasEC($scope.id_servicio_cliente_etapa);
//cargarGastosAuditorias($scope.id_servicio_cliente_etapa);
cargarCatalogoGastosAuditorias();
$scope.cargarParticipantes($scope.id_servicio_cliente_etapa);

//cargarRolesAuditor();
//cargarOpcionesSelectMetaDatos();

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
