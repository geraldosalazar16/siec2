<?php 
	include  '../init.php'; 
	include  '../../ex_common/getAll.php';

	$entidad = $_REQUEST["entidad"];
	$TABLA = "CLIENTES";
	$JOIN = [
		"[>]EX_EXPEDIENTE_ENTIDAD(exp_ent)" => ["CLIENTES.ID_TIPO_ENTIDAD" => "ID_ENTIDAD"],
		"[>]EX_TIPO_EXPEDIENTE(tipo_exp)" => ["exp_ent.ID_TIPO_EXPEDIENTE" => "ID"],
	];

	$columnas = [
		"exp_ent.ID(ID_EXPEDIENTE_ENTIDAD)",
	  	"tipo_exp.ID(ID_EXPEDIENTE)",
	  	"tipo_exp.NOMBRE(NOMBRE)",
	];
	$registro = $database->select($TABLA, $JOIN, $columnas, ["CLIENTES.ID" => $entidad]);
	valida_error_medoo_and_die($TABLA, $CORREO); 
	print_r(json_encode($registro));
?> 
