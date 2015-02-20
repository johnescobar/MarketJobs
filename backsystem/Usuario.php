<?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Usuarios",
					"table" => "Usuario",
					"key" => "IDUsuario",
					"mod" => "Usuario"
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
		//seguridad para cada campo del formulario
		foreach($_POST["param"]["usuario"] as $clave=>$valor)
			$_POST["param"]["usuario"][$clave] = SIMUtil::antiinjection($valor);
		
		/*
		 * Verificamos si el formulario valida.
		 * Si no valida devuelve un mensaje de error.
		 * SIMResources::capture  captura ese mensaje y si el mensaje existe devulve true
		*/
		
		$_POST["param"]["usuario"]["IDPais"] = SIMNet::post( "IDPais" );	
		$_POST["param"]["usuario"]["IDDepartamento"] = SIMNet::post( "IDDepartamento" );
                $_POST["param"]["usuario"]["IDCiudad"] = SIMNet::post( "IDCiudad" );
                $_POST["param"]["usuario"]["IDPerfil"] = SIMNet::post( "IDPerfil" );
                $_POST["param"]["usuario"]["IDEquipo"] = SIMNet::post( "IDEquipo" );


		
		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["usuario"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["usuario"] );

                        
            $frm[Password] = sha1($frm[Password]);
			
			//insertamos los datos del asistente
			$id = $dbo->insert( $frm , $table , $key );

			echo mysql_error();
			exit;
			
			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id ."&m=insertarexito" );
		}
		else
			print_form( $_POST["param"]["usuario"] , "insert" , "Agregar Registro" );
	break;
	
	case "edit":
		//seguridad para cada campo del formulario
		foreach($_GET as $clave=>$valor)
			$_GET[$clave] = SIMUtil::antiinjection($valor);
		$frm = $dbo->fetchById( $table , $key , $_GET["id"] , "array" );		
		print_form( $frm , "update" , "Realizar Cambios" );
	break ;
	
	case "update" :
		//seguridad para cada campo del formulario
		foreach($_POST["param"]["usuario"] as $clave=>$valor)
			$_POST["param"]["usuario"][$clave] = SIMUtil::antiinjection($valor);
		
		$_POST["param"]["usuario"]["IDPais"] = SIMNet::post( "IDPais" );
                $_POST["param"]["usuario"]["IDDepartamento"] = SIMNet::post( "IDDepartamento" );
		$_POST["param"]["usuario"]["IDCiudad"] = SIMNet::post( "IDCiudad" );
		$_POST["param"]["usuario"]["IDPerfil"] = SIMNet::post( "IDPerfil" );
                $_POST["param"]["usuario"]["IDEquipo"] = SIMNet::post( "IDEquipo" );
		
		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["usuario"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["usuario"] );
			$excepcion = array();

            if($frm[Password] != "")
            {
                $frm[Password] = sha1($frm[Password]);
            }
            else
            {
                //añnadir excepcion
                $excepcion = array("Password");
            }
			
			$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") , $excepcion );
			
			$frm = $dbo->fetchById( $table , $key , $id , "array" );
			
			SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );
			
			print_form( $frm , "update" ,  "Realizar Cambios" );
		}
		else
			print_form( $_POST["param"]["usuario"] , "update" ,  "Realizar Cambios" );
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
				
		$sql = " SELECT V.* FROM " . $table . " V " . $params["from"] . $params["where"];
		
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
	if(!$_GET['idlang'])
		$_GET['idlang'] = 1;
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
	<div id="usuario">
		<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">
		<table class="adminform">
			<tr>
				<th>&nbsp;Datos</th>
			</tr>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">	
						
						<tr>
							<td class="columnafija"> Perfil </td>
							<td>
								<div class="a-select">
								<?php echo SIMHTML::formPopUp( "Perfil" , "Nombre" , "Nombre" , "IDPerfil" , $frm["IDPerfil"] , "1" , "[Seleccione Perfil]" , "popup" , "title = \"Perfil\"" )?>
								</div>
							</td>
						</tr>
                       

						 <tr>
							<td> Tipo de Usuario </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$tipo_usuario ) , $frm["TipoUsuario"] , "param[usuario][TipoUsuario]" , "title=\"Tipo de Usuario\"" )?></td>
						</tr>



                                                <tr>
							<td> Numero De Documento </td>
							<td><input id="param[usuario][NumeroDocumento]" type="text" size="25" title="NumeroDocumento" name="param[usuario][NumeroDocumento]" class="input" value="<?php echo $frm["NumeroDocumento"] ?>" /> </td>
						</tr>
                                                <tr>
							<td> Nombre </td>
							<td><input id="param[usuario][Nombre]" type="text" size="25" title="Nombre" name="param[usuario][Nombre]" class="input mandatory" value="<?php echo $frm["Nombre"] ?>" /> </td>
						</tr>
                                               
                                                <?php
                                                if($frm["Fecha"] == "0000-00-00")
                                                    $frm["Fecha"] = "";
                                                ?>

                                                <tr>
							<td> Fecha de Nacimiento </td>
							<td><input id="param[usuario][FechaNacimiento]" type="text" size="10" title="Fecha" name="param[usuario][FechaNacimiento]" class="input mandatory calendar" value="<?php echo $frm["FechaNacimiento"] ?>" readonly="readonly" /> </td>
						</tr>
						<tr>
							<td> Telefono </td>
							<td><input id="param[usuario][Telefono]" type="text" size="25" title="Telefono" name="param[usuario][Telefono]" class="input " value="<?php echo $frm["Telefono"] ?>" /> </td>
						</tr>
						<tr>
							<td> Email </td>
							<td><input id="param[usuario][Email]" type="text" size="25" title="Email" name="param[usuario][Email]" class="input " value="<?php echo $frm["Email"] ?>" /> </td>
						</tr>
                                                
						<tr>
							<td> Password </td>
							<td><input id="param[usuario][Password]" type="text" size="25" title="Password" name="param[usuario][Password]" class="input" value="" /> </td>
						</tr>
						
						<tr>
							<td> Autorizado </td>
							<td><?php echo SIMHTML::formRadioGroup( array_flip( SIMResources::$sino ) , $frm["Autorizado"] , "param[usuario][Autorizado]" , "title=\"Autorizado\"" )?> </td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<a href="#" class="btnEnviar button orange" ><?php echo $submit_caption ?></a>
							</td>
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
	$key = SIMReg::get( "key" );
	$table = SIMReg::get( "table" );
	$dbo =&SIMDB::get();
	
	if( empty( $sql ) )
	 	$sql =  "SELECT * FROM " . $table . " WHERE 1 ORDER BY " . $key;
	 	
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
						<th>Tipo Usuario</th>
						<th>Perfil</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Autorizado</th>
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
						<td><?php echo $r->TipoUsuario?></td>
						<td><?php echo $dbo->getFields( "Perfil", "Nombre", "IDPerfil = '" . $r->IDPerfil . "'") ?></td>
						<td><?php echo $r->Nombre?></td>
						<td><?php echo $r->Email?></td>
						<td><?php echo $r->Autorizado?></td>
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