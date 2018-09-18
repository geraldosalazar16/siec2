<?php
	include 'common/apiserver.php';
	//echo urldecode($_REQUEST['codigo'])."<br>";
	//echo $_REQUEST['codigo']."<br>";
	//echo urlencode($_REQUEST['codigo'])."<br>";
	//$global_apiserver."/ex_common/ExpedienteArchivos.php";
	$entidad = $_REQUEST['entidad'];
	if($entidad == 0)
		$file_url = "/ex_common/ExpedienteArchivos.php";
	else if($entidad == 1)
		$file_url = "/ex_common/CitasArchivos.php";
	//echo $global_apiserver.$file_url."?codigo=".urlencode($_REQUEST['codigo']);
	
	$archivo = file_get_contents($global_apiserver.$file_url."?codigo=".urlencode($_REQUEST['codigo']));
	$archivo = $global_apiserver.trim($archivo);
	
	if($archivo == false){
		print_r("<body style='background-color:black;'>");
		print_r("<div style='width:100%;height:100%;text-align:center;color:white;'>");
		print_r("<div style='margin: auto;width: 50%;height:50%;padding: 5%;font-size:30px;text-align:center;'>");
		print_r("<pre>");
		print_r("Lo sentimos, el archivo no existe.<br/><br/>");
		print_r("<span style='font-size:90px;'>");
		
		print_r("<br />");
		print_r(" /)/)");
		print_r("<br />");
		print_r("(T-T)");
		print_r("<br />");
		print_r("c(\")(\")");
		print_r("<br />");
		print_r("</span>");		
		print_r("</pre>");
		print_r("</div>");
		print_r("</div>");	
		print_r("</body>");
	}
	else{
		//echo $archivo;
		header('Content-type: application/pdf');
		//$file = $archivo;
		//echo $archivo;
		readfile($archivo);
	}
?>