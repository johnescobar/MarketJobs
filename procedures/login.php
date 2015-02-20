<?php

//Para validar los campos del formulario
$array_valida = array(
	"Nombre" => "Nombre", "NumeroDocumento" => "NumeroDocumento", "Password" => "Password", "Email" => "Email", "Telefono" => "Telefono"
);

if($_POST["action"])
	$action = $_POST["action"];
elseif($_GET["action"])
	$action = $_GET["action"];

$msg = $_GET["msg"];

switch( $action )
	{

		case 'Iniciar':

			$login = SIMUtil::antiinjection($_POST["Email"]);
			$clave = SIMUtil::antiinjection($_POST["Password"]);
			
			$dbo =& SIMDB::get();
			
			$user_data = $dbo->fetchAll( "Registro" , "Email = '" . $login . "' AND Password = '" . sha1($clave) . "'" , "object" );
			
			$simsession->clean();	
			
			if( $user_data )
			{	
				$usuariosave = addslashes( serialize( $user_data ) );
				
				if( $simsession->crear( $user_data->IDRegistro , $usuariosave ) )
				{
					if( $datos_carro["Items"] > 0 )
						SIMHTML::jsRedirect("viewcart.php");
					else
						SIMHTML::jsRedirect("micuenta.php");
					exit;
				}//end if			
			}
			else
			{
				SIMHTML::jsRedirect("registro.php?error=di");
				exit;
			}

		break;

		case 'Salir':
			$simsession->eliminar();
			header( "location:index.php?msg=EX" );//cierre correcto
			exit;
		break;
		case "insert":
			
			foreach( $_POST as $key => $value )
				$_POST[$key] = SIMUtil::antiinjection( $value );
			
			
			if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
			{
				
					
					$frm = SIMUtil::makeSafe( $_POST );			
					
					$frm["Password"] = sha1($frm["Password"]);
					$frm["FechaRegistro"] = date( "Y-m-d" );
					$frm["UsuarioTrCr"]   = "Administrador General";
					$frm["FechaTrCr"]     = date( "Y-m-d h:i:s" );
					$frm["FechaNacimiento"] = $frm["AnoNacimiento"] . "-" . str_pad($frm["MesNacimiento"], 2, "0", STR_PAD_LEFT) . "-" . str_pad($frm["DiaNacimiento"], 2, "0", STR_PAD_LEFT);
					

					//validamos si ya existe el registro
					$SqlRegistro = "SELECT * From Registro WHERE Email = '" . $frm["Email"] . "' OR NumeroDocumento = '" . $frm["NumeroDocumento"] . "'";
					$QryRegistro = $dbo -> query( $SqlRegistro );
					$NumRegistro = $dbo -> rows( $QryRegistro );
					if( $NumRegistro < 1 )//si no existe lo creamos
					{
						
						//insertamos los datos
						$id = $dbo->insert( $frm , "Registro" , "IDRegistro" );
						$user_data = $dbo->fetchAll( "Registro" , "IDRegistro = '" . $id . "'" , "object" );
			
						$simsession->clean();	
						
						if( $user_data )
						{	
							$usuariosave = addslashes( serialize( $user_data ) );
							
							if( $simsession->crear( $user_data->IDRegistro , $usuariosave ) )
							{
								SIMHTML::jsRedirect("registro_beneficiario.php");
								exit;
							}//end if			
						}
						
						SIMHTML::jsRedirect("login.php");
						exit;
						
						
						
					}//de lo contario lo actualizamos
					else
						$msg= "Registro ya existe con: Email " . $frm["Email"] . " o el Documento " . $frm["NumeroDocumento"] ;
						//SIMHTML::jsRedirect( "registro.php" );
				
			
			}
			else
				$msg="Hay campos vacios por favor verifique e intente de nuevo";
			
		break;
		
	}
	
	
	
$title = ": Login y Registro";
$keywords = "Login y Registro";
$description = "Login y Registro";	
	
	
	
	
?>