<?php


    $filename = urldecode($_GET["root"]);
    $root = '../../arch_facturacion/'.$filename;
    $name = urldecode($_GET["name"]);

	if(file_exists($root))
	{
		header('Content-Transfer-Encoding: binary');
		header('Content-Description: File Trasnfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$name);
		header('Expires: 0');
		header('Cache-Control:must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.filesize($root));
		flush();
		readfile($root);
		exit;
	}
	else
	{
		print_r('Lo sentimos pero ese documento no se encuentra en la ruta');
	}
//	header('Content-Transfer-Encoding: binary');
//	header('Last-Modified: '.gmdate('D, d M Y H:i:s'),filemtime($root));
//	header('Accept-Ranges: bytes');
//	header('Content-Length: '.filesize($root));
//	header('Content-Encoding: none');
//	header('Content-Disposition: attachment; filename='.$filename)

?>
