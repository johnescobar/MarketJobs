<?php
class SIMLog
{
	
	function insert( $id_usuario , $table , $mod , $transaccion , $operacion )
	{
		$IP = SIMUtil::get_IP();
		
		$operacion = substr( $operacion , 0 , 200 );
		
		$sentencia = urlencode( $operacion );
		
		$dbo =& SIMDB::get();
		
		$dbo->query( "INSERT INTO Log ( IDUsuario , Fecha , Modulo , Transaccion , Operacion , DireccionIP )
						VALUES( '" . $id_usuario . "' , NOW() , '" . $table . "','" . $mod . "','" . $transaccion . "','" . $sentencia . "','" . $IP . "')" );

		return true;
	}
}
?>