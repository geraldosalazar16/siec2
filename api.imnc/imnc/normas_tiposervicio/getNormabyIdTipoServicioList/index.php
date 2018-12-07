<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 

	function valida_error_medoo_and_die(){
		global $database, $mailerror;
		if ($database->error()[2]) { //Aqui está el error
			$respuesta['resultado']="error";
			$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
			print_r(json_encode($respuesta));
			$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
			die();
		}
	}
	
	$nombre_tabla = "NORMAS_TIPOSERVICIO";
	
	$norma_tipo=array(); 
	$normas = array(); 
	$id= $_REQUEST['id']; 
	$in = 0;
	$norma_tipo = $database->select($nombre_tabla,"*",["ID_TIPO_SERVICIO"=>$id]); 
	valida_error_medoo_and_die();
	for ($i=0; $i < count($norma_tipo) ; $i++) { 
		
		if (!is_null($norma_tipo[$i]["ID_NORMA"])) {
			$norma = $database->get("NORMAS", "*", ["ID"=>$norma_tipo[$i]["ID_NORMA"]]);
			valida_error_medoo_and_die();
			
			$normas[] = $norma;
			
			


		}
	}
	
	print_r(json_encode($normas)); 
?> 
