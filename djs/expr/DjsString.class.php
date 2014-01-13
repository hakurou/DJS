<?php

namespace djs\expr;

class DjsString extends Exp
{
    protected $content;
    
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_STRING);
        $this->content = $value;
    }
    
    public function interpret()
    {
    	$c = $this->content->getValue();
    	$c = '"'.$c.'"';
		if(preg_match_all('#\$\{([^}]+)\}#', $c, $occ, PREG_SET_ORDER))
		{
			foreach($occ as $subOcc)
				$c = str_replace($subOcc[0], '" + '.$subOcc[1].' + "', $c);
		}
		
    	return $c;
    }
	
	public function parse($parser)
	{
		return null;
	}
}

?>