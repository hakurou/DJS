<?php

namespace djs\analysis;

class Parser
{
    protected $lexer;
	protected $djs;
    protected $currentToken;
	
    public function __construct($djs)
    {
    	$this->djs = $djs;
    }
    
    public function parseFile($filename)
    {
        $this->lexer = new Lexer($filename, Lexer::T_FILE);
		$content = "";
		
		while(($expr = $this->parseExpr()) != null)
		{	
	        $content .= $expr->interpret();
		}
		
		return $content ;
    }
	
	public function getCurrentToken()
	{
		return $this->currentToken;
	}
	
	public function nextToken()
    {
        $this->currentToken = $this->lexer->getNextToken();
    }

	public function parseExpr()
	{
		// Parse seulement les éléments relatifs à DJS, le reste est du natif donc on y touche pas
		$this->nextToken();

		if($this->currentToken == null)
			return null;
		
		if($this->currentToken->getType() == Lexer::TT_WORD)
		{
			$word = $this->djs->searchWord($this->currentToken->getValue());
			if($word != null)
				return $word->parse($this);
			else
				return new \djs\expr\DjsWord($this->currentToken);
		}
		else if($this->currentToken->getType() == Lexer::TT_STRING)
			return new \djs\expr\DjsString($this->currentToken);
		else if($this->currentToken->getType() == Lexer::TT_LEFT_BRACE)
		{
			$s = new \djs\expr\DjsBlock(); // Native code fragment
			return $s->parse($this);
		}
		else if($this->currentToken->getType() == Lexer::TT_RIGHT_BRACE)
			return new \djs\expr\RightBrace();
		else
			return new \djs\expr\Native($this->currentToken);
	}
	
	public function parseWordsSuit()
	{
		$s = '';
		
		$this->nextToken();
		$t = $this->currentToken;
		
		while($t != null)
		{
			if($t->getType() != Lexer::TT_WORD)
				trigger_error('Parse::parseSuitString: Unexpected token');
			
			$s .= $t->getValue();
			$this->nextToken();
			$t = $this->getCurrentToken();
			if($t->getValue() == '.')
			{
				$s .= $t->getValue();
			
				$this->nextToken();
				$t = $this->getCurrentToken();
			
				if($t->getType() != \djs\analysis\Lexer::TT_WORD)
					trigger_error('DjsClass::parse: Unexpected token', E_USER_ERROR);
			}
			else
				break;
		}
		
		return $s;	
	}
}

?>