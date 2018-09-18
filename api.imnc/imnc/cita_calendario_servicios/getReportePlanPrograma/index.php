<?php 
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=plan_programado.csv");

include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

$resultado = "CLIENTE,TIPO DE SERVICIO,NOMBRE DE LA TAREA,FECHA DE INICIO,PROGRAMADO,TIPO DE AUDITORIA\r\n";

//Primeramente obtengo todos los servicios contratados
$servicios_contratados = $database->select("SERVICIO_CLIENTE_ETAPA","*");

$cont = 0;
for($i=0;$i<count($servicios_contratados);$i++)
{
    //Obtengo las tareas programadas para cada servicio contratado
    $tareas = $database->select("TAREAS_SERVICIOS_CONTRATADOS","*",["ID_SERVICIO" => $servicios_contratados[$i]["ID"]]);
 
    for($j = 0;$j<count($tareas);$j++)
    {
        //Busco el nombre del cliente en la tabla clientes con el id_cliente en servicio_cliente_etapa
        $resultado .= $database->get("CLIENTES","NOMBRE",["ID" => $servicios_contratados[$i]["ID_CLIENTE"]]).",";
         
        //Busco el tipo de servicio en sg_tipo_servicio con el id de servicio_cliente_etapa
        $resultado .= $database->get("SG_TIPOS_SERVICIO","ID_TIPO_SERVICIO",["ID_SERVICIO_CLIENTE_ETAPA" => $servicios_contratados[$i]["ID"]]).",";
        
        //Determino si existe una auditoria programada
        //Primero obtengo el tipo de AUDITORIA correspondiente
        $tipo_auditoria = $database->select("CAT_TAREAS_SERVICIOS_CONTRATADOS",["ID_AUDITORIA","NOMBRE_TAREA"],["ID" => $tareas[$j]["ID_TAREA"]]);
        
        $resultado .= $tipo_auditoria[0]["NOMBRE_TAREA"].",";
        $resultado .= $tareas[$j]["FECHA_INICIO"].",";
        
        //Ahora busco si existe auditoria de ese tipo programada para ese servicio contratado
        if($tipo_auditoria["ID_AUDITORIA"] != "0" && !$tipo_auditoria["ID_AUDITORIA"] && $tipo_auditoria["ID_AUDITORIA"] != "")
        {
            $strQuery = "SELECT SGA.TIPO_AUDITORIA,COUNT(SGA.ID) FROM SERVICIO_CLIENTE_ETAPA SCE INNER JOIN SG_TIPOS_SERVICIO SGTS ON SCE.ID = SGTS.ID_SERVICIO_CLIENTE_ETAPA INNER JOIN SG_AUDITORIAS SGA ON SGA.ID_SG_TIPO_SERVICIO = SGTS.ID WHERE SCE.ID = ".$servicios_contratados[$i]["ID"]." AND SGA.TIPO_AUDITORIA = ".$tipo_auditoria["ID_AUDITORIA"]." GROUP BY SGA.TIPO_AUDITORIA";

            $cant_auditorias = $database->query($strQuery)->fetchAll();
           
            //Si existe la auditoria
            if($cant_auditorias[1] == 1)
            {
                $resultado .= "SI,";
                $resultado .= $cant_auditorias[0]."\r\n";
            }
            else
            {
                $resultado .= "NO,";
                $resultado .= "SIN ASIGTNAR\r\n";
            }
        }
        else
        {
            $resultado .= "NO,";
             $resultado .= "SIN ASIGTNAR\r\n";
        }
        $cont++;
    }
}
print_r($resultado); 
?> 
