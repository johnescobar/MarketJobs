<div id="Color">

	<form name="frmpro" id="frmpro"
		action="?mod=<?php echo SIMReg::get( "mod" )?>" method="post"
		class="formvalida" enctype="multipart/form-data"><?php
		$action = "InsertarColor";

		if( SIMNet::get("IDColorTratamiento") )
		{
			$EditColor =$dbo->fetchAll("ColorTratamiento"," IDColorTratamiento = '" . SIMNet::get("IDColorTratamiento") . "' ","array");
			$action = "ModificaColor";
		?>
			<input type="hidden" name="IDColorTratamiento" id="IDColorTratamiento" value="<?php echo $EditColor[IDColorTratamiento]?>" /> 
		<?php
		}
		?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
		<tr>
			<th colspan="2">Colores</th>
		</tr>
		<tr>
			<td>Nombre</td>
			<td>
				<input id="Nombre" type="text" size="25" title="Nombre" name="Nombre" class="input mandatory" value="<?php echo $EditColor["Nombre"] ?>" /></td>
		</tr>
		<tr>
			<td>Imagen ()</td>
			<td><?php
			if($EditColor["Foto"])
			{
				?> 
				<a href="<?php echo LENTES_ROOT.$EditColor["Foto"]?>"><?php echo $EditColor["Foto"] ?></a>
				<a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelDocNot&id=".$frm[ $key ]."&idd=" .$EditColor["IDTratamientoLente"]?>"><img src='images/trash.png' border='0'></a> <?php
			}
			else
			{
				?> <input type="file" name="Foto" id="Foto" class="popup" title="Foto"> <?php
			}
			?></td>
		</tr>
		<tr>
			<td align="center"><input type="submit" class="submit" value="Enviar">
			</td>
		</tr>
	</table>
	 <input type="hidden" name="IDTratamientoLente" id="IDTratamientoLente" value="<?php echo $frm[ $key ] ?>" /> 
	 <input type="hidden" name="action" id="action" value="<?php echo $action?>" />
	</form>
	<br />
	<table class="adminlist" width="100%">
		<tr>
			<th class="title" colspan="15"><?php echo strtoupper( "Link" ) . ": Listado"?></th>
		</tr>
		<tr>
			<th align="center" valign="middle" width="64">Editar</th>
			<th>Nombre</th>
			<th align="center" valign="middle" width="64">Eliminar</th>
		</tr>
		<tbody id="listacontactosanunciante">
		<?php

		$qry_detalle =& $dbo->all( "ColorTratamiento" , "IDTratamientoLente = '" . $frm[$key] . "' " );

		while( $r = $dbo->object( $qry_detalle ) )
		{
			?>

			<tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1'?>">
				<td align="center" width="64"><a
					href="<?php echo "?mod=" . SIMReg::get( "mod" ) . "&action=edit&id=" . $frm[ $key ] ."&IDColorTratamiento=".$r->IDColorTratamiento."#Color"?>"><img
					src='images/edit.png' border='0'></a></td>
				<td><? echo $r->Nombre ?></td>
				<td align="center" width="64"><a
					href="?mod=<?php echo SIMReg::get( "mod" )?>&action=EliminaColor&id=<?php echo $frm[ $key ];?>&IDColorTratamiento=<? echo $r->IDColorTratamiento ?>"><img
					src='images/trash.png' border='0' /></a></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tr>
			<th class="texto" colspan="15"></th>
		</tr>
	</table>
</div>