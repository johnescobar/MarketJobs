
<div id="tabOfertas" class="contentTab">

		<div class="contentButtonsActions">
			<a href="detalleofertas.php?action=add&idempresa=<?=$frm["IDEmpresa"] ?>" class="button orange colorbox">Agregar Oferta</a>
			
		</div>
		<table class="adminform">
			<tr>
				<th>
					&nbsp;Ofertas de <?=$frm["Nombre"] ?>
				</th>
				
			</tr>
			<tr>
				<td>
					
						
					<table class="adminlist" width="100%">
						
						<tr>
							<th align="center" valign="middle" width="64">Ver Oferta</th>
						    <th>Publicación&nbsp;</th>
						    <th>Origen&nbsp;</th>
						    <th>Cargo&nbsp;</th>
						    <th>Estado&nbsp;</th>
						    <th>Fecha de Cierre&nbsp;</th>
						    <th>Publicado&nbsp;</th>
						</tr>
						<tbody id="listaofertas">
						<?php
							$dbo =& SIMDB::get();
							$r_ofertas =& $dbo->all( "Oferta" , "IDEmpresa = '" . $frm[ "IDEmpresa" ] . "' ORDER BY FechaTrCr DESC" );
						 
							while( $r = $dbo->object( $r_contactos ) )
							{
						?>
					  	
						<tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1'?>">
							<td align="center" width="64"><a href="detalleoferta.php&id=<?=$frm["IDEmpresa"]?>&idoferta=<?=$r->IDOferta?>" class="viewoferta" rel="<?php echo $r->IDOferta?>" rev="Oferta"><img src='images/edit.png' border='0' /></a></td>
							<td><? echo SIMUtil::tiempo( substr( $r->FechaTrCr, 0, 10 ) ) ?></td>
							<td><? echo $r->Origen ?></td>
							<td><? echo $r->Cargo ?></td>
							<td><? echo $r->Estado ?></td>
							<td><? echo SIMUtil::tiempo( substr( $r->FechaCierre, 0, 10 ) ) ?></td>
							<td><? echo $r->Publicar ?></td> 
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