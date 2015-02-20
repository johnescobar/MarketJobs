<?php
error_reporting(E_ALL);
$modulos = SIMREsources::$modulos_2_buscar;
$qrystring = $_GET["querystring"];
$array_result = array();
$i = 0;

foreach( $modulos as $tabla => $datos )
{
	foreach( $datos["String"] as $key_string => $field )
	{
		$keyword = SIMUtil::makeboolean( $field  , $qrystring);
		$sql =  "SELECT * FROM " . $tabla . " WHERE " . $keyword;
		$qry = $dbo->query( $sql );
		while( $r = $dbo->fetchArray( $qry ) )
		{
			$array_result[$i]["Titulo"] = $r[ $datos["Title"] ];
			
			if( empty( $r["URL"] ) )
				$array_result[$i]["URL"] = $datos["URL"] . $r[ $datos["ID"] ];
			else
				$array_result[$i]["URL"] = $r[ "URL" ];

			$i++;
		}//end while
	}//end for
}//end for

//print_r($array_result);

$title = "Buscador Ortopedicos Williamson y Williamson - " . $qrystring ;
$keywords = "Ortopedicos";
$description = "Todo en productos ortopedicos";

?>	