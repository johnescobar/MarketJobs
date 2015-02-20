<?php
if(is_object($datos))
	header("Location: catalogo.php");

//Para validar los campos del formulario
$array_valida = array(
	 "Email" => "Email"
);


if($_POST[action])
	$action = $_POST[action];

switch( $action )
	{

		case "Actualizar":
			if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
			{
			//insertamos los datos del contacto
			
			$sqlverificar = "SELECT * FROM Registro WHERE Email = '".$_POST["Email"]."'";
			if($dbo->rows($dbo->query($sqlverificar)) > 0)
			{
				$contrasena = SIMUtil::generarPassword(8);
				
				$usuario = $dbo->fetchArray($dbo->query( $sqlverificar ));
				
				$frm[Password] = sha1($contrasena);
				
				
				
				$id_registro = $dbo->update( $frm , "Registro" , "IDRegistro" , $usuario[IDRegistro] );
				
			
			
			
				$Cuerpo = 'Tus datos de acceso son: <br />';
				$Cuerpo .= 'Email es: '.$usuario[Email].'<br />';
				$Cuerpo .= 'Contrasena es: '.$contrasena.'<br />';
			
				$To = $usuario[Email];
				$Asunto = "Recordar contrasena";
				
				
				
				
					$mail = new PHPMailer();
					$mail->From       = "no-reply@teoria.com.co";
					$mail->FromName   = "TEORIA";
					$mail->Subject    = $Asunto;
					$mail->MsgHTML( $Cuerpo );
					$mail->AddAddress( $To );
					$mail->Send();
				
					SIMHTML::jsAlert("Un correo fue enviado a tu E-mail con una nueva clave");
					SIMHTML::jsRedirect("recordar.php");
					exit;
			
			}
			else
			{
				SIMHTML::jsAlert("El E-mail no coniciden");
				SIMHTML::jsRedirect("recordar.php");
				exit;
			}
				
				
			}
			else{
				$mensaje="Hay campos vacios por favor verifique e intente de nuevo";
				$frm = $_POST;
			}
		
	}
	
	
	
	
$title = "Recordar contraseña";
$keywords = "Recordar contraseña";
$description = "Recordar contraseña";	
	
	
	
?>