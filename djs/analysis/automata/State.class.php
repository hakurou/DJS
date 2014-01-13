<?php

namespace djs\analysis\automata;

class State
{
    protected $finalState;
    protected $transitions;
    protected $tokenType;
    protected $back;
    protected $transitionAllChar;
    protected $capture;
    
    public function __construct()
    {
        $this->capture = true;
        $this->finalState = false;
        $this->transitions = array();
        $this->back = false;
    }
    
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
    
    public function getTransition($letter)
    {
        if(isset($this->transitions[$letter]))
            return $this->transitions[$letter];
        else if($this->transitionAllChar != null)
            return $this->transitionAllChar;
        else
            return null;
    }
    
    public function isFinal()
    {
        return $this->finalState;
    }
    
    public function isBack()
    {
        return $this->back;
    }
    
    public function setBack($value = true)
    {
        $this->back = $value;
    }
    
    public function getTokenType()
    {
        return $this->tokenType;
    }
    
    public function setTokenType($type)
    {
        $this->tokenType = $type;
    }
    
    public function setFinal($value = true)
    {
        $this->finalState = $value;
    }
    
    public function setCapture($capture)
    {
        $this->capture = $capture;
    }
    
    public function getCapture()
    {
        return $this->capture;
    }
}

?>