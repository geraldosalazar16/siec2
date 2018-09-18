app.controller('ec_tipos_servicio_controller',['$scope','$http' ,function($scope,$http){
	
	$scope.titulo	= "Cargando....";
	$scope.modulo_permisos =  global_permisos["SERVICIOS"];
	$scope.formData = {};
	$scope.formDataSector = {};
	$scope.formDataSitiosEC = {};
	$scope.formDataSitio = {};
	$scope.formDataAuditoria = {};
	$scope.formDataGrupoAuditor	=	{};
	$scope.resp={};
	$scope.resp1={};
	$scope.prueba	= "PAGINA EN DESARROLLO";
	$scope.id_servicio_cliente_etapa = getQueryVariable("id_serv_cli_et");
	$scope.PrincipalSectores	=	{0:{ID:"S",NOMBRE:"Si"},1:{ID:"N",NOMBRE:"No"}};
	$scope.ocultar	=	false;
	var f = new Date();
	$scope.FechaHoy = f.getFullYear()+'-'+(f.getMonth()+1)+'-'+f.getDate();
	$scope.FechaPrueba = ["2018-10-5","2018-9-3"];
	$scope.txtInsertarFechas = new Array();
	$scope.txtFechasAuditoria={};
	$scope.txtInsertarFechasGrupo = {};
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
		$http.get(  global_apiserver + "/i_meta_sce/getByIdTipoServicio/?id="+id_tipos_servicio+"&norma="+norma)
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
			if($scope.DatosServicio.ID_TIPO_SERVICIO == 17){
				cargarMetaDatos($scope.DatosServicio.ID_TIPO_SERVICIO,$scope.DatosServicio.ID_NORMA);
				
			}
			else{
				cargarMetaDatos($scope.DatosServicio.ID_TIPO_SERVICIO,0);
			}
			cargarMetaDatosSitios($scope.DatosServicio.ID_TIPO_SERVICIO);
		});
	
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
		if($scope.DatosServicio.ID_SERVICIO == 2){
			llenar_modal_sitiosEC($scope.id_servicio_cliente_etapa,id);
		}
		//llenar_modal_sitio(id);
	}
	if($scope.DatosServicio.ID_SERVICIO == 1){
		
		$("#modalInsertarActualizarSitios").modal("show");
	}
	if($scope.DatosServicio.ID_SERVICIO == 2){
		
		//cargarMetaDatosSitios($scope.DatosServicio.ID_TIPO_SERVICIO);
		$("#modalInsertarActualizarSitiosEC").modal("show");
	}	
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
			$scope.DatosSitiosEC = response.data;
			
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
$scope.mostrarvalorselectsitios	= function(dat,ind){
		$http.get(  global_apiserver + "/i_opciones_select_metadatos_sitios/getById/?id="+dat)
						.then(function( response ){
							$scope.resp1[ind]	= response.data.OPCION;
			
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
	//}		
	//$scope.formDataSitioSG.cmbClaveClienteDomSitio = id_cliente_domicilio;
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
				
                if(response.data.resultado=="OK"){
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
                if(response.data.resultado=="OK"){
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
//========================================================================
// ***** 	FUNCION PARA CARGAR SITIOS que se pueden asignar a una auditoria		 *****
// =======================================================================
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
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
                $("#modalExplorarSitios").modal("hide");
            });
          
    }
    $scope.eliminar_sitio_auditoria = function(id_sce,id_tipo_auditoria,id_cliente_domicilio,ciclo){
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
                    $("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"sitios-auditoria").collapse("show");	
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
	
	cargarTiposAuditoria();
	cargarStatusAuditoria();
	clear_modal_auditorias();
	
	$scope.accion_auditoria	=	accion_auditoria;
	if($scope.accion_auditoria == 'insertar'){
		$scope.modal_titulo_auditoria = "INSERTAR AUDITORIAS";
		$scope.formDataAuditoria.CICLO = $scope.DatosServicio.CICLO;
	}
	if($scope.accion_auditoria == 'editar'){
		$scope.modal_titulo_auditoria = "EDITAR AUDITORIAS";
		$scope.formDataAuditoria.CICLO = ciclo;
		llenar_modal_AuditoriasSG($scope.id_servicio_cliente_etapa,id_tipo_auditoria)
		
	}
	$("#modalInsertarActualizarAuditoria").modal("show");
}
// ===========================================================================
// ***** Funcion para limpiar las variables del modal auditorias para SG *****
// ===========================================================================
function clear_modal_auditorias(){
	
	$scope.formDataAuditoria	=	{};
	
	
}
// ===========================================================================
// ***** 	Funcion para llenar las variables del modal AuditoriasSG		 *****
// ===========================================================================
function llenar_modal_AuditoriasSG(id_servicio_cliente_etapa,id_tipo_auditoria){

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
	
}
// =======================================================================
// ***** 	FUNCION PARA EL BOTON GUARDAR DEL MODAL	AUDITORIAS 		 *****
// =======================================================================
	$scope.submitFormAuditoria = function (formDataAuditoria) {
		
		if($scope.formDataAuditoria.chkNoMetodo!=true){
			$scope.formDataAuditoria.txtSitiosAuditoria = "";
			$scope.formDataAuditoria.chkNoMetodo = false;
		}
				
		var datos	=	{
				ID	:	$scope.id_servicio_cliente_etapa,
				DURACION_DIAS:	$scope.formDataAuditoria.txtDuracionAuditoria,
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
			
			$.each($scope.DatosAuditoriasSG, function( i, datos1 ) {
				$.each(datos1.AUDITORIA_FECHAS, function( j, datos ) {
						$scope.txtFechasAuditoria[datos.ID]=datos.FECHA.substring(0,4)+"-"+datos.FECHA.substring(4,6)+"-"+datos.FECHA.substring(6,8);
		 
					});
				});	
		});
		
    }
$scope.btnInsertaSitiosAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,id_cliente_domicilio,ciclo){
    cargarSitiosParaAuditoria(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo);
}
// ======================================================================
// *****				FUNCION TIPOS AUDITORIAS					*****
// ======================================================================
function cargarTiposAuditoria(){
	$http.get(  global_apiserver + "/i_sg_tipos_auditorias/getAll/")
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
//$scope.id_tipo_auditoria = id_tipo_auditoria;
//	$http.get(  global_apiserver + "/i_sg_auditoria_sitios/getBySgAuditoria/?id="+id_servicio_cliente_etapa+"&id_tipo_auditoria="+id_tipo_auditoria)
//		.then(function( response ){
			
//			$scope.objSitioAuditorias = response.data;
			$("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"-sitios-auditoria").collapse("show");	
//		});
}

//	=====================================================================
//	*****	FUNCION PARA AGREGAR O EDITAR FECHAS A UNA AUDITORIA	*****
//	=====================================================================
$scope.agregar_editar_fechasAuditoria = function(id_sce,id_tipo_auditoria,accion,ciclo,id){
	
		if(accion == 'insertar'){
			if( $scope.txtInsertarFechas[id_tipo_auditoria] != "" && typeof $scope.txtInsertarFechas[id_tipo_auditoria] != 'undefined' ){
				
				var datos	=	{
					ID_SERVICIO_CLIENTE_ETAPA:	id_sce,
					TIPO_AUDITORIA:	id_tipo_auditoria,
					CICLO: ciclo,
					FECHA:	$scope.txtInsertarFechas[id_tipo_auditoria].substring(0,4)+$scope.txtInsertarFechas[id_tipo_auditoria].substring(5,7)+$scope.txtInsertarFechas[id_tipo_auditoria].substring(8,10),
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
				$http.post(global_apiserver + "/i_sg_auditoria_fechas/insert/",datos).
					then(function(response){
					$scope.txtInsertarFechas[id_tipo_auditoria]="";
					if(response.data.resultado=="ok"){
						notify('&Eacutexito','Se han actualizado los datos','success');
						
						cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
						
					}
					else{
						notify('Error',response.data.mensaje,'error');
					}
               
				});
			}
			else{
				notify('Error',"Debe seleccionar una fecha para agregar",'error');
			}
		 }
		if(accion == 'editar'){	
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
					cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);

					
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
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

	var datos	=	{
					ID:	$scope.IdFechaEliminar,
					ID_USUARIO:	sessionStorage.getItem("id_usuario")
				};
			$http.post(global_apiserver + "/i_sg_auditoria_fechas/delete/",datos).
            then(function(response){
                if(response.data.resultado=="ok"){
					notify('&Eacutexito','Se han eliminado los datos','success');
					cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					$scope.IdFechaEliminar="";

					
                }
                else{
                    notify('Error',response.data.mensaje,'error');
					cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
					$scope.IdFechaEliminar="";
                }
               
            });
	 $("#modalConfirmacion").modal("hide");
	 
}

// ======================================================================
// *****		FUNCION PARA EL BOTON GRUPOS AUDITORIAS				*****
// ======================================================================
$scope.btnGrupoAuditoria = function(id_servicio_cliente_etapa,id_tipo_auditoria,ciclo){
	$("#collapse-"+id_servicio_cliente_etapa+"-"+id_tipo_auditoria+"-"+ciclo+"-grupo-auditoria").collapse("show");	
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
			$scope.AuditoresParaAuditoria = response.data.CON_CALIFICACION;
			$scope.AuditoresParaAuditoria1 = response.data.SIN_CALIFICACION;
			$scope.cant_auditores = $scope.AuditoresParaAuditoria.length;
			//$scope.grupo_id_tipo_auditoria = id_tipo_auditoria;
			//$scope.grupo_ciclo=ciclo;
            $("#modalExplorarGrupo").modal("show");
			//$("#modalInsertarActualizarGrupoAuditoria").modal("show");
		});
		
    }
// ======================================================================
// *****  FUNCION PARA CARGAR MODAL QUE GUARDA EL AUDITOR GRUPO		*****
// ======================================================================
$scope.cargarModalInsertarActualizarGrupoAuditor = function(id_pt_calif,nombre_completo){
	$scope.formDataGrupoAuditor.txtClavePTCalifGrupo = nombre_completo;
	$scope.formDataGrupoAuditor.idPTCalifGrupo = id_pt_calif;
	$("#modalExplorarGrupo").modal("hide");
    $("#modalInsertarActualizarGrupoAuditoria").modal("show");
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
                    cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
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
	
	var fecha = {
            ID_SERVICIO_CLIENTE_ETAPA:id_sce ,
            TIPO_AUDITORIA:	id_ta ,
			CICLO:	ciclo,
            ID_PERSONAL_TECNICO_CALIF: id_pt,
			FECHA:	$scope.txtInsertarFechasGrupo[id_pt].substring(0,4)+$scope.txtInsertarFechasGrupo[id_pt].substring(5,7)+$scope.txtInsertarFechasGrupo[id_pt].substring(8,10),
			ID_USUARIO:sessionStorage.getItem("id_usuario")
          };
	$http.post(global_apiserver + "/i_sg_auditoria_grupo_fechas/insert/",fecha).
            then(function(response){
			
                if(response.data.resultado=="ok"){
                    notify('&Eacutexito','Se ha insertado una nueva fecha','success');
                    cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
                }
                else{
                    notify('Error',response.data.mensaje,'error');
                }
               
            });
		$scope.txtInsertarFechasGrupo={};			
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
$scope.mostrarFecha = function(fecha){
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
		if(fechas[key].FECHA.substring(4,5)==0){
			mes = fechas[key].FECHA.substring(5,6);
		}
		else{
			mes = fechas[key].FECHA.substring(4,6);
		}
		if(fechas[key].FECHA.substring(6,7)==0)	{
			dia = fechas[key].FECHA.substring(7,8)
		}
		else{
			dia = fechas[key].FECHA.substring(6,8);
		}	
		fecha1[key] = ano+"/"+mes+"/"+dia;
		//fecha1[key] = fecha1[key].toString();
	}
	//var fecha1 = ["2018-9-3"];
	//var fecha2 = ['2018/10/19','2018-9-21'];//,"2018-10-11","2018-10-12"
	//fecha3= new Array(fecha1);
	return fecha1;
}

DatosServicioContratado($scope.id_servicio_cliente_etapa);
cargarValoresMetaDatosServicio($scope.id_servicio_cliente_etapa);
cargarSectoresServicio($scope.id_servicio_cliente_etapa);
cargarSitiosECServicio($scope.id_servicio_cliente_etapa);
cargarSitiosSGServicio($scope.id_servicio_cliente_etapa);
cargarTodosSitiosECServicio($scope.id_servicio_cliente_etapa);
cargarDatosAuditoriasSG($scope.id_servicio_cliente_etapa);
cargarRolesAuditor();
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
	  for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
		  return pair[1];
		}
	  } 
	  alert('Query Variable ' + variable + ' not found');
	}