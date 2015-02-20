 <?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Noticia",
					"table" => "Noticia",
					"key" => "IDNoticia",
					"mod" => "Noticia"
) );

//Para validar los campos del formulario
$array_valida = array(
	"Titular" => "Titular","Publicar" => "Publicar"
);


//extraemos las variables
$table = SIMReg::get( "table" );
$key = SIMReg::get( "key" );
$mod = SIMReg::get( "mod" );
$dbo =& SIMDB::get();

//creando las notificaciones que llegan en el parametro m de la URL
SIMNotify::capture( SIMResources::$mensajes[ SIMNet::req("m") ]["msg"] , SIMResources::$mensajes[ SIMNet::req("m") ]["type"] );


switch ( SIMNet::req( "action" ) )
{
	case "add" :
		print_form( "" , "insert" , "Agregar Registro" );
		break;

	case "insert" :
		$_POST["param"]["noticia"]["IDSeccion"] = SIMNet::post( "IDSeccion" );
		$_POST["param"]["noticia"]["Cuerpo"] = $_POST["Cuerpo"];

		//seguridad para cada campo del formulario
		foreach($_POST["param"]["noticia"] as $clave=>$valor)
		{
			if( $clave != Cuerpo)
			$_POST["param"]["noticia"][$clave] = SIMUtil::antiinjection($valor);
		}
		/*
		 * Verificamos si el formulario valida.
		 * Si no valida devuelve un mensaje de error.
		 * SIMResources::capture  captura ese mensaje y si el mensaje existe devulve true
		 */

		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["noticia"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["noticia"] );

			$files =  SIMFile::upload( $_FILES["NoticiaImagen"] , IMGNOTICIA_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["NoticiaImagen"]["name"] ) )
			SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

			$frm["NoticiaFile"] = $files[0]["innername"];
			
			//insertamos los datos del asistente
			$id = $dbo->insert( $frm , $table , $key );

			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&m=insertarexito" );
		}
		else
		print_form( $_POST["param"]["noticia"] , "insert" , "Agregar Registro" );
		break;

	case "edit":
		//seguridad para cada campo del formulario
		foreach($_GET as $clave=>$valor)
		$_GET[$clave] = SIMUtil::antiinjection($valor);
		$frm = $dbo->fetchById( $table , $key , $_GET["id"] , "array" );
		print_form( $frm , "update" , "Realizar Cambios" );
		break ;

	case "update" :
		$_POST["param"]["noticia"]["IDSeccion"] = SIMNet::post( "IDSeccion" );
		$_POST["param"]["noticia"]["Cuerpo"] = $_POST["Cuerpo"];

		/*if($_FILES["NoticiaImagen"])
		$_POST["param"]["noticia"]["NoticiaFile"] = $_FILES["NoticiaImagen"]["name"];
		*/

		//seguridad para cada campo del formulario
		foreach($_POST["param"]["noticia"] as $clave=>$valor)
		{
			if( $clave != Cuerpo)
			$_POST["param"]["noticia"][$clave] = SIMUtil::antiinjection($valor);
		}

		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["noticia"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["noticia"] );

			

			$files =  SIMFile::upload( $_FILES["NoticiaImagen"] , IMGNOTICIA_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["NoticiaImagen"]["name"] ) )
			SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

			$frm["NoticiaFile"] = $files[0]["innername"];
			
			$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );
			
			
			$frm = $dbo->fetchById( $table , $key , $id , "array" );

			SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );

			print_form( $frm , "update" ,  "Realizar Cambios" );
		}
		else
		print_form( $_POST["param"]["noticia"] , "update" ,  "Realizar Cambios" );
		break;

	case "del":
		//seguridad para cada campo del formulario
		foreach($_GET as $clave=>$valor)
		$_GET[$clave] = SIMUtil::antiinjection($valor);
		$frm = $dbo->fetchById( $table , $key , $_GET["id"] , "array" );

		print_form( $frm , "delete" , "Remover Registro" );
		break ;

	case "delete" :
		$dbo =& SIMDB::get();
		//seguridad para cada campo del formulario
		foreach($_POST as $clave=>$valor)
		$_POST[$clave] = SIMUtil::antiinjection($valor);

		$dbo->deleteById( $table , $key , $_POST["ID"] );

		SIMHTML::jsAlert("Registro Eliminado Correctamente");

		SIMHTML::jsRedirect( "?mod=" . $mod . "&m=eliminarrexito" );
		break;

	case "DelImgNot":
		$doceliminar = IMGNOTICIA_DIR.$dbo->getFields( "Noticia" , $_GET[Campo] , "IDNoticia = '" . $_GET[id] . "' " );
		unlink($doceliminar);
		$dbo->query("UPDATE Noticia SET ".$_GET[Campo]." = '' WHERE IDNoticia = $_GET[id] LIMIT 1 ;");
		SIMHTML::jsAlert("Imagen Eliminada Correctamente");
		SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=".$id );
		exit;
		break;

	case "list" :

		$where_array = array();
		$fieldInt = array();
		$fieldStr = array ( "Titular" );

		$fromjoin = $fieldInt;

		$wherejoin = $fieldInt;

		$params = SIMUtil::filter( $fieldInt , $fieldStr , $fromjoin , $where_array , $wherejoin );

		$sql = " SELECT V.* FROM " . $table . " V " . $params["from"] . $params["where"] . " ";
		
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
<table class="adminheading">
	<tr>
		<th><?php echo SIMReg::get( "title" )?></th>
	</tr>
</table>
	<?php
	//imprime el HTML de errores
	SIMNotify::each();
	include( "includes/tabs.html" );
	?>
<div id="tabsform">

<div id="NoticiaActual">
<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>"
	method="post" enctype="multipart/form-data" class="formvalida">
<table class="adminform">
	<tr>
		<th>&nbsp;Datos</th>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td class="columnafija">Seccion Padre</td>
				<td><input type="hidden" id="IDSeccion" name="IDSeccion"
					value="<?php echo $frm["IDSeccion"];?>"> <input type="text"
					id="NombreSeccion" name="NombreSeccion" class="input"
					value="<?php echo $dbo->getFields( "Seccion" , "Nombre" , "IDSeccion = '" . $frm["IDSeccion"] . "'" )?>"
					readonly="readonly"> <a href="PopupSeccion.php" target="_blank"
					onClick="window.open(this.href, this.target, 'width=300,height=1000,scrollbars=yes'); return false;"><img
					alt="Seccion" src="images/magnifier.png" border="0"></a></td>
			</tr>
			<tr>
				<td>Titular</td>
				<td><input id="param[noticia][Titular]" type="text" size="25"
					title="Titular" name="param[noticia][Titular]"
					class="input mandatory" value="<?php echo $frm["Titular"] ?>" /></td>
			</tr>
            
			<tr>
				<td>Introduccion</td>
				<td><textarea rows="5" cols="100" id="param[noticia][Introduccion]"
					name="param[noticia][Introduccion]" title="Introduccion"
					class="input mandatory"><?php echo $frm["Introduccion"];?></textarea>
				</td>
			</tr>
			<tr>
				<td>Cuerpo</td>
				<td><?php
				$oCuerpo = new FCKeditor( "Cuerpo" ) ;
				$oCuerpo->BasePath = "jscript/fckeditor/";
				$oCuerpo->Height = 400;
				$oCuerpo->EnterMode = "p";
				$oCuerpo->Value =  $frm["Cuerpo"];
				$oCuerpo->Create() ;
				?></td>
			</tr>
			<tr>
				<td>Publicar</td>
				<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Publicar"] , "param[noticia][Publicar]" , "title=\"Publicar\"" )?>
				</td>
			</tr>
            <tr>
				<td>Orden</td>
				<td><input id="param[noticia][Orden]" type="text" size="25"
					title="Orden" name="param[noticia][Orden]" class="input mandatory"
					value="<?php echo $frm["Orden"] ?>" /></td>
			</tr>
			<tr>
				<td>Fecha Inicio</td>
				<td><input id="param[noticia][FechaInicio]" type="text" size="10"
					title="Fecha Inicio" name="param[noticia][FechaInicio]"
					class="input mandatory calendar"
					value="<?php echo $frm["FechaInicio"] ?>" readonly="readonly" /></td>
			</tr>
			<tr>
				<td>Fecha Fin</td>
				<td><input id="param[noticia][FechaFin]" type="text" size="10"
					title="Fecha Fin" name="param[noticia][FechaFin]"
					class="input mandatory calendar"
					value="<?php echo $frm["FechaFin"] ?>" readonly="readonly" /></td>
			</tr>
			<tr>
				<td>Imagen Noticia (341 X 267)</td>
				<td><?php
				if($frm["NoticiaFile"])
				{
					?> <img alt="<?php echo $frm["NoticiaFile"] ?>"
					src="<?php echo IMGNOTICIA_ROOT.$frm["NoticiaFile"]?>" width="341" height="267"> <a
					href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelImgNot&Campo=NoticiaFile&id=" .$frm[ $key ]?>"><img
					src='images/trash.png' border='0'></a> <?php
				}
				else
				{
					?> <input type="file" name="NoticiaImagen" id="NoticiaImagen"
					class="popup" title="Noticia Imagen"> <?php
				}
				?></td>
			</tr>
            
			<tr>
				<td>URL</td>
				<td><input id="param[noticia][URL]" type="text" size="25"
					title="URL" name="param[noticia][URL]" class="input"
					value="<?php echo $frm["URL"] ?>" /></td>
			</tr>
			<tr>
				<td>SEO Title</td>
				<td><textarea rows="5" cols="50" id="param[noticia][SEO_Title]"
					name="param[noticia][SEO_Title]" class="input"><?php echo $frm["SEO_Title"] ?></textarea>
				</td>
			</tr>
			<tr>
				<td>SEO Description</td>
				<td><textarea rows="5" cols="50"
					id="param[noticia][SEO_Description]"
					name="param[noticia][SEO_Description]" class="input"><?php echo $frm["SEO_Description"] ?></textarea>
				</td>
			</tr>
			<tr>
				<td>SEO KeyWords</td>
				<td><textarea rows="5" cols="50" id="param[noticia][SEO_KeyWords]"
					name="param[noticia][SEO_KeyWords]" class="input"><?php echo $frm["SEO_KeyWords"] ?></textarea>
			
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit"
					value="<?php echo $submit_caption ?>" class="submit" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>

                <input type="hidden"
	name="ID" id="ID" value="<?php echo $frm[ $key ] ?>" /> <input
	type="hidden" name="action" id="action" value="<?php echo $newmode?>" />
</form>
</div>

	</div>

	<?php

}// End function print_form()

/*******************************************************************************************
 funcion Listar
 *******************************************************************************************/
function list_r( $sql = "" )
{
	$key = SIMReg::get( "key" );
	$table = SIMReg::get( "table" );
	$dbo =&SIMDB::get();

	if( empty( $sql ) )
	$sql =  "SELECT * FROM " . $table . " ORDER BY FechaTrCr DESC";

	$paging = new PHPPaging;
	$paging->agregarConsulta($sql);
	$paging->porPagina(100);
	$paging->linkSeparador("  ");
	$paging->mostrarPrimera("[<<]", true);
	$paging->mostrarUltima("[>>]", true);
	$paging->mostrarAnterior("<");
	$paging->mostrarSiguiente(">");
	$paging->ejecutar();
	?>
<table class="adminheading">
	<tr>
		<th><?php echo SIMReg::get( "title" )?></th>
	</tr>
</table>
	<?php
	filtrar();

	if( $paging->numTotalPaginas() > 0 )
	{
		//imprime el HTML de errores
		SIMNotify::each();
		?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
		<table class="adminlist" id="orderTable">
			<thead>
				<tr>
					<th align="center" valign="middle" width="64">Editar</th>
					<th>Titular</th>
                    <th>Seccion</th>
					<th>Publicar</th>
					<th align="center" valign="middle" width="64">Eliminar</th>
				</tr>
			</thead>
			<tbody>

			<?php

			while( $r = $paging->fetchResultado() )
			{
				?>
				<tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1' ?>">
					<td align="center" width="64"><a
						href="<?php echo "?mod=" . SIMReg::get( "mod" ) . "&amp;action=edit&amp;id=" . $r->$key?>"><img
						src='images/edit.png' border='0'></a></td>
					<td><?php echo $r->Titular?></td>
                    <td><? echo $dbo->getFields( "Seccion","Nombre","IDSeccion = " . $r->IDSeccion ) ?></td>
					<td><?php echo $r->Publicar?></td>
					<td align="center" width="64"><a
						href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&amp;action=del&amp;id=" . $r->$key?>"><img
						src='images/trash.png' border='0'></a></td>
				</tr>
				<?php
			}
			?>
				<tr>
					<th class="texto" colspan="5"><?php echo $paging->fetchNavegacion()?></th>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
</table>

			<?php
	}
	else
	{
		SIMNotify::capture( "No se han encontrado registros" , "error" );
		//imprime el HTML de errores
		SIMNotify::each();
	}
}// Enf function list()

/*******************************************************************************************
 funcion filtrar
 *******************************************************************************************/
function filtrar()
{
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
				<td width="100">Titular</td>
				<td width="131"><input type="text" size="14" value="" name="Titular"
					id="Titular" class="input" /></td>
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
?>