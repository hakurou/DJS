#ifndef LIBRARY_DJS_ANALYSIS_AUTOMATA_STATE_H
#define LIBRARY_DJS_ANALYSIS_AUTOMATA_STATE_H

#include <iostream>
#include <map>
#include "../tokens/tokenType.h"

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace automata
			{
				class State
				{
					public:
						State();
						virtual ~State();
						void addTransition(char letters[], State nextState);
						void addTransition(State nextState);
						State* getTransition(char letter) const;
						bool isFinal() const;
						bool isBack() const;
						bool getCapture() const;
						void setBack(bool b = true);
						void setTokenType(library::djs::analysis::tokens::tokenType tt);
						void setFinal(bool f = true);
						void setCapture(bool c = true);
						library::djs::analysis::tokens::tokenType getTokenType() const;

					protected:
						std::map<char, State> transitions;
						library::djs::analysis::tokens::tokenType tokenType;
						bool stateBack;
						bool capture;
						bool finalState;
						State* transitionAllChar;
				};
			}
		}
	}
}

#endif // LIBRARY_DJS_ANALYSIS_AUTOMATA_STATE_H
