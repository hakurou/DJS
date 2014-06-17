<?php

namespace djs\analysis\automata;

/**
 * Représente un état/étape de l'automate
 * @author hakurou
 * @version 1.0.0
 */
class State
{
    protected $finalState;
    protected $transitions;
    protected $tokenType;
    protected $back;
    protected $transitionAllChar;
    protected $capture;
    
	/**
	 * Constructeur
	 */
    public function __construct()
    {
        $this->capture = true;
        $this->finalState = false;
        $this->transitions = array();
        $this->back = false;
    }
    
	/**
	 * Ajoute une transition
	 * @param Array/null $letters			Liste de caracteres ou null si tous les caracteres possibles
	 * @param State $nextState				Etape suivante
	 */
    public function addTransition($letters, $nextState)
    {
        if($letters!= null && count($letters) > 0)
        {
            foreach($letters as $letter)
                $this->transitions[$letter] = $nextState;
        }
        else
            $this->transitionAllChar = $nextState;
    }
    
	/**
	 * Récupère une transition
	 * @param Char $letter					Caractère clé
	 * @return State/null					Retourne l'étape correspondante ou null si non trouvée
	 */
    public function getTransition($letter)
    {
        if(isset($this->transitions[$letter]))
            return $this->transitions[$letter];
        else if($this->transitionAllChar != null)
            return $this->transitionAllChar;
        else
            return null;
    }
    
	/**
	 * Dit si l'étape est finale
	 * @return Boolean						True si c'est final, sinon false
	 */
    public function isFinal()
    {
        return $this->finalState;
    }
    
	/**
	 * Dit si le caractere doit être retraité
	 * @return Boolean						True si oui, sinon false 
	 */
    public function isBack()
    {
        return $this->back;
    }
    
	/**
	 * Définit si le caractere doit être retraité
	 * @param Boolean $value				A true si ça doit être retraité, sinon false
	 */
    public function setBack($value = true)
    {
        $this->back = $value;
    }
    
	/**
	 * Retourne le type de token
	 * @return Int Const					Type de token
	 */
    public function getTokenType()
    {
        return $this->tokenType;
    }
    
	/**
	 * Définit le type de token
	 * @param Int Const $type				Type de token
	 */
    public function setTokenType($type)
    {
        $this->tokenType = $type;
    }
    
	/**
	 * Définit si c'est une étape finale
	 * @param Boolean $value				A true si oui, sinon false
	 */
    public function setFinal($value = true)
    {
        $this->finalState = $value;
    }
    
	/**
	 * Définit si l'étape capture le caractere
	 * @param Boolean $capture				A true si oui, sinon false
	 */
    public function setCapture($capture)
    {
        $this->capture = $capture;
    }
    
	/**
	 * Retourne si le caractere doit être capturé
	 * @return Boolean 						True si oui, sinon false
	 */
    public function getCapture()
    {
        return $this->capture;
    }
}

?>