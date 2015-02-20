 <?

SIMReg::setFromStructure( array(
					"title" => "GaleriaVideo",
					"table" => "GaleriaVideo",
					"key" => "IDVideo",
					"mod" => "GaleriaVideo"
) );


//para validar los campos del formulario
$array_valida = array(  
	 
); 



//extraemos las variables
$table = SIMReg::get( "table" );
$key = SIMReg::get( "key" );
$mod = SIMReg::get( "mod" );
$dbo =& SIMDB::get();

//creando las notificaciones que llegan en el parametro m de la URL
SIMNotify::capture( SIMResources::$mensajes[ SIMNet::req("m") ]["msg"] , SIMResources::$mensajes[ SIMNet::req("m") ]["type"] );	




		switch ( $action ) {
			case "add" :
				print_form( "" , "insert" , "Agregar Registro" );
			break;
			
			case "insert" :	
				if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
				{
					//los campos al final de las tablas
					$frm = SIMUtil::varsLOG( $_POST );
					
					//insertamos los datos
					$id = $dbo->insert( $frm , $table , $key );
					
					SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&m=insertarexito" );
				}
				else
					print_form( $_POST , "insert" , "Agregar Registro" );
			break;
			case "addset" :
			
				//$request='http://gdata.youtube.com/feeds/api/users/TomaCafe10/uploads';
				$request='http://gdata.youtube.com/feeds/api/users/NationalGeographic/uploads';
				$response = file_get_contents($request);
				$xmlarray = xml2array($response);
				$contador = 0;
				
				
				/*echo sizeof( $xmlarray[feed][entry] );
				echo "<hr />";*/
				/*echo "<pre>";
				print_r( $xmlarray[feed] );
				echo "</pre>";
				die();*/
				
				
				foreach($xmlarray[feed][entry] as $video)
					{
						//echo "<br>";
						//echo "SELECT * FROM ".$table." where IDYouTube='".$arrvid[6]."'" . "<br>";
						$arrvid = explode( "/", $video[id]);
						
						/*echo "<pre>";
						print_r( $video );
						echo "</pre>";*/
						
						//echo "SELECT * FROM ".$table." where IDYouTube='".$arrvid[6]."'<br>";
						
						if( $arrvid[6] != "" )
						{
							
							
							$quid = $dbo->query("SELECT * FROM ".$table." where IDYouTube='".$arrvid[6]."'");
							
							
							if($dbo->rows($quid) == 0)
							{
						     	$contador++;
								
						     	$insertset = $dbo->query("INSERT INTO ".$table." ( Nombre, IDYouTube, Publicar, Home, FechaTrCr ) VALUES(
								'".utf8_encode( $video['title'] )."',
								'".$arrvid[6]."',
								'S',
								'N', NOW()
								)");
								
								
								//$contador = $contador + 1;
							}
						}	
						
					}					
					
				//die();
				/*<img align="middle" width="80"  src="http://farm3.static.flickr.com/<?=$sets['attr']['server']?>/<?=$sets['attr']['primary']?>_<?=$sets['attr']['secret']?>.jpg"/>	*/
					
					
				echo "<script>
				alert('Se sincronizaron ".$contador." videos');
				window.location.href='?mod=$mod';
				</script>";
			break;
			case "edit":
			
				$frm = $dbo->fetchById( $table , $key , SIMNet::reqInt("id") , "array" );		
				print_form( $frm , "update" , "Realizar Cambios" );
				
			break ;
			
			case "update" :	
				if( !SIMNotify::capture( SIMUtil::valida( $_POST , $array_valida ) , "error" ) )
				{
					//los campos al final de las tablas
					$frm = SIMUtil::varsLOG( $_POST );
					
					$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );
					
					$frm = $dbo->fetchById( $table , $key , $id , "array" );
					
					SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );
					
					print_form( $frm , "update" ,  "Realizar Cambios" );
				}
				else
					print_form( $_POST , "update" ,  "Realizar Cambios" );	
			break;
			
			case "del":
				$frm = $dbo->fetchById( $table , $key , $id , "array" );
				print_form( $frm , "delete" , "Remover Registro" );
			break ;
					
			case "delete" :
				$dbo =& SIMDB::get();
				$dbo->deleteById( $table , $key , SIMNet::reqInt("ID") );
				
				SIMHTML::jsRedirect( "?mod=" . $mod . "&amp;m=eliminarrexito" );
			break;
			
			case "list" :
				$where_array = array();
				$fieldInt = array();
						
				$fieldStr = array ( "Nombre");		 	
				$listjoin = array();
				$fromjoin = array();
					 
				$wherejoin = array();
												
				$params = SIMUtil::filter( $fieldInt , $fieldStr , $fromjoin , $listjoin , $where_array , $wherejoin );
						
				$sql = " SELECT " . $params["fields"] . " FROM " . $table . " V " . $params["from"] . $params["where"];
				
				list_r( $sql );
			break;
			
			default : 
				list_r();
			break;
		
		} // End switch



/*******************************************************************************************
		funtcion Print_form
*******************************************************************************************/
function print_form( $frm = "" , $newmode , $submit_caption )
{
	$dbo =& SIMDB::get();
	$key = SIMReg::get( "key" );
	$table = SIMReg::get( "table" );
	$request='http://gdata.youtube.com/feeds/api/videos/'.$frm[IDYouTube];
			$response = file_get_contents($request);
			$xmlarray =& SIMUtil::xml2array($response);
			$video = $xmlarray[entry];
?>


<table class=adminheading>
		<tr>
			<th> 
			<?php echo SIMReg::get( "title" )?> </th>
			
			
		</tr>
</table>
<?
//imprime el HTML de errores
SIMNotify::each();
?>


<div id="tabsform">
	<div id="">
		<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">
		<table class="adminform">
			<tr>
				<th>&nbsp;Datos</th>
			</tr>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td  class="columnafija" > Nombre </td><td><input id=Nombre readonly="readonly" type=text size=25  name=Nombre class="input mandatory" title="Nombre" value="<?=$video['title']?>"> </td>
			</tr>
            <tr>
                <td> Descripcion </td>
                <td><textarea rows="5" cols="50" id="Descripcion" name="Descripcion" class="input " title="Descripcion" ><?php echo $frm["Descripcion"] ?></textarea></td>
            </tr>
			<tr>
			<td  class="columnafija" > Video </td><td><!-- <object width="425" height="344">
									<param name="movie" value="<?=$video['media:group']['media:content']['0_attr']['url']?>"></param>
									<param name="allowFullScreen" value="true"></param>
									<param name="allowscriptaccess" value="always"></param>
									<embed src="<?=$video['media:group']['media:content']['0_attr']['url']?>" type="<?=$video['media:group']['media:content']['0_attr']['type']?>" allowscriptaccess="always" allowfullscreen="true" width="425" height="344">									</embed>
									</object>-->
									<iframe width="425" height="344" src="http://www.youtube.com/embed/<?php echo $frm["IDYouTube"] ?>" frameborder="0" allowfullscreen></iframe>
									
									</td>
			</tr>
			<tr>
              <td  class="columnafija" >Publicar</td>
			  <td><?= SIMHTML::formRadioGroup( SIMResources::$sino , SIMResources::$sino[ substr( $frm[ Publicar ] , 0 , 1 ) ] , "Publicar") ?></td>
			  </tr>
              <tr>
              <td  class="columnafija" >Home</td>
			  <td><?= SIMHTML::formRadioGroup( SIMResources::$sino , SIMResources::$sino[ substr( $frm[ Home ] , 0 , 1 ) ] , "Home") ?></td>
			  </tr>
			<tr>
			<td colspan=2 align=center>
			
			<input type=submit name=submit value="<? echo $submit_caption ?>" class=submit>
			<input type=hidden name=ID value="<? echo $frm[$key] ?>">
			<input type=hidden name=action value=<?=$newmode?>></td>
				</tr>
			</table>
            
		</td>
	</tr>
</table>
</form>

			
</div>
</div>


<?
}// End function print_form()

/*******************************************************************************************
		funcion Listar
*******************************************************************************************/
	function list_r($sql=""){
		$key = SIMReg::get( "key" );
		$table = SIMReg::get( "table" );
		$mod =  SIMReg::get( "mod" );
		
		if( empty( $sql ) )
			$sql =  "SELECT * FROM " . $table . " ORDER BY " . $key;
			
		$result =& SIMUtil::createPag( $sql , 20 );	
	
?>	
	
	
	
<table class="adminheading">
		<tr>
			<th><?php echo SIMReg::get( "title" )?></th>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" id="toolbar">
					<tr height="60" valign="middle" align="center">
						<td><a href="./?mod=<?=$mod?>&action=addset" class="toolbar"><img src='images/new_f2.png' border='0'><br>
					    Sincronizar</a></td>
				  </tr>
				</table>
			</td>
		</tr>
	</table>
	<?php
	filtrar();
	
	if( $result["rows"] > 0 )
	{			
		//imprime el HTML de errores
		SIMNotify::each();
	?>	


<table width=100%  cellpadding=0 cellspacing=0 align=center>
	<tr>
		<td>
			<table class=adminlist width=100% >
	
	<tr>
					<th class=title colspan=7   ><?php echo strtoupper( SIMReg::get( "title" ) ) . ": Listado"?></th>
				</tr>


<tr>
					<th class=texto colspan=7  ><?php echo $result["info"]?></th>
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
			  IDRegistro&nbsp;			</th>
				<th>
					Nombre&nbsp;				</th>
				<th>
					Video&nbsp;				</th>
					<th>
					Duraci&oacute;n&nbsp;				</th>
					<th>
					Fecha Publicaci&oacute;n&nbsp;				</th>
					
<th align=center valign=middle width=64>Eliminar</th>
</tr>

<? 
$dbo =&SIMDB::get();
while( $r = $dbo->object( $result["result"] ) )
{
			$request='http://gdata.youtube.com/feeds/api/videos/'.$r->IDYouTube;
			$response = file_get_contents($request);
			$xmlarray =& SIMUtil::xml2array($response);
			$video = $xmlarray[entry];
			
?>
  	
<tr class=<?php echo SIMUtil::repetition()?'row0':'row1';?>>
<td align=center width=64 ><a href='<?php echo "?mod=" . $mod . "&amp;action=edit&amp;id=" . $r->$key?>'><img src='images/edit.png' border='0'></a></td>
<td nowrap><? echo $r->IDRegistro ?></td> <td nowrap><? echo $video['title'] ?></td> <td nowrap><img border="0" src="<?=$video['media:group']['media:thumbnail']['0_attr']['url']?>" width="<?=$video['media:group']['media:thumbnail']['0_attr']['width']?>" height="<?=$video['media:group']['media:thumbnail']['0_attr']['height']?>" /></td>
<td nowrap><? echo $video['media:group']['media:thumbnail']['0_attr']['time'] ?></td>
<td nowrap><? echo substr($video['published'], 0,10) ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=7 nowrap  ><?php echo $result["pages"]?></th>
				</tr>		
</table></td>
</tr>
</table>	

<? 			
}// End if$rows


else
{
	SIMNotify::capture( "No se han encontrado registros" , "error" );
	//imprime el HTML de errores
	SIMNotify::each();
}//end else



}// Enf function list()				

/*******************************************************************************************
		funcion filtrar
*******************************************************************************************/
	function filtrar(){
?>

<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>"
	method="get"><input type="hidden" name="mod" id="mod"
	value="<?php echo SIMReg::get( "mod" )?>" /> <input type="hidden"
	name="action" id="action" value="list" />
<table width="100%" align="center" class="adminlist">
	<tr>
		<th align="center" class="title">BUSCAR</th>
	</tr>
	<tr>
		<td align="center">
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
			<tr>
				<td width="100">Nombre</td>
				<td width="131"><input type="text" size="14" value="" name="Nombre"
					id="Nombre" class="input" /></td>
				<td width="100">&nbsp;</td>
				<td width="131">&nbsp;</td>
				<td width="100">&nbsp;</td>
				<td width="131">&nbsp;</td>
			</tr>
			<tr>
				<td width="100">&nbsp;</td>
				<td width="131">&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" name="buscar" class="submit" value="Buscar"></td>
				<td></td>
				<td><input type="reset" name="submit" class="submit"
					value="Limpiar Campos"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
<?		
	}//End function filtrar
	
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
?>

