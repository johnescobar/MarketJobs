 <?

SIMReg::setFromStructure( array(
					"title" => "Candidato",
					"table" => "Candidato",
					"key" => "IDCandidato",
					"mod" => "Candidato"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDNacionalidad" => "IDNacionalidad" , "IDPais" => "IDPais" , "IDDepartamento" => "IDDepartamento" , "IDCiudad" => "IDCiudad" , "Viaje" => "Viaje" , "ViajeFrecuencia" => "ViajeFrecuencia" , "ZonaAsignada" => "ZonaAsignada" , "IDNivelProfesional" => "IDNivelProfesional" , "EstadoCivil" => "EstadoCivil" , "Genero" => "Genero" , "IDRangoExperiencia" => "IDRangoExperiencia" , "Nombre" => "Nombre" , "Apellido" => "Apellido" , "FechaNacimiento" => "FechaNacimiento" , "IDTipoDocumento" => "IDTipoDocumento" , "NumeroDocumento" => "NumeroDocumento" , "Email" => "Email" , "Telefono" => "Telefono" , "Celular" => "Celular" , "CodigoPostal" => "CodigoPostal" , "File" => "File" , "IDRangoSalarioActual" => "IDRangoSalarioActual" , "IDRangoSalarioDeseado" => "IDRangoSalarioDeseado" , "AceptaCondiciones" => "AceptaCondiciones" , "Condiciones" => "Condiciones" , "ConocimientosEspecificos" => "ConocimientosEspecificos" , "Foto" => "Foto" , "Origen" => "Origen" 	
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
			<td  class="columnafija" > IDNacionalidad </td><td>
			<div class="a-select">
				<?php echo SIMHTML::formPopUp( "Pais" , "Nombre" , "Nombre" , "IDNacionalidad" , $frm["IDPais"] ," 1 " , "[Seleccione Nacionalidad]" , "popup mandatory" , "title = \"país\"" )?>
			</div>
			</td>
			</tr>
			<tr>
				<td  class="columnafija" > País </td><td>
					<div class="a-select">
						<?php echo SIMHTML::formPopUp( "Pais" , "Nombre" , "Nombre" , "IDPais" , $frm["IDPais"] ," 1 " , "[Seleccione el Pais]" , "popup mandatory" , "title = \"país\"" )?>
					</div>
				</td>
			</tr>
			<tr>
				<td  class="columnafija" > Departamento </td><td>

					<div class="a-select">
						<?php echo SIMHTML::formPopUp( "Departamento" , "Nombre" , "Nombre" , "IDDepartamento" , $frm["IDDepartamento"] ," 1 " , "[Seleccione el Departamento]" , "popup mandatory" , "title = \"departamento\"" )?>
					</div>
				</td>
			</tr>
			<tr>
				<td  class="columnafija" > Ciudad </td><td>
					<div class="a-select">
						<?php echo SIMHTML::formPopUp( "Ciudad" , "Nombre" , "Nombre" , "IDCiudad" , $frm["IDCiudad"] ," 1 " , "[Seleccione la Ciudad]" , "popup mandatory" , "title = \"ciudad\"" )?>
					</div>

				</td>
			</tr>
			<tr>
			<td  class="columnafija" > Viaje </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Viaje"] , 'Viaje' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > ViajeFrecuencia </td><td><input id=ViajeFrecuencia type=text size=25  name=ViajeFrecuencia class="input mandatory " title="ViajeFrecuencia" value="<?=$frm[ViajeFrecuencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > ZonaAsignada </td><td><input id=ZonaAsignada type=text size=25  name=ZonaAsignada class="input mandatory " title="ZonaAsignada" value="<?=$frm[ZonaAsignada] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDNivelProfesional </td><td><input id=IDNivelProfesional type=text size=25  name=IDNivelProfesional class="input mandatory " title="IDNivelProfesional" value="<?=$frm[IDNivelProfesional] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > EstadoCivil </td><td><input id=EstadoCivil type=text size=25  name=EstadoCivil class="input mandatory " title="EstadoCivil" value="<?=$frm[EstadoCivil] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Genero </td><td><input id=Genero type=text size=25  name=Genero class="input mandatory " title="Genero" value="<?=$frm[Genero] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoExperiencia </td><td><input id=IDRangoExperiencia type=text size=25  name=IDRangoExperiencia class="input mandatory " title="IDRangoExperiencia" value="<?=$frm[IDRangoExperiencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Nombre </td><td><input id=Nombre type=text size=25  name=Nombre class="input mandatory " title="Nombre" value="<?=$frm[Nombre] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Apellido </td><td><input id=Apellido type=text size=25  name=Apellido class="input mandatory " title="Apellido" value="<?=$frm[Apellido] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > FechaNacimiento </td><td><input id=FechaNacimiento type=text size=10 readonly name=FechaNacimiento class="input mandatory  calendar " title="FechaNacimiento" value="<?=$frm[FechaNacimiento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDTipoDocumento </td><td><input id=IDTipoDocumento type=text size=25  name=IDTipoDocumento class="input mandatory " title="IDTipoDocumento" value="<?=$frm[IDTipoDocumento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > NumeroDocumento </td><td><input id=NumeroDocumento type=text size=25  name=NumeroDocumento class="input mandatory " title="NumeroDocumento" value="<?=$frm[NumeroDocumento] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Email </td><td><input id=Email type=text size=25  name=Email class="input mandatory " title="Email" value="<?=$frm[Email] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Telefono </td><td><input id=Telefono type=text size=25  name=Telefono class="input mandatory " title="Telefono" value="<?=$frm[Telefono] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Celular </td><td><input id=Celular type=text size=25  name=Celular class="input mandatory " title="Celular" value="<?=$frm[Celular] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > CodigoPostal </td><td><input id=CodigoPostal type=text size=25  name=CodigoPostal class="input mandatory " title="CodigoPostal" value="<?=$frm[CodigoPostal] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > File </td><td><input id=File type=text size=25  name=File class="input mandatory " title="File" value="<?=$frm[File] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoSalarioActual </td><td><input id=IDRangoSalarioActual type=text size=25  name=IDRangoSalarioActual class="input mandatory " title="IDRangoSalarioActual" value="<?=$frm[IDRangoSalarioActual] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoSalarioDeseado </td><td><input id=IDRangoSalarioDeseado type=text size=25  name=IDRangoSalarioDeseado class="input mandatory " title="IDRangoSalarioDeseado" value="<?=$frm[IDRangoSalarioDeseado] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > AceptaCondiciones </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["AceptaCondiciones"] , 'AceptaCondiciones' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Condiciones </td><td><textarea rows="5" id=Condiciones cols=60 wrap=virtual class="input mandatory" title="Condiciones" name=Condiciones><?=$frm[Condiciones]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > ConocimientosEspecificos </td><td><textarea rows="5" id=ConocimientosEspecificos cols=60 wrap=virtual class="input mandatory" title="ConocimientosEspecificos" name=ConocimientosEspecificos><?=$frm[ConocimientosEspecificos]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" >
		<? if (!empty($frm[Foto])) {
		echo "<img src='img/$frm[Foto]' width=55 height=66>";
			?>
			<a href="<? echo "?mod=$MOD&action=delfoto&foto=$frm[Foto]&campo=Foto&id=".$frm[$Key]; ?>">
			<img src='images/trash.gif' border='0'>
			</a>
			<?
			}// END if
			?>
	 Foto </td><td><input name="Foto" id=file class="mandatory" title="Foto" type="file" size="25" style="font-size:10px"></td>
			</tr>
			<tr>
			<td  class="columnafija" > Origen </td><td><? 
								$arrayop = array();
								$arrayop = array('Web','Admin'); 
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm[Origen]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"Origen[]");
								?></td>
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
					<td class=texto colspan=31  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					IDNacionalidad&nbsp;
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
					Viaje&nbsp;
				</th>
				<th>
					ViajeFrecuencia&nbsp;
				</th>
				<th>
					ZonaAsignada&nbsp;
				</th>
				<th>
					IDNivelProfesional&nbsp;
				</th>
				<th>
					EstadoCivil&nbsp;
				</th>
				<th>
					Genero&nbsp;
				</th>
				<th>
					IDRangoExperiencia&nbsp;
				</th>
				<th>
					Nombre&nbsp;
				</th>
				<th>
					Apellido&nbsp;
				</th>
				<th>
					FechaNacimiento&nbsp;
				</th>
				<th>
					IDTipoDocumento&nbsp;
				</th>
				<th>
					NumeroDocumento&nbsp;
				</th>
				<th>
					Email&nbsp;
				</th>
				<th>
					Telefono&nbsp;
				</th>
				<th>
					Celular&nbsp;
				</th>
				<th>
					CodigoPostal&nbsp;
				</th>
				<th>
					File&nbsp;
				</th>
				<th>
					IDRangoSalarioActual&nbsp;
				</th>
				<th>
					IDRangoSalarioDeseado&nbsp;
				</th>
				<th>
					AceptaCondiciones&nbsp;
				</th>
				<th>
					Condiciones&nbsp;
				</th>
				<th>
					ConocimientosEspecificos&nbsp;
				</th>
				<th>
					Foto&nbsp;
				</th>
				<th>
					Origen&nbsp;
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
<td nowrap><? echo $r->IDNacionalidad ?></td> <td nowrap><? echo $r->IDPais ?></td> <td nowrap><? echo $r->IDDepartamento ?></td> <td nowrap><? echo $r->IDCiudad ?></td> <td nowrap><? echo $r->Viaje ?></td> <td nowrap><? echo $r->ViajeFrecuencia ?></td> <td nowrap><? echo $r->ZonaAsignada ?></td> <td nowrap><? echo $r->IDNivelProfesional ?></td> <td nowrap><? echo $r->EstadoCivil ?></td> <td nowrap><? echo $r->Genero ?></td> <td nowrap><? echo $r->IDRangoExperiencia ?></td> <td nowrap><? echo $r->Nombre ?></td> <td nowrap><? echo $r->Apellido ?></td> <td nowrap><? echo $r->FechaNacimiento ?></td> <td nowrap><? echo $r->IDTipoDocumento ?></td> <td nowrap><? echo $r->NumeroDocumento ?></td> <td nowrap><? echo $r->Email ?></td> <td nowrap><? echo $r->Telefono ?></td> <td nowrap><? echo $r->Celular ?></td> <td nowrap><? echo $r->CodigoPostal ?></td> <td nowrap><? echo $r->File ?></td> <td nowrap><? echo $r->IDRangoSalarioActual ?></td> <td nowrap><? echo $r->IDRangoSalarioDeseado ?></td> <td nowrap><? echo $r->AceptaCondiciones ?></td> <td nowrap><? echo $r->Condiciones ?></td> <td nowrap><? echo $r->ConocimientosEspecificos ?></td> <td nowrap><? echo $r->Foto ?></td> <td nowrap><? echo $r->Origen ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=31 nowrap  ><?php echo $result["pages"]?></th>
					
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

