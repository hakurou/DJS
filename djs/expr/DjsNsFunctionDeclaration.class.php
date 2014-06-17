<?php

namespace djs\expr;

/**
 * Expression représentant une déclaration de fonction, permet en fait d'appliquer un namespace sur une déclaration
 * @author hakurou
 * @version 1.0.0
 */
class DjsNsFunctionDeclaration extends Exp
{
	protected $name;
	
	/**
	 * Constructeur
	 * @param String $name					Nom de la fonction
	 */
    public function __construct($name = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_WORD_FUNCTION);
		$this->name = $name;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
		return $this->parentNamespace.'.'.$this->name.' = function';
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