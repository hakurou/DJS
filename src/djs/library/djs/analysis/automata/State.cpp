#include "State.h"

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace automata
			{

				State::State(): stateBack(false), capture(true), finalState(false), transitionAllChar(nullptr)
				{

				}

				State::~State()
				{

				}

				void State::addTransition(char letters[], State nextState)
				{
					for(unsigned int i = 0, im = sizeof(letters); i < im; ++i)
					{
						this->transitions[letters[i]] = nextState;
					}
				}

				State* State::getTransition(char letter) const
				{
					return nullptr;
				}

				void State::addTransition(State nextState)
				{
					this->transitionAllChar = &nextState;
				}

				bool State::isFinal() const
				{
					return this->finalState;
				}

				bool State::isBack() const
				{
					return this->stateBack;
				}

				bool State::getCapture() const
				{
					return this->capture;
				}

				void State::setBack(bool b)
				{
					this->stateBack = b;
				}

				void State::setTokenType(library::djs::analysis::tokens::tokenType tt)
				{
					this->tokenType = tt;;
				}

				void State::setFinal(bool f)
				{
					this->finalState = f;
				}

				void State::setCapture(bool c)
				{
					this->capture = c;
				}

				library::djs::analysis::tokens::tokenType State::getTokenType() const
				{
					return this->tokenType;
				}
			}
		}
	}
}

