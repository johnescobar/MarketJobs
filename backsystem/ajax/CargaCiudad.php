<?php
header('Content-Type: text/txt; charset=ISO-8859-1');
require( "../config.inc.php" );

$Ciudades = $dbo ->all("Ciudad","IDDepartamento = '".$_POST['IDDepartamento']."' ORDER BY Nombre");
	while( $RCiudades = $dbo->fetchArray( $Ciudades ) )
		$ArrayCiudad[$RCiudades[IDCiudad]]=$RCiudades;

?>
{
<?php
$fin=count($ArrayCiudad);
foreach($ArrayCiudad as $key => $value)
{
	if($fin-- == 1)
	{
	?>
		"<?php echo $value['IDCiudad'];?>" : "<?php echo $value['Nombre'];?>"
	<?	
	}
	else
	{
	?>
		"<?php echo $value['IDCiudad'];?>" : "<?php echo $value['Nombre'];?>",
	<?
	}
}
?>
}