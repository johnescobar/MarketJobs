 <?

SIMReg::setFromStructure( array(
					"title" => "Oferta",
					"table" => "Oferta",
					"key" => "IDOferta",
					"mod" => "Oferta"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDEstadoOferta" => "IDEstadoOferta" , "IDEmpresa" => "IDEmpresa" , "IDPais" => "IDPais" , "IDDepartamento" => "IDDepartamento" , "IDCiudad" => "IDCiudad" , "Origen" => "Origen" , "Home" => "Home" , "PosicionHome" => "PosicionHome" , "Cargo" => "Cargo" , "FechaCierre" => "FechaCierre" , "Estado" => "Estado" , "Descripcion" => "Descripcion" , "Sectores" => "Sectores" , "Conocimientos" => "Conocimientos" , "Viaje" => "Viaje" , "ViajeFrecuencia" => "ViajeFrecuencia" , "ZonaAsignada" => "ZonaAsignada" , "IDTipoSalario" => "IDTipoSalario" , "Garantizado" => "Garantizado" , "TiempoGarantizado" => "TiempoGarantizado" , "MontoGarantizado" => "MontoGarantizado" , "SalariosAnio" => "SalariosAnio" , "Beneficios" => "Beneficios" , "ModalidadContrato" => "ModalidadContrato" , "IDHorario" => "IDHorario" , "IDNivelProfesional" => "IDNivelProfesional" , "EstadoCivil" => "EstadoCivil" , "IDRangoEdad" => "IDRangoEdad" , "Genero" => "Genero" , "IDRangoExperiencia" => "IDRangoExperiencia" , "FechaInicio" => "FechaInicio" , "Publicar" => "Publicar" 	
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
			<td  class="columnafija" > IDEstadoOferta </td><td><input id=IDEstadoOferta type=text size=25  name=IDEstadoOferta class="input mandatory " title="IDEstadoOferta" value="<?=$frm[IDEstadoOferta] ?>"> </td>
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
			<td  class="columnafija" > IDCiudad </td><td><input id=IDCiudad type=text size=25  name=IDCiudad class="input mandatory " title="IDCiudad" value="<?=$frm[IDCiudad] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Origen </td><td><? 
								$arrayop = array();
								$arrayop = array('Nuevo','Reemplazo','Otro'); 
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm[Origen]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"Origen[]");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Home </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Home"] , 'Home' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > PosicionHome </td><td><input id=PosicionHome type=text size=25  name=PosicionHome class="input mandatory " title="PosicionHome" value="<?=$frm[PosicionHome] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Cargo </td><td><input id=Cargo type=text size=25  name=Cargo class="input mandatory " title="Cargo" value="<?=$frm[Cargo] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > FechaCierre </td><td><input id=FechaCierre type=text size=10 readonly name=FechaCierre class="input mandatory  calendar " title="FechaCierre" value="<?=$frm[FechaCierre] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Estado </td><td><? 
								$arrayop = array();
								$arrayop = array('Abierta','Cerrada'); 
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm[Estado]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"Estado[]");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Descripcion </td><td><textarea rows="5" id=Descripcion cols=60 wrap=virtual class="input mandatory" title="Descripcion" name=Descripcion><?=$frm[Descripcion]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Sectores </td><td><textarea rows="5" id=Sectores cols=60 wrap=virtual class="input mandatory" title="Sectores" name=Sectores><?=$frm[Sectores]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Conocimientos </td><td><textarea rows="5" id=Conocimientos cols=60 wrap=virtual class="input mandatory" title="Conocimientos" name=Conocimientos><?=$frm[Conocimientos]?></textarea></td>
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
			<td  class="columnafija" > IDTipoSalario </td><td><input id=IDTipoSalario type=text size=25  name=IDTipoSalario class="input mandatory " title="IDTipoSalario" value="<?=$frm[IDTipoSalario] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Garantizado </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Garantizado"] , 'Garantizado' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > TiempoGarantizado </td><td><input id=TiempoGarantizado type=text size=25  name=TiempoGarantizado class="input mandatory " title="TiempoGarantizado" value="<?=$frm[TiempoGarantizado] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > MontoGarantizado </td><td><input id=MontoGarantizado type=text size=25  name=MontoGarantizado class="input mandatory " title="MontoGarantizado" value="<?=$frm[MontoGarantizado] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > SalariosAnio </td><td><input id=SalariosAnio type=text size=25  name=SalariosAnio class="input mandatory " title="SalariosAnio" value="<?=$frm[SalariosAnio] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Beneficios </td><td><textarea rows="5" id=Beneficios cols=60 wrap=virtual class="input mandatory" title="Beneficios" name=Beneficios><?=$frm[Beneficios]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > ModalidadContrato </td><td><? 
								$arrayop = array();
								$arrayop = array('TerminoFijo','Indefinido'); 
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm[ModalidadContrato]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"ModalidadContrato[]");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > IDHorario </td><td><input id=IDHorario type=text size=25  name=IDHorario class="input mandatory " title="IDHorario" value="<?=$frm[IDHorario] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDNivelProfesional </td><td><input id=IDNivelProfesional type=text size=25  name=IDNivelProfesional class="input mandatory " title="IDNivelProfesional" value="<?=$frm[IDNivelProfesional] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > EstadoCivil </td><td><input id=EstadoCivil type=text size=25  name=EstadoCivil class="input mandatory " title="EstadoCivil" value="<?=$frm[EstadoCivil] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoEdad </td><td><input id=IDRangoEdad type=text size=25  name=IDRangoEdad class="input mandatory " title="IDRangoEdad" value="<?=$frm[IDRangoEdad] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Genero </td><td><input id=Genero type=text size=25  name=Genero class="input mandatory " title="Genero" value="<?=$frm[Genero] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > IDRangoExperiencia </td><td><input id=IDRangoExperiencia type=text size=25  name=IDRangoExperiencia class="input mandatory " title="IDRangoExperiencia" value="<?=$frm[IDRangoExperiencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > FechaInicio </td><td><input id=FechaInicio type=text size=10 readonly name=FechaInicio class="input mandatory  calendar " title="FechaInicio" value="<?=$frm[FechaInicio] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Publicar </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Publicar"] , 'Publicar' , "class=' mandatory'" ) ?></td>
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
					<td class=texto colspan=35  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					IDEstadoOferta&nbsp;
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
					IDCiudad&nbsp;
				</th>
				<th>
					Origen&nbsp;
				</th>
				<th>
					Home&nbsp;
				</th>
				<th>
					PosicionHome&nbsp;
				</th>
				<th>
					Cargo&nbsp;
				</th>
				<th>
					FechaCierre&nbsp;
				</th>
				<th>
					Estado&nbsp;
				</th>
				<th>
					Descripcion&nbsp;
				</th>
				<th>
					Sectores&nbsp;
				</th>
				<th>
					Conocimientos&nbsp;
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
					IDTipoSalario&nbsp;
				</th>
				<th>
					Garantizado&nbsp;
				</th>
				<th>
					TiempoGarantizado&nbsp;
				</th>
				<th>
					MontoGarantizado&nbsp;
				</th>
				<th>
					SalariosAnio&nbsp;
				</th>
				<th>
					Beneficios&nbsp;
				</th>
				<th>
					ModalidadContrato&nbsp;
				</th>
				<th>
					IDHorario&nbsp;
				</th>
				<th>
					IDNivelProfesional&nbsp;
				</th>
				<th>
					EstadoCivil&nbsp;
				</th>
				<th>
					IDRangoEdad&nbsp;
				</th>
				<th>
					Genero&nbsp;
				</th>
				<th>
					IDRangoExperiencia&nbsp;
				</th>
				<th>
					FechaInicio&nbsp;
				</th>
				<th>
					Publicar&nbsp;
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
<td nowrap><? echo $r->IDEstadoOferta ?></td> <td nowrap><? echo $r->IDEmpresa ?></td> <td nowrap><? echo $r->IDPais ?></td> <td nowrap><? echo $r->IDDepartamento ?></td> <td nowrap><? echo $r->IDCiudad ?></td> <td nowrap><? echo $r->Origen ?></td> <td nowrap><? echo $r->Home ?></td> <td nowrap><? echo $r->PosicionHome ?></td> <td nowrap><? echo $r->Cargo ?></td> <td nowrap><? echo $r->FechaCierre ?></td> <td nowrap><? echo $r->Estado ?></td> <td nowrap><? echo $r->Descripcion ?></td> <td nowrap><? echo $r->Sectores ?></td> <td nowrap><? echo $r->Conocimientos ?></td> <td nowrap><? echo $r->Viaje ?></td> <td nowrap><? echo $r->ViajeFrecuencia ?></td> <td nowrap><? echo $r->ZonaAsignada ?></td> <td nowrap><? echo $r->IDTipoSalario ?></td> <td nowrap><? echo $r->Garantizado ?></td> <td nowrap><? echo $r->TiempoGarantizado ?></td> <td nowrap><? echo $r->MontoGarantizado ?></td> <td nowrap><? echo $r->SalariosAnio ?></td> <td nowrap><? echo $r->Beneficios ?></td> <td nowrap><? echo $r->ModalidadContrato ?></td> <td nowrap><? echo $r->IDHorario ?></td> <td nowrap><? echo $r->IDNivelProfesional ?></td> <td nowrap><? echo $r->EstadoCivil ?></td> <td nowrap><? echo $r->IDRangoEdad ?></td> <td nowrap><? echo $r->Genero ?></td> <td nowrap><? echo $r->IDRangoExperiencia ?></td> <td nowrap><? echo $r->FechaInicio ?></td> <td nowrap><? echo $r->Publicar ?></td> 
<td align=center width=64 ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=35 nowrap  ><?php echo $result["pages"]?></th>
					
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

