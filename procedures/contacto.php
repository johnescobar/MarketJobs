<?php
$ids = 5;

//seccion
$qry_seccion = $dbo->query( "SELECT * FROM Seccion WHERE Publicar = 'S' AND   IDSeccion = '". $ids ."' " );
$seccion = $dbo->fetchArray( $qry_seccion );

switch( $_POST["action"] )
{

	case "insert":
		$_POST["FechaTrCr"] = date( "Y-m-d H:i:s" );
		$_POST["FechaRegistro"] = date( "Y-m-d H:i:s" );

		//seguridad para cada campo del formulario
		foreach($_POST as $clave=>$valor)
		{
			$_POST[$clave] = SIMUtil::antiinjection($valor);
		}//end for
		
		//insertamos los datos del contacto
		$id = $dbo->insert( $_POST , "Contacto" , "IDContacto" );			
		
		//Enviamos el correo al usario de conectar
		$parametros = $dbo -> fetchById( "Parametro" , "IDParametro" , "1" , "array" );
										
       	$dest = trim( $parametros["Email"] );
		
  	   	$head  = "From: " . $_POST["Email"] . "\r\n";	  
       	$head .= "To: " . $dest . " \r\n";
  
       	// Ahora creamos el cuerpo del mensaje
       	$msg  = "Mensaje Enviado a Servicios Uno A Uno \n\n";	  	       	  
      	foreach($_POST as $key => $value)
			$msg .= $key." : ".$value." \n";
  
        // Finalmente enviamos el mensaje
        if ( mail( $dest, "Contacto Ortopedicos WYW", $msg, $head ) ) 
  	      	SIMHTML::jsRedirect( "contacto.php?msg=1" );
       	else 
   			SIMHTML::jsRedirect( "contacto.php?msg=2" );
	break;
	
}//end switch


$title = "Contáctenos";
$keywords = "Contáctenos";
$description = "Contáctenos de Ortopedicos WYW";

$msg = SIMNet::get("msg");
	
?>