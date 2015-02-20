<?php
	require( "config.inc.php" );
	
	SIMUtil::cache();
	
	$_POST = SIMUtil::makeSafe( $_POST );

	//handler de sesion
	$simsession = new SIMSession( SESSION_LIMIT );
	
	if(isset($_POST[ "action"]))
		$action = $_POST[ "action"];
	else
		$action = $_GET[ "action"];
	
	switch( $action )
	{

		case 'Iniciar':

			$login = SIMUtil::antiinjection( $_POST[ "login"] );
			$clave = SIMUtil::antiinjection( $_POST[ "clave"]);

            $dbo =& SIMDB::get();
			
			$user_data = $dbo->fetchAll( "Usuario" , "Email = '" . $login . "' AND Password = '" . sha1($clave) . "'  AND Autorizado = 'S' AND TipoUsuario = 'Admin' "  , "object" );
			
			$simsession->clean();		
			
			if( $user_data )
			{	
				$usuariosave = addslashes( serialize( $user_data ) );
				
				if( $simsession->crear( $user_data->IDUsuario , $usuariosave ) )
				{
					header( "location:./?mod=Admin" );
					exit;
				}			
			}
			else
			{
				header( "location:login.php?msg=LI" );//login incorrecto
				exit;
			}

		break;

		case 'Salir':
			$simsession->eliminar();
			header( "location:login.php?msg=EX" );//cierre correcto
			exit;
		break;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title><?php echo APP_TITLE;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	
	<link rel="stylesheet" href="css/estilos.css?<?=rand(1,100) ?>" type="text/css" />	
	
	<script language="JavaScript" src="jscript/validaForm.js"></script>
	
</head>
<body class="mainbody" >
		
		<table width="100%" border="0" cellspacing="0" cellpadding="1" height="500">
			<tr height="500">
				<td valign="top" height="500">
					<table width="100%" border="0"  align="center"  cellpadding="0" cellspacing="0">
				  
						<tr >
							<td  align="center"  >
								<table class="login"  cellspacing=0 cellpadding=0 border=0 width="100%" align="center">
									
										
                                        
                                         <tr class="row0">
											<td colspan="2" >
                                            
                                            	Bienvenido al<br />
                                                
                                               	<div class="titulosistema">Administrador <strong>Market Jobs</strong></div>
                                            </td>
											
										</tr>
                                        
                                        
                                        
								</table>
                                <br />
                                <form action="<?php echo $PHP_SELF?>" method="post" name="loginfrm" >  
                                <table class="loginfrm"  cellspacing=0 cellpadding=0 border=0 width="100%" align="center">
                                      
                                        <tr >
                                            <td colspan="2" class="menubackgrlow" align="left" style="padding-left:10px;" height="18">
                                            <?php
	                                            $msg = $_GET["msg"];
	                                            
	                                            if( empty( $msg ) )
	                                                $msg = "Ingrese su usuario y clave por favor";
	                                            else
	                                            	$msg = SIMResources::$session[ $msg ];
	                                                
	                                            echo $msg;
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
											<td align=right>Usuario</td>
											<td><input type="text" size="25"  id="Usuario" name="login" class="input" /></td>
										</tr>
										<tr>
											<td align="right">Clave</td>
											<td><input type="password" size="25"  id="Clave" name="clave" class="input" /></td>
										</tr>
										<tr>
											<td colspan="2" align=center><input type="hidden" value="<? echo $redirect?>" name="redirect" />
											<input class="submit" type="submit" name="action" value="Iniciar" /></td>
										</tr>
									
								</table>
								</form>
							</td>
						</tr>
					</table>
			  </td>
			</tr>
            <tr>
            	<td  bgcolor="#FFFFFF" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="37">
						<tr height="37">
						  <td class="bgBottom" height="37" align="center"><span class="siteBotLinks">
						  </span><span class="gen">&nbsp;</span>
                          	<span class="copyright">
                            	2014 &copy; Todos los derechos reservados <a href="#" target="_blank" class="copyright">22cero2</a>
                               <br />
                                
                                Desarrollado por:  <a href="http://www.22cero.co" target="_blank" class="copyright">22cero2</a>
                                <br />
                                <small>Powered By 22cero2 Tools v.<?php echo VERSION?></small>
                            </span></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p></p>
	</body>
</html>
<?php
	$dbo->close();
?>
