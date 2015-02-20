<?php

class SIMDB
{
	static $instance = null;
	static $conexion = null;
	
	
	function &get()
	{
		if( !self::$instance )
		{
			self::$instance = new SIMDB();
			return self::$instance;
		}
		else
			return self::$instance;
	}
	
	function getConection()
	{
		return self::$conexion;
	}
	
	function connect( $dbhost , $dbname , $dbuser , $dbpass )
	{	
		if( $this == self::$instance )
		{
			self::$conexion = mysql_connect( $dbhost , $dbuser , $dbpass) or 
			die( "<h2>No se puede conectar a " . $dbhost . " como " . $dbuser . "</h2><p><b>MySQL Error</b>: " . mysql_error() );
		
			mysql_select_db( $dbname ) or die( "<h2>No se puede seleccionar la BD: " . $dbname . "</h2><p><b>MySQL Error</b>: " . mysql_error() );
			
			return self::$conexion;
		}
		else
			return false;
	}
	
	function close()
	{
		mysql_close( self::$conexion );
		return true;
	}
	
	function query( $query , $debug = false , $die = false )
	{	
		if( $this == self::$instance )
		{
			if ( $debug )
			{
				echo "<pre>" . htmlspecialchars($query) . "</pre>";
				if ( $die ) die;
			}
		
			$qid = mysql_query( $query , self::$conexion );
		
			if ( $debug && !$qid )
				echo "<h2>Ha ocurrido un error</h2><p><b>MySQL Error</b>: " . mysql_error();
		
			return $qid;
		}
		else
			return false;
	}
	
	function fetchArray( $qid ) 
	{
		return mysql_fetch_array( $qid );
	}
	
	function assoc( $qid ) 
	{
		return mysql_fetch_assoc( $qid );
	}
	
	function row( $qid )
	{
		return mysql_fetch_row($qid);
	}
	
	function object( $qid )
	{	
		return mysql_fetch_object( $qid );
	}
	
	function rows( $qid )
	{	
		return mysql_num_rows( $qid );
	}
	
	function affected()
	{
		return mysql_affected_rows();
	}
	
	function lastID() 
	{	
		return mysql_insert_id( self::$conexion );
	}
	
	function free( $qid ) 
	{	
		mysql_free_result( $qid );
		return true;
	}
	
	function fields( $qid ) 
	{
		return mysql_num_fields( $qid );
	}
	
	function fieldName( $qid , $fieldno )
	{	
		return mysql_field_name( $qid , $fieldno );
	}
	
	function &all( $table , $condition = 0 )
	{
		$sql = "SELECT * FROM " . $table;
		
		if( $condition )
			$sql .= " WHERE " . $condition;			
		
		return $this->query( $sql );
	}
	
	function fetchById( $table , $key , $id , $type )
	{
                return $this->fetchAll( $table ,  $key . "=" . $id , $type );
	}
	
	function fetchAll( $table , $condition = 0 , $type = "array" )
	{
		$res =& $this->all( $table , $condition );
		return $this->fetch( $res , $type );
	}
	
	function fetch( $resource , $type = "array" )
	{
		$resultado = array();
		
		if( gettype( $resource ) == "string" )
			$resource = $this->query( $resource );
			
		if( !$this->rows( $resource ) )	return false;
		
		if( $type == "array" )
		{
			if( $this->rows( $resource ) > 1 )
			{
				while( $r = $this->assoc( $resource ) )
					$resultado[] = $r;
			}
			else
				$resultado = $this->assoc( $resource );
		}
		else
		{
			if( $this->rows( $resource ) > 1 )
			{
				while( $r = $this->object( $resource ) )
					$resultado[] = $r;
			}
			else
			{
				$resultado = new stdClass;
				$resultado = $this->object( $resource );
			}
		}
		
		$this->free( $resource );
		
		return $resultado;	
	}
	
	function insert( $frm , $table , $key , $idval = 0)
	{
		$values = "";
		$fields = $this->fieldsOf( $table );
		$str = "INSERT INTO " . $table . " ( ";
	
		foreach( $fields as $row )
		{
	    	if( $idval == 1 )
	    	{
    		if( !empty( $frm[ $row[ "Field" ] ] ) )
	    			$field[] = $row[ "Field" ] ;
	    	}
			else
			{
			if( !empty( $frm[ $row[ "Field" ] ] ) )
	    		if( $row[ "Field" ] <> $key )
					$field[] = $row[ "Field" ] ;
			}
	    }
		
		$fields = implode( "," , $field ) ;
			
		$str.= $fields . " ) ";
		
		$str.= " VALUES ( ";
		$sep = "";
		
		for($i = 0 ; $i < ( count($field ) ); $i++)
		{
			$values .= $sep . "'" . $frm[ $field[$i] ] . "'";
			$sep = ",";
		}

		$str.= $values . " ) ";
		
		if( $this->query( $str ) )
			return $this->lastID();
		else
			return false;
	}
	
	function update( $frm , $table , $key , $id , $exceptions = array() , $condicion = "" )
	{
		
		$array_field = array();
		$value_array = array();
		
		$fields = $this->fieldsOf( $table );
	
		foreach( $fields as $row )
		{
			if( !in_array( $row["Field"] , $exceptions ) && isset( $frm[ $row["Field"] ] ) )
				$array_field[] = $row["Field"];
			else
			{
				if( !empty( $frm[ $row["Field"] ] ) )
					$array_field[] = $row["Field"];
			}
		}
		
		$str = "UPDATE " . $table . " SET ";
		
		foreach( $array_field as $field )
		{			 	
		 	if( $field <> $key )
				array_push( $value_array , $field . " = '" . $frm[ $field ] . "' " );
		}
		
		 $str .= implode( " , " , $value_array ) . " WHERE " . $key . " = '" . $id . "' ".$condicion;
		
		
		 if( $results = $this->query( $str ) )
		 {
		 	$this->free( $results );
		 	return $id;
		 }
		 else
		 	return false;		
	}
	
	function fieldsOf( $table )
	{
		$fields = array();
		$resultfields = $this->query( "SHOW FIELDS FROM " . $table );
		while( $row = $this->assoc( $resultfields ) )
			$fields[] = $row;
	
		$this->free( $resultfields );
	
		return $fields;
	}
	
	function deleteById( $table , $key , $id )
	{
		$qry = $this->query( "SELECT " . $key . " FROM " . $table . " WHERE " . $key . " = '" . $id . "'" );
		
		if( $this->rows( $qry ) )
		{
			
			$qry_delete = $this->query( "DELETE FROM " . $table . " WHERE " . $key . " = '" . $id . "' " );
			
			return true;
		}
		else
			return false;		
	}
	
	function delete( $table , $condicion = 1 )
	{
		if( $this->query( "DELETE FROM " . $table . " WHERE " . $condicion ) )
			return true;
		else 
			return false;
	}
	
	function getFields( $table , $fields , $condicion = 1 )
	{
		if( is_array( $fields ) )
			$fieldstr = implode( "," , $fields );
		else
			$fieldstr = $fields;
		
		$qry = $this->query( " SELECT " . $fieldstr . " FROM " . $table . " WHERE " . $condicion );
		
		if( $this->rows( $qry ) )
		{
			$r = $this->assoc( $qry );			
			if( is_array( $fields ) )
				return $r;
			else
				return $r[ $fields ];
		}
		else
			return false;
	}
}
?>