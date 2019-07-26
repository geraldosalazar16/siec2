<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

/**
 * Metodo getDiasHabiles
 *
 * Permite devolver un arreglo con los dias habiles
 * entre el rango de fechas dado excluyendo los
 * dias feriados dados (Si existen)
 *
 * @param string $fechainicio Fecha de inicio en formato Y-m-d
 * @param string $fechafin Fecha de fin en formato Y-m-d
 * @param array $diasferiados Arreglo de dias feriados en formato Y-m-d
 * @return array $diashabiles Arreglo definitivo de dias habiles
 */
function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);
       
        // Incremento en 1 dia
        $diainc = 24*60*60;
       
        // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();
       
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
                // Si el dia indicado, no es sabado o domingo es habil
                if (!in_array(date('N', $midia), array(6,7))) { // DOC: http://www.php.net/manual/es/function.date.php
                        // Si no es un dia feriado entonces es habil
                        if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                                array_push($diashabiles, date('Y-m-d', $midia));
                        }
                }
        }
       
        return $diashabiles;
}

/** 
	* Funcion para obtener el maximo dia de un mes y anhio dado
	* @param int $mes Mes en cuestion desde 0-enero a 11-diciembre
	* @param int $anhio en cuestio formato YYYY Ej. 2019
	* return string $fechamaxmes Formato Y-m-d Ej. 2019-07-23
*/
function getFechaMaximaMesAnhio($mes,$anhio){
	
$diaMayor=0;
$mesPHP="";
switch($mes){
		case 0:
			$diaMayor = 31;
			$mesPHP="01";
			break;
		case 1:
			if($anhio%4 == 0){
				$diaMayor = 29;
			}
			else{
				$diaMayor = 28;
			}
			$mesPHP="02";
			break;
		case 2:
			$diaMayor = 31;
			$mesPHP="03";
			break;
		case 3:
			$diaMayor = 30;
			$mesPHP="04";
			break;
		case 4:
			$diaMayor = 31;
			$mesPHP="05";
			break;
		case 5:
			$diaMayor = 30;
			$mesPHP="06";
			break;
		case 6:
			$diaMayor = 31;
			$mesPHP="07";
			break;
		case 7:	
			$diaMayor = 31;
			$mesPHP="08";
			break;
		case 8:
			$diaMayor = 30;
			$mesPHP="09";
			break;
		case 9:
			$diaMayor = 31;
			$mesPHP="10";
			break;
		case 10:
			$diaMayor = 30;
			$mesPHP="11";
			break;
		case 11:
			$diaMayor = 31;
			$mesPHP="12";
			break;
	}
	return $anhio.'-'.$mesPHP.'-'.$diaMayor;
}
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

$respuesta=array(); 
$datos1= array();
//Constantes
$anhio_curso = date('Y');
$anhio_anterior = $anhio_curso - 1;
$mes_curso = date('m')-1;
//$mes_curso1 = substr('0'.$mes_curso,-2); ;
$dias_max_mes_x_anhio = array();
$datos1['TASA_OCUPACIONAL'] = array(0,0,0,0,0,0,0,0,0,0,0,0);
// Obtener dias habiles del ano por mes.
for($i=0;$i<12;$i++){
	$fecha_inicio = "";
	$fecha_fin = "";
	$dias_max_mes_x_anhio[$i] = getFechaMaximaMesAnhio($i,$anhio_curso);
	$mes_curso1 = substr('0'.($i+1),-2); 
	$fecha_inicio = $anhio_curso.'-'.$mes_curso1.'-'.'01';
	$fecha_fin = $dias_max_mes_x_anhio[$i];
	$dias_h =getDiasHabiles($fecha_inicio,$fecha_fin);
	$dias_habiles_x_mes[$i] = count($dias_h);
}
$respuesta["DIAS_MAX_MES_ANHIO"] = $dias_max_mes_x_anhio;
$respuesta["DIAS_HABILES_X_MES"] = $dias_habiles_x_mes;

/*
	* VAMOS A BUSCAR LOS AUDITORES CONTRA LOS QUE SE HARA LA TABLA.
*/
$auditores = $database->select('PERSONAL_TECNICO','*',['PADRON'=>0]);
valida_error_medoo_and_die();
for($i=0;$i<count($auditores);$i++){
	
	for($j=0;$j<12;$j++){
		// DIAS TRABAJADOS POR AUDITOR
		$m='';
		$m= substr('0'.($j+1),-2);
		$consulta = "SELECT COUNT(DT.ID)  AS COUNT_DT FROM(
							SELECT DISTINCT `ISAGF`.`ID`
							FROM `I_SG_AUDITORIA_GRUPO_FECHAS` AS `ISAGF`
							JOIN `PERSONAL_TECNICO_CALIFICACIONES` AS `PTC` ON `ISAGF`.`ID_PERSONAL_TECNICO_CALIF` = `PTC`.`ID` 
							WHERE 
								`PTC`.`ID_PERSONAL_TECNICO`=".$auditores[$i]['ID']." AND `ISAGF`.`FECHA` LIKE  '".$anhio_curso.$m."%') AS DT";
		$resp1 = $database->query($consulta)->fetchAll();
		
		// A PARTIR DE AQUI BUSCAMOS SI TIENE ALGUN DIA DE VACACIONES
		$DIAS_VACAC = 0;
		$consulta2 = "SELECT  	`PTE`.`FECHA_INICIO`,
								`PTE`.`FECHA_FIN`
						FROM `PERSONAL_TECNICO_EVENTOS` AS `PTE`
						WHERE 
							`PTE`.`ID_PERSONAL_TECNICO`=".$auditores[$i]['ID']." AND (`PTE`.`FECHA_INICIO` LIKE  '".$anhio_curso."/".$m."%' OR `PTE`.`FECHA_FIN` LIKE  '".$anhio_curso."/".$m."%') AND (`PTE`.`EVENTO`='VACACIONES' OR `PTE`.`EVENTO`='Vacaciones' OR `PTE`.`EVENTO`='vacaciones')";
		$resp2 = $database->query($consulta2)->fetchAll();
		for($z=0;$z<count($resp2);$z++){
			$anhio_ini = number_format(substr($resp2[$z]['FECHA_INICIO'],0,4));
			$mes_ini = number_format(substr($resp2[$z]['FECHA_INICIO'],5,2));
			$dia_ini = number_format(substr($resp2[$z]['FECHA_INICIO'],8,2));
			$anhio_fin = number_format(substr($resp2[$z]['FECHA_FIN'],0,4));
			$mes_fin = number_format(substr($resp2[$z]['FECHA_FIN'],5,2));
			$dia_fin = number_format(substr($resp2[$z]['FECHA_FIN'],8,2));
			if($anhio_ini == $anhio_fin){
				if($mes_ini == $mes_fin){
					$DIAS_VACAC += $dia_fin - $dia_ini+1; 
				}
				else{
					$DIAS_VACAC += substr($dias_max_mes_x_anhio[($mes_ini-1)],8,2)-$dia_ini + $dia_fin -1+2; // CDO PASA DE UN MES PARA OTRO LAS VACACIONES SERIAN CONTAR DESDE Q EMPIEZAN HASTA EL FINAL DEL MES Y DESDE EL 1 DE MES SIGUIENTE HASTA Q ACABA
				}
			}
			else{
				$DIAS_VACAC += 31-$dia_ini + $dia_fin -1+2; // CDO PASA DE UN ANO PARA OTRO LAS VACACIONES SERIAN CONTAR DESDE Q EMPIEZAN HASTA EL 31 DE DICIEMBRE Y DESDE EL 1 DE ENERO HASTA Q ACABA
			}
		}
		
		$datos1['TASA_OCUPACIONAL'][$j] += number_format($resp1[0]['COUNT_DT'],2) + $DIAS_VACAC;
	}
	
}
for($j=0;$j<12;$j++){
	$datos1['TASA_OCUPACIONAL'][$j] = number_format($datos1['TASA_OCUPACIONAL'][$j]*100/($dias_habiles_x_mes[$j]*count($auditores)),2);
}
print_r(json_encode($datos1));

?> 
