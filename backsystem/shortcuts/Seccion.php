<?
$Acceso = "Seccion";
?>
<div class="hidemenu">
    <a href="#" id="hidemenuleft" ><img src="images/hide.gif" alt="ocultar panel" border="0" /></a>
</div>
<div id="shortcuts">
    <br />
    <strong>Accesos Directos</strong>
    <hr />
    <ul>
        <li><a href="?mod=<?=$mod?>">Listar <?=$Acceso?></a></li>
        <li><a href="?mod=<?=$mod?>&action=add">Nuevo <?=$Acceso?></a></li>
        <?php
        if($action == 'edit')
		{
		?>
        <li><a href="?mod=<?=$mod?>&action=del&id=<?php echo $id?>">Eliminar <?=$Acceso?></a></li>
        <?php
		}
		?>
    </ul>
</div>