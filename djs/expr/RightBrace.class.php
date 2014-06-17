<?php

namespace djs\expr;

/**
 * Expression représentant une accolade droite utilise pour la transpilation
 * @author hakurou
 * @version 1.0.0
 */
class RightBrace extends Exp
{
	/**
	 * Constructeur
	 */
    public function __construct()
    {
    	parent::__construct(\djs\analysis\Lexer::TT_RIGHT_BRACE);
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	return '}';
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