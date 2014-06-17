<?php

namespace djs\expr;

/**
 * Expression représentant un import de fichier 
 * @author hakurou
 * @version 1.0.0
 * @todo Empecher les imports cycliques
 */
class Import extends Exp
{
    protected $content;
	protected static $includedFiles;
    
	/**
	 * Constructeur
	 * @param String $value					Chemin du fichier a importer
	 */
    public function __construct($value = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_IMPORT);
        $this->content = $value;
		if(self::$includedFiles == null)
			$includedFiles = array();
    }
	
	/**
	 * Vide la liste des fichiers inclut pour le parsing du projet courant
	 */
	public static function clearIncludedFiles()
	{
		self::$includedFiles = array();
	}
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
		
    	$content = "";
		$this->content = str_replace('/', DIRECTORY_SEPARATOR, $this->content);
		
		$path = $parser->getFilename();
		$path = substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR) + 1);
		$path .= $this->content; 
		
    	if(file_exists($path))
		{
			if(in_array($path, self::$includedFiles))
				return '';
			
			$djs = new \djs\DJS(true);
			$content = $djs->parseFile($path);
			
			self::$includedFiles[] = $path;
		}
		else
		{
			$content .= 'console.log("Import error, path not found: '.str_replace('\\', '\\\\', $path).'");';	
		}
		
		return $content;
    }
	
	/**
	 * @see Exp::parse
	 */
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