<?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Galeria",
					"table" => "Galeria",
					"key" => "IDGaleria",
					"mod" => "Galeria"
) );

//Para validar los campos del formulario
$array_valida = array(
	"Nombre" => "Nombre"
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
                $_POST["param"]["galeria"]["IDNoticia"] = SIMNet::post( "IDNoticia" );

                //seguridad para cada campo del formulario
		foreach($_POST["param"]["galeria"] as $clave=>$valor)
			$_POST["param"]["galeria"][$clave] = SIMUtil::antiinjection($valor);

		/*
		 * Verificamos si el formulario valida.
		 * Si no valida devuelve un mensaje de error.
		 * SIMResources::capture  captura ese mensaje y si el mensaje existe devulve true
		*/

		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["galeria"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["galeria"] );

                        $files =  SIMFile::upload( $_FILES["Foto"] , PRODUCTOS_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
				SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

			$frm["Foto"] = $files[0]["name"];
                        
			//insertamos los datos del asistente
			$id = $dbo->insert( $frm , $table , $key );

			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id ."&m=insertarexito" );
		}
		else
			print_form( $_POST["param"]["galeria"] , "insert" , "Agregar Registro" );
	break;

	case "edit":
		//seguridad para cada campo del formulario
		foreach($_GET as $clave=>$valor)
			$_GET[$clave] = SIMUtil::antiinjection($valor);
		$frm = $dbo->fetchById( $table , $key , $_GET["id"] , "array" );
		print_form( $frm , "update" , "Realizar Cambios" );

	break ;

	case "update" :
                $_POST["param"]["galeria"]["IDNoticia"] = SIMNet::post( "IDNoticia" );
                
                if($_FILES["Foto"])
                    $_POST["param"]["galeria"]["Foto"] = $_FILES["Foto"]["name"];

                //seguridad para cada campo del formulario
		foreach($_POST["param"]["galeria"] as $clave=>$valor)
			$_POST["param"]["galeria"][$clave] = SIMUtil::antiinjection($valor);

		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["galeria"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["galeria"] );

			$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );

                        $files =  SIMFile::upload( $_FILES["Foto"] , PRODUCTOS_DIR , "IMAGE" );
			if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
				SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );
			
			$frm = $dbo->fetchById( $table , $key , $id , "array" );

			SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );

			print_form( $frm , "update" ,  "Realizar Cambios" );
		}
		else
			print_form( $_POST["param"]["galeria"] , "update" ,  "Realizar Cambios" );
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

		SIMHTML::jsRedirect( "?mod=" . $mod . "&amp;m=eliminarrexito" );
	break;

	case "list" :
		$where_array = array();
		$fieldInt = array();
		$fieldStr = array ( "Nombre" );

		$fromjoin = $fieldInt;

		$wherejoin = $fieldInt;

		$params = SIMUtil::filter( $fieldInt , $fieldStr , $fromjoin , $where_array , $wherejoin );

		if($_GET[Nombre])
                    $sql = " SELECT V.* FROM " . $table . " V " . $params["from"] . $params["where"] . " ";
                else
                    $sql = " SELECT V.* FROM " . $table . " V " . $params["from"] . " ";

		list_r( $sql );
	break;

        case "DelImgNot":
		$doceliminar = PRODUCTOS_DIR.$dbo->getFields( "Galeria" , "Foto" , "IDGaleria = '" . $_GET[id] . "' " );
		unlink($doceliminar);
		$dbo->query("UPDATE Galeria SET Foto = '' WHERE IDGaleria = $_GET[id] LIMIT 1 ;");
		SIMHTML::jsAlert("Imagen Eliminada Correctamente");
		SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=".$id );
		exit;
	break;

        case "InsertarGaleria":
                $frm = SIMUtil::varsLOG( $_POST );
                $files =  SIMFile::upload( $_FILES["Foto"] , PRODUCTOS_DIR , "IMAGE" );
                if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
                        SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

                $frm["Foto"] = $files[0]["name"];
                $id = $dbo->insert( $frm , "FotoGaleria" , "IDFoto" );
                SIMHTML::jsAlert("Registro Exitoso");
                SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $frm[IDGaleria] ."#ImagenesGaleria" );
                exit;
	break;

        case "DelImgGal":
		$doceliminar = PRODUCTOS_DIR.$dbo->getFields( "FotoGaleria" , "Foto" , "IDFoto  = '" . $_GET[idg] . "' " );
		unlink($doceliminar);
		$dbo->query("UPDATE FotoGaleria SET Foto = '' WHERE IDFoto = $_GET[idg] LIMIT 1 ;");
		SIMHTML::jsAlert("Imagen Eliminada Correctamente");
		SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=".$id."&IDFoto=".$_GET[idg]."#ImagenesGaleria" );
		exit;
	break;


        case "ModificaGaleria":

                $_POST["Foto"] = $_FILES["Foto"]["name"];
                $frm = SIMUtil::varsLOG( $_POST );
                $dbo->update( $frm , "FotoGaleria" , "IDFoto" , $frm[IDFoto] );

                $files =  SIMFile::upload( $_FILES["Foto"] , PRODUCTOS_DIR , "IMAGE" );
                if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
                        SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );
                SIMHTML::jsAlert("Modificacion Exitoso");
                SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $frm[IDGaleria] ."#ImagenesGaleria" );
                exit;
        break;

        case "EliminaGaleria":
			$id = $dbo->query( "DELETE FROM FotoGaleria WHERE IDFoto  = '".$_GET[IDFoto]."' LIMIT 1" );
			SIMHTML::jsAlert("Eliminacion Exitoso");
			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $_GET[id] . "#ImagenesGaleria" );
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
include( "includes/tabs.html" );
?>
<div id="tabsform">
        <ul>
		<li>
			<a href="#GaleriaActual" title="galerias"><span>Galeria</span></a>
		</li>
                <li>
			<a href="#ImagenesGaleria" title="imagenes galeria"><span>Imagenes Galeria</span></a>
		</li>
	</ul>

	<div id="GaleriaActual">
		<form name="frm" id="frm" action="<?php echo $PHP_SELF?>" method="post" class="formvalida" enctype="multipart/form-data">
		<table class="adminform">
			<tr>
				<th>&nbsp;Datos</th>
			</tr>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
							<td class="columnafija"> Noticia </td>
							<td><?php echo SIMHTML::formPopUp( "Noticia" , "Titular" , "FechaInicio DESC" , "IDNoticia" , $frm["IDNoticia"] , 1 , "[Seleccione La Noticia]" , "popup" , "title = \"Noticia\"" )?> </td>
						</tr>
                                                <tr>
							<td> Nombre </td>
							<td><input id="param[galeria][Nombre]" type="text" size="25" title="Nombre" name="param[galeria][Nombre]" class="input mandatory" value="<?php echo $frm["Nombre"] ?>" /> </td>
						</tr>
                                                <tr>
							<td> Descripcion </td>
							<td><textarea rows="5" cols="50" id="param[galeria][Descripcion]" name="param[galeria][Descripcion]" class="input mandatory" title="Descripcion" ><?php echo $frm["Descripcion"] ?></textarea></td>
						</tr>
                                                <tr>
							<td> Fecha </td>
							<td><input id="param[galeria][Fecha]" type="text" size="10" title="Fecha" name="param[galeria][Fecha]" class="input mandatory calendar" value="<?php echo $frm["Fecha"] ?>" readonly="readonly" /> </td>
						</tr>
                                                <tr>
							<td> Imagen (189px x 146px)</td>
							<td>
							<?php
							if($frm["Foto"])
							{
								?>
								<img alt="<?php echo $frm["Foto"] ?>" src="<?php echo PRODUCTOS_ROOT.$frm["Foto"]?>">
								<a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelImgNot&id=" .$frm[ $key ]?>"><img src='images/trash.png' border='0'></a>
							<?php
							}
							else
							{
							?>
							<input type="file" name="Foto" id="Foto" class="popup" title="Foto">
							<?php
							}
							?>
							</td>
						</tr>
                                                <tr>
							<td> Visitas </td>
							<td><input id="param[galeria][Visitas]" type="text" size="25" title="Visitas" name="param[galeria][Visitas]" class="input mandatory" value="<?php echo $frm["Visitas"] ?>" /> </td>
						</tr>
                                                <tr>
							<td> Publicar </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Publicar"] , "param[galeria][Publicar]" , "title=\"Publicar\"" )?> </td>
						</tr>
                        
                        <tr>
							<td> Home </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Home"] , "param[galeria][Home]" , "title=\"Home\"" )?> </td>
						</tr>
                       
                         <tr>
							<td> Sociales </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Sociales"] , "param[galeria][Sociales]" , "title=\"Sociales\"" )?> </td>
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
        <div id="ImagenesGaleria">
            <form name="frmpro" id="frmpro" action="?mod=<?php echo SIMReg::get( "mod" )?>" method="post" class="formvalida" enctype="multipart/form-data">
                <?php
                $action = "InsertarGaleria";

                if( $_GET[IDFoto] )
                {
                        $EditGaleria =$dbo->fetchAll("FotoGaleria"," IDFoto = '".$_GET[IDFoto]."' ","array");
                        $action = "ModificaGaleria";
                        ?>
                        <input type="hidden" name="IDFoto" id="IDFoto" value="<?php echo $EditGaleria[IDFoto]?>" />
                        <?php
                }
                ?>
                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminform">
                <tr>
                        <th colspan="2">Imagenes Galeria</th>
                </tr>
                <tr>
                        <td> Nombre </td>
                        <td><input id="Nombre" type="text" size="25" title="Nombre" name="Nombre" class="input mandatory" value="<?php echo $EditGaleria["Nombre"] ?>" /> </td>
                </tr>
                <tr>
                        <td class="columnafija">Descripcion</td>
                        <td>
                                <textarea rows="5" cols="50" title="Descripcion" name="Descripcion" class="input"><?php echo $EditGaleria[Descripcion];?></textarea>
                        </td>
                </tr>
                <tr>
                        <td> Imagen </td>
                        <td>
                        <?php
                        if($EditGaleria["Foto"])
                        {
                                ?>
                                <img alt="<?php echo $EditGaleria["Foto"] ?>" src="<?php echo PRODUCTOS_ROOT.$EditGaleria["Foto"]?>">
                                <a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&action=DelImgGal&id=".$frm[ $key ]."&idg=" .$EditGaleria["IDGaleria"]?>"><img src='images/trash.png' border='0'></a>
                        <?php
                        }
                        else
                        {
                        ?>
                        <input type="file" name="Foto" id="Foto" class="popup" title="Imagen">
                        <?php
                        }
                        ?>
                        </td>
                </tr>
                <tr>
                        <td align="center"><input type="submit" class="submit" value="Enviar"> </td>
                </tr>
                </table>
                <input type="hidden" name="IDGaleria" id="IDGaleria" value="<?php echo $frm[ $key ]?>" />
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
                                <th>Descripcion</th>
                                <th>Imagen</th>
                                <th align="center" valign="middle" width="64">Eliminar</th>
                        </tr>
                        <tbody id="listacontactosanunciante">
                        <?php

                                $r_galeria =& $dbo->all( "FotoGaleria" , "IDGaleria = '" . $frm[$key] . "' " );

                                while( $r = $dbo->object( $r_galeria ) )
                                {
                        ?>

                        <tr class="<?php echo SIMUtil::repetition() ? 'row0' : 'row1'?>">
                                <td align="center" width="64">
                                        <a href="<?php echo "?mod=" . SIMReg::get( "mod" ) . "&action=edit&id=" . $_GET[id] ."&IDFoto=".$r->IDFoto."#ImagenesGaleria"?>"><img src='images/edit.png' border='0'></a>
                                </td>
                                <td><? echo $r->Nombre ?></td>
                                <td><? echo $r->Descripcion ?></td>
                                <td><img alt="<?php echo $r->Foto ?>" src="<?php echo PRODUCTOS_ROOT.$r->Foto?>" width="50px"></td>
                                <td align="center" width="64">
                                        <a href="?mod=<?php echo SIMReg::get( "mod" )?>&action=EliminaGaleria&id=<?php echo $frm[ $key ];?>&IDFoto=<? echo $r->IDFoto ?>"><img src='images/trash.png' border='0' /></a>
                                </td>
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
        <?php
		if( $newmode!== "update" )
		{
                    echo "<script type=\"text/javascript\">" .
					"jQuery( function(){ jQuery('#tabsform').disableTab( 2 ); });" .
				"</script>";
		}
	?>


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
	 	$sql =  "SELECT * FROM " . $table . " ORDER BY FechaTrCr DESC ";

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
				<table class="adminlist" id="orderTable" >
                	<thead>
					<tr>
						<th align="center" valign="middle" width="64">Editar</th>
						<th>Nombre</th>
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
						<td align="center" width="64">
							<a href="<?php echo "?mod=" . SIMReg::get( "mod" ) . "&amp;action=edit&amp;id=" . $r->$key?>"><img src='images/edit.png' border='0'></a>						</td>
						<td><?php echo $r->Nombre?></td>
						<td><?php echo $r->Publicar?></td>
						<td align="center" width="64">
							<a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&amp;action=del&amp;id=" . $r->$key?>"><img src='images/trash.png' border='0'></a>						</td>
					</tr>
	<?php
		}
	?>
					<tr>
						<th class="texto" colspan="7" ><?php echo $paging->fetchNavegacion()?></th>
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
<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="get">
<input type="hidden" name="mod" id="mod" value="<?php echo SIMReg::get( "mod" )?>" />
<input type="hidden" name="action" id="action" value="list" />
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
</form>
<?
	}//End function filtrar
?>