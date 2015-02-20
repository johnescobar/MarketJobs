<?php
class SIMUtil
{
	
	function cache( $type = "text/html" )
	{
		header( "Content-type: " . $type );
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );             
		header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" ); 
		header( "Cache-Control: no-cache, must-revalidate" );           
		header( "Pragma: no-cache" );
		return true;                                   
	}
	function generarPassword($caracteres){
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$cad = "";
		for($i=0;$i<$caracteres;$i++) {
			$cad .= substr($str,rand(0,62),1);
		}
		return $cad;
	}
	function sendMail( $To , $Subject , $Msg , $vars , $exclude , $From , $cabs )
	{
		$mess = "";
	
		foreach( $vars as $key => $val )
		{
		     if( !in_array( $key , $exclude ) )
		     	$mess .=  " - " . $key . " : " . $val . "\n";
		}
	
		$Msg .= "\n" . $mess;
	
		if ( mail( $To , $Subject , $Msg , "From: ".$From . implode( "\n" ) , $cabs ) )	
			return true;
		else
			return false;		
	}
	
	function repetition( $reps = 2 )
	{
		static $skip = 1;
	    
	    if ( $skip++ == $reps )
	    {
	      $skip = 1;
	      return true;
	    }
	    else
	    	return false;
	}
	
	function encrypt($plain_text,$password,$iv_len=16)
	{
		$plain_text.="\x13";
		$n=strlen($plain_text);
		if($n%16)$plain_text.=str_repeat("\0",16-($n%16));
		$i=0;
		$enc_text=get_rnd_iv($iv_len);
		$iv=substr($password^$enc_text,0,512);
		while($i<$n){
		$block=substr($plain_text,$i,16)^pack('H*',md5($iv));
		$enc_text.=$block;
		$iv=substr($block.$iv,0,512)^$password; 
		$i+=16;
		}
		return base64_encode($enc_text);
	}

	function decrypt($enc_text,$password,$iv_len=16){
		$enc_text=base64_decode($enc_text);
		$n=strlen($enc_text);
		$i=$iv_len;
		$plain_text='';
		$iv=substr($password^substr($enc_text,0,$iv_len),0,512);
		while($i<$n){
		$block=substr($enc_text,$i,16);
		$plain_text.=$block^pack('H*',md5($iv));
		$iv=substr($block.$iv,0,512)^$password;
		$i+=16;
		}
		return preg_replace('/\\x13\\x00*$/','',$plain_text);
	}
	
	function makeUrlString()
	{
		$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
		
	    $cgi = $REQUEST_METHOD == 'GET' ? $_GET : $_POST;
	    reset ( $cgi );
	    
	    foreach( $cgi as $key => $value )
	    {
	      if ( $key != "row"  && !empty( $value ) && $key != "Submit" )
	        $query_string .= "&" . $key . "=" . $value;
	    }
	    return $query_string;
  	}

	function getMicroTime()
	{ 
	    list( $usec , $sec ) = explode( " " , microtime() ); 
	    return ( (float)$usec + (float)$sec ); 
	} 


	function varsLOG( $frm , $usuario , $table , $key , $id , $do )
	{
		$usuario = SIMUser::get("Nombre");
		$table = SIMReg::get("table");
		$key = SIMReg::get("key");
		$id = SIMNet::reqInt("id");
		$do = SIMNet::get( "action" );
		
		
		$dbo =& SIMDB::get();
		$qry = $dbo->query( "SELECT UsuarioTrCr , FechaTrCr , UsuarioTrEd , FechaTrEd FROM " . $table . " WHERE " . $key . " = '" . $id . "'" );
		$r = $dbo->object( $qry );
		
		
		
		$now = date( "Y-m-j h:i:s" );
		
		if( $do == "edit" )
		{
			$frm['UsuarioTrEd'] = $usuario;
			$frm['FechaTrEd'] = $now;
			$frm['UsuarioTrCr'] = $r->UsuarioTrCr;
			$frm['FechaTrCr'] = $r->FechaTrCr;	
		}
		else
		{
			$frm['UsuarioTrCr'] = $usuario;
			$frm['FechaTrCr'] = $now;
			$frm['UsuarioTrEd'] = $r->UsuarioTrEd;
			$frm['FechaTrEd'] = $r->FechaTrEd;
		}
		
		return $frm;
	}
	
	
	function makeSafe( $data )
	{
		if( !get_magic_quotes_gpc()  )
		{
			if( is_array( $data ) )
			{
				foreach( $data as $key => $value )
				{
					if( is_array( $value ) )
						$data[$key] = self::makeSafe( $value );
					else
						$data[$key] = addslashes( $value );
				}					
			}
			else
				return addslashes( $data );
		}
		
		return $data;
	}

	function tiempo( $fecha ) 
	{
		$horafinal = "";
		
		$fechahora = explode( " " , $fecha );
		
		$fecha = explode( "-" , $fechahora[0] );
		$hora = explode( ":" , $fechahora[1] );
		
		if( !empty( $fecha ) )
			$horafinal .= SIMResources::$meses[ $fecha[1] - 1 ] . " " . $fecha[2] . " de " . $fecha[0];
		
		if( !empty( $hora[0] ) )
		{
			$hora[0] = (int)$hora[0];
			
			if( $hora[0] > 12 )
			{
				$hora[0] = $hora[0] - 12;
				$merid = "pm";
				
				if( $hora[0] < 10 )
					$hora[0] = "0" . $hora[0];
			}
			else
			{
				if( $hora[0] == 12 )
					$merid = "pm";
				else
					$merid = "am";
					
			}
				
				
			 $horafinal .= " " . $hora[0] . ":" . $hora[1] . ":" . $hora[2] . " " . $merid;
		}
		

		return trim( $horafinal );	
	}
	
	function lastURI()
	{
		return $_SERVER[ "REQUEST_URI" ];
	}
	
	function &createPag( $sql , $limit = 50 )
	{
		
		$nav = new buildNav;
		$nav->offset = 'offset';
	   	$nav->limit = $limit;
	   	$nav->execute( $sql );
		$result = $nav->sql_result;
		$rows = $nav->rows;
		
		//$pages = $nav->show_num_pages( '&laquo;' , '&laquo; prev' , '&raquo;' , 'next &raquo;' , '|' , 'class=navvar' );
		$pages = $nav->show_num_pages_front( 'Inicio' , '< prev' , 'Fin' , 'next >' , '|' , 'class=navvar' );
		$info = $nav->show_info();
		
		return array( "info" => $info , "pages" => $pages , "rows" => $rows , "result" => &$result );
		
	}
	
	function filter( $fieldInt , $fieldStr , $fromjoin , $fieldsjoin , $where_array , $wherejoin )
	{
		extract( SIMUtil::makeSafe( $_GET ) );
		extract( SIMUtil::makeSafe( $_POST ) );
		
		$fieldlist = array();
		$fromput = array();
		
		// Adicionando los INT campos para el query
			    foreach( $fieldInt as $v )
			        if( ${$v} != "" )
			          	array_push($where_array," V.$v = '".${$v}."'");

			    
			    // Adicionando los campos para el query
			    foreach( $fieldStr as $v )
			        if( ${$v} != "" )
			          	array_push( $where_array , " V.$v LIKE '%" . ${$v} . "%' ");	
			  	
			  	foreach( $fieldsjoin as $v => $field )
				{
			  		
					//echo "entro";
					
					$fieldlist[] = $field;
			  		$fromput[] = $v;
			  		$where_array[$v] = $fromjoin[$v];
			  	}
			   	
				
			  	foreach( $wherejoin as $v => $from )
			  		if( !empty( ${$v} ) )
			  			$whereput .= $wherejoin[$v]." AND ";
			  	
			  	$fieldlist[] = " V.* ";
			  	$fieldiststr = implode( "," , $fieldlist );
			  	
			  	if( sizeof( $where_array ) ) 
			  	{
				 	$condiciones = implode(" AND ",$where_array);
				    $condicion .= " WHERE $condiciones ";
				    
				    //JOINS
				    if( sizeof( $where_array ) )
				    	$condicion .= " AND ".$whereput." 1 ";
				}
				elseif( !empty( $whereput ) )
					$condicion = " WHERE ".$whereput." 1 ";
	
		return array( "from" => implode( "," , $fromput ) , "where" => $condicion , "fields" => $fieldiststr );
	}
	
	function valida( $frm , $arr_valida )
	{
		$errorMsg = "";
		$errorList = array();
		$arrayFields = $arr_valida;
	
		foreach( $arrayFields as $field => $text )
		{
			$value = $frm[ $field ];
	
			if ( trim( $frm[ $field ] ) == "" )
				$errorList[] = array( "field" => $field, "value" => $value , "msg" => $text );
		}
				
		if( count( $errorList ) > 0 )
		{
			$mess = "<strong>ATENCION!</strong> Debe Completar los siguientes campos:\n<ul>";
			
			foreach( $errorList as $item ) 
				$mess .= "<li>" . $item['msg'] . "</li>\n";
			
			$mess .= "</ul>\nPor favor corrijalos e intente de nuevo\n";
			
			return $mess;
		}
		else
			return false;
	}

	function convertirmoneda( $moneda1, $valor1, $moneda2, $fecha )
	{
		$dbo =& SIMDB::get();
		$qry_moneda1 = $dbo->query( "SELECT * FROM CambioMoneda WHERE '$fecha' BETWEEN FechaDesde AND FechaHasta AND IDMoneda = '$moneda1' LIMIT 1" );
		$r_moneda1 = $dbo->object( $qry_moneda1 );
		
		$qry_moneda2 = $dbo->query( "SELECT * FROM CambioMoneda WHERE '$fecha' BETWEEN FechaDesde AND FechaHasta AND IDMoneda = '$moneda2' LIMIT 1" );
		$r_moneda2 = $dbo->object( $qry_moneda2 );
		
		if( $moneda1 == 1 )
		{
			//Multiplica
			$ValorCambio = $valor1 / $r_moneda2->Valor;
		}//end if
		elseif( $moneda2 == 1 )
		{
			//Divide
			$ValorCambio = $valor1 * $r_moneda1->Valor;
		}//end if
		else
		{
			//Primero parar a pesos (dividir)
			$ValorCambio = $valor1 * $r_moneda1->Valor;
			//Luego pasar a la moneda (multiplicar)
			$ValorCambio = $ValorCambio / $r_moneda2->Valor;
			
		}//end else
		
		
		return $ValorCambio;
		
	}
	
	function clearm( $value )
	{
		return preg_replace( "/[\,]+/" , "" , $value );
	}
	
	function verify( $nivelmodulo , $nivelusuario )
	{
		if( $nivelmodulo < $nivelusuario )
		{
			SIMHTML::jsRedirect( "index.php?mod=denegado" );
			die;
		}
	}

	function get_permiso( $mod , $perfil )
	{
		$dbo =& SIMDB::get();
		$qry_modulo = $dbo->query( "SELECT IDModulo FROM Modulo WHERE NombreModulo = '$mod' LIMIT 1" );
		$r_modulo = $dbo->object( $qry_modulo );
		
		$qry_permiso = $dbo->query( "SELECT Permiso FROM Permisos WHERE IDModulo = '$r_modulo->IDModulo' AND IDPerfil = '$perfil' LIMIT 1" );
		$r_permiso = $dbo->object( $qry_permiso );
		
		return $r_permiso->Permiso;

	}

	function floatvalue($value) {
		 return floatval(preg_replace('#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,2}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.\\4'", $value));
	} 
	
	function get_comerciales( $table, $usuario )
	{
		$dbo =& SIMDB::get();
		$comerciales = $dbo->fetchAll( "ComercialEjecutivo", " IDComercial = '" . $usuario . "' ", "array" );
		
		foreach( $comerciales as $key => $value )
			$array_comerciales[] = $value["IDUsuario"];
		
		if( count( $array_comerciales ) > 0 )
			$in = " AND " . $table . ".IDUsuario IN ( " . $usuario . "," . implode(",",$array_comerciales) . " ) ";
		
		return $in ;

	}
	
	function makeboolean($sqlfieldname,$keywordstr){
	
	$keyword = $keywordstr;
	
	// Convert String To Lower Case
	 $keyword = strtolower($keyword);
	
	// Replace Word Operators With Single Character Operators
	 $keyword = ereg_replace(" ","+",$keyword);
	 $keyword = ereg_replace(",","|",$keyword);
	// $keyword = ereg_replace(" -","-",$keyword);
	//  $keyword = ereg_replace("-","-",$keyword);
	
	
	// Build The Keywords String Based On Operators Assigned Above
	 $operatorcount = 0;
	 $len = strlen($keyword);
	 for ($z = 0; $z < $len; $z++) {
	  if(($keyword[$z] == "+") || ($keyword[$z] == "|") ) { //|| ($keyword[$z] == "-")
	   $operatorpos[$operatorcount] = $z;
	   $operatorcount++;
	  }
	 } 
	
	  if ($operatorcount != 0) { 
	   for ($z = 0; $z < $operatorcount; $z++) {
		 if($z == 0) {
		   $startpos = 0;
		   $endpos = $operatorpos[$z];
		 } else {
		   $startpos = $operatorpos[$z - 1] + 1;
		   $endpos = $operatorpos[$z];
		 }
	 
		$word = $endpos - $startpos;
		$keystring = substr($keyword,$startpos,$word);
		$keystring = ereg_replace("\(","",$keystring);
		$keystring = ereg_replace("\)","",$keystring);
		$keywords[$z] = $keystring;
		$operator_pos = $operatorpos[$z];
		$operators[$z] = $keyword[$operator_pos];
	   } // end the for loop
	
	 $wordcount = $operatorcount + 1;
	 $startpos = $operatorpos[$z - 1] + 1;
	 $len2 = strlen($keyword) - $startpos;
	 $linestr = substr($keyword,$startpos,$len2);
	
	 //store the line into the keywords array
	 $keywords[$wordcount - 1] = $linestr;
	
	
	 //loop through all of the words in the words array replacing them in the original string with a LIKE clause
	 for ($z=0; $z < $wordcount; $z++) {
		$replacekeyword = $keywords[$z];
		$y = $z -1;
		if ($operators[$y] != "-")   //odd case is in a NOT...must do something different!
		  $keyword = ereg_replace($replacekeyword,"$sqlfieldname LIKE '%$replacekeyword%'",$keyword);
	 //   else
	 //     $keyword = ereg_replace($replacekeyword,"$sqlfieldname NOT LIKE '%$replacekeyword%'",$keyword);
	 }
	
	// Replace Our Operators With The Correct SQL Operators  
	  $keyword = ereg_replace("\+"," AND ", $keyword);
	  $keyword = ereg_replace("\|"," OR ", $keyword);
	 // $keyword = ereg_replace("\-"," AND ", $keyword);  //I fudged in the above statement so this possible :-
	
	} // end if operatorcount != 0
	else { //there were no operators in the string
		$replacekeyword = $keyword;
	  if ($keyword != "") {
		$keyword = ereg_replace($replacekeyword,"$sqlfieldname LIKE '%$replacekeyword%'",$keyword);  
	  }
	}
	 
	 $keyword = " (".$keyword.") ";
	 
	  return($keyword);
	}//end function
	
	function get_banner( $categoria, $ubicacion, $limit = "", $condition = "" )
	{
		$dbo =& SIMDB::get();
		$array_banner = array();
		
		//ver si es un array
		if( is_array( $categoria )  )
			$categoria = implode(",",$categoria);
		
		//aramar el query de categorias
		if( !empty( $categoria ) )
		{
			$condition = " LEFT JOIN Banner_CategoriaEmpresa ON Banner.IDBanner = Banner_CategoriaEmpresa.IDBanner ";
			$condition_and = " AND Banner_CategoriaEmpresa.IDCategoriaEmpresa IN( " . $categoria . " ) ";
			$groupby = " GROUP BY Banner.IDBanner ";
		}//end if
		
		//traer los banner	
		$sql_banner = " SELECT Banner.* FROM Banner " . $condition . "
						WHERE Banner.FechaInicio <= CURDATE() 
						AND Banner.FechaFin >= CURDATE() 
						AND Banner.Publicar = 'S' 
						AND FIND_IN_SET( '" . $ubicacion . "',Ubicacion ) > 0
						AND Banner.IDLenguaje = '" . LENGUAJE . "' " . $condition_and . $groupby . " ORDER BY " . $limit;
		
		$qry_banner = $dbo->query( $sql_banner );
		while( $r_banner = $dbo->fetchArray( $qry_banner ) )
			$array_banner[] = $r_banner;
		
		return $array_banner;
		
	}//end function
	
	
	function multilike( $campo , $busqueda , $unico = false ){
		
		$like = "( "; 
		
		if( $unico )
		{
			$like = $like." ".$campo." LIKE '%".$busqueda."%' AND ";
		}
		else
		{
			$busqueda = explode(" ",$busqueda);
			
			foreach( $busqueda as $value )
			{
				$like = $like." ".$campo." LIKE '%".$value."%' AND ";
			}	
		}
		
		$like = substr ($like, 0, -4);
		
		$like = $like." )";
		
		return $like;
	}
	
	function antiinjection($str) {
	        $banchars = array ("'","*",";", "--","\n","\r");
	        $banwords = array ("key_column_usage","UNION"," or "," OR "," Or "," oR "," and ", " AND "," aNd "," aND "," AnD ","group_concat","table_name");
	        if ( eregi ( "[a-zA-Z0-9]+", $str ) ) {
	                $str = str_ireplace ( $banchars, '', ( $str ) );
	                $str = str_ireplace ( $banwords, '', ( $str ) );
	        } else {
	                $str = NULL;
	        }
	        $str = trim($str);
	        $str = strip_tags($str);
	        $str = stripslashes($str);
	        $str = addslashes($str);
	        $str = htmlspecialchars($str);
	        return $str;
	}
	
	function generatePassword ($length = 8)
	{
	
	  // start with a blank password
	  $password = "";
	
	  // define possible characters
	  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
		
	  // set up a counter
	  $i = 0; 
		
	  // add random characters to $password until $length is reached
	  while ($i < $length) { 
	
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			
		// we don't want this character if it's already in the password
		if (!strstr($password, $char)) { 
		  $password .= $char;
		  $i++;
		}
	
	  }
	
	  // done!
	  return $password;
	
	}//end function
	
	function CuentaRegistros ( $ID , $Tabla , $IDTabla , $IDLenguaje )
	{
		$dbo =& SIMDB::get();
		$Count = $dbo->fetchArray($dbo->query("SELECT count(*) as Total FROM ".$Tabla." WHERE IDLenguaje = ".$IDLenguaje));
	  	return $Count[Total]; 
	}//end function
	
	
	function CuentaComentarios ( $ID , $IDLenguaje )
	{
		$dbo =& SIMDB::get();
		
		$Total = 0;
		$Temas = $dbo ->all("TemaForo","IDSalaForo = '".$ID."' AND IDLenguaje = '".$IDLenguaje."'");
			while( $r = $dbo->fetchArray( $Temas ) )
			{
				$Count = array();
				$Count = $dbo->fetchArray($dbo->query("SELECT count(*) as Total FROM CometarioForo WHERE IDTemaForo = ".$r[IDTemaForo]." AND IDLenguaje = ".$IDLenguaje));
				$Total = $Total + $Count[Total];
			}
			
		return $Total;
	}//end function
	
	function UltimoComentarios ( $ID , $IDLenguaje )
	{
		$dbo =& SIMDB::get();
		
		$Total = 0;
		$Temas = $dbo ->all("TemaForo","IDSalaForo = '".$ID."' AND IDLenguaje = '".$IDLenguaje."'");
		while( $r = $dbo->fetchArray( $Temas ) )
		{
			$IDTEMAS = "$r[IDTemaForo] , ".$IDTEMAS;
		}

		$IDTEMAS = substr ($IDTEMAS, 0, -3);
		
		$Comentarios = $dbo ->all("CometarioForo","IDTemaForo IN (".$IDTEMAS.") AND IDLenguaje = '".$IDLenguaje."' ORDER BY FechaTrCr DESC ");
			
		$RegistroComentario = $dbo->fetchArray( $Comentarios) ;
		
		return $RegistroComentario;
		
	}//end function
	
	function UltimoComentariosTema ( $ID , $IDLenguaje )
	{
		$dbo =& SIMDB::get();
		
		$Temas = $dbo ->all("CometarioForo","IDTemaForo = '".$ID."' AND IDLenguaje = '".$IDLenguaje."' ORDER BY FechaTrCr DESC");
		$RegistroComentario = $dbo->fetchArray( $Temas ) ;
		return $RegistroComentario;
		
	}//end function
	
	function get_arbol( $table, $key, $field, $ubicacion = "Menu" )
	{
		$dbo =& SIMDB::get();
		$array_menu = array( );
		
		if( !empty( $ubicacion ) )
			$sqlUbicacion = " AND FIND_IN_SET( '" . $ubicacion . "', Ubicacion ) > 0 ";
		
		$qry = $dbo->all( "Seccion", "Publicar = 'S' AND  IDLenguaje = '" . LENGUAJE . "' " . $sqlUbicacion . " ORDER BY Orden ASC " );	
		while ( $r = $dbo->fetchArray( $qry ) )
		{
			$array_menu[ $r["IDPadre"] ][ $r["IDSeccion"] ] = $r;
		}//end while
				
		return $array_menu;
				
	}//end function

        function url_navegacion( $tableseccion, $keyseccion, $tablecategoria, $keycategoria , $pagina , $mod)
	{
		session_start();
                $dbo =& SIMDB::get();
                $nombreseccion = $dbo->getFields( $tableseccion , "Nombre" , "IDSeccion = '".$keyseccion."' AND IDLenguaje = '".$_SESSION["SITE_LENGUAJE"]."'" );
                $urlseccion = $dbo->getFields( $tableseccion , "URL" , "IDSeccion = '".$keyseccion."' AND IDLenguaje = '".$_SESSION["SITE_LENGUAJE"]."'" );
                ?>
                <span><a  class="miga" href="index.php">Inicio</a></span><span class="migaInactivo"> &gt; </span>
                <span><a  class="miga" href="<?php echo $urlseccion;?>"><?php echo  ucfirst(strtolower($nombreseccion));?></a></span><span class="migaInactivo"> &gt; </span>
                <?
                if($keycategoria)
                {
                    $ArrayArbol[] = $keycategoria;
                    for( $i = 1 ; $i<=10 ; $i++ )
                    {
                        $qry = $dbo->all( $tablecategoria , "IDCategoria = '".$keycategoria."' AND Publicar = 'S' AND  IDLenguaje = '".$_SESSION["SITE_LENGUAJE"]."'" );
                        $r = $dbo->fetchArray( $qry );
                        if( $r[IDPadre] > 0 )
                        {
                            $keycategoria = $r[IDPadre];
                            $ArrayArbol[] = $r[IDPadre];
                        }
                        else
                            $i=10;
                    }
                    $ArrayArbol = array_reverse($ArrayArbol);
                    foreach( $ArrayArbol as $clave => $valor )
                    {
                        $nombrecategoria = $dbo->getFields( $tablecategoria , "Nombre" , "IDCategoria = '".$valor."' AND IDLenguaje = '".$_SESSION["SITE_LENGUAJE"]."'" );
                        ?>
                        <span><a  class="miga" href="<?php echo $pagina."?".$mod."=".$valor;?>"><?php echo ucfirst(strtolower($nombrecategoria));?></a></span><span class="migaInactivo"> &gt; </span>
                        <?

                    }
                }
                
                


	}//end function
	
	

	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				print_r($var);
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
   
    return($xml_array);
}

function getMigaProducto( $id, $rotmiga = "" )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id )  || $id != 0 )
	{
		$SqlMiga = "SELECT * FROM Producto_Categoria WHERE IDCategoria = '" . $id . "' AND Publicar = 'S'";
		$QryMiga = $dbo->query( $SqlMiga );
		$NumMiga = $dbo->rows( $QryMiga );
		if( $NumMiga > 0 )
		{
			$Miga = $dbo->fetchArray( $QryMiga );
			$Padre = $Miga["IDPadre"];
			$rotmiga[] = $Miga["Nombre"]; 		
			
			if( $Padre ==  0 )
			{
				$migas = "";
				for( $i = sizeof( $rotmiga ); $i >= 0 ; $i-- )
				{
					$migas .= $migas == "" ? $rotmiga[$i] : " / " . $rotmiga[$i];
					
				}
				return $migas;
			}
			else
			{			
				return self::getMigaProducto( $Padre, $rotmiga );
			}			
		}
			
	}		
	else
	{
		return $rotmiga;	
	}
	
}
	

function getPadreCategoria( $id )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id ) || $id != 0 )
	{
		$SqlPadre = "SELECT * FROM Producto_Categoria WHERE IDCategoria = '" . $id . "' AND Publicar = 'S'";
		$QryPadre = $dbo->query( $SqlPadre );
		$NumPadre = $dbo->rows( $QryPadre );
		if( $NumPadre > 0 )
		{
			$Padre = $dbo->fetchArray( $QryPadre );
			$IdPadre = $Padre["IDPadre"];
			$IdPadre . "<br>" ;
			if( $IdPadre ==  0 )
			{
				return $Padre["IDCategoria"];
			}
			else
			{			
				return self::getPadreCategoria( $Padre["IDPadre"] );
			}			
		}
			
	}		
	else
	{
		return false ;	
	}
	
}

function getMigaProductoInt( $id, $rotmiga = "" )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id )  || $id != 0 )
	{
		$SqlMiga = "SELECT * FROM Producto_CategoriaInt WHERE IDCategoria = '" . $id . "' AND Publicar = 'S'";
		$QryMiga = $dbo->query( $SqlMiga );
		$NumMiga = $dbo->rows( $QryMiga );
		if( $NumMiga > 0 )
		{
			$Miga = $dbo->fetchArray( $QryMiga );
			$Padre = $Miga["IDPadre"];
			$rotmiga[] = $Miga["Nombre"]; 		
			
			if( $Padre ==  0 )
			{
				$migas = "";
				for( $i = sizeof( $rotmiga ); $i >= 0 ; $i-- )
				{
					$migas .= $migas == "" ? $rotmiga[$i] : " / " . $rotmiga[$i];
					
				}
				return $migas;
			}
			else
			{			
				return self::getMigaProductoInt( $Padre, $rotmiga );
			}			
		}
			
	}		
	else
	{
		return $rotmiga;	
	}
	
}
	

function getPadreCategoriaInt( $id )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id ) || $id != 0 )
	{
		$SqlPadre = "SELECT * FROM Producto_CategoriaInt WHERE IDCategoria = '" . $id . "' AND Publicar = 'S'";
		$QryPadre = $dbo->query( $SqlPadre );
		$NumPadre = $dbo->rows( $QryPadre );
		if( $NumPadre > 0 )
		{
			$Padre = $dbo->fetchArray( $QryPadre );
			$IdPadre = $Padre["IDPadre"];
			$IdPadre . "<br>" ;
			if( $IdPadre ==  0 )
			{
				return $Padre["IDCategoria"];
			}
			else
			{			
				return self::getPadreCategoriaInt( $Padre["IDPadre"] );
			}			
		}
			
	}		
	else
	{
		return false ;	
	}
	
}
	
function getMigaInterna( $id, $rotmiga = "" )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id )  || $id != 0 )
	{
		$SqlMiga = "SELECT * FROM Seccion WHERE IDSeccion = '" . $id . "' AND Publicar = 'S'";
		$QryMiga = $dbo->query( $SqlMiga );
		$NumMiga = $dbo->rows( $QryMiga );
		if( $NumMiga > 0 )
		{
			$Miga = $dbo->fetchArray( $QryMiga );
			$Padre = $Miga["IDPadre"];
			$rotmiga[] = $Miga["Nombre"]  . "-" . $Miga["IDSeccion"];
			
			if( $Padre ==  0 )
			{
				$migas = "";
				
				foreach( array_reverse( $rotmiga ) as $key => $value )
				{
					$value = explode( "-", $value );
					
					$sqlHijosMiga = "SELECT COUNT(*) AS HijosMiga FROM Noticia WHERE IDSeccion = '" . $value[1] . "' AND Publicar = 'S' AND FechaInicio <= CURDATE() AND FechaFin >= CURDATE()";
					$qryHijosMiga = $dbo->query( $sqlHijosMiga );
					$resHijosMiga = $dbo->fetchArray( $qryHijosMiga );
					
					$urlMiga = $resHijosMiga["HijosMiga"] > 1 ? "<a href='seccion.php?ids=" . $value[1] . "' title='" . $value[0] . "'>" . $value[0] . "</a> | " : "<span style='font-weight: normal;'>" . $value[0] . "</span> | " ;
					
					$migas .= /*$urlMiga;*/$migas == "" ? $urlMiga : "<span>" . $value[0] . "</span>";
					
				}
				return $migas;
			}
			else
			{			
				return self::getMigaInterna( $Padre, $rotmiga );
			}			
		}
			
	}		
	else
	{
		return $rotmiga;	
	}
	
}

function getMigaInternaInt( $id, $rotmiga = "" )//Metodo para obtener miga de pan en CATALOGO DE PRODUCTOS
{
	$dbo =& SIMDB::get();
	//$arraymiga = array();
	if( !empty( $id )  || $id != 0 )
	{
		$SqlMiga = "SELECT * FROM SeccionInt WHERE IDSeccion = '" . $id . "' AND Publicar = 'S'";
		$QryMiga = $dbo->query( $SqlMiga );
		$NumMiga = $dbo->rows( $QryMiga );
		if( $NumMiga > 0 )
		{
			$Miga = $dbo->fetchArray( $QryMiga );
			$Padre = $Miga["IDPadre"];
			$rotmiga[] = $Miga["Nombre"];
			
			if( $Padre ==  0 )
			{
				$migas = "";
				
				for( $i = sizeof( $rotmiga ) - 1; $i >= 0 ; $i-- )
				{
					$migas .= $migas == "" ? "<span style='font-weight: normal;'>" . $rotmiga[$i] . "</span> | " : "<span>" . $rotmiga[$i] . "</span>";
					
				}
				return $migas;
			}
			else
			{			
				return self::getMigaInternaInt( $Padre, $rotmiga );
			}			
		}
			
	}		
	else
	{
		return $rotmiga;	
	}
	
}
function limitarPalabras($cadena, $longitud, $elipsis = "...") {
	$palabras = explode(' ', $cadena);
	if (count($palabras) > $longitud){
		return implode(' ', array_slice($palabras, 0, $longitud)) . $elipsis;
	}else{
		return $cadena;
	}
}
}
?>