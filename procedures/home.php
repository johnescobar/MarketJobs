<?php
$sql_banner = $dbo->query( "SELECT * FROM Banner WHERE Publicar = 'S'  AND Ubicacion = 'Home' AND FechaInicio <= CURDATE() AND FechaFin >= CURDATE() ORDER BY RAND() " );
while($r_banner = $dbo->fetchArray( $sql_banner ))
{
	$bannerhome[$r_banner["IDBanner"]] = $r_banner;		
}//enwd while
$cantidadbanners = count( $bannerhome );


//traer productos destacados home
$sql_destacado = "SELECT Producto.* FROM Producto WHERE Publicar = 'S' AND Home = 'S' ORDER BY RAND() ";
$qry_destacado = $dbo->query( $sql_destacado );
while( $r_destacado = $dbo->fetchArray( $qry_destacado ) )
{
	$array_destacado[ $r_destacado["IDProducto"] ] = $r_destacado;
	//categorias
	$sql_categoria_producto = " SELECT ProductoCategoria.IDCategoria, Categoria.Nombre FROM ProductoCategoria LEFT JOIN Categoria ON ProductoCategoria.IDCategoria = Categoria.IDCategoria WHERE ProductoCategoria.IDProducto = '" . $r_destacado["IDProducto"] . "' ";
	$qry_categoria_producto = $dbo->query( $sql_categoria_producto );
	while( $r_categoria_producto = $dbo->fetchArray( $qry_categoria_producto ) )
		$array_destacado[ $r_destacado["IDProducto"] ]["Categoria"][] = $r_categoria_producto;
}//end while

$title = "Ortopedicos Williamson y Williamson";
$keywords = "Ortopedicos";
$description = "Todo en productos ortopedicos";

?>	