<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
function compararFechas($primera, $segunda)
 {
  $valoresPrimera = explode ("/", $primera);   
  $valoresSegunda = explode ("/", $segunda); 

  $diaPrimera    = $valoresPrimera[0];  
  $mesPrimera  = $valoresPrimera[1];  
  $anyoPrimera   = $valoresPrimera[2]; 

  $diaSegunda   = $valoresSegunda[0];  
  $mesSegunda = $valoresSegunda[1];  
  $anyoSegunda  = $valoresSegunda[2];

  $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
  $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
    // "La fecha ".$primera." no es v&aacute;lida";
    return 0;
  }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
    // "La fecha ".$segunda." no es v&aacute;lida";
    return 0;
  }else{
    return  $diasPrimeraJuliano - $diasSegundaJuliano;
  } 

}
$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$DURACION_DIAS = $objeto->DURACION_DIAS; 
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
$CICLO =  $objeto->CICLO;
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$STATUS_AUDITORIA = $objeto->STATUS_AUDITORIA; 
valida_parametro_and_die($STATUS_AUDITORIA, "Falta el STATUS_AUDITORIA");
$NO_USA_METODO = $objeto->NO_USA_METODO; 
$SITIOS_AUDITAR = $objeto->SITIOS_AUDITAR; 
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");


$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");
/*====================================================================================*/
// AQUI BUSCO EL STATUS_AUDITORIA_ANTERIOR
$STATUS_AUDITORIA_ANT = $database->get("I_SG_AUDITORIAS","STATUS_AUDITORIA", 
	["AND"=>["TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"CICLO"=>$CICLO]]
); 
valida_error_medoo_and_die();

/*====================================================================================*/

$id1 = $database->update("I_SG_AUDITORIAS",
											
											[
												"DURACION_DIAS"=>$DURACION_DIAS,
												"STATUS_AUDITORIA" => $STATUS_AUDITORIA,
												"NO_USA_METODO" => $NO_USA_METODO,
												"SITIOS_AUDITAR" => $SITIOS_AUDITAR,
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												"FECHA_MODIFICACION"=>date("Y-m-d H:i:s")
												
												
											], 
	["AND"=>["TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"CICLO"=>$CICLO]]
); 
valida_error_medoo_and_die(); 

if($objeto->DURACION_DIAS_INTEGRAL == "NO INTEGRAL"){
	$respuesta["resultado"]="ok"; 
}
else{

	$INPUT	=	json_decode($objeto->DURACION_DIAS_INTEGRAL,true);
	foreach($INPUT as $i => $valor){
		$id1 = $database->update("SCE_NORMAS", [ 
					"DIAS_AUDITOR" => $valor,
					"ID_TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
					"CICLO"=>$CICLO],[
				"AND"=>["ID_SCE" => $ID_SERVICIO_CLIENTE_ETAPA,"ID_NORMA"=>$i,"ID_TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO]
				]);
	valida_error_medoo_and_die();
	
	
	}
	$respuesta["resultado"]="ok";
}
/*====================================================================================*/

$ano_actual = date('Y');
$mes_actual = date('m');
$dia_actual = date('d');
$ta = (int)$TIPO_AUDITORIA;
if($STATUS_AUDITORIA_ANT == '1' && $STATUS_AUDITORIA == '2'){
	// A PARTIR DE AQUI TRABAJO EN I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS
	if($ta>5 && $ta<13){
		// CONSULTA PARA BUSCAR LA MINIMA FECHA DE LA AUDITORIA
		$consulta = "SELECT MIN(`ISAF`.`FECHA`) AS FECHA
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` =".$ID_SERVICIO_CLIENTE_ETAPA." AND `ISAF`.`TIPO_AUDITORIA` = ".$TIPO_AUDITORIA." AND `ISAF`.`CICLO` = ".$CICLO." ";
		$datos = $database->query($consulta)->fetchAll();
		// AQUI VERIFICO QUE EXISTA FECHA PARA ESTE AUDITORIA
		if($datos[0]['FECHA'] == null){
			//SINO EXISTE FECHA PROGRAMADA PARA ESTA AUDITORIA PUES NO SE DEBERIA DEJAR CAMBIAR A CONFIRMADA Y SE DEBERIA REPORTAR EL ERROR
			$id1 = $database->update("I_SG_AUDITORIAS",["STATUS_AUDITORIA" => $STATUS_AUDITORIA_ANT], 
						["AND"=>["TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"CICLO"=>$CICLO]]
					); 
			valida_parametro_and_die("", "Debe existir por lo menos una fecha de esta auditoria para pasarla a estado CONFIRMADA");
		}
		else{
			//SI TIENE FECHAS CARGADAS PUES ENTONCES REALIZAMOS LAS OPERACIONES
			//1er PASO BUSCAR SI EN EL MES EN CURSO AHI ALGUN REGISTRO GUARDADO
			$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
                                              		FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV` 
                                              		WHERE `IPOV`.`FECHA` LIKE '".$ano_actual.$mes_actual."%' ORDER BY `IPOV`.`ID` DESC";
			$datos1 = $database->query($consulta)->fetchAll();	
			if(empty($datos1)){
				//si no existen datos guardado en este mes pues se inicializan las variables en 0
				$cant_aud_prog_a_tiempo = 0;
				$cant_aud_prog_f_tiempo = 0;
			}
			else{
				// si existen datos pues se toma el primero ya que se ordeno desde el ultimo hacia el primero
				$cant_aud_prog_a_tiempo = $datos1[0]['CANT_AUD_PROG_A_TIEMPO'];
				$cant_aud_prog_f_tiempo = $datos1[0]['CANT_AUD_PROG_F_TIEMPO'];
			}		
			//2do PASO SE CALCULA LA DIFERENCIA EN DIAS PARA SABER SI ESTA A TIEMPO O FUERA DE TIEMPO
			$fecha2 = $dia_actual.'/'.$mes_actual.'/'.$ano_actual;  //Fecha de hoy
			$fecha1 = substr($datos[0]['FECHA'],6,2).'/'.substr($datos[0]['FECHA'],4,2).'/'.substr($datos[0]['FECHA'],0,4);
			$dif_dias = compararFechas($fecha1,$fecha2);
			if($dif_dias > 30 ){
				$cant_aud_prog_a_tiempo +=1;
			}
			else{
				$cant_aud_prog_f_tiempo +=1;
			}
			$id2 = $database->insert("I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG",
											
											[
												"CANT_AUD_PROG_A_TIEMPO"=>$cant_aud_prog_a_tiempo,
												"CANT_AUD_PROG_F_TIEMPO" => $cant_aud_prog_f_tiempo,
												"FECHA" => $FECHA_MODIFICACION]
												); 
			valida_error_medoo_and_die(); 
		}
	}
	// A PARTIR DE AQUI TRABAJO EN I_PROGRAMACIONES_OPORTUNAS_RENOVACION
	if($ta == 4 ){
		// CONSULTA PARA BUSCAR LA MINIMA FECHA DE LA AUDITORIA
		$consulta = "SELECT MIN(`ISAF`.`FECHA`) AS FECHA
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` =".$ID_SERVICIO_CLIENTE_ETAPA." AND `ISAF`.`TIPO_AUDITORIA` = ".$TIPO_AUDITORIA." AND `ISAF`.`CICLO` = ".$CICLO." ";
		$datos = $database->query($consulta)->fetchAll();
		// AQUI VERIFICO QUE EXISTA FECHA PARA ESTE AUDITORIA
		if($datos[0]['FECHA'] == null){
			//SINO EXISTE FECHA PROGRAMADA PARA ESTA AUDITORIA PUES NO SE DEBERIA DEJAR CAMBIAR A CONFIRMADA Y SE DEBERIA REPORTAR EL ERROR
			$id1 = $database->update("I_SG_AUDITORIAS",["STATUS_AUDITORIA" => $STATUS_AUDITORIA_ANT], 
						["AND"=>["TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"CICLO"=>$CICLO]]
					); 
			valida_parametro_and_die("", "Debe existir por lo menos una fecha de esta auditoria para pasarla a estado CONFIRMADA");
		}
		else{
			//SI TIENE FECHAS CARGADAS PUES ENTONCES REALIZAMOS LAS OPERACIONES
			//1er PASO BUSCAR SI EN EL MES EN CURSO AHI ALGUN REGISTRO GUARDADO
			$consulta = "SELECT `IPOR`.`CANT_AUD_PROG_A_TIEMPO`,`IPOR`.`CANT_AUD_PROG_F_TIEMPO`
                                              		FROM `I_PROGRAMACIONES_OPORTUNAS_RENOVACION_SG` `IPOR` 
                                              		WHERE `IPOR`.`FECHA` LIKE '".$ano_actual.$mes_actual."%' ORDER BY `IPOR`.`ID` DESC";
			$datos1 = $database->query($consulta)->fetchAll();	
			if(empty($datos1)){
				//si no existen datos guardado en este mes pues se inicializan las variables en 0
				$cant_aud_prog_a_tiempo = 0;
				$cant_aud_prog_f_tiempo = 0;
			}
			else{
				// si existen datos pues se toma el primero ya que se ordeno desde el ultimo hacia el primero
				$cant_aud_prog_a_tiempo = $datos1[0]['CANT_AUD_PROG_A_TIEMPO'];
				$cant_aud_prog_f_tiempo = $datos1[0]['CANT_AUD_PROG_F_TIEMPO'];
			}		
			//2do PASO SE CALCULA LA DIFERENCIA EN DIAS PARA SABER SI ESTA A TIEMPO O FUERA DE TIEMPO
			$fecha2 = $dia_actual.'/'.$mes_actual.'/'.$ano_actual;  //Fecha de hoy
			$fecha1 = substr($datos[0]['FECHA'],6,2).'/'.substr($datos[0]['FECHA'],4,2).'/'.substr($datos[0]['FECHA'],0,4);
			$dif_dias = compararFechas($fecha1,$fecha2);
			if($dif_dias > 30 ){
				$cant_aud_prog_a_tiempo +=1;
			}
			else{
				$cant_aud_prog_f_tiempo +=1;
			}
			$id2 = $database->insert("I_PROGRAMACIONES_OPORTUNAS_RENOVACION_SG",
											
											[
												"CANT_AUD_PROG_A_TIEMPO"=>$cant_aud_prog_a_tiempo,
												"CANT_AUD_PROG_F_TIEMPO" => $cant_aud_prog_f_tiempo,
												"FECHA" => $FECHA_MODIFICACION]
												); 
			valida_error_medoo_and_die(); 
		}
	}
}
/*====================================================================================*/

print_r(json_encode($respuesta));
?> 