<?php

namespace djs\expr;

class DjsNamespace extends Exp
{
    protected $content;
	protected $name;
    
    public function __construct($name = null, $value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_NAMESPACE);
        $this->content = $value;
		$this->name = $name;
    }
    
    public function interpret($parser)
    {
    	$content = '';
		$str = '';
		$parentNs = ($this->parentNamespace != null) ? $this->parentNamespace.'.': ''; 
    	if($this->content != null && count($this->content) > 0)
		{
			foreach($this->content as $c)
			{
				$c->setNamespace($parentNs.$this->name);									
				$str .= $c->interpret($parser);
			}
		}
		
		$def = $this->generateNs();
		$declaration = ($this->parentNamespace != null) ? '': 'var ';
		$ns = $parentNs.$this->name;
		
		return '
			'.$def.'
			(function(__namespace__){
				'.$str.'
			})('.$ns.');
		';
    }
	
	public function parse($parser)
	{
		$name = $this->parseWordsSuit($parser);
		
		if($parser->getCurrentToken()->getType() != \djs\analysis\Lexer::TT_LEFT_BRACE)
			trigger_error('DjsNamespace::parse: Unexpected token', E_USER_ERROR);
		
		$exprList = array();

		for( ; ; )
		{
			$e = $this->parseExpr($parser);
			
			if($e != null && $e->getType() != \djs\analysis\Lexer::TT_RIGHT_BRACE)
				$exprList[] = $e;
		
			if($e == null || $e->getType() == \djs\analysis\Lexer::TT_RIGHT_BRACE)
				break;
		}
		return new self($name, $exprList);
	}
	
	protected function parseWordsSuit($parser)
	{
		$s = '';
		
		$parser->nextToken();
		$t = $parser->getCurrentToken();
		
		while($t != null)
		{
			if($t->getType() != Lexer::TT_WORD)
				trigger_error('DjsNamespace::parseWordsSuit: Unexpected token');
			
			$s .= $t->getValue();
			$parser->nextToken();
			$t = $parser->getCurrentToken();
			if($t->getValue() == '.')
			{
				$s .= $t->getValue();
			
				$parser->nextToken();
				$t = $parser->getCurrentToken();
			
				if($t->getType() != \djs\analysis\Lexer::TT_WORD)
					trigger_error('DjsNamespace::parseWordsSuit: Unexpected token', E_USER_ERROR);
			}
			else
				break;
		}
		
		return $s;	
	}
	
	protected function generateNs()
	{
		$p = explode('.', $this->name);
		$def = '';
		if(count($p) > 0)
		{
			$n = '';
			if($this->parentNamespace == null)
				$first = true;
			else
			{
				$first = false;
				$n = $this->parentNamespace;
			}
			
			foreach($p as $sp)
			{
				if($n == '')
					$n = $sp;
				else
					$n .= '.'.$sp;
				
				$def .= '
					if(typeof '.$n.' == "undefined")
						'.(($first) ? 'var ': '').$n.' = {};
				';
				
				$first = false;
			}
		}
		
		return $def;
	}
	
	protected function parseExpr($parser)
	{
		$e = $parser->parseExpr();
		
		if($e->getType() == \djs\analysis\Lexer::TT_WORD_VAR)
		{
			$e = new DjsNsDeclaration();
		}
		else if($e->getType() == \djs\analysis\Lexer::TT_WORD_FUNCTION)
		{
			$parser->nextToken();
			$e = new DjsNsFunctionDeclaration($parser->getCurrentToken()->getValue());
		}
		
		return $e;
	}
}

?>