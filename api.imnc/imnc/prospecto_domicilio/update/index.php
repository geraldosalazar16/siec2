<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_DOMICILIO";
	$correo = "galeanaisauro19@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);

    $ID=$objeto->ID_DOMICILIO;
    $NOMBRE=$objeto->NOMBRE;
    valida_parametro_and_die($NOMBRE, "Falta nombre del domicilio");
	$PAIS=$objeto->PAIS;
	valida_parametro_and_die($PAIS, "Falta seleccionar el país");
	$ESTADO=$objeto->ESTADO;
	valida_parametro_and_die($ESTADO, "Falta el estado");
	$MUNICIPIO=$objeto->MUNICIPIO;
	valida_parametro_and_die($MUNICIPIO, "Falta el municipio");
	$COLONIA=$objeto->COLONIA;
	valida_parametro_and_die($COLONIA, "Falta seleccionar la colonia");
    $CODIGO_POSTAL=$objeto->CODIGO_POSTAL;
    valida_parametro_and_die($CODIGO_POSTAL, "Falta seleccionar el código postal");
    $CALLE=$objeto->CALLE;
    valida_parametro_and_die($CALLE, "Falta la calle");
    $NUMERO_INTERIOR=$objeto->NUMERO_INTERIOR;
    $NUMERO_EXTERIOR=$objeto->NUMERO_EXTERIOR;
    valida_parametro_and_die($NUMERO_EXTERIOR, "Falta el número exterior");
    $FISCAL=$objeto->FISCAL;
    valida_parametro_and_die($ESTADO, "Falta seleccionar si es domicilio fiscal");
    $ACTIVO=$objeto->ACTIVO;
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
      
	$id = $database->update($nombre_tabla, [ 
	"NOMBRE"=>$NOMBRE,
	"PAIS"=>$PAIS,
	"ESTADO"=>$ESTADO,
	"MUNICIPIO"=>$MUNICIPIO,
	"COLONIA"=>$COLONIA,
	"CODIGO_POSTAL"=>$CODIGO_POSTAL,
	"CALLE"=>$CALLE,
	"NUMERO_INTERIOR"=>$NUMERO_INTERIOR,
    "NUMERO_EXTERIOR"=>$NUMERO_EXTERIOR,
    "FISCAL"=>$FISCAL,
	"FECHA_MODIFICACION"=>$FECHA_MODIFICACION,
	"USUARIO_MODIFICACION"=>$ID_USUARIO_MODIFICACION,
    "CENTRAL"=>$ACTIVO
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
