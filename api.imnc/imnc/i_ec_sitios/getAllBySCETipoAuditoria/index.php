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
		$mailerror->send("I_EC_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id_sce = $_REQUEST["idsce"]; 
$id_tipo_auditoria = $_REQUEST["idtipoauditoria"];
$ciclo = $_REQUEST["ciclo"];

$consulta = "SELECT 
	`I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO`, 
    `CLIENTES_DOMICILIOS`.`NOMBRE_DOMICILIO`,
    `TIPOS_SERVICIO`.`ACRONIMO`, 
    `I_META_SITIOS`.`NOMBRE` AS `NOMBRE_META_SITIOS`,
	`I_META_SITIOS`.`TIPO` AS `TIPO`,
    `I_EC_SITIOS`.`VALOR`

 FROM 
 	`I_EC_SITIOS` 
    INNER JOIN `I_META_SITIOS` ON `I_META_SITIOS`.`ID` = `I_EC_SITIOS`.`ID_META_SITIOS`
    INNER JOIN `CLIENTES_DOMICILIOS` ON `CLIENTES_DOMICILIOS`.`ID` = `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO` 
    INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_EC_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` 
    JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` 
WHERE 
	`I_EC_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` = ".$id_sce." 
    AND NOT EXISTS 
    	(SELECT * FROM `I_SG_AUDITORIA_SITIOS` 
         			WHERE `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` = `I_EC_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` 
         			AND `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA` = ".$id_tipo_auditoria." 
         			AND `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO` = `CLIENTES_DOMICILIOS`.`ID` 
         			AND `I_SG_AUDITORIA_SITIOS`.`CICLO` = ".$ciclo.") ORDER BY `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO`";

$valores = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die(); 
$sitios = array();
$i=0;$k=0;
for($j=0;$j<count($valores);$j++){
	if($j==0){
		$k=0;
		$sitios[$i]["ID_CLIENTE_DOMICILIO"] = $valores[$j]["ID_CLIENTE_DOMICILIO"];
		$sitios[$i]["NOMBRE_DOMICILIO"] = $valores[$j]["NOMBRE_DOMICILIO"];
		$sitios[$i]["ACRONIMO"] = $valores[$j]["ACRONIMO"];
		$sitios[$i]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores[$j]["NOMBRE_META_SITIOS"];
		$sitios[$i]["DATOS"][$k]["VALOR"]=$valores[$j]["VALOR"];
		$sitios[$i]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores[$j]["TIPO"];
		//$i++;
	}
	else{
		if($valores[$j]["ID_CLIENTE_DOMICILIO"]!=$sitios[$i]["ID_CLIENTE_DOMICILIO"]){
			$i++;
			$sitios[$i]["ID_CLIENTE_DOMICILIO"] = $valores[$j]["ID_CLIENTE_DOMICILIO"];
			$sitios[$i]["NOMBRE_DOMICILIO"] = $valores[$j]["NOMBRE_DOMICILIO"];
			$sitios[$i]["ACRONIMO"] = $valores[$j]["ACRONIMO"];
			$sitios[$i]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores[$j]["NOMBRE_META_SITIOS"];
			$sitios[$i]["DATOS"][$k]["VALOR"]=$valores[$j]["VALOR"];
			$sitios[$i]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores[$j]["TIPO"];
		}
		else{
			$k++;
			$sitios[$i]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores[$j]["NOMBRE_META_SITIOS"];
			$sitios[$i]["DATOS"][$k]["VALOR"]=$valores[$j]["VALOR"];
			$sitios[$i]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores[$j]["TIPO"];
		}
	}
}

print_r(json_encode($sitios)); 
?> 
