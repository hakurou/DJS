<?php

namespace djs\expr;

class DjsNsFunctionDeclaration extends Exp
{
	protected $name;
	
    public function __construct($name = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_WORD_FUNCTION);
		$this->name = $name;
    }
    
    public function interpret($parser)
    {
		return $this->parentNamespace.'.'.$this->name.' = function';
    }
	
	public function parse($parser)
	{
		return null;
	}
}

?>