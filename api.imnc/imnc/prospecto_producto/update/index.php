<?php  
	include  '../../ex_common/query.php';
function valida_parametro_and_die1($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
}	
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	
	$ID_PRODUCTO = $objeto->id;
	valida_parametro_and_die1($ID_PRODUCTO,"Es necesario seleccionar un producto");
	$ID_PROSPECTO = $objeto->id_prospecto;
	valida_parametro_and_die1($ID_PROSPECTO,"Es necesario seleccionar un prospecto");
	$ID_SERVICIO=$objeto->area;
	valida_parametro_and_die1($ID_SERVICIO,"Es necesario seleccionar un servicio");
	$ID_TIPO_SERVICIO = $objeto->departamento; 
	valida_parametro_and_die1($ID_TIPO_SERVICIO,"Es necesario seleccionar un tipo de servicio");
    $NORMAS= "";
	$MODALIDAD = "";
	$CURSO = "";
    $CANTIDAD = "";
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
	else{
        $MODALIDAD = $objeto->modalidad;
        valida_parametro_and_die($MODALIDAD,"Es necesario seleccionar una modalidad");
        $CURSO = $objeto->curso;
        valida_parametro_and_die($CURSO,"Es necesario seleccionar un curso");
        $CANTIDAD = $objeto->cantidad;
        if($MODALIDAD == 'insitu')
            valida_parametro_and_die($CANTIDAD,"Es necesario intruducir la cantidad de personas");
	}
	$ALCANCE= $objeto->alcance;
	if(!$ALCANCE){
		$ALCANCE = "";
	}
      
	$id_producto = $database->update($nombre_tabla, [ 
		"ID_SERVICIO" => $ID_SERVICIO, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"ALCANCE" => $ALCANCE
	], ["ID" => $ID_PRODUCTO]); 	
	valida_error_medoo_and_die($nombre_tabla,$correo);
    if($ID_SERVICIO!=3) {
	//ACTUALIZAR LAS NORMAS
	//borro todas las normas asociadas al producto
	$id = $database->delete("PROSPECTO_PRODUCTO_NORMAS", 
	[
		"AND" => [
			"ID_PRODUCTO" => $ID_PRODUCTO
		]		
	]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	//Inserto las normas capturadas

        for ($i = 0; $i < count($NORMAS); $i++) {
            $id_norma = $NORMAS[$i]->ID_NORMA;
            $id_producto_normas = $database->insert("PROSPECTO_PRODUCTO_NORMAS", [
                "ID_PRODUCTO" => $ID_PRODUCTO,
                "ID_NORMA" => $id_norma
            ]);
            valida_error_medoo_and_die($nombre_tabla, $correo);
        }
    }
    else{

        if($MODALIDAD=="programado")
		{
			$id_producto_curso = $database->update("PROSPECTO_PRODUCTO_CURSO", ["ID_CURSO" => null,"ID_CURSO_PROGRAMADO" => $CURSO,"MODALIDAD"=> $MODALIDAD,"CANTIDAD_PARTICIPANTES"=>0],["ID_PRODUCTO" => $ID_PRODUCTO]);
		}
		if($MODALIDAD=="insitu")
		{
            $id_producto_curso = $database->update("PROSPECTO_PRODUCTO_CURSO", ["ID_CURSO" => $CURSO,"ID_CURSO_PROGRAMADO" => null,"MODALIDAD"=> $MODALIDAD,"CANTIDAD_PARTICIPANTES"=>$CANTIDAD],["ID_PRODUCTO" => $ID_PRODUCTO]);
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