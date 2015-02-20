<?php

if( !$datos_cliente->FLAG )
{
	header("Location:registro.php?error=di");
	exit;
}//end if

if($_POST["action"])
	$action = $_POST["action"];
elseif($_GET["action"])
	$action = $_GET["action"];

$msg = $_GET["msg"];
$view = SIMNet::req("view");

switch( $action )
{

		
	case "update":
		
		foreach( $_POST as $key => $value )
			$_POST[$key] = SIMUtil::antiinjection( $value );
		
		$sql_update = "UPDATE Registro SET Nombre = '" . $_POST["Nombre"] . "', Apellido = '" . $_POST["Apellido"] . "', NumeroDocumento = '" . $_POST["NumeroDocumento"] . "', Celular = '" . $_POST["Celular"] . "', Direccion = '" . $_POST["Direccion"] . "', Email = '" . $_POST["Email"] . "' WHERE IDRegistro = '" . $datos_cliente->IDRegistro . "' ";
		$qry_update = $dbo->query( $sql_update );
		header("Location: micuenta.php?msg=ok");
		exit;
		
	break;

	case "updateBeneficiario":
		$frm = SIMUtil::varsLOG( $_POST );
		$frm["IDRegistro"] = $datos_cliente->IDRegistro;

		$exceptions = array("FechaNacimiento", "Barrio");

		$id = $dbo->update( $frm , "Beneficiario" , "IDBeneficiario" , $_POST["IDBeneficiario"], $exceptions );

		$msg = "Los datos de tu beneficiario han sido actualizados correctamente";
	break;
	case "insertBeneficiario":
		$frm = SIMUtil::varsLOG( $_POST );
		$frm["IDRegistro"] = $datos_cliente->IDRegistro;
		$dbo->insert( $frm , "Beneficiario" , "IDBeneficiario" );
		$msg = "Se ha agregado a " . $frm["Nombre"] . " a tu registro";

	break;
	
}//end sw

//verificar vista
switch( $view ){
	case "data":
		$active_data = "class=\"active\"";
		$vista = "includes/micuenta/data.php";
	break;
	case "pedidos":
		$active_data = "class=\"active\"";
		$vista = "includes/micuenta/pedidos.php";

		//get pedidos
		$sql_pedidos = "SELECT * FROM Pedido WHERE IDRegistro = '" . $datos_cliente->IDRegistro . "' ";
		$qry_pedidos = $dbo->query( $sql_pedidos );
		while( $r_pedidos = $dbo->fetchArray( $qry_pedidos ) )
		{
			//get detalle
			$sql_detalle = "SELECT D.*, P.ImagenThumb, P.Descripcion FROM DetallePedido D LEFT JOIN Producto P ON P.IDProducto = D.IDProducto  WHERE D.IDPedido = '" . $r_pedidos["IDPedido"] . "' ";
			$qry_detalle = $dbo->query( $sql_detalle );
			while( $r_detalle = $dbo->fetchArray( $qry_detalle ) )
			{
				$productos_pedido[] = $r_detalle;
			}//end while
		}//end while


	break;
	case "beneficiario":
		$active_beneficiario = "class=\"active\"";
		$vista = "includes/micuenta/beneficiarios.php";

		$beneficiarios = $dbo->fetchAll( "Beneficiario" , "IDRegistro = '" . $datos_cliente->IDRegistro . "'"  );
		if( $_POST["dataBeneficiario"] )
		{
			$data_beneficiario = $dbo->fetchById( "Beneficiario", "IDBeneficiario", $_POST["dataBeneficiario"], "array" );
		}//end if


	break;
	case "add_beneficiario":
		$active_add_beneficiario = "class=\"active\"";
		$vista = "includes/micuenta/add_beneficiario.php";



	break;
	default:
		$active_data = "class=\"active\"";
		$vista = "includes/micuenta/data.php";
	break;
}//end sw
	
	
	
$title = ": Login y Registro";
$keywords = "Login y Registro";
$description = "Login y Registro";	
	
	
	
	
?>