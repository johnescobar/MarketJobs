<?php
class Cart
{  

	function add_item( $session, $datos ) 
	{
		$dbo =& SIMDB::get();
		$table = "Carro";
		$key = "ID";
		$datos["Session"] = $session;

		// Checks to see if they already have that product in their list 
	
	   	$in_list =  "SELECT * FROM Carro WHERE Session = '" . $session . "' AND IDProducto = '" . $datos["IDProducto"] . "' ";
	
	   	$result = $dbo->query( $in_list );
	
	   	$num_rows = $dbo->rows( $result );
	
	   	if( $num_rows == 0 )
	   	{
			$id = $dbo->insert( $datos , $table , $key );
	   	}
	   	else
	   	{
	
			$row = $dbo->fetchArray($result);	
		  	$quantity = $datos["Cantidad"] + $row["Cantidad"];	
		  	$months = $datos["Meses"] + $row["Meses"];
		  	
		  	$sql =  "UPDATE Carro SET Cantidad = '" . $quantity . "', Meses = '" . $months . "' WHERE ";	
		  	$sql .=  "Session = '" . $session . "' ";	
		  	$sql .=  " AND ID = '" . $row[ID] . "' ";
	
		  	$dbo->query( $sql );
	
	   	}	
	}
        
	
	// delete a specified item 
	
	function delete_item( $session, $product)
	{
		$dbo =& SIMDB::get();
		
		$SqlItem = "SELECT * 
		              FROM Carro
		             WHERE IDProducto = '" . $product . "'
		               AND Session = '" . $session . "'
				   ";
		$QryItem = $dbo->query( $SqlItem );
		if( $dbo->rows( $QryItem ) > 0 )
		{
	  		$dbo->query( "DELETE FROM Carro WHERE  Session = '" . $session ."' AND IDProducto = '" . $product . "' ");
		}//end if
		
	}//end function
	
	
	
	// modifies a quantity of an item
	function modify_quantity($session, $idproducto, $quantity, $months)
	{
		$dbo =& SIMDB::get();
	
	   	$sql =  "UPDATE Carro SET Cantidad = '" . $quantity . "', Meses = '" . $months . "'  ";	
		$sql .=  "WHERE Session='" . $session . "' AND IDProducto = '" . $idproducto . " .' ";
	
	   	$dbo->query( "$sql");
	
	}
	
	// clear all content in their cart
	function clear_cart($session)
	{
	
		$dbo =& SIMDB::get();
	   	$dbo->query( "DELETE FROM Carro WHERE Session='" . $session . "'");
	
	}
	
	 //add up the shopping cart total 
	
	
	
	function cart_total($session)
	{
	
	   	$dbo =& SIMDB::get();
	   	$result = $dbo->query( "SELECT * FROM Carro WHERE Session='" . $session . "'");
	
	   	if($dbo->rows($result) >0)
	   	{
	
			while($carro = $dbo->object($result))	
		  	{	
				//$total = $total + (($carro->ValorUnitario + $carro->IVA)*$carro->Cantidad);
				$total = $total + ( $carro->ValorUnitario * $carro->Cantidad * $carro->Meses );
				$iva = $iva + ( $carro->IVA * $carro->Cantidad * $carro->Meses );
			}//end while
	   	}//end if



		$array_total["SubTotal"] = $total;
		$array_total["IVA"] = $iva;

		$array_total["Total"] = $total + $iva;


		return $array_total;
	}
	function cart_count($session)
	{
	
	   	$dbo =& SIMDB::get();
	   	$result = $dbo->query( "SELECT SUM(Cantidad) as Cantidad FROM Carro WHERE Session='" . $session . "'");
	
	   	if($dbo->rows($result) >0)
	   	{
	   		$carro = $dbo->object($result);
			return $carro->Cantidad;
			
	   	}	
		return false;
	}	
	function display_contents($session)
	{
	
		$dbo =& SIMDB::get();
		$count = 0;
		$transporte = 0;
		$parametros = $dbo -> fetchById( "Parametro" , "IDParametro" , "1" , "array" );

		$sql_carro = "SELECT * FROM Carro WHERE session='" . $session . "'";
		$result = $dbo->query( $sql_carro );
	
		while($carro = $dbo->fetchArray($result))
		{
			$result_inv = $dbo->query( "SELECT P.* FROM Producto P WHERE  P.IDProducto ='" . $carro["IDProducto"] . "' ");
	
			$Producto = $dbo->fetchArray($result_inv);
	
			$contents["productos"][$count] = $Producto;
			$contents["productos"][$count][ "Total"] = number_format( ( $carro[ "ValorUnitario" ] + $carro[ "IVA" ] ) * $carro[ "Cantidad" ] * $carro[ "Meses" ] );
			$contents["productos"][$count][ "FotoCart"] = PRODUCTO_ROOT . $Producto["ImagenThumb"];
			$contents["productos"][$count][ "Cantidad"] = $carro["Cantidad"];
			$contents["productos"][$count][ "Meses"] = $carro["Meses"];
			$contents["productos"][$count][ "Unidad"] = "unidad";
			$contents["productos"][$count][ "Tiempo"] = "mes";

			if( $carro["Cantidad"] > 1 )
				$contents["productos"][$count][ "Unidad"] = "unidades";

			if( $carro["Meses"] > 1 )
				$contents["productos"][$count][ "Tiempo"] = "meses";

			$contents["productos"][$count][ "Tipo"] = $carro["Tipo"];

			//verificar si el producto es de categoria = 1, si hay alguno se suma transporte
			$sql_categoria_producto = " SELECT ProductoCategoria.IDCategoria, Categoria.Nombre FROM ProductoCategoria LEFT JOIN Categoria ON ProductoCategoria.IDCategoria = Categoria.IDCategoria WHERE ProductoCategoria.IDProducto = '" . $Producto["IDProducto"] . "' ";
			$qry_categoria_producto = $dbo->query( $sql_categoria_producto );
			while( $r_categoria_producto = $dbo->fetchArray( $qry_categoria_producto ) )
			{
				$contents["productos"][$count][ "Categoria"] = $r_categoria_producto["IDCategoria"];
				if( $r_categoria_producto["IDCategoria"] == 1 ) //incluir transporte
					$transporte = $parametros["Transporte"];

			}//end while categoria
			
			$count ++;
	
		}//end while
		 
		 //PARA LAS DE AYEAR
	
		$array_total = $this->cart_total($session);

		$total = $array_total["Total"];
		$totaliva = $array_total["IVA"];

		$contents[ "SubTotal"] = number_format( $array_total["SubTotal"] ,2,',','.');
		
		$contents[ "VTotal"] = number_format($total,2,',','.');
		$contents[ "VTotalTransporte"] = number_format($total + $transporte,2,',','.');
		

		$contents[ "TotalIVA"] = number_format($totaliva,2,',','.');

		$contents[ "Transporte"] = number_format($transporte,2,',','.');

		$contents[ "Items"] = $this->num_items($session);
		$contents[ "strItems"] = "producto";
		if( $contents[ "Items"] > 1 )
			$contents[ "strItems"] = "productos";

		

		return $contents;
	
	}//end function
	
	
	function num_items($session)
	{
	
		$dbo =& SIMDB::get();
		
		$result = $dbo->query( "SELECT * FROM Carro WHERE Session='$session'");
	
		$num_rows = $dbo->rows($result);
	
		return $num_rows;
	
	 }
	
	 
	function order($session,$IDCliente,$Nombre, $NumeroDocumento, $Email)
	{
	
		$dbo =& SIMDB::get();
	
	
		$qry_insert_pedido = $dbo->query("Insert Into Pedido (,IDCliente,NumeroDocumento,Fecha,Estado)
	
									   Values ( '$IDCliente','$NumeroDocumento',NOW(),'G')
	
									 ");
	
		
	
		/********ENVIA EMAIL*********/
	
		$Asunto = "Pedido Generado Sitio Web Calzado Caprino";
	
		$To = "comercial@proveedoresenchina.com";
	
		$From = "pedidos@proveedoresenchina.com";
	
		
	
		$cab = "From:$From\n";
	
		$body = "Se ha generado un nuevo pdido con los siguientes datos:\n\n";
	
		$body .= "Cliente:  ".$Nombre."\n";
	
		$body .= "Numero Documento: ".$NumeroDocumento."\n";
	
		$body .= "Email: ".$Email."\n";
	
		
	
		$body .= "Productos:\n";
	
		/********ENVIA EMAIL*********/
	
		$qry_carro = $dbo->query( "SELECT * FROM Carro WHERE session='$session'");
	
		while($carro = $dbo->object($qry_carro))
		{
	
			$result_inv = $dbo->query( "SELECT P.Nombre,P.Referencia,P.IDProducto FROM Producto P
	
									WHERE P.IDProducto ='$carro->IDProducto' ");
	
			$Producto = $dbo->object($result_inv);
	
	
			$qry_insert_item = $dbo->query("Insert Into DetallePedido (IDPedido,IDProducto,Nombre,Cantidad,Referencia)
	
										 Values ('$IDPedido','$Producto->IDProducto','$Producto->Nombre',
	
												'$carro->Cantidad','$Producto->Referencia')
										");
	
										
			$body .="\t".$Producto->Nombre;
	
			$body .="\t".$Producto->Referencia;
	
			$body .="\t".$carro->Cantidad."\n";
	
	
			$IDProducto = $Producto->Referencia;
	
	
	
	
		}//end while
	
		mail($To,$Asunto,$body,$cab);
	
	
		return $IDPedido;
	
	 
	
	}//end functioin
	
	 
	
	
 
	
 
}//end class


?>