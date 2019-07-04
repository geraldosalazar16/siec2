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
		$mailerror->send("PERSONAL_TECNICO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$respuesta=array();
$mes = $_REQUEST["mes"];
$ano = $_REQUEST["ano"];

//$personal_tecnico = $database->select("PERSONAL_TECNICO", "*",["STATUS"=>"activo","ORDER"=>"PADRON"]);
$consulta = "SELECT * FROM `PERSONAL_TECNICO` WHERE `STATUS`='activo' ORDER BY `PADRON`,`PRIORIDAD_CERTI`,`ID` ASC";
$personal_tecnico = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();
// A partir de aqui genero las fechas del mes en cuestion
$diaMayor=0;
$mesPHP="";
switch($mes){
		case 0:
			$diaMayor = 31;
			$mesPHP="01";
			break;
		case 1:
			if($ano%4 == 0){
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

$OJBETO_MES =new stdClass();
$json_response = new stdClass();
for($i = 0 ; $i <$diaMayor;$i++){
	$a = 'd'.($i+1);
	$OJBETO_MES->$a = " ";
}
$FECHA_INICIO = date("Ymd", strtotime($ano.$mesPHP.'01'));
$FECHA_FIN= date("Ymd", strtotime($ano.$mesPHP.$diaMayor));

//A partir de aqui genero un ciclo para recorrer todos los auditores.
$indice =0; 		//Indice para guardar los datos en la variable de salida
for($i = 0 ; $i <count($personal_tecnico);$i++){
	$FLAG = "si";
	$razon="";
	$OJBETO_MES_PT = clone $OJBETO_MES;
	$OJBETO_MES_PT->Auditor = $personal_tecnico[$i]['NOMBRE'].' '.$personal_tecnico[$i]['APELLIDO_PATERNO'].' '.$personal_tecnico[$i]['APELLIDO_MATERNO'];
	$OJBETO_MES_PT->ID = $personal_tecnico[$i]['ID'];

	
// ===================================================================
// ***** 			VERIFICA SI TIENE AUDITORIAS				 *****
// ===================================================================
	$consulta= "SELECT SGAGF.FECHA, SGAGF.ID_SERVICIO_CLIENTE_ETAPA,SGAGF.TIPO_AUDITORIA,SGAGF.CICLO,PTC.ID_TIPO_SERVICIO FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN I_SG_AUDITORIA_GRUPO_FECHAS SGAGF ON PTC.ID = SGAGF.ID_PERSONAL_TECNICO_CALIF  WHERE PTC.ID_PERSONAL_TECNICO =".$personal_tecnico[$i]['ID']." AND SGAGF.FECHA >= ".$FECHA_INICIO." AND SGAGF.FECHA<= ".$FECHA_FIN;
	$I_SG_AUDITORIA_GRUPO_FECHAS = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();

	for ($j=0; $j < count($I_SG_AUDITORIA_GRUPO_FECHAS) ; $j++) {

            $FECHA = date("Ymd", strtotime($I_SG_AUDITORIA_GRUPO_FECHAS[$j]["FECHA"]));
		    $FLAG = "no";
			$dia1= (int)substr($FECHA,6,8);
		    $numero = $dia1;
			$dia1 = 'd'.$dia1;
			
		    $SERVICIO = $database->get("SERVICIO_CLIENTE_ETAPA",
				["[><]CLIENTES" => ["ID_CLIENTE" => "ID"]],
				[
				 "SERVICIO_CLIENTE_ETAPA.ID",
				 "SERVICIO_CLIENTE_ETAPA.ID_CLIENTE",
				 "SERVICIO_CLIENTE_ETAPA.REFERENCIA",
				 "CLIENTES.NOMBRE"
				],
				["SERVICIO_CLIENTE_ETAPA.ID"=>$I_SG_AUDITORIA_GRUPO_FECHAS[$j]['ID_SERVICIO_CLIENTE_ETAPA']] );
		    valida_error_medoo_and_die();
		    $AUDITORES = $database->select("I_SG_AUDITORIA_GRUPOS",
			              [
			              	"[><]PERSONAL_TECNICO_CALIFICACIONES"=>["ID_PERSONAL_TECNICO_CALIF"=>"ID"],
			              	"[><]PERSONAL_TECNICO_ROLES"=>["ID_ROL"=>"ID"],
			              	"[><]PERSONAL_TECNICO"=>["PERSONAL_TECNICO_CALIFICACIONES.ID_PERSONAL_TECNICO"=>"ID"],
						  ],
			              [
							  "PERSONAL_TECNICO.NOMBRE",
							  "PERSONAL_TECNICO.APELLIDO_MATERNO",
							  "PERSONAL_TECNICO.APELLIDO_PATERNO",
							  "PERSONAL_TECNICO.EMAIL",
							  "PERSONAL_TECNICO_ROLES.ACRONIMO",
						  ],
			              ["AND"=>["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=>$I_SG_AUDITORIA_GRUPO_FECHAS[$j]['ID_SERVICIO_CLIENTE_ETAPA'],
							  "I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=>$I_SG_AUDITORIA_GRUPO_FECHAS[$j]['TIPO_AUDITORIA'],
							  "I_SG_AUDITORIA_GRUPOS.CICLO"=>$I_SG_AUDITORIA_GRUPO_FECHAS[$j]['CICLO']]]
			);
		    valida_error_medoo_and_die();
			switch($I_SG_AUDITORIA_GRUPO_FECHAS[$j]["ID_TIPO_SERVICIO"]){
				case 1:
					$OJBETO_MES_PT->$dia1 = "Auditoria(C)- ".$SERVICIO["NOMBRE"]."		";
					break;
				case 2:
					$OJBETO_MES_PT->$dia1 = "Auditoria(A)- ".$SERVICIO["NOMBRE"]."		";
					break;
				case 12:
					$OJBETO_MES_PT->$dia1 = "Auditoria(SAST)- ".$SERVICIO["NOMBRE"]."		";
					break;
				case 21:
					$OJBETO_MES_PT->$dia1 = "Auditoria(SGEN)- ".$SERVICIO["NOMBRE"]."		";
					break;
				default:
					$OJBETO_MES_PT->$dia1 = $SERVICIO["NOMBRE"]."		";
					break;
			}
		    $SERVICIO["AUDITORES"]=$AUDITORES;
		   // $OJBETO_MES_PT->$dia1 = $SERVICIO["NOMBRE"]."		";
		    $OJBETO_MES_PT->$numero->SCE = $SERVICIO;
			$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
			$respuesta[$i] = $OJBETO_MES_PT; 
			
    }	
// ===================================================================
// ***** 			  VERIFICA SI TIENE EVENTOS				     *****
// ===================================================================
	$PERSONAL_TECNICO_EVENTOS = $database->select("PERSONAL_TECNICO_EVENTOS", ["FECHA_INICIO","FECHA_FIN","EVENTO"] , ["ID_PERSONAL_TECNICO"=>$personal_tecnico[$i]['ID']]);
	valida_error_medoo_and_die();
	for ($e=0; $e < count($PERSONAL_TECNICO_EVENTOS) ; $e++) {
		$FECHA_I = date("Ymd", strtotime($PERSONAL_TECNICO_EVENTOS[$e]["FECHA_INICIO"]));
		$FECHA_F = date("Ymd", strtotime($PERSONAL_TECNICO_EVENTOS[$e]["FECHA_FIN"]));
		if(($FECHA_I>=$FECHA_INICIO && $FECHA_I<=$FECHA_FIN)||($FECHA_F>=$FECHA_INICIO && $FECHA_F<=$FECHA_FIN))
		{
			$FLAG = "no";
			$razon = "Este auditor tiene ".$PERSONAL_TECNICO_EVENTOS[$e]["EVENTO"].". 	";
			$diai= (int)substr($FECHA_I,6,8);
			$diaf= (int)substr($FECHA_F,6,8);
			for($x=$diai;$x<=$diaf;$x++){
				$dia1 = 'd'.$x;
				$OJBETO_MES_PT->$dia1 = $razon;
				$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
				$respuesta[$i] = $OJBETO_MES_PT;
			}
			

		}

	}
// ===================================================================
// ***** 			  VERIFICA SI TIENE CURSOS PROG 		     *****
// ===================================================================

    $CURSOS_PROGRAMADOS= $database->select("CURSOS_PROGRAMADOS", ["FECHAS"] , ["ID_INSTRUCTOR"=>$personal_tecnico[$i]['ID']]);
	valida_error_medoo_and_die();
	
	for ($c=0; $c < count($CURSOS_PROGRAMADOS) ; $c++) {
		$FECHAS = explode("-",$CURSOS_PROGRAMADOS[$c]["FECHAS"]);
		$FECHA_I = explode("/",$FECHAS[0]);
		$FECHA_F = explode("/",$FECHAS[1]);
		$FECHA_I = date("Ymd", strtotime($FECHA_I[2].$FECHA_I[1].$FECHA_I[0]));
		$FECHA_F = date("Ymd", strtotime($FECHA_F[2].$FECHA_F[1].$FECHA_F[0]));
		if(($FECHA_I>=$FECHA_INICIO && $FECHA_I<=$FECHA_FIN)||($FECHA_F>=$FECHA_INICIO && $FECHA_F<=$FECHA_FIN))
		{
			$FLAG = "no";
			$razon = "Este auditor tiene curso programado. 	";
			$diai= (int)substr($FECHA_I,6,8);
			$diaf= (int)substr($FECHA_F,6,8);
			for($y=$diai;$y<=$diaf;$y++){
				$dia1 = 'd'.$y;
				$OJBETO_MES_PT->$dia1 = $razon;
				$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
				$respuesta[$i] = $OJBETO_MES_PT;
			}

		}
	}	
// ===================================================================
// ***** 			  VERIFICA SI TIENE CURSOS INSITUS		     *****
// ===================================================================

    $CURSOS_INSITUS= $database->select("SCE_CURSOS", ["FECHA_INICIO","FECHA_FIN"] , ["ID_INSTRUCTOR"=>$personal_tecnico[$i]['ID']]);
	for ($c=0; $c < count($CURSOS_INSITUS) ; $c++) {
    $FECHA_I = date("Ymd", strtotime($CURSOS_INSITUS[$c]["FECHA_INICIO"]));
    $FECHA_F = date("Ymd", strtotime($CURSOS_INSITUS[$c]["FECHA_FIN"]));
    if(($FECHA_I>=$FECHA_INICIO && $FECHA_I<=$FECHA_FIN)||($FECHA_F>=$FECHA_INICIO && $FECHA_F<=$FECHA_FIN))
    {

        $FLAG = "no";
        $razon = "Este auditor tiene curso insitus. 	";
        $diai= (int)substr($FECHA_I,6,8);
		$diaf= (int)substr($FECHA_F,6,8);
			for($y=$diai;$y<=$diaf;$y++){
				$dia1 = 'd'.$y;
				$OJBETO_MES_PT->$dia1 = $razon;
				$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
				$respuesta[$i] = $OJBETO_MES_PT;
			}

    }
}	
	if($FLAG == 'si'){
		$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
		$respuesta[$i] = $OJBETO_MES_PT; 
		$indice++;
	}
	else{
		//$razon = $json_response->razon;
	/*	if($razon != 'Ese auditor no esta activo.'){
			$var1 = explode(': ',$razon);
			if( count($var1) >1 ){
				$dia1= (int)substr($var1[1],0,2);
				$dia1 = 'd'.$dia1;
				$OJBETO_MES_PT->$dia1 = $razon;
				$personal_tecnico[$i]['DATOS']= $OJBETO_MES_PT;
				$respuesta[$indice] = $OJBETO_MES_PT; 
				$indice++;
			}
		}*/
	}
	
	
}
print_r(json_encode($respuesta,JSON_PRETTY_PRINT));


//-------- FIN --------------
?>
