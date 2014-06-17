<?php

namespace djs\expr;

/**
 * Expression représentant une déclaration de variable, permet en fait d'appliquer un namespace sur une déclaration
 * @author hakurou
 * @version 1.0.0
 */
class DjsNsDeclaration extends Exp
{
	/**
	 * Constructeur
	 */
    public function __construct()
    {
    	parent::__construct(\djs\analysis\Lexer::TT_WORD_VAR);
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
		return $this->parentNamespace.'.';
    }
	
	/**
	 * @see Exp::parse
	 */
	public function parse($parser)
	{
		return null;
	}
}

?>