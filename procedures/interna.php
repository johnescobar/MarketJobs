<?php

$ids = SIMUtil::antiinjection( $_GET["ids"] );
$idn = SIMUtil::antiinjection( $_GET["id"] );



//seccion
$sql_seccion = $dbo->query( "SELECT * FROM Seccion WHERE Publicar = 'S' AND IDSeccion = '". $ids ."' ORDER BY Orden ASC" );
$seccion = $dbo->fetchArray( $sql_seccion );

$i = 0;
$sql_noticia = $dbo->query( "SELECT * FROM Noticia WHERE IDSeccion = '". $seccion["IDSeccion"] ."'  AND Publicar = 'S' AND FechaInicio <= CURDATE() AND FechaFin >= CURDATE() ORDER BY Orden ASC" );
while($r_noticia = $dbo->fetchArray( $sql_noticia ))
{
	if(!$id)
	{
		$id = $r_noticia["IDNoticia"];
		$key_active = $i;
	}//end if

	$r_noticia[URL] = 'interna.php?ids='.$r_noticia["IDSeccion"].'&amp;id='.$r_noticia["IDNoticia"];
	
	$noticia[ $i ]= $r_noticia;
	$i++;
	
}//end while



$title = $seccion["SEO_Title"];
$keywords = $seccion["SEO_KeyWords"];
$description = $seccion["SEO_Description"];

?>	