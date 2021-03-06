 <?

SIMReg::setFromStructure( array(
					"title" => "OfertaIdioma",
					"table" => "OfertaIdioma",
					"key" => "IDOferta",
					"mod" => "OfertaIdioma"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDOferta" => "IDOferta" , "IDIdioma" => "IDIdioma" , "Para" => "Para" , "Lee" => "Lee" , "Habla" => "Habla" , "Escribe" => "Escribe" , "CondicionesEspeciales" => "CondicionesEspeciales" 	
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
			<td  class="columnafija" > IDOferta </td><td><input id=IDOferta type=text size=25 readonly name=IDOferta class="input mandatory " title="IDOferta" value="<?=$frm[IDOferta] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDIdioma </td><td><input id=IDIdioma type=text size=25  name=IDIdioma class="input mandatory " title="IDIdioma" value="<?=$frm[IDIdioma] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Para </td><td><textarea rows="5" id=Para cols=60 wrap=virtual class="input mandatory" title="Para" name=Para><?=$frm[Para]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Lee </td><td><input id=Lee type=text size=25  name=Lee class="input mandatory " title="Lee" value="<?=$frm[Lee] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Habla </td><td><input id=Habla type=text size=25  name=Habla class="input mandatory " title="Habla" value="<?=$frm[Habla] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Escribe </td><td><input id=Escribe type=text size=25  name=Escribe class="input mandatory " title="Escribe" value="<?=$frm[Escribe] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > CondicionesEspeciales </td><td><textarea rows="5" id=CondicionesEspeciales cols=60 wrap=virtual class="input mandatory" title="CondicionesEspeciales" name=CondicionesEspeciales><?=$frm[CondicionesEspeciales]?></textarea></td>
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
					<td class=texto colspan=10  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					IDOferta&nbsp;
				</th>
				<th>
					IDIdioma&nbsp;
				</th>
				<th>
					Para&nbsp;
				</th>
				<th>
					Lee&nbsp;
				</th>
				<th>
					Habla&nbsp;
				</th>
				<th>
					Escribe&nbsp;
				</th>
				<th>
					CondicionesEspeciales&nbsp;
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
<td nowrap><? echo $r->IDOferta ?></td> <td nowrap><? echo $r->IDIdioma ?></td> <td nowrap><? echo $r->Para ?></td> <td nowrap><? echo $r->Lee ?></td> <td nowrap><? echo $r->Habla ?></td> <td nowrap><? echo $r->Escribe ?></td> <td nowrap><? echo $r->CondicionesEspeciales ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=10 nowrap  ><?php echo $result["pages"]?></th>
					
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

