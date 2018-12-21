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

$query = "SELECT * FROM
(SELECT  PT.ID,CONCAT(PT.NOMBRE,' ',PT.APELLIDO_PATERNO,' ',PT.APELLIDO_MATERNO) AS NOMBRE,PT.STATUS,PTR.ID AS ID_ROL, PTR.ROL,(SELECT COUNT(PTR1.ID) as total FROM PERSONAL_TECNICO_CALIFICACIONES PTC1 INNER JOIN PERSONAL_TECNICO_ROLES PTR1 ON  PTC1.ID_ROL = PTR1.ID WHERE  PTR1.ID = 7 AND PTC1.ID_PERSONAL_TECNICO = PT.ID) AS ISROL ,'' AS ID_CURSO, '' AS NOMBRE_CURSO,0 AS ISCURSO
FROM PERSONAL_TECNICO PT INNER JOIN PERSONAL_TECNICO_CALIFICACIONES PTC ON PT.ID = PTC.ID_PERSONAL_TECNICO 
INNER JOIN PERSONAL_TECNICO_ROLES PTR 
ON  PTC.ID_ROL = PTR.ID 
UNION ALL
SELECT  PT.ID,CONCAT(PT.NOMBRE,' ',PT.APELLIDO_PATERNO,' ',PT.APELLIDO_MATERNO) AS NOMBRE,PT.STATUS,'' AS ID_ROL, '' AS ROL, 0 AS ISROL,C.ID_CURSO, C.NOMBRE,
(SELECT COUNT(C1.ID_CURSO) as total FROM PERSONAL_TECNICO_CALIFICACIONES PTC1 INNER JOIN PERSONAL_TECNICO_CALIF_CURSOS PTCC1 ON  PTC1.ID = PTCC1.ID_PERSONAL_TECNICO_CALIFICACION INNER JOIN CURSOS C1 ON PTCC1.ID_CURSO = C1.ID_CURSO WHERE C1.ISACTIVO = '1' AND C1.ID_CURSO = ".$id_curso." AND PTC1.ID_PERSONAL_TECNICO=PT.ID) AS ISCURSO
FROM PERSONAL_TECNICO PT 
LEFT JOIN PERSONAL_TECNICO_CALIFICACIONES PTC ON PT.ID = PTC.ID_PERSONAL_TECNICO
LEFT JOIN PERSONAL_TECNICO_CALIF_CURSOS PTCC ON  PTC.ID = PTCC.ID_PERSONAL_TECNICO_CALIFICACION 
LEFT JOIN CURSOS C ON PTCC.ID_CURSO = C.ID_CURSO
)tt
GROUP BY ID,ID_ROL,ID_CURSO
ORDER BY ID";
$consulta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

$instructores = null;
$id_pt = null;
$count_pt = -1;
$id_rol = null;
$id_curso = null;



for ($i = 0 ; $i<count($consulta) ; $i++) {

    if ($consulta[$i]["ID"] != $id_pt) {
        $count_pt++;
        $instructores[$count_pt]["ID"] = $consulta[$i]["ID"];
        $instructores[$count_pt]["NOMBRE"] = $consulta[$i]["NOMBRE"];
        $instructores[$count_pt]["STATUS"] = $consulta[$i]["STATUS"];
        $instructores[$count_pt]["ISROL"] = false;
        $instructores[$count_pt]["ISCURSO"] = false;
        $id_pt = $consulta[$i]["ID"];

    }

    if($consulta[$i]["ID_ROL"]!=$id_rol )
    {
        if($consulta[$i]["ID_ROL"]!="")
		{
            $rol["ID_ROL"] = intval($consulta[$i]["ID_ROL"]);
            $rol["ROL"] = $consulta[$i]["ROL"];
            $instructores[$count_pt]["ROLES"][]	= $rol;

            if(intval($consulta[$i]["ISROL"])>0){$instructores[$count_pt]["ISROL"] = true;}
		}
        $id_rol = $consulta[$i]["ID_ROL"];

    }
	if($consulta[$i]["ID_CURSO"]!=$id_curso )
	{
        if($consulta[$i]["ID_CURSO"]!="")
		{
            $curso["ID_CURSO"] = intval($consulta[$i]["ID_CURSO"]);
            $curso["NOMBRE_CURSO"] = $consulta[$i]["NOMBRE_CURSO"];
            if(intval($consulta[$i]["ISCURSO"])>0){$instructores[$count_pt]["ISCURSO"] = true;}

            $instructores[$count_pt]["CURSOS"][]= $curso;

		}
		$id_curso = $consulta[$i]["ID_CURSO"];
	}






}
/*for ($i = 0 ; $i<count($instructores) ; $i++)
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


}*/

print_r(json_encode($instructores,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
?> 
