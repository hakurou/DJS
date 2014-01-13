<?php

namespace djs\expr;

class DjsBlock extends Exp
{
    protected $content;
    
    public function __construct($exprs = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_SCOPE);
        $this->content = $exprs;
    }
    
    public function interpret()
    {
    	$str = '';
		$str .= '{'; // Réintégration de la premiere accolade
		if($this->content != null && count($this->content) > 0)
		{
			foreach($this->content as $c)
				$str .= $c->interpret();	
		}
		$str .= '}'; // Réintégration de la derniere accolade
		return $str;
    }
	
	public function parse($parser)
	{
		$exprs = array();
		
		for( ; ; )
		{
			$e = $parser->parseExpr();
		
			if($e != null && $e->getType() != \djs\analysis\Lexer::TT_RIGHT_BRACE)
				$exprs[] = $e;
		
			if($e == null || $e->getType() == \djs\analysis\Lexer::TT_RIGHT_BRACE)
				break;
		}

		return new self($exprs);
	}
}

?>