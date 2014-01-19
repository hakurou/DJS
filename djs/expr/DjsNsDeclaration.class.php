<?php

namespace djs\expr;

class DjsNsDeclaration extends Exp
{
    public function __construct()
    {
    	parent::__construct(\djs\analysis\Lexer::TT_WORD_VAR);
    }
    
    public function interpret($parser)
    {
		return $this->parentNamespace.'.';
    }
	
	public function parse($parser)
	{
		return null;
	}
}

?>