<?php
class SIMFile
{	
	static $SEPARADOR = "_";
	
	function write( $name , $content )
	{
		if( !is_writable( $name ) ) return false;
		
		if( file_exists( $name ) )
			$filep = @fopen( $name , 'a' );
		else
			$filep = @fopen( $name , 'w' );
			
		if( @fwrite( $filep , $content ) )
			return true;
		else
			return false;
		
		fclose( $filep );
	}
	
	function delete( $name )
	{
		return unlink( $name );
	}
	
	function getFileData( $archivo )
	{
		return pathinfo( $archivo );
	}
	
	function getExtension( $archivo )
	{
		$pathinfo = pathinfo( $archivo );
	
		return $pathinfo["extension"];
	}

	function getName( $archivo )
	{
		$pathinfo = pathinfo( $archivo );
	
		return $pathinfo["basename"];
	}
	
	function getPathName( $archivo )
	{
		$pathinfo = pathinfo( $archivo );
	
		return $pathinfo["dirname"];
	}



	function getSize( $file )
	{
	
		$size = filesize( $file );
	
		$sizes = Array(' Bytes', ' Kbs', ' Mbs', 'Gbs', 'Tbs', 'Pbs', 'Ebs');
	
		$ext = $sizes[0];
	
		for ( $i = 1 ; ($i < count( $sizes ) && $size >= 1024 ) ; $i++ )
		{
			$size = $size / 1024;
			$ext  = $sizes[ $i ];
		}
	
		clearstatcache();
	
		return round( $size , 2 ) . $ext;
	}
	
	
	
	function isMIMEValid( $mimetype )
	{
		return in_array( $mimetype , SIMResources::$mimeValidos );
	}
	
	
	
	function isMIMEImageValid( $mimetype )
	{
		return in_array( $mimetype , SIMResources::$mimeImagenValidos );
	}
	
	
	
	function isMIMEDocValid( $mimetype )
	{
		return in_array( $mimetype , SIMResources::$mimeDocsValidos );
	}
	
	
	
	function isMIMEVideoValid( $mimetype )
	{
		return in_array( $mimetype , SIMResources::$mimeVideoValidos );
	}
	
	
	
	function isMIMEGraphValid( $mimetype )
	{
		return in_array( $mimetype , SIMResources::$mimeGraficoValidos );
	}
	
	function makeSure( $filename )
	{
		return preg_replace( "/([^a-z0-9\.])/i" , "_" , $filename );
	}
	
	function upload( $files_req , $destination , $validation = "ALL" )
	{
		//datos del archivo a devolver
		$file_data = false;
		
		//flag de validacion
		$ismimevalid = false;
		
		//url temporal de destino para usar en el bucle
		$tmp_dest = "";
		
		if( !isset( $files_req[0] ) )
			$files[0] =  $files_req;
		else
			$files = $files_req;
			
		foreach( $files as $nombre => $archivo )
		{
			switch( $validation )
			{
				case "IMAGE":
					$ismimevalid = self::isMIMEImageValid( $archivo['type'] );
				break;
				case "DOC":
					$ismimevalid = self::isMIMEDocValid( $archivo['type'] );
				break;
				case "VIDEO":
					$ismimevalid = self::isMIMEVideoValid( $archivo['type']);
				break;
				case "GRAPH":
					$ismimevalid = self::isMIMEGraphValid( $archivo['type'] );
				break;			
				default:
					$ismimevalid = self::isMIMEValid( $archivo['type'] );
				break;
			}
			
			
			if( !$archivo['error'] && $ismimevalid )
			{			
				$safename = self::makeSure( $archivo['name'] );
				$innername = self::makeInner($safename);

				if( move_uploaded_file( $archivo['tmp_name'] , $destination . "/" . $innername ) )
				{
					if( !is_array( $file_data ) ) $file_data = array();
										
					$file_data[] = array( "name" => $safename, "innername" => $innername , "origname" => $archivo["name"] , "size" => $archivo["size"] , "type" => $archivo["type"] );
				}
			}
		}	
		
			
		return $file_data;
	}
	function makeInner( $filename )
	{
		$startwith = (string)rand(1001,9999);		
		return $startwith . SIMFile::$SEPARADOR . $filename;
	}

	function download( $file , $filename )
	{
		// BEGIN extra headers to resolve IE caching bug (JRP 9 Feb 2003)
		// [http://bugs.php.net/bug.php?id=16173]
		header("Pragma: ");
		header("Cache-Control: ");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		
		//	header("Cache-Control: no-store, no-cache, must-revalidate");  
		//HTTP/1.1
		//	header("Cache-Control: post-check=0, pre-check=0", false);
		// END extra headers to resolve IE caching bug
		
		header("Content-Length: ".filesize($filename)); 
	    header("Content-Type: $file->FileType");
	 	header("Content-Disposition: attachment; filename={$file->File}"); 
	
		readfile( $filename );
		
		return true;
	}
	
	function makeDir( $dir_name )
	{
		if( !mkdir( $dir_name , 0755 ) )
			return false;
		else
			chmod($dir_name,0757);
		
		return true;
	}
	
	
	function listDir( $dirname )
	{ 
		if( $dirname[ strlen( $dirname ) - 1 ] != "/" )
			$dirname.="/";
		
		$result_array = array();
		
		$mode = fileperms($dirname);
		
		if( ( $mode & 0x4000 ) == 0x4000 && ( $mode & 0x00004 ) == 0x00004)
		{ 
			chdir( $dirname ); 
			$handle = @opendir( $dirname) ;
		}
		
		if( isset( $handle ) )
		{
			while ( $file = readdir( $handle ) )
			{
				if( $file == '.' || $file == '..' ) 
					continue; 
				
				if( is_file( $dirname . $file ) ) 
					$result_array[] = $file;
			} 
			
			closedir( $handle );
		} 
		return $result_array;
	}
}
?>