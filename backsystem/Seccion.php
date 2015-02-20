<?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Secciones",
					"table" => "Seccion",
					"key" => "IDSeccion",
					"mod" => "Seccion"
) );

//Para validar los campos del formulario
$array_valida = array(
	"Nombre" => "Nombre"
);


//permisos
SIMUtil::verify( 0 , SIMUser::get( "Nivel" ) );

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

		/*
		 * Verificamos si el formulario valida.
		 * Si no valida devuelve un mensaje de error.
		 * SIMResources::capture  captura ese mensaje y si el mensaje existe devulve true
		*/
				$_POST["param"]["seccion"]["Ubicacion"] = implode(",",$_POST["param"]["seccion"]["Ubicacion"]);

		$_POST["param"]["seccion"]["IDPadre"] = SIMNet::post( "IDSeccion" );

               
                //seguridad para cada campo del formulario
		foreach($_POST["param"]["seccion"] as $clave=>$valor)
			$_POST["param"]["seccion"][$clave] = SIMUtil::antiinjection($valor);


		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["seccion"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["seccion"] );


			$files =  SIMFile::upload( $_FILES["SeccionImagen"] , IMGSECCION_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["SeccionImagen"]["name"] ) )
				SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

			$frm["SeccionFile"] = $files[0]["innername"];


			//insertamos los datos del asistente
			$id = $dbo->insert( $frm , $table , $key );

			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&m=insertarexito" );
		}
		else
			print_form( $_POST["param"]["seccion"] , "insert" , "Agregar Registro" );
	break;

	case "edit":
		//seguridad para cada campo del formulario
		foreach($_GET as $clave=>$valor)
			$_GET[$clave] = SIMUtil::antiinjection($valor);
		$frm = $dbo->fetchById( $table , $key , $_GET["id"] , "array" );
		print_form( $frm , "update" , "Realizar Cambios" );
	break ;

	case "update" :

				$_POST["param"]["seccion"]["Ubicacion"] = implode(",",$_POST["param"]["seccion"]["Ubicacion"]);
                 $_POST["param"]["seccion"]["IDPadre"] = SIMNet::post( "IDSeccion" );
    
                //seguridad para cada campo del formulario
                foreach($_POST["param"]["seccion"] as $clave=>$valor)
					$_POST["param"]["seccion"][$clave] = SIMUtil::antiinjection($valor);


		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["seccion"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["seccion"] );

			

			$files =  SIMFile::upload( $_FILES["SeccionImagen"] , IMGSECCION_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["SeccionImagen"]["name"] ) )
				SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

			$frm["SeccionFile"] = $files[0]["innername"];

			$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id"));

			$frm = $dbo->fetchById( $table , $key , $id , "array" );

			SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );

			print_form( $frm , "update" ,  "Realizar Cambios" );
		}
		else
			print_form( $_POST["param"]["seccion"] , "update" ,  "Realizar Cambios" );
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

	case "DelImgSec":
		$doceliminar = IMGSECCION_DIR.$dbo->getFields( "Seccion" , "SeccionFile" , "IDSeccion = '" . $_GET[id] .  "'" );
		unlink($doceliminar);
		$dbo->query("UPDATE Seccion SET SeccionFile = '' WHERE IDSeccion = $_GET[id] LIMIT 1 ;");
		SIMHTML::jsAlert("Imagen Eliminada Correctamente");
		SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=".$_GET[id] );
		exit;
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

?>
<div id="tabsform">
	<div id="seccion">
		<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">
		<table class="adminform">
			<tr>
				<th>&nbsp;Datos</th>
			</tr>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td class="columnafija"> Seccion Padre </td>
							<td>
							<input type="hidden" id="IDSeccion" name="IDSeccion" value="<?php echo $frm["IDPadre"];?>">
							<input type="text" id="NombreSeccion" name="NombreSeccion" class="input" value="<?php echo $dbo->getFields( "Seccion" , "Nombre" , "IDSeccion = '" . $frm["IDPadre"] . "'" )?>" readonly="readonly">
							<a href="PopupSeccion.php" target="_blank" onClick="window.open(this.href, this.target, 'width=300,height=1000,scrollbars=yes'); return false;"><img alt="Seccion" src="images/magnifier.png" border="0"></a>
							<a style="cursor:pointer;" onclick="document.frm.NombreSeccion.value = '';document.frm.IDSeccion.value = '';">Borrar</a>
                                                        </td>
						</tr>
						<tr>
							<td> Nombre </td>
							<td><input id="param[seccion][Nombre]" type="text" size="25" title="Nombre" name="param[seccion][Nombre]" class="input mandatory" value="<?php echo $frm["Nombre"] ?>" /> </td>
						</tr>
						<tr>
							<td> Descripcion </td>
							<td>
							<textarea rows="5" cols="60" id="param[seccion][Descripcion]" title="Descripcion" name="param[seccion][Descripcion]" class="input"><?php echo $frm["Descripcion"] ?></textarea>
							</td>
						</tr>
						<tr>
							<td> Publicar </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Publicar"] , "param[seccion][Publicar]" , "title=\"Publicar\"" )?> </td>
						</tr>
                        
						                       <tr>
							<td> Ubicacion </td>
							<td>
							<?php 
									$array_seleccion = split(",",$frm["Ubicacion"]);
									echo SIMHTML::formCheckGroup( array_flip( SIMResources::$UbicacionSeccion ) , $array_seleccion , "param[seccion][Ubicacion][]"  )
								?> 
                             </td>
						</tr>
                        
						<tr>
							<td> Orden </td>
							<td><input id="param[seccion][Orden]" type="text" size="25" title="Orden" name="param[seccion][Orden]" class="input mandatory" value="<?php echo $frm["Orden"] ?>" /> </td>
						</tr>
                        <!--
                        <tr>
							<td> Orden Menu Inferior</td>
							<td><input id="param[seccion][OrdenInferior]" type="text" size="25" title="Orden" name="param[seccion][OrdenInferior]" class="input " value="<?php echo $frm["OrdenInferior"] ?>" /> </td>
						</tr>
                        <tr>
							<td> Clase Css </td>
							<td><input id="param[seccion][ClaseCss]" type="text" size="25" title="ClaseCss" name="param[seccion][ClaseCss]" class="input " value="<?php echo $frm["ClaseCss"] ?>" /> </td>
						</tr>
                      	-->
						<tr>
							<td> Imagen Seccion </td>
							<td>
							<?php
							if($frm["SeccionFile"])
							{
								?>
								<img alt="<?php echo $frm["SeccionFile"] ?>" src="<?php echo IMGSECCION_ROOT.$frm["SeccionFile"]?>">
								<a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelImgSec&id=" .$frm[ $key ]?>"><img src='images/trash.png' border='0'></a>
							<?php
							}
							else
							{
							?>
							<input type="file" name="SeccionImagen" id="SeccionImagen" class="popup" title="Seccion Imagen">
							<?php
							}
							?>
							</td>
						</tr>
						<tr>
							<td> URL </td>
							<td><input id="param[seccion][URL]" type="text" size="25" title="URL" name="param[seccion][URL]" class="input" value="<?php echo $frm["URL"] ?>" /> </td>
						</tr>
						<tr>
							<td> SEO_Title </td>
							<td>
							<textarea rows="5" cols="60" id="param[seccion][SEO_Title]" title="SEO_Title" name="param[seccion][SEO_Title]" class="input"><?php echo $frm["SEO_Title"] ?></textarea>
							</td>
						</tr>
						<tr>
							<td> SEO_Description </td>
							<td>
							<textarea rows="5" cols="60" id="param[seccion][SEO_Description]" title="SEO_Description" name="param[seccion][SEO_Description]" class="input"><?php echo $frm["SEO_Description"] ?></textarea>
							</td>
						</tr>
						<tr>
							<td> SEO_KeyWords </td>
							<td>
							<textarea rows="5" cols="60" id="param[seccion][SEO_KeyWords]" title="SEO_KeyWords" name="param[seccion][SEO_KeyWords]" class="input"><?php echo $frm["SEO_KeyWords"] ?></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<input type="submit" name="submit" value="<?php echo $submit_caption ?>" class="submit" />							</td>
						</tr>
					</table>
			  </td>
			</tr>
		</table>
                <input type="hidden" name="ID"  id="ID" value="<?php echo $frm[ $key ] ?>" />
		<input type="hidden" name="action" id="action" value="<?php echo $newmode?>" />
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

	//Funcion Crea Arbol
	function CreaArbolSecciones($ValorSecciones)
	{
		$dbo =& SIMDB::get();
		$Padre=$ValorSecciones['IDSeccion'];
		$RegistrosHijos=$dbo->all("Seccion","IDPadre = '".$Padre."' ");
			while($RHijos=$dbo->fetchArray( $RegistrosHijos ))
					$ArrayHijos[$RHijos['IDSeccion']]=$RHijos;
		?>
		<li>
			<span class="folder"><a href="?mod=Seccion&action=edit&id=<?php echo $ValorSecciones['IDSeccion']?>"><?php echo $ValorSecciones['Nombre'];?></a></span>
		<?php
		if( $ArrayHijos != Null )
		{
			?>
			<ul>
			<?php
			foreach($ArrayHijos as $clave => $valor)
				CreaArbolSecciones($valor);
			?>
			</ul>
			<?php
		}
		?>
		</li>
		<?php
		return true;
	}
	//fin funcion

$dbo =& SIMDB::get();
$key = SIMReg::get( "key" );
$table = SIMReg::get( "table" );

//Secciones Padre
$Secciones=$dbo->all("Seccion","IDPadre = '0' ");

while( $RSeccioones = $dbo->fetchArray( $Secciones ) )
	$ArraySecciones[$RSeccioones[IDSeccion]]=$RSeccioones;
?>
<table class="adminheading">
	<tbody><tr>
		<th>Seleccione la secci&oacute;n deseada haciendo clic.</th>
	</tr>
</tbody></table>
<br>
		<ul id="ArbolSecciones" class="filetree">
			<?php
				foreach($ArraySecciones as $ClaveSeccion => $ValorSecciones)
						CreaArbolSecciones($ValorSecciones)
			?>
		</ul>
<?php
}// Enf function list()
?>