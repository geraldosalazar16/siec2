<?php  
	include  '../../common/conn-apiserver.php';  
	include  '../../common/conn-medoo.php';  

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
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id_usuario = $objeto->id_usuario;
valida_parametro_and_die($id_usuario,"Falta ID de Usuario");

$FECHA = date("Ymd");
$HORA = date("His");
//obtener los q tienen estado emitida(2) en solicitudes y pasaron a ese estado(pend--->emit) hace más de 30 dias
//desde el hist. En un futuro se podría agregar al id_estatus=2 un OR con otros como pej pag parc, suspend, etc
$IDsVencidas="SELECT id FROM FACTURACION_SOLICITUDES fs
INNER JOIN FACTURACION_SOLICITUD_HISTORICO fsh ON fs.ID=fsh.ID_SOLICITUD
WHERE id_estatus=2 AND 
fsh.ID_ESTATUS_ANTERIOR=1 AND fsh.ID_ESTATUS_ACTUAL=2
 AND DATEDIFF(CURDATE(),STR_TO_DATE(fecha,'%Y%m%d'))>30";
$IDsVenc=$database->query($IDsVencidas)->fetchAll(PDO::FETCH_ASSOC);

$Update="";
$AddHist="";
$errorActHist = false;
$errorActSolicit = false;
$msgerror="";
foreach ($IDsVenc as $id => $value) {
	//"update FACTURACION_SOLICITUDES set id_estatus=3 where id=".$value['id'].";";

	$Update = $database->update("FACTURACION_SOLICITUDES", 
	[  
		"ID_ESTATUS" => 3,
		"FECHA_MODIFICACION" => $FECHA,
		"HORA_MODIFICACION" => $HORA,
		"USUARIO_MODIFICACION" => $id_usuario
	],[
		"ID" => $value['id']
	]); 
	valida_error_medoo_and_die();

	/*"insert into FACTURACION_SOLICITUD_HISTORICO(ID_SOLICITUD,CAMBIO,DESCRIPCION,FECHA,HORA,USUARIO,ID_ESTATUS_ANTERIOR,ID_ESTATUS_ACTUAL)
	  values(".$value['id'].",'De Emitida a Vencida','Vencida por fecha',".$FECHA.",".$HORA.",".$id_usuario.",2,3)";*/
	$addHist = $database->insert("FACTURACION_SOLICITUD_HISTORICO",[
		"ID_SOLICITUD" => $value['id'],
		"CAMBIO" => 'De Emitida a Vencida',
		"DESCRIPCION" => 'Vencida por fecha',
		"FECHA" => $FECHA,
		"HORA" => $HORA,
		"USUARIO" => $id_usuario,
		"ID_ESTATUS_ANTERIOR" => 2, 
		"ID_ESTATUS_ACTUAL" => 3
	]);
	valida_error_medoo_and_die();	 
}
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
