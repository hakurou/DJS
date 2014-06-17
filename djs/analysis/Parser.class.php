<?php

namespace djs\analysis;

/**
 * Analyseur syntaxique
 * @author hakurou
 * @version 1.0.0
 */
class Parser
{
	/**
	 * Instance de l'analyseur lexical
	 * @var Lexer
	 */
    protected $lexer;
	
	/**
	 * Instance du launcher
	 * @var DJS
	 */
	protected $djs;
	/**
	 * Token courant
	 * @var Token
	 */
    protected $currentToken;
	
	/**
	 * Chemin du fichier a parser
	 * @var String
	 */
    protected $filename;
	
	/**
	 * Constructeur
	 * @param DJS $djs						Instance du launcher
	 */
    public function __construct($djs)
    {
    	$this->djs = $djs;
    }
	
	/**
	 * Récupère le nom du fichier a parser
	 * @return String						Nom du fichier
	 */
	public function getFilename()
	{
		return $this->filename;
	}
    
	/**
	 * Parse un fichier de source
	 * @param String $filename					Fichier a traiter
	 */
    public function parseFile($filename)
    {
    	$this->filename = $filename;
		$this->filename = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $this->filename);
        $this->lexer = new Lexer($filename, Lexer::T_FILE);
		$content = "";
		
		while(($expr = $this->parseExpr()) != null)
		{	
	        $content .= $expr->interpret($this);
		}
		
		return $content ;
    }
	
	/**
	 * Récupère le token courant
	 * @return Token							Token courant
	 */
	public function getCurrentToken()
	{
		return $this->currentToken;
	}
	
	/**
	 * récupère le token suivant
	 */
	public function nextToken()
    {
        $this->currentToken = $this->lexer->getNextToken();
    }

	/**
	 * Parse une expression
	 * @return Exp/null							Retourne une expression si trouvée, sinon null
	 */
	public function parseExpr()
	{
		// Parse seulement les éléments relatifs à DJS, le reste est du natif donc on y touche pas
		$this->nextToken();

		if($this->currentToken == null)
			return null;
		
		if($this->currentToken->getType() == Lexer::TT_WORD)
		{
			$word = $this->djs->searchWord($this->currentToken->getValue());
			if($word != null)
				return $word->parse($this);
			else
				return new \djs\expr\DjsWord($this->currentToken);
		}
		else if($this->currentToken->getType() == Lexer::TT_STRING)
			return new \djs\expr\DjsString($this->currentToken);
		else if($this->currentToken->getType() == Lexer::TT_LEFT_BRACE)
		{
			$s = new \djs\expr\DjsBlock(); // Native code fragment
			return $s->parse($this);
		}
		else if($this->currentToken->getType() == Lexer::TT_RIGHT_BRACE)
			return new \djs\expr\RightBrace();
		else
			return new \djs\expr\Native($this->currentToken);
	}
}

?>