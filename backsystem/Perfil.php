<?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Perfiles",
					"table" => "Perfil",
					"key" => "IDPerfil",
					"mod" => "Perfil"
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
		foreach($_POST["param"]["perfil"] as $clave=>$valor)
			$_POST["param"]["perfil"][$clave] = SIMUtil::antiinjection($valor);
		
		/*
		 * Verificamos si el formulario valida.
		 * Si no valida devuelve un mensaje de error.
		 * SIMResources::capture  captura ese mensaje y si el mensaje existe devulve true
		*/
		
		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["perfil"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["perfil"] );
			
			//insertamos los datos del asistente

			$id = $dbo->insert( $frm , $table , $key );
			
			SIMHTML::jsRedirect( "?mod=" . $mod . "&action=edit&id=" . $id . "&idlang=".$frm[IDLenguaje]."&m=insertarexito" );
		}
		else
			print_form( $_POST["param"]["perfil"] , "insert" , "Agregar Registro" );
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
		foreach($_POST["param"]["perfil"] as $clave=>$valor)
			$_POST["param"]["perfil"][$clave] = SIMUtil::antiinjection($valor);
		
		if( !SIMNotify::capture( SIMUtil::valida( $_POST["param"]["perfil"] , $array_valida ) , "error" ) )
		{
			//los campos al final de las tablas
			$frm = SIMUtil::varsLOG( $_POST["param"]["perfil"] );
			
			$id = $dbo->update( $frm , $table , $key , SIMNet::reqInt("id") );
			
			$frm = $dbo->fetchById( $table , $key , $id , "array" );
			
			SIMNotify::capture( "Los cambios han sido guardados satisfactoriamente" , "info" );
			
			print_form( $frm , "update" ,  "Realizar Cambios" );
		}
		else
			print_form( $_POST["param"]["perfil"] , "update" ,  "Realizar Cambios" );
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
		
		$dbo->deleteById( $table , $key , $_POST["ID"]);
		
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
	<div id="perfil">
		<form name="frm" id="frm" action="<?php echo SIMUtil::lastURI()?>" method="post" enctype="multipart/form-data" class="formvalida">
		<table class="adminform">
			<tr>
				<th>&nbsp;Datos</th>
			</tr>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">	
						<tr>
							<td> Nombre </td>
							<td><input id="param[perfil][Nombre]" type="text" size="25" title="Nombre" name="param[perfil][Nombre]" class="input mandatory" value="<?php echo $frm["Nombre"] ?>" /> </td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<a href="#" class="btnEnviar button orange"><?php echo $submit_caption ?></a>
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
	 	$sql =  "SELECT * FROM " . $table . " WHERE 1 ORDER BY FechaTrCr DESC";
	 	
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
						<th><a href="javascript:void(0);">Nombre</a></th>
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
						<td align="center" width="64">
							<a href="<? echo "?mod=" . SIMReg::get( "mod" ) . "&amp;action=del&amp;id=" . $r->$key?>"><img src='images/trash.png' border='0'></a>						</td>
					</tr>
	<?php 
		}
	?>
					<tr>
						<th class="texto" colspan="4" ><?php echo $paging->fetchNavegacion()?></th>
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