<?php

namespace djs\expr;

/**
 * Expression représentant un mot
 * @author hakurou
 * @version 1.0.0
 */
class DjsWord extends Exp
{
    protected $content;
    
	/**
	 * Constructeur
	 * @param Token $value					Token représentant un mot
	 */
    public function __construct($value = null)
    {
    	if($value->getValue() == 'super')
    		parent::__construct(\djs\analysis\Lexer::TT_WORD_SUPER); // Trouver autre chose comme façon de faire
		else if($value->getValue() == 'var')
    		parent::__construct(\djs\analysis\Lexer::TT_WORD_VAR); 
		else if($value->getValue() == 'function')
    		parent::__construct(\djs\analysis\Lexer::TT_WORD_FUNCTION); 
		else
    		parent::__construct(\djs\analysis\Lexer::TT_WORD);
		
        $this->content = $value;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	// Mots necessitants forcément un espace a la suite
    	// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Reserved_Words
    	$c = $this->content->getValue();
    	switch($c)
		{
			case "delete":
			case "function":
			case "if":
			case "new":
			case "return":
			case "typeof":
			case "var":
				return $c.' ';
			case "in":
			case "else":
			case "instanceof":
				return ' '.$c.' ';
			default:
				return $c;
		}
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