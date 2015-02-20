<?php
	require( "config.inc.php" );
	SIMUtil::cache();
	
	
	
	//handler de sesion
	$simsession = new SIMSession( SESSION_LIMIT );
	
	//traemos lo datos de la session
	$datos = $simsession->verificar();
	
	if( !is_object( $datos ) )
	{
		header( "location:login.php?msg=" . $datos );
		exit;
	}
	
	//encapsulamos los parammetros
	SIMUser::setFromStructure( $datos );

	
	SIMReg::setFromStructure( array(
					"title" => "Oferta",
					"table" => "Oferta",
					"key" => "IDOferta",
					"mod" => "Oferta"
	) );

	//Borrar Temporales
	//borrrar pregrado y posgrado
	$dbo->query("DELETE FROM TMPOfertaPregrado WHERE Sesion = '" . $_SESSION["MARKETJOBS"] . "' ");
	$dbo->query("DELETE FROM TMPOfertaPosgrado WHERE Sesion = '" . $_SESSION["MARKETJOBS"] . "' ");



//para validar los campos del formulario
$array_valida = array(  
	 "Cargo" => "Cargo" , "IDEmpresa" => "IDEmpresa" ,  "Origen" => "Origen"  	
); 


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



//extraemos las variables
$table = SIMReg::get( "table" );
$key = SIMReg::get( "key" );
$mod = SIMReg::get( "mod" );
$dbo =& SIMDB::get();

//creando las notificaciones que llegan en el parametro m de la URL
SIMNotify::capture( SIMResources::$mensajes[ SIMNet::req("m") ]["msg"] , SIMResources::$mensajes[ SIMNet::req("m") ]["type"] );	

$idempresa = SIMNet::req("idempresa");
$step = SIMNet::req( "step" );
if( empty( $step ) )
	$step = "descripcion";

$newmode = SIMNet::req( "newmode" );
if( empty( $newmode ) )
	$newmode = "insert";



		switch (  SIMNet::req( "action" )  ) {
			
			
			case "insert" :	
				if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
				{
					//los campos al final de las tablas
					$frm = SIMUtil::varsLOG( $_POST );
					
					//insertamos los datos
					$id = $dbo->insert( $frm , $table , $key );
					
					SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&m=insertarexito" );
				}
				else
					print_form( $_POST , "insert" , "Agregar Registro" );
			break;
			
			
			case "update" :	
				if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
				{
					//los campos al final de las tablas
					$frm = SIMUtil::varsLOG( $_POST );
					
					$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );
					
					$frm = $dbo->fetchById( $table , $key , $id , "array" );
					
					SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );
					
					print_form( $frm , "update" ,  "Realizar Cambios" );
				}
				else
					print_form( $_POST , "update" ,  "Realizar Cambios" );	
			break;
			
			
		
		} // End switch





	if( !empty( $idempresa ) )
	{
		$frm["IDEmpresa"] = $idempresa;
		$empresa = $dbo->fetchById("Empresa","IDEmpresa", $frm["IDEmpresa"] );
	}//end if

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<head>
	<title><?php echo APP_TITLE?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" href="css/estilos.css" type="text/css" />
	
	<script type="text/javascript" src="jscript/jquery-1.11.2.min.js"></script>

	<script src="jscript/jquery.colorbox.js"></script>

	<script src="jscript/jquery-ui/jquery-ui.js"></script>
	<link href="jscript/jquery-ui/jquery-ui.css" rel="stylesheet">


	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	
	<!--general-->
	<script type="text/javascript" src="jscript/sim.js"></script>
	<script type="text/javascript" src="jscript/common.js"></script>
	<script type="text/javascript" src="jscript/marketjobs.js"></script>
	<script type="text/javascript" src="jscript/jquery.selectboxes.js"></script>
	
	<!-- tree jquery -->
	<script src="jscript/treeview/jquery.cookie.js" type="text/javascript"></script>
	<script src="jscript/treeview/jquery.treeview.js" type="text/javascript"></script>
	<link rel="stylesheet" href="jscript/treeview/jquery.treeview.css" />

	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">



</head>
<body>

<table class=adminheading>
		<tr>
			<th> 
			<?php echo SIMReg::get( "title" )?> </th>
			
			
		</tr>
</table>
<?
//imprime el HTML de errores
SIMNotify::each();
?>


	<div class="row form-group">
        <div class="col-xs-8">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active"><a href="#oferta_descripcion">
                    <h4 class="list-group-item-heading">Paso 1</h4>
                    <p class="list-group-item-text">Descripción de la Oferta</p>
                </a></li>
                <li ><a href="#oferta_academica">
                    <h4 class="list-group-item-heading">Paso 2</h4>
                    <p class="list-group-item-text">Información Académica</p>
                </a></li>
                <li ><a href="#oferta_experiencia">
                    <h4 class="list-group-item-heading">Paso 3</h4>
                    <p class="list-group-item-text">Experiencia Laboral</p>
                </a></li>
                <li ><a href="#oferta_idiomas">
                    <h4 class="list-group-item-heading">Paso 4</h4>
                    <p class="list-group-item-text">Idiomas</p>
                </a></li>
            </ul>
        </div>
	</div>
	<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">

	<?
		include("includes/oferta/descripcion.inc.php");
		include("includes/oferta/academica.inc.php");
		include("includes/oferta/experiencia.inc.php");
		include("includes/oferta/idiomas.inc.php");
	?>
		<input type="hidden" name="ID" value="<? echo $frm[$key] ?>">
		<input type="hidden" name="action" value="<?=$newmode?>">
	</form>

</body>
</html>


<?










?>



