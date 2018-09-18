<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_CONTACTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
    
    $ID_PROSPECTO= $objeto->ID_PROSPECTO;
    valida_parametro_and_die($ID_PROSPECTO, "Falta seleccionar un prospecto");
    $ID_PROSPECTO_DOMICILIO= $objeto->ID_PROSPECTO_DOMICILIO;
    valida_parametro_and_die($ID_PROSPECTO_DOMICILIO, "Falta seleccionar el domicilio");			
    $NOMBRE = $objeto->NOMBRE;
    valida_parametro_and_die($NOMBRE, "Falta nombre del contacto");
	$CORREO = $objeto->CORREO;
	valida_parametro_and_die($CORREO, "Falta el correo");
	
	if($CORREO !== " ")
    	if (!filter_var($CORREO, FILTER_VALIDATE_EMAIL)) {
    		imprime_error_and_die("El correo debe tener un formato válido (e.j. tu.nombre@tuempresa.com)");
    	}
    	
	$CORREO2 = $objeto->CORREO2;
    if($CORREO2 !== " ")
    	if (!filter_var($CORREO2, FILTER_VALIDATE_EMAIL)) {
    		imprime_error_and_die("El correo2 debe tener un formato válido (e.j. tu.nombre@tuempresa.com)");
    	}
    	
	$TELEFONO= $objeto->TELEFONO;
	valida_parametro_and_die($TELEFONO, "Falta el teléfono");
	if ($TELEFONO != " " && $TELEFONO != "" && (!is_numeric($TELEFONO) || intval($TELEFONO) < 0)) {
		imprime_error_and_die("Verifica que el teléfono sea un número y sea mayor o igual a cero");
	}
	$CELULAR= $objeto->CELULAR;
	$PUESTO= $objeto->PUESTO;
	valida_parametro_and_die($PUESTO, "Falta indicar el puesto");
	$DATOS_ADICIONALES = $objeto->DATOS_ADICIONALES;
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION;
	$ACTIVO = $objeto->ACTIVO;

	
	
	$id = $database->insert($nombre_tabla, [

		"ID"=> $ID,
		"ID_PROSPECTO" => $ID_PROSPECTO,
	    "ID_PROSPECTO_DOMICILIO"=> $ID_PROSPECTO_DOMICILIO,
	    "NOMBRE"=>$NOMBRE,
	    "CORREO"=>$CORREO,
	    "CORREO2"=>$CORREO2,
	    "TELEFONO"=>$TELEFONO,
	    "CELULAR"=>$CELULAR,
	    "PUESTO"=>$PUESTO,
        "FECHA_CREACION"=>$FECHA_CREACION,
	    "FECHA_MODIFICACION"=>$FECHA_MODIFICACION,
	    "USUARIO_CREACION"=>$ID_USUARIO_CREACION,
	    "USUARIO_MODIFICACION"=>$ID_USUARIO_MODIFICACION,
	    "ACTIVO"=>$ACTIVO,
	    "DATOS_ADICIONALES"=>$DATOS_ADICIONALES
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
