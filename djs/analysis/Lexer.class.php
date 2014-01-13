<?php

namespace djs\analysis;

class Lexer
{
    const T_FILE = 1;
    const T_STRING = 2;
    
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
    
    public function __construct($str, $contentType)
    {
        $this->cursor = 0;
		
        if($contentType == self::T_FILE)
            $this->source = file_get_contents($str);
        else if($contentType == self::T_STRING)
            $this->source = $str;
        
        $this->automaton = new automata\DjsAutomaton();
    }
    
    public function getNextToken()
    {
    	$line = 1;
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