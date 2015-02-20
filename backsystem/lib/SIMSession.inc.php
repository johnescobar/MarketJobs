<?php
class SIMSession
{
	//tiempo limite de la sesion
	var $_limit;
	
	//constructor
	function SIMSession( $msgerror , $session_limit = 40 )
	{
		$this->_limit = $session_limit;
	}
	
	function crear( $idusuario , $datos )
	{
		session_start(); 
		
		$fecha = date( "Y-m-d H-i-s" , time() );

		$id = md5( uniqid( $fecha ) );
		
		$campos = array( "IDSesion" => $id , "IDUsuario" => $idusuario , "Inicio" => $fecha , "Datos" => $datos );
		
		$dbo =& SIMDB::get();
		
		$guardarqry = $dbo->insert( $campos , "Sesion" , "IDSession" );
		
		$_SESSION["SIM_SESION"] = $id; 
		
		if( $_SESSION["SIM_SESION"] )
			return true;
		return false;
	}

	function verificar()
	{
		session_start(); 
		
		$defaultdata = array( "flag" => false );
		
		$variable_session = $_SESSION["SIM_SESION"];
	
	
		//Primero verificar que el cookie este activo
		if ( !$variable_session )
			return "NSA";//sesion no activa
		else
		{			
			$this->clean();
			
			$dbo =& SIMDB::get();
			$sessiondata = $dbo->getFields( "Sesion" , "Datos" , "IDSesion = '" . $variable_session . "'" );
			
			if( !$sessiondata )
				return "XS";//expiro la sesion
			else
			{				
				$defaultdata = unserialize( stripslashes( $sessiondata ) );
				//Actualizo la sesio a la hora de la transaccion
				$dbo->query( "UPDATE Sesion SET Inicio = NOW() WHERE IDSesion='" . $variable_session . "'" );
				
				return $defaultdata;
			}
		}
		
	}
	
	function clean()
	{
		$dbo =& SIMDB::get();
		return $dbo->query( "DELETE FROM Sesion WHERE DATE_ADD( Inicio, INTERVAL " . $this->_limit . " MINUTE ) < NOW()" );
	}
	
	function eliminar()
	{
		session_start(); 
		
		$variable_session = $_SESSION["SIM_SESION"];
		
		session_destroy(); 
		
		$dbo =& SIMDB::get();
		
		return $dbo->deleteById( "Sesion" , "IDSesion" , $variable_session );
	}
}

?>