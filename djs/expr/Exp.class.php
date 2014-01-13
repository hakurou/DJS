<?php

namespace djs\expr;

abstract class Exp
{
	public $type;
	protected $parentNamespace;
	
    public function __construct($type)
    {
        $this->type = $type;
    }
    
	public function getType()
	{
		return $this->type;
	}
	
	public function setNamespace($parentNamespace)
	{
		$this->parentNamespace = $parentNamespace;
	}
	
    abstract public function interpret();
	
	abstract public function parse($parser);
}

?>