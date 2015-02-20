 <?

SIMReg::setFromStructure( array(
					"title" => "EmpresaContacto",
					"table" => "EmpresaContacto",
					"key" => "IDContacto",
					"mod" => "EmpresaContacto"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDContacto" => "IDContacto" , "IDEmpresa" => "IDEmpresa" , "IDPais" => "IDPais" , "IDDepartamento" => "IDDepartamento" , "NumeroDocumento" => "NumeroDocumento" , "Nombre" => "Nombre" , "Apellido" => "Apellido" , "Email" => "Email" , "Celular" => "Celular" , "Telefono" => "Telefono" , "Extension" => "Extension" , "Cargo" => "Cargo" 	
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
			<td  class="columnafija" > IDContacto </td><td><input id=IDContacto type=text size=25 readonly name=IDContacto class="input mandatory " title="IDContacto" value="<?=$frm[IDContacto] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDEmpresa </td><td><input id=IDEmpresa type=text size=25  name=IDEmpresa class="input mandatory " title="IDEmpresa" value="<?=$frm[IDEmpresa] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDPais </td><td><input id=IDPais type=text size=25  name=IDPais class="input mandatory " title="IDPais" value="<?=$frm[IDPais] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDDepartamento </td><td><input id=IDDepartamento type=text size=25  name=IDDepartamento class="input mandatory " title="IDDepartamento" value="<?=$frm[IDDepartamento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > NumeroDocumento </td><td><input id=NumeroDocumento type=text size=25  name=NumeroDocumento class="input mandatory " title="NumeroDocumento" value="<?=$frm[NumeroDocumento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Nombre </td><td><input id=Nombre type=text size=25  name=Nombre class="input mandatory " title="Nombre" value="<?=$frm[Nombre] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Apellido </td><td><input id=Apellido type=text size=25  name=Apellido class="input mandatory " title="Apellido" value="<?=$frm[Apellido] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Email </td><td><input id=Email type=text size=25  name=Email class="input mandatory " title="Email" value="<?=$frm[Email] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Celular </td><td><input id=Celular type=text size=25  name=Celular class="input mandatory " title="Celular" value="<?=$frm[Celular] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Telefono </td><td><input id=Telefono type=text size=25  name=Telefono class="input mandatory " title="Telefono" value="<?=$frm[Telefono] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Extension </td><td><input id=Extension type=text size=25  name=Extension class="input mandatory " title="Extension" value="<?=$frm[Extension] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Cargo </td><td><input id=Cargo type=text size=25  name=Cargo class="input mandatory " title="Cargo" value="<?=$frm[Cargo] ?>"> </td>
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
					<td class=texto colspan=15  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					IDContacto&nbsp;
				</th>
				<th>
					IDEmpresa&nbsp;
				</th>
				<th>
					IDPais&nbsp;
				</th>
				<th>
					IDDepartamento&nbsp;
				</th>
				<th>
					NumeroDocumento&nbsp;
				</th>
				<th>
					Nombre&nbsp;
				</th>
				<th>
					Apellido&nbsp;
				</th>
				<th>
					Email&nbsp;
				</th>
				<th>
					Celular&nbsp;
				</th>
				<th>
					Telefono&nbsp;
				</th>
				<th>
					Extension&nbsp;
				</th>
				<th>
					Cargo&nbsp;
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
<td nowrap><? echo $r->IDContacto ?></td> <td nowrap><? echo $r->IDEmpresa ?></td> <td nowrap><? echo $r->IDPais ?></td> <td nowrap><? echo $r->IDDepartamento ?></td> <td nowrap><? echo $r->NumeroDocumento ?></td> <td nowrap><? echo $r->Nombre ?></td> <td nowrap><? echo $r->Apellido ?></td> <td nowrap><? echo $r->Email ?></td> <td nowrap><? echo $r->Celular ?></td> <td nowrap><? echo $r->Telefono ?></td> <td nowrap><? echo $r->Extension ?></td> <td nowrap><? echo $r->Cargo ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=15 nowrap  ><?php echo $result["pages"]?></th>
					
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

