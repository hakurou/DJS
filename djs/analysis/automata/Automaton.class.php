<?php

namespace djs\analysis\automata;

class Automaton
{
    const R_ERROR = 1;
    const R_SUCCESS = 2;
    const R_SUCCESS_BACK = 4;
    const R_SUCCESS_IGNORE = 5;
    const R_IN_PROCESSING = 3;
    
    protected $startState;
    protected $currentState;
    protected $buffer;
    protected $tokenType;
    
    public function __construct()
    {
        $this->buffer = "";
        $this->startState = new State();
    }
    
    public function setStartState($state)
    {
        $this->startState = $state;
    }
    
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
    
    public function getToken()
    {
        $t = array(
            'word'      => $this->buffer,
            'tokenType' => $this->tokenType
        );
        
        $this->clearBuffer();
        
        return $t;
    }
    
    public function clearBuffer()
    {
        $this->buffer = "";
    }
}

?>