<?php

namespace djs\expr;

class DjsSuper extends Exp
{
    protected $args;
	protected $methodName;
	protected $className;
    
    public function __construct($className = null, $methodName = null, $args = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_SUPER);
        $this->args = $args;
        $this->methodName = $methodName;
        $this->className = $className;
    }
    
    public function interpret()
    {
    	$args = '';
		
		if(count($this->args) > 0)
		{
			$args .= ', ';
			foreach($this->args as $arg)
				$args .= $arg->interpret();
		}
		
    	$mName = ($this->methodName == 'construct') ? 'constructor': $this->methodName;
    	return '
    		'.$this->className.'.__super__.'.$mName.'.call(this '.$args.');
    	';
    }
	
	public function parse($parser)
	{
		$args = array();
		$parser->nextToken();
		if($parser->getCurrentToken()->getValue() != '.')
			trigger_error('DjsSuper::parse: Unexpected token, dot waited', E_USER_ERROR);
			
		$parser->nextToken();
		$methodName = $parser->getCurrentToken();
		if($methodName->getType() != \djs\analysis\Lexer::TT_WORD)
			trigger_error('DjsSuper::parse: Unexpected token, method name waited', E_USER_ERROR);
		
		$parser->nextToken();
		if($parser->getCurrentToken()->getValue() != '(')
			trigger_error('DjsSuper::parse: Unexpected token, left parenthesis waited', E_USER_ERROR);
	
		$openBrace = 1;
		$parser->nextToken();
		$t = $parser->getCurrentToken();
		// Récupération des arguments sans évaluation, on réinjecte le tout dans l'interprete
		while($t != null && $t->getValue() != ')')
		{
			if($t != null && $t->getValue() == '{')
				$openBrace++;
			else if($t != null && $t->getValue() == '}')
				$openBrace--;
			
			if($t != null)
				$args[] = new Native($t);
			
			$parser->nextToken();
			$t = $parser->getCurrentToken();
		}
	
		if($parser->getCurrentToken()->getValue() != ')')
			trigger_error('DjsSuper::parse: Unexpected token, right parenthesis waited', E_USER_ERROR);
	
		$parser->nextToken();
		if($parser->getCurrentToken()->getValue() != ';')
			trigger_error('DjsSuper::parse: Unexpected token, semicolon waited', E_USER_ERROR);
	
		return new self($this->className, $methodName->getValue(), $args);	
	}
}

?>