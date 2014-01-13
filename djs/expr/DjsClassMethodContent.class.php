<?php

namespace djs\expr;

class DjsClassMethodContent extends Exp
{
    protected $content;
    protected $className;
    
    public function __construct($className = null, $value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_METHOD_CONTENT);
        $this->content = $value;
        $this->className= $className;
    }
    
    public function interpret()
    {
    	$str = '';
		
		if($this->content != null && count($this->content) > 0)
		{
			foreach($this->content as $c)
				$str .= $c->interpret();
		}
		
		return $str;
    }
	
	public function parse($parser)
	{
		$exprs = array();
		
		for( ; ; )
		{
			$e = $parser->parseExpr();
		
			if($e->getType() == \djs\analysis\Lexer::TT_WORD_SUPER)
			{
				$s = new DjsSuper($this->className);
				$e = $s->parse($parser);
			}
			
			if($e != null && $e->getType() != \djs\analysis\Lexer::TT_RIGHT_BRACE)
				$exprs[] = $e;
		
			if($e == null || $e->getType() == \djs\analysis\Lexer::TT_RIGHT_BRACE)
				break;
		}

		return new self($this->className, $exprs);
	}
}

?>