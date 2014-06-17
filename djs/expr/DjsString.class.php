<?php

namespace djs\expr;

/**
 * Expression représentant une chaine de caractères 
 * @author hakurou
 * @version 1.0.0
 */
class DjsString extends Exp
{
    protected $content;
    
	/**
	 * Constructeur
	 * @param Token $value					Token représentant la chaine 
	 */
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_STRING);
        $this->content = $value;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	$c = $this->content->getValue();
    	$c = '"'.$c.'"';
		if(preg_match_all('#\$\{([^}]+)\}#', $c, $occ, PREG_SET_ORDER))
		{
			foreach($occ as $subOcc)
				$c = str_replace($subOcc[0], '" + '.$subOcc[1].' + "', $c);
		}
		
    	return $c;
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