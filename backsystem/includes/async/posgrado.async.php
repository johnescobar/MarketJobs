<?php
include( "../../config.inc.php" );
SIMUtil::cache( "text/json" );

session_start(); 

$simsession = new SIMSession( SESSION_LIMIT );
	
//traemos lo datos de la session
$datos = $simsession->verificar();

//if( !is_object( $datos ) )
//	exit;

//encapsulamos los parammetros
//SIMUser::setFromStructure( $datos );

$dbo =& SIMDB::get();

 //seguridad para

$frm = SIMUtil::makeSafe( $_POST );
//$id = SIMNet::reqInt( "IDContacto" );

$columns = array();

$table = "Posgrado";
$key = "IDPosgrado";

switch( $frm["action"] )
{
	case "insert_posgrado":
		$frm = SIMUtil::varsLOG( $frm );
		$frm["Nombre"] = $frm["posgrado"];


		if( !is_object( $datos ) )
			exit;
			
		//insertamos los datos del asistente
		$id = $dbo->insert( $frm , $table , $key );

		$str_response = '
			"Nombre"' . ':"' . $frm["Nombre"]  . '", 
			"' . $key . '"' . ':"' . $id  . '", 
			"insertok"' . ':"true"
			
		';
		
		$str_columns = "{\"column\":{";

		$str_columns .= $str_response;
		
		$str_columns .= "}}";
		
		$_SESSION[ $key ] = $id;
	break;
	
	default:
		$sql = " SELECT * FROM " . $table . " WHERE Nombre LIKE '%" . $frm["name_startsWith"] . "%' ";
		$qry = $dbo->query( $sql );
		while( $r = $dbo->fetchArray( $qry ) )	
		{
			$columns[] = '{
				"Nombre"' . ':"' . $r["Nombre"]  . '", 
				"' . $key . '"' . ':"' . $r[ $key ]  . '", 
				"Nombre"' . ':"' . $r["Nombre"] . '"
				
			}';
			
		}//end while
		
		$crearcolumns = 1;
		
		
	break;
}

if( $crearcolumns == 1 )
{
	$str = "{\"column\":[";

	$str .= implode( "," , $columns );
	
	$str .= "]}";

}//end if
elseif( !empty( $str_columns ) )
	echo $str_columns;

echo $str;
?>