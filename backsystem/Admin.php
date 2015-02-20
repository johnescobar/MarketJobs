<?php
//Encapsulando datos globales
SIMReg::setFromStructure( array(
					"title" => "Administraci&oacute;n del Sistema",
					"mod" => "Admin"
) );
?>
<table class="adminheading">
	<tr>
		<th>
            <?php echo SIMReg::get( "title" )?>
        </th>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table class=adminlist width=100% >
				<tr>
					<th class=title>
						Seguridad
					</th>
				</tr>
                <tr>
					<td>
						<table class="tableadmin"  cellspacing="10">
                            <tr>
                            	<td>
                                	<img src="images/user.png" border="0" />
                                    <p><a href="?mod=Usuario">Usuarios</a></p>
                                </td>
								<td>
									<img src="images/addusers.png" border="0" />
                                    <p><a href="?mod=Usuario&amp;action=add">Agregar Usuarios</a></p>
                                </td>
                               
                                <td>
                                	<img src="images/addusers.png" border="0" />
                                    <p><a href="?mod=Perfil">Perfiles de Usuarios</a></p>
                                	
                                </td>
                                 <td>
                                	<img src="images/addusers.png" border="0" />
                                    <p><a href="?mod=Modulo">Módulos del Sistema</a></p>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                            		<img src="images/addusers.png" border="0" />
                                    <p><a href="?mod=Aseguridad">Asignar Seguridad</a></p>
                                </td>
								<td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </table>
					</td>
				</tr>
			</table>
            
            <br>
            
			<table class=adminlist width=100% >
				<tr>
					<th class=title>
						Valores del Sistema
					</th>
				</tr>
                <tr>
					<td>
						<table class="tableadmin"  cellspacing="10">
                            <tr>
                            	<td>
                            		 <img src="images/browser.png" border="0" />
                                	<p><a href="?mod=Parametro">Parametros Del Sistema</a></p>
                                </td>
                                <td>
                            		<img src="images/browser.png" border="0" />
                                    <p><a href="?mod=Pais">Paises</a></p>                                
                                </td>
                                <td>
                            		<img src="images/browser.png" border="0" />
                                    <p><a href="?mod=Departamento">Departamentos</a></p>
                                </td>
                                <td>
                                	<img src="images/browser.png" border="0" />
                                     <p><a href="?mod=Ciudad">Ciudad</a></p>             
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                      <img src="images/browser.png" border="0" />
                                     <p><a href="?mod=Caracteristica">Características Productos</a></p> 
                            	</td>
                                <td>
                                     
                                    
	                            </td>
                                <td>
                                     
                                </td>
                                <td>
                                
                                </td>
                            </tr>
                           
                        </table>
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>