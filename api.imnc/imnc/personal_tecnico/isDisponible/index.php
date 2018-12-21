<?php  
include '../../common/conn-apiserver.php';
include '../../common/conn-medoo.php';
include '../../common/conn-sendgrid.php';
include '../../common/common_functions.php';
/* por bmyoth
Se pasa por parametro ID de PERSONAL_TECNICO y FECHAS separadas por comas ejemplo "15/12/2018,23/12/2018"

 ESTO RESPONDE: disponible: si | no
              razon: para el caso de que sea no
              error: en caso de algun error
*/


$FLAG = "si";
$razon = "";
$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 
if($objeto!=null){
	$ID = $objeto->ID; //ID_PERSONAL_TECNICO a buscar si esta disponoble
	valida_parametro_and_die($ID, "Es necesario un ID_PERSONAL_TECNICO");
	$FECHAS = $objeto->FECHAS; // En formato dd/mm/yyyy separados por comas SIN ESPACIOS
	valida_parametro_and_die($FECHAS, "Es necesario asignarle fechas al auditor");
}
else{
	$ID = $_REQUEST["ID"]; //ID_PERSONAL_TECNICO a buscar si esta disponoble
	valida_parametro_and_die($ID, "Es necesario un ID_PERSONAL_TECNICO");
	$FECHAS = $_REQUEST["FECHAS"]; // En formato dd/mm/yyyy separados por comas SIN ESPACIOS
	valida_parametro_and_die($FECHAS, "Es necesario asignarle fechas al auditor");
}
$FECHAS = explode(",", $FECHAS);
$FECHA_INICIO = explode("/",$FECHAS[0]);
$FECHA_INICIO = date("Ymd", strtotime($FECHA_INICIO[2].$FECHA_INICIO[1].$FECHA_INICIO[0]));
verifica_fecha_valida($FECHA_INICIO);
$FECHA_FIN = explode("/",$FECHAS[1]);
$FECHA_FIN = date("Ymd", strtotime($FECHA_FIN[2].$FECHA_FIN[1].$FECHA_FIN[0]));
verifica_fecha_valida($FECHA_FIN);

// ===================================================================
// ***** 			VERIFICA SI PT ESTA ACTIVO			    	 *****
// ===================================================================
$PERSONAL_TECNICO = $database->count("PERSONAL_TECNICO", "*" , ["AND"=>["ID"=>$ID,"STATUS"=>"inactivo"]]);

if($PERSONAL_TECNICO > 0)
{
    $FLAG = "no";
    $razon = "Ese auditor no esta activo.";
    goto handle_errors;
}

// ===================================================================
// ***** 			VERIFICA SI TIENE AUDITORIAS				 *****
// ===================================================================
$consulta= "SELECT SGAGF.FECHA FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN I_SG_AUDITORIA_GRUPO_FECHAS SGAGF ON PTC.ID = SGAGF.ID_PERSONAL_TECNICO_CALIF  WHERE PTC.ID_PERSONAL_TECNICO =".$ID;
$I_SG_AUDITORIA_GRUPO_FECHAS = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

for ($i=0; $i < count($I_SG_AUDITORIA_GRUPO_FECHAS) ; $i++) {
            $FECHA = date("Ymd", strtotime($I_SG_AUDITORIA_GRUPO_FECHAS[$i]["FECHA"]));

    if($FECHA>=$FECHA_INICIO && $FECHA<=$FECHA_FIN)
		{
            $FLAG = "no";
            $razon = "Ese auditor tiene auditoría para esta fecha: ".substr($FECHA,6,8)."-".substr($FECHA,-4,2)."-".substr($FECHA,0,4);
            goto handle_errors;
			break;


		}
    }
// ===================================================================
// ***** 			  VERIFICA SI TIENE EVENTOS				     *****
// ===================================================================
$PERSONAL_TECNICO_EVENTOS = $database->select("PERSONAL_TECNICO_EVENTOS", ["FECHA_INICIO","FECHA_FIN","EVENTO"] , ["ID_PERSONAL_TECNICO"=>$ID]);
valida_error_medoo_and_die();
for ($e=0; $e < count($PERSONAL_TECNICO_EVENTOS) ; $e++) {
    $FECHA_I = date("Ymd", strtotime($PERSONAL_TECNICO_EVENTOS[$e]["FECHA_INICIO"]));
    $FECHA_F = date("Ymd", strtotime($PERSONAL_TECNICO_EVENTOS[$e]["FECHA_FIN"]));
    if(($FECHA_I>=$FECHA_INICIO && $FECHA_I<=$FECHA_FIN)||($FECHA_F>=$FECHA_INICIO && $FECHA_F<=$FECHA_FIN))
    {
        $FLAG = "no";
        $razon = "Ese auditor tiene ".$PERSONAL_TECNICO_EVENTOS[$e]["EVENTO"]." fecha: ".substr($FECHA_I,6,8)."-".substr($FECHA_I,-4,2)."-".substr($FECHA_I,0,4)." a ".substr($FECHA_F,6,8)."-".substr($FECHA_F,-4,2)."-".substr($FECHA_F,0,4);
        goto handle_errors;
        break;

    }

}

// ===================================================================
// ***** 			  VERIFICA SI TIENE CURSOS PROG 		     *****
// ===================================================================
$CURSOS_PROGRAMADOS= $database->select("CURSOS_PROGRAMADOS", ["FECHAS"] , ["ID_INSTRUCTOR"=>$ID]);
for ($c=0; $c < count($CURSOS_PROGRAMADOS) ; $c++) {
    $FECHAS = explode("-",$CURSOS_PROGRAMADOS[$c]["FECHAS"]);
    $FECHA_I = explode("/",$FECHAS[0]);
    $FECHA_F = explode("/",$FECHAS[1]);
    $FECHA_I = date("Ymd", strtotime($FECHA_I[2].$FECHA_I[1].$FECHA_I[0]));
    $FECHA_F = date("Ymd", strtotime($FECHA_F[2].$FECHA_F[1].$FECHA_F[0]));
    if(($FECHA_I>=$FECHA_INICIO && $FECHA_I<=$FECHA_FIN)||($FECHA_F>=$FECHA_INICIO && $FECHA_F<=$FECHA_FIN))
    {
        $FLAG = "no";
        $razon = "Ese auditor tiene curso programado fecha: ".substr($FECHA_I,6,8)."-".substr($FECHA_I,-4,2)."-".substr($FECHA_I,0,4)." a ".substr($FECHA_F,6,8)."-".substr($FECHA_F,-4,2)."-".substr($FECHA_F,0,4);
        goto handle_errors;
        break;

    }
}


handle_errors:
if($FLAG=="si")
{
    $respuesta["disponible"]=$FLAG;
}
else{
    $respuesta["disponible"]=$FLAG;
    $respuesta["razon"]=$razon;
}



print_r(json_encode($respuesta)); 	



?> 
