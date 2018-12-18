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
$id_curso = $_REQUEST["id"];
valida_parametro_and_die($id_curso, "Es necesario un ID_CURSO");

$query = "SELECT DISTINCT  PT.ID,CONCAT(PT.NOMBRE,' ',PT.APELLIDO_PATERNO,' ',PT.APELLIDO_MATERNO) AS NOMBRE,PT.STATUS FROM PERSONAL_TECNICO PT ORDER BY PT.NOMBRE,PT.APELLIDO_PATERNO,PT.APELLIDO_MATERNO";
$instructores = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();
for ($i = 0 ; $i<count($instructores) ; $i++)
{

	$roles =  $database->query("SELECT DISTINCT PTR.ID, PTR.ROL FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN PERSONAL_TECNICO_ROLES PTR 
ON  PTC.ID_ROL = PTR.ID WHERE PTC.ID_PERSONAL_TECNICO =".$instructores[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
    valida_error_medoo_and_die();
    $instructores[$i]["ROLES"] = $roles;

    $count_rol = $database->query("SELECT COUNT(PTR.ID) as total FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN PERSONAL_TECNICO_ROLES PTR
ON  PTC.ID_ROL = PTR.ID WHERE  PTR.ID = 7 AND PTC.ID_PERSONAL_TECNICO =".$instructores[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
    valida_error_medoo_and_die();

    if(intval($count_rol[0]["total"])>0){$instructores[$i]["ISROL"] = true;} else {$instructores[$i]["ISROL"] = false;}


    $cursos = $database->query("SELECT DISTINCT C.ID_CURSO, C.NOMBRE FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN PERSONAL_TECNICO_CALIF_CURSOS PTCC ON  PTC.ID = PTCC.ID_PERSONAL_TECNICO_CALIFICACION INNER JOIN CURSOS C ON PTCC.ID_CURSO = C.ID_CURSO WHERE C.ISACTIVO = '1' AND PTC.ID_PERSONAL_TECNICO= ".$instructores[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
    valida_error_medoo_and_die();
    $instructores[$i]["CURSOS"] = $cursos;

    $count_curso = $database->query("SELECT COUNT(C.ID_CURSO) as total FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN PERSONAL_TECNICO_CALIF_CURSOS PTCC ON  PTC.ID = PTCC.ID_PERSONAL_TECNICO_CALIFICACION INNER JOIN CURSOS C ON PTCC.ID_CURSO = C.ID_CURSO WHERE C.ISACTIVO = '1' AND C.ID_CURSO = ".$id_curso." AND PTC.ID_PERSONAL_TECNICO=".$instructores[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
    valida_error_medoo_and_die();
    if(intval($count_curso[0]["total"])>0){ $instructores[$i]["ISCURSO"] = true;}else{$instructores[$i]["ISCURSO"] = false;}


}

print_r(json_encode($instructores));
?> 
