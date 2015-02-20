 <?

SIMReg::setFromStructure( array(
					"title" => "CandidatoExperiencia",
					"table" => "CandidatoExperiencia",
					"key" => "IDExperiencia",
					"mod" => "CandidatoExperiencia"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDExperiencia" => "IDExperiencia" , "IDPais" => "IDPais" , "IDDepartamento" => "IDDepartamento" , "IDCiudad" => "IDCiudad" , "IDSector" => "IDSector" , "IDArea" => "IDArea" , "Empresa" => "Empresa" , "Cargo" => "Cargo" , "ACargo" => "ACargo" , "IDRangoExperiencia" => "IDRangoExperiencia" , "IDRangoSalario" => "IDRangoSalario" , "CargoActual" => "CargoActual" , "Responsabilidades" => "Responsabilidades" 	
); 



//extraemos las variables
$table = SIMReg::get( "table" );
$key = SIMReg::get( "key" );
$mod = SIMReg::get( "mod" );
$dbo =& SIMDB::get();

//creando las notificaciones que llegan en el parametro m de la URL
SIMNotify::capture( SIMResources::$mensajes[ SIMNet::req("m") ]["msg"] , SIMResources::$mensajes[ SIMNet::req("m") ]["type"] );	




		switch ( SIMNet::req( "action" )   ) {
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
				$frm = $dbo->fetchById( $table , $key , SIMNet::reqInt("id") );
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
						
				$fieldStr = array ( "Nombre" );		 	
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
			<td  class="columnafija" > IDExperiencia </td><td><input id=IDExperiencia type=text size=25 readonly name=IDExperiencia class="input mandatory " title="IDExperiencia" value="<?=$frm[IDExperiencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDPais </td><td><input id=IDPais type=text size=25  name=IDPais class="input mandatory " title="IDPais" value="<?=$frm[IDPais] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDDepartamento </td><td><input id=IDDepartamento type=text size=25  name=IDDepartamento class="input mandatory " title="IDDepartamento" value="<?=$frm[IDDepartamento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDCiudad </td><td><input id=IDCiudad type=text size=25  name=IDCiudad class="input mandatory " title="IDCiudad" value="<?=$frm[IDCiudad] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDSector </td><td><input id=IDSector type=text size=25  name=IDSector class="input mandatory " title="IDSector" value="<?=$frm[IDSector] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDArea </td><td><input id=IDArea type=text size=25  name=IDArea class="input mandatory " title="IDArea" value="<?=$frm[IDArea] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Empresa </td><td><input id=Empresa type=text size=25  name=Empresa class="input mandatory " title="Empresa" value="<?=$frm[Empresa] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Cargo </td><td><input id=Cargo type=text size=25  name=Cargo class="input mandatory " title="Cargo" value="<?=$frm[Cargo] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > ACargo </td><td><textarea rows="5" id=ACargo cols=60 wrap=virtual class="input mandatory" title="ACargo" name=ACargo><?=$frm[ACargo]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoExperiencia </td><td><input id=IDRangoExperiencia type=text size=25  name=IDRangoExperiencia class="input mandatory " title="IDRangoExperiencia" value="<?=$frm[IDRangoExperiencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoSalario </td><td><input id=IDRangoSalario type=text size=25  name=IDRangoSalario class="input mandatory " title="IDRangoSalario" value="<?=$frm[IDRangoSalario] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > CargoActual </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["CargoActual"] , 'CargoActual' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Responsabilidades </td><td><textarea rows="5" id=Responsabilidades cols=60 wrap=virtual class="input mandatory" title="Responsabilidades" name=Responsabilidades><?=$frm[Responsabilidades]?></textarea></td>
			</tr>
			<tr>
			<td colspan=2 align=center>
			
			 <a href="#" class="button btnEnviar orange"><? echo $submit_caption ?></a>
			<input type=hidden name=ID value="<? echo $frm[$key] ?>">
			<input type=hidden name=action value=<?=$newmode?>>
</td>
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
			
		$result =& SIMUtil::createPag( $sql , 50 );	
	
?>	
	
	
	
<table class="adminheading">
		<tr>
			<th><?php echo SIMReg::get( "title" )?></th>
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
					<td class=texto colspan=16  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					IDExperiencia&nbsp;
				</th>
				<th>
					IDPais&nbsp;
				</th>
				<th>
					IDDepartamento&nbsp;
				</th>
				<th>
					IDCiudad&nbsp;
				</th>
				<th>
					IDSector&nbsp;
				</th>
				<th>
					IDArea&nbsp;
				</th>
				<th>
					Empresa&nbsp;
				</th>
				<th>
					Cargo&nbsp;
				</th>
				<th>
					ACargo&nbsp;
				</th>
				<th>
					IDRangoExperiencia&nbsp;
				</th>
				<th>
					IDRangoSalario&nbsp;
				</th>
				<th>
					CargoActual&nbsp;
				</th>
				<th>
					Responsabilidades&nbsp;
				</th>
					
<th align=center valign=middle width=64>Eliminar</th>
</tr>

<? 
$dbo =&SIMDB::get();
while( $r = $dbo->object( $result["result"] ) )
{
?>
  	
<tr class=<? echo SIMUtil::repetition()?'row0':'row1'; ?>>
<td align=center width=64 ><a href='<?php echo "?mod=" . $mod . "&amp;action=edit&amp;id=" . $r->$key?>'><img src='images/edit.png' border='0'></a></td>
<td nowrap><? echo $r->IDExperiencia ?></td> <td nowrap><? echo $r->IDPais ?></td> <td nowrap><? echo $r->IDDepartamento ?></td> <td nowrap><? echo $r->IDCiudad ?></td> <td nowrap><? echo $r->IDSector ?></td> <td nowrap><? echo $r->IDArea ?></td> <td nowrap><? echo $r->Empresa ?></td> <td nowrap><? echo $r->Cargo ?></td> <td nowrap><? echo $r->ACargo ?></td> <td nowrap><? echo $r->IDRangoExperiencia ?></td> <td nowrap><? echo $r->IDRangoSalario ?></td> <td nowrap><? echo $r->CargoActual ?></td> <td nowrap><? echo $r->Responsabilidades ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=16 nowrap  ><?php echo $result["pages"]?></th>
					
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




<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="get">			
<table width="100%" align="center" class="adminlist">
		<tr>
	   		<th align="center" class="title">BUSCAR</th>
	  	</tr>
		<tr>
			<td align="center">
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
						<td width="100">Nombre</td>
						<td width="131"><input type="text" size="14" value="" name="Nombre" id="Nombre" class="input" /></td>
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
						<td><input type="reset" name="submit" class="submit" value="Limpiar Campos"></td>
					</tr>
				</table>
			</td>
		</tr>
</table>
<input type="hidden" name="mod" id="mod" value="<?php echo SIMReg::get( "mod" )?>" />
<input type="hidden" name="action" id="action" value="list" />
</form>






<?		
	}//End function filtrar
?>

