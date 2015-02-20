<?php
class SIMHTML
{
	
	function formRadioGroup( $options , $value , $name , $attrs = "" )
	{	
		$radiogroup = "";
	
		foreach( $options as $key => $val )
		{
			$radiogroup .= "<label class=\"radiogroup\"><input type=\"radio\" name=\"". $name ."\" id=\"" . $name . "\" value=\"". $val ."\" " . $attrs;
			
			if( !empty( $value ) )
				$radiogroup .= ( $val == $value ) ? " checked" : "";
				
			$radiogroup .= "> ".$key."</label>";
		}
		
		return $radiogroup;
	}
	
	
	
	function formCheckGroup( $options , $selection , $name , $sep = "" , $attrs = "" )
	{	
		$checkgroup = "";
	
		foreach( $options as $key => $val )
		{
			$checkgroup .= "<label class=\"checkgroup\"><input type=\"checkbox\" name=\"". $name ."\" id=\"" . $name . "\" value=\"". $val ."\" ". $attrs;
			
			if( !empty( $selection ) )
				$checkgroup .= ( in_array( $val , $selection ) ) ? " checked" : "";
				
			$checkgroup .= "> ". $key;
			$checkgroup .= "</label>" . $sep;
		}
		
		return $checkgroup;
	}
	
	
	function formPopup( $table , $field , $order , $name , $value = "" , $where = "1" , $text = "" , $style = "" , $attrs = "" )
	{
		
		$popup .= "<select name=\"" . $name . "\" id=\"" . $name . "\" class=\"" . $style . "\" " . $attrs . ">";
		$popup .= "<option value=\"\">" . $text . "</option>";
		
		$dbo =& SIMDB::get();
		$qry =& $dbo->all( $table , $where. " ORDER BY " . $order );
		
		while ( $r = $dbo->object( $qry ) )
		{
			$popup .= "<option value=" . $r->$name;
			$popup .= ( $r->$name == $value ) ? " selected" : "";
			$popup .=  " >" .  $r->$field  . "</option>";
		}
		
		$popup .= "</select>";
		
		return $popup;
	}

	function formPopupArray( $options , $selection , $name , $initialtext = "" , $class = "" , $attrs = "" )
	{	
		$checkgroup = "<select name=\"" . $name . "\" id=\"" . $name . "\" title=\"" . $name . "\" class=\"" . $class . "\" " . $attrs . "><option value=\"\">" . $initialtext . "</option>";
	
		foreach( $options as $key => $val )
		{
			$checkgroup .= "<option value=\"" . $key . "\"";
			
			if( !empty( $selection ) && $selection == $key ) 
				$checkgroup .= " selected";
			
			$checkgroup .= "> " . $val . "</option>";
		}
		
		$checkgroup .= "</select>";
		
		return $checkgroup;
	}

        function formPopupOcacion( $table , $field , $order , $name , $value = "" , $where = "1" , $text = "" , $style = "" , $attrs = "" )
	{

		$popup .= "<select name=\"" . $name . "\" id=\"" . $name . "\" class=\"" . $style . "\" " . $attrs . ">";
		$popup .= "<option value=\"\">" . $text . "</option>";

		$dbo =& SIMDB::get();
		$qry =& $dbo->all( $table , $where. " ORDER BY " . $order );

		while ( $r = $dbo->object( $qry ) )
		{
			$popup .= "<option value=" . $r->IDReceta;
			$popup .= ( $r->IDReceta == $value ) ? " selected" : "";
			$popup .=  " >" .  $r->$field  . "</option>";
		}

		$popup .= "</select>";

		return $popup;
	}


	
	function message( $message , $class )
	{
		return "<div class=\"mensaje " . $class . "\">" . $message . "</div>";
	}
	
	function tableCheckList( $descfield , $Key , $key_value , $table_option , $key_option , $table_reference , $check_name )
	{
		$dbo =& SIMDB::get();
		$option_checked = array();
		$array_option = array();
		
		$qry_option_checked = $dbo->query( "SELECT " . $key_option . " FROM " . $table_reference . " WHERE " . $Key . " = '" . $key_value . "' ");
		
		while( $r_option = $dbo->assoc( $qry_option_checked ) )
			$option_checked[] = $r_option[ $key_option ];
	
		$qry = $dbo->query( "SELECT * FROM " . $table_option );
										
		while ( $item_option = $dbo->object( $qry ) )
			$array_option[ $item_option->$descfield ] = $item_option->$key_option;
		
		return self::formCheckGroup( $array_option , $option_checked , $check_name );
	}


	function formPopUpHora( $fecha , $name , $style = "" , $text = "[Seleccione]" , $attrs = ""  )
	{
		$horasvalue = array("00:00:00","00:30:00","01:00:00","01:30:00","02:00:00","02:30:00","03:00:00","03:30:00","04:00:00",
		"04:30:00","05:00:00","05:30:00","06:00:00","06:30:00","07:00:00","07:30:00","08:00:00","08:30:00","09:00:00",
		"09:30:00","10:00:00","10:30:00","11:00:00","11:30:00","12:00:00","12:30:00","13:00:00","13:30:00","14:00:00",
		"14:30:00","15:00:00","15:30:00","16:00:00","16:30:00","17:00:00","17:30:00","18:00:00","18:30:00","19:00:00",
		"19:30:00","20:00:00","20:30:00","21:00:00","21:30:00","22:00:00","22:30:00","23:00:00","23:30:00");
		
		$horamostrar = array("12:00 am","12:30 am","01:00 am","01:30 am","02:00 am","02:30 am","03:00 am","03:30 am","04:00 am",
		"04:30 am","05:00 am","05:30 am","06:00 am","06:30 am","07:00 am","07:30 am","08:00 am","08:30 am","09:00 am",
		"09:30 am","10:00 am","10:30 am","11:00 am","11:30 am","12:00 m","12:30 pm","01:00 pm","01:30 pm","02:00 pm",
		"02:30 pm","03:00 pm","03:30 pm","04:00 pm","04:30 pm","05:00 pm","05:30 pm","06:00 pm","06:30 pm","07:00 pm",
		"07:30 pm","08:00 pm","08:30 pm","09:00 pm","09:30 pm","10:00 pm","10:30 pm","11:00 pm","11:30 pm");
		
		$popup .= "<select name=\"" . $name . "\" id=\"" . $name . "\" class=\"" . $style . "\" " . $attrs . ">";
		$popup .= "<option value=\"\">" . $text . "</option>";
		
		foreach( $horasvalue as $key => $horavalue )
		{
			$popup .= "<option value=".$horavalue;
			
			$popup .= ( ( $horavalue == $fecha ) ? " selected" : "" );
			
			$popup .=  " >".$horamostrar[ $key ]."</option>";
			
		}
		
		$popup .= "</select>";
		
		return $popup;
	}

	function URL( $text )
	{
		$tildes = array( '�' , '�' , '�' , '�' , '�' , '�' , '�' , '�' , '�' , '�' , ' ' , '�' );
		$sin_tildes = array( 'a' , 'e' , 'i' , 'o' , 'u' , 'a' , 'e' , 'i' , 'o' , 'u' , '-' , 'n' );
		//reemplazar tildes y espacios
		$text = str_replace( $tildes , $sin_tildes , strtolower( trim( $text ) ) );
		//otros caracteres
		$text = preg_replace( "/([^a-z0-9-_])/i" , "" , $text );
		return $text;
	}
	
	
	
	function jsAlert( $msg )
	{
		echo "<script type=\"text/javascript\">alert(\"" .  $msg . "\");</script> ";
		return true;
	}

	
	
	function jsRedirect( $msg )
	{
		echo "<script type=\"text/javascript\">location.href=\"" .  $msg . "\";</script> ";
		return true;
	}
	
	function generarThumb($pathNombre,$ImgOriginal,$anchoLimite,$altoLimite){
		$original = imagecreatefromjpeg($ImgOriginal);
		
		//Defino variables
		$anchoFoto = "";
		$altoFoto = "";
		//Armo las dimesiones de la imagen
		$ancho = imagesx($original);
		$alto = imagesy($original);
		if( $ancho > $anchoLimite ){
			$anchoFoto = $anchoLimite;
			$altoFoto = ($alto * $anchoLimite) / $ancho; 
		}
		if( $alto > $altoLimite ){
			$altoFoto = $altoLimite;
			$anchoFoto = ($ancho * $altoLimite) / $alto;
		}
		if( $anchoFoto > $anchoLimite ){
			$anchoFoto = $anchoLimite;
			$altoFoto = ($alto * $anchoLimite) / $ancho; 
		}
		if( $altoFoto > $altoLimite ){
			$altoFoto = $altoLimite;
			$anchoFoto = ($ancho * $altoLimite) / $alto;
		}
		if( $anchoFoto!="" && $altoFoto!="" ){
			$thumb = imagecreatetruecolor($anchoFoto,$altoFoto); // Lo haremos de un tamaÒo 150x150		
			imagecopyresampled($thumb,$original,0,0,0,0,$anchoFoto,$altoFoto,$ancho,$alto);
		}else{
			$thumb = imagecreatetruecolor($ancho,$alto); // Lo haremos de un tamaÒo 150x150		
			imagecopyresampled($thumb,$original,0,0,0,0,$ancho,$alto,$ancho,$alto);
		}
		
		//return imagejpeg($thumb,'thumb.jpg',90); // 90 es la calidad de compresiÛn
		return imagejpeg($thumb,$pathNombre,100); // 90 es la calidad de compresiÛn
	}
	
	function AnchoAlto($ancho,$alto,$anchoLimite,$altoLimite,$lado)
	{
		if( $ancho > $anchoLimite ){
			$anchoFoto = $anchoLimite;
			$altoFoto = ($alto * $anchoLimite) / $ancho; 
		}
		if( $alto > $altoLimite ){
			$altoFoto = $altoLimite;
			$anchoFoto = ($ancho * $altoLimite) / $alto;
		}
		if( $anchoFoto > $anchoLimite ){
			$anchoFoto = $anchoLimite;
			$altoFoto = ($alto * $anchoLimite) / $ancho; 
		}
		if( $altoFoto > $altoLimite ){
			$altoFoto = $altoLimite;
			$anchoFoto = ($ancho * $altoLimite) / $alto;
		}
		if( $lado == "Alto")
			return (int)$altoFoto;
		if( $lado == "Ancho")
			return (int)$anchoFoto;
	}	
	
	function mostrar_arbol( $menu_seccion, $idpadre, $class = "subnav" )
	{
		if( !empty( $menu_seccion[ $idpadre ] ) )
		{
	?>
			<ul class="<?=$class?> link-<?=$idpadre?>">
            	<?
                foreach( $menu_seccion[ $idpadre ] as $idseccion => $datos_seccion )
				{
					$link = "interna.php?ids=" . $idseccion;
					if( !empty( $datos_seccion["URL"] ) )
						$link = $datos_seccion["URL"];
					if( !empty( $menu_seccion[ $idseccion ] ) )
						$link = "javascript:void(0);";
                    ?>
                    <li>
                        <a href="<?=$link?>"><?=$datos_seccion["Nombre"]?></a>
                        <?
                        
						
						
						
						//para las secciones especiales
						switch( $idseccion )
						{
							case "49":
								$array_catestablecimientos = SIMReg::get("cat_estab");
								echo "<ul class=\"subnav2\">";
								foreach( $array_catestablecimientos as $idcat => $datos_cat )
									echo "<li><a href=\"establecimiento.php?idcat=" . $idcat . "\">" . $datos_cat["Nombre"] . "</a></li>";
								echo "</ul>";
							break;
							default:
								SIMHTML::mostrar_arbol( $menu_seccion, $idseccion, "subnav2" );
							break;
						}//end sw
						
						
                        ?>
                    </li>


				<?
				}//end for
				?>
            </ul>
	<?		
		}//end if
		return false;
	}//end function
	
	
}
?>