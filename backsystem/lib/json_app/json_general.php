<?php
include("../../config.inc.php");

//json general para contenido de app mobile

$json = new Services_JSON;

$arrayGeneral         = array();
$arrayRecetaDestacada = array();
$arrayTipo            = array();
$arrayTipoReceta      = array();
$arrayRecetaTipo      = array();
$arrayMarcas          = array();
$arrayCategoria       = array();
$arrayCategoria1      = array();
$arrayCategoria2      = array();

//Datos Recetas
$SqlTipo = "SELECT * FROM REC_Tipo WHERE Publicar = 'S'";
$QryTipo = $dbo->query( $SqlTipo );
$NumTipo = $dbo->rows( $QryTipo );

if( $NumTipo > 0 )
{
	while( $Tipo = $dbo->fetchArray( $QryTipo ) )	
	{		
		//obtener secciones
		$SqlTipoReceta = "SELECT * FROM REC_TipoReceta WHERE IDTipo = '" . $Tipo["IDTipo"] . "'";
		$QryTipoReceta = $dbo->query( $SqlTipoReceta );
		$NumTipoReceta = $dbo->rows( $QryTipoReceta );
		if( $NumTipoReceta > 0 )
		{
			$i = 0;
			while( $TipoReceta = $dbo->fetchArray( $QryTipoReceta ) )	
			{
				$arrayTipoReceta[$i] = $TipoReceta;
				$i++;
					
			}
			
			foreach( $arrayTipoReceta as $key => $value )
			{
				$SqlReceta = "SELECT * FROM Receta WHERE IDReceta = '" . $value["IDReceta"] . "' AND Publicar = 'S'";
				$QryReceta = $dbo->query( $SqlReceta );
				$NumReceta = $dbo->rows( $QryReceta );
				if( $NumReceta > 0 )
				{
					$Receta = $dbo->fetchArray( $QryReceta );
					$Receta["Foto"] = IMGRECETA_ROOT . $Receta["Foto"];
				}

				$arrayReceta[$Receta["IDReceta"]] = $Receta;
				$Tipo["Receta"][$Receta["IDReceta"]] = $arrayReceta[$Receta["IDReceta"]];
			}
		}
		else
		{
			$arrayRecetaTipo = NULL;
		}
				
		$arrayRecetaTipo[$Tipo["IDTipo"]] = $Tipo;
		
	}	
}
else
{
	$arrayTipoSeccion = NULL;
	
}


//datos Home
$SqlFotoHome = "SELECT * FROM Banner WHERE Publicar = 'S' AND Ubicacion = 'Home' ORDER BY FechaTrCr DESC LIMIT 1";
$QryFotoHome = $dbo->query( $SqlFotoHome );
$NumFotoHome = $dbo->rows( $QryFotoHome );
if( $NumFotoHome > 0 )
{
	$FotoHome = $dbo->fetchArray( $QryFotoHome );
	$FotoHome["BannerFile"] = IMGBANNER_ROOT . $FotoHome["BannerFile"]; 
}

//Recetas Destacadas
$SqlRecetaDestacada = "SELECT * FROM Receta WHERE Publicar = 'S' AND Home = 'S' ORDER BY FechaTrCr DESC LIMIT 2";
$QryRecetaDestacada = $dbo->query( $SqlRecetaDestacada );
$NumRecetaDestacada = $dbo->rows( $QryRecetaDestacada );
if( $NumRecetaDestacada > 0 )
{
	while( $RecetaDestacada = $dbo->fetchArray( $QryRecetaDestacada ) )
	{
		$RecetaDestacada["Foto"] = IMGRECETA_ROOT . $RecetaDestacada["Foto"]; 
		$arrayRecetaDestacada[$RecetaDestacada["IDReceta"]] = $RecetaDestacada; 
	}
}

//marcas
$SqlMarcas = "SELECT * FROM Producto_Categoria WHERE IDPadre = '0' AND Publicar = 'S'";
$QryMarcas = $dbo->query( $SqlMarcas );
$NumMarcas = $dbo->rows( $QryMarcas );
while( $Marcas = $dbo->fetchArray( $QryMarcas ) )
{
	$arrayMarcas[$Marcas["IDCategoria"]] = $Marcas;
	
	//tipos de producto por marca
	$SqlTipoCategoria = "SELECT * FROM PRODUCTO_Categoria WHERE IDPadre = '" . $Marcas["IDCategoria"] . "' AND Publicar = 'S'";
	$QryTipoCategoria = $dbo->query( $SqlTipoCategoria );
	$NumTipoCategoria = $dbo->rows( $QryTipoCategoria );
	if( $NumTipoCategoria > 0 )
	{
		while( $TipoCategoria = $dbo->fetchArray( $QryTipoCategoria ) )
		{
			
			$arrayCategoria["IDCategoria"] = $TipoCategoria;
			
			//subcategoria
			/*$SqlSubCategoria = "SELECT * FROM PRODUCTO_Categoria WHERE IDPadre = '" . $TipoCategoria["IDCategoria"] . "' AND Publicar = 'S' LIMIT 10";
			$QrySubCategoria = $dbo->query( $SqlSubCategoria );
			$NumSubCategoria = $dbo->rows( $QrySubCategoria );
			if( $NumSubCategoria > 0 )
			{
				
				while( $SubCategoria = $dbo->fetchArray( $QrySubCategoria ) )
				{
					
					//subcategoria1
					$SqlSubCategoria1 = "SELECT * FROM PRODUCTO_Categoria WHERE IDPadre = '" . $SubCategoria["IDCategoria"] . "' AND Publicar = 'S' LIMIT 10";
					$QrySubCategoria1 = $dbo->query( $SqlSubCategoria1 );
					$NumSubCategoria1 = $dbo->rows( $QrySubCategoria1 );
					if( $NumSubCategoria1 > 0 )
					{
						
						while( $SubCategoria1 = $dbo->fetchArray( $QrySubCategoria1 ) )
						{
							
						}
						
					}
					else
					{
						
					}					
				}				
			}
			else
			{
				
			}*/					
		}
		
	}
}

$arrayTipo = $arrayRecetaTipo;
$arrayGeneral["Home"] = $FotoHome;
$arrayGeneral["RecetaDestacada"] = $arrayRecetaDestacada;
$arrayGeneral["TipoReceta"] = $arrayTipo;
$arrayGeneral["Marcas"] = $arrayMarcas;

//visualizamos el array/json
echo "<pre>";
print_r( $arrayGeneral );
echo "</pre>";
echo "<hr />";
//echo $json->encode( $arrayRecetaTipo );

?>