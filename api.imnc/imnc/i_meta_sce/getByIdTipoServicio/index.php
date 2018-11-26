<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 
function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("I_META_SCE", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 
$id_sce = $_REQUEST["id_sce"];
$norma	=	$_REQUEST["norma"];
if($id != 20){
	$meta_sce = $database->select("I_META_SCE",
											"*",
											["AND"=>["ID_TIPOS_SERVICIO"=>$id,"ID_NORMA"=>$norma]]); 
	valida_error_medoo_and_die(); 
}
else{
	$meta_sce = $database->query("SELECT * FROM `I_META_SCE` WHERE `ID_TIPOS_SERVICIO` IN (SELECT `ID_TIPO_SERVICIO` FROM `NORMAS_TIPOSERVICIO` WHERE `ID_NORMA` IN (SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`=  ".$id_sce.") AND `ID_TIPO_SERVICIO` !=20)")->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
	for($i=0;$i<count($meta_sce);$i++){
		$tipo_serv = $database->get("TIPOS_SERVICIO","NOMBRE",["ID"=>$meta_sce[$i]["ID_TIPOS_SERVICIO"]]);
		$meta_sce[$i]["NOMBRE"] .= " ".$tipo_serv;
	}
}
print_r(json_encode($meta_sce)); 
?> 
