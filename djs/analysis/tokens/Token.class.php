<?php

namespace djs\analysis\tokens;
/**
 * Représente un lexeme
 * @author hakurou
 * @version 1.0.0
 */
class Token
{
    protected $type;
    protected $value;
    protected $line;
    
	/**
	 * Définit le type du token
	 * @param Int Const $value				Type de token
	 */
    public function setType($value)
    {
        $this->type = $value;
    }
    
	/**
	 * Définit la valeur du token
	 * @param String $value					Valeur du token
	 */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
	/**
	 * Définit le numéro de la ligne correspondante au token
	 * @param Int value						Ligne
	 */
    public function setLine($value)
    {
        $this->line = $value;
    }
    
	/**
	 * Retourne le type de token
	 * @return Int Const					Type de token
	 */
    public function getType()
    {
        return $this->type;
    }
    
	/**
	 * Retourne la valeur du token
	 * @return String						Valeur du token
	 */
    public function getValue()
    {
        return $this->value;
    }
    
	/**
	 * Retourne la ligne de provenance du token
	 * @return Int 							Numéro de la ligne d'origine
	 */
    public function getLine()
    {
        return $this->line;
    }
}

?>