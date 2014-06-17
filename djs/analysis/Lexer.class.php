<?php

namespace djs\analysis;

/**
 * Analyseur lexical
 * @author hakurou
 * @version 1.0.0
 */
class Lexer
{
	// Constantes du type de source a traiter
    const T_FILE = 1;
    const T_STRING = 2;
    
	// Constantes de type token
    const TT_IMPORT = 999;
    const TT_STRING = 998;
    const TT_WORD = 997;
	const TT_SEMICOLON = 996;
	const TT_NATIVE = 995;
	const TT_CLASS = 994;
	const TT_SUPER = 993;
	const TT_LEFT_BRACE = 992;
	const TT_RIGHT_BRACE = 991;
	const TT_SCOPE = 990;
	const TT_METHOD_CONTENT = 989;
	const TT_WORD_SUPER = 988;
	const TT_NAMESPACE = 987;
	const TT_WORD_FUNCTION = 986;
	const TT_WORD_VAR = 985;
    
    protected $source;
    protected $states;
    protected $automaton;
    protected $cursor;
    
	/**
	 * Constructeur
	 * @param String $str					Soit la source, soit le chemin de la source
	 * @param Int Const $contentType		Type de source a parser
	 */
    public function __construct($str, $contentType)
    {
        $this->cursor = 0;
		
        if($contentType == self::T_FILE)
            $this->source = file_get_contents($str);
        else if($contentType == self::T_STRING)
            $this->source = $str;
        
        $this->automaton = new automata\DjsAutomaton();
    }
    
	/**
	 * Récupère le prochain token
	 * @return Token						Retourne un token quand il est trouvé, sinon null
	 */
    public function getNextToken()
    {
        $tokens = null;
        while(isset($this->source[$this->cursor]))
        {
            $result = $this->automaton->test($this->source[$this->cursor]);
         	
            if($result == automata\DjsAutomaton::R_SUCCESS || 
                $result == automata\DjsAutomaton::R_SUCCESS_BACK ||
                $result == automata\DjsAutomaton::R_SUCCESS_IGNORE)
            {
                $t = $this->automaton->getToken();
                if($t['tokenType'] != null)
                {
                    $token = new tokens\Token();
                    $token->setValue($t['word']);
                    $token->setType($t['tokenType']);
                    $tokens = $token;
                }
            }
            else if($result == automata\DjsAutomaton::R_ERROR)
            {
                trigger_error('Lexical error');
                break;
            }
            
            if($result != automata\DjsAutomaton::R_SUCCESS_BACK)
                $this->cursor++;
            
            if($result == automata\DjsAutomaton::R_SUCCESS || 
                $result == automata\DjsAutomaton::R_SUCCESS_BACK)
                break;
        }

        return $tokens;
    }
}

?>