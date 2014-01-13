<?php

namespace djs\expr;

class RightBrace extends Exp
{

    public function __construct()
    {
    	parent::__construct(\djs\analysis\Lexer::TT_RIGHT_BRACE);
    }
    
    public function interpret()
    {
    	return '}';
    }
	
	public function parse($parser)
	{
		return null;
	}
}

?>