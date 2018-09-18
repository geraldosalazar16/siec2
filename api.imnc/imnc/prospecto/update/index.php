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
	
	$ID=$objeto->ID;
	$NOMBRE = $objeto->NOMBRE; 
	valida_parametro_and_die1($NOMBRE, "Es necesario capturar un nombre");
	//No obligatorio
	$RFC= $objeto->RFC;
	if($RFC== "" or is_null($RFC))
	{
        $RFC = "Sin asignar";
	}
	//No es obligatorio
    $GIRO=$objeto->GIRO;
    if($GIRO== "" or is_null($GIRO))
	{
        $GIRO = "Sin asignar";
	}
	
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$ACTIVO = $objeto->ACTIVO;
	valida_parametro_and_die1($ACTIVO, "Es necesario seleccionar si está activo");
	$ORIGEN = $objeto->ORIGEN;
	valida_parametro_and_die1($ORIGEN, "Es necesario seleccionar un origen");
	$COMPETENCIA =$objeto->COMPETENCIA;
	valida_parametro_and_die1($COMPETENCIA, "Es necesario seleccionar una competencia");
	$ESTATUS_SEGUIMIENTO =$objeto->ESTATUS_SEGUIMIENTO;
	valida_parametro_and_die1($ESTATUS_SEGUIMIENTO, "Es necesario seleccionar un estatus");
	$TIPO_CONTRATO =$objeto->TIPO_CONTRATO;
	valida_parametro_and_die1($TIPO_CONTRATO, "Es necesario seleccionar un tipo de contrato");
	$ID_USUARIO_SECUNDARIO = $objeto->USUARIO_SECUNDARIO;
	valida_parametro_and_die1($ID_USUARIO_SECUNDARIO, "Es necesario seleccionar un usuario secundario");
	$ID_USUARIO_PRINCIPAL = $objeto->ID_USUARIO;
	valida_parametro_and_die1($ID_USUARIO_PRINCIPAL, "Es necesario seleccionar un usuario principal");

      
	$id = $database->update($nombre_tabla, [ 
		"NOMBRE" => $NOMBRE, 
		"RFC" => $RFC,
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
		"USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
		"GIRO"=>$GIRO,
		"ACTIVO"=>$ACTIVO,
		"ORIGEN" => $ORIGEN,
		"ID_COMPETENCIA" => $COMPETENCIA,
		"ID_ESTATUS_SEGUIMIENTO" => $ESTATUS_SEGUIMIENTO,
		"ID_TIPO_CONTRATO" => $TIPO_CONTRATO,
		"ID_USUARIO_SECUNDARIO" => $ID_USUARIO_SECUNDARIO,
		"ID_USUARIO_PRINCIPAL" => $ID_USUARIO_PRINCIPAL,
	], ["ID"=>$ID]); 
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	
	/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
	$cons1	=	$database->get("PROSPECTO_ESTATUS_FECHAS","*",["ID_PROSPECTO"=>$ID]);
	if($cons1 == null){
		if($ESTATUS_SEGUIMIENTO==1){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS_SEGUIMIENTO==2){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')]);
		
		}
		if($ESTATUS_SEGUIMIENTO==3){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==4){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_FIRMADO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS_SEGUIMIENTO==5){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==6){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==7){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO]);
		
		}
		if($ESTATUS_SEGUIMIENTO==8){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')]);	
			
		}
		if($ESTATUS_SEGUIMIENTO==9){
			$cons2	=	$database->insert("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')]);	
		
		}
		
	}
	else{
		if($cons1["ID_ESTATUS_SEGUIMIENTO"] != $ESTATUS_SEGUIMIENTO){
			if($ESTATUS_SEGUIMIENTO==1){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			}
			if($ESTATUS_SEGUIMIENTO==2){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
					
			}
			if($ESTATUS_SEGUIMIENTO==3){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS_SEGUIMIENTO==4){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_FIRMADO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS_SEGUIMIENTO==5){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS_SEGUIMIENTO==6){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO],["ID"=>$cons1["ID"]]);
		
			}
			if($ESTATUS_SEGUIMIENTO==7){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO],["ID"=>$cons1["ID"]]);
		
			}
			if($ESTATUS_SEGUIMIENTO==8){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
			if($ESTATUS_SEGUIMIENTO==9){
				$cons3	=	$database->update("PROSPECTO_ESTATUS_FECHAS",["ID_PROSPECTO"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTATUS_SEGUIMIENTO, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')],["ID"=>$cons1["ID"]]);
			
			}
		}
		
		
	}
	/*////////////////////////////////////////////////////////////////////////////////////////////*/
	print_r(json_encode($respuesta)); 
?> 
