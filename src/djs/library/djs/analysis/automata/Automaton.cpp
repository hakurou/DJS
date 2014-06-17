#include "Automaton.h"

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace automata
			{
				Automaton::Automaton(): buffer("")
				{

				}

				Automaton::~Automaton()
				{

				}

				Automaton::status Automaton::test(char letter)
				{
					if(this->currentState == NULL)
						this->currentState = &this->startState;

					State* s = this->currentState->getTransition(letter);

					if(s != NULL)
					{
						if(s->isFinal())
						{
							this->tokenType = s->getTokenType();
							this->currentState = &this->startState;

							if(s->isBack())
							{
								if(s->getCapture())
									this->buffer += letter;

								if(s->getTokenType() != library::djs::analysis::tokens::tokenType::TT_NONE)
									return Automaton::status::S_SUCCESS;
								else
									return Automaton::status::S_SUCCESS_IGNORE;
							}
							else
								return Automaton::status::S_SUCCESS_BACK;
						}
						else
						{
							this->currentState = s;
							if(s->getCapture())
								this->buffer += letter;

							return Automaton::status::S_IN_PROCESSING;
						}
					}

					return Automaton::status::S_ERROR;
				}

				void Automaton::getToken() const
				{

				}

				void Automaton::cleanBuffer()
				{
					this->buffer.clear();
				}
			}
		}
	}
}
