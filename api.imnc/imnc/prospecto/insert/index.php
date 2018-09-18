<?php  
include  '../../ex_common/query.php';
include '../../ex_common/funciones.php';
include '../../ex_common/archivos.php';
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
	$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
	$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP
	
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$ID_Prospecto = $ID;

	$NOMBRE = $objeto->NOMBRE; 
	valida_parametro_and_die1($NOMBRE, "Es necesario capturar un nombre");
	$RFC= $objeto->RFC;
    if(!$RFC)
    {
        $RFC = " ";
    }
    $GIRO=$objeto->GIRO;
	if(!$GIRO)
    {
        $GIRO = " ";
    }
    /*
	$TIPO_SERVICIO = $objeto->TIPO_SERVICIO;
	valida_parametro_and_die1($TIPO_SERVICIO, "Es necesario capturar el tipo de servicio");
	*/
    $ID_CLIENTE=$objeto->ID_CLIENTE;
	valida_parametro_and_die1($ID_CLIENTE, "Es necesario capturar el cliente");
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$ACTIVO = $objeto->ACTIVO;
	valida_parametro_and_die1($ACTIVO, "Es necesario seleccionar si está activo");
	$ORIGEN = $objeto->ORIGEN;
	valida_parametro_and_die1($ORIGEN, "Es necesario seleccionar un origen");
	$COMPETENCIA =$objeto->COMPETENCIA;
	if(!$COMPETENCIA)
    {
        $COMPETENCIA = 0;
    }
	$ESTATUS_SEGUIMIENTO =$objeto->ESTATUS_SEGUIMIENTO;
	valida_parametro_and_die1($ESTATUS_SEGUIMIENTO, "Es necesario seleccionar un estatus");
	$TIPO_CONTRATO =$objeto->TIPO_CONTRATO;
	valida_parametro_and_die1($TIPO_CONTRATO, "Es necesario seleccionar un tipo de contrato");
	$ID_USUARIO_PRINCIPAL = $objeto->ID_USUARIO;
	$ID_USUARIO_SECUNDARIO = $objeto->ID_USUARIO_SECUNDARIO;
	valida_parametro_and_die1($ID_USUARIO_SECUNDARIO, "Es necesario seleccionar u usuario secundario");
	/*
	$DEPARTAMENTO = $objeto->DEPARTAMENTO;
	valida_parametro_and_die1($DEPARTAMENTO, "Es necesario seleccionar un departamento");
	*/
	
	$consulta = "INSERT INTO PROSPECTO 
	(ID, ID_CLIENTE, RFC, NOMBRE,  GIRO, FECHA_CREACION,USUARIO_CREACION, FECHA_MODIFICACION, USUARIO_MODIFICACION, ACTIVO,
	ORIGEN,ID_COMPETENCIA,ID_ESTATUS_SEGUIMIENTO, ID_TIPO_CONTRATO,ID_USUARIO_PRINCIPAL,ID_USUARIO_SECUNDARIO,ID_PORCENTAJE) VALUES
	(".$ID.",".$ID_CLIENTE.",'".$RFC."','".$NOMBRE."','".$GIRO."','".$FECHA_CREACION."',
	".$ID_USUARIO_CREACION.",'".$FECHA_MODIFICACION."',".$ID_USUARIO_MODIFICACION.",".$ACTIVO.",".$ORIGEN.",".$COMPETENCIA.","
	.$ESTATUS_SEGUIMIENTO.",".$TIPO_CONTRATO.",".$ID_USUARIO_PRINCIPAL.",".$ID_USUARIO_SECUNDARIO.",3);";
	
	
	$database->query($consulta); 
	
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok";
	$respuesta["id"]=$ID; 
	/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
	
	
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
		
	
	/*////////////////////////////////////////////////////////////////////////////////////////////*/

	creacion_expediente_registro($ID_Prospecto, 3, $rutaExpediente, $database);
	crea_instancia_expedientes_registro($ID_Prospecto, 3, $database);
	
	print_r(json_encode($respuesta)); 
?> 