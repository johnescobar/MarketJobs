<?
//verificar si viene el id del contacto
$classForm = "hide";
if( !empty( SIMNet::get("idcontacto") ) )
{
	$qry_contacto = $dbo->all( "EmpresaContacto" , "IDEmpresa = '" . $frm["IDEmpresa"] . "' AND IDContacto = '" . SIMNet::get("idcontacto") . "' " );
	$Contacto = $dbo->fetchArray($qry_contacto);
	$actioncontacto = "updatecontacto";
	$classForm = "";

	//Usuario
	$usuario = $dbo->fetchById("Usuario" , "IDContacto" , SIMNet::get("idcontacto") , "array"  );

}//end if
?>
<div id="tabContactos">
	<form name="frmcontacto" id="frmcontacto" action="?mod=<?php echo SIMReg::get( "mod" )?>&amp;action=contact" method="post" rev="Cliente" class="formvalida">
		<div class="contentButtonsActions">
			<input type="button" class="submit btnShow " rel="contentContact" value="Agregar Contacto" />
		</div>
		<table class="adminform">
			<tr>
				<th>
					&nbsp;Contactos de <?=$frm["Nombre"] ?>
				</th>
				
			</tr>
			<tr>
				<td>
					<div class="<?=$classForm ?> " id="contentContact">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
								<td  class="columnafija" > País </td><td>
									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Pais" , "Nombre" , "Nombre" , "IDPais" , $Contacto["IDPais"] ," 1 " , "[Seleccione el Pais]" , "popup mandatory" , "title = \"país\"" )?>
									</div>
								</td>
							</tr>
							<tr>
								<td  class="columnafija" > Departamento </td><td>

									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Departamento" , "Nombre" , "Nombre" , "IDDepartamento" , $Contacto["IDDepartamento"] ," 1 " , "[Seleccione el Departamento]" , "popup mandatory" , "title = \"departamento\"" )?>
									</div>
								</td>
							</tr>
							<tr>
								<td  class="columnafija" > Ciudad </td><td>
									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Ciudad" , "Nombre" , "Nombre" , "IDCiudad" , $Contacto["IDCiudad"] ," 1 " , "[Seleccione la Ciudad]" , "popup mandatory" , "title = \"ciudad\"" )?>
									</div>

								</td>
							</tr>


							<tr>
								<td class="columnafija"> Nombre </td>
								<td><input type="text" size="25" title="Nombre" name="param[contacto][Nombre]" id="param[contacto][Nombre]" class="input mandatory" value="<?php echo $Contacto["Nombre"]?>" /> </td>
							</tr>
							<tr>
								<td> Apellido</td>
								<td><input type="text" size="25" title="Apellido" name="param[contacto][Apellido]" id="param[contacto][Apellido]" class="input mandatory" value="<?php echo $Contacto["Apellido"]?>"> </td>
							</tr>
							<tr>
								<td> Numero Documento</td>
								<td><input type="text" size="25" title="NumeroDocumento" name="param[contacto][NumeroDocumento]" id="param[contacto][NumeroDocumento]" class="input mandatory" value="<?php echo $Contacto["NumeroDocumento"]?>"> </td>
							</tr>
							
							<tr>
								<td> Tel&eacute;fono </td>
								<td><input type="text" size="25" name="param[contacto][Telefono]" id="param[contacto][Telefono]" title="Tel&eacute;fono" class="input mandatory" value="<?php echo $Contacto["Telefono"]?>" /> </td>
							</tr>
							<tr>
								<td> Extensión </td>
								<td><input type="text" size="25" name="param[contacto][Extension]" id="param[contacto][Extension]" title="Tel&eacute;fono" class="input mandatory" value="<?php echo $Contacto["Extension"] ?>" /> </td>
							</tr>
							<tr>
								<td> Email </td>
								<td><input type="text" size="25" title="Email" name="param[contacto][Email]" id="param[contacto][Email]" class="input mandatory" value="<?php echo $Contacto["Email"]?>" /> </td>
							</tr>

							<tr>
								<td> Password </td>
								<td><input type="password" size="25" title="Password" name="param[contacto][Password]" id="param[contacto][Password]" class="input " value="<?php echo $frm["Password"]?>" /> </td>
							</tr>
							<tr>
								<td> Autorizado </td>
								<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $usuario["Autorizado"] , "param[contacto][Autorizado]" , "title=\"Autorizado\"" )?> </td>
							</tr>
							
							<tr>
								<td> Cargo </td>
								<td><input type="text" size="25" title="Cargo" name="param[contacto][Cargo]" id="param[contacto][Cargo]" class="input mandatory" value="<?php echo $Contacto["Cargo"]?>" /> </td>
							</tr>
							<tr>
								<td> Celular </td>
								<td><input type="text" size="25" title="Celular" name="param[contacto][Celular]" id="param[contacto][Celular]" class="input mandatory" value="<?php echo $Contacto["Celular"]?>" /> </td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="hidden" name="action" id="action" value="<?php echo $actioncontacto ? $actioncontacto : "insertcontacto"?>" />
									<input type="hidden" name="IDContacto" id="IDContacto" value="<?php echo $Contacto["IDContacto"]?>" />
									<input type="hidden" name="IDEmpresa" id="IDEmpresa" value="<?php echo $frm["IDEmpresa"]?>" />
									<a href="#" class="button btnEnviar orange" >Guardar Contacto</a>
								</td>
							</tr>
						</table>
					</div>
						
					<br />
					<table class="adminlist" width="100%">
						<tr>
							<th class="title" colspan="15"><?php echo strtoupper( "Contactos del Cliente" ) . ": Listado"?></th>
						</tr>
						<tr>
							<th align="center" valign="middle" width="64">Editar</th>
						    <th>Nombre&nbsp;</th>
						    <th>Numero Documento&nbsp;</th>
						    <th>Telefono&nbsp;</th>
						    <th>Email&nbsp;</th>
						    <th>Cargo&nbsp;</th>
						    <th>Celular&nbsp;</th>
						</tr>
						<tbody id="listacontactosanunciante">
						<?php
							$dbo =& SIMDB::get();
							$r_contactos =& $dbo->all( "EmpresaContacto" , "IDEmpresa = '" . $frm[ "IDEmpresa" ] . "'" );
						 
							while( $r = $dbo->object( $r_contactos ) )
							{
						?>
					  	
						<tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1'?>">
							<td align="center" width="64"><a href="?mod=<?=$mod ?>&action=edit&id=<?=$frm["IDEmpresa"]?>&idcontacto=<?=$r->IDContacto?>#tabContactos" class="editcontact" rel="<?php echo $r->IDContacto?>" rev="Cliente"><img src='images/edit.png' border='0' /></a></td>
							<td><? echo utf8_decode( $r->Apellido ) . " " . utf8_decode( $r->Nombre) ?></td>
							<td><? echo $r->NumeroDocumento ?></td>
							<td><? echo $r->Telefono ?></td>
							<td><? echo $r->Email ?></td>
							<td><? echo utf8_decode( $r->Cargo )?></td>
							<td><? echo $r->Celular ?></td> 
						</tr>
						<?php
						}
						?>
						</tbody>
						<tr>
							<th class="texto" colspan="15"></th>
						</tr>		
					</table>
				</td>
			</tr>
		</table>
	</form>
</div>