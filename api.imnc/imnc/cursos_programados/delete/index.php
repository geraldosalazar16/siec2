<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Es necesario un ID para poder eliminar");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$query = "SELECT *,(SELECT C.NOMBRE FROM CURSOS C WHERE C.ID_CURSO = CP.ID_CURSO) AS NOMBRE_CURSO,(SELECT CONCAT(PT.NOMBRE,' ',PT.APELLIDO_PATERNO,' ',PT.APELLIDO_MATERNO)  FROM PERSONAL_TECNICO PT WHERE PT.ID = CP.ID_INSTRUCTOR) AS NOMBRE_INSTRUCTOR  FROM CURSOS_PROGRAMADOS CP WHERE ID =".$ID;
$anterior = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

$id_cp = $database->delete("CURSOS_PROGRAMADOS", ["ID"=>$ID]);
valida_error_medoo_and_die();

if($id_cp	!=	0) {
    $estado_anterior =  " Referencia: " . $anterior[0]["REFERENCIA"] . ", Curso: " . $anterior[0]["NOMBRE_CURSO"] . ", Fecha: " . $anterior[0]["FECHAS"] . ", Instructor: " . strtoupper($anterior[0]["NOMBRE_INSTRUCTOR"]) . ", Mínimo: " . $anterior[0]["PERSONAS_MINIMO"] . ", Etapa: " . $anterior[0]["ETAPA"];
    $id1 = $database->insert("CURSOS_PROGRAMADOS_HISTORICO", [
        "ID_CURSO_PROGRAMADO" => $ID,
        "MODIFICACION" => "ELIMINO CURSO",
        "ESTADO_ANTERIOR" => $estado_anterior,
        "ESTADO_ACTUAL" =>  "",
        "ID_USUARIO" => $ID_USUARIO_CREACION,
        "FECHA" => date("Ymd"),

    ]);

}

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
