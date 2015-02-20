<div id="oferta_descripcion" class="setup-content">



		



		
		
		


	<table class="adminform">
			
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0" border="0" width="100%">	


<tr>
			<td  class="columnafija" > Estado Oferta </td>
				<td>

					<div class="a-select">
						<?php echo SIMHTML::formPopUp( "EstadoOferta" , "Nombre" , "Nombre" , "IDEstadoOferta" , $frm["IDEstadoOferta"] ," 1 " , "[Seleccione el Estado]" , "popup mandatory" , "title = \"estado\"" )?>
					</div>
				</td>
			</tr>
			<tr>
				<td  class="columnafija" > Empresa </td>
				<td>
					<?
						if( !empty( $idempresa ) )
						{
							echo $empresa->Nombre;
						}
						else
						{
							echo "<div class=\"a-select\">";
							echo SIMHTML::formPopUp( "Empresa" , "Nombre" , "Nombre" , "IDEmpresa" , $frm["IDEmpresa"] ," 1 " , "[Seleccione la Empresa]" , "popup mandatory" , "title = \"empresa\"" );
							echo "</div>";
						}//end else
					?>
				</td>
			</tr>
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
			<td  class="columnafija" > Origen </td><td><? 
								$arrayop = array();
								$arrayop = array('Nuevo'=>'Nuevo','Reemplazo'=>'Reemplazo','Otro'=>'Otro'); 
									$selection = $frm["Origen"];
									echo SIMHTML::formradiogroup($arrayop,$selection,"Origen" , "class=' mandatory'");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Home </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Home"] , 'Home' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Posición Home </td><td><input id=PosicionHome type=text size=25  name=PosicionHome class="input mandatory " title="PosicionHome" value="<?=$frm[PosicionHome] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Cargo </td><td><input id=Cargo type=text size=25  name=Cargo class="input mandatory " title="Cargo" value="<?=$frm[Cargo] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Fecha de Cierre </td><td><input id=FechaCierre type=text size=10 readonly name=FechaCierre class="input mandatory  calendar " title="FechaCierre" value="<?=$frm[FechaCierre] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Estado </td><td><? 
								$arrayop = array();
								$arrayop = array('Abierta'=>'Abierta','Cerrada'=>'Cerrada'); 
									$selection = $frm["Estado"];
									echo SIMHTML::formradiogroup($arrayop,$selection,"Estado" , "class=' mandatory'");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Descripcion </td><td><textarea rows="5" id=Descripcion cols=60 wrap=virtual class="input mandatory" title="Descripcion" name=Descripcion><?=$frm[Descripcion]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Sectores </td><td>

				<?
					$arrayop = array();
								$arrayop = SIMReg::get("sectores"); 
								
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm["Sectores"]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"Sectores[]");
				?>

			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Conocimientos </td><td><textarea rows="5" id=Conocimientos cols=60 wrap=virtual class="input mandatory" title="Conocimientos" name=Conocimientos><?=$frm[Conocimientos]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Viaje </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Viaje"] , 'Viaje' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Frecuencia de Viaje </td><td><input id=ViajeFrecuencia type=text size=25  name=ViajeFrecuencia class="input mandatory " title="ViajeFrecuencia" value="<?=$frm[ViajeFrecuencia] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Zona Asignada </td><td><input id=ZonaAsignada type=text size=25  name=ZonaAsignada class="input mandatory " title="ZonaAsignada" value="<?=$frm[ZonaAsignada] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Tipo de Salario </td><td>

				<div class="a-select">
					<?php echo SIMHTML::formPopUp( "TipoSalario" , "Nombre" , "Nombre" , "IDTipoSalario" , $frm["IDTipoSalario"] ," 1 " , "[Seleccione el Tipo de Salario]" , "popup " , "title = \"tipo de salario\"" )?>
				</div>

			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Garantizado </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Garantizado"] , 'Garantizado' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Tiempo Garantizado </td><td><input id=TiempoGarantizado type=text size=25  name=TiempoGarantizado class="input mandatory " title="TiempoGarantizado" value="<?=$frm[TiempoGarantizado] ?>"> meses </td>
			</tr>
			<tr>
			<td  class="columnafija" > Monto Garantizado </td><td><input id=MontoGarantizado type=text size=25  name=MontoGarantizado class="input mandatory " title="MontoGarantizado" value="<?=$frm[MontoGarantizado] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Salarios al Año </td><td><input id=SalariosAnio type=text size=25  name=SalariosAnio class="input mandatory " title="SalariosAnio" value="<?=$frm[SalariosAnio] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Beneficios </td><td><textarea rows="5" id=Beneficios cols=60 wrap=virtual class="input mandatory" title="Beneficios" name=Beneficios><?=$frm[Beneficios]?></textarea></td>
			</tr>
			<tr>
			<td  class="columnafija" > Modalidad de Contrato </td><td><? 
								$arrayop = array();
								$arrayop = array('TerminoFijo'=>'Termino Fijo','Indefinido'=>'Indefinido'); 
								$array_opcion  = array();
									foreach($arrayop AS $opcion)
										$array_opcion[$opcion] = $opcion;
									$selection = split(',',$frm[ModalidadContrato]);
									echo SIMHTML::formcheckgroup($array_opcion,$selection,"ModalidadContrato[]");
								?></td>
			</tr>
			<tr>
			<td  class="columnafija" > Horario </td>
			<td>
				<div class="a-select">
					<?php echo SIMHTML::formPopUp( "Horario" , "Nombre" , "Nombre" , "IDHorario" , $frm["IDHorario"] ," 1 " , "[Seleccione el Horario]" , "popup " , "title = \"tipo de horario\"" )?>
				</div>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Nivel Profesional </td>
			<td>
				<div class="a-select">
					<?php echo SIMHTML::formPopUp( "NivelProfesional" , "Nombre" , "Nombre" , "IDNivelProfesional" , $frm["IDNivelProfesional"] ," 1 " , "[Nivel Profesional]" , "popup " , "title = \"nivel profesional\"" )?>
				</div>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Estado Civil </td>
			<td>
				<? echo SIMHTML::formradiogroup( array_flip( SIMResources::$estadoCivil ) , $frm["EstadoCivil"] , 'EstadoCivil' , "class=' '" ) ?>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Rango de Edad </td>
			<td>
				<div class="a-select">
					<?php echo SIMHTML::formPopUp( "RangoEdad" , "Nombre" , "Nombre" , "IDRangoEdad" , $frm["IDRangoEdad"] ," 1 " , "[Rango de Edad Requerido]" , "popup " , "title = \"rango de edad\"" )?>
				</div>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Género </td><td>

				<? echo SIMHTML::formradiogroup( array_flip( SIMResources::$genero ) , $frm["Genero"] , 'Genero' , "class=' '" ) ?>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Rango de Experiencia </td>
			<td>
				<div class="a-select">
					<?php echo SIMHTML::formPopUp( "RangoExperiencia" , "Nombre" , "Nombre" , "IDRangoExperiencia" , $frm["IDRangoExperiencia"] ," 1 " , "[Rango de Experiencia Requerido]" , "popup " , "title = \"rango de experiencia\"" )?>
				</div>
			</td>
			</tr>
			<tr>
			<td  class="columnafija" > Fecha de Inicio </td><td><input id=FechaInicio type=text size=10 readonly name=FechaInicio class="input mandatory  calendar " title="FechaInicio" value="<?=$frm[FechaInicio] ?>"> </td>
			</tr>
			<tr>
			<td  class="columnafija" > Publicar </td><td><? echo SIMHTML::formradiogroup( array_flip( SIMResources::$sino ) , $frm["Publicar"] , 'Publicar' , "class=' mandatory'" ) ?></td>
			</tr>
			<tr>
			<td colspan=2 align=center>
			
			 <a href="#oferta_academica" class="button btnWizard orange" >Siguiente</a>
			
</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


			
</div>