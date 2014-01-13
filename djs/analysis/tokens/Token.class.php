<?php

namespace djs\analysis\tokens;

class Token
{
    protected $type;
    protected $value;
    protected $line;
    
    public function setType($value)
    {
        $this->type = $value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function setLine($value)
    {
        $this->line = $value;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getLine()
    {
        return $this->line;
    }
}

?>