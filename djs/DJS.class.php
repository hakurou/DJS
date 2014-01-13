<?php

namespace djs;

/**
 * Amméliorer la cohérence de l'ensemble, mieux gérer la partie "class" et ensuite on pourra
 * décliner le parseur avec un EXE
 * 
 * @todo Empecher les inclusions cycliques
 */
class DJS
{
	protected $reservedWords;
	public static $includeInheritCode;
	protected $childProcess;
	
    public function __construct($childProcess = false)
    {
        $this->reservedWord();
		$this->childProcess = $childProcess;
		self::$includeInheritCode = false;
    }
    
    public function parseFile($filename, $fileout = null)
    {
        $parser = new analysis\Parser($this);
		$r = $parser->parseFile($filename);
		
		if(self::$includeInheritCode && !$this->childProcess)
			$r = expr\DjsClass::getInheritSrc().$r;	
		
		if($fileout != null)
        	file_put_contents($fileout, $r);
		else
			return $r;
    }
	
	public static function out($out)
	{
		echo '<pre>'.print_r($out, true).'</pre>';
	}
    
	public function searchWord($word)
	{
		if(isset($this->reservedWords[$word]))
			return $this->reservedWords[$word];
		else
			return null;
	}
	
    protected function reservedWord()
    {
    	$this->reservedWords = array();
        $this->reservedWords['import'] 		= new expr\Import();
        $this->reservedWords['class'] 		= new expr\DjsClass();
        $this->reservedWords['namespace'] 	= new expr\DjsNamespace();
    }
}

?>