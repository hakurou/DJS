<?php

namespace djs\expr;

/**
 * Expression représentant un contenu entouré d'accolade, permet d'éviter le mélange 
 * entre accolades DJS et accolades javascript
 * @author hakurou
 * @version 1.0.0
 */
class DjsBlock extends Exp
{
    protected $content;
    
	/**
	 * Constructeur
	 * @param Array<Token> $exprs					Liste de tokens
	 */
    public function __construct($exprs = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_SCOPE);
        $this->content = $exprs;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	$str = '';
		$str .= '{'; // Réintégration de la premiere accolade
		if($this->content != null && count($this->content) > 0)
		{
			foreach($this->content as $c)
				$str .= $c->interpret($parser);	
		}
		$str .= '}'; // Réintégration de la derniere accolade
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
		
			if($e != null && $e->getType() != \djs\analysis\Lexer::TT_RIGHT_BRACE)
				$exprs[] = $e;
		
			if($e == null || $e->getType() == \djs\analysis\Lexer::TT_RIGHT_BRACE)
				break;
		}

		return new self($exprs);
	}
}

?>