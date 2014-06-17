<?php

namespace djs\expr;

/**
 * Expression représentant un contenu de méthode de classe
 * @author hakurou
 * @version 1.0.0
 */
class DjsClassMethodContent extends Exp
{
    protected $content;
    protected $className;
    
	/**
	 * Constructeur
	 * @param String $className					Nom de la classe
	 * @param Array<Token>						Liste de token représentant le contenu
	 */
    public function __construct($className = null, $value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_METHOD_CONTENT);
        $this->content = $value;
        $this->className= $className;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	$str = '';
		
		if($this->content != null && count($this->content) > 0)
		{
			foreach($this->content as $c)
				$str .= $c->interpret($parser);
		}
		
		return $str;
    }
	
	/**
	 * @see Exp::parse
	 */
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