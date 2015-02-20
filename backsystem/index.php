<?php
	require( "config.inc.php" );
	SIMUtil::cache();
	
	session_start();
	
	//handler de sesion
	$simsession = new SIMSession( SESSION_LIMIT );

	
	//creamos la sesion general del site
	if( empty( $_SESSION["MARKETJOBS"] ) )
	{
		$fecha = md5( uniqid( date("Y-m-d H:i:s") ) );
		$_SESSION["MARKETJOBS"] = $fecha;
	}//end if

	
	//traemos lo datos de la session
	$datos = $simsession->verificar();
	
	if( !is_object( $datos ) )
	{
		header( "location:login.php?msg=" . $datos );
		exit;
	}
	
	//encapsulamos los parammetros
	SIMUser::setFromStructure( $datos );

    //seguridad para
    foreach($_GET as $clave=>$valor)
            $_GET[$clave] = SIMUtil::antiinjection($valor);

    $sql_sectores = " SELECT IDSector, Nombre FROM Sector  ";
    $qry_sectores = $dbo->query( $sql_sectores );
    while( $r_sectores = $dbo->fetchArray( $qry_sectores ) )
    	$array_sectores[ $r_sectores["IDSector"] ] = $r_sectores["Nombre"];

   	SIMReg::set("sectores", $array_sectores);


   	$sql_areas = " SELECT IDArea, Nombre FROM Area  ";
    $qry_areas = $dbo->query( $sql_areas );
    while( $r_areas = $dbo->fetchArray( $qry_areas ) )
    	$array_areas[ $r_areas["IDArea"] ] = $r_areas["Nombre"];

   	SIMReg::set("areas", $array_areas);


    $sql_usuarios = " SELECT * FROM Usuario  ";
    $qry_usuarios = $dbo->query( $sql_usuarios );
    while( $r_usuarios = $dbo->fetchArray( $qry_usuarios ) )
    	$array_usuarios[ $r_usuarios["IDUsuario"] ] = $r_usuarios;

   	SIMReg::set("usuarios", $array_usuarios);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<head>
<title><?php echo APP_TITLE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" href="css/admin.css?<?=rand(1,100)?>" type="text/css" />


	<link rel="stylesheet" href="css/estilos.css?<?=rand(1,100)?>" type="text/css" />
	<link rel="stylesheet" href="css/colorbox.css?<?=rand(1,100)?>" type="text/css" />
	
	<!--jQuery-->
	<script type="text/javascript" src="jscript/jquery-1.11.2.min.js"></script>

	<script src="jscript/jquery.colorbox.js"></script>

	<script src="jscript/jquery-ui/jquery-ui.js"></script>
	<link href="jscript/jquery-ui/jquery-ui.css" rel="stylesheet">

	
	<!--general-->
	<script type="text/javascript" src="jscript/sim.js"></script>
	<script type="text/javascript" src="jscript/common.js"></script>
	<script type="text/javascript" src="jscript/marketjobs.js"></script>
	<script type="text/javascript" src="jscript/jquery.selectboxes.js"></script>
	
	<!-- tree jquery -->
	<script src="jscript/treeview/jquery.cookie.js" type="text/javascript"></script>
	<script src="jscript/treeview/jquery.treeview.js" type="text/javascript"></script>
	<link rel="stylesheet" href="jscript/treeview/jquery.treeview.css" />
	
	    
		
	
</head>
<body class="mainbody" >
	



	<div id="a-top">
			
		<div id="a-systembar">
			<div class="a-toggle">
				<ul>
					<li>
						<h1>Administrador 22cero2</h1>
					</li>
				</ul>
			</div>
			<div id="site_message_box" class="hide">
				<!--placeholder for system message-->
			</div>
			<div id="billing_message_box" class="hide">
				<!--placeholder for billing message-->
			</div>
			<ul class="a-user ttw-notification-menu">
				
				<li>
					Bienvenido: <?php echo htmlentities( SIMUser::get( "Nombre" ) )?>       </li>



				<li>
					<a href="#" class="notification-menu-item" id="projects">Notificaciones<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(245, 108, 126);">0</span></a>
				</li>

				<li>
					<a href="?mod=Admin">Admin</a>
				</li>
		        
		        <li class="actualizacion">
					<a href="login.php?action=Salir">Salir</a>

				</li>

				
			</ul>
		</div>
		
		<?
			include("includes/menu.php");
		?>

		
	</div>





	<table width="99%" border="0" cellspacing="0" cellpadding="1">
	  	<tr>
			<td valign="top" >
					
					
                    
					<table width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
		  				<tr >
							<td valign="top" width="100%"  >
								<div class="content">
									<table width="100%" border="0">
										<tr>
											<?php 
											$mod = $_GET[mod];
											if( ( $mod != "Admin" ) )
											{
											?>
		                                	<td class="shortcuts">
		                                    <?php
		                                    
		                                    	$mod = SIMUtil::makeSafe( $_GET["mod"] );
		                                    	
												if( empty( $mod ) )
													$mod = "Admin";
												
												include( "shortcuts/".$mod.".php" );
											
											?>
		                                    </td>
		                                    <?php 
											}
		                                    ?>
		                                    <td class="contenido">
		                                     	<?php 
														include( $mod.".php" );
												?>
		                                     </td>
	                                     </tr>
	                                </table>   
                                 </div>
							 </td>
						</tr>
					</table>
					
		</td>
			</tr>
			<tr >
				<td  bgcolor="#FFFFFF" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="37">
						<tr height="37">
						  <td class="bgBottom" height="37" align="center"><span class="siteBotLinks">
						  </span><span class="gen">&nbsp;</span>
                          	<span class="copyright">
                            	2014 &copy; Todos los derechos reservados <a href="#" target="_blank" class="copyright">22cero2</a>
                               <br />
                                
                                Desarrollado por:  <a href="http://www.22cero2.com" target="_blank" class="copyright">22cero2</a>
                                <br />
                                <small>Powered By SIM Tools v.<?php echo VERSION?></small>
                            </span></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
<?php
	$dbo =& SIMDB::get();
	$dbo->close();
?>