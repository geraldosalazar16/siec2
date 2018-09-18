<?php
include  '../../ex_common/query.php';
	function encriptar($cadena){
		$key='EXPEDIENTEDHT2016CMLJJJAI';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		return urlencode($encrypted); //Devuelve el string encriptado
	}
	
	$correo = "isauro.mendoza@dhttecno.com"; 

    $id = $_REQUEST["id"];
	$respuesta=array();
	$query=" 
	SELECT HISTORIAL.ID as ID_HISTORIAL,HISTORIAL.ID_CITA AS ID, CITAS.ASUNTO AS ASUNTO, TIPO.DESCRIPCION AS TIPO,DATE_FORMAT(CITAS.FECHA_INICIO,'%Y-%m-%d') AS FECHA_CITA, DATE_FORMAT(CITAS.FECHA_INICIO,'%T') AS INICIO, DATE_FORMAT(CITAS.FECHA_FIN,'%T')AS FIN, HISTORIAL.FECHA AS FECHA ,USUARIOS.NOMBRE AS NOMBRE
	FROM PROSPECTO_CITA_HISTORIAL AS HISTORIAL
	INNER JOIN PROSPECTO_CITAS CITAS ON HISTORIAL.ID_CITA=CITAS.ID
	INNER JOIN TIPO_ASUNTO TIPO ON TIPO.ID=CITAS.TIPO_ASUNTO
	INNER JOIN USUARIOS ON USUARIOS.ID=HISTORIAL.USUARIO
    WHERE HISTORIAL.ID_CITA=".$database->quote($id)."
    ORDER BY HISTORIAL.ID_CITA
	";
	
	$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
	for($i = 0 ; $i < sizeof($respuesta);$i++){
		$query2 = "SELECT * FROM PROSPECTO_CITAS_ARCHIVOS WHERE ID_CITA_HISTORIAL = ".$database->quote($respuesta[$i]["ID_HISTORIAL"]);
		$respuesta2 = $database->query($query2)->fetchAll(PDO::FETCH_ASSOC);
		for($j = 0 ; $j< sizeof($respuesta2); $j++){
			$respuesta2[$j]["codificado"] = encriptar($respuesta2[$j]["ID"]);
		}
		$respuesta[$i]["archivos"]=$respuesta2;
	}
	valida_error_medoo_and_die("PROSPECTO_CITA_HISTORIAL",$correo); 
	print_r(json_encode($respuesta)); 

?>