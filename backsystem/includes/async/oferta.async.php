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

$table = "Oferta";
$key = "IDOferta";

switch( $frm["action"] )
{
	case "insert_pregrado":
		$frm = SIMUtil::varsLOG( $frm );



		$array_pregrado = $dbo->fetchById("Pregrado", "IDPregrado",  $frm["IDPregrado"] , "array");
		
		//insertamos los datos del asistente
		$frm["Sesion"] = $_SESSION["MARKETJOBS"];
		$frm["Carrera"] = $array_pregrado["Nombre"];
		$table = "TMPOfertaPregrado";
		$key = "IDOferta";


		$id = $dbo->insert( $frm , $table , $key );

		$str = '
			"Nombre"' . ':"' . $array_pregrado["Nombre"]  . '", 
			"IDPregrado"' . ':"' . $array_pregrado["IDPregrado"]  . '", 
			"insertok"' . ':"true"
			
		';
		
		$str_columns = "{\"column\":{";

		$str_columns .= $str;
		
		$str_columns .= "}}";
		
		$_SESSION[ $key ] = $id;
	break;

	case "eliminar_pregrado":
		$frm = SIMUtil::varsLOG( $frm );

		
		$table = "TMPOfertaPregrado";
		$key = "IDOferta";


		$id = $dbo->delete( $table , "IDPregrado = '" . $frm["IDPregrado"]  . "' AND Sesion = '" . $_SESSION["MARKETJOBS"] . "' " );

		$str = '
			"deleteok"' . ':"true"
			
		';
		
		$str_columns = "{\"column\":{";

		$str_columns .= $str;
		
		$str_columns .= "}}";
		
		$_SESSION[ $key ] = $id;
	break;



	case "insert_posgrado":
		$frm = SIMUtil::varsLOG( $frm );



		$array_posgrado = $dbo->fetchById("Posgrado", "IDPosgrado",  $frm["IDPosgrado"] , "array");
		
		//insertamos los datos del asistente
		$frm["Sesion"] = $_SESSION["MARKETJOBS"];
		$frm["Carrera"] = $array_posgrado["Nombre"];
		$table = "TMPOfertaPosgrado";
		$key = "IDOferta";


		$id = $dbo->insert( $frm , $table , $key );

		$str = '
			"Nombre"' . ':"' . $array_posgrado["Nombre"]  . '", 
			"IDPosgrado"' . ':"' . $array_posgrado["IDPosgrado"]  . '", 
			"insertok"' . ':"true"
			
		';
		
		$str_columns = "{\"column\":{";

		$str_columns .= $str;
		
		$str_columns .= "}}";
		
		$_SESSION[ $key ] = $id;
	break;

	case "eliminar_posgrado":
		$frm = SIMUtil::varsLOG( $frm );

		
		$table = "TMPOfertaPosgrado";
		$key = "IDOferta";


		$id = $dbo->delete( $table , "IDPosgrado = '" . $frm["IDPosgrado"]  . "' AND Sesion = '" . $_SESSION["MARKETJOBS"] . "' " );

		$str = '
			"deleteok"' . ':"true"
			
		';
		
		$str_columns = "{\"column\":{";

		$str_columns .= $str;
		
		$str_columns .= "}}";
		
		$_SESSION[ $key ] = $id;
	break;
	
	
}



echo $str_columns;
?>