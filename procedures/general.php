<?php
include("backsystem/config.inc.php");
SIMUtil::cache();

//Session 22cero2
session_start();
$session = $_SESSION["cero2"];
if( !$session )
{
	$fecha = date( "Y-m-d H-i-s" , time() );
	$id = md5( uniqid( $fecha ) );
	$_SESSION["cero2"] = $id; 
}//end if

//SESSION CLIENTE
//handler de sesion
$simsession = new SIMSessionCliente( SESSION_LIMIT );

//traemos lo datos de la session
$datos_cliente = $simsession->verificar();

//seguridad para post y get
foreach( $_GET as $clave => $valor )
{
	$_GET[$clave] = SIMUtil::antiinjection( $valor );
}

foreach( $_POST as $clave => $valor )
{
	$_POST[$clave] = SIMUtil::antiinjection( $valor );
}

$Parametro = $dbo->fetchById( "Parametro" , "IDParametro" , "1" , "array" );

//menu superior
$sql_menu = $dbo->query( "SELECT * FROM Seccion WHERE Publicar = 'S' AND IDPadre = 0 AND FIND_IN_SET('Menu', Ubicacion) > 0  ORDER BY Orden, IDSeccion" );
while( $r_menu = $dbo->fetchArray( $sql_menu ) )
{	
	
	if(!$r_menu["URL"])
		$r_menu["URL"] = 'interna.php?ids='.$r_menu["IDSeccion"];
				
	switch($r_menu["IDSeccion"])
	{
		
		default:
			//subseccion
		   	$sql_sub_seccion = "SELECT * FROM Seccion WHERE Publicar = 'S' AND IDPadre = " . $r_menu["IDSeccion"];
			$qry_sub_seccion = $dbo->query( $sql_sub_seccion );
			while( $r_sub_seccion = $dbo->fetchArray( $qry_sub_seccion ) )
			{
					 
				if(!$r_sub_seccion["URL"])
					$r_sub_seccion["URL"] = '/interna.php?ids='.$r_sub_seccion["IDSeccion"];
				
				$array_menu[ $r_menu["IDSeccion"] ][ $r_sub_seccion["IDSeccion"] ] = $r_sub_seccion;
			}//end while
		break;
	
	}//end switch
	
	$array_menu[0][$r_menu["IDSeccion"]] = $r_menu;


}//end while

//traer categorias_padre
$sql_categoria = "SELECT * FROM Categoria WHERE Publicar = 'S' ORDER BY Orden";
$qry_categoria = $dbo->query( $sql_categoria );
while( $r_categoria = $dbo->fetchArray( $qry_categoria ) )
{

	$array_categoria[ $r_categoria["Tipo"] ][ $r_categoria["IDCategoria"] ] = $r_categoria;
	if( $r_categoria["IDPadre"] == 0 )
		$array_categoria_padre[ $r_categoria["IDCategoria"] ] = $r_categoria;
}//end while	

$array_css_home = array("credenciales","postales","tarjetas","empaques");

//CArro de Compras
$cart = new Cart();
$datos_carro = $cart->display_contents( $session );

//producto destacado
$sql_destacado = "SELECT Producto.* FROM Producto WHERE Publicar = 'S' AND Destacado = 'S' ORDER BY RAND() LIMIT 1 ";
$qry_destacado = $dbo->query( $sql_destacado );
$destacado = $dbo->fetchArray( $qry_destacado );
$destacado["IDCategoria"] = $dbo->getFields( "ProductoCategoria" , "IDCategoria" ,  "IDProducto = '" . $destacado["IDProducto"] . "' " );
	
?>