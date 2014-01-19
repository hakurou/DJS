<?php

namespace djs\expr;

class Native extends Exp
{
    protected $content;
    
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_NATIVE);
        $this->content = $value;
    }
    
    public function interpret($parser)
    {
    	return $this->content->getValue();
    }
	
	public function parse($parser)
	{
		
	}
}

?>