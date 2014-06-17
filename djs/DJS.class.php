<?php

namespace djs;

/**
 * Amorce du parseur
 * @author hakurou
 * @version 1.0.0 
 * @todo Amméliorer la cohérence de l'ensemble, mieux gérer la partie "class" et ensuite on pourra
 * @todo - décliner le parseur avec un EXE
 * @todo Empecher les inclusions cycliques
 */
class DJS
{
	/**
	 * Liste des mots clés
	 * @var Array
	 */
	protected $reservedWords;
	
	/**
	 * Dit si on doit inclure le code d'héritage dans le script
	 * Si une source utilise les classes, alors cet attribut sera à true
	 * @var Boolean
	 */
	public static $includeInheritCode;
	
	/**
	 * Dit si le processus courant est un enfant
	 * @var Boolean
	 */
	protected $childProcess;
	
	/**
	 * Constructeur
	 * @param Boolean $childProcess				A true si l'instance est est processus secondaire, sinon false
	 */
    public function __construct($childProcess = false)
    {
        $this->reservedWord();
		$this->childProcess = $childProcess;
		self::$includeInheritCode = false;
		
		if(!$this->childProcess)
			expr\Import::clearIncludedFiles();
    }
    
	/**
	 * Demande à parser une fichier
	 * @param String $filename					Fichier à parser
	 * @param String/null $fileout				Nom du fichier de sortie, si sortie directe alors null
	 * @return String/null						Si $fileout = null, alors retourne le script parsé, sinon null
	 */
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
	
	/**
	 * Méthode d'affichage pour le debug
	 * @param Mixed $out						Valeur à afficher
	 */
	public static function out($out)
	{
		echo '<pre>'.print_r($out, true).'</pre>';
	}
    
	/**
	 * Recherche un mot clé précis
	 * @param String $word						Nom du mot clé
	 * @return Exp								Retourne une expression de mot clé si trouvé, sinon null
	 */
	public function searchWord($word)
	{
		if(isset($this->reservedWords[$word]))
			return $this->reservedWords[$word];
		else
			return null;
	}
	
	/**
	 * Prépare la liste des mots clés
	 */
    protected function reservedWord()
    {
    	$this->reservedWords = array();
        $this->reservedWords['import'] 		= new expr\Import();
        $this->reservedWords['class'] 		= new expr\DjsClass();
        $this->reservedWords['namespace'] 	= new expr\DjsNamespace();
    }
}

?>