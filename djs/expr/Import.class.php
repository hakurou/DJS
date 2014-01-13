<?php

namespace djs\expr;

/**
 * @todo Empecher les imports cycliques
 */
class Import extends Exp
{
    protected $content;
    
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_IMPORT);
        $this->content = $value;
    }
    
    public function interpret()
    {
    	$content = "";
		$this->content = str_replace('/', DIRECTORY_SEPARATOR, $this->content);
		
    	if(file_exists($this->content))
		{
			$djs = new \djs\DJS(true);
			$content = $djs->parseFile($this->content);
		}
		
		return $content;
    }
	
	public function parse($parser)
	{
		$parser->nextToken();
		$value = $parser->getCurrentToken();
		$parser->nextToken();
		
		if($value->getType() == \djs\analysis\Lexer::TT_STRING &&
			$parser->getCurrentToken()->getType() == \djs\analysis\Lexer::TT_SEMICOLON)
			return new self($value->getValue());
		else
		{
			trigger_error('Import::parse: Unexpected token, semicolon waited', E_USER_ERROR);
		}
	}
}

?>