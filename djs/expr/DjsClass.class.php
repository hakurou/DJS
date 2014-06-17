<?php

namespace djs\expr;

/**
 * Expression représentant une classe
 * @author hakurou
 * @version 1.0.0
 * @todo Exploser cette classe pour proposer un type "methode" et "attribut" pour faire plus propre
 */
class DjsClass extends Exp
{
	/**
	 * Constantes d'encapsulation
	 */
	const T_PUBLIC = 999;
	const T_STATIC = 998;
	
    protected $className;
    protected $methods;
    protected $attrs;
	protected $extends;
    
	/**
	 * Constructeur
	 * @param String $className 					Nom de la classe
	 * @param Array $methods						Liste des méthodes par encapsulation
	 * @param Array $attrs							Liste des attributes de classe
	 * @param String $extends						Nom de la classe parente si elle existe
	 */
    public function __construct($className = null, $methods = null, $attrs = null, $extends = null)
    {
    	parent::__construct(\djs\analysis\Lexer::TT_CLASS);
        $this->className = $className;
        $this->methods = $methods;
        $this->attrs = $attrs;
        $this->extends = $extends;
    }
    
	/**
	 * @see Exp::interpret
	 */
    public function interpret($parser)
    {
    	$attrs = $this->getAttr(self::T_PUBLIC, $parser);
    	$staticAttrs = $this->getAttr(self::T_STATIC, $parser);
		$methods = $this->getMethods(self::T_PUBLIC, $parser);
		$staticMethods = $this->getMethods(self::T_STATIC, $parser);
		$construct = $this->getCtorContent($parser);
        $ctorArgs = array();
        if(isset($this->methods['public']['construct']))
            $ctorArgs = $this->getArgsList($this->methods['public']['construct']);
        
		$this->prepareInheritSrc();
		$super = $this->getSuperSrc();
		$parentCtor = '';
		if((!isset($this->methods['public']) || !isset($this->methods['public']['construct'])) &&
			$this->extends  != '')
		{
			$parentCtor = '
				return '.$this->className.'.__super__.constructor.apply(this, arguments);
			';
		}
		
		if($this->parentNamespace != null)
			$declaration = $this->parentNamespace.'.';
		else
			$declaration = 'var ';
	
    	$content = '
    		'.$declaration.$this->className.' = (function(){
    			'.$super.'
    			function '.$this->className.'('.implode(', ', $ctorArgs).')
				{
					'.$attrs.'
					'.$construct.'
					'.$parentCtor.'
				}
				
				'.$methods.'
				'.$staticAttrs.'	
				'.$staticMethods.'
				return '.$this->className.';
    		})();
    	';
		
		return $content;
    }
	
	/**
	 * Retourne un fragment de JS pour l'héritage
	 * @return String						Code de l'héritage
	 */
	protected function getSuperSrc()
	{
		if($this->extends  != '')
		{
			return '
				__extends('.$this->className.', '.$this->extends.');
			';
		}
		
		return '';
	}
	
	/**
	 * Dit au launcher d'inclure le code de création de classe en JS
	 */
	protected function prepareInheritSrc()
	{
		\djs\DJS::$includeInheritCode = true;
	}

	/**
	 * Retourne le code de création de classe
	 * @return String						Code de création de classe
	 */
	public static function getInheritSrc()
	{
		return '
			/* Ref d\'origine pour l\'heritage: https://github.com/jashkenas/coffee-script */
			var __extends = function(child, parent) {
				for(var i in parent) 
				{
					if (!child.hasOwnProperty(i))
						child[i] = parent[i]; 
				} 
				
				function ctor() 
				{
					this.constructor = child; 
				} 
				
				ctor.prototype = parent.prototype; 
				child.prototype = new ctor(); 
				child.__super__ = parent.prototype; 
			};
		';
	}
	
	/**
	 * @param $parser
	 * @return String
	 */
	protected function getCtorContent($parser)
	{
		if(isset($this->methods['public']['construct']))
		{
			return $this->getContentList($this->methods['public']['construct'], $parser);
		}
		else
			return '';
	}
	
	/**
	 * @param $type
	 * @param $parser
	 * @return String
	 */
	protected function getMethods($type, $parser)
	{
		$t = ($type == self::T_PUBLIC) ? 'public': 'static';
		$methods = '';
		if(isset($this->methods[$t]) && count($this->methods[$t]) > 0)
		{
			foreach($this->methods[$t] as $methName => $methValues)
			{
				if($methName == 'construct')
					continue;
				
				$args = $this->getArgsList($methValues);
				$methodContent = $this->getContentList($methValues, $parser);
				$attach = ($type == self::T_PUBLIC) ? '.prototype': '';
				$methods .= $this->className.$attach.'.'.$methName.' = function('.implode(', ', $args).'){
					'.$methodContent.'
				};';
			}
		}
		
		return $methods;
	}

	/**
	 * @param $type
	 * @param $parser
	 * @return String
	 */
	protected function getAttr($type, $parser)
	{
		$t = ($type == self::T_PUBLIC) ? 'public': 'static';
		$attrs = '';
		if(isset($this->attrs[$t]) && count($this->attrs[$t]) > 0)
		{
			foreach($this->attrs[$t] as $attrName => $attrValues)
			{
				if($type == self::T_PUBLIC)
					$attrs .= 'this.'.$attrName.' = ';
				else
					$attrs .= $this->className.'.'.$attrName.' = ';
						
				foreach($attrValues as $attrValue)
					$attrs .=  $attrValue->interpret($parser);
				
				$attrs .= ';';
			}
		}
		
		return $attrs;
	}

	/**
	 * @param $methValues
	 * @param $parser
	 * @return String
	 */
	protected function getContentList($methValues, $parser)
	{
		$methodContent = '';
		if(count($methValues['content']) > 0)
		{
			foreach($methValues['content'] as $arg)
				$methodContent .= $arg->interpret($parser);
		}
		
		return $methodContent;
	}
	
	/**
	 * Récupère les arguments de la méthode souhaitée
	 * @param $methValues
	 * return Array
	 */
	protected function getArgsList($methValues)
	{
		$args = array();
		if(isset($methValues['args']) && count($methValues['args']) > 0)
		{
			foreach($methValues['args'] as $arg)
				$args[] = $arg->getValue();
		}
		
		return $args;
	}
	
	/**
	 * @see Exp::parse
	 */
	public function parse($parser)
	{
		$attrs = array();
		$extends = '';
		$methods = array();
		$parser->nextToken();
		$className = $parser->getCurrentToken()->getValue();
		$parser->nextToken();
		
		if($parser->getCurrentToken()->getValue() != '{')
		{
			if($parser->getCurrentToken()->getValue() == 'extends')
			{
				$parser->nextToken();
				$t = $parser->getCurrentToken();
				while($t != null && $t->getValue() != '{')
				{
					if($t->getType() != \djs\analysis\Lexer::TT_WORD)
						trigger_error('DjsClass::parse: Unexpected token "'.$t->getValue().'"', E_USER_ERROR);
					
					$extends .= $t->getValue();
					
					$parser->nextToken();
					$t = $parser->getCurrentToken();
					if($t->getValue() == '.')
					{
						$extends .= $t->getValue();
					
						$parser->nextToken();
						$t = $parser->getCurrentToken();
					
						if($t->getType() != \djs\analysis\Lexer::TT_WORD)
							trigger_error('DjsClass::parse: Unexpected token', E_USER_ERROR);
					}
				}

			}
		}	
			
		$parser->nextToken();
		$t = $parser->getCurrentToken();
		while($t != null && $t->getValue() != '}')
		{
			$this->parseClassContent($parser, $attrs, $methods, $className);

			$parser->nextToken();
			$t = $parser->getCurrentToken();
		}

		return new self($className, $methods, $attrs, $extends);
	}
	
	/**
	 * Parse le contenu de la classe dans son ensemble
	 * @param Parser $parser					Instance du parser
	 * @param &Array $attrs						Référence vers la liste des attributs
	 * @param $Array $methods					Référence vers la liste des méthodes
	 * @param String $className					Nom de la classe
	 */
	protected function parseClassContent($parser, &$attrs, &$methods, $className)
	{
		$static = false;
		if($parser->getCurrentToken()->getValue() == 'static')
		{
			$static = true;
			$parser->nextToken();
		}
		
		$name = $parser->getCurrentToken();
		$parser->nextToken();
		
		if($parser->getCurrentToken()->getValue() == '=')
			$this->parseClassAttr($name, $parser, $attrs, $methods, $static);
		else if($parser->getCurrentToken()->getValue() == '(')
			$this->parseClassMethod($name, $parser, $attrs, $methods, $static, $className);
		else
			trigger_error('DjsClass::parseClassContent: Unexpected token "'.$parser->getCurrentToken()->getValue().'", attribute or method waited', E_USER_ERROR);
	}
	
	/**
	 * Parse une méthode de classe
	 * @param String $name						Nom de la méthode
	 * @param Parser $parser					Instance du parser
	 * @param &Array $attrs						Référence vers la liste des attributs
	 * @param $Array $methods					Référence vers la liste des méthodes
	 * @param Boolean $static					Dit si la méthode est statique ou non
	 * @param String $className					Nom de la classe
	 */
	protected function parseClassMethod($name, $parser, &$attrs, &$methods, $static, $className)
	{
		$parser->nextToken();
		$t = $parser->getCurrentToken();
		while($t != null && $t->getValue() != ')')
		{
			$methods[($static) ? 'static': 'public'][$name->getValue()]['args'][] = $t;
			
			$parser->nextToken();
			$t = $parser->getCurrentToken();
			
			if($t->getValue() == ',')
			{
				$parser->nextToken();
				$t = $parser->getCurrentToken();
				if($t->getValue() == ')')
					trigger_error('DjsClass::parseClassMethod: Unexpected token, argument waited', E_USER_ERROR);
			}
		}
		
		$parser->nextToken();
		if($parser->getCurrentToken()->getType() != \djs\analysis\Lexer::TT_LEFT_BRACE)
			trigger_error('DjsClass::parseClassMethod: Unexpected token, argument waited', E_USER_ERROR);
		
		$m = new DjsClassMethodContent($className);
		$e = $m->parse($parser);

		if($e == null)
			trigger_error('DjsClass::parseClassMethod: Unexpected token', E_USER_ERROR);
		
		$methods[($static) ? 'static': 'public'][$name->getValue()]['content'][] = $e;
	}

	/**
	 * Parse un attribut de classe
	 * @param String $name						Nom de l'attribut
	 * @param Parser $parser					Instance du parser
	 * @param &Array $attrs						Référence vers la liste des attributs
	 * @param $Array $methods					Référence vers la liste des méthodes
	 * @param Boolean $static					Dit si l'attribut est statique ou non
	 */
	protected function parseClassAttr($name, $parser, &$attrs, &$methods, $static)
	{
		$parser->nextToken();
		$t = $parser->getCurrentToken();
		$openBrace = 0;
		// Bloque les ; quand ils sont dans des {} en cas de fonction ou autre
		// Refaire cette zone en plus propre
		while($t != null && !($t->getType() == \djs\analysis\Lexer::TT_SEMICOLON && $openBrace == 0))
		{
			if($t->getValue() == '{')
				$openBrace++;
			else if($t->getValue() == '}')
				$openBrace--;
			
			if($t->getType() == \djs\analysis\Lexer::TT_STRING)
				$o = new \djs\expr\DjsString($t);
			else
				$o = new \djs\expr\Native($t);
				 
			$attrs[($static) ? 'static': 'public'][$name->getValue()][] = $o;
			$parser->nextToken();
			$t = $parser->getCurrentToken();
		}

		if($parser->getCurrentToken()->getType() != \djs\analysis\Lexer::TT_SEMICOLON)
			trigger_error('DjsClass::parseClassContent: Unexpected token', E_USER_ERROR);
	}
}

?>