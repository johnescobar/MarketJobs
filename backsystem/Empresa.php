 <?

SIMReg::setFromStructure( array(
					"title" => "Empresa",
					"table" => "Empresa",
					"key" => "IDEmpresa",
					"mod" => "Empresa"
) );


//para validar los campos del formulario
$array_valida = array(  
	 "IDPais" => "IDPais" , "IDDepartamento" => "IDDepartamento" , "IDCiudad" => "IDCiudad" , "IDArea" => "IDArea" , "IDSector" => "IDSector"  , 
	 "NumeroDocumento" => "NumeroDocumento" , "Nombre" => "Nombre"  ,  "Direccion" => "Direccion"  	
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

					$files =  SIMFile::upload( $_FILES["Foto"] , IMGEMPRESA_DIR , "IMAGE" );
					if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
						SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

					$frm["Foto"] = $files[0]["innername"];

					
					
					//insertamos los datos
					$id = $dbo->insert( $frm , $table , $key );


					SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&m=insertarexito" );
				}
				else
					print_form( $_POST , "insert" , "Agregar Registro" );
			break;

			case "insertcontacto" :	
				

					//los campos al final de las tablas
					$frm = SIMUtil::varsLOG( $_POST["param"]["contacto"] );
					
					$frm["IDPais"] = $_POST["IDPais"];
					$frm["IDDepartamento"] = $_POST["IDDepartamento"];
					$frm["IDCiudad"] = $_POST["IDCiudad"];
					$frm["IDEmpresa"] = $_POST["IDEmpresa"];
					
					//insertamos los datos
					$id = $dbo->insert( $frm , "EmpresaContacto" , "IDContacto" );

					//crear usuario
					$usuario = array();
					$usuario["Perfil"] = 0;
					$usuario["TipoUsuario"] = "Empresa";
					$usuario["IDEmpresa"] = $frm["IDEmpresa"];
					$usuario["IDContacto"] = $id;
					$usuario["Nombre"] = $frm["Nombre"] . " " . $frm["Apellido"];
					$usuario["Email"] = $frm["Email"];
					$usuario["Password"] = sha1( $frm["Password"] );
					$usuario["Autorizado"] = "S";
					$usuario["Nivel"] = 1;
					$usuario["FechaTrCr"] = $frm["FechaTrCr"];
					$usuario["UsuarioTrCr"] = $frm["UsuarioTrCr"];

					$idusuario = $dbo->insert( $usuario , "Usuario" , "IDUsuario" );


					SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $usuario["IDEmpresa"] . "&m=insertarexito#tabContactos" );
				
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

					$files =  SIMFile::upload( $_FILES["Foto"] , IMGEMPRESA_DIR , "IMAGE" );
					if( empty( $files ) && !empty( $_FILES["Foto"]["name"] ) )
						SIMNotify::capture( "Ha ocurrido un error durante la carga de la imagen. Verifique que la imagen no contenga errores y que el tipo de archivo sea permitido." , "error" );

					$frm["Foto"] = $files[0]["innername"];

					$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );
					
					$frm = $dbo->fetchById( $table , $key , $id , "array" );
					
					SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );
					
					print_form( $frm , "update" ,  "Realizar Cambios" );
				}
				else
					print_form( $_POST , "update" ,  "Realizar Cambios" );	
			break;


			case "updatecontacto" :

				//los campos al final de las tablas
				$frm = SIMUtil::varsLOG( $_POST["param"]["contacto"] );
				
				$frm["IDPais"] = $_POST["IDPais"];
				$frm["IDDepartamento"] = $_POST["IDDepartamento"];
				$frm["IDCiudad"] = $_POST["IDCiudad"];
				$frm["IDEmpresa"] = $_POST["IDEmpresa"];
				$frm["IDContacto"] = $_POST["IDContacto"];

				$exceptions = array();

				$idcontacto = $dbo->update( $frm , "EmpresaContacto" , "IDContacto" , $frm["IDContacto"]  , $exceptions , "AND IDEmpresa = '" . $frm["IDEmpresa"] . "' " );

				//actualizar el password del usuario
				if( !empty( $frm["Password"] ) )
				{

					//traer usuario
					$password = sha1( $frm["Password"] );
					//actualizar password
					$sql_update = "UPDATE Usuario SET Password = '" . $password . "' Autorizado = '" . $frm["Autorizado"] . "' WHERE IDContacto = '" . $frm["IDContacto"] . "' LIMIT 1 ";
					$qry_update = $dbo->query( $sql_update );

				}//end if

				//Autorizar o desautorizar usuario
				$sql_update = "UPDATE Usuario SET  Autorizado = '" . $frm["Autorizado"] . "' WHERE IDContacto = '" . $frm["IDContacto"] . "' LIMIT 1 ";
				$qry_update = $dbo->query( $sql_update );


				SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $frm["IDEmpresa"] . "&m=guardarexito#tabContactos" );

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
	$mod =  SIMReg::get( "mod" );


	if( $newmode == "insert" )
	{
?>
		<script>
			$( document ).ready(function(){
				$( "#tabsform" ).tabs({
				  disabled: [ 1, 2 ]
				});
			});

		</script>
<?
	}//end if

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
	<ul>
		<li>
			<a href="#tabEmpresa" title="Edite los datos b&aacute;sicos de la empresa"><span>Empresa</span></a>
		</li>
		<li>
			<a href="#tabContactos" title="Ver contactos de la empresa"><span>Contactos</span></a>
		</li>
		<li>
			<a href="#tabOfertas" title="Ver ofertas de la empresa"><span>Ofertas</span></a>
		</li>
    </ul>
	<div id="tabEmpresa">
		<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">
			<table class="adminform">
				<tr>
					<th>&nbsp;Datos</th>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0" border="0" width="100%">	


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
								<td  class="columnafija" > Área </td><td>
									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Area" , "Nombre" , "Nombre" , "IDArea" , $frm["IDArea"] ," 1 " , "[Seleccione la Área]" , "popup" , "title = \"area\"" )?>
									</div>

								</td>
							</tr>
							<tr>
								<td  class="columnafija" > Sector </td><td>
									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Sector" , "Nombre" , "Nombre" , "IDSector" , $frm["IDSector"] ," 1 " , "[Seleccione el Sector]" , "popup" , "title = \"sector\"" )?>
									</div>

								</td>
							</tr>
							<tr>
								<td  class="columnafija" > Comercial Asignado </td><td>
									<div class="a-select">
										<?php echo SIMHTML::formPopUp( "Usuario" , "Nombre" , "Nombre" , "IDUsuario" , $frm["IDUsuario"] ," IDPerfil = '1' " , "[Seleccione el Comercial]" , "popup" , "title = \"comercial\"" )?>
									</div>
								</td>
								</tr>
								<tr>
								<td  class="columnafija" > Número de Documento </td><td><input id=NumeroDocumento type=text size=25  name=NumeroDocumento class="input mandatory " title="NumeroDocumento" value="<?=$frm[NumeroDocumento] ?>"> </td>
								</tr>
								<tr>
								<td  class="columnafija" > Nombre </td><td><input id=Nombre type=text size=25  name=Nombre class="input mandatory " title="Nombre" value="<?=$frm[Nombre] ?>"> </td>
								</tr>
								
								<tr>
								<td  class="columnafija" > Teléfono </td><td><input id=Telefono type=text size=25  name=Telefono class="input mandatory " title="Telefono" value="<?=$frm[Telefono] ?>"> </td>
								</tr>
								<tr>
								<td  class="columnafija" > Dirección </td><td><input id=Direccion type=text size=25  name=Direccion class="input mandatory " title="Direccion" value="<?=$frm[Direccion] ?>"> </td>
								</tr>
								<tr>
								<td  class="columnafija" > Numero de Empleados </td><td><input id=NumeroEmpleados type=text size=25  name=NumeroEmpleados class="input mandatory onlynumber" title="NumeroEmpleados" value="<?=$frm[NumeroEmpleados] ?>"> </td>
								</tr>
								<tr>
								<td  class="columnafija" >
							<? if (!empty($frm[Foto])) {
							echo "<img src='" . IMGEMPRESA_ROOT . "/$frm[Foto]' width=55 height=66>";
								?>
								<a href="<? echo "?mod=$MOD&action=delfoto&foto=$frm[Foto]&campo=Foto&id=".$frm[$Key]; ?>">
								<img src='images/trash.gif' border='0'>
								</a>
								<?
								}// END if
								?>
						 Foto </td><td><input name="Foto" id="file" class="mandatory input" title="Foto" type="file" ></td>
								</tr>
								<tr>
								<td  class="columnafija" > Objetivo </td><td><textarea rows="5" id=Objetivo cols=60 wrap=virtual class="input mandatory" title="Objetivo" name=Objetivo><?=$frm[Objetivo]?></textarea></td>
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

	<?
		include("includes/empresa/contactos.inc.php");
		include("includes/empresa/ofertas.inc.php");
	?>

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

		$array_areas = SIMReg::get("areas");
		$array_sectores = SIMReg::get("sectores");
		$array_usuarios = SIMReg::get("usuarios");

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
					<td class=texto colspan=17  ><?php echo $result["info"]?></td>
					
				</tr>
<tr>
<th align=center valign=middle width=64>Editar</th>
<th>
					País
				</th>
				<th>
					Numero de Documento&nbsp;
				</th>
				<th>
					Nombre&nbsp;
				</th>
				
				<th>
					Área
				</th>
				<th>
					Sector&nbsp;
				</th>
				<th>
					Comercial
				</th>
				
				
				<th>
					Telefono&nbsp;
				</th>
				
					
<th align=center valign=middle width=64>Eliminar</th>
</tr>

<? 
$dbo =&SIMDB::get();
while( $r = $dbo->object( $result["result"] ) )
{
?>
  	
<tr class=<? echo SIMUtil::repetition()?'row0':'row1'; ?>>
<td align=center ><a href='<?php echo "?mod=" . $mod . "&amp;action=edit&amp;id=" . $r->$key?>'><img src='images/edit.png' border='0'></a></td>
<td nowrap><? echo $dbo->getFields( "Pais" , "Nombre" , "IDPais = '" . $r->IDPais . "'" ) ?></td> <td nowrap><? echo $r->NumeroDocumento ?></td><td nowrap><? echo $r->Nombre ?></td><td nowrap><? echo $array_areas[ $r->IDArea ] ?></td> <td nowrap><? echo $array_sectores[ $r->IDSector ] ?></td> <td nowrap><? echo $array_usuarios[ $r->IDUsuario ]["Nombre"] ?></td>  <td nowrap><? echo $r->Telefono ?></td>  
<td align=center ><a href='<? echo "?mod=" . $mod . "&amp;action=del&amp;id=" . $r->$key?>'><img src='images/trash.png' border='0'></a></td></tr>
<? } // END for
?>
<tr>
					<th class=texto colspan=17 nowrap  ><?php echo $result["pages"]?></th>
					
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

