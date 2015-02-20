<?
    class buildNav // [Class : Controls all Functions for Prev/Next Nav Generation]
    {
        var $limit, $execute, $query;
       
	    function execute($query) // [Function : mySQL Query Execution]
        {

            !isset($_GET[ $this->offset ]) ? $GLOBALS[$this->offset] = 0 : $GLOBALS[$this->offset] = $_GET[$this->offset];
			
			$dbo =& SIMDB::get();
			
            $this->sql_result = $dbo->query($query);

            $this->total_result = $dbo->rows($this->sql_result);

            if(isset($this->limit))
            {
                $query .= " LIMIT " . $GLOBALS[$this->offset] . ", $this->limit";
                $this->sql_result = $dbo->query($query);
                $this->num_pages = ceil($this->total_result/$this->limit);
                $this->rows = $dbo->rows($this->sql_result);
            }
        }
		function show_num_pages_front($frew = '', $rew = '', $ffwd = '', $fwd = '', $separator = '|', $objClass = '') // [Function : Generates Prev/Next Links]
        {
            $current_pg = $GLOBALS[$this->offset]/$this->limit+1;
			if ($current_pg > 5)
            {
                $fgp = $current_pg - 5 > 0 ? $current_pg - 5 : 1;
                $egp = $current_pg+4;

                if ($egp > $this->num_pages)
                {
                    $egp = $this->num_pages;
                    $fgp = $this->num_pages - 9 > 0 ? $this->num_pages  - 9 : 1;
                }
            }

            else {
                $fgp = 1;
                $egp = $this->num_pages >= 10 ? 10 : $this->num_pages;
            }

            if($this->num_pages > 1) {
                // searching for http_get_vars
				foreach ($_GET as $_get_name => $_get_value) {
						
					
                    if ($_get_name != $this->offset) {
                        $this->_get_vars .= "&$_get_name=$_get_value";
                    }
                }

                $this->successivo = $GLOBALS[$this->offset] + $this->limit;
                $this->precedente = $GLOBALS[$this->offset] - $this->limit;
                $this->theClass = $objClass;

                if (!empty($rew)) {
                    $return .= ($GLOBALS[$this->offset] > 0) ? "<a href=\"?$this->offset=$this->precedente$this->_get_vars\" $this->theClass>$rew</a> " : "<span class='navvar'>$rew</span> ";
                }

                // showing pages

            if ($this->show_pages_number || !isset($this->show_pages_number))
                {
                    for($this->a = $fgp; $this->a <= $egp; $this->a++)
                    {
                        $this->theNext = ($this->a-1)*$this->limit;
                        $_ss_k = floor($this->theNext/26);

                        if ($this->theNext != $GLOBALS[$this->offset])
                        {
                            $return .= " <a href=\"?$this->offset=$this->theNext$this->_get_vars\" $this->theClass> ";

                            
                            $return .= $this->a;
                            
                            $return .= "</a> ";
                            
                            
                        } else {
                            
                            $return .= "<span class='current10'>".$this->a."</span>";
                            
                            $return .= ($this->a < $this->num_pages) ? "  " : " ";
                        }
                    }
                    $this->theNext = $GLOBALS[$this->offset] + $this->limit;

                    if (!empty($fwd)) {

                        $offset_end = ($this->num_pages-1)*$this->limit;

                        $return .= ($GLOBALS[$this->offset] + $this->limit < $this->total_result) ? "<a href=\"?$this->offset=$this->successivo$this->_get_vars\" $this->theClass>$fwd</a> " : "<span class='navvar'>$fwd</span>";
                    }
                }
            }
            return $return;
        }
        function show_num_pages($frew = '', $rew = '', $ffwd = '', $fwd = '', $separator = '|', $objClass = '') // [Function : Generates Prev/Next Links]
        {
            $current_pg = $GLOBALS[$this->offset]/$this->limit+1;
			if ($current_pg > 5)
            {
                $fgp = $current_pg - 5 > 0 ? $current_pg - 5 : 1;
                $egp = $current_pg+4;

                if ($egp > $this->num_pages)
                {
                    $egp = $this->num_pages;
                    $fgp = $this->num_pages - 9 > 0 ? $this->num_pages  - 9 : 1;
                }
            }

            else {
                $fgp = 1;
                $egp = $this->num_pages >= 10 ? 10 : $this->num_pages;
            }

            if($this->num_pages > 1) {
                // searching for http_get_vars
				foreach ($_GET as $_get_name => $_get_value) {
						
					
                    if ($_get_name != $this->offset) {
                        $this->_get_vars .= "&$_get_name=$_get_value";
                    }
                }

                $this->successivo = $GLOBALS[$this->offset] + $this->limit;
                $this->precedente = $GLOBALS[$this->offset] - $this->limit;
                $this->theClass = $objClass;

                if (!empty($rew)) {
                    $return .= ($GLOBALS[$this->offset] > 0) ? "[<a href=\"?$this->offset=0$this->_get_vars\" $this->theClass>$frew</a>] <a href=\"?$this->offset=$this->precedente$this->_get_vars\" $this->theClass>$rew</a> $separator " : "[$frew] $rew $separator ";
                }

                // showing pages

            if ($this->show_pages_number || !isset($this->show_pages_number))
                {
                    for($this->a = $fgp; $this->a <= $egp; $this->a++)
                    {
                        $this->theNext = ($this->a-1)*$this->limit;
                        $_ss_k = floor($this->theNext/26);

                        if ($this->theNext != $GLOBALS[$this->offset])
                        {
                            $return .= " <a href=\"?$this->offset=$this->theNext$this->_get_vars\" $this->theClass> ";

                            
                            $return .= $this->a;
                            
                            $return .= "</a> ";
                            
                            
                        } else {
                            
                            $return .= "<span $this->theClass>".$this->a."</span>";
                            
                            $return .= ($this->a < $this->num_pages) ? " $separator " : " ";
                        }
                    }
                    $this->theNext = $GLOBALS[$this->offset] + $this->limit;

                    if (!empty($fwd)) {

                        $offset_end = ($this->num_pages-1)*$this->limit;

                        $return .= ($GLOBALS[$this->offset] + $this->limit < $this->total_result) ? "$separator <a href=\"?$this->offset=$this->successivo$this->_get_vars\" $this->theClass>$fwd</a> [<a href=\"?$this->offset=$offset_end$this->_get_vars\" $this->theClass>$ffwd</a>]" : "$separator $fwd [$ffwd]";
                    }
                }
            }
            return $return;
        }

        function show_info() // [Function : Showing the Information for the Offset]
        {
           if($GLOBALS[$this->offset] >= $this->total_result || $GLOBALS[$this->offset] < 0) 
           		return false;
			
			$return .= " Se encontraron ".$this->total_result . " Resultado(s) ";

            $_from = $GLOBALS[$this->offset] + 1;

            $GLOBALS[$this->offset] + $this->limit >= $this->total_result ? $_to = $this->total_result : $_to = $GLOBALS[$this->offset] + $this->limit;

            $return .= "Mostrando del  " . $_from . " al " . $_to . "<br>";

            return $return;

        }
    }

?>