<?php  
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$error = $database->error();
		if($error[0] == '23000' && $error[1] == 1062){
			$respuesta['mensaje']="El registro que intenta ingresar ya existe";
		} else {
			$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		}
		print_r(json_encode($respuesta));
		die();
	}
}	
	
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	
	$ID_PROSPECTO = $objeto->id_prospecto;
	valida_parametro_and_die($ID_PROSPECTO,"Es necesario seleccionar un prospecto");
	$ID_SERVICIO=$objeto->area;
	valida_parametro_and_die($ID_SERVICIO,"Es necesario seleccionar un servicio");
	$ID_TIPO_SERVICIO = $objeto->departamento; 
	valida_parametro_and_die($ID_TIPO_SERVICIO,"Es necesario seleccionar un tipo de servicio");
    $NORMAS= "";
    $MODALIDAD = "";
    $CURSO = "";
	$CANTIDAD = "";
	$SOLO_CLIENTE = "";
    if($ID_SERVICIO!=3)
	{
		$NORMAS= $objeto->producto;
		if(count($NORMAS) == 0){
			$respuesta['resultado']="error";
			$respuesta['mensaje']="Es necesario seleccionar una norma";
			print_r(json_encode($respuesta));
			die();
		}
    }
    else
	{
        $MODALIDAD = $objeto->modalidad;
		valida_parametro_and_die($MODALIDAD,"Es necesario seleccionar una modalidad");
		
        $CURSO = $objeto->curso;
		valida_parametro_and_die($CURSO,"Es necesario seleccionar un curso");    
		    
		$SOLO_CLIENTE = $objeto->solo_cliente;
        if($MODALIDAD == 'programado')
			valida_parametro_and_die($SOLO_CLIENTE,"Es necesario definir opciones de participantes");
		
		$CANTIDAD = $objeto->cantidad;
		valida_parametro_and_die($CANTIDAD,"Es necesario intruducir la cantidad de personas");
	}
	$ALCANCE= $objeto->alcance;
	if(!$ALCANCE){
		$ALCANCE = "";
	}
	$TIPO_PERSONA = $objeto->tipo_persona;

	if($ID_SERVICIO!=3) {
        $existe = $database->select(
            "PROSPECTO_PRODUCTO",
            "*",
            [
                "AND" => [
                    "ID_PROSPECTO" => $ID_PROSPECTO,
                    "ID_SERVICIO" => $ID_SERVICIO,
                    "ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO
                ]
            ]
        );
        if (count($existe) > 0) {
            $respuesta['resultado'] = "error";
            $respuesta['mensaje'] = "El registro que intenta ingresar ya existe";
            print_r(json_encode($respuesta));
            die();
        }
    }
	$id_producto = $database->insert("PROSPECTO_PRODUCTO", [ 
	    "ID_PROSPECTO" => $ID_PROSPECTO,
		"ID_SERVICIO" => $ID_SERVICIO, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"ALCANCE" => $ALCANCE
	]); 
	valida_error_medoo_and_die();
	//iNSERTAR LAS NORMAS
	if($ID_SERVICIO!=3) {
        for ($i = 0; $i < count($NORMAS); $i++) {
            $id_norma = $NORMAS[$i]->ID_NORMA;
            $id_producto_normas = $database->insert("PROSPECTO_PRODUCTO_NORMAS", [
                "ID_PRODUCTO" => $id_producto,
                "ID_NORMA" => $id_norma
            ]);
            valida_error_medoo_and_die();
        }
    }
    else
	{
        if($MODALIDAD == "programado")
			$id_producto_curso = $database->insert("PROSPECTO_PRODUCTO_CURSO", [
				"ID_PRODUCTO" => $id_producto,
				"ID_CURSO_PROGRAMADO" => $CURSO,
				"MODALIDAD"=> $MODALIDAD,
				"CANTIDAD_PARTICIPANTES"=>$CANTIDAD,
				"SOLO_PARA_CLIENTE"=>$SOLO_CLIENTE
			]);
        if($MODALIDAD == "insitu")
            $id_producto_curso = $database->insert("PROSPECTO_PRODUCTO_CURSO", [
				"ID_PRODUCTO" => $id_producto,
				"ID_CURSO" => $CURSO,
				"MODALIDAD"=> $MODALIDAD,
				"CANTIDAD_PARTICIPANTES"=>$CANTIDAD
			]);
        valida_error_medoo_and_die();
	}

	//Insertar integración (si es integral)
	if( $ID_TIPO_SERVICIO == 20){ //Id de integral
		$preguntas = $database->select("INTEGRACION_PREGUNTAS","*");
		valida_error_medoo_and_die();
		foreach ($preguntas as $key => $pregunta) {
			$id_producto_integracion = $database->insert("PRODUCTO_INTEGRACION", [ 
				"ID_PRODUCTO" => $id_producto,
				"ID_PREGUNTA" => $pregunta['ID'],
				"RESPUESTA" => $pregunta['RESPUESTA_INICIAL']
			]); 
			valida_error_medoo_and_die();
		}
	}
    if($TIPO_PERSONA!="")
	{
        $id_prospecto= $database->update("PROSPECTO", [
            "TIPO_PERSONA" => $TIPO_PERSONA
        ], ["ID" => $ID_PROSPECTO]);
    }
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
