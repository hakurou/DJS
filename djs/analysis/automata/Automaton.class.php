<?php

namespace djs\analysis\automata;

/**
 * Automate DFA pour la partie lexicale
 * @author hakurou
 * @version 1.0.0
 */
class Automaton
{
	/**
	 * Constantes de type de retour de test de transition
	 */
    const R_ERROR = 1;
    const R_SUCCESS = 2;
    const R_SUCCESS_BACK = 4;
    const R_SUCCESS_IGNORE = 5;
    const R_IN_PROCESSING = 3;
    
    protected $startState;
    protected $currentState;
    protected $buffer;
    protected $tokenType;
    
	/**
	 * Constructeur
	 */
    public function __construct()
    {
        $this->buffer = "";
        $this->startState = new State();
    }
    
	/**
	 * Définit l'étape de démarrage
	 * @param Stage $state					Etape de demarrage
	 */
    public function setStartState($state)
    {
        $this->startState = $state;
    }
    
	/**
	 * Teste chaque caractere afin de voir si il correspond
	 * à l'une des étapes de l'automate
	 * @param Char $letter					Lettre a tester
	 * @return Int Const					Retourne une valeur correspondant a un type de resultat
	 */
    public function test($letter)
    {
        if($this->currentState == null)
            $this->currentState = $this->startState;
        
        $nextState = $this->currentState->getTransition($letter);

        if($nextState != null)
        {
            if($nextState->isFinal())
            {
                $this->tokenType = $nextState->getTokenType();
                $this->currentState = $this->startState;
                
                if(!$nextState->isBack())
                {
                    if($nextState->getCapture())
                        $this->buffer .= $letter;
                    
                    if($this->tokenType != null)             
                        return self::R_SUCCESS;
                    else
                        return self::R_SUCCESS_IGNORE;
                }
                else
                {
                    return self::R_SUCCESS_BACK;
                }
            }
            else 
            {
                $this->currentState = $nextState;
                
                if($nextState->getCapture())
                    $this->buffer .= $letter;
                
                return self::R_IN_PROCESSING;
            }
        }
        else
        {
            return self::R_ERROR;
        }
    }
    
	/**
	 * Retourne des informations sur le token trouvé
	 * @return Array							Infos trouvées
	 */
    public function getToken()
    {
        $t = array(
            'word'      => $this->buffer,
            'tokenType' => $this->tokenType
        );
        
        $this->clearBuffer();
        
        return $t;
    }
    
	/**
	 * Vide le tampon
	 */
    public function clearBuffer()
    {
        $this->buffer = "";
    }
}

?>