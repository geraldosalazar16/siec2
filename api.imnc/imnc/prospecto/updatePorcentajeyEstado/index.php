<?php  
	include  '../../ex_common/query.php';
function valida_parametro_and_die1($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro) or $parametro == "ninguno") { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
}	
	
	$nombre_tabla = "PROSPECTO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	
	$ID = $objeto->ID_PROSPECTO;
	valida_parametro_and_die1($ID, "Es necesario seleccionar un prospecto");
	$PORCENTAJE=$objeto->PORCENTAJE;
	valida_parametro_and_die1($PORCENTAJE, "Es necesario seleccionar el porcentaje");
	$ESTATUS=$objeto->ESTATUS;
	valida_parametro_and_die1($ESTATUS, "Es necesario seleccionar el estatus");

      
	$id = $database->update($nombre_tabla, [ 
		"ID_PORCENTAJE" => $PORCENTAJE,
		"ID_ESTATUS_SEGUIMIENTO" => $ESTATUS
	], ["ID"=>$ID]); 
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	
	/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
	$cons1	=	$database->get("PROSPECTO_ESTATUS_FECHAS","*",["ID_PROSPECTO"=>$ID]);
	if($cons1 == null){
		if($ESTATUS==1){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS==2){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS==3){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS]);
		
		}
		if($ESTATUS==4){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_FIRMADO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS==5){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS]);
		
		}
		if($ESTATUS==6){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS]);
		
		}
		if($ESTATUS==7){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS]);
		
		}
		if($ESTATUS==8){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS==9){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')]);	
		
		}
		
	}
	else{
		if($cons1["ID_ESTATUS_SEGUIMIENTO"] != $ESTATUS){
			if($ESTATUS==1){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			}
			if($ESTATUS==2){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
					
			}
			if($ESTATUS==3){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS==4){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_FIRMADO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS==5){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS==6){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS],["ID"=>$cons1["ID"]]);
		
			}
			if($ESTATUS==7){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS],["ID"=>$cons1["ID"]]);
		
			}
			if($ESTATUS==8){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS==9){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
		}
		
		
	}
	/*////////////////////////////////////////////////////////////////////////////////////////////*/
	
	print_r(json_encode($respuesta)); 
?> 
