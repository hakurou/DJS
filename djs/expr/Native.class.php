<?php

namespace djs\expr;

/**
 * Expression représentant un block de code javascript
 * @author hakurou
 * @version 1.0.0
 */
class Native extends Exp
{
    protected $content;
    
	/**
	 * Constructeur
	 * @param $value
	 */
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_NATIVE);
        $this->content = $value;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	return $this->content->getValue();
    }
	
	/**
	 * @see Exp::parse
	 */
	public function parse($parser)
	{
		
	}
}

?>