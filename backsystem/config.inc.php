<?php

error_reporting( E_ERROR || E_PARSE );

define( "VERSION" , "4.0" );
//Datos de acceso a la BD
define( "DBHOST" , "localhost" );
define( "DBNAME" , "MArketJobs" );
define( "DBUSER" , "root" );
define( "DBPASS" , "" );

//Directorio del administrador del sitio
define( "DIRROOT" , dirname( __FILE__ )."/" );

//Directorio de las librerias
define( "LIBDIR" , DIRROOT . "lib/" );

//Directorio del FCK EDITOR
define( "JSFCKDIR" , DIRROOT . "lib/fckeditor/" );

//Diretorio del sistema
define( "SITEDIR" , dirname( $_SERVER["PHP_SELF"] ) ); 

//Direccion absoluta del sitio
define( "URLROOT" , "http://" . $_SERVER["HTTP_HOST"] ."/MarketJobs/" );


//Titulo de la aplicacion
define( "APP_TITLE" , ":: Market Jobs::" );

//tiempo limite de sesion
define( "SESSION_LIMIT" , 30 );

//Directorios File y Imegenes


//define( "IMGPRODUCTOINT_ROOT" , URLROOT . "file/Producto/" );
//define( "IMGPRODUCTOINT_DIR" , DIRROOT . "../file/Producto/" );


define( "IMGNOTICIA_ROOT" , URLROOT . "file/Noticia/" );
define( "IMGNOTICIA_DIR" , DIRROOT . "../file/Noticia/" );


define( "IMGEMPRESA_ROOT" , URLROOT . "file/Empresa/" );
define( "IMGEMPRESA_DIR" , DIRROOT . "../file/Empresa/" );


define( "IMGSECCION_ROOT" , URLROOT . "file/Seccion/" );
define( "IMGSECCION_DIR" , DIRROOT . "../file/Seccion/" );

define( "IMGBANNER_ROOT" , URLROOT . "file/Banner/" );
define( "IMGBANNER_DIR" , DIRROOT . "../file/Banner/" );

define( "PARAMETRO_ROOT" , URLROOT . "file/Parametro/" );
define( "PARAMETRO_DIR" , DIRROOT . "../file/Parametro/" );


//Librerias y Clases
require( LIBDIR . "SIMDB.inc.php" );
require( LIBDIR . "class.phpmailer.php" );
require( LIBDIR . "SIMSession.inc.php" );
require( LIBDIR . "SIMSessionCliente.inc.php" );
require( LIBDIR . "SIMResources.inc.php" );
require( LIBDIR . "SIMUtil.inc.php" );
require( LIBDIR . "SIMFile.inc.php" );
require( LIBDIR . "SIMLog.inc.php" );
require( LIBDIR . "SIMHTML.inc.php" );
require( LIBDIR . "SIMNet.inc.php" );
require( LIBDIR . "SIMNotify.inc.php" );
require( LIBDIR . "SIMArchivos.php" );
require( LIBDIR . "SIMReg.inc.php" );
require( LIBDIR . "SIMUser.inc.php" );
require( LIBDIR . "PHPPaging.lib.php" );
require( LIBDIR . "buildNav.php" );
require( JSFCKDIR . "fckeditor.php" );
require( LIBDIR . "JSON.php" );  

require( LIBDIR . "shoppingcart.php" ); 


$DB_DEBUG = true;
$DB_DIE_ON_FAIL = true;

$dbo =& SIMDB::get();
$dblink = $dbo->connect( DBHOST , DBNAME, DBUSER , DBPASS );
?>
