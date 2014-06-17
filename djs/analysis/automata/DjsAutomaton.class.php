<?php

namespace djs\analysis\automata;

/**
 * Automate propre au langage DJS
 * @author hakurou
 * @version 1.0.0
 */
class DjsAutomaton extends Automaton
{
	/**
	 * Constructeur
	 */
    public function __construct()
    {
        parent::__construct();
        
        $this->initAutomaton();
    }
    
	/**
	 * Initialise l'automate en remplissant les étapes pour la capture lexicale
	 */
    protected function initAutomaton()
    {
        $this->prepareString();
        $this->prepareWord();
		$this->prepareSemiColon();
        $this->prepareBlank();
        $this->prepareComment();
		$this->prepareBraces();
        $this->prepareNative();
    }
	
	/**
	 * Ajoute les étapes pour traiter les accolades
	 */
	protected function prepareBraces()
	{
		$s1 = new State();
		$s2 = new State();
		
		$this->startState->addTransition(array('{'), $s1);
		$this->startState->addTransition(array('}'), $s2);
		
		$s1->setFinal();
		$s1->setTokenType(\djs\analysis\Lexer::TT_LEFT_BRACE);
		
		$s2->setFinal();
		$s2->setTokenType(\djs\analysis\Lexer::TT_RIGHT_BRACE);
	}
	
	/**
	 * Ajoute les étapes pour traiter les caractères ne correspondants pas
	 * à DJS, on traite ces elements comme provenant du code natif javascript
	 */
	protected function prepareNative()
	{
		$s1 = new State();		
        $this->startState->addTransition(array(), $s1);
		$s1->setFinal();
		$s1->setTokenType(\djs\analysis\Lexer::TT_NATIVE);
	}
	
	/**
	 * Ajoute les étapes pour traiter les points virgules
	 */
	protected function prepareSemiColon()
	{
		$s1 = new State();
		
		$this->startState->addTransition(array(';'), $s1);
		
		$s1->setFinal();
		$s1->setTokenType(\djs\analysis\Lexer::TT_SEMICOLON);
	}
    
	/**
	 * Ajoute les étapes pour traiter les commentaires
	 */
    protected function prepareComment()
    {
        $s1 = new State();
        $s2 = new State();
        $s3 = new State();
        $s4 = new State();
        $s5 = new State();
        $s6 = new State();
		
		$this->startState->addTransition(array('/'), $s1);
		
		$s1->addTransition(array('/'), $s2);
		$s1->addTransition(array('*'), $s3);
		$s1->addTransition(null, $s4);
		
		$s2->addTransition(null, $s2);
		$s2->addTransition(array("\n"), $s5);
		
		$s3->addTransition(null, $s6);
		$s3->addTransition(array('/'), $s5);
		
		$s6->addTransition(null, $s6);
		$s6->addTransition(array('*'), $s3);
		
		$s4->setFinal();
		$s4->setTokenType(\djs\analysis\Lexer::TT_NATIVE);
		
		$s5->setFinal();
    }

	/**
	 * Ajoute les étapes pour traiter les chaines de caracteres
	 */
    protected function prepareString()
    {
        $s3 = new State();
        $s4 = new State();
        $s12 = new State();
        $s5 = new State();
		
        $this->startState->addTransition(array('"'), $s3);
        
        $s3->setCapture(false);
        $s3->addTransition(null, $s12);
        $s3->addTransition(array('"'), $s4);
        $s3->addTransition(array('\\'), $s5);
		
        $s12->addTransition(null, $s12);
        $s12->addTransition(array('"'), $s4);
        $s12->addTransition(array('\\'), $s5);
		
		$s5->addTransition(null, $s12);
		
        $s4->setCapture(false);
        $s4->setFinal();
        $s4->setTokenType(\djs\analysis\Lexer::TT_STRING);
    }
    
	/**
	 * Ajoute les étapes pour traiter les mots (variables, fonctions, etc...)
	 */
    protected function prepareWord()
    {
        $s1 = new State();
        $s2 = new State();
        $s3 = new State();
        
		$alpha = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
			'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
			's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
			'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
			'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'_'
		);
		
		$alphaNum = array_merge($alpha, array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		));
		
		$stopper = null;
		
        $this->startState->addTransition($alpha, $s1);
        $s1->addTransition($alphaNum, $s3);
        $s1->addTransition(null, $s2);
        
		$s3->addTransition($alphaNum, $s3);
        $s3->addTransition($stopper, $s2);
		
        $s2->setFinal();
        $s2->setBack();
        $s2->setTokenType(\djs\analysis\Lexer::TT_WORD);
    }
    
	/**
	 * Ajoute les étapes pour traiter les espaces blancs
	 */
    protected function prepareBlank()
    {
        $s9 = new State();
        
        $this->startState->addTransition(array("\r", "\n", ' ', "\t"), $s9);
        
        $s9->setFinal();
        $s9->setCapture(false);
        
    }
}

?>