<?php
$carpeta = "../ArchivoExpediente/Clientes";
$carpeta2 = "../ArchivoExpediente/Auditores";
$withThis = true;
eliminarDir($carpeta,$withThis);
eliminarDir($carpeta2,$withThis);
function eliminarDir($carpeta, $withThis)
{
    foreach(glob($carpeta . "/*") as $archivos_carpeta)
    {
        echo $archivos_carpeta;
 
        if (is_dir($archivos_carpeta))
        {
            eliminarDir($archivos_carpeta, true);
        }
        else
        {
            unlink($archivos_carpeta);
        }
    }
    if($withThis)
        rmdir($carpeta);
}
?>