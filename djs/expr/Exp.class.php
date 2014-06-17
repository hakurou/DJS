<?php

namespace djs\expr;

/**
 * Représente une expression de base
 * @author hakurou
 * @version 1.0.0
 */
abstract class Exp
{
	public $type;
	protected $parentNamespace;
	
	/**
	 * Constructeur
	 * @param Const Int $type				Type d'expression
	 */
    public function __construct($type)
    {
        $this->type = $type;
    }
    
	/**
	 * Retourne le type d'expression
	 * @return Const Int 					Type d'expression
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * Définit le namespace pour l'expression courante
	 * @param String $parentNamespace		Nom du namespace
	 */
	public function setNamespace($parentNamespace)
	{
		$this->parentNamespace = $parentNamespace;
	}
	
	/**
	 * Demande l'interprétation de l'expression
	 * @param Parser $parser				Instance du parseur syntaxique
	 * @return Int Const/null				Retourne le type de retour ou null si pas de resultat
	 */
    abstract public function interpret($parser);
	
	/**
	 * Demande le parsing syntaxique d'un type particulier
	 * @param Parser $parser				Instance du parseur syntaxique
	 * @return Exp							Retourne l'expression correspondante
	 */
	abstract public function parse($parser);
}

?>