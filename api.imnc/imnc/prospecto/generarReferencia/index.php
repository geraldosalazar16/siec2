<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@gmail.com";
	$tabla_cliente = ["[>]USUARIOS" => ["ID_USUARIO_PRINCIPAL" => "ID"]];
	$campos = ["USUARIOS.NOMBRE"];
	$id = $_REQUEST["id"]; 
	$nombre_usuario_principal = $database->get($nombre_tabla, $tabla_cliente, $campos, ["PROSPECTO.ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo );
	$a= array();
	$a	=	explode(" ",$nombre_usuario_principal["NOMBRE"]);
	$Iniciales="";
	for($i=0;$i<count($a);$i++){
		$Iniciales.=	substr($a[$i],0,1);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////
	$mes = date('m');
	$ano = date('Y');
	$nombre_tabla = "COTIZACION_RAPIDA";
	$tabla_cliente = ["[>]PROSPECTO" => ["ID_PROSPECTO" => "ID"],"[>]USUARIOS" => ["PROSPECTO.ID_USUARIO_PRINCIPAL" => "ID"]];
	$campos = ["COTIZACION_RAPIDA.NOMBRE"];
	$cuenta = $database->count($nombre_tabla, $tabla_cliente, $campos, ["AND"=>["PROSPECTO.ID"=>$id,"ANO_REFERENCIA"=>$ano]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo );
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	$datos["REFERENCIA"] = $Iniciales.'-'.($cuenta+1).'-'.$mes.'-'.$ano;
	print_r(json_encode($datos)); 
?> 
