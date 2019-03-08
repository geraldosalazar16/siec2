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
		die(); 
	} 
} 

//Funcion para redondear
function redondeado ($numero, $decimales) { 
   $factor = pow(10, $decimales); 
   return (round($numero*$factor)/$factor); 
  }
   
   
$respuesta=array(); 
$datos1= array();
//Constantes
$ano_curso = date('Y');
$ano_1 = $ano_curso - 1;
$ano_2 = $ano_curso - 2;
$ano_3 = $ano_curso - 3;
$ano_4 = $ano_curso - 4;
//INICIALIZANDO VARIABLES

		$datos1['X'][0]= $ano_curso;
		$datos1['X'][1]= $ano_1;
		$datos1['X'][2]= $ano_2;
 		$datos1['X'][3]= $ano_3;
 		$datos1['X'][4]= $ano_4;
 		
		$datos1['Y1'][0]= '';	
		$datos1['Y1'][1]= '';
		$datos1['Y1'][2]= '';	
		$datos1['Y1'][3]= '';
		$datos1['Y1'][4]= '';	
		
			
				
				
		
		
// ANO ACTUAL
$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_curso."%' AND 
                            `ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                
                            ) ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_curso."%'  ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y1'][0] = ''; 
		
	}
	else{
		$datos1['Y1'][0] = count($dd0); 
		
	}
// ANO ACTUAL - 1
$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_1."%' AND 
                            `ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                
                            ) ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_1."%'  ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y1'][1] = ''; 
	}
	else{
		$datos1['Y1'][1] = count($dd1);   
	}
// ANO ACTUAL - 2
$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_2."%' AND 
                            `ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                
                            ) ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_2."%'  ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y1'][2] = ''; 
	}
	else{
		$datos1['Y1'][2] = count($dd2);   
	}
// ANO ACTUAL - 3
$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_3."%' AND 
                            `ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                
                            ) ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_3."%'  ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y1'][3] = ''; 
	}
	else{
		$datos1['Y1'][3] = count($dd3);   
	}
// ANO ACTUAL - 4
$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_4."%' AND 
                            `ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                
                            ) ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_4."%'  ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y1'][4] = ''; 
	}
	else{
		$datos1['Y1'][4] = count($dd4);   
	}
/**************************************/
print_r(json_encode($datos1));

?> 
